{{-- resources/views/ppdb/partials/_styles.blade.php --}}
<style>
    :root {
        --bs-primary: #00288e;
        --bs-primary-rgb: 0, 40, 142;
        --bs-primary-container: #1e40af;
        --bs-secondary: #006398;
        --bs-secondary-container: #5bb8fe;
        --bs-primary-fixed: #dde1ff;
        --bs-secondary-fixed: #cce5ff;
        --bs-surface: #f8f9ff;
        --bs-on-surface: #0d1c2e;
        --bs-on-surface-variant: #444653;
        --bs-surface-container-lowest: #ffffff;
        --bs-surface-container-low: #eff4ff;
        --bs-surface-container: #e6eeff;
        --bs-surface-container-high: #dce9ff;
        --bs-surface-container-highest: #d5e3fc;
        --bs-outline: #757684;
        --bs-outline-variant: #c4c5d5;
        --bs-error: #ba1a1a;
        --bs-success: #10B981;
        --bs-success-dark: #065F46;
        --bs-success-light: #ECFDF5;
        --bs-success-border: #A7F3D0;
        --bs-warning: #F59E0B;
        --bs-warning-dark: #92400E;
        --bs-warning-light: #FFFBEB;
        --bs-warning-border: #FDE68A;
        --bs-slate-300: #CBD5E1;
        --bs-slate-400: #94A3B8;
    }

    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        background-color: var(--bs-surface);
        color: var(--bs-on-surface);
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
        padding-bottom: 9rem;
    }

    @media (min-width: 768px) {
        body {
            padding-bottom: 6rem;
        }
    }

    .material-symbols-outlined {
        font-family: 'Material Symbols Outlined';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
        vertical-align: middle;
    }

    /* ===== TOP APP BAR ===== */
    .app-header {
        background: var(--bs-surface-container-lowest);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        position: sticky;
        top: 0;
        z-index: 1030;
    }

    .app-header .brand-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        background: var(--bs-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    /* ===== CARDS ===== */
    .ppdb-card {
        background: var(--bs-surface-container-lowest);
        border: 1px solid var(--bs-outline-variant);
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
    }

    .ppdb-card .card-number {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        background: var(--bs-primary-fixed);
        color: var(--bs-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    /* ===== BADGES ===== */
    .badge-soft {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-success-soft {
        background: var(--bs-success-light);
        color: var(--bs-success-dark);
        border: 1px solid var(--bs-success-border);
    }

    .badge-warning-soft {
        background: var(--bs-warning-light);
        color: var(--bs-warning-dark);
        border: 1px solid var(--bs-warning-border);
    }

    /* ===== FORM CONTROLS ===== */
    .form-control,
    .form-select {
        height: 3rem;
        border: 1px solid var(--bs-slate-300);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        background-color: var(--bs-surface-container-lowest);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--bs-primary-container);
        box-shadow: 0 0 0 2px rgba(91, 184, 254, 0.35);
    }

    .form-control::placeholder {
        color: var(--bs-slate-400);
    }

    textarea.form-control {
        height: auto;
        min-height: 4rem;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--bs-on-surface);
        margin-bottom: 0.35rem;
    }

    /* ===== JALUR CHIP SELECTOR ===== */
    .jalur-chip {
        cursor: pointer;
        border: 1px solid var(--bs-slate-300);
        background: var(--bs-surface-container-lowest);
        border-radius: 0.75rem;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
        transition: all 0.15s;
        height: 100%;
    }

    .jalur-chip:hover {
        background: var(--bs-surface-container-low);
    }

    .jalur-chip.active {
        border: 2px solid var(--bs-primary);
        background: rgba(221, 225, 255, 0.3);
    }

    .jalur-chip.active .jalur-label {
        color: var(--bs-primary);
        font-weight: 700;
    }

    .jalur-chip input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    /* ===== CHECKBOX PILL ===== */
    .check-pill {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem;
        border-radius: 0.5rem;
        border: 1px solid var(--bs-slate-300);
        background: var(--bs-surface-container-lowest);
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.15s;
    }

    .check-pill:hover {
        background: var(--bs-surface-container-low);
    }

    .check-pill.active {
        border-color: var(--bs-primary);
        background: rgba(221, 225, 255, 0.3);
        color: var(--bs-primary);
    }

    /* ===== TIMELINE STEPPER ===== */
    .timeline {
        position: relative;
        padding-left: 1.75rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.6rem;
        top: 0.5rem;
        bottom: 0.5rem;
        width: 2px;
        background: var(--bs-slate-300);
    }

    .timeline-step {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-step:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -1.75rem;
        top: 0.15rem;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bs-surface-container-lowest);
        border: 2px solid var(--bs-outline-variant);
        z-index: 1;
    }

    .timeline-dot.active,
    .timeline-dot.done {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 4px var(--bs-primary-fixed);
        color: #fff;
    }

    .timeline-dot .dot-inner {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 999px;
        background: var(--bs-outline);
    }

    /* ===== FILE UPLOAD ===== */
    .file-box {
        border: 1px dashed var(--bs-slate-300);
        border-radius: 0.75rem;
        padding: 1rem;
        background: var(--bs-surface-container-low);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        transition: background 0.15s;
    }

    .file-box:hover {
        background: var(--bs-surface-container);
    }

    @media (min-width: 576px) {
        .file-box {
            flex-direction: row;
            text-align: left;
        }
    }

    .file-box-uploaded {
        border: 1px solid var(--bs-success-border);
        border-style: solid;
        background: var(--bs-surface-container-low);
    }

    /* ===== STICKY BOTTOM BAR ===== */
    .sticky-action-bar {
        position: fixed;
        bottom: 3.75rem;
        left: 0;
        right: 0;
        z-index: 1040;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px);
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.06);
        padding: 0.75rem 1rem;
    }

    @media (min-width: 768px) {
        .sticky-action-bar {
            bottom: 0;
        }
    }

    /* ===== BOTTOM NAV ===== */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 1050;
        background: var(--bs-surface-container-lowest);
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 0.5rem 0.75rem;
    }

    @media (min-width: 768px) {
        .bottom-nav {
            display: none;
        }
    }

    .bottom-nav a {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.75rem;
        border-radius: 0.75rem;
        color: var(--bs-on-surface-variant);
        text-decoration: none;
        font-size: 0.65rem;
        transition: all 0.15s;
    }

    .bottom-nav a.active {
        background: var(--bs-primary-fixed);
        color: var(--bs-primary);
        font-weight: 700;
    }

    /* ===== BUTTONS ===== */
    .btn-primary-custom {
        background: var(--bs-primary);
        border: none;
        color: #fff;
        font-weight: 700;
        height: 3rem;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.15s;
    }

    .btn-primary-custom:hover {
        background: #1D4ED8;
        color: #fff;
    }

    .btn-primary-custom:disabled {
        opacity: 0.7;
    }

    .btn-outline-custom {
        border: 1px solid var(--bs-slate-300);
        background: var(--bs-surface-container-lowest);
        color: var(--bs-on-surface-variant);
        font-weight: 600;
        height: 3rem;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-outline-custom:hover {
        background: var(--bs-surface-container-low);
        color: var(--bs-on-surface);
    }

    /* ===== GEO PIN BOX ===== */
    .geo-box {
        padding: 0.85rem;
        background: var(--bs-surface-container-low);
        border-radius: 0.5rem;
        border: 1px solid var(--bs-outline-variant);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* ===== INFO ALERT ===== */
    .info-alert {
        padding: 0.85rem;
        background: rgba(221, 225, 255, 0.3);
        border: 1px solid rgba(0, 40, 142, 0.2);
        border-radius: 0.75rem;
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
    }

    /* ===== HELP CARD GRADIENT ===== */
    .help-card {
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-primary-container));
        color: #fff;
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .help-card .help-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 600;
        transition: background 0.15s;
    }

    .help-card .help-link:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    /* ===== MICRO ANIMATION ===== */
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.98);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    /* ===== TIMER BOX ===== */
    .timer-box {
        background: var(--bs-warning-light);
        border-left: 4px solid var(--bs-warning);
        border-radius: 0.5rem;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* ===== PROGRESS BAR ===== */
    .progress-thin {
        height: 0.5rem;
        border-radius: 999px;
        background: var(--bs-surface-container-high);
        overflow: hidden;
    }

    .progress-thin .progress-bar {
        background: var(--bs-primary);
        border-radius: 999px;
    }

    /* ===== MODAL CUSTOM ===== */
    .modal-doc-preview {
        background: var(--bs-surface-container-lowest);
        border-radius: 1rem;
        border: 1px solid var(--bs-outline-variant);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        width: 100%;
        max-width: 42rem;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .doc-preview-content {
        background: var(--bs-surface-container-low);
        padding: 1.5rem;
        overflow-y: auto;
        display: flex;
        justify-content: center;
    }

    .doc-preview-paper {
        background: #fff;
        border: 1px solid var(--bs-outline-variant);
        border-radius: 0.75rem;
        padding: 1.5rem;
        max-width: 32rem;
        width: 100%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* ===== RESPONSIVE TYPOGRAPHY ===== */
    .headline-lg-mobile {
        font-size: 1.5rem;
        line-height: 2rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    @media (min-width: 768px) {
        .headline-lg-mobile {
            font-size: 2rem;
            line-height: 2.5rem;
        }
    }

    /* ===== ICON STACK IN BUTTON ===== */
    .btn-icon {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-icon .material-symbols-outlined {
        font-size: 1rem;
    }

    /* Small button */
    .btn-sm-custom {
        padding: 0.35rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border: 1px solid var(--bs-outline-variant);
        background: var(--bs-surface-container-lowest);
        color: var(--bs-primary);
        transition: background 0.15s;
        cursor: pointer;
    }

    .btn-sm-custom:hover {
        background: var(--bs-surface-container);
        color: var(--bs-primary);
    }

    /* ===== UTILITY ===== */
    .min-w-0 {
        min-width: 0;
    }

    .w-sm-auto {
        width: auto;
    }

    @media (min-width: 576px) {
        .w-sm-auto {
            width: auto !important;
        }
    }

    /* Border dashed utility */
    .border-dashed {
        border-style: dashed !important;
    }

    /* ===== CUSTOM SCROLLBAR ===== */
    .doc-preview-content::-webkit-scrollbar {
        width: 6px;
    }

    .doc-preview-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .doc-preview-content::-webkit-scrollbar-thumb {
        background: var(--bs-outline-variant);
        border-radius: 999px;
    }
</style>
