{{ Form::open(['method' => 'POST', 'route' => ['cms_redirect.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label for="from_url" class="form-label">Dari URL <span class="text-danger">*</span></label>
            <input type="text" name="from_url" id="from_url" class="form-control" value="{{ old('from_url') }}" required
                placeholder="/halaman-lama">
            <div class="form-text">URL lama yang ingin dialihkan</div>
        </div>

        <div class="mb-3">
            <label for="to_url" class="form-label">Ke URL <span class="text-danger">*</span></label>
            <input type="text" name="to_url" id="to_url" class="form-control" value="{{ old('to_url') }}"
                required placeholder="/halaman-baru">
            <div class="form-text">Tujuan URL yang baru</div>
        </div>

        <div class="mb-3">
            <label for="status_code" class="form-label">Jenis Redirect</label>
            <select name="status_code" id="status_code" class="form-select">
                @foreach ($statusCodes as $code => $label)
                    <option value="{{ $code }}" {{ old('status_code', 301) == $code ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">
                <strong>301</strong> = Permanen (untuk SEO), <strong>302</strong> = Sementara
            </div>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Aktifkan Redirect</label>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
