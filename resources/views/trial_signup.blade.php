<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coba Gratis — EduPulse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --brand-blue: #2f5ce0;
            --brand-blue-dark: #1d3fa8;
            --brand-blue-light: #eaf0ff;
            --navy: #111a33;
            --ink: #101426;
            --muted: #5b6478;
            --soft-bg: #eef2f3;
            --card-border: #e6e9ee;
            --success: #22b573;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: var(--soft-bg);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4,
        .font-display {
            font-family: 'Poppins', sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* Top bar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--card-border);
            padding: 1rem 0;
        }

        .topbar .brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--ink);
        }

        .topbar .brand-icon {
            width: 32px;
            height: 32px;
            background: var(--brand-blue);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            margin-right: 8px;
            font-size: .9rem;
        }

        .exit-link {
            color: var(--muted);
            font-weight: 500;
            font-size: .9rem;
        }

        .exit-link:hover {
            color: var(--ink);
        }

        /* Progress */
        .wizard-progress {
            padding: 2.5rem 0 1rem;
        }

        .steps-track {
            display: flex;
            align-items: flex-start;
            max-width: 760px;
            margin: 0 auto;
            position: relative;
        }

        .step-node {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #d7dce6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--muted);
            margin: 0 auto .6rem;
            transition: all .25s ease;
            font-size: .95rem;
        }

        .step-node.active .step-circle {
            background: var(--brand-blue);
            border-color: var(--brand-blue);
            color: #fff;
            box-shadow: 0 0 0 5px var(--brand-blue-light);
        }

        .step-node.done .step-circle {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .step-label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted);
            max-width: 100px;
            margin: 0 auto;
        }

        .step-node.active .step-label {
            color: var(--ink);
        }

        .step-line-bg {
            position: absolute;
            top: 21px;
            left: 0;
            right: 0;
            height: 2px;
            background: #d7dce6;
            z-index: 1;
        }

        .step-line-fill {
            position: absolute;
            top: 21px;
            left: 0;
            height: 2px;
            background: var(--success);
            z-index: 1;
            transition: width .3s ease;
            width: 0%;
        }

        /* Card wrapper */
        .wizard-wrap {
            padding: 2rem 0 4rem;
        }

        .wizard-card {
            background: #fff;
            border: 1px solid var(--card-border);
            border-radius: 18px;
            max-width: 760px;
            margin: 0 auto;
            padding: 2.5rem;
            box-shadow: 0 20px 50px rgba(20, 30, 60, .06);
        }

        .step-eyebrow {
            color: var(--brand-blue);
            font-weight: 700;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .step-heading {
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: .3rem;
        }

        .step-desc {
            color: var(--muted);
            font-size: .92rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: .88rem;
            color: var(--ink);
        }

        .form-control,
        .form-select {
            border: 1.5px solid var(--card-border);
            border-radius: 10px;
            padding: .62rem .9rem;
            font-size: .92rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px var(--brand-blue-light);
        }

        /* Chip multi-select (Jenjang) */
        .chip-option {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: 1.5px solid var(--card-border);
            border-radius: 999px;
            padding: .5rem 1.1rem;
            cursor: pointer;
            font-size: .88rem;
            font-weight: 600;
            color: var(--ink);
            transition: all .15s ease;
        }

        .chip-option:hover {
            border-color: #b9c6f5;
        }

        .chip-option input {
            position: absolute;
            opacity: 0;
        }

        .chip-option::before {
            content: '';
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid #d7dce6;
            flex-shrink: 0;
            background: #fff;
            display: inline-block;
        }

        .chip-option:has(input:checked) {
            border-color: var(--brand-blue);
            background: var(--brand-blue-light);
            color: var(--brand-blue);
        }

        .chip-option:has(input:checked)::before {
            background: var(--brand-blue) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3E%3Cpath d='M13.7 3.7 6 11.4 2.3 7.7 3.7 6.3 6 8.6l6.3-6.3z'/%3E%3C/svg%3E") center/10px no-repeat;
            border-color: var(--brand-blue);
        }

        /* Module checklist cards */
        .module-option {
            display: block;
            border: 1.5px solid var(--card-border);
            border-radius: 12px;
            padding: 1.1rem 1.2rem;
            cursor: pointer;
            height: 100%;
            transition: all .15s ease;
            position: relative;
        }

        .module-option:hover {
            border-color: #b9c6f5;
        }

        .module-option input {
            position: absolute;
            opacity: 0;
        }

        .module-option .mod-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: var(--brand-blue-light);
            color: var(--brand-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .7rem;
            font-size: 1rem;
        }

        .module-option .mod-title {
            font-weight: 700;
            font-size: .94rem;
        }

        .module-option .mod-desc {
            color: var(--muted);
            font-size: .8rem;
            margin-top: .2rem;
        }

        .module-option .check-mark {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 22px;
            height: 22px;
            border-radius: 6px;
            border: 1.5px solid #d7dce6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            font-size: .7rem;
            background: #fff;
        }

        .module-option input:checked~.check-mark {
            background: var(--brand-blue);
            border-color: var(--brand-blue);
            color: #fff;
        }

        .module-option:has(input:checked) {
            border-color: var(--brand-blue);
            background: var(--brand-blue-light);
        }

        /* Role radio cards */
        .role-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1.5px solid var(--card-border);
            border-radius: 12px;
            padding: 1rem 1.2rem;
            cursor: pointer;
            transition: all .15s ease;
            position: relative;
        }

        .role-option:hover {
            border-color: #b9c6f5;
        }

        .role-option input {
            position: absolute;
            opacity: 0;
        }

        .role-option .role-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--brand-blue-light);
            color: var(--brand-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .role-option .role-title {
            font-weight: 700;
            font-size: .94rem;
        }

        .role-option .role-desc {
            color: var(--muted);
            font-size: .8rem;
        }

        .role-option .check-mark {
            margin-left: auto;
            width: 22px;
            height: 22px;
            border-radius: 6px;
            border: 1.5px solid #d7dce6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            font-size: .7rem;
            background: #fff;
            flex-shrink: 0;
        }

        .role-option input:checked~.check-mark {
            background: var(--brand-blue);
            border-color: var(--brand-blue);
            color: #fff;
        }

        .role-option:has(input:checked) {
            border-color: var(--brand-blue);
            background: var(--brand-blue-light);
        }

        /* Notification toggle cards */
        .notif-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1.5px solid var(--card-border);
            border-radius: 12px;
            padding: 1.1rem 1.2rem;
            margin-bottom: .9rem;
            transition: all .15s ease;
        }

        .notif-option.active {
            border-color: var(--brand-blue);
            background: var(--brand-blue-light);
        }

        .notif-option .notif-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .notif-option .notif-title {
            font-weight: 700;
            font-size: .94rem;
        }

        .notif-option .notif-desc {
            color: var(--muted);
            font-size: .8rem;
        }

        .form-switch .form-check-input {
            width: 2.6em;
            height: 1.4em;
            margin-left: auto;
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--success);
            border-color: var(--success);
        }

        /* Buttons */
        .btn-brand {
            background: var(--brand-blue);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: .7rem 1.6rem;
            border-radius: 10px;
        }

        .btn-brand:hover {
            background: var(--brand-blue-dark);
            color: #fff;
        }

        .btn-brand:disabled {
            background: #a9b8e8;
        }

        .btn-ghost {
            background: transparent;
            border: 1.5px solid var(--card-border);
            color: var(--ink);
            font-weight: 600;
            padding: .7rem 1.6rem;
            border-radius: 10px;
        }

        .btn-ghost:hover {
            background: #f5f6f8;
        }

        .wizard-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2.2rem;
            padding-top: 1.8rem;
            border-top: 1px solid var(--card-border);
        }

        .fade-step {
            animation: fadeIn .35s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e6f9f0;
            color: var(--success);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }

        @media (max-width:576px) {
            .step-label {
                display: none;
            }

            .wizard-card {
                padding: 1.5rem;
            }
        }

        .form-control::placeholder {
            color: #999;
            opacity: 0.6;
            /* Firefox menurunkan opacity secara default */
        }

        /* Gaya default select */
        select {
            color: #212529;
            opacity: 0.6;
            /* Warna normal */
        }

        /* Saat opsi yang dipilih adalah disabled (placeholder) */
        select:invalid {
            color: #6c757d;
            opacity: 0.6;
            /* Abu-abu seperti placeholder */
        }

        /* Untuk Firefox */
        select:-moz-ui-invalid {
            color: #6c757d;
            opacity: 0.6;
        }
    </style>
