@extends('layouts.guest')

@section('content')
<section class="hero" style="padding: 3rem 0 5rem;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="text-center mb-5">
                    <h1 class="text-light">Jadwalkan Demo</h1>
                    <p class="lead text-light opacity-75">Isi formulir di bawah ini dan tim kami akan menghubungi Anda dalam waktu 1×24 jam.</p>
                </div>

                {{-- CARD FORM — menggunakan Laravel Collective --}}
                <div class="demo-card p-4 p-md-5">
                    {!! Form::open(['route' => 'submit_request_demo.form', 'method' => 'POST']) !!}
                        @csrf

                        <div class="row g-4">
                            {{-- Nama Sekolah --}}
                            <div class="col-md-6">
                                {!! Form::label('school_name', 'Nama Sekolah', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::text('school_name', null, [
                                    'class' => 'form-control form-control-lg',
                                    'placeholder' => 'Masukkan nama sekolah',
                                    'required'
                                ]) !!}
                            </div>

                            {{-- Nama Penanggung Jawab --}}
                            <div class="col-md-6">
                                {!! Form::label('person_name', 'Nama Penanggung Jawab', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::text('person_name', null, [
                                    'class' => 'form-control form-control-lg',
                                    'placeholder' => 'Nama lengkap Anda',
                                    'required'
                                ]) !!}
                            </div>

                            {{-- Jabatan --}}
                            <div class="col-md-6">
                                {!! Form::label('position', 'Jabatan', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::text('position', null, [
                                    'class' => 'form-control form-control-lg',
                                    'placeholder' => 'Contoh: Kepala Sekolah, IT Admin',
                                    'required'
                                ]) !!}
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                {!! Form::label('email', 'Email', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::email('email', null, [
                                    'class' => 'form-control form-control-lg',
                                    'placeholder' => 'email@sekolah.sch.id',
                                    'required'
                                ]) !!}
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="col-md-6">
                                {!! Form::label('phone', 'Nomor Telepon / WhatsApp', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::tel('phone', null, [
                                    'class' => 'form-control form-control-lg',
                                    'placeholder' => '0812xxxxxxx',
                                    'required'
                                ]) !!}
                            </div>

                            {{-- Pilihan Paket --}}
                            <div class="col-md-6">
                                {!! Form::label('package', 'Pilihan Paket', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::select('package', [
                                    '' => 'Pilih Paket',
                                    'starter' => 'Paket Starter — Rp 300rb / bln',
                                    'professional' => 'Paket Profesional — Rp 700rb / bln',
                                    'enterprise' => 'Paket Enterprise — Harga Khusus',
                                ], null, [
                                    'class' => 'form-select form-select-lg',
                                    'required'
                                ]) !!}
                            </div>

                            {{-- Tanggal & Waktu Demo --}}
                            <div class="col-md-6">
                                {!! Form::label('demo_datetime', 'Tanggal & Waktu Demo', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::datetimeLocal('demo_datetime', null, [
                                    'class' => 'form-control form-control-lg',
                                    'required'
                                ]) !!}
                            </div>

                            {{-- Pesan / Keterangan Tambahan --}}
                            <div class="col-12">
                                {!! Form::label('message', 'Pesan / Keterangan Tambahan', ['class' => 'form-label fw-semibold']) !!}
                                {!! Form::textarea('message', null, [
                                    'class' => 'form-control',
                                    'rows' => 4,
                                    'placeholder' => 'Tuliskan kebutuhan khusus atau pertanyaan Anda...'
                                ]) !!}
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="col-12 mt-2">
                                {!! Form::submit('Jadwalkan Demo Sekarang', [
                                    'class' => 'btn btn-primary w-100 py-3'
                                ]) !!}
                            </div>

                            {{-- Keterangan Bawah --}}
                            <div class="col-12 text-center mt-3">
                                <p class="text-muted small mb-0">Tim kami akan menghubungi Anda dalam 1×24 jam untuk konfirmasi.</p>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ============================================
       VARIABEL UNTUK DARK/LIGHT MODE
    ============================================ */
    :root {
        --card-bg: #ffffff;
        --card-border: #e6e9ee;
        --card-shadow: rgba(20, 30, 60, 0.08);
        --text-primary: #101426;
        --text-muted: #5b6478;
        --input-bg: #ffffff;
        --input-border: #d7dbe3;
        --input-focus-shadow: rgba(47, 92, 224, 0.15);
    }

    [data-bs-theme="dark"] {
        --card-bg: #151d2e;
        --card-border: #2a3348;
        --card-shadow: rgba(0, 0, 0, 0.3);
        --text-primary: #e8edf5;
        --text-muted: #98a1b5;
        --input-bg: #0a0e1a;
        --input-border: #2a3348;
        --input-focus-shadow: rgba(47, 92, 224, 0.25);
    }

    /* ============================================
       DEMO CARD
    ============================================ */
    .demo-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        box-shadow: 0 20px 60px var(--card-shadow);
        transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }

    /* ============================================
       FORM ELEMENTS
    ============================================ */
    .form-label {
        color: var(--text-primary);
        font-size: .92rem;
        margin-bottom: .5rem;
        transition: color 0.3s ease;
    }

    .form-control,
    .form-select {
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 10px;
        padding: .65rem 1rem;
        font-size: .95rem;
        color: var(--text-primary);
        transition: background 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
        transition: color 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--brand-blue);
        box-shadow: 0 0 0 .25rem var(--input-focus-shadow);
        background: var(--input-bg);
        color: var(--text-primary);
    }

    /* Select option di dark mode */
    [data-bs-theme="dark"] .form-select option {
        background: #0a0e1a;
        color: #e8edf5;
    }

    textarea.form-control {
        resize: none;
    }

    /* ============================================
       HERO TEXT (override dari layout)
    ============================================ */
    .hero .text-light {
        color: var(--hero-text, #ffffff) !important;
        transition: color 0.3s ease;
    }

    .hero .text-light.opacity-75 {
        color: var(--hero-muted, #c7cede) !important;
        transition: color 0.3s ease;
    }

    /* ============================================
       TEXT MUTED
    ============================================ */
    .text-muted {
        color: var(--text-muted) !important;
        transition: color 0.3s ease;
    }

    /* ============================================
       RESPONSIVE FIX
    ============================================ */
    @media (max-width: 768px) {
        .demo-card {
            padding: 1.5rem !important;
        }
        .hero h1 {
            font-size: 2rem;
        }
    }
</style>
@endpush