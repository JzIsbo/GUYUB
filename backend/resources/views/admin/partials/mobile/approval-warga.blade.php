{{-- resources/views/admin/partials/mobile/approval-warga.blade.php --}}

<div class="p-3 space-y-3 max-w-full mx-auto">
    {{-- Hero Banner --}}
    <div class="relative bg-gradient-to-br from-[#065f46] via-[#047857] to-[#0f172a] rounded-2xl p-4 overflow-hidden shadow-lg">
        <div class="absolute -right-4 -bottom-4 text-white/[0.04] text-[6rem] rotate-12 pointer-events-none">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div class="space-y-2.5 relative z-10">
            <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-3 py-1">
                <i class="fa-solid fa-circle-check text-emerald-300 text-[10px]"></i>
                <span class="text-[10px] font-semibold text-emerald-200 tracking-widest uppercase">Persetujuan Warga</span>
            </div>
            <div>
                <h2 class="text-base font-extrabold text-white tracking-tight leading-tight">Persetujuan Registrasi</h2>
                <p class="text-emerald-200/70 text-[10px] mt-1 font-medium">Validasi warga baru yang mendaftar mandiri</p>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="relative w-full">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-magnifying-glass text-[10px]"></i></span>
        <input type="text" id="m-search-approval" placeholder="Cari nama, KK, blok..." class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all" onkeyup="filterApprovalMobile(this.value)">
    </div>

    {{-- Cards List --}}
    <div class="space-y-2">
        @forelse($list_registrasi ?? [] as $reg)
        <div class="approval-mobile-card bg-white rounded-xl border border-gray-100 p-3 shadow-sm"
             data-search="{{ strtolower($reg->nama_lengkap . ' ' . $reg->email . ' ' . $reg->nomor_kk . ' ' . $reg->blok_rumah) }}">
            <div class="flex justify-between items-start gap-2">
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-gray-800 text-[12px] truncate">{{ $reg->nama_lengkap }}</p>
                    <p class="text-[10px] text-gray-500 font-semibold mt-0.5">NIK: {{ $reg->nik }}</p>
                    <p class="text-[10px] text-gray-500 font-semibold">KK: {{ $reg->nomor_kk }}</p>
                    <p class="text-[10px] text-gray-500 font-semibold">Blok: {{ $reg->blok_rumah }}</p>
                    <p class="text-[9px] text-gray-400 mt-1">Umur: {{ $reg->umur }} thn ({{ $reg->status_keluarga }})</p>
                </div>
                <div class="flex flex-col items-end gap-1.5 shrink-0">
                    @if($reg->status === 'Pending')
                    <span class="px-2 py-0.5 rounded-full text-[8px] font-bold bg-yellow-100 text-yellow-600">PENDING</span>
                    @elseif($reg->status === 'Ditolak')
                    <span class="px-2 py-0.5 rounded-full text-[8px] font-bold bg-red-100 text-red-600">DITOLAK</span>
                    @else
                    <span class="px-2 py-0.5 rounded-full text-[8px] font-bold bg-green-100 text-green-600">AKTIF</span>
                    @endif

                    <div class="flex gap-1 mt-1">
                        @if($reg->foto_ktp)
                        <a href="{{ asset($reg->foto_ktp) }}" target="_blank" class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold" title="Lihat Foto KTP">
                            <i class="fa-solid fa-address-card text-[10px]"></i>
                        </a>
                        @endif

                        @if($reg->status === 'Pending')
                        <button onclick="quickApproveMobile({{ $reg->user_id }}, '{{ addslashes($reg->nama_lengkap) }}', 'Aktif')" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-650 flex items-center justify-center font-bold" title="Setujui"><i class="fa-solid fa-check text-[10px]"></i></button>
                        <button onclick="quickApproveMobile({{ $reg->user_id }}, '{{ addslashes($reg->nama_lengkap) }}', 'Ditolak')" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center font-bold" title="Tolak"><i class="fa-solid fa-xmark text-[10px]"></i></button>
                        @endif

                        <button onclick="editRegistrasiMobile({{ json_encode($reg) }})" class="w-7 h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center" title="Edit"><i class="fa-solid fa-pen text-[9px]"></i></button>
                        <button onclick="deleteRegistrasiMobile({{ $reg->user_id }}, '{{ addslashes($reg->nama_lengkap) }}')" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center" title="Hapus"><i class="fa-solid fa-trash text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
            Belum ada data pengajuan warga baru.
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL EDIT REGISTRASI MOBILE --}}
<div id="m-modal-edit-registrasi" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-3 hidden">
    <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 relative overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between mb-4 border-b pb-2">
            <h3 class="text-sm font-bold text-gray-800">Edit Data Pendaftaran</h3>
            <button onclick="closeEditModalMobile()" class="w-7 h-7 rounded-full bg-gray-50 text-gray-405 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="m-form-edit-registrasi" onsubmit="saveRegistrasiMobile(event)">
            @csrf
            <input type="hidden" name="id" id="m-edit-id">

            <div class="space-y-3 max-h-[50vh] overflow-y-auto pr-1">
                <div>
                    <label class="block text-[10px] font-bold text-gray-550 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="m-edit-name" required class="w-full py-2 px-3 text-xs border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-550 mb-1">Email</label>
                    <input type="email" name="email" id="m-edit-email" required class="w-full py-2 px-3 text-xs border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-550 mb-1">No KK (16 digit)</label>
                        <input type="text" name="nomor_kk" id="m-edit-nomor-kk" required minlength="16" maxlength="16" class="w-full py-2 px-3 text-xs border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-550 mb-1">Status Warga</label>
                        <select name="status_warga" id="m-edit-status-warga" required class="w-full py-2 px-3 border rounded-xl text-xs font-bold">
                            <option value="Tetap">Warga Tetap</option>
                            <option value="Kontrak">Warga Kontrak</option>
                            <option value="Kos">Warga Kos</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-550 mb-1">Alamat (Blok Rumah)</label>
                    <input type="text" name="blok_rumah" id="m-edit-blok-rumah" required class="w-full py-2 px-3 text-xs border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-550 mb-1">Umur (Tahun)</label>
                        <input type="number" name="umur" id="m-edit-umur" required min="1" max="120" class="w-full py-2 px-3 text-xs border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-550 mb-1">Hubungan KK</label>
                        <select name="status_keluarga" id="m-edit-status-keluarga" required class="w-full py-2 px-3 border rounded-xl text-xs font-bold">
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-550 mb-1">Status</label>
                    <select name="status" id="m-edit-status" required class="w-full py-2 px-3 border rounded-xl text-xs font-bold">
                        <option value="Pending">Pending</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 mt-4 pt-2 border-t">
                <button type="button" onclick="closeEditModalMobile()" class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold">Batal</button>
                <button type="submit" class="flex-1 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function quickApproveMobile(id, name, status) {
        const actionText = status === 'Aktif' ? 'menyetujui' : 'menolak';
        if (!confirm('Apakah Anda yakin ingin ' + actionText + ' pendaftaran warga "' + name + '"?')) return;
        
        const fd = new FormData();
        fd.append('id', id);
        fd.append('status', status);
        fd.append('_token', window.csrfToken);
        
        fetch('{{ route("approval-warga.update") }}', { 
            method: 'POST', 
            body: fd, 
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        })
        .then(r => r.json())
        .then(d => { 
            alert(d.message || 'Status pendaftaran diperbarui'); 
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('approval-warga'); }
            switchPage('approval-warga'); 
        })
        .catch(() => alert('Gagal memperbarui status pendaftaran.'));
    }

    function editRegistrasiMobile(data) {
        document.getElementById('m-edit-id').value = data.user_id;
        document.getElementById('m-edit-name').value = data.nama_lengkap;
        document.getElementById('m-edit-email').value = data.email;
        document.getElementById('m-edit-nomor-kk').value = data.nomor_kk;
        document.getElementById('m-edit-status-warga').value = data.status_domisili;
        document.getElementById('m-edit-blok-rumah').value = data.blok_rumah;
        document.getElementById('m-edit-umur').value = data.umur;
        document.getElementById('m-edit-status-keluarga').value = data.status_keluarga;
        document.getElementById('m-edit-status').value = data.status;

        document.getElementById('m-modal-edit-registrasi').classList.remove('hidden');
    }

    function closeEditModalMobile() {
        document.getElementById('m-modal-edit-registrasi').classList.add('hidden');
    }

    function saveRegistrasiMobile(e) {
        e.preventDefault();
        const fd = new FormData(document.getElementById('m-form-edit-registrasi'));
        
        fetch('{{ route("approval-warga.update") }}', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                alert(d.message);
                closeEditModalMobile();
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('approval-warga'); }
                switchPage('approval-warga');
            } else {
                alert('Gagal: ' + d.message);
            }
        })
        .catch(() => alert('Terjadi kesalahan saat menyimpan data.'));
    }

    function deleteRegistrasiMobile(id, name) {
        if (!confirm('Apakah Anda yakin ingin menghapus pendaftaran warga "' + name + '"? Semua data kependudukan akun ini akan dihapus permanen.')) return;
        
        const fd = new FormData();
        fd.append('id', id);
        fd.append('_token', window.csrfToken);
        
        fetch('{{ route("approval-warga.delete") }}', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            alert(d.message);
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('approval-warga'); }
            switchPage('approval-warga');
        })
        .catch(() => alert('Gagal menghapus pendaftaran warga.'));
    }

    function filterApprovalMobile(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('.approval-mobile-card').forEach(card => {
            const data = card.getAttribute('data-search') || '';
            card.style.display = (!q || data.includes(q)) ? '' : 'none';
        });
    }
</script>
