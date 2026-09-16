
{{-- resources/views/proctoring/dashboard.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4">📡 Dasbor Pengawasan Ujian — Real-Time</h2>

    {{-- Filter --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <select id="filter-exam" class="form-select">
                <option value="">Semua Paket Ujian</option>
                @foreach($exams as $e)
                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="filter-status" class="form-select">
                <option value="">Semua Status</option>
                <option value="in_progress">Sedang Berlangsung</option>
                <option value="paused">Dijeda</option>
                <option value="flagged">✅ Ditandai</option>
            </select>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row mb-3">
        <div class="col-md-2"><div class="card bg-light p-2"><h5 id="total-sessions">0</h5><p>Total Sesi</p></div></div>
        <div class="col-md-2"><div class="card bg-success p-2"><h5 id="safe-sessions">0</h5><p>Aman</p></div></div>
        <div class="col-md-2"><div class="card bg-warning text-dark p-2"><h5 id="warn-sessions">0</h5><p>Perlu Perhatian</p></div></div>
        <div class="col-md-2"><div class="card bg-danger text-white p-2"><h5 id="alert-sessions">0</h5><p>Pelanggaran</p></div></div>
    </div>

    {{-- Tabel Sesi --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Peserta</th>
                        <th>Paket Ujian</th>
                        <th>Status</th>
                        <th>Skor Kepercayaan</th>
                        <th>Detak Terakhir</th>
                        <th>Log Aktivitas</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody id="sessions-table">
                    {{-- Diisi otomatis via JS --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- Log Aktivitas Terbaru --}}
    <div class="card mt-4">
        <div class="card-header">📋 Log Aktivitas Terbaru</div>
        <div class="card-body p-0">
            <div id="activity-log" class="list-group list-group-flush" style="max-height:350px;overflow-y:auto;">
                {{-- Log masuk real-time --}}
            </div>
        </div>
    </div>
</div>

{{-- Modal Tandai Sesi --}}
<div class="modal fade" id="flagModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="flagForm">
            <div class="modal-content">
                <div class="modal-header"><h5>Tandai Sesi Pelanggaran</h5></div>
                <div class="modal-body">
                    <input type="hidden" id="flag_session_id">
                    <div class="mb-3">
                        <label class="form-label">Alasan</label>
                        <textarea name="flag_reason" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Pengawas</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Tanda</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.min.js"></script>
<script>
const echo = new Echo({
    broadcaster: 'reverb',
    key: '{{ config("broadcasting.connections.reverb.key") }}',
    host: window.location.hostname + ':8080',
    scheme: 'http',
});

let sessions = @json($sessions);
const tbody = document.getElementById('sessions-table');
const activityLog = document.getElementById('activity-log');

// Render tabel sesi
function renderSessions() {
    tbody.innerHTML = '';
    sessions.forEach(s => {
        const scoreClass = s.trust_score >= 80 ? 'text-bg-success' :
                          s.trust_score >= 60 ? 'text-bg-warning' : 'text-bg-danger';
        const timeAgo = s.last_heartbeat ? timeSince(s.last_heartbeat) : '—';

        tbody.innerHTML += `
            <tr data-id="${s.id}">
                <td>${s.student?.name ?? 'Peserta #' + s.student_id}</td>
                <td>${s.exam_package?.name ?? '—'}</td>
                <td><span class="badge bg-${statusColor(s.status)}">${s.status}</span></td>
                <td><span class="badge ${scoreClass}">${s.trust_score ?? 100}</span></td>
                <td class="small">${timeAgo}</td>
                <td><span class="badge bg-secondary">${s.log_count ?? 0}</span></td>
                <td><button class="btn btn-sm btn-outline-danger flag-btn" data-id="${s.id}">⚠ Tandai</button></td>
            </tr>`;
    });
    updateSummary();
}

function statusColor(st) {
    return { 'in_progress':'primary', 'paused':'warning', 'flagged':'danger', 'completed':'success' }[st] ?? 'secondary';
}

function timeSince(dtStr) {
    const dt = new Date(dtStr);
    const sec = Math.floor((Date.now() - dt.getTime())/1000);
    if (sec <30) return '<span class="text-success">Baru saja</span>';
    if (sec <60) return `${sec} detik lalu`;
    return `${Math.floor(sec/60)} menit lalu`;
}

function updateSummary() {
    document.getElementById('total-sessions').textContent = sessions.length;
    document.getElementById('safe-sessions').textContent = sessions.filter(s => (s.trust_score??100)>=80).length;
    document.getElementById('warn-sessions').textContent = sessions.filter(s => (s.trust_score??100)>=60 && (s.trust_score??100)<80).length;
    document.getElementById('alert-sessions').textContent = sessions.filter(s => (s.trust_score??100)<60).length;
}

// Masukkan log aktivitas baru
function prependLog(item) {
    const sevClass = { info:'bg-light', warning:'bg-warning', danger:'bg-danger text-white', critical:'bg-dark text-white' }[item.severity] ?? 'bg-light';
    activityLog.insertAdjacentHTML('afterbegin', `
        <div class="list-group-item list-group-item-action ${sevClass} py-2 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <strong>${item.student_name}</strong>
                <small>${new Date(item.recorded_at).toLocaleTimeString()}</small>
            </div>
            <div class="mb-1">${item.activity_type}</div>
            ${item.snapshot_url ? `<a href="${item.snapshot_url}" target="_blank" class="btn btn-sm btn-outline-dark">Lihat Cuplikan</a>` : ''}
        </div>`);
    // Perbarui baris terkait
    const idx = sessions.findIndex(s => s.id === item.session_id);
    if (idx !== -1) {
        sessions[idx].trust_score = item.trust_score;
        sessions[idx].log_count = (sessions[idx].log_count ?? 0) + 1;
    }
    renderSessions();
}

// Langganan saluran real-time
echo.private('proctoring.dashboard')
    .listen('.proctoring.update', (e) => {
        if (e.eventType === 'heartbeat') {
            const idx = sessions.findIndex(s => s.id === e.data.session_id);
            if (idx !== -1) {
                sessions[idx].last_heartbeat = e.data.last_heartbeat;
                sessions[idx].status = e.data.status;
                sessions[idx].trust_score = e.data.trust_score;
            }
            renderSessions();
        } else if (e.eventType === 'new_activity') {
            prependLog(e.data);
        } else if (e.eventType === 'session_flagged') {
            const idx = sessions.findIndex(s => s.id === e.data.session_id);
            if (idx !== -1) sessions[idx].status = 'flagged';
            renderSessions();
        }
    });

// Buka modal tandai sesi
document.addEventListener('click', e => {
    if (e.target.classList.contains('flag-btn')) {
        document.getElementById('flag_session_id').value = e.target.dataset.id;
        new bootstrap.Modal(document.getElementById('flagModal')).show();
    }
});

// Kirim form tandai
document.getElementById('flagForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('session_id', document.getElementById('flag_session_id').value);

    await fetch('/api/proctoring/flag-session', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify(Object.fromEntries(fd))
    });
    bootstrap.Modal.getInstance(document.getElementById('flagModal')).hide();
});

document.addEventListener('DOMContentLoaded', renderSessions);
</script>
@endsection