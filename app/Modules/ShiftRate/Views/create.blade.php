{{ Form::open(['method' => 'POST', 'route' => ['shift_rate.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('ShiftRate::_form')
{{ Form::close() }}

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shiftSelect = document.getElementById('shift_id');
            const shiftInfo = document.getElementById('shiftInfo');

            function updateShiftInfo() {
                const selectedOption = shiftSelect.options[shiftSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    shiftInfo.innerHTML = '';
                    return;
                }
                const start = selectedOption.dataset.start;
                const end = selectedOption.dataset.end;
                const crossesMidnight = start > end;
                shiftInfo.innerHTML = `
            <span class="badge bg-info">
                ${crossesMidnight ? '🌙 Melewati Tengah Malam' : '☀️ Hari yang Sama'}
            </span>
        `;
            }

            shiftSelect.addEventListener('change', updateShiftInfo);
            updateShiftInfo(); // Panggil saat modal pertama dibuka (untuk mode edit)
        });
    </script>
@endpush
