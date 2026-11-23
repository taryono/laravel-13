<div class="alert alert-info py-2 mb-3">
    <small><i class="bi bi-info-circle"></i> <strong>Catatan:</strong> Jika menggunakan rentang tanggal, sistem akan
        otomatis membuat entri untuk <strong>setiap hari</strong> dalam rentang tersebut.</small>
</div>

{{ Form::open(['method' => 'POST', 'route' => ['holiday.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data', 'id' => 'formHoliday']) }}
@include('Holiday::_form')
{{ Form::close() }}
