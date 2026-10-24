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

        /* STEP 2: Module Picker & Billing Styles */
        .module-category-nav {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            scrollbar-width: thin;
        }
        .module-category-nav::-webkit-scrollbar {
            height: 4px;
        }
        .module-category-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .category-pill {
            white-space: nowrap;
            padding: 0.4rem 0.85rem;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .category-pill:hover {
            border-color: #2f5ce0;
            color: #2f5ce0;
            background: #eff6ff;
        }
        .category-pill.active {
            background: #2f5ce0;
            color: #fff;
            border-color: #2f5ce0;
            box-shadow: 0 4px 12px rgba(47, 92, 224, 0.25);
        }

        .module-scroll-container {
            max-height: 450px;
            overflow-y: auto;
            padding-right: 6px;
            margin-bottom: 1.25rem;
            scrollbar-width: thin;
        }
        .module-scroll-container::-webkit-scrollbar {
            width: 6px;
        }
        .module-scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }

        .module-option {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-height: 140px;
            padding: 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .module-option:hover {
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(47, 92, 224, 0.08);
        }
        .module-option:has(input:checked) {
            border-color: #2f5ce0;
            background: #f0f7ff;
            box-shadow: 0 6px 18px rgba(47, 92, 224, 0.12);
        }
        .module-option .check-mark {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1.5px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: transparent;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .module-option input:checked ~ .check-mark {
            background: #2f5ce0;
            border-color: #2f5ce0;
            color: #ffffff;
        }

        .mod-price-pill {
            display: inline-flex;
            align-items: baseline;
            gap: 4px;
            padding: 0.3rem 0.65rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 700;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .module-option:has(input:checked) .mod-price-pill {
            background: #e0edff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        /* Sticky / Prominent Billing Summary Card */
        .billing-summary-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .billing-summary-card .badge-trial {
            background: rgba(34, 197, 94, 0.18);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.35);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 50px;
        }
        .billing-summary-card .amount-display {
            font-size: 1.75rem;
            font-weight: 800;
            color: #38bdf8;
            line-height: 1;
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
                                    <span>SMA / SMK / Sederajat</span>
                                </label>
                                <label class="chip-option">
                                    <input type="checkbox" name="education_level_id[]" value="universitas"
                                        {{ in_array('universitas', $savedLevels) ? 'checked' : '' }}>
                                    <span>Perguruan Tinggi / Universitas</span>
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
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                        <h3 class="step-heading mb-0">Pilih Modul yang Anda Butuhkan</h3>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-cube me-1"></i> {{ isset($modules) ? $modules->count() : 0 }} Modul Siap Digunakan
                        </span>
                    </div>
                    <p class="step-desc mb-3">Centang modul yang ingin Anda aktifkan. Setiap modul memiliki harga lisensi bulanan yang otomatis dihitung di bawah sebagai acuan tagihan setelah masa trial gratis berakhir.</p>

                    <!-- Toolbar: Pencarian & Aksi Cepat -->
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" id="moduleSearch" class="form-control border-start-0 ps-0" placeholder="Ketik nama modul, tabel, atau fitur (misal: Rapor, SPP, Presensi, CBT)...">
                                <button type="button" class="btn btn-outline-secondary border" id="btnClearSearch" style="display:none;"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <button type="button" class="btn btn-sm btn-outline-primary me-1" id="btnSelectAllVisible">
                                <i class="fa-solid fa-check-double me-1"></i>Pilih Semua di Kategori
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnUnselectAllVisible">
                                <i class="fa-solid fa-rotate-left me-1"></i>Reset
                            </button>
                        </div>
                    </div>

                    <!-- Category Filter Pills -->
                    @php
                        $categories = isset($modules) ? $modules->pluck('category')->filter()->unique()->values() : collect();
                        $featuredCodes = ['akademik', 'kesiswaan', 'keuangan', 'absensi', 'cbt', 'perpustakaan', 'pesantren', 'kepegawaian', 'parent_portal', 'analitik_ai', 'sarpras', 'layanan_kesehatan'];
                    @endphp
                    <div class="module-category-nav" id="categoryNav">
                        <button type="button" class="category-pill active" data-category="featured">
                            <i class="fa-solid fa-star text-warning me-1"></i>Modul Unggulan (12)
                        </button>
                        <button type="button" class="category-pill" data-category="all">
                            <i class="fa-solid fa-layer-group me-1"></i>Semua Modul ({{ isset($modules) ? $modules->count() : 0 }})
                        </button>
                        @foreach($categories as $cat)
                            @php
                                $catCount = $modules->where('category', $cat)->count();
                            @endphp
                            <button type="button" class="category-pill" data-category="{{ $cat }}">
                                {{ $cat }} ({{ $catCount }})
                            </button>
                        @endforeach
                    </div>

                    <!-- Scrollable Module Cards Container -->
                    <div class="module-scroll-container">
                        <div class="row g-3" id="moduleGrid">
                            @if(isset($modules) && $modules->count())
                                @foreach ($modules as $mod)
                                    @php
                                        $isFeatured = in_array(strtolower($mod->code), $featuredCodes);
                                        $isChecked = in_array(strtolower($mod->code), $savedModules) || in_array((string)$mod->id, $savedModules);
                                        $cat = $mod->category ?? 'Umum';
                                        $icon = $mod->icon ?: ($isFeatured ? 'fa-solid fa-star' : 'fa-solid fa-cube');
                                        $price = (float)$mod->price;
                                    @endphp
                                    <div class="col-md-6 module-item"
                                         data-category="{{ $cat }}"
                                         data-featured="{{ $isFeatured ? '1' : '0' }}"
                                         data-name="{{ strtolower($mod->name) }}"
                                         data-desc="{{ strtolower($mod->description ?? '') }}"
                                         data-code="{{ strtolower($mod->code) }}">
                                        <label class="module-option">
                                            <input type="checkbox" name="modul[]" value="{{ $mod->code }}"
                                                data-price="{{ $price }}"
                                                data-name="{{ $mod->name }}"
                                                data-code="{{ $mod->code }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <div>
                                                <div class="d-flex justify-content-between align-items-start mb-2 pe-4">
                                                    <div class="mod-icon mb-0 me-2 flex-shrink-0">
                                                        <i class="{{ $icon }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="mod-title">{{ $mod->name }}</div>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <span class="badge bg-light text-secondary border" style="font-size:0.7rem; font-weight:600;">
                                                                {{ $cat }}
                                                            </span>
                                                            @if($isFeatured)
                                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size:0.7rem;">
                                                                    <i class="fa-solid fa-star me-1 text-warning"></i>Populer
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mod-desc text-muted mb-2">
                                                    {{ $mod->description ?: 'Modul pengelolaan operasional sekolah terintegrasi.' }}
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-auto">
                                                <span class="text-muted small">Biaya Lisensi:</span>
                                                <div class="mod-price-pill">
                                                    <span>Rp {{ number_format($price, 0, ',', '.') }}</span>
                                                    <span class="text-muted fw-normal" style="font-size:0.75rem;">/ bln</span>
                                                </div>
                                            </div>
                                            <div class="check-mark"><i class="fa-solid fa-check"></i></div>
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 text-center py-4">
                                    <p class="text-muted">Tidak ada modul yang tersedia.</p>
                                </div>
                            @endif
                        </div>

                        <div id="noModuleMatch" class="text-center py-5 d-none">
                            <i class="fa-solid fa-box-open text-muted fs-1 mb-2"></i>
                            <h6 class="text-secondary fw-bold">Modul tidak ditemukan</h6>
                            <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau pilih kategori modul lainnya.</p>
                        </div>
                    </div>

                    <!-- Panel Total Tagihan Bulanan / Estimasi Biaya -->
                    <div class="billing-summary-card">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge-trial"><i class="fa-solid fa-gift me-1"></i>14 Hari Trial Gratis</span>
                                    <span class="badge bg-white bg-opacity-25 text-white" id="selectedCountBadge">0 Modul Dipilih</span>
                                </div>
                                <h5 class="fw-bold mb-0 text-white">Estimasi Tagihan Bulanan</h5>
                                <small class="text-white-50" style="font-size:0.8rem;">
                                    *Nilai ini merupakan acuan tagihan rutin jika Anda melanjutkan langganan setelah masa uji coba berakhir.
                                </small>
                            </div>
                            <div class="text-md-end">
                                <div class="text-white-50 small mb-1">Total Biaya Lisensi:</div>
                                <div class="d-flex align-items-baseline gap-1 justify-content-md-end">
                                    <span class="amount-display" id="totalMonthlyPrice">Rp 0</span>
                                    <span class="text-white-50 small">/ bulan</span>
                                </div>
                                <div class="text-success small fw-semibold mt-1">
                                    <i class="fa-solid fa-circle-check me-1"></i>Rp 0 Ditagihkan Selama Trial
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-text mt-2 text-danger fw-semibold" id="moduleError" style="display:none;">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>Pilih minimal satu modul untuk melanjutkan pendaftaran trial.
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

        // ==========================================
        // STEP 2: MODULE SELECTION, FILTER & PRICING
        // ==========================================
        const moduleSearchInput = document.getElementById('moduleSearch');
        const btnClearSearch = document.getElementById('btnClearSearch');
        const categoryNav = document.getElementById('categoryNav');
        const moduleItems = document.querySelectorAll('.module-item');
        const noModuleMatch = document.getElementById('noModuleMatch');
        const totalMonthlyPriceEl = document.getElementById('totalMonthlyPrice');
        const selectedCountBadge = document.getElementById('selectedCountBadge');
        const btnSelectAllVisible = document.getElementById('btnSelectAllVisible');
        const btnUnselectAllVisible = document.getElementById('btnUnselectAllVisible');

        let activeCategory = 'featured';

        // 1. Calculate & Update Total Billing Preview
        function updateModuleBillingSummary() {
            const checkedInputs = document.querySelectorAll('input[name="modul[]"]:checked');
            let total = 0;

            checkedInputs.forEach(input => {
                const price = parseFloat(input.dataset.price) || 0;
                total += price;
            });

            if (selectedCountBadge) {
                selectedCountBadge.textContent = `${checkedInputs.length} Modul Dipilih`;
            }

            if (totalMonthlyPriceEl) {
                totalMonthlyPriceEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            if (checkedInputs.length > 0 && moduleError) {
                moduleError.style.display = 'none';
            }
        }

        // 2. Filter Modules by Category & Search Keyword
        function filterModules() {
            const query = (moduleSearchInput?.value || '').trim().toLowerCase();
            let visibleCount = 0;

            if (btnClearSearch) {
                btnClearSearch.style.display = query ? 'block' : 'none';
            }

            moduleItems.forEach(item => {
                const cat = item.dataset.category || '';
                const isFeatured = item.dataset.featured === '1';
                const name = item.dataset.name || '';
                const desc = item.dataset.desc || '';
                const code = item.dataset.code || '';

                // Category match
                let matchCategory = false;
                if (activeCategory === 'all') {
                    matchCategory = true;
                } else if (activeCategory === 'featured') {
                    matchCategory = isFeatured;
                } else {
                    matchCategory = (cat === activeCategory);
                }

                // Search query match
                let matchQuery = true;
                if (query) {
                    matchQuery = name.includes(query) || desc.includes(query) || code.includes(query) || cat.toLowerCase().includes(query);
                }

                if (matchCategory && matchQuery) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            if (noModuleMatch) {
                noModuleMatch.classList.toggle('d-none', visibleCount > 0);
            }
        }

        // Category Pills Click Handler
        if (categoryNav) {
            categoryNav.addEventListener('click', (e) => {
                const pill = e.target.closest('.category-pill');
                if (!pill) return;

                categoryNav.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');

                activeCategory = pill.dataset.category || 'all';
                filterModules();
            });
        }

        // Search Input Handler
        if (moduleSearchInput) {
            moduleSearchInput.addEventListener('input', () => {
                filterModules();
            });
        }

        // Clear Search Button
        if (btnClearSearch) {
            btnClearSearch.addEventListener('click', () => {
                moduleSearchInput.value = '';
                filterModules();
                moduleSearchInput.focus();
            });
        }

        // Select All Visible Button
        if (btnSelectAllVisible) {
            btnSelectAllVisible.addEventListener('click', () => {
                moduleItems.forEach(item => {
                    if (!item.classList.contains('d-none')) {
                        const input = item.querySelector('input[name="modul[]"]');
                        if (input && !input.checked) {
                            input.checked = true;
                        }
                    }
                });
                updateModuleBillingSummary();
            });
        }

        // Unselect All Visible Button
        if (btnUnselectAllVisible) {
            btnUnselectAllVisible.addEventListener('click', () => {
                moduleItems.forEach(item => {
                    if (!item.classList.contains('d-none')) {
                        const input = item.querySelector('input[name="modul[]"]');
                        if (input && input.checked) {
                            input.checked = false;
                        }
                    }
                });
                updateModuleBillingSummary();
            });
        }

        // Checkbox change listener for all module options
        document.querySelectorAll('input[name="modul[]"]').forEach(input => {
            input.addEventListener('change', () => {
                updateModuleBillingSummary();
            });
        });

        // Initialize display & calculation
        filterModules();
        updateModuleBillingSummary();

        renderStep();
    </script>
</body>

</html>
