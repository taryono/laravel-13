@extends('layouts.cbt')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">{{ $promotion->name }}</h1>
                <small class="text-muted">
                    <code>{{ $promotion->code }}</code> &middot;
                    {{ $promotion->fromAcademicYear?->name }}
                    <i class="bi bi-arrow-right"></i>
                    {{ $promotion->toAcademicYear?->name }}
                </small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                @if ($promotion->status === 'draft')
                    <form action="{{ route('promotions.generate', $promotion) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary">
                            <i class="bi bi-magic"></i> Generate Draft
                        </button>
                    </form>
                @endif
                @if ($promotion->status === 'completed' && !$promotion->is_finalized)
                    <form action="{{ route('promotions.finalize', $promotion) }}" method="POST"
                        onsubmit="return confirm('Finalize batch ini? Aksi tidak bisa dibatalkan.')">
                        @csrf
                        <button class="btn btn-success">
                            <i class="bi bi-check2-circle"></i> Finalize
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Statistik --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="text-muted small">Total Siswa</div>
                        <div class="h3 mb-0">{{ $promotion->total_students }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="text-muted small">Naik Kelas</div>
                        <div class="h3 mb-0 text-success">{{ $promotion->promoted_count }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="text-muted small">Tinggal Kelas</div>
                        <div class="h3 mb-0 text-warning">{{ $promotion->retained_count }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="text-muted small">Lulus</div>
                        <div class="h3 mb-0 text-info">{{ $promotion->graduated_count }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama / NIS..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="promoted" {{ request('status') === 'promoted' ? 'selected' : '' }}>Naik Kelas
                            </option>
                            <option value="retained" {{ request('status') === 'retained' ? 'selected' : '' }}>Tinggal Kelas
                            </option>
                            <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>Lulus
                            </option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas Asal</th>
                            <th>Kelas Tujuan</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $p)
                            <tr>
                                <td><code>{{ $p->student?->nis }}</code></td>
                                <td>{{ $p->student?->name }}</td>
                                <td>
                                    <small>
                                        {{ $p->fromClassroom?->name ?? '-' }}
                                        ({{ $p->fromClassLevel?->name ?? '-' }})
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        {{ $p->toClassroom?->name ?? '-' }}
                                        ({{ $p->toClassLevel?->name ?? '-' }})
                                    </small>
                                </td>
                                <td>{{ $p->final_score ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $p->status_badge }}">
                                        {{ $p->status_label }}
                                    </span>
                                    @if ($p->is_manual)
                                        <i class="bi bi-pencil-square text-warning" title="Manual override"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (!$p->is_finalized)
                                        <a href="{{ route('promotions.edit', $p) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada data promosi. Klik "Generate Draft" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($promotions->hasPages())
                <div class="card-footer">
                    {{ $promotions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
