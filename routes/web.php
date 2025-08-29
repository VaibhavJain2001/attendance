<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceViewController;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/my-attendance', [AttendanceViewController::class, 'index'])->name('attendance.index');
    Route::get('/dashboard', [AttendanceViewController::class, 'index']);
    Route::post('/my-attendance/clock-in', [AttendanceViewController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/my-attendance/clock-out', [AttendanceViewController::class, 'clockOut'])->name('attendance.clockOut');
    Route::get('/my-attendance/nonce', [AttendanceController::class, 'nonce'])->name('attendance.nonce');
    Route::get('/admin/employees', [AttendanceViewController::class, 'employeeList'])->name('employees.list');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::get('/attendance/nonce', [AttendanceController::class, 'nonce'])->name('attendance.nonce');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockin');
    Route::get('/attendance/reports', [AttendanceController::class, 'reports'])->name('attendance.reports');
    Route::get('/user_dashboard', [DashboardController::class, 'index'])->name('user_dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/', function () {
    return view('welcome');
});


require __DIR__ . '/auth.php';
