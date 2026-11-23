  @if (!$holiday->exists)
      <strong>Mode Tambah:</strong> Anda bisa menginput rentang tanggal. Sistem akan otomatis membuat entri untuk
      <strong>setiap hari</strong> dalam rentang tersebut.
  @else
      <strong>Mode Edit:</strong> Anda sedang mengedit 1 tanggal spesifik.
  @endif
  {{ Form::model($holiday, ['method' => 'PUT', 'route' => ['holiday.update', $holiday->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data', 'id' => 'formHoliday']) }}
  @include('Holiday::_form')
  {{ Form::close() }}
