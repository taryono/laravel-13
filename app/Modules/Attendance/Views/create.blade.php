{{-- app/Modules/Payroll/Views/create.blade.php --}}
<div class="row">
    <div class="col-12">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Data Kehadiran</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Data Kehadiran</h1>
        </div>
        {{ Form::open(['method' => 'POST', 'route' => ['attendance.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data', 'id' => 'formAttendance']) }}
        @include('Attendance::_form')
        {{ Form::close() }}
    </div>
</div>
