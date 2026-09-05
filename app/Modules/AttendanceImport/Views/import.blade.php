@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import Data Fingerprint</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Import Form -->
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('attendance.import.process') }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          id="import-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">File Fingerprint <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".csv,.txt,.xlsx,.xls,.json" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Format yang didukung: CSV, Excel (.xlsx, .xls), JSON. 
                                Maksimal 10MB.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="machine_id" class="form-label">ID Mesin Fingerprint</label>
                            <input type="text" class="form-control @error('machine_id') is-invalid @enderror" 
                                   id="machine_id" name="machine_id" 
                                   placeholder="Contoh: FPM-01" value="{{ old('machine_id') }}">
                            @error('machine_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="import-btn">
                                <span class="material-symbols-outlined align-middle">upload</span>
                                Import Data
                            </button>
                            <button type="button" class="btn btn-info" id="preview-btn">
                                <span class="material-symbols-outlined align-middle">preview</span>
                                Preview
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h6 class="mb-2">📌 Panduan Format File</h6>
                        <p class="mb-1">File harus memiliki minimal kolom:</p>
                        <ul class="mb-0">
                            <li><strong>NIK / Employee ID</strong> - Kode karyawan</li>
                            <li><strong>Tanggal / Waktu</strong> - Tanggal dan jam scan</li>
                        </ul>
                        <hr class="my-2">
                        <p class="mb-0 small">Contoh format CSV:</p>
                        <code>
                            employee_id,datetime,machine_id,status<br>
                            EMP001,2026-01-15 07:05:30,FPM-01,IN<br>
                            EMP001,2026-01-15 15:30:00,FPM-01,OUT
                        </code>
                    </div>
                </div>
            </div>

            <!-- Preview Result -->
            <div id="preview-result" style="display:none;" class="mt-4">
                <h6>Preview Data</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="preview-table">
                        <thead id="preview-head"></thead>
                        <tbody id="preview-body"></tbody>
                    </table>
                </div>
                <div id="preview-info"></div>
            </div>

            <!-- Recent Imports -->
            @if($batches->count() > 0)
                <div class="mt-4">
                    <h6 class="border-bottom pb-2">Riwayat Import</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Batch Code</th>
                                    <th>File</th>
                                    <th>Machine</th>
                                    <th>Total</th>
                                    <th>Processed</th>
                                    <th>Failed</th>
                                    <th>Status</th>
                                    <th>Import By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batches as $batch)
                                    <tr>
                                        <td>{{ $batch->batch_code }}</td>
                                        <td>{{ $batch->file_name }}</td>
                                        <td>{{ $batch->machine_source ?? '-' }}</td>
                                        <td>{{ $batch->total_records }}</td>
                                        <td>{{ $batch->processed_records }}</td>
                                        <td>{{ $batch->failed_records }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'uploaded' => 'secondary',
                                                    'processing' => 'warning',
                                                    'completed' => 'success',
                                                    'partial' => 'info',
                                                    'failed' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$batch->status] ?? 'secondary' }}">
                                                {{ ucfirst($batch->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $batch->importedBy?->name ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" 
                                                    data-toggle="ajax-modal"
                                                    data-url="{{ route('attendance.import.show', $batch->id) }}"
                                                    data-title="Detail Import">
                                                <span class="material-symbols-outlined">visibility</span>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-button"
                                                    data-url="{{ route('attendance.import.destroy', $batch->id) }}"
                                                    data-confirm-delete="true">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Preview file
    $('#preview-btn').click(function() {
        var formData = new FormData($('#import-form')[0]);
        
        $.ajax({
            url: '{{ route('attendance.import.preview') }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    displayPreview(response.data);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Gagal preview data. ' + xhr.responseJSON?.message || '');
            }
        });
    });

    function displayPreview(data) {
        var $result = $('#preview-result');
        var $head = $('#preview-head');
        var $body = $('#preview-body');
        var $info = $('#preview-info');

        // Display headers
        var headers = data.headers || [];
        var headerHtml = '<tr>';
        headers.forEach(function(header) {
            var isMapped = Object.values(data.mapping || {}).includes(header);
            headerHtml += '<th class="' + (isMapped ? 'table-success' : '') + '">' + 
                          header + (isMapped ? ' ✅' : '') + '</th>';
        });
        headerHtml += '</tr>';
        $head.html(headerHtml);

        // Display preview data
        var previewData = data.preview || [];
        var bodyHtml = '';
        previewData.forEach(function(row) {
            bodyHtml += '<tr>';
            headers.forEach(function(header) {
                bodyHtml += '<td>' + (row[header] || '') + '</td>';
            });
            bodyHtml += '</tr>';
        });
        $body.html(bodyHtml);

        // Display info
        var infoHtml = '<div class="alert alert-info mt-2">';
        infoHtml += '<strong>Total Data:</strong> ' + data.total_rows + ' baris<br>';
        infoHtml += '<strong>Format Terdeteksi:</strong> ' + data.detected_format + '<br>';
        infoHtml += '<strong>Mapping Kolom:</strong><br>';
        for (var key in data.mapping) {
            infoHtml += '&nbsp;&nbsp;' + key + ' → ' + (data.mapping[key] || '(not found)') + '<br>';
        }
        infoHtml += '</div>';
        $info.html(infoHtml);

        $result.show();
    }
});
</script>
@endpush