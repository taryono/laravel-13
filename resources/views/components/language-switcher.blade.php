<!-- resources/views/components/language-switcher.blade.php -->
<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        {{ app()->getLocale() === 'id' ? '🇮🇩 ID' : '🇬🇧 EN' }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" 
               href="{{ route('locale.switch', 'en') }}">
                🇬🇧 English
            </a>
        </li>
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" 
               href="{{ route('locale.switch', 'id') }}">
                🇮🇩 Indonesia
            </a>
        </li>
    </ul>
</div>