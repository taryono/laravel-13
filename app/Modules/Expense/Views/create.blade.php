 {{-- ============================================================ --}}
 {{-- 1. FORM EXPENSE (CREATE/EDIT)                                --}}
 {{-- ============================================================ --}}

 @if (isset($expense))
     {{ Form::model($expense, ['method' => 'PUT', 'route' => ['expense.update', $expense->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' => 'expenseForm']) }}
 @else
     {{ Form::open(['route' => 'expense.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'id' => 'expenseForm']) }}
 @endif

 @csrf
 @if (isset($expense))
     @method('PUT')
 @endif

 <div class="row g-3">

     {{-- ===== HEADER ===== --}}
     <div class="col-12">
         <h5 class="border-bottom pb-2">
             <i class="fas fa-receipt me-2"></i>
             {{ isset($expense) ? 'Edit Pengeluaran' : 'Tambah Pengeluaran Baru' }}
         </h5>
     </div>

     {{-- ===== INFO DASAR ===== --}}
     <div class="col-md-6">
         <label class="form-label">
             <i class="fas fa-hashtag me-1"></i> Nomor Pengeluaran <span class="text-danger">*</span>
         </label>
         <input type="text" name="expense_number" class="form-control"
             value="{{ old('expense_number', $expense->expense_number ?? generateExpenseNumber()) }}"
             placeholder="EXP-2024-001" required>
         @error('expense_number')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     <div class="col-md-6">
         <label class="form-label">
             <i class="fas fa-calendar-alt me-1"></i> Tanggal Pengeluaran <span class="text-danger">*</span>
         </label>
         <input type="date" name="expense_date" class="form-control"
             value="{{ old('expense_date', $expense->expense_date ?? date('Y-m-d')) }}" required>
         @error('expense_date')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     {{-- ===== VENDOR & KATEGORI ===== --}}
     <div class="col-md-6">
         <label class="form-label">
             <i class="fas fa-building me-1"></i> Vendor / Pemasok
         </label>
         <input type="text" name="vendor_name" class="form-control"
             value="{{ old('vendor_name', $expense->vendor_name ?? '') }}" placeholder="Nama vendor atau pemasok">
         @error('vendor_name')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     <div class="col-md-6">
         <label class="form-label">
             <i class="fas fa-tags me-1"></i> Kategori Pengeluaran <span class="text-danger">*</span>
         </label>
         <select name="expense_category_id" class="form-select" required>
             <option value="">-- Pilih Kategori --</option>
             @foreach ($categories as $category)
                 <option value="{{ $category->id }}"
                     {{ old('expense_category_id', $expense->expense_category_id ?? '') == $category->id ? 'selected' : '' }}>
                     {{ $category->code }} - {{ $category->name }}
                 </option>
             @endforeach
         </select>
         @error('expense_category_id')
             <small class="text-danger">{{ $message }}</small>
         @enderror

         {{-- Tombol tambah kategori cepat --}}
         <small class="text-muted">
             <a href="#" data-bs-toggle="modal" data-bs-target="#categoryModal" class="text-primary">
                 <i class="fas fa-plus-circle"></i> Tambah Kategori Baru
             </a>
         </small>
     </div>

     {{-- ===== DESKRIPSI ===== --}}
     <div class="col-12">
         <label class="form-label">
             <i class="fas fa-align-left me-1"></i> Deskripsi <span class="text-danger">*</span>
         </label>
         <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi pengeluaran" required>{{ old('description', $expense->description ?? '') }}</textarea>
         @error('description')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     {{-- ===== NOMINAL ===== --}}
     <div class="col-md-4">
         <label class="form-label">
             <i class="fas fa-money-bill-wave me-1"></i> Jumlah (Rp) <span class="text-danger">*</span>
         </label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" name="amount" class="form-control" step="0.01" min="0"
                 value="{{ old('amount', $expense->amount ?? 0) }}" placeholder="0" required id="expense_amount">
         </div>
         @error('amount')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     <div class="col-md-4">
         <label class="form-label">
             <i class="fas fa-percentage me-1"></i> Pajak (Rp)
         </label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" name="tax" class="form-control" step="0.01" min="0"
                 value="{{ old('tax', $expense->tax ?? 0) }}" placeholder="0" id="expense_tax">
         </div>
         @error('tax')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     <div class="col-md-4">
         <label class="form-label">
             <i class="fas fa-calculator me-1"></i> Total (Rp)
         </label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" name="total_amount" class="form-control" step="0.01" min="0"
                 value="{{ old('total_amount', $expense->total_amount ?? 0) }}" placeholder="0" id="expense_total"
                 readonly style="font-weight: bold; color: #2d2d5c;">
         </div>
         <small class="text-muted">Otomatis: Jumlah + Pajak</small>
         @error('total_amount')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     {{-- ===== PEMBAYARAN ===== --}}
     <div class="col-md-4">
         <label class="form-label">
             <i class="fas fa-credit-card me-1"></i> Metode Pembayaran
         </label>
         <select name="payment_method" class="form-select">
             <option value="">-- Pilih Metode --</option>
             <option value="cash"
                 {{ old('payment_method', $expense->payment_method ?? '') == 'cash' ? 'selected' : '' }}>
                 <i class="fas fa-money-bill"></i> Tunai
             </option>
             <option value="bank_transfer"
                 {{ old('payment_method', $expense->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>
                 <i class="fas fa-university"></i> Transfer Bank
             </option>
             <option value="check"
                 {{ old('payment_method', $expense->payment_method ?? '') == 'check' ? 'selected' : '' }}>
                 <i class="fas fa-file-invoice"></i> Cek
             </option>
         </select>
         @error('payment_method')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     <div class="col-md-4">
         <label class="form-label">
             <i class="fas fa-check-circle me-1"></i> Status Pembayaran
         </label>
         <select name="payment_status" class="form-select" required>
             <option value="unpaid"
                 {{ old('payment_status', $expense->payment_status ?? '') == 'unpaid' ? 'selected' : '' }}>
                 ⏳ Belum Dibayar
             </option>
             <option value="partial"
                 {{ old('payment_status', $expense->payment_status ?? '') == 'partial' ? 'selected' : '' }}>
                 🔄 Sebagian
             </option>
             <option value="paid"
                 {{ old('payment_status', $expense->payment_status ?? '') == 'paid' ? 'selected' : '' }}>
                 ✅ Lunas
             </option>
         </select>
         @error('payment_status')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     {{-- ===== JOURNAL ENTRY (Opsional) ===== --}}
     <div class="col-md-4">
         <label class="form-label">
             <i class="fas fa-book me-1"></i> Jurnal Entry
         </label>
         <select name="journal_entry_id" class="form-select">
             <option value="">-- Tidak ada jurnal --</option>
             @foreach ($journalEntries as $journal)
                 <option value="{{ $journal->id }}"
                     {{ old('journal_entry_id', $expense->journal_entry_id ?? '') == $journal->id ? 'selected' : '' }}>
                     {{ $journal->journal_number }} - {{ $journal->description }}
                 </option>
             @endforeach
         </select>
         @error('journal_entry_id')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     {{-- ===== ATTACHMENT ===== --}}
     <div class="col-md-6">
         <label class="form-label">
             <i class="fas fa-paperclip me-1"></i> Lampiran
         </label>
         <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
         @if (isset($expense) && $expense->attachment_path)
             <small class="text-muted">
                 <i class="fas fa-file"></i> File saat ini:
                 <a href="{{ asset('storage/' . $expense->attachment_path) }}" target="_blank">
                     {{ basename($expense->attachment_path) }}
                 </a>
             </small>
         @endif
         @error('attachment')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     <div class="col-md-6">
         <label class="form-label">
             <i class="fas fa-user-check me-1"></i> Disetujui Oleh
         </label>
         <select name="approved_by" class="form-select">
             <option value="">-- Pilih Approver --</option>
             @foreach ($approvers as $user)
                 <option value="{{ $user->id }}"
                     {{ old('approved_by', $expense->approved_by ?? '') == $user->id ? 'selected' : '' }}>
                     {{ $user->name }}
                 </option>
             @endforeach
         </select>
         @error('approved_by')
             <small class="text-danger">{{ $message }}</small>
         @enderror
     </div>

     {{-- ===== APPROVED AT ===== --}}
     @if (isset($expense) && $expense->approved_at)
         <div class="col-md-6">
             <label class="form-label">
                 <i class="fas fa-clock me-1"></i> Tanggal Disetujui
             </label>
             <input type="text" class="form-control" value="{{ $expense->approved_at->format('d/m/Y H:i') }}"
                 readonly>
         </div>
     @endif

     {{-- ===== FOOTER ===== --}}
     <div class="col-12">
         <div class="alert alert-info border-0 small">
             <i class="fas fa-info-circle me-2"></i>
             <strong>Info:</strong> Pengeluaran akan dicatat di jurnal umum setelah disetujui.
         </div>
     </div>

     {{-- ===== SUBMIT BUTTONS ===== --}}
     <div class="col-12">
         <button type="submit" class="btn btn-primary">
             <i class="fas fa-save me-2"></i>
             {{ isset($expense) ? 'Update Pengeluaran' : 'Simpan Pengeluaran' }}
         </button>
         <a href="{{ route('expense.index') }}" class="btn btn-secondary">
             <i class="fas fa-arrow-left me-2"></i> Kembali
         </a>
     </div>

 </div>

 {{ Form::close() }}

 {{-- ============================================================ --}}
 {{-- 2. MODAL UNTUK TAMBAH KATEGORI CEPAT                         --}}
 {{-- ============================================================ --}}
 <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">
                     <i class="fas fa-plus-circle me-2"></i> Tambah Kategori Baru
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
             </div>
             <form id="categoryForm" action="{{ route('expense-category.store') }}" method="POST">
                 @csrf
                 <div class="modal-body">
                     <div class="mb-3">
                         <label class="form-label">Kode <span class="text-danger">*</span></label>
                         <input type="text" name="code" class="form-control" placeholder="OPR" required>
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                         <input type="text" name="name" class="form-control" placeholder="Operasional"
                             required>
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Deskripsi</label>
                         <textarea name="description" class="form-control" rows="2"></textarea>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                     <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                 </div>
             </form>
         </div>
     </div>
 </div>

 {{-- ============================================================ --}}
 {{-- 3. JAVASCRIPT UNTUK AUTO-CALCULATE TOTAL                   --}}
 {{-- ============================================================ --}}
 @push('scripts')
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const amountInput = document.getElementById('expense_amount');
             const taxInput = document.getElementById('expense_tax');
             const totalInput = document.getElementById('expense_total');

             function calculateTotal() {
                 const amount = parseFloat(amountInput.value) || 0;
                 const tax = parseFloat(taxInput.value) || 0;
                 const total = amount + tax;
                 totalInput.value = total.toFixed(2);
             }

             amountInput.addEventListener('input', calculateTotal);
             taxInput.addEventListener('input', calculateTotal);

             // Trigger awal
             calculateTotal();

             // ===== FORM SUBMIT VIA AJAX =====
             const form = document.getElementById('expenseForm');
             if (form) {
                 form.addEventListener('submit', function(e) {
                     // Validasi tambahan
                     const category = document.querySelector('[name="expense_category_id"]');
                     if (!category.value) {
                         e.preventDefault();
                         alert('Silakan pilih kategori pengeluaran!');
                         category.focus();
                         return false;
                     }
                 });
             }

             // ===== SUBMIT KATEGORI VIA AJAX =====
             const categoryForm = document.getElementById('categoryForm');
             if (categoryForm) {
                 categoryForm.addEventListener('submit', function(e) {
                     e.preventDefault();

                     const formData = new FormData(this);

                     fetch(this.action, {
                             method: 'POST',
                             headers: {
                                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                     .content,
                                 'Accept': 'application/json'
                             },
                             body: formData
                         })
                         .then(response => response.json())
                         .then(data => {
                             if (data.success) {
                                 // Tambahkan opsi ke dropdown
                                 const select = document.querySelector('[name="expense_category_id"]');
                                 const option = document.createElement('option');
                                 option.value = data.category.id;
                                 option.textContent = data.category.code + ' - ' + data.category.name;
                                 select.appendChild(option);
                                 select.value = data.category.id;

                                 // Tutup modal
                                 const modal = bootstrap.Modal.getInstance(document.getElementById(
                                     'categoryModal'));
                                 modal.hide();

                                 // Reset form
                                 categoryForm.reset();

                                 alert('✅ Kategori berhasil ditambahkan!');
                             } else {
                                 alert('❌ Gagal: ' + data.message);
                             }
                         })
                         .catch(error => {
                             console.error('Error:', error);
                             alert('Terjadi kesalahan!');
                         });
                 });
             }
         });
     </script>
 @endpush
