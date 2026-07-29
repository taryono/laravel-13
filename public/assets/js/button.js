// Dark mode toggle
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');
    const body = document.body;

    // Ambil preferensi tersimpan (default: light)
    const savedMode = localStorage.getItem('theme') || 'light';
    applyMode(savedMode);

    toggleBtn.addEventListener('click', function () {
        const isDark = body.classList.contains('dark-mode');
        const newMode = isDark ? 'light' : 'dark';
        applyMode(newMode);
        localStorage.setItem('theme', newMode);
    });

    function applyMode(mode) {
        if (mode === 'dark') {
            body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            body.classList.remove('light-mode');
            icon.textContent = 'light_mode';
            toggleBtn.classList.remove('btn-light');
            toggleBtn.classList.add('btn-dark');
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            icon.textContent = 'dark_mode';
            toggleBtn.classList.remove('btn-dark');
            toggleBtn.classList.add('btn-light');
        }
    }
});