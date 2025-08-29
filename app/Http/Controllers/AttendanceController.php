<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\AttendanceNonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 1. Nonce API
    public function nonce()
    {
        $user = Auth::user();

        $nonce = AttendanceNonce::generateForUser($user->id);

        return response()->json([
            'token' => $nonce->token,
            'expires_at' => $nonce->expires_at,
        ]);
    }

    // 2. Clock In
    public function clockIn(Request $request)
    {
        return $this->handleAttendance($request, 'in');
    }

    // 3. Clock Out
    public function clockOut(Request $request)
    {
        return $this->handleAttendance($request, 'out');
    }

    // Helper function
    private function handleAttendance(Request $request, $type)
    {
        $user = Auth::user();

        // Validation
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,webp|max:3072',
            'nonce' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate nonce
        $nonce = AttendanceNonce::where('user_id', $user->id)
            ->where('token', $request->nonce)
            ->first();

        if (!$nonce || !$nonce->isValid()) {
            return response()->json(['error' => 'Invalid or expired nonce'], 422);
        }

        // Mark nonce as used
        $nonce->update(['used_at' => Carbon::now()]);

        $today = Carbon::now('Asia/Kolkata')->toDateString();

        // Store photo
        $path = $request->file('photo')->store('attendance', 'public');

        // Find or create attendance record
        $attendance = AttendanceLog::firstOrCreate(
            ['user_id' => $user->id, 'work_date' => $today]
        );

        if ($type === 'in') {
            if ($attendance->clock_in_at) {
                return response()->json(['error' => 'Already clocked in today'], 422);
            }
            $attendance->update([
                'clock_in_at' => Carbon::now(),
                'clock_in_photo' => $path,
                'ip_address' => $request->ip(),
                'device_info' => $request->header('User-Agent'),
                'location' => $request->input('location', null),
            ]);
        } else {
            if (!$attendance->clock_in_at) {
                return response()->json(['error' => 'Clock-in required before clock-out'], 422);
            }
            if ($attendance->clock_out_at) {
                return response()->json(['error' => 'Already clocked out today'], 422);
            }
            $attendance->update([
                'clock_out_at' => Carbon::now(),
                'clock_out_photo' => $path,
                'ip_address' => $request->ip(),
                'device_info' => $request->header('User-Agent'),
                'location' => $request->input('location', null),
            ]);
        }

        return response()->json([
            'message' => "Clock $type recorded successfully",
            'attendance' => $attendance,
        ]);
    }

    public function reports()
    {
        $user = Auth::user();

        // Latest 50 logs for the signed-in user
        $logs = AttendanceLog::where('user_id', $user->id)
            ->orderByDesc('work_date')
            ->limit(50)
            ->get();

        return view('attendance.attendance_reports', compact('user', 'logs'));
    }
}
