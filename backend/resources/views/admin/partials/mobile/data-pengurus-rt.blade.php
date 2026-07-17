<div class="p-3 space-y-3" id="pengurus-rt-container">

    {{-- ========== HERO BANNER ========== --}}
    <div class="relative bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 overflow-hidden shadow-lg">
        {{-- Decorative Background Icon --}}
        <div class="absolute top-1/2 right-4 -translate-y-1/2 opacity-[0.04] pointer-events-none">
            <i class="fa-solid fa-user-tie text-[6rem] text-white"></i>
        </div>

        <div class="relative z-10 space-y-3">
            <div class="space-y-1.5">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-3 py-1">
                    <i class="fa-solid fa-user-tie text-blue-300 text-[10px]"></i>
                    <span class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Struktur Organisasi</span>
                </div>
                {{-- Title --}}
                <h1 class="text-lg font-black text-white tracking-tight">Data Pengurus RT & RW</h1>
                <p class="text-xs text-blue-200/70 font-medium">Manajemen struktur organisasi RT & RW</p>
            </div>

            <div class="flex items-center gap-2">
                {{-- Stats Badge --}}
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[70px]">
                    <div class="text-lg font-black text-white">{{ count($list_pengurus ?? []) }}</div>
                    <div class="text-[9px] font-bold text-blue-300/70 uppercase tracking-wider">Total</div>
                </div>

                {{-- Add Button --}}
                @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']))
                <button type="button" onclick="document.getElementById('modal-tambah-pengurus').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-400 text-white px-3.5 py-2 rounded-xl font-bold text-xs shadow-lg hover:shadow-blue-500/25 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    Tambah
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== CARD LIST ========== --}}
    <div class="space-y-2">
        @forelse($list_pengurus ?? [] as $item)
            <div id="m-row-pengurus-{{ $item->id }}" class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[11px] font-bold">{{ strtoupper(substr($item->nama_warga ?? 'N', 0, 1)) }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-gray-800 text-[12px] truncate">{{ $item->nama_warga ?? 'N/A' }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                <span class="bg-blue-50 text-blue-600 text-[8px] font-bold px-1.5 py-0.5 rounded"><i class="fa-solid fa-briefcase text-[7px] mr-0.5"></i> {{ $item->jabatan }}</span>
                                <span class="inline-flex items-center gap-0.5 text-[8px] font-bold px-1.5 py-0.5 rounded {{ $item->status_aktif == 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                                    <span class="w-1 h-1 rounded-full {{ $item->status_aktif == 'Aktif' ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                    {{ $item->status_aktif }}
                                </span>
                            </div>
                            <p class="text-[9px] text-gray-400 mt-1"><i class="fa-regular fa-calendar mr-0.5"></i> Mulai: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</p>
                        </div>
                    </div>
                    @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']))
                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" onclick="window.bukaModalEdit('{{ $item->id }}', '{{ addslashes($item->jabatan) }}', '{{ $item->tanggal_mulai }}', '{{ $item->status_aktif }}')" class="w-7 h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-pen text-[9px]"></i></button>
                        <button type="button" onclick="window.hapusPengurus('{{ $item->id }}')" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash text-[9px]"></i></button>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada pengurus RT & RW yang terdaftar.
            </div>
        @endforelse
    </div>
</div>

<div id="modal-tambah-pengurus" class="hidden fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-0">
    <div class="bg-white rounded-t-2xl p-5 w-full max-w-[95vw] shadow-2xl max-h-[85vh] overflow-y-auto">
        <h2 class="text-base font-bold mb-4">Tambah Pengurus</h2>
        <form id="form-tambah-pengurus" action="{{ route('pengurus.store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-tambah-pengurus', 'data-pengurus-rt')">
            @csrf
            <div class="mb-3">
                <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Pilih Warga</label>
                <select name="warga_id" class="w-full py-2 px-3 text-sm border rounded-xl" required>
                    @foreach($all_warga ?? [] as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Jabatan Pengurus</label>
                <select name="jabatan" class="w-full py-2 px-3 text-sm border rounded-xl font-bold text-gray-700 bg-gray-50 focus:bg-white" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Ketua RW">Ketua RW</option>
                    <option value="Sekretaris RW">Sekretaris RW</option>
                    <option value="Bendahara RW">Bendahara RW</option>
                    <option value="Penasihat RW">Penasihat RW</option>
                    <option value="Ketua RT">Ketua RT</option>
                    <option value="Wakil Ketua RT">Wakil Ketua RT</option>
                    <option value="Sekretaris RT">Sekretaris RT</option>
                    <option value="Bendahara RT">Bendahara RT</option>
                    <option value="Koordinator Keamanan & Ronda">Koordinator Keamanan & Ronda</option>
                    <option value="Koordinator Kebersihan & Lingkungan">Koordinator Kebersihan & Lingkungan</option>
                    <option value="Koordinator Posyandu & Kesehatan">Koordinator Posyandu & Kesehatan</option>
                    <option value="Koordinator Humas & Sosial">Koordinator Humas & Sosial</option>
                    <option value="Koordinator Koperasi & UMKM">Koordinator Koperasi & UMKM</option>
                    <option value="Penasihat RT">Penasihat RT</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Mulai</label>
                    <input type="date" name="tanggal_mulai" class="w-full py-2 px-3 text-sm border rounded-xl" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Status</label>
                    <select name="status_aktif" class="w-full py-2 px-3 text-sm border rounded-xl">
                        <option value="Aktif">Aktif</option>
                        <option value="Demisioner">Demisioner</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2 mt-2">
                <button type="button" onclick="document.getElementById('modal-tambah-pengurus').classList.add('hidden')" class="flex-1 py-2.5 px-3 bg-gray-100 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 px-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-pengurus" class="hidden fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-0">
    <div class="bg-white rounded-t-2xl p-5 w-full max-w-[95vw] shadow-2xl max-h-[85vh] overflow-y-auto">
        <h2 class="text-base font-bold mb-4">Ubah Data Pengurus</h2>
        <form id="form-edit-pengurus" action="{{ route('pengurus.update') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-edit-pengurus', 'data-pengurus-rt')">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="mb-3">
                <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Jabatan Pengurus</label>
                <select name="jabatan" id="edit-jabatan" class="w-full py-2 px-3 text-sm border rounded-xl font-bold text-gray-700 bg-gray-50 focus:bg-white" required>
                    <option value="Ketua RW">Ketua RW</option>
                    <option value="Sekretaris RW">Sekretaris RW</option>
                    <option value="Bendahara RW">Bendahara RW</option>
                    <option value="Penasihat RW">Penasihat RW</option>
                    <option value="Ketua RT">Ketua RT</option>
                    <option value="Wakil Ketua RT">Wakil Ketua RT</option>
                    <option value="Sekretaris RT">Sekretaris RT</option>
                    <option value="Bendahara RT">Bendahara RT</option>
                    <option value="Koordinator Keamanan & Ronda">Koordinator Keamanan & Ronda</option>
                    <option value="Koordinator Kebersihan & Lingkungan">Koordinator Kebersihan & Lingkungan</option>
                    <option value="Koordinator Posyandu & Kesehatan">Koordinator Posyandu & Kesehatan</option>
                    <option value="Koordinator Humas & Sosial">Koordinator Humas & Sosial</option>
                    <option value="Koordinator Koperasi & UMKM">Koordinator Koperasi & UMKM</option>
                    <option value="Penasihat RT">Penasihat RT</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Mulai</label>
                    <input type="date" name="tanggal_mulai" id="edit-tanggal" class="w-full py-2 px-3 text-sm border rounded-xl" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 mb-1.5">Status</label>
                    <select name="status_aktif" id="edit-status" class="w-full py-2 px-3 text-sm border rounded-xl">
                        <option value="Aktif">Aktif</option>
                        <option value="Demisioner">Demisioner</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2 mt-2">
                <button type="button" onclick="document.getElementById('modal-edit-pengurus').classList.add('hidden')" class="flex-1 py-2.5 px-3 bg-gray-100 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 px-3 bg-amber-500 text-white rounded-xl font-bold text-sm hover:bg-amber-600 transition">Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>
window.hapusPengurus = function(id) {
    const doDelete = () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        fetch(`/admin/pengurus/delete/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const el = document.getElementById(`m-row-pengurus-${id}`);
                if (el) el.remove();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data pengurus RT telah dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' }
                    });
                } else {
                    alert('Data pengurus RT berhasil dihapus.');
                }

                if (typeof window.invalidatePageCache === 'function') window.invalidatePageCache('data-pengurus-rt');
                if (typeof switchPage === 'function') switchPage('data-pengurus-rt', document.querySelector('.menu-active'));
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Gagal menghapus data.',
                        icon: 'error',
                        customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' }
                    });
                } else {
                    alert('Gagal: ' + (data.message || 'Gagal menghapus data.'));
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Error!', text: 'Terjadi kesalahan koneksi.', icon: 'error' });
            } else {
                alert('Terjadi kesalahan koneksi saat menghapus data.');
            }
        });
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus Data Pengurus?',
            text: 'Data pengurus RT ini akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' }
        }).then((result) => {
            if (result.isConfirmed) {
                doDelete();
            }
        });
    } else {
        if (confirm('Yakin ingin menghapus data pengurus ini?')) {
            doDelete();
        }
    }
};

window.bukaModalEdit = function(id, jabatan, tanggal, status) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-jabatan').value = jabatan;
    document.getElementById('edit-tanggal').value = tanggal;
    document.getElementById('edit-status').value = status;
    document.getElementById('modal-edit-pengurus').classList.remove('hidden');
};
</script>
