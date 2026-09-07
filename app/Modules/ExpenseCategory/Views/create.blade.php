{{-- ============================================================ --}}
{{-- FORM EXPENSE CATEGORY (CREATE/EDIT)                          --}}
{{-- ============================================================ --}}

@if (isset($category))
    {{ Form::model($category, ['method' => 'PUT', 'route' => ['expense-category.update', $category->id], 'class' => 'form-horizontal', 'id' => 'categoryForm']) }}
@else
    {{ Form::open(['route' => 'expense-category.store', 'class' => 'form-horizontal', 'id' => 'categoryForm']) }}
@endif

@csrf
@if (isset($category))
    @method('PUT')
@endif

<div class="row g-3">

    {{-- ===== HEADER ===== --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2">
            <i class="fas fa-tags me-2"></i>
            {{ isset($category) ? 'Edit Kategori Pengeluaran' : 'Tambah Kategori Pengeluaran' }}
        </h5>
    </div>

    {{-- ===== KODE KATEGORI ===== --}}
    <div class="col-md-6">
        <label class="form-label">
            <i class="fas fa-hashtag me-1"></i> Kode Kategori <span class="text-danger">*</span>
        </label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $category->code ?? '') }}"
            placeholder="OPR" required {{ isset($category) ? 'readonly' : '' }}>
        @error('code')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <small class="text-muted">Kode unik, contoh: OPR, UTL, SEW</small>
    </div>

    {{-- ===== NAMA KATEGORI ===== --}}
    <div class="col-md-6">
        <label class="form-label">
            <i class="fas fa-tag me-1"></i> Nama Kategori <span class="text-danger">*</span>
        </label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}"
            placeholder="Operasional" required>
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- ===== DESKRIPSI ===== --}}
    <div class="col-12">
        <label class="form-label">
            <i class="fas fa-align-left me-1"></i> Deskripsi
        </label>
        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi kategori pengeluaran">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- ===== PARENT KATEGORI ===== --}}
    <div class="col-md-6">
        <label class="form-label">
            <i class="fas fa-sitemap me-1"></i> Kategori Induk
        </label>
        <select name="parent_id" class="form-select">
            <option value="">-- Tanpa Induk --</option>
            @foreach ($parentCategories as $parent)
                @if (!isset($category) || $parent->id != $category->id)
                    <option value="{{ $parent->id }}"
                        {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->code }} - {{ $parent->name }}
                    </option>
                @endif
            @endforeach
        </select>
        @error('parent_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- ===== SORT ORDER ===== --}}
    <div class="col-md-3">
        <label class="form-label">
            <i class="fas fa-sort-numeric-down me-1"></i> Urutan
        </label>
        <input type="number" name="sort_order" class="form-control"
            value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
        @error('sort_order')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- ===== STATUS AKTIF ===== --}}
    <div class="col-md-3">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                <i class="fas fa-check-circle me-1"></i> Aktif
            </label>
        </div>
        @error('is_active')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- ===== STATISTIK (untuk edit) ===== --}}
    @if (isset($category))
        <div class="col-12">
            <div class="alert alert-light border">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h6 class="text-muted">Total Pengeluaran</h6>
                        <h4 class="text-primary">Rp
                            {{ number_format($category->expenses->sum('total_amount') ?? 0, 0, ',', '.') }}</h4>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Jumlah Transaksi</h6>
                        <h4 class="text-primary">{{ $category->expenses->count() }}</h4>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Rata-rata</h6>
                        <h4 class="text-primary">Rp
                            {{ number_format($category->expenses->avg('total_amount') ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{ Form::close() }}
