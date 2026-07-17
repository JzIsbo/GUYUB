<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto" id="pengurus-rt-container">

    {{-- ========== HERO BANNER ========== --}}
    <div class="relative bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 overflow-hidden shadow-xl">
        {{-- Decorative Background Icon --}}
        <div class="absolute top-1/2 right-8 -translate-y-1/2 opacity-[0.04] pointer-events-none">
            <i class="fa-solid fa-user-tie text-[10rem] lg:text-[14rem] text-white"></i>
        </div>
        {{-- Decorative Dots --}}
        <div class="absolute top-4 left-4 w-20 h-20 opacity-10 pointer-events-none">
            <div class="grid grid-cols-4 gap-1.5">
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
            </div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div class="space-y-3">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-4 py-1.5">
                    <i class="fa-solid fa-user-tie text-blue-300 text-xs"></i>
                    <span class="text-[11px] font-bold text-blue-200 uppercase tracking-widest">Struktur Organisasi</span>
                </div>
                {{-- Title --}}
                <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight">Data Pengurus RT & RW</h1>
                <p class="text-sm text-blue-200/70 font-medium max-w-md">Manajemen struktur organisasi pimpinan lingkungan RT & RW</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Stats Badge --}}
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[100px]">
                    <div class="text-2xl font-black text-white">{{ count($list_pengurus ?? []) }}</div>
                    <div class="text-[10px] font-bold text-blue-300/70 uppercase tracking-wider mt-0.5">Total Pengurus</div>
                </div>

                {{-- Add Button --}}
                @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']))
                <button type="button" onclick="document.getElementById('modal-tambah-pengurus').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-400 text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-lg hover:shadow-blue-500/25 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Pengurus
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== TABLE CARD ========== --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-list-ul text-blue-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Daftar Pengurus RT & RW</h3>
                    <p class="text-[11px] text-gray-400 font-medium">Seluruh jabatan pengurus RT & RW</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/60">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Pengurus</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jabatan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mulai</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']))
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($list_pengurus ?? [] as $item)
                    <tr id="row-pengurus-{{ $item->id }}" class="hover:bg-blue-50/30 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($item->nama_warga ?? 'N', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-800">{{ $item->nama_warga ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-lg">
                                <i class="fa-solid fa-briefcase text-[10px]"></i>
                                {{ $item->jabatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-lg {{ $item->status_aktif == 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $item->status_aktif == 'Aktif' ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300' }}"></span>
                                {{ $item->status_aktif }}
                            </span>
                        </td>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']))
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="window.bukaModalEdit('{{ $item->id }}', '{{ addslashes($item->jabatan) }}', '{{ $item->tanggal_mulai }}', '{{ $item->status_aktif }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all flex items-center justify-center" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button type="button" onclick="window.hapusPengurus('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 transition-all flex items-center justify-center" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 font-medium italic">Belum ada data pengurus RT.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-tambah-pengurus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-md shadow-2xl">
        <h2 class="text-xl font-bold mb-6">Tambah Pengurus Baru</h2>
        <form id="form-tambah-pengurus" action="{{ route('pengurus.store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-tambah-pengurus', 'data-pengurus-rt')">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 mb-2">Pilih Warga</label>
                <select name="warga_id" class="w-full p-3 border rounded-xl" required>
                    @foreach($all_warga ?? [] as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 mb-2">Jabatan Pengurus</label>
                <select name="jabatan" class="w-full p-3 border rounded-xl font-bold text-gray-700 bg-gray-50 focus:bg-white transition-all" required>
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
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2">Mulai</label>
                    <input type="date" name="tanggal_mulai" class="w-full p-3 border rounded-xl" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2">Status</label>
                    <select name="status_aktif" class="w-full p-3 border rounded-xl">
                        <option value="Aktif">Aktif</option>
                        <option value="Demisioner">Demisioner</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-2">
                <button type="button" onclick="document.getElementById('modal-tambah-pengurus').classList.add('hidden')" class="flex-1 p-3 bg-gray-100 text-gray-500 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 p-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-pengurus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-md shadow-2xl">
        <h2 class="text-xl font-bold mb-6">Ubah Data Pengurus</h2>
        <form id="form-edit-pengurus" action="{{ route('pengurus.update') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-edit-pengurus', 'data-pengurus-rt')">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 mb-2">Jabatan Pengurus</label>
                <select name="jabatan" id="edit-jabatan" class="w-full p-3 border rounded-xl font-bold text-gray-700 bg-gray-50 focus:bg-white transition-all" required>
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
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2">Mulai</label>
                    <input type="date" name="tanggal_mulai" id="edit-tanggal" class="w-full p-3 border rounded-xl" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2">Status</label>
                    <select name="status_aktif" id="edit-status" class="w-full p-3 border rounded-xl">
                        <option value="Aktif">Aktif</option>
                        <option value="Demisioner">Demisioner</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-2">
                <button type="button" onclick="document.getElementById('modal-edit-pengurus').classList.add('hidden')" class="flex-1 p-3 bg-gray-100 text-gray-500 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 p-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition">Update Data</button>
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
                const el = document.getElementById(`row-pengurus-${id}`);
                if (el) el.remove();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data pengurus RT telah berhasil dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-3xl p-6 font-sans' }
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
                        customClass: { popup: 'rounded-3xl p-6 font-sans' }
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
            text: 'Data pengurus RT ini akan dihapus secara permanen dari sistem.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-3xl p-6 font-sans' }
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
