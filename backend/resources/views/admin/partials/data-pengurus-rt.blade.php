<div class="space-y-8" id="pengurus-rt-container">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Data Pengurus RT</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Manajemen struktur organisasi pimpinan lingkungan</p>
        </div>
        <button type="button" onclick="document.getElementById('modal-tambah-pengurus').classList.remove('hidden')" class="bg-blue-600 text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-lg hover:bg-blue-700 transition-all">
            <i class="fa-solid fa-user-tie mr-2"></i> Tambah Pengurus
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/70">
                    <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Pengurus</th>
                    <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jabatan</th>
                    <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mulai</th>
                    <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($list_pengurus ?? [] as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-6 text-sm font-bold text-gray-800">{{ $item->nama_warga ?? 'N/A' }}</td>
                    <td class="p-6 text-sm font-semibold text-blue-600">{{ $item->jabatan }}</td>
                    <td class="p-6 text-sm font-medium text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                    <td class="p-6">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $item->status_aktif == 'Aktif' ? 'text-emerald-600' : 'text-gray-400' }}">
                            <span class="w-2 h-2 rounded-full {{ $item->status_aktif == 'Aktif' ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                            {{ $item->status_aktif }}
                        </span>
                    </td>
                    <td class="p-6 text-center flex justify-center gap-2">
                        <button type="button" onclick="bukaModalEdit('{{ $item->id }}', '{{ addslashes($item->jabatan) }}', '{{ $item->tanggal_mulai }}', '{{ $item->status_aktif }}')" class="text-blue-500 hover:text-blue-700 transition p-2">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" onclick="hapusPengurus('{{ $item->id }}')" class="text-red-500 hover:text-red-700 transition p-2">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-12 text-center text-sm text-gray-400 italic">Belum ada pengurus RT.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah-pengurus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-md shadow-2xl">
        <h2 class="text-xl font-bold mb-6">Tambah Pengurus</h2>
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
                <label class="block text-xs font-bold text-gray-500 mb-2">Jabatan</label>
                <input type="text" name="jabatan" class="w-full p-3 border rounded-xl" required>
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
            <button type="submit" class="w-full p-3 bg-blue-600 text-white rounded-xl font-bold">Simpan</button>
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
                <label class="block text-xs font-bold text-gray-500 mb-2">Jabatan</label>
                <input type="text" name="jabatan" id="edit-jabatan" class="w-full p-3 border rounded-xl" required>
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
            <button type="submit" class="w-full p-3 bg-amber-500 text-white rounded-xl font-bold">Update Data</button>
        </form>
    </div>
</div>

<script>
function hapusPengurus(id) {
    if(!confirm('Yakin ingin menghapus data pengurus ini?')) return;
    fetch(`/admin/pengurus/delete/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (typeof switchPage === 'function') switchPage('data-pengurus-rt');
            else window.location.reload();
        } else { alert('Gagal: ' + data.message); }
    });
}

function bukaModalEdit(id, jabatan, tanggal, status) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-jabatan').value = jabatan;
    document.getElementById('edit-tanggal').value = tanggal;
    document.getElementById('edit-status').value = status;
    document.getElementById('modal-edit-pengurus').classList.remove('hidden');
}
</script>
