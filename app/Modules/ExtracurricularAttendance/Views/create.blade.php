{{ Form::open(['method' => 'POST', 'route' => ['extracurricular_attendance.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('ExtracurricularAttendance::form')
{{ Form::close() }}

{{-- JS: Muat jadwal sesuai kegiatan yang dipilih --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ecSelect = document.getElementById('extracurricular_id');
        const daySelect = document.getElementById('extracurricular_day_id');

        if (!ecSelect || !daySelect) return;

        // Simpan daftar jadwal dari PHP (kirim dari controller)
        const scheduleData = @json($scheduleMapping ?? []);

        // Muat jadwal saat halaman pertama kali dibuka
        loadScheduleOptions(ecSelect.value);

        // Muat ulang saat kegiatan berubah
        ecSelect.addEventListener('change', function() {
            loadScheduleOptions(this.value);
        });

        function loadScheduleOptions(ecId) {
            daySelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';

            if (!ecId || !scheduleData[ecId]) return;

            scheduleData[ecId].forEach(function(item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.label;

                // Pilih otomatis saat edit
                const selectedId =
                    {{ old('extracurricular_day_id', $attendance?->extracurricular_day_id ?? 'null') }};
                if (selectedId && item.id == selectedId) {
                    option.selected = true;
                }

                daySelect.appendChild(option);
            });
        }
    });
</script>
