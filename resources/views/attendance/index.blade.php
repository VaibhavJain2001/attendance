@extends('layouts.user')

@section('title', 'Attendance System')

@section('content')
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">📋 Attendance System</h1>
        <p class="text-muted mb-0">Hello, <strong>{{ $user->name }}</strong>. Mark your presence below.</p>
    </div>

    <!-- Flash Message -->
    <div id="flash" class="alert fw-semibold text-center" style="display:none;"></div>

    <!-- Camera -->
    <div class="d-flex justify-content-center mb-5">
        <div class="card shadow-lg border-0" style="width: 700px;">
            <div class="card-header bg-dark text-white fw-bold text-center">
                Live Camera
            </div>
            <div class="card-body text-center">
                <video id="video" autoplay playsinline width="420" height="300"
                    class="rounded shadow border"></video>
                <canvas id="canvas" width="420" height="300" style="display:none;"></canvas>
                <div class="mt-4">
                    <button id="btnIn" class="btn btn-lg btn-success me-3 px-4">✅ Clock In</button>
                    <button id="btnOut" class="btn btn-lg btn-danger px-4">⏹️ Clock Out</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Logs -->
    <div class="card shadow-lg border-0 mt-5">
        <div class="card-header bg-light text-black fw-bold">Recent Attendance Logs</div>
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
                                <td class="fw-bold">{{ \Carbon\Carbon::parse($log->work_date)->format('d M Y') }}</td>
                                <td>
                                    @if ($log->clock_in_at)
                                        <span
                                            class="badge bg-success px-3 py-2">{{ \Carbon\Carbon::parse($log->clock_in_at)->timezone('Asia/Kolkata')->format('h:i A') }}</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Not Marked</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->clock_out_at)
                                        <span
                                            class="badge bg-danger px-3 py-2">{{ \Carbon\Carbon::parse($log->clock_out_at)->timezone('Asia/Kolkata')->format('h:i A') }}</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Not Marked</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ $log->ip_address ?? '—' }}</td>
                                <td class="text-truncate" style="max-width: 250px;" title="{{ $log->device_info }}">
                                    {{ $log->device_info ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-4">🚫 No attendance records available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const token = @json($token);

        const flash = (msg, ok = true) => {
            const el = document.getElementById('flash');
            el.style.display = 'block';
            el.className = 'alert ' + (ok ? 'alert-success' : 'alert-danger');
            el.innerText = msg;
            setTimeout(() => {
                el.style.display = 'none';
            }, 3500);
        };

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                flash('Camera error: ' + err.message, false);
            });

        async function getNonce() {
            const res = await fetch('/api/attendance/nonce', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Nonce fetch failed');
            return res.json();
        }

        async function sendAttendance(urlPath) {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
            const {
                token: nonceToken
            } = await getNonce();

            const fd = new FormData();
            fd.append('photo', blob, 'capture.jpg');
            fd.append('nonce', nonceToken);

            const res = await fetch(urlPath, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                },
                body: fd
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data?.error || 'Request failed');

            flash(data.message || 'Attendance marked!');
            setTimeout(() => window.location.reload(), 1000);
        }

        document.getElementById('btnIn').addEventListener('click', () => {
            sendAttendance('/api/attendance/clock-in').catch(err => flash(err.message, false));
        });
        document.getElementById('btnOut').addEventListener('click', () => {
            sendAttendance('/api/attendance/clock-out').catch(err => flash(err.message, false));
        });
    </script>
@endpush
