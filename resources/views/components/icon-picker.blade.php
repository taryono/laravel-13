@props([
    'id' => 'icon-picker',
    'name' => 'icon',
    'typeName' => 'icon_type',
    'value' => 'fas fa-th-large',
    'typeValue' => 'fa',
    'label' => 'Icon Menu',
    'required' => false
])

<div class="form-icon-group mb-3" id="{{ $id }}-wrapper">
    <label class="form-label">
        <i class="fas fa-icons me-1"></i> {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input type="hidden" name="{{ $typeName }}" id="{{ $id }}-type" value="{{ $typeValue }}">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}-value" value="{{ $value }}">

    <div class="icon-picker-wrapper">
        <div class="icon-preview-box" id="{{ $id }}-preview-box">
            <div class="preview-icon" id="{{ $id }}-preview-display">
                @if($typeValue === 'fa')
                    <i class="{{ $value }}"></i>
                @else
                    <span class="material-symbols-outlined">{{ $value }}</span>
                @endif
            </div>
            <div class="preview-info">
                <div class="icon-name" id="{{ $id }}-preview-name">Pilih Icon</div>
                <div class="icon-class" id="{{ $id }}-preview-class">{{ $value }}</div>
                <span class="icon-type-badge" id="{{ $id }}-preview-type">
                    {{ $typeValue === 'fa' ? 'Font Awesome' : 'Material Symbols' }}
                </span>
            </div>
            <div style="margin-left: auto; color: #b0b8d0;">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>

        <div class="form-preview-result">
            <div class="result-icon" id="{{ $id }}-result-display">
                @if($typeValue === 'fa')
                    <i class="{{ $value }}"></i>
                @else
                    <span class="material-symbols-outlined">{{ $value }}</span>
                @endif
            </div>
            <div>
                <small class="text-muted d-block">Akan dikirim:</small>
                <code id="{{ $id }}-result-code">icon_type: {{ $typeValue }} | icon: {{ $value }}</code>
            </div>
        </div>
    </div>
</div>