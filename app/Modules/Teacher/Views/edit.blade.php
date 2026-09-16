{{ Form::model($teacher, ['method' => 'PUT', 'route' => ['teacher.update', $teacher->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    {{-- Organization --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Organisasi</label>
    <select name="organization_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <option value="">Pilih Organisasi</option>
        {{-- Contoh data: @foreach($organizations as $org) --}}
        <option value="1">Sekolah Contoh</option>
    </select>
</div>

{{-- User (opsional) --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Akun Pengguna</label>
    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tidak Dihubungkan</option>
        {{-- Contoh data: @foreach($users as $user) --}}
        <option value="1">admin@example.com</option>
    </select>
</div>

{{-- NIP --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
    <input type="text" name="nip" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" maxlength="50" placeholder="Masukkan NIP">
</div>

{{-- Nama Lengkap --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" maxlength="150" required placeholder="Nama lengkap guru">
</div>

{{-- Spesialisasi --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi / Bidang Keahlian</label>
    <input type="text" name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" maxlength="100" placeholder="Contoh: Matematika, Bahasa Indonesia">
</div>

{{-- Tanggal Bergabung --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bergabung</label>
    <input type="date" name="join_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

{{-- Status --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="ACTIVE" selected>Aktif</option>
        <option value="RESIGNED">Mengundurkan Diri</option>
        <option value="RETIRED">Pensiun</option>
    </select>
</div>
{{ Form::close() }}
<!-- /.row -->