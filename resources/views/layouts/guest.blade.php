<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduPulse — Platform Sekolah All-in-One</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <style>
  :root {
    --edu-blue: #1565C0;
    --edu-blue-light: #1976D2;
    --edu-accent: #2196F3;
    --edu-bg: #F4F6FA;
    --edu-muted: #6B7280;
    --edu-red: #EF4444;
    --edu-green: #22C55E;
  }
  * { box-sizing: border-box; }
  body {
    background: var(--edu-bg);
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
  }
  .app-shell {
    width: 100%;
    max-width: 420px;
  }
  /* ── LOGO ── */
  .auth-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 28px;
  }
  .auth-logo .logo-box {
    width: 44px; height: 44px;
    background: var(--edu-blue);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.3rem;
    box-shadow: 0 4px 14px rgba(21,101,192,.35);
  }
  .auth-logo span {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--edu-blue);
    letter-spacing: -.5px;
  }
  /* ── CARD ── */
  .auth-card {
    background: #fff;
    border-radius: 20px;
    padding: 32px 28px 28px;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
  }
  .auth-card h4 {
    font-size: 1.2rem;
    font-weight: 800;
    color: #1A1A2E;
    margin-bottom: 4px;
  }
  .auth-card .subtitle {
    font-size: .82rem;
    color: var(--edu-muted);
    margin-bottom: 24px;
  }
  /* ── FORM ── */
  .form-label {
    font-size: .8rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 5px;
  }
  .input-group-custom {
    position: relative;
    margin-bottom: 16px;
  }
  .input-group-custom .input-icon {
    position: absolute;
    left: 14px; top: 50%;
    transform: translateY(-50%);
    color: #9CA3AF;
    font-size: 1rem;
    pointer-events: none;
    z-index: 2;
  }
  .input-group-custom .form-control {
    padding-left: 40px;
    border-radius: 10px;
    border: 1.5px solid #E5E7EB;
    font-size: .9rem;
    height: 46px;
    transition: border-color .2s, box-shadow .2s;
  }
  .input-group-custom .form-control:focus {
    border-color: var(--edu-accent);
    box-shadow: 0 0 0 3px rgba(33,150,243,.12);
    outline: none;
  }
  .input-group-custom .form-control.is-invalid {
    border-color: var(--edu-red);
  }
  /* toggle password */
  .toggle-pw {
    position: absolute;
    right: 13px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: #9CA3AF; font-size: 1rem;
    cursor: pointer; z-index: 2;
    padding: 0;
  }
  .toggle-pw:hover { color: var(--edu-blue); }
  /* ── REMEMBER / FORGOT ── */
  .row-opts {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
  }
  .row-opts .form-check-label {
    font-size: .82rem;
    color: #374151;
  }
  .row-opts a {
    font-size: .82rem;
    color: var(--edu-accent);
    text-decoration: none;
    font-weight: 600;
    white-space: nowrap;
  }
  /* ── BUTTON ── */
  .btn-primary-edu {
    width: 100%;
    background: var(--edu-blue);
    color: #fff;
    border: none;
    border-radius: 12px;
    height: 48px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s, transform .1s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
  }
  .btn-primary-edu:hover { background: var(--edu-blue-light); }
  .btn-primary-edu:active { transform: scale(.98); }
  /* ── DIVIDER ── */
  .or-divider {
    display: flex; align-items: center; gap: 12px;
    margin: 20px 0;
    font-size: .78rem;
    color: var(--edu-muted);
  }
  .or-divider::before,
  .or-divider::after {
    content: ''; flex: 1;
    height: 1px; background: #E5E7EB;
  }
  /* ── SOCIAL BTN ── */
  .btn-social {
    width: 100%;
    background: #F9FAFB;
    border: 1.5px solid #E5E7EB;
    border-radius: 12px;
    height: 46px;
    font-size: .88rem;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    margin-bottom: 10px;
    transition: background .15s, border-color .15s;
  }
  .btn-social:hover {
    background: #F3F4F6;
    border-color: #D1D5DB;
  }
  .btn-social img { 
    width: 20px; 
    height: 20px; 
    flex-shrink: 0;
  }
  /* ── FOOTER ── */
  .auth-footer {
    text-align: center;
    margin-top: 20px;
    font-size: .83rem;
    color: var(--edu-muted);
  }
  .auth-footer a {
    color: var(--edu-blue);
    font-weight: 700;
    text-decoration: none;
  }
  /* ── ALERT ── */
  .invalid-msg {
    font-size: .76rem;
    color: var(--edu-red);
    margin-top: 4px;
    display: none;
  }
  .invalid-msg.show { display: block; }

  /* ── RESPONSIVE ADJUSTMENT ── */
  @media (max-width: 480px) {
    .auth-card {
      padding: 24px 18px 20px;
    }
    .auth-logo span {
      font-size: 1.25rem;
    }
  }
  </style>
  @stack('styles')
</head>
<body>
  <div class="app-shell">
    {{-- ISI KONTEN HALAMAN AKAN MUNCUL DI SINI --}}
    {{ $slot }}
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>