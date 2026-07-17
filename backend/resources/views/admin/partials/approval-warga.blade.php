{{-- resources/views/admin/partials/approval-warga.blade.php --}}

<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/40 dark:bg-slate-900/30 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold">
                <i class="fa-solid fa-user-check text-[10px]"></i>
                Administrasi Kependudukan
            </div>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-2">Persetujuan Registrasi Warga</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Validasi dan kelola persetujuan akun warga baru yang mendaftar secara mandiri</p>
        </div>
    </div>

    {{-- Controls --}}
    <div class="bg-white/40 dark:bg-slate-900/30 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-3xl p-4 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-80">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
            <input type="text" id="search-approval" placeholder="Cari nama, email, KK, atau blok..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 rounded-2xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 transition" onkeyup="filterApproval(this.value)">
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white/40 dark:bg-slate-900/30 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200/60 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-900/20 text-slate-400 dark:text-slate-500">
                        <th class="p-6 text-[10px] font-bold uppercase tracking-wider">Nama & NIK</th>
                        <th class="p-6 text-[10px] font-bold uppercase tracking-wider">No. KK & Kontak</th>
                        <th class="p-6 text-[10px] font-bold uppercase tracking-wider">Rumah (Blok)</th>
                        <th class="p-6 text-[10px] font-bold uppercase tracking-wider">Detail</th>
                        <th class="p-6 text-[10px] font-bold uppercase tracking-wider text-center">Status</th>
                        <th class="p-6 text-[10px] font-bold uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/50 dark:divide-slate-850">
                    @forelse($list_registrasi ?? [] as $reg)
                    <tr class="approval-row hover:bg-slate-50/40 dark:hover:bg-slate-900/10 transition-colors" 
                        data-search="{{ strtolower($reg->nama_lengkap . ' ' . $reg->email . ' ' . $reg->nomor_kk . ' ' . $reg->blok_rumah) }}">
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/10 dark:bg-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($reg->nama_lengkap, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white text-xs">{{ $reg->nama_lengkap }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">NIK: {{ $reg->nik }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <p class="font-semibold text-slate-700 dark:text-slate-300 text-xs">KK: {{ $reg->nomor_kk }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $reg->email }}</p>
                        </td>
                        <td class="p-6">
                            <p class="font-semibold text-slate-700 dark:text-slate-300 text-xs">{{ $reg->blok_rumah }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Domisili: {{ $reg->status_domisili }}</p>
                        </td>
                        <td class="p-6">
                            <p class="text-[11px] text-slate-600 dark:text-slate-400 font-semibold">{{ $reg->umur }} Tahun</p>
                            <p class="text-[9px] text-slate-400 mt-0.5">{{ $reg->status_keluarga }}</p>
                        </td>
                        <td class="p-6 text-center">
                            @if($reg->status === 'Pending')
                            <span class="inline-flex items-center gap-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 px-2 py-0.5 rounded-full text-[9px] font-bold border border-amber-500/10">
                                <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                                Pending
                            </span>
                            @elseif($reg->status === 'Ditolak')
                            <span class="inline-flex items-center gap-1 bg-red-500/10 text-red-600 dark:text-red-400 px-2 py-0.5 rounded-full text-[9px] font-bold border border-red-500/10">
                                <span class="w-1 h-1 rounded-full bg-red-500"></span>
                                Ditolak
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded-full text-[9px] font-bold border border-emerald-500/10">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($reg->foto_ktp)
                                <a href="{{ asset($reg->foto_ktp) }}" target="_blank" class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition inline-flex items-center justify-center cursor-pointer" title="Lihat Foto KTP">
                                    <i class="fa-solid fa-address-card text-xs"></i>
                                </a>
                                @endif

                                @if($reg->status === 'Pending')
                                <button onclick="quickApprove({{ $reg->user_id }}, '{{ addslashes($reg->nama_lengkap) }}', 'Aktif')" class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-450 hover:bg-emerald-650 hover:text-white transition inline-flex items-center justify-center cursor-pointer font-bold" title="Setujui Pendaftaran">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </button>
                                <button onclick="quickApprove({{ $reg->user_id }}, '{{ addslashes($reg->nama_lengkap) }}', 'Ditolak')" class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 dark:text-red-400 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer font-bold" title="Tolak Pendaftaran">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                                @endif

                                <button onclick="editRegistrasi({{ json_encode($reg) }})" class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition inline-flex items-center justify-center cursor-pointer" title="Edit Data Registrasi">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>

                                <button onclick="deleteRegistrasi({{ $reg->user_id }}, '{{ addslashes($reg->nama_lengkap) }}')" class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 dark:text-red-400 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer" title="Hapus Registrasi">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-sm font-medium text-slate-400 dark:text-slate-500">Belum ada data pengajuan registrasi warga baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDIT REGISTRASI --}}
