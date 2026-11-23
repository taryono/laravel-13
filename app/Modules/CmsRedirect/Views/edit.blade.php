{{ Form::model($cms_redirect, ['method' => 'PUT', 'route' => ['cms_redirect.update', $cms_redirect->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label for="from_url" class="form-label">Dari URL <span class="text-danger">*</span></label>
            <input type="text" name="from_url" id="from_url" class="form-control"
                value="{{ old('from_url', $cmsRedirect->from_url) }}" required>
        </div>

        <div class="mb-3">
            <label for="to_url" class="form-label">Ke URL <span class="text-danger">*</span></label>
            <input type="text" name="to_url" id="to_url" class="form-control"
                value="{{ old('to_url', $cmsRedirect->to_url) }}" required>
        </div>

        <div class="mb-3">
            <label for="status_code" class="form-label">Jenis Redirect</label>
            <select name="status_code" id="status_code" class="form-select">
                @foreach ($statusCodes as $code => $label)
                    <option value="{{ $code }}"
                        {{ old('status_code', $cmsRedirect->status_code) == $code ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                    {{ old('is_active', $cmsRedirect->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Aktifkan Redirect</label>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
