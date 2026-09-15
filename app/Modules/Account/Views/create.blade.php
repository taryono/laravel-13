{{ Form::open([
    'method' => $method ?? 'POST',
    'route' => $action ?? 'account.store',
    'class' => 'form-horizontal',
    'id' => 'formAccount',
]) }}
@csrf
@if (($account->id ?? 0) > 0)
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3">
            {{ ($account->id ?? 0) > 0 ? 'Ubah Akun' : 'Tambah Akun Baru' }}
        </h5>
    </div>

    {{-- Kode & Nama Akun --}}
    <div class="col-md-4">
        <label class="form-label">Kode Akun <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $account->code ?? '') }}" placeholder="Contoh: 1-1000" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label class="form-label">Nama Akun <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $account->name ?? '') }}" placeholder="Contoh: Kas Kecil" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kategori & Jenis Akun --}}
    <div class="col-md-4">
        <label class="form-label">Kategori Akun <span class="text-danger">*</span></label>
        <select name="account_category_id" class="form-select @error('account_category_id') is-invalid @enderror"
            required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('account_category_id', $account->account_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('account_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Jenis Akun</label>
        <select name="account_type" class="form-select">
            @php $types = ['header' => 'Header / Induk', 'detail' => 'Rincian / Transaksi']; @endphp
            @foreach ($types as $val => $label)
                <option value="{{ $val }}"
                    {{ old('account_type', $account->account_type ?? 'detail') == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Header = hanya kelompok, Detail = bisa dipakai transaksi</small>
    </div>

    <div class="col-md-4">
        <label class="form-label">Saldo Normal <span class="text-danger">*</span></label>
        <select name="normal_balance" class="form-select @error('normal_balance') is-invalid @enderror" required>
            @php $balances = ['debit' => 'Debit', 'kredit' => 'Kredit']; @endphp
            @foreach ($balances as $val => $label)
                <option value="{{ $val }}"
                    {{ old('normal_balance', $account->normal_balance ?? '') == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('normal_balance')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Akun Induk & Mata Uang --}}
    <div class="col-md-6">
        <label class="form-label">Akun Induk</label>
        <select name="parent_id" class="form-select">
            <option value="">-- Tidak Ada Induk (Akun Utama) --</option>
            @foreach ($parentAccounts as $pAcc)
                <option value="{{ $pAcc->id }}"
                    {{ old('parent_id', $account->parent_id ?? '') == $pAcc->id ? 'selected' : '' }}>
                    {{ $pAcc->code }} — {{ $pAcc->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Mata Uang</label>
        <select name="currency_code" class="form-select">
            @foreach ($currencies as $curr)
                <option value="{{ $curr }}"
                    {{ old('currency_code', $account->currency_code ?? 'IDR') == $curr ? 'selected' : '' }}>
                    {{ $curr }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Saldo Awal --}}
    <div class="col-md-6">
        <label class="form-label">Saldo Awal</label>
        <input type="number" name="opening_balance" class="form-control" step="0.01" min="0"
            value="{{ old('opening_balance', $account->opening_balance ?? '0.00') }}">
        <small class="text-muted">Hanya diisi saat pembukaan periode baru</small>
    </div>

    {{-- Status --}}
    <div class="col-md-6 align-self-center pt-3">
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                {{ old('is_active', $account->is_active ?? 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Akun Aktif</label>
        </div>
    </div>

    {{-- Keterangan --}}
    <div class="col-12">
        <label class="form-label">Keterangan</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $account->description ?? '') }}</textarea>
    </div>
</div>
{{ Form::close() }}