<div id="modal-edit-registrasi" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-[2rem] p-6 relative overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-5 border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Edit Data Registrasi Warga</h3>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-slate-650 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="form-edit-registrasi" onsubmit="saveRegistrasi(event)">
            @csrf
            <input type="hidden" name="id" id="edit-id">

            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="edit-name" required class="w-full py-2 px-3.5 text-xs font-bold border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Email</label>
                    <input type="email" name="email" id="edit-email" required class="w-full py-2 px-3.5 text-xs font-bold border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">No. Kartu Keluarga (KK)</label>
                        <input type="text" name="nomor_kk" id="edit-nomor-kk" required minlength="16" maxlength="16" class="w-full py-2 px-3.5 text-xs font-bold border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Status Warga</label>
                        <select name="status_warga" id="edit-status-warga" required class="w-full py-2 px-3 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Tetap">Warga Tetap</option>
                            <option value="Kontrak">Warga Kontrak</option>
                            <option value="Kos">Warga Kos</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Alamat (Blok Rumah)</label>
                    <input type="text" name="blok_rumah" id="edit-blok-rumah" required class="w-full py-2 px-3.5 text-xs font-bold border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Umur (Tahun)</label>
                        <input type="number" name="umur" id="edit-umur" required min="1" max="120" class="w-full py-2 px-3.5 text-xs font-bold border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Hubungan dalam KK</label>
                        <select name="status_keluarga" id="edit-status-keluarga" required class="w-full py-2 px-3 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Status Persetujuan</label>
                    <select name="status" id="edit-status" required class="w-full py-2 px-3 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Pending">Pending</option>
                        <option value="Aktif">Aktif (Setujui)</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold cursor-pointer transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold cursor-pointer transition shadow-md shadow-blue-500/10">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function quickApprove(id, name, status) {
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

    function editRegistrasi(data) {
        document.getElementById('edit-id').value = data.user_id;
        document.getElementById('edit-name').value = data.nama_lengkap;
        document.getElementById('edit-email').value = data.email;
        document.getElementById('edit-nomor-kk').value = data.nomor_kk;
        document.getElementById('edit-status-warga').value = data.status_domisili;
        document.getElementById('edit-blok-rumah').value = data.blok_rumah;
        document.getElementById('edit-umur').value = data.umur;
        document.getElementById('edit-status-keluarga').value = data.status_keluarga;
        document.getElementById('edit-status').value = data.status;

        document.getElementById('modal-edit-registrasi').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modal-edit-registrasi').classList.add('hidden');
    }

    function saveRegistrasi(e) {
        e.preventDefault();
        const fd = new FormData(document.getElementById('form-edit-registrasi'));
        
        fetch('{{ route("approval-warga.update") }}', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                alert(d.message);
                closeEditModal();
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('approval-warga'); }
                switchPage('approval-warga');
            } else {
                alert('Gagal: ' + d.message);
            }
        })
        .catch(() => alert('Terjadi kesalahan saat menyimpan data.'));
    }

    function deleteRegistrasi(id, name) {
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

    function filterApproval(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('.approval-row').forEach(row => {
            const data = row.getAttribute('data-search') || '';
            row.style.display = (!q || data.includes(q)) ? '' : 'none';
        });
    }
</script>
