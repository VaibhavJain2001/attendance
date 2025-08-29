<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;


class AttendanceViewController extends Controller
{
    // Show the attendance page
    public function index(Request $request)
    {
        $user = Auth::user();

        // If Admin
        if ($user->is_admin) {
            //dd(1);
            $query = AttendanceLog::with('user');
            //dd($query);
            if ($request->from_date) {
                $query->whereDate('work_date', '>=', Carbon::parse($request->from_date)->startOfDay());
            }
            if ($request->to_date) {
                $query->whereDate('work_date', '<=', Carbon::parse($request->to_date)->endOfDay());
            }

            if ($request->employee_id) {
                $query->where('user_id', $request->employee_id);
            }
            if ($request->missing == 1) {
                $query->whereNull('clock_out_at');
            }

            $logs = $query->latest('work_date')->paginate(10);
            $token = $user->createToken('web-ui')->plainTextToken;
            $employees = User::all();

            // ✅ CSV Export logic
            if ($request->has('download')) {
                $filename = "attendance_" . now()->format('Ymd_His') . ".csv";

                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $columns = ['Date', 'Employee', 'Clock In', 'Clock Out', 'IP Address', 'Device Info'];

                // If user selects with_photos=1 then add photos
                if ($request->with_photos) {
                    $columns[] = "Clock In Photo";
                    $columns[] = "Clock Out Photo";
                }

                $callback = function () use ($query, $columns, $request) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($query->get() as $log) {
                        $row = [
                            $log->work_date,
                            $log->user->name ?? '—',
                            $log->clock_in_at,
                            $log->clock_out_at,
                            $log->ip_address,
                            $log->device_info,
                        ];

                        if ($request->with_photos) {
                            $row[] = $log->clock_in_photo ? url('storage/' . $log->clock_in_photo) : '';
                            $row[] = $log->clock_out_photo ? url('storage/' . $log->clock_out_photo) : '';
                        }

                        fputcsv($file, $row);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }


            return view('attendance.admin_index', compact('user', 'logs', 'employees', 'token'));
        }

        // If Normal User
        $logs = AttendanceLog::where('user_id', $user->id)
            ->orderByDesc('work_date')
            ->limit(30)
            ->get();

        $token = $user->createToken('web-ui')->plainTextToken;

        return view('attendance.index', compact('user', 'logs', 'token'));
    }

    // public function employeeList()
    // {
    //     $employees = User::where('is_admin', 0)->paginate(10);
    //     return view('attendance.employee', compact('employees'));
    // }
    public function employeeList()
    {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        $employees = User::withCount([
            'attendanceLogs as present_count' => function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('work_date', [$monthStart, $monthEnd])
                  ->whereNotNull('clock_in_at');
            },
            'attendanceLogs as absent_count' => function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('work_date', [$monthStart, $monthEnd])
                  ->whereNull('clock_in_at'); // You may need another table/logic for Absent
            }
        ])->get();

        return view('attendance.employee', compact('employees'));
    }
    // public function employeeList()
    // {
    //     // Fetch users with attendance logs and count
    //     $employees = User::withCount('attendanceLogs') // attendance_count column
    //         ->with(['attendanceLogs' => function ($q) {
    //             $q->orderBy('work_date', 'desc');
    //         }])
    //         ->get();

    //     return view('attendance.employee', compact('employees'));
    // }

    // Handle Clock-in from UI
    public function clockIn(Request $request)
    {
        $user = Auth::user();

        $attendance = AttendanceLog::create([
            'user_id'       => $user->id,
            'work_date'     => Carbon::today()->toDateString(),
            'clock_in_at'   => Carbon::now(),
            'ip_address'    => $request->ip(),
            'device_info'   => $request->userAgent(),
        ]);

        return redirect()->route('attendance.index')->with('success', 'Clock In successful');
    }

    // Handle Clock-out from UI
    public function clockOut(Request $request)
    {
        $user = Auth::user();

        $attendance = AttendanceLog::where('user_id', $user->id)
            ->whereDate('work_date', Carbon::today())
            ->first();

        if ($attendance && !$attendance->clock_out_at) {
            $attendance->update([
                'clock_out_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('attendance.index')->with('success', 'Clock Out successful');
    }
}
