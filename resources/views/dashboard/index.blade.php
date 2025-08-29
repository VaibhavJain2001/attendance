@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
    <h2 class="fw-bold text-primary mb-4">🏠 Dashboard</h2>

    <div class="row mb-4">
        <!-- Summary Cards -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Total Days</h5>
                    <h3 class="fw-bold">{{ $totalDays }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Clocked In</h5>
                    <h3 class="fw-bold text-success">{{ $totalClockedIn }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Clocked Out</h5>
                    <h3 class="fw-bold text-danger">{{ $totalClockedOut }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Missing Clock Out</h5>
                    <h3 class="fw-bold text-warning">{{ $missingClockOut }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Attendance Logs -->
    <h4 class="fw-bold mb-3">Recent Attendance</h4>
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>IP Address</th>
                            <th>Device Info</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->work_date)->format('d M Y') }}</td>
                                <td>
                                    @if ($log->clock_in_at)
                                        <span
                                            class="badge bg-success px-3 py-2">{{ \Carbon\Carbon::parse($log->clock_in_at)->format('h:i A') }}</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Not Marked</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->clock_out_at)
                                        <span
                                            class="badge bg-danger px-3 py-2">{{ \Carbon\Carbon::parse($log->clock_out_at)->format('h:i A') }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">⚠ Missing</span>
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? '—' }}</td>
                                <td class="text-truncate" style="max-width:200px;" title="{{ $log->device_info }}">
                                    {{ $log->device_info ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-4">🚫 No attendance records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('attendance.reports') }}" class="btn btn-primary">📊 View Full Reports</a>
        <a href="{{ route('attendance.index') }}" class="btn btn-success">✅ Mark Attendance</a>
    </div>
@endsection
