{{ Form::open(['method' => 'POST', 'route' => ['admission.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Admission::form')
{{ Form::close() }}

<script>
    (function() {
        function formatDateIndo(dateObj) {
            return dateObj.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        function updateAgePreviews() {
            // Preview Usia Minimal
            const minY = parseInt(document.getElementById('min_age_years')?.value || '');
            const minM = parseInt(document.getElementById('min_age_months')?.value || '0');
            const minRef = document.getElementById('min_age_reference_date')?.value;
            const minBox = document.getElementById('minAgePreview');

            if (!isNaN(minY) && minRef) {
                const d = new Date(minRef + 'T00:00:00');
                d.setFullYear(d.getFullYear() - minY);
                d.setMonth(d.getMonth() - (isNaN(minM) ? 0 : minM));
                minBox.innerHTML = `Syarat: Lahir <strong>pada atau sebelum ${formatDateIndo(d)}</strong>`;
            } else {
                minBox.textContent = 'Tidak ada batas usia minimal.';
            }

            // Preview Usia Maksimal
            const maxY = parseInt(document.getElementById('max_age_years')?.value || '');
            const maxM = parseInt(document.getElementById('max_age_months')?.value || '0');
            const maxRef = document.getElementById('max_age_reference_date')?.value;
            const maxBox = document.getElementById('maxAgePreview');

            if (!isNaN(maxY) && maxRef) {
                const d = new Date(maxRef + 'T00:00:00');
                d.setFullYear(d.getFullYear() - maxY);
                d.setMonth(d.getMonth() - (isNaN(maxM) ? 0 : maxM));
                maxBox.innerHTML = `Syarat: Lahir <strong>pada atau setelah ${formatDateIndo(d)}</strong>`;
            } else {
                maxBox.textContent = 'Tidak ada batas usia maksimal.';
            }
        }

        document.querySelectorAll('.age-rule-input').forEach(el => {
            el.addEventListener('input', updateAgePreviews);
            el.addEventListener('change', updateAgePreviews);
        });
        updateAgePreviews();
    })();
</script>