</head>

<body>

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="brand d-flex align-items-center">
                <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>EduPulse
            </a>
            <a href="{{ url('/') }}" class="exit-link"><i class="fa-solid fa-xmark me-1"></i>Batal</a>
        </div>
    </div>

    <!-- PROGRESS -->
    <div class="wizard-progress">
        <div class="steps-track" id="stepsTrack">
            <div class="step-line-bg"></div>
            <div class="step-line-fill" id="stepLineFill"></div>

            <div class="step-node active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Info yayasan/Sekolah</div>
            </div>
            <div class="step-node" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Pilih Modul</div>
            </div>
            <div class="step-node" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Role Pengguna</div>
            </div>
            <div class="step-node" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Notifikasi</div>
            </div>
            <div class="step-node" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Akun Anda</div>
            </div>
        </div>
    </div>

    <!-- WIZARD -->
    <div class="wizard-wrap">
        <div class="wizard-card">
            <form id="trialForm" novalidate>

                <!-- STEP 1 -->
                <div class="wizard-step fade-step" data-step="1">
                    <div class="step-eyebrow">Langkah 1 dari 5</div>
                    <h3 class="step-heading">Informasi Yayasan/Sekolah &amp; Jabatan Anda</h3>
                    <p class="step-desc">Ceritakan sedikit tentang Yayasan/Sekolah Anda supaya kami bisa menyiapkan akun
                        yang tepat.</p>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Yayasan/ Sekolah</label>
                            <input type="text" class="form-control" name="nama_sekolah"
                                placeholder="Contoh: SMA Harapan Bangsa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NPSN</label>
                            <input type="text" class="form-control" name="npsn" placeholder="Contoh: 20123456">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Jenjang Sekolah <span class="fw-normal text-muted">(bisa pilih
                                    lebih dari satu)</span></label>
                            <div class="d-flex flex-wrap gap-2" id="educationLevelGroup">
                                <label class="chip-option">
                                    <input type="checkbox" name="jenjang" value="paud">
                                    <span>PAUD / TK</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="jenjang" value="sd">
                                    <span>SD / MI</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="jenjang" value="smp">
                                    <span>SMP / MTs</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="jenjang" value="sma">
                                    <span>SMA / MA</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="jenjang" value="smk">
                                    <span>SMK</span>
                                </label>
                            </div>
                            <div class="form-text mt-1" id="jenjangError" style="display:none;color:#dc3545;">Pilih
                                minimal satu jenjang.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Siswa</label>
                            <select class="form-select" name="total_student" required>
                                <option value="" selected disabled>Pilih rentang</option>
                                <option value="<100">Kurang dari 100</option>
                                <option value="100-300">100 - 300</option>
                                <option value="300-600">300 - 600</option>
                                <option value=">600">Lebih dari 600</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Sekolah</label>
                            <input type="text" class="form-control" name="address"
                                placeholder="Jalan, Kelurahan, Kecamatan, Kota">
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap Anda</label>
                            <input type="text" class="form-control" name="nama_user" placeholder="Nama lengkap"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan di Sekolah</label>
                            <select class="form-select" name="position_id" required>
                                <option value="" selected disabled>Pilih jabatan</option>
                                <option value="kepala_sekolah">Kepala Sekolah</option>
                                <option value="wakil_kepala_sekolah">Wakil Kepala Sekolah</option>
                                <option value="guru">Guru</option>
                                <option value="tata_usaha">Staff Tata Usaha</option>
                                <option value="admin_it">Admin IT</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="wizard-step d-none" data-step="2">
                    <div class="step-eyebrow">Langkah 2 dari 5</div>
                    <h3 class="step-heading">Pilih Modul yang Anda Butuhkan</h3>
                    <p class="step-desc">Centang modul yang ingin Anda coba. Anda bisa menambah modul lain kapan saja
                        nanti.</p>

                    <div class="row g-3" id="moduleGrid">
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul" value="akademik" checked>
                                <div class="mod-icon"><i class="fa-solid fa-book-open"></i></div>
                                <div class="mod-title">Akademik</div>
                                <div class="mod-desc">Kurikulum Merdeka, e-Learning, Rapor Digital.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul" value="kesiswaan">
                                <div class="mod-icon"><i class="fa-solid fa-user-group"></i></div>
                                <div class="mod-title">Kesiswaan</div>
                                <div class="mod-desc">PPDB Online, profil siswa, alumni &amp; prestasi.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul" value="keuangan">
                                <div class="mod-icon"><i class="fa-solid fa-wallet"></i></div>
                                <div class="mod-title">Keuangan</div>
                                <div class="mod-desc">SPP Online (QRIS/VA), laporan BOS &amp; RKAS.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul" value="absensi">
                                <div class="mod-icon"><i class="fa-solid fa-calendar-check"></i></div>
                                <div class="mod-title">Absensi</div>
                                <div class="mod-desc">QR Code, RFID, Face Recognition, notifikasi WA.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul" value="parent_portal">
                                <div class="mod-icon"><i class="fa-solid fa-people-roof"></i></div>
                                <div class="mod-title">Parent Portal</div>
                                <div class="mod-desc">Dashboard orang tua: nilai, absensi, pembayaran.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul" value="analitik_ai">
                                <div class="mod-icon"><i class="fa-solid fa-chart-line"></i></div>
                                <div class="mod-title">Analitik AI</div>
                                <div class="mod-desc">Prediksi kelulusan &amp; analisis akademik berbasis AI.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                    </div>
                    <div class="form-text mt-2" id="moduleError" style="display:none;color:#dc3545;">Pilih minimal
                        satu modul untuk melanjutkan.</div>
                </div>

                <!-- STEP 3 -->
                <div class="wizard-step d-none" data-step="3">
                    <div class="step-eyebrow">Langkah 3 dari 5</div>
                    <h3 class="step-heading">Pilih Role Pengguna</h3>
                    <p class="step-desc">Role ini akan melekat pada akun pengguna pertama yang mengelola sistem. Bisa
                        pilih lebih dari satu, dan Anda dapat menambah pengguna lain setelah aktif.</p>

                    <div class="d-flex flex-column gap-3">
                        <label class="role-option">
                            <input type="checkbox" name="role" value="admin_sekolah" checked>
                            <div class="role-icon"><i class="fa-solid fa-user-gear"></i></div>
                            <div>
                                <div class="role-title">Admin Sekolah</div>
                                <div class="role-desc">Akses penuh ke seluruh modul dan pengaturan sistem.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role" value="guru">
                            <div class="role-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                            <div>
                                <div class="role-title">Guru</div>
                                <div class="role-desc">Kelola nilai, absensi kelas, dan materi pembelajaran.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role" value="tata_usaha">
                            <div class="role-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div>
                                <div class="role-title">Tata Usaha / Keuangan</div>
                                <div class="role-desc">Kelola pembayaran, laporan BOS, dan administrasi.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role" value="kepala_sekolah">
                            <div class="role-icon"><i class="fa-solid fa-user-tie"></i></div>
                            <div>
                                <div class="role-title">Kepala Sekolah</div>
                                <div class="role-desc">Pantau dashboard eksekutif dan laporan menyeluruh.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role" value="orang_tua">
                            <div class="role-icon"><i class="fa-solid fa-people-roof"></i></div>
                            <div>
                                <div class="role-title">Orang Tua / Wali</div>
                                <div class="role-desc">Pantau nilai, absensi, dan pembayaran anak.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                    </div>
                    <div class="form-text mt-2" id="roleError" style="display:none;color:#dc3545;">Pilih minimal satu
                        role untuk melanjutkan.</div>
                </div>

                <!-- STEP 4 -->
                <div class="wizard-step d-none" data-step="4">
                    <div class="step-eyebrow">Langkah 4 dari 5</div>
                    <h3 class="step-heading">Pilih Notifikasi yang Ingin Diaktifkan</h3>
                    <p class="step-desc">Kami akan mengirimkan pengingat dan informasi penting lewat kanal yang Anda
                        pilih.</p>

                    <div class="notif-option active" id="notifWhatsapp">
                        <div class="notif-icon" style="background:#25D366;"><i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <div class="notif-title">WhatsApp</div>
                            <div class="notif-desc">Notifikasi absensi, pembayaran, dan pengumuman.</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="notif_whatsapp" checked>
                        </div>
                    </div>

                    <div class="notif-option active" id="notifEmail">
                        <div class="notif-icon" style="background:#2f5ce0;"><i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <div class="notif-title">Email</div>
                            <div class="notif-desc">Ringkasan mingguan, invoice, dan laporan resmi.</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="notif_email" checked>
                        </div>
                    </div>

                    <div class="notif-option" id="notifSms">
                        <div class="notif-icon" style="background:#5b6478;"><i class="fa-solid fa-comment-sms"></i>
                        </div>
                        <div>
                            <div class="notif-title">SMS</div>
                            <div class="notif-desc">Notifikasi darurat saat jaringan internet terbatas.</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="notif_sms">
                        </div>
                    </div>
                </div>

                <!-- STEP 5 -->
                <div class="wizard-step d-none" data-step="5">
                    <div class="step-eyebrow">Langkah 5 dari 5</div>
                    <h3 class="step-heading">Buat Akun Anda</h3>
                    <p class="step-desc">Langkah terakhir. Data ini akan digunakan untuk masuk ke dashboard EduPulse
                        Anda.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Email Login</label>
                            <input type="email" class="form-control" name="email"
                                placeholder="nama@sekolah.sch.id" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Handphone</label>
                            <input type="tel" class="form-control" name="no_hp" placeholder="08xxxxxxxxxx"
                                required>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <label class="form-label">Buat Password</label>
                            <input type="password" class="form-control" name="password"
                                placeholder="Minimal 8 karakter" minlength="8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirm"
                                placeholder="Ulangi password" minlength="8" required>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms"
                                    style="font-size:.87rem;color:var(--muted);">
                                    Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> dan <a
                                        href="#">Kebijakan Privasi</a> EduPulse.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER NAV -->
                <div class="wizard-footer">
                    <button type="button" class="btn btn-ghost" id="btnPrev" style="visibility:hidden;">
                        <i class="fa-solid fa-arrow-left me-2"></i>Sebelumnya
                    </button>
                    <button type="button" class="btn btn-brand" id="btnNext">
                        Selanjutnya<i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-brand d-none" id="btnSubmit">
                        Mulai Coba Gratis<i class="fa-solid fa-rocket ms-2"></i>
                    </button>
                </div>

            </form>

            <!-- SUCCESS STATE -->
            <div id="successState" class="d-none text-center fade-step">
                <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                <h3 class="step-heading">Akun Trial Anda Siap!</h3>
                <p class="step-desc">Kami telah mengirimkan detail akses ke email dan WhatsApp Anda. Tim kami juga akan
                    menghubungi Anda dalam 1x24 jam untuk membantu proses setup.</p>
                <a href="edupulse.html" class="btn btn-brand">Kembali ke Beranda<i
                        class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const totalSteps = 5;
        let currentStep = 1;

        const stepsWrap = document.querySelectorAll('.wizard-step');
        const stepNodes = document.querySelectorAll('.step-node');
        const lineFill = document.getElementById('stepLineFill');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const moduleError = document.getElementById('moduleError');
        const jenjangError = document.getElementById('jenjangError');
        const roleError = document.getElementById('roleError');
        const form = document.getElementById('trialForm');
        const successState = document.getElementById('successState');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function renderStep() {
            stepsWrap.forEach(s => s.classList.toggle('d-none', parseInt(s.dataset.step) !== currentStep));

            stepNodes.forEach(node => {
                const n = parseInt(node.dataset.step);
                node.classList.remove('active', 'done');
                if (n < currentStep) node.classList.add('done');
                if (n === currentStep) node.classList.add('active');
            });

            const fillPct = ((currentStep - 1) / (totalSteps - 1)) * 100;
            lineFill.style.width = fillPct + '%';

            btnPrev.style.visibility = currentStep === 1 ? 'hidden' : 'visible';
            btnNext.classList.toggle('d-none', currentStep === totalSteps);
            btnSubmit.classList.toggle('d-none', currentStep !== totalSteps);

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function validateStep(step) {
            if (step === 1) {
                const required = document.querySelectorAll('.wizard-step[data-step="1"] [required]');
                let valid = true;
                required.forEach(f => {
                    if (!f.checkValidity()) {
                        f.reportValidity();
                        valid = false;
                    }
                });

                const jenjangChecked = document.querySelectorAll('input[name="jenjang"]:checked');
                if (jenjangChecked.length === 0) {
                    jenjangError.style.display = 'block';
                    valid = false;
                } else {
                    jenjangError.style.display = 'none';
                }
                return valid;
            }
            if (step === 2) {
                const checked = document.querySelectorAll('input[name="modul"]:checked');
                if (checked.length === 0) {
                    moduleError.style.display = 'block';
                    return false;
                }
                moduleError.style.display = 'none';
                return true;
            }
            if (step === 3) {
                const checked = document.querySelectorAll('input[name="role"]:checked');
                if (checked.length === 0) {
                    roleError.style.display = 'block';
                    return false;
                }
                roleError.style.display = 'none';
                return true;
            }
            if (step === 5) {
                const required = document.querySelectorAll('.wizard-step[data-step="5"] [required]');
                let valid = true;
                required.forEach(f => {
                    if (!f.checkValidity()) {
                        f.reportValidity();
                        valid = false;
                    }
                });
                const pw = form.password.value,
                    pwc = form.password_confirm.value;
                if (pw !== pwc) {
                    alert('Password dan konfirmasi password tidak sama.');
                    valid = false;
                }
                return valid;
            }
            return true;
        }

        function clearServerErrors(step) {
            document.querySelectorAll(`.wizard-step[data-step="${step}"] .server-error`).forEach(el => el.remove());
            document.querySelectorAll(`.wizard-step[data-step="${step}"] .is-invalid`).forEach(el => el.classList.remove(
                'is-invalid'));
        }

        function showServerErrors(step, errors) {
            // errors = { field: [messages] } dari Laravel ValidationException (422)
            Object.entries(errors).forEach(([field, messages]) => {
                const input = form.querySelector(
                    `.wizard-step[data-step="${step}"] [name="${field}"], .wizard-step[data-step="${step}"] [name="${field}[]"]`
                );
                const msg = document.createElement('div');
                msg.className = 'server-error text-danger small mt-1';
                msg.textContent = messages[0];
                if (input) {
                    input.classList.add('is-invalid');
                    input.closest('.col-12, .col-md-6, .col-md-4, .col-md-8')?.appendChild(msg);
                } else {
                    document.querySelector(`.wizard-step[data-step="${step}"] .step-desc`).insertAdjacentElement(
                        'afterend', msg);
                }
            });
        }

        function setButtonLoading(btn, loading, loadingText) {
            btn.disabled = loading;
            btn.dataset.originalText = btn.dataset.originalText || btn.innerHTML;
            btn.innerHTML = loading ?
                `<span class="spinner-border spinner-border-sm me-2"></span>${loadingText}` :
                btn.dataset.originalText;
        }

        async function saveStep(step) {
            clearServerErrors(step);
            const stepEl = document.querySelector(`.wizard-step[data-step="${step}"]`);
            const formData = new FormData();
            stepEl.querySelectorAll('input, select, textarea').forEach(field => {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    if (field.checked) formData.append(field.name, field.value);
                } else {
                    formData.append(field.name, field.value);
                }
            });

            try {
                const res = await fetch(`/trial-signup/step/${step}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData,
                });

                if (res.status === 422) {
                    const {
                        errors
                    } = await res.json();
                    showServerErrors(step, errors);
                    return false;
                }
                if (!res.ok) {
                    alert('Terjadi kesalahan pada server. Silakan coba lagi.');
                    return false;
                }
                return true;
            } catch (err) {
                alert('Gagal terhubung ke server. Periksa koneksi internet Anda.');
                return false;
            }
        }

        btnNext.addEventListener('click', async () => {
            if (!validateStep(currentStep)) return;

            setButtonLoading(btnNext, true, 'Menyimpan...');
            const saved = await saveStep(currentStep);
            setButtonLoading(btnNext, false);

            if (!saved) return;
            if (currentStep < totalSteps) {
                currentStep++;
                renderStep();
            }
        });

        btnPrev.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                renderStep();
            }
        });

        let isSubmitting = false;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (isSubmitting) return;
            if (!validateStep(5)) return;

            isSubmitting = true;
            clearServerErrors(5);
            setButtonLoading(btnSubmit, true, 'Memproses...');

            const formData = new FormData(
                form); // ambil semua field, termasuk step 5: email, no_hp, password, password_confirm

            try {
                const res = await fetch('/trial-signup/complete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData,
                });

                if (res.status === 422) {
                    const {
                        errors
                    } = await res.json();
                    showServerErrors(5, errors);
                    setButtonLoading(btnSubmit, false);
                    isSubmitting = false;
                    return;
                }
                if (!res.ok) {
                    alert('Terjadi kesalahan pada server. Silakan coba lagi.');
                    setButtonLoading(btnSubmit, false);
                    isSubmitting = false;
                    return;
                }

                const {
                    redirect
                } = await res.json();
                form.classList.add('d-none');
                document.getElementById('stepsTrack').style.display = 'none';
                successState.classList.remove('d-none');

                setTimeout(() => {
                    window.location.href = redirect || 'edupulse.html';
                }, 1800);
            } catch (err) {
                alert('Gagal terhubung ke server. Periksa koneksi internet Anda.');
                setButtonLoading(btnSubmit, false);
                isSubmitting = false;
            }
        });

        // Notification card active-state toggle
        ['notifWhatsapp', 'notifEmail', 'notifSms'].forEach(id => {
            const wrap = document.getElementById(id);
            const input = wrap.querySelector('input[type="checkbox"]');
            input.addEventListener('change', () => wrap.classList.toggle('active', input.checked));
        });

        renderStep();
    </script>
</body>

</html>
