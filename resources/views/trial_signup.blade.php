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
    <link rel="stylesheet" href="{{ asset('assets/css/trial.css') }}">
    <style>
        .input-group-password {
            position: relative;
        }
        .btn-toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 5;
            padding: 0;
        }
        .btn-toggle-password:hover {
            color: var(--brand-blue, #2f5ce0);
        }
        .step-node.done {
            cursor: pointer;
        }
        .notif-option {
            cursor: pointer;
            user-select: none;
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
                <div class="step-label">Info Yayasan/Sekolah</div>
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

                @php
                    $savedLevels = isset($registration)
                        ? $registration->education_level->pluck('code')->map(fn($c) => strtoupper($c))->toArray()
                        : [];
                    $savedModules = isset($registration)
                        ? $registration->modules->pluck('code')->map(fn($c) => strtolower($c))->toArray()
                        : ['akademik'];
                    $savedRoles = isset($registration)
                        ? $registration->roles->pluck('code')->map(fn($c) => strtolower(str_replace('-', '_', $c)))->toArray()
                        : ['admin_sekolah'];
                @endphp

                <!-- STEP 1 -->
                <div class="wizard-step fade-step" data-step="1">
                    <div class="step-eyebrow">Langkah 1 dari 5</div>
                    <h3 class="step-heading">Informasi Yayasan/Sekolah &amp; Jabatan Anda</h3>
                    <p class="step-desc">Ceritakan sedikit tentang Yayasan/Sekolah Anda supaya kami bisa menyiapkan akun yang tepat.</p>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Yayasan / Sekolah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_sekolah"
                                value="{{ old('nama_sekolah', $registration->nama_sekolah ?? '') }}"
                                placeholder="Contoh: SMA Harapan Bangsa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NPSN</label>
                            <input type="text" class="form-control" name="npsn"
                                value="{{ old('npsn', $registration->npsn ?? '') }}"
                                placeholder="Contoh: 20123456">
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Jenjang Sekolah <span class="fw-normal text-muted">(bisa pilih lebih dari satu)</span> <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-2" id="educationLevelGroup">
                                <label class="chip-option">
                                    <input type="checkbox" name="education_level_id[]" value="TK"
                                        {{ in_array('TK', $savedLevels) || in_array('PAUD', $savedLevels) ? 'checked' : '' }}>
                                    <span>PAUD / TK</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="education_level_id[]" value="SD"
                                        {{ in_array('SD', $savedLevels) ? 'checked' : '' }}>
                                    <span>SD / MI</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="education_level_id[]" value="SMP"
                                        {{ in_array('SMP', $savedLevels) ? 'checked' : '' }}>
                                    <span>SMP / MTs</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="education_level_id[]" value="SMA"
                                        {{ in_array('SMA', $savedLevels) ? 'checked' : '' }}>
                                    <span>SMA / MA</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="education_level_id[]" value="SMK"
                                        {{ in_array('SMK', $savedLevels) ? 'checked' : '' }}>
                                    <span>SMK</span>
                                </label>
                            </div>
                            <div class="form-text mt-1" id="jenjangError" style="display:none;color:#dc3545;">
                                Pilih minimal satu jenjang sekolah.
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Siswa <span class="text-danger">*</span></label>
                            <select class="form-select" name="total_student" required>
                                <option value="" {{ empty($registration->total_student) ? 'selected' : '' }} disabled>Pilih rentang</option>
                                @if(isset($student_ranges) && $student_ranges->count())
                                    @foreach ($student_ranges as $range)
                                        <option value="{{ $range->name }}"
                                            {{ ($registration->total_student ?? '') == $range->name || ($registration->total_student ?? '') == $range->id ? 'selected' : '' }}>
                                            {{ $range->name }} Siswa
                                        </option>
                                    @endforeach
                                @else
                                    <option value="< 100" {{ ($registration->total_student ?? '') == '< 100' ? 'selected' : '' }}>&lt; 100 Siswa</option>
                                    <option value="100 - 300" {{ ($registration->total_student ?? '') == '100 - 300' ? 'selected' : '' }}>100 - 300 Siswa</option>
                                    <option value="300 - 600" {{ ($registration->total_student ?? '') == '300 - 600' ? 'selected' : '' }}>300 - 600 Siswa</option>
                                    <option value="600-1000" {{ ($registration->total_student ?? '') == '600-1000' || ($registration->total_student ?? '') == '600 - 1000' ? 'selected' : '' }}>600 - 1000 Siswa</option>
                                    <option value="> 1000" {{ ($registration->total_student ?? '') == '> 1000' ? 'selected' : '' }}>&gt; 1000 Siswa</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Sekolah</label>
                            <textarea class="form-control" name="address" rows="3" placeholder="Jalan, Kelurahan, Kecamatan, Kota">{{ old('address', $registration->address ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap Anda <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_user"
                                value="{{ old('nama_user', $registration->nama_user ?? '') }}"
                                placeholder="Nama lengkap Anda" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan di Sekolah <span class="text-danger">*</span></label>
                            <select class="form-select" name="position_id" required>
                                <option value="" {{ empty($registration->position_id) ? 'selected' : '' }} disabled>Pilih jabatan</option>
                                @if(isset($positions) && $positions->count())
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->code ?? $position->id }}"
                                            {{ ($registration->position_id ?? '') == ($position->code ?? $position->id) || ($registration->position_id ?? '') == $position->id ? 'selected' : '' }}>
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="kepala_sekolah" {{ ($registration->position_id ?? '') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                    <option value="wakil_kepala_sekolah" {{ ($registration->position_id ?? '') == 'wakil_kepala_sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                                    <option value="guru" {{ ($registration->position_id ?? '') == 'guru' ? 'selected' : '' }}>Guru / Tenaga Pendidik</option>
                                    <option value="tata_usaha" {{ ($registration->position_id ?? '') == 'tata_usaha' ? 'selected' : '' }}>Tata Usaha / Administrasi / Keuangan</option>
                                    <option value="admin_it" {{ ($registration->position_id ?? '') == 'admin_it' ? 'selected' : '' }}>Admin IT / Operator</option>
                                    <option value="lainnya" {{ ($registration->position_id ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="wizard-step d-none" data-step="2">
                    <div class="step-eyebrow">Langkah 2 dari 5</div>
                    <h3 class="step-heading">Pilih Modul yang Anda Butuhkan</h3>
                    <p class="step-desc">Centang modul yang ingin Anda coba. Anda bisa menambah modul lain kapan saja nanti.</p>

                    <div class="row g-3" id="moduleGrid">
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul[]" value="akademik"
                                    {{ in_array('akademik', $savedModules) ? 'checked' : '' }}>
                                <div class="mod-icon"><i class="fa-solid fa-book-open"></i></div>
                                <div class="mod-title">Akademik</div>
                                <div class="mod-desc">Kurikulum Merdeka, e-Learning, Rapor Digital.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul[]" value="kesiswaan"
                                    {{ in_array('kesiswaan', $savedModules) ? 'checked' : '' }}>
                                <div class="mod-icon"><i class="fa-solid fa-user-group"></i></div>
                                <div class="mod-title">Kesiswaan</div>
                                <div class="mod-desc">PPDB Online, profil siswa, alumni &amp; prestasi.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul[]" value="keuangan"
                                    {{ in_array('keuangan', $savedModules) ? 'checked' : '' }}>
                                <div class="mod-icon"><i class="fa-solid fa-wallet"></i></div>
                                <div class="mod-title">Keuangan</div>
                                <div class="mod-desc">SPP Online (QRIS/VA), laporan BOS &amp; RKAS.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul[]" value="absensi"
                                    {{ in_array('absensi', $savedModules) ? 'checked' : '' }}>
                                <div class="mod-icon"><i class="fa-solid fa-calendar-check"></i></div>
                                <div class="mod-title">Absensi</div>
                                <div class="mod-desc">QR Code, RFID, Face Recognition, notifikasi WA.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul[]" value="parent_portal"
                                    {{ in_array('parent_portal', $savedModules) ? 'checked' : '' }}>
                                <div class="mod-icon"><i class="fa-solid fa-people-roof"></i></div>
                                <div class="mod-title">Parent Portal</div>
                                <div class="mod-desc">Dashboard orang tua: nilai, absensi, pembayaran.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="module-option">
                                <input type="checkbox" name="modul[]" value="analitik_ai"
                                    {{ in_array('analitik_ai', $savedModules) ? 'checked' : '' }}>
                                <div class="mod-icon"><i class="fa-solid fa-chart-line"></i></div>
                                <div class="mod-title">Analitik AI</div>
                                <div class="mod-desc">Prediksi kelulusan &amp; analisis akademik berbasis AI.</div>
                                <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                            </label>
                        </div>
                    </div>
                    <div class="form-text mt-2" id="moduleError" style="display:none;color:#dc3545;">
                        Pilih minimal satu modul untuk melanjutkan.
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="wizard-step d-none" data-step="3">
                    <div class="step-eyebrow">Langkah 3 dari 5</div>
                    <h3 class="step-heading">Pilih Role Pengguna</h3>
                    <p class="step-desc">Role ini akan melekat pada akun pengguna pertama yang mengelola sistem. Bisa pilih lebih dari satu, dan Anda dapat menambah pengguna lain setelah aktif.</p>

                    <div class="d-flex flex-column gap-3">
                        <label class="role-option">
                            <input type="checkbox" name="role[]" value="admin_sekolah"
                                {{ in_array('admin_sekolah', $savedRoles) || in_array('admin-sekolah', $savedRoles) || in_array('admin-yayasan', $savedRoles) ? 'checked' : '' }}>
                            <div class="role-icon"><i class="fa-solid fa-user-gear"></i></div>
                            <div>
                                <div class="role-title">Admin Sekolah</div>
                                <div class="role-desc">Akses penuh ke seluruh modul dan pengaturan sistem.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role[]" value="guru"
                                {{ in_array('guru', $savedRoles) ? 'checked' : '' }}>
                            <div class="role-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                            <div>
                                <div class="role-title">Guru</div>
                                <div class="role-desc">Kelola nilai, absensi kelas, dan materi pembelajaran.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role[]" value="tata_usaha"
                                {{ in_array('tata_usaha', $savedRoles) || in_array('admin_keuangan', $savedRoles) ? 'checked' : '' }}>
                            <div class="role-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div>
                                <div class="role-title">Tata Usaha / Keuangan</div>
                                <div class="role-desc">Kelola pembayaran, laporan BOS, dan administrasi.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role[]" value="kepala_sekolah"
                                {{ in_array('kepala_sekolah', $savedRoles) || in_array('kepala-sekolah', $savedRoles) ? 'checked' : '' }}>
                            <div class="role-icon"><i class="fa-solid fa-user-tie"></i></div>
                            <div>
                                <div class="role-title">Kepala Sekolah</div>
                                <div class="role-desc">Pantau dashboard eksekutif dan laporan menyeluruh.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                        <label class="role-option">
                            <input type="checkbox" name="role[]" value="orang_tua"
                                {{ in_array('orang_tua', $savedRoles) || in_array('orang-tua', $savedRoles) ? 'checked' : '' }}>
                            <div class="role-icon"><i class="fa-solid fa-people-roof"></i></div>
                            <div>
                                <div class="role-title">Orang Tua / Wali</div>
                                <div class="role-desc">Pantau nilai, absensi, dan pembayaran anak.</div>
                            </div>
                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                        </label>
                    </div>
                    <div class="form-text mt-2" id="roleError" style="display:none;color:#dc3545;">
                        Pilih minimal satu role untuk melanjutkan.
                    </div>
                </div>

                <!-- STEP 4 -->
                <div class="wizard-step d-none" data-step="4">
                    <div class="step-eyebrow">Langkah 4 dari 5</div>
                    <h3 class="step-heading">Pilih Notifikasi yang Ingin Diaktifkan</h3>
                    <p class="step-desc">Kami akan mengirimkan pengingat dan informasi penting lewat kanal yang Anda pilih.</p>

                    @php
                        $notifWa = old('notif_whatsapp', $registration->notif_whatsapp ?? true);
                        $notifEmail = old('notif_email', $registration->notif_email ?? true);
                        $notifSms = old('notif_sms', $registration->notif_sms ?? false);
                    @endphp

                    <div class="notif-option {{ $notifWa ? 'active' : '' }}" id="notifWhatsapp">
                        <div class="notif-icon" style="background:#25D366;"><i class="fa-brands fa-whatsapp"></i></div>
                        <div>
                            <div class="notif-title">WhatsApp</div>
                            <div class="notif-desc">Notifikasi absensi, pembayaran, dan pengumuman.</div>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" name="notif_whatsapp" value="1" {{ $notifWa ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="notif-option {{ $notifEmail ? 'active' : '' }}" id="notifEmail">
                        <div class="notif-icon" style="background:#2f5ce0;"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <div class="notif-title">Email</div>
                            <div class="notif-desc">Ringkasan mingguan, invoice, dan laporan resmi.</div>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" name="notif_email" value="1" {{ $notifEmail ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="notif-option {{ $notifSms ? 'active' : '' }}" id="notifSms">
                        <div class="notif-icon" style="background:#5b6478;"><i class="fa-solid fa-comment-sms"></i></div>
                        <div>
                            <div class="notif-title">SMS</div>
                            <div class="notif-desc">Notifikasi darurat saat jaringan internet terbatas.</div>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" name="notif_sms" value="1" {{ $notifSms ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- STEP 5 -->
                <div class="wizard-step d-none" data-step="5">
                    <div class="step-eyebrow">Langkah 5 dari 5</div>
                    <h3 class="step-heading">Buat Akun Anda</h3>
                    <p class="step-desc">Langkah terakhir. Data ini akan digunakan untuk masuk ke dashboard EduPulse Anda.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Email Login <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email"
                                value="{{ old('email', $registration->email ?? '') }}"
                                placeholder="nama@sekolah.sch.id" required autocomplete="username">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Handphone / WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="no_hp"
                                value="{{ old('no_hp', $registration->no_hp ?? '') }}"
                                placeholder="08xxxxxxxxxx" required autocomplete="tel">
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <label class="form-label">Buat Password <span class="text-danger">*</span></label>
                            <div class="input-group-password">
                                <input type="password" class="form-control pe-5" name="password" id="passwordInput"
                                    placeholder="Minimal 8 karakter" minlength="8" required autocomplete="new-password">
                                <button type="button" class="btn-toggle-password" data-target="passwordInput" aria-label="Toggle password visibility">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group-password">
                                <input type="password" class="form-control pe-5" name="password_confirmation" id="passwordConfirmInput"
                                    placeholder="Ulangi password" minlength="8" required autocomplete="new-password">
                                <button type="button" class="btn-toggle-password" data-target="passwordConfirmInput" aria-label="Toggle password confirmation visibility">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <div class="text-danger small mt-1" id="passwordMismatchError" style="display:none;">
                                Password dan konfirmasi password tidak sama.
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agree_terms" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms" style="font-size:.87rem;color:var(--muted);">
                                    Saya menyetujui <a href="javascript:void(0)" class="text-decoration-underline" onclick="alert('Syarat & Ketentuan berlaku untuk penggunaan masa uji coba EduPulse selama 14 hari.')">Syarat &amp; Ketentuan</a> dan <a href="javascript:void(0)" class="text-decoration-underline" onclick="alert('Privasi dan data sekolah Anda dijaga keamanannya sesuai standar enkripsi.')">Kebijakan Privasi</a> EduPulse.
                                </label>
                            </div>
                            <div class="text-danger small mt-1" id="termsError" style="display:none;">
                                Anda harus menyetujui Syarat &amp; Ketentuan untuk melanjutkan.
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
                <p class="step-desc">Kami telah menyiapkan akun trial Anda. Sedang mengalihkan Anda ke Dashboard...</p>
                <a href="{{ route('dashboard') }}" class="btn btn-brand">Masuk ke Dashboard<i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const totalSteps = 5;
        let currentStep = {{ min(5, max(1, (int)($registration->current_step ?? 1))) }};

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
        const passwordMismatchError = document.getElementById('passwordMismatchError');
        const termsError = document.getElementById('termsError');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

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

        // Allow navigation by clicking on completed step nodes
        stepNodes.forEach(node => {
            node.addEventListener('click', () => {
                const targetStep = parseInt(node.dataset.step);
                if (targetStep < currentStep) {
                    currentStep = targetStep;
                    renderStep();
                }
            });
        });

        function validateStep(step) {
            clearServerErrors(step);

            if (step === 1) {
                const required = document.querySelectorAll('.wizard-step[data-step="1"] [required]');
                let valid = true;
                required.forEach(f => {
                    if (!f.checkValidity()) {
                        f.reportValidity();
                        f.classList.add('is-invalid');
                        valid = false;
                    }
                });

                const jenjangChecked = document.querySelectorAll('input[name="education_level_id[]"]:checked');
                if (jenjangChecked.length === 0) {
                    jenjangError.style.display = 'block';
                    valid = false;
                } else {
                    jenjangError.style.display = 'none';
                }
                return valid;
            }

            if (step === 2) {
                const checked = document.querySelectorAll('input[name="modul[]"]:checked');
                if (checked.length === 0) {
                    moduleError.style.display = 'block';
                    return false;
                }
                moduleError.style.display = 'none';
                return true;
            }

            if (step === 3) {
                const checked = document.querySelectorAll('input[name="role[]"]:checked');
                if (checked.length === 0) {
                    roleError.style.display = 'block';
                    return false;
                }
                roleError.style.display = 'none';
                return true;
            }

            if (step === 4) {
                return true;
            }

            if (step === 5) {
                const required = document.querySelectorAll('.wizard-step[data-step="5"] [required]');
                let valid = true;
                required.forEach(f => {
                    if (!f.checkValidity()) {
                        f.reportValidity();
                        f.classList.add('is-invalid');
                        valid = false;
                    }
                });

                const pw = form.password.value;
                const pwc = form.password_confirmation.value;

                if (pw && pwc && pw !== pwc) {
                    passwordMismatchError.style.display = 'block';
                    form.password_confirmation.classList.add('is-invalid');
                    valid = false;
                } else {
                    passwordMismatchError.style.display = 'none';
                }

                const agree = document.getElementById('agreeTerms');
                if (!agree.checked) {
                    termsError.style.display = 'block';
                    agree.classList.add('is-invalid');
                    valid = false;
                } else {
                    termsError.style.display = 'none';
                }

                return valid;
            }

            return true;
        }

        function clearServerErrors(step) {
            document.querySelectorAll(`.wizard-step[data-step="${step}"] .server-error`).forEach(el => el.remove());
            document.querySelectorAll(`.wizard-step[data-step="${step}"] .is-invalid`).forEach(el => el.classList.remove('is-invalid'));
            if (step === 1 && jenjangError) jenjangError.style.display = 'none';
            if (step === 2 && moduleError) moduleError.style.display = 'none';
            if (step === 3 && roleError) roleError.style.display = 'none';
            if (step === 5) {
                if (passwordMismatchError) passwordMismatchError.style.display = 'none';
                if (termsError) termsError.style.display = 'none';
            }
        }

        function showServerErrors(step, errors) {
            Object.entries(errors).forEach(([field, messages]) => {
                const cleanField = field.replace(/\.\d+$/, '');
                const input = form.querySelector(
                    `.wizard-step[data-step="${step}"] [name="${cleanField}"], .wizard-step[data-step="${step}"] [name="${cleanField}[]"]`
                );
                const msg = document.createElement('div');
                msg.className = 'server-error text-danger small mt-1';
                msg.textContent = messages[0];

                if (cleanField === 'education_level_id') {
                    jenjangError.textContent = messages[0];
                    jenjangError.style.display = 'block';
                } else if (cleanField === 'modul') {
                    moduleError.textContent = messages[0];
                    moduleError.style.display = 'block';
                } else if (cleanField === 'role') {
                    roleError.textContent = messages[0];
                    roleError.style.display = 'block';
                } else if (cleanField === 'password_confirmation' || cleanField === 'password') {
                    input?.classList.add('is-invalid');
                    input?.closest('.col-12, .col-md-6, .col-md-4, .col-md-8')?.appendChild(msg);
                } else if (input) {
                    input.classList.add('is-invalid');
                    input.closest('.col-12, .col-md-6, .col-md-4, .col-md-8')?.appendChild(msg);
                } else {
                    document.querySelector(`.wizard-step[data-step="${step}"] .step-desc`)?.insertAdjacentElement('afterend', msg);
                }
            });
        }

        function setButtonLoading(btn, loading, loadingText) {
            btn.disabled = loading;
            btn.dataset.originalText = btn.dataset.originalText || btn.innerHTML;
            btn.innerHTML = loading
                ? `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`
                : btn.dataset.originalText;
        }

        async function saveStep(step) {
            clearServerErrors(step);
            const stepEl = document.querySelector(`.wizard-step[data-step="${step}"]`);
            const formData = new FormData();

            stepEl.querySelectorAll('input, select, textarea').forEach(field => {
                if (field.type === 'checkbox') {
                    if (field.name.endsWith('[]')) {
                        if (field.checked) formData.append(field.name, field.value);
                    } else {
                        formData.append(field.name, field.checked ? '1' : '0');
                    }
                } else if (field.type === 'radio') {
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
                    const data = await res.json();
                    showServerErrors(step, data.errors || {});
                    return false;
                }
                if (!res.ok) {
                    alert('Terjadi kesalahan pada server (Status ' + res.status + '). Silakan coba lagi.');
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
            setButtonLoading(btnSubmit, true, 'Memproses Akun...');

            const formData = new FormData(form);

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
                    const data = await res.json();
                    showServerErrors(5, data.errors || {});
                    setButtonLoading(btnSubmit, false);
                    isSubmitting = false;
                    return;
                }
                if (!res.ok) {
                    alert('Terjadi kesalahan pada server saat memproses akun. Silakan coba lagi.');
                    setButtonLoading(btnSubmit, false);
                    isSubmitting = false;
                    return;
                }

                const data = await res.json();
                form.classList.add('d-none');
                document.getElementById('stepsTrack').style.display = 'none';
                successState.classList.remove('d-none');

                setTimeout(() => {
                    window.location.href = data.redirect || '{{ url("/") }}';
                }, 1800);
            } catch (err) {
                alert('Gagal terhubung ke server. Periksa koneksi internet Anda.');
                setButtonLoading(btnSubmit, false);
                isSubmitting = false;
            }
        });

        // Notification card active-state toggle & whole card click handling
        ['notifWhatsapp', 'notifEmail', 'notifSms'].forEach(id => {
            const wrap = document.getElementById(id);
            if (!wrap) return;
            const input = wrap.querySelector('input[type="checkbox"]');

            wrap.addEventListener('click', (e) => {
                if (e.target !== input) {
                    input.checked = !input.checked;
                    input.dispatchEvent(new Event('change'));
                }
            });

            input.addEventListener('change', () => {
                wrap.classList.toggle('active', input.checked);
            });
        });

        // Toggle password visibility
        document.querySelectorAll('.btn-toggle-password').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.dataset.target;
                const input = document.getElementById(targetId);
                if (!input) return;
                const icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Auto clear is-invalid on input
        form.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('input', () => {
                el.classList.remove('is-invalid');
                const err = el.closest('.col-12, .col-md-6, .col-md-4, .col-md-8')?.querySelector('.server-error');
                if (err) err.remove();
            });
            el.addEventListener('change', () => {
                el.classList.remove('is-invalid');
                const err = el.closest('.col-12, .col-md-6, .col-md-4, .col-md-8')?.querySelector('.server-error');
                if (err) err.remove();
            });
        });

        renderStep();
    </script>
</body>

</html>
