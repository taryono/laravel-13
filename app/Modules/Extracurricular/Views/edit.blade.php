{{ Form::model($extracurricular, ['method' => 'PUT', 'route' => ['extracurricular.update', $extracurricular->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Extracurricular::form')
{{ Form::close() }}
{{-- Toggle tampilan jadwal --}}
<script>
    // Versi dengan jQuery (jika sudah dimuat di halaman)
    $(document).ready(function() {
        $('.day-toggle').each(function() {
            syncDisplay($(this));
        });

        $('.day-toggle').on('change', function() {
            syncDisplay($(this));
        });

        function syncDisplay($el) {
            console.log($el)
            const targetId = $el.data('target');
            const $target = $('#' + targetId);
            if ($el.is(':checked')) {
                $target.removeClass('d-none');
            } else {
                $target.addClass('d-none');
            }
        }
    });
</script>
