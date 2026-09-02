<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <h6 class="border-bottom pb-2 mb-3">Informasi Dasar</h6>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="40%">NIK</th>
                    <td>{{ $employee->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>{{ $employee->name }}</td>
                </tr>
                <tr>
                    <th>Nama Panggilan</th>
                    <td>{{ $employee->nick_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $employee->gender_label }}</td>
                </tr>
                <tr>
                    <th>Tempat, Tanggal Lahir</th>
                    <td>{{ $employee->birth_place ?? '-' }}, {{ $employee->birth_date ? $employee->birth_date->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>Usia</th>
                    <td>{{ $employee->age ?? '-' }} tahun</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $employee->address ?? '-' }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $employee->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $employee->email ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <h6 class="border-bottom pb-2 mb-3">Status Kepegawaian</h6>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="40%">Departemen</th>
                    <td>{{ $employee->department->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $employee->position->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Atasan Langsung</th>
                    <td>{{ $employee->supervisor->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Bergabung</th>
                    <td>{{ $employee->join_date ? $employee->join_date->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Resign</th>
                    <td>{{ $employee->resign_date ? $employee->resign_date->format('d/m/Y') : 'Aktif' }}</td>
                </tr>
                <tr>
                    <th>Status Karyawan</th>
                    <td><span class="badge bg-{{ $employee->employment_status == 'tetap' ? 'primary' : ($employee->employment_status == 'kontrak' ? 'warning' : ($employee->employment_status == 'probation' ? 'info' : 'secondary')) }}">{{ $employee->employment_status_label }}</span></td>
                </tr>
                <tr>
                    <th>Masa Kerja</th>
                    <td>{{ number_format($employee->years_of_service ?? 0, 1) }} tahun</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $employee->is_active_employee ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>' }}</td>
                </tr>
            </table>
        </div>

        <div class="col-12">
            <h6 class="border-bottom pb-2 mb-3">Data Bank & BPJS</h6>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="15%">Bank</th>
                    <td width="18%">{{ $employee->bank_name ?? '-' }}</td>
                    <th width="15%">No. Rekening</th>
                    <td width="18%">{{ $employee->bank_account_number ?? '-' }}</td>
                    <th width="15%">Nama Pemilik</th>
                    <td width="19%">{{ $employee->bank_account_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NPWP</th>
                    <td>{{ $employee->npwp ?? '-' }}</td>
                    <th>BPJS TK</th>
                    <td>{{ $employee->bpjs_ketenagakerjaan ?? '-' }}</td>
                    <th>BPJS Kes</th>
                    <td>{{ $employee->bpjs_kesehatan ?? '-' }}</td>
                </tr>
            </table>
        </div>

        @if($employee->subordinates->count() > 0)
            <div class="col-12">
                <h6 class="border-bottom pb-2 mb-3">Bawahan</h6>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->subordinates as $subordinate)
                            <tr>
                                <td>{{ $subordinate->nik }}</td>
                                <td>{{ $subordinate->name }}</td>
                                <td>{{ $subordinate->position->name ?? '-' }}</td>
                                <td>{{ $subordinate->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>