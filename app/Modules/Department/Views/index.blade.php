@extends('layouts.app')

@section('content')
<style>
/* ============================================
   CARD & HEADER
============================================ */
.card.dt-card {
    border: none;
    overflow: hidden;
}

.card.dt-card .card-header {
    background: linear-gradient(135deg, var(--bs-primary) 0%, #4b6fdb 100%);
    border-bottom: none;
    padding: 1rem 1.5rem;
}

.card.dt-card .card-header h5 {
    font-weight: 600;
    letter-spacing: 0.02em;
}

.card.dt-card .card-header .btn-light {
    font-weight: 500;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    transition: transform 0.15s ease;
}
.card.dt-card .card-header .btn-light:hover {
    transform: translateY(-1px);
}

/* ============================================
   DATATABLES WRAPPER (search, length, info)
============================================ */
.dataTables_wrapper {
    padding: 0.25rem 0;
}

.dataTables_wrapper .dataTables_length select {
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    padding: 0.3rem 2rem 0.3rem 0.75rem;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_filter input {
    border-radius: 2rem;
    border: 1px solid #dee2e6;
    padding: 0.4rem 1rem;
    margin-left: 0.5rem;
    font-size: 0.875rem;
    transition: box-shadow 0.15s ease, border-color 0.15s ease;
}
.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.15rem rgba(var(--bs-primary-rgb), 0.15);
}

.dataTables_wrapper .dataTables_info {
    font-size: 0.85rem;
    color: #6c757d;
    padding-top: 0.75rem;
}

/* ============================================
   TABLE HEADER
============================================ */
table.dataTable thead th {
    background-color: var(--bs-light);
    color: var(--bs-dark);
    font-weight: 600;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 2px solid var(--bs-primary) !important;
    padding-top: 0.85rem;
    padding-bottom: 0.85rem;
    white-space: nowrap;
}

table.dataTable thead th:first-child {
    border-top-left-radius: 0.5rem;
}
table.dataTable thead th:last-child {
    border-top-right-radius: 0.5rem;
}

/* Sort icon lebih rapi */
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    opacity: 0.35;
}

/* ============================================
   TABLE BODY
============================================ */
table.dataTable tbody td {
    font-size: 0.9rem;
    vertical-align: middle;
    padding-top: 0.7rem;
    padding-bottom: 0.7rem;
}

table.dataTable tbody tr {
    transition: background-color 0.12s ease;
}

table.dataTable tbody tr:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.06) !important;
}

/* Empty state biar tidak polos */
table.dataTable tbody tr.odd.no-records-found td,
table.dataTable tbody td.dataTables_empty {
    text-align: center;
    padding: 2.5rem 0;
    color: #adb5bd;
    font-style: italic;
}

/* ============================================
   PAGINATION
============================================ */
.dataTables_wrapper .dataTables_paginate {
    padding-top: 1rem;
}

.dataTables_wrapper .page-link {
    border-radius: 0.5rem;
    margin: 0 2px;
    border: none;
    color: var(--bs-dark);
    font-size: 0.85rem;
}

.dataTables_wrapper .page-item.active .page-link {
    background-color: var(--bs-primary);
    color: #fff;
    box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.35);
}

.dataTables_wrapper .page-item.disabled .page-link {
    background-color: transparent;
    color: #ced4da;
}

.dataTables_wrapper .page-link:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
}

/* ============================================
   BADGE JUMLAH DATA DI HEADER (opsional)
============================================ */
.dt-count-badge {
    font-size: 0.75rem;
    font-weight: 500;
    background-color: rgba(255,255,255,0.2);
    color: #fff;
    padding: 0.2rem 0.6rem;
    border-radius: 1rem;
}
</style>

<div class="container">
    <div class="card dt-card shadow-sm rounded-3">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-building"></i> Daftar Department
            </h5>
            <button type="button" 
                class="btn btn-light btn-sm d-flex align-items-center gap-1"
                data-toggle="ajax-modal" data-size="lg" data-method="GET"
                data-title="Tambah Data Department Baru" 
                data-url="{{ route('department.create') }}"
                data-submit='<button class="btn btn-primary" type="submit" form="formDepartment">Simpan</button>'
                data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                <i class="bi bi-plus-circle"></i> Tambah Baru
            </button>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                {!! $dataTable->table([
                    'class' => 'table table-striped table-hover align-middle w-100'
                ]) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush