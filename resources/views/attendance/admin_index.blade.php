@extends('layouts.admin')

@section('title', 'Attendance Logs')

@section('content')
    <h2 class="fw-bold text-primary mb-4">📋 Employee Attendance Logs</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('attendance.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label fw-semibold">From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Employee</label>
            <select name="employee_id" class="form-select">
                <option value="">-- All Employees --</option>
                @foreach ($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Missing Clock Out</label>
            <select name="missing" class="form-select">
                <option value="">-- All --</option>
                <option value="1" {{ request('missing') == 1 ? 'selected' : '' }}>Only Missing</option>
            </select>
        </div>
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary px-4">🔍 Filter</button>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary px-4">Reset</a>
            <!-- Export CSV without photos -->
            {{--  <a href="{{ route('attendance.index', array_merge(request()->all(), ['download' => 1])) }}"
            class="btn btn-success px-4">⬇ Export CSV</a> --}}

            <!-- Export CSV with photos -->
            <a href="{{ route('attendance.index', array_merge(request()->all(), ['download' => 1, 'with_photos' => 1])) }}"
                class="btn btn-info px-4">⬇ Export Report</a>
        </div>
    </form>

    <!-- Attendance Table -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>IP</th>
                            <th>Device Info</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($logs as $log)
                            <tr>
                                <td class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($log->work_date)->format('d M Y') }}
                                </td>
                                <td>{{ $log->user->name }}</td>
                                <td>
                                    @if ($log->clock_in_at)
                                        <span class="badge bg-success px-3 py-2">
                                            {{ \Carbon\Carbon::parse($log->clock_in_at)->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->clock_out_at)
                                        <span class="badge bg-danger px-3 py-2">
                                            {{ \Carbon\Carbon::parse($log->clock_out_at)->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">⚠ Missing</span>
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? '—' }}</td>
                                <td class="text-truncate" style="max-width:200px" title="{{ $log->device_info }}">
                                    {{ $log->device_info ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted py-4">🚫 No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
@endsection
