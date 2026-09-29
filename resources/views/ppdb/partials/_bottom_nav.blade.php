{{-- resources/views/ppdb/partials/_bottom_nav.blade.php --}}
<nav class="bottom-nav" aria-label="Navigasi Bawah">
    <a href="#jadwal-section">
        <span class="material-symbols-outlined" style="font-size:1.2rem;">calendar_today</span>
        <span>Jadwal</span>
    </a>
    <a href="#" class="active" aria-current="page">
        <span class="material-symbols-outlined" style="font-size:1.2rem;">assignment</span>
        <span>Formulir</span>
    </a>
    <a href="#" onclick="saveDraft(); return false;">
        <span class="material-symbols-outlined" style="font-size:1.2rem;">edit_document</span>
        <span>Draf</span>
    </a>
    <a href="#bantuan">
        <span class="material-symbols-outlined" style="font-size:1.2rem;">contact_support</span>
        <span>Bantuan</span>
    </a>
</nav>
