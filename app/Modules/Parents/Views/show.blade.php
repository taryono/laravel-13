@extends('layouts.app')

@section('title', 'Detail Orang Tua / Wali')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detail Orang Tua / Wali</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('parents.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <a href="{{ route('parents.edit', $parent) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Identitas</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-4">Nama</dt><dd class="col-8">{{ $parent->name }}</dd>
                        <dt class="col-4">NIK</dt><dd class="col-8">{{ $parent->nik ?: '-' }}</dd>
                        <dt class="col-4">Jenis Kelamin</dt>
                        <dd class="col-8">{{ $parent->gender === 'L' ? 'Laki-laki' : ($parent->gender === 'P' ? 'Perempuan' : '-') }}</dd>
                        <dt class="col-4">Tempat/Tgl Lahir</dt>
                        <dd class="col-8">
                            {{ $parent->birth_place ?: '-' }},
                            {{ $parent->birth_date?->format('d/m/Y') ?? ($parent->birth_year ?: '-') }}
                        </dd>
                        <dt class="col-4">Agama</dt><dd class="col-8">{{ $parent->religion ?: '-' }}</dd>
                        <dt class="col-4">Kewarganegaraan</dt><dd class="col-8">{{ $parent->nationality ?: '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Hubungan & Kontak</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-4">Hubungan</dt><dd class="col-8">{{ ucfirst($parent->relation) }} {{ $parent->relation_detail ? '('.$parent->relation_detail.')' : '' }}</dd>
                        <dt class="col-4">Orang Tua Kandung</dt><dd class="col-8">{{ $parent->is_biological ? 'Ya' : 'Tidak' }}</dd>
                        <dt class="col-4">Kontak Utama</dt><dd class="col-8">{{ $parent->is_primary_contact ? 'Ya' : 'Tidak' }}</dd>
                        <dt class="col-4">HP</dt><dd class="col-8">{{ $parent->phone }}</dd>
                        <dt class="col-4">WhatsApp</dt><dd class="col-8">{{ $parent->whatsapp ?: '-' }}</dd>
                        <dt class="col-4">Email</dt><dd class="col-8">{{ $parent->email ?: '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Alamat</div>
                <div class="card-body">
                    <p class="mb-1">{{ $parent->address ?: '-' }}</p>
                    <p class="text-muted mb-0">
                        RT {{ $parent->rt ?: '-' }} / RW {{ $parent->rw ?: '-' }},
                        {{ $parent->village ?: '-' }}, {{ $parent->district ?: '-' }},
                        {{ $parent->city ?: '-' }}, {{ $parent->province ?: '-' }} {{ $parent->postal_code }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Sosial-Ekonomi</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-4">Pendidikan</dt><dd class="col-8">{{ $parent->education_level ?: '-' }}</dd>
                        <dt class="col-4">Pekerjaan</dt><dd class="col-8">{{ $parent->occupation ?: '-' }}</dd>
                        <dt class="col-4">Penghasilan</dt><dd class="col-8">{{ $parent->income_range ?: '-' }}</dd>
                        <dt class="col-4">Status</dt>
                        <dd class="col-8">
                            @if($parent->is_alive)
                                <span class="badge bg-success">Hidup</span>
                            @else
                                <span class="badge bg-secondary">Meninggal</span>
                            @endif
                            @if($parent->is_active)
                                <span class="badge bg-primary">Aktif</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection