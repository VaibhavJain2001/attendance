@extends('layouts.user')

@section('title', 'Attendance Reports')

@section('content')
    <h2 class="fw-bold text-primary mb-4">📊 Attendance History</h2>

    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
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
                                        <span class="badge bg-success px-3 py-2">{{ \Carbon\Carbon::parse($log->clock_in_at)->format('h:i A') }}</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Not Marked</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->clock_out_at)
                                        <span class="badge bg-danger px-3 py-2">{{ \Carbon\Carbon::parse($log->clock_out_at)->format('h:i A') }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">⚠ Missing</span>
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? '—' }}</td>
                                <td class="text-truncate" style="max-width:200px;" title="{{ $log->device_info }}">{{ $log->device_info ?? '—' }}</td>
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
@endsection
