<div class="p-3 space-y-3">
    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-4 rounded-2xl shadow-xl text-white relative overflow-hidden">
        <div class="relative z-10">
            <span class="bg-blue-500/10 text-blue-400 text-[8px] px-2 py-1 rounded-lg font-black border border-blue-500/20 uppercase tracking-wider">Inventaris</span>
            <h2 class="text-lg font-black mt-2 tracking-tight">Aset RT</h2>
            <div class="mt-2">
                <p class="text-2xl font-black text-white">{{ count($list_perangkat ?? []) }} <span class="text-xs text-gray-500 font-medium">Unit</span></p>
            </div>
        </div>
        <i class="fa-solid fa-laptop-house absolute -bottom-6 -right-6 text-[80px] opacity-5 rotate-12"></i>
    </div>

    <!-- List -->
    <div class="bg-white p-3 rounded-xl border border-gray-50 shadow-sm">
        <h3 class="text-xs font-black text-gray-800 mb-2">Daftar Aset</h3>
        <div class="overflow-x-auto -mx-3">
            <table class="w-full text-[10px] text-left min-w-[300px]">
                <thead class="text-[9px] text-gray-400 uppercase tracking-wider">
                    <tr class="border-b border-gray-100">
                        <th class="px-3 pb-2">#</th>
                        <th class="px-3 pb-2">Nama</th>
                        <th class="px-3 pb-2">Jenis</th>
                        <th class="px-3 pb-2">Kondisi</th>
                        <th class="px-3 pb-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 font-bold">
                    @forelse($list_perangkat ?? [] as $index => $item)
                    <tr class="border-b border-gray-50">
                        <td class="px-3 py-2">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 truncate max-w-[80px]">{{ $item->nama_perangkat }}</td>
                        <td class="px-3 py-2">{{ $item->jenis_perangkat ?? '-' }}</td>
                        <td class="px-3 py-2"><span class="px-1.5 py-0.5 rounded text-[8px] {{ $item->kondisi == 'Baik' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $item->kondisi }}</span></td>
                        <td class="px-3 py-2 text-center flex justify-center gap-1">
                            <button onclick="editPerangkat({{ $item->id }}, '{{ addslashes($item->nama_perangkat) }}', '{{ addslashes($item->jenis_perangkat) }}', '{{ $item->kondisi }}')" class="w-6 h-6 rounded-md bg-blue-50 text-blue-500 inline-flex items-center justify-center"><i class="fa-solid fa-pen text-[8px]"></i></button>
                            <button onclick="hapusPerangkat({{ $item->id }})" class="w-6 h-6 rounded-md bg-red-50 text-red-500 inline-flex items-center justify-center"><i class="fa-solid fa-trash text-[8px]"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-400 text-xs">Data kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Form -->
    <div class="bg-white p-4 rounded-xl border border-gray-50 shadow-sm">
        <h3 class="text-xs font-black text-gray-800 mb-3">Tambah Aset</h3>
        <form id="form-perangkat" action="{{ url('/admin/perangkat/store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-perangkat', 'perangkat-sistem')">
            @csrf
            <div class="space-y-2 mb-3">
                <input type="text" name="nama_perangkat" placeholder="Nama Aset" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                <input type="text" name="jenis_perangkat" placeholder="Jenis" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                <select name="kondisi" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-[#2563EB] text-white py-2.5 rounded-xl font-bold text-xs">Simpan</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit-perangkat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-3">
    <div class="bg-white p-5 rounded-2xl w-full max-w-[95vw] shadow-2xl">
        <h3 class="font-bold mb-4 text-sm">Ubah Data Aset</h3>
        <form id="form-edit-perangkat" action="{{ url('/admin/perangkat/update') }}" onsubmit="simpanDataUmum(event, 'form-edit-perangkat', 'perangkat-sistem')">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="space-y-2 mb-3">
                <input type="text" name="nama_perangkat" id="edit-nama" class="w-full border py-2 px-3 rounded-xl text-sm" required>
                <input type="text" name="jenis_perangkat" id="edit-jenis" class="w-full border py-2 px-3 rounded-xl text-sm" required>
                <select name="kondisi" id="edit-kondisi" class="w-full border py-2 px-3 rounded-xl text-sm">
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-perangkat').classList.add('hidden')" class="flex-1 bg-gray-100 py-2.5 rounded-xl font-bold text-xs text-gray-600">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl font-bold text-xs">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editPerangkat(id, nama, jenis, kondisi) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-jenis').value = jenis;
        document.getElementById('edit-kondisi').value = kondisi;
        document.getElementById('modal-edit-perangkat').classList.remove('hidden');
    }
    function hapusPerangkat(id) {
        if(!confirm('Hapus perangkat ini?')) return;
        let formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        fetch('/admin/perangkat/delete/' + id, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(() => { if (typeof switchPage === 'function') switchPage('perangkat-sistem'); else location.reload(); });
    }
</script>
