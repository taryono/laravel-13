<div class="modal-body">
    <h6>Informasi Shift</h6>
    <table class="table table-bordered">
        <tr>
            <th width="30%">Kode</th>
            <td>{{ $shift->code }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $shift->name }}</td>
        </tr>
        <tr>
            <th>Jam Mulai</th>
            <td>{{ date('H:i', strtotime($shift->start_time)) }}</td>
        </tr>
        <tr>
            <th>Jam Selesai</th>
            <td>{{ date('H:i', strtotime($shift->end_time)) }}</td>
        </tr>
        <tr>
            <th>Durasi</th>
            <td>{{ $shift->duration_in_hours }} jam</td>
        </tr>
        <tr>
            <th>Cross Day</th>
            <td>{{ $shift->is_cross_day ? 'Ya' : 'Tidak' }}</td>
        </tr>
        <tr>
            <th>Toleransi Telat</th>
            <td>{{ $shift->grace_period_start ?? 0 }} menit</td>
        </tr>
        <tr>
            <th>Toleransi Pulang Cepat</th>
            <td>{{ $shift->grace_period_end ?? 0 }} menit</td>
        </tr>
        <tr>
            <th>Rate Lembur</th>
            <td>{{ $shift->overtime_rate ?? 1.5 }}x</td>
        </tr>
        <tr>
            <th>Shift Malam</th>
            <td>{{ $shift->night_shift ? 'Ya (Mulai ' . date('H:i', strtotime($shift->night_shift_start ?? '22:00')) . ')' : 'Tidak' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $shift->is_active ? 'Aktif' : 'Nonaktif' }}</td>
        </tr>
    </table>

    @if($shift->employee_shifts->count() > 0)
        <h6 class="mt-3">Karyawan dengan Shift Ini</h6>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Berlaku</th>
                    <th>Sampai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shift->employee_hifts as $es)
                    <tr>
                        <td>{{ $es->employee->employee_id ?? '-' }}</td>
                        <td>{{ $es->employee->full_name ?? '-' }}</td>
                        <td>{{ $es->effective_date }}</td>
                        <td>{{ $es->end_date ?? 'Seterusnya' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info mt-3">Belum ada karyawan yang menggunakan shift ini.</div>
    @endif
</div>