@php
    $isEdit = $parent->exists;
    $v = fn($key, $default = null) => old($key, $parent->{$key} ?? $default);
    $dateVal = function ($key) use ($v) {
        $val = $v($key);
        return $val instanceof \DateTimeInterface ? $val->format('Y-m-d') : $val;
    };
    $bool = function ($key, $default = false) use ($isEdit, $parent) {
        if (old($key) !== null) return (bool) old($key);
        if ($isEdit) return (bool) $parent->{$key};
        return $default;
    };
@endphp

<div class="row g-4">

    {{-- Identitas Pribadi --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Identitas Pribadi</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Organisasi <span class="text-danger">*</span></label>
                    <select name="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Organisasi --</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}"
                                @selected((int) $v('organization_id', auth()->user()?->organization_id) === $org->id)>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organization_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ $v('name') }}"
                           class="form-control @error('name') is-invalid @enderror" maxlength="150" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" value="{{ $v('nik') }}"
                               class="form-control @error('nik') is-invalid @enderror"
                               maxlength="16" inputmode="numeric" placeholder="16 digit">
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="L" @selected($v('gender') === 'L')>Laki-laki</option>
                            <option value="P" @selected($v('gender') === 'P')>Perempuan</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ $v('birth_place') }}"
                               class="form-control @error('birth_place') is-invalid @enderror" maxlength="100">
                        @error('birth_place') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tahun Lahir</label>
                        <input type="number" name="birth_year" value="{{ $v('birth_year') }}"
                               class="form-control @error('birth_year') is-invalid @enderror"
                               min="1900" max="{{ date('Y') }}">
                        @error('birth_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $dateVal('birth_date') }}"
                               class="form-control @error('birth_date') is-invalid @enderror">
                        @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agama</label>
                        <select name="religion" class="form-select @error('religion') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $r)
                                <option value="{{ $r }}" @selected($v('religion') === $r)>{{ $r }}</option>
                            @endforeach
                        </select>
                        @error('religion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kewarganegaraan</label>
                        <input type="text" name="nationality" value="{{ $v('nationality', 'IDN') }}"
                               class="form-control @error('nationality') is-invalid @enderror" maxlength="3">
                        @error('nationality') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hubungan & Kontak --}}
    <div class="col-lg-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Hubungan dengan Siswa</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hubungan <span class="text-danger">*</span></label>
                        <select name="relation" class="form-select @error('relation') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['father'=>'Ayah','mother'=>'Ibu','guardian'=>'Wali','other'=>'Lainnya'] as $val => $label)
                                <option value="{{ $val }}" @selected($v('relation') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('relation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Keterangan Hubungan</label>
                        <input type="text" name="relation_detail" value="{{ $v('relation_detail') }}"
                               class="form-control @error('relation_detail') is-invalid @enderror"
                               placeholder="cth: Ayah Kandung, Ibu Tiri" maxlength="50">
                        @error('relation_detail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-check">
                    <input type="hidden" name="is_biological" value="0">
                    <input class="form-check-input" type="checkbox" name="is_biological" value="1"
                           id="is_biological" @checked($bool('is_biological', true))>
                    <label class="form-check-label" for="is_biological">Orang Tua Kandung</label>
                </div>
                <div class="form-check">
                    <input type="hidden" name="is_primary_contact" value="0">
                    <input class="form-check-input" type="checkbox" name="is_primary_contact" value="1"
                           id="is_primary_contact" @checked($bool('is_primary_contact'))>
                    <label class="form-check-label" for="is_primary_contact">Kontak Utama</label>
                </div>
                <div class="form-check">
                    <input type="hidden" name="lives_with_student" value="0">
                    <input class="form-check-input" type="checkbox" name="lives_with_student" value="1"
                           id="lives_with_student" @checked($bool('lives_with_student'))>
                    <label class="form-check-label" for="lives_with_student">Tinggal Serumah dengan Siswa</label>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Kontak</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. HP <span class="text-danger">*</span></label>
                        <input type="text" name="phone" value="{{ $v('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror" maxlength="20" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ $v('whatsapp') }}"
                               class="form-control @error('whatsapp') is-invalid @enderror" maxlength="20">
                        @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telepon Rumah</label>
                        <input type="text" name="home_phone" value="{{ $v('home_phone') }}"
                               class="form-control @error('home_phone') is-invalid @enderror" maxlength="20">
                        @error('home_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $v('email') }}"
                               class="form-control @error('email') is-invalid @enderror" maxlength="100">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alamat --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Alamat</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address" rows="2"
                              class="form-control @error('address') is-invalid @enderror">{{ $v('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Jalan / Gang</label>
                    <textarea name="address_line" rows="2"
                              class="form-control @error('address_line') is-invalid @enderror">{{ $v('address_line') }}</textarea>
                    @error('address_line') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">RT</label>
                        <input type="text" name="rt" value="{{ $v('rt') }}" class="form-control" maxlength="5">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">RW</label>
                        <input type="text" name="rw" value="{{ $v('rw') }}" class="form-control" maxlength="5">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Desa / Kelurahan</label>
                        <input type="text" name="village" value="{{ $v('village') }}" class="form-control" maxlength="100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="district" value="{{ $v('district') }}" class="form-control" maxlength="100">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kabupaten / Kota</label>
                        <input type="text" name="city" value="{{ $v('city') }}" class="form-control" maxlength="100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="province" value="{{ $v('province') }}" class="form-control" maxlength="100">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ $v('postal_code') }}" class="form-control" maxlength="10">
                </div>
            </div>
        </div>
    </div>

    {{-- Sosial-Ekonomi --}}
    <div class="col-lg-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Sosial-Ekonomi</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Pendidikan Terakhir</label>
                    <select name="education_level" class="form-select @error('education_level') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        @foreach(['Tidak Sekolah','SD','SMP','SMA/SMK','D1-D3','S1','S2','S3'] as $edu)
                            <option value="{{ $edu }}" @selected($v('education_level') === $edu)>{{ $edu }}</option>
                        @endforeach
                    </select>
                    @error('education_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="occupation" value="{{ $v('occupation') }}"
                               class="form-control @error('occupation') is-invalid @enderror" maxlength="100">
                        @error('occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penghasilan / Bulan</label>
                        <select name="income_range" class="form-select @error('income_range') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="< 1 juta" @selected($v('income_range') === '< 1 juta')>Kurang dari 1 juta</option>
                            <option value="1 - 3 juta" @selected($v('income_range') === '1 - 3 juta')>1 - 3 juta</option>
                            <option value="3 - 5 juta" @selected($v('income_range') === '3 - 5 juta')>3 - 5 juta</option>
                            <option value="5 - 10 juta" @selected($v('income_range') === '5 - 10 juta')>5 - 10 juta</option>
                            <option value="> 10 juta" @selected($v('income_range') === '> 10 juta')>Lebih dari 10 juta</option>
                        </select>
                        @error('income_range') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Instansi / Tempat Kerja</label>
                        <input type="text" name="workplace" value="{{ $v('workplace') }}" class="form-control" maxlength="150">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="position" value="{{ $v('position') }}" class="form-control" maxlength="100">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Status & Kebutuhan Khusus</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Kebutuhan Khusus</label>
                    <textarea name="special_needs" rows="2"
                              class="form-control @error('special_needs') is-invalid @enderror">{{ $v('special_needs') }}</textarea>
                    @error('special_needs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-2">
                    <input type="hidden" name="is_alive" value="0">
                    <input class="form-check-input" type="checkbox" name="is_alive" value="1"
                           id="is_alive" @checked($bool('is_alive', true))>
                    <label class="form-check-label" for="is_alive">Masih Hidup</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Meninggal</label>
                    <input type="date" name="death_date" id="death_date" value="{{ $dateVal('death_date') }}"
                           class="form-control @error('death_date') is-invalid @enderror">
                    @error('death_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-2">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           id="is_active" @checked($bool('is_active', true))>
                    <label class="form-check-label" for="is_active">Data Aktif</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Kartu Keluarga</label>
                    <input type="text" name="family_card_number" value="{{ $v('family_card_number') }}"
                           class="form-control @error('family_card_number') is-invalid @enderror"
                           maxlength="16" inputmode="numeric">
                    @error('family_card_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-0">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="2" class="form-control">{{ $v('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alive = document.getElementById('is_alive');
        const death = document.getElementById('death_date');
        if (!alive || !death) return;

        const toggle = () => {
            death.disabled = alive.checked;
            if (alive.checked) death.value = '';
        };
        alive.addEventListener('change', toggle);
        toggle();
    });
</script>
@endpush