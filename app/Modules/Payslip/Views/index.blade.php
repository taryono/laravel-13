@extends('layouts.app')
@section('title', 'Daftar Payslip (Slip Gaji)')

@section('content')
    <div class="container-fluid px-3 py-2">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold text-primary">Daftar Payslip (Slip Gaji)</h5>
                    <small class="text-muted">Kelola dan cetak slip gaji karyawan per periode payroll</small>
                </div>
                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                    data-toggle="ajax-modal"
                    data-size="lg"
                    data-method="GET"
                    data-title="Tambah Data Payslip Baru"
                    data-url="{{ route('payslip.create') }}"
                    data-submit='<button class="btn btn-primary modal-submit" type="submit" form="formPayslip" data-is_execute_drawer="true" data-datatable-id="payslip-table">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <i class="bi bi-plus-circle"></i> Tambah Baru
                </button>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped w-100 align-middle', 'id' => 'payslip-table']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
