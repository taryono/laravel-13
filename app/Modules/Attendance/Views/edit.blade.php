{{-- app/Modules/Payroll/Views/edit.blade.php --}}
<div class="row">
    <div class="col-12">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Data Kehadiran</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Data Kehadiran</h1>
            <span class="badge bg-info">{{ $attendance->employee->full_name ?? 'N/A' }}</span>
        </div>

        <!-- Form -->
        {{ Form::model($attendance, ['method' => 'PUT', 'route' => ['attendance.update', $attendance->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data', 'id' => 'formAttendance']) }}
        @include('Attendance::_form')
        {{ Form::close() }}
    </div>
</div>
