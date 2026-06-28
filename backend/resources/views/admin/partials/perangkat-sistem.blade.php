<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">
    <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-8 rounded-[2.5rem] shadow-xl text-white h-fit relative overflow-hidden">
        <div class="relative z-10">
            <span class="bg-blue-500/10 text-blue-400 text-[10px] px-3 py-1.5 rounded-xl font-black border border-blue-500/20 uppercase tracking-widest">Sistem Inventaris</span>
            <h2 class="text-3xl font-black mt-4 tracking-tight">Perangkat RT</h2>
            <div class="mt-8 pt-6 border-t border-white/5 space-y-3">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-none">Total Perangkat:</p>
                <p class="text-4xl font-black text-white">{{ count($list_perangkat ?? []) }} <span class="text-sm text-gray-500 font-medium">Unit</span></p>
            </div>
        </div>
        <i class="fa-solid fa-laptop-house absolute -bottom-10 -right-10 text-[150px] opacity-5 rotate-12"></i>
    </div>

    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)]">
            <h3 class="text-xl font-black text-gray-800 mb-6 tracking-tight">Daftar Perangkat</h3>
            <table class="w-full text-sm text-left">
                <thead class="text-gray-400 uppercase text-[10px] font-bold tracking-widest">
                    <tr class="border-b border-gray-100">
                        <th class="pb-3">No</th>
                        <th class="pb-3">Nama Perangkat</th>
                        <th class="pb-3">Jenis</th>
                        <th class="pb-3">Kondisi</th>
                        <th class="pb-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 font-bold">
                    @forelse($list_perangkat ?? [] as $index => $item)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4">{{ $index + 1 }}</td>
                        <td class="py-4">{{ $item->nama_perangkat }}</td>
                        <td class="py-4">{{ $item->jenis_perangkat ?? '-' }}</td>
                        <td class="py-4">
                            <span class="px-2 py-1 rounded-lg text-[10px] {{ $item->kondisi == 'Baik' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $item->kondisi }}
                            </span>
                        </td>
                        <td class="py-4 text-center flex justify-center gap-2">
                            <button type="button" onclick="editPerangkat({{ $item->id }}, '{{ addslashes($item->nama_perangkat) }}', '{{ addslashes($item->jenis_perangkat) }}', '{{ $item->kondisi }}')" class="text-blue-500 hover:text-blue-700"><i class="fa-solid fa-pen"></i></button>
                            <button type="button" onclick="hapusPerangkat({{ $item->id }})" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-400">Data kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)]">
            <h3 class="text-xl font-black text-gray-800 mb-6">Tambah Perangkat Baru</h3>
            <form id="form-perangkat" action="{{ url('/admin/perangkat/store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-perangkat', 'perangkat-sistem')">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <input type="text" name="nama_perangkat" placeholder="Nama Perangkat" required class="w-full bg-gray-50 border p-3 rounded-2xl">
                    <input type="text" name="jenis_perangkat" placeholder="Jenis" required class="w-full bg-gray-50 border p-3 rounded-2xl">
                </div>
                <select name="kondisi" class="w-full bg-gray-50 border p-3 rounded-2xl mb-4">
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                </select>
                <button type="submit" class="w-full bg-[#2563EB] text-white p-4 rounded-2xl font-bold hover:bg-blue-700">Simpan Inventaris</button>
            </form>
        </div>
    </div>
</div>

<div id="modal-edit-perangkat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white p-8 rounded-[2rem] w-full max-w-sm shadow-2xl">
        <h3 class="font-bold mb-6 text-lg">Ubah Data Perangkat</h3>
        <form id="form-edit-perangkat" action="{{ url('/admin/perangkat/update') }}" onsubmit="simpanDataUmum(event, 'form-edit-perangkat', 'perangkat-sistem')">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <input type="text" name="nama_perangkat" id="edit-nama" class="w-full border p-3 rounded-xl mb-4" required>
            <input type="text" name="jenis_perangkat" id="edit-jenis" class="w-full border p-3 rounded-xl mb-4" required>
            <select name="kondisi" id="edit-kondisi" class="w-full border p-3 rounded-xl mb-4">
                <option value="Baik">Baik</option>
                <option value="Rusak">Rusak</option>
            </select>
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-perangkat').classList.add('hidden')" class="flex-1 bg-gray-100 p-3 rounded-xl font-bold text-gray-600">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white p-3 rounded-xl font-bold">Update</button>
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

        fetch('/admin/perangkat/delete/' + id, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(() => {
            if (typeof switchPage === 'function') switchPage('perangkat-sistem');
            else location.reload();
        });
    }
</script>
