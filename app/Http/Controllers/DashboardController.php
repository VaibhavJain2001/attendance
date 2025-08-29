<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceLog;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Latest 5 attendance logs
        $logs = AttendanceLog::where('user_id', $user->id)
            ->orderByDesc('work_date')
            ->limit(5)
            ->get();

        // Attendance summary
        $totalDays = AttendanceLog::where('user_id', $user->id)->count();
        $totalClockedIn = AttendanceLog::where('user_id', $user->id)->whereNotNull('clock_in_at')->count();
        $totalClockedOut = AttendanceLog::where('user_id', $user->id)->whereNotNull('clock_out_at')->count();
        $missingClockOut = $totalClockedIn - $totalClockedOut;

        return view('dashboard.index', compact('user', 'logs', 'totalDays', 'totalClockedIn', 'totalClockedOut', 'missingClockOut'));
    }

    public function adminDashboard()
    {
        $today = Carbon::today();

        $totalEmployees = User::count();

        $presentToday = AttendanceLog::whereDate('work_date', $today)
                            ->whereNotNull('clock_in_photo') // or clock_in_at
                            ->distinct('user_id')
                            ->count('user_id');

        $absentToday = $totalEmployees - $presentToday;

        $missingClockOut = AttendanceLog::whereDate('work_date', $today)
                                ->whereNull('clock_out_photo') // or clock_out_at
                                ->count();

        return view('dashboard.admin_dashboard', compact(
            'totalEmployees',
            'presentToday',
            'absentToday',
            'missingClockOut'
        ));
    }
}
