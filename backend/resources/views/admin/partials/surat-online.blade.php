<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Layanan Surat Online</h2>
            <p class="text-sm text-gray-500">Kelola pengajuan surat pengantar dari warga</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-surat').classList.remove('hidden')" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">
            + Buat Pengajuan Manual
        </button>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="p-5">Tanggal</th>
                    <th class="p-5">Nama Warga</th>
                    <th class="p-5">Jenis Surat</th>
                    <th class="p-5">Status</th>
                    <th class="p-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($list_surat as $surat)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-5 text-gray-500">{{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}</td>
                    <td class="p-5 font-bold text-gray-800">{{ $surat->nama_warga }}</td>
                    <td class="p-5 text-gray-600">{{ $surat->jenis_surat }}<br><span class="text-xs text-gray-400">{{ $surat->keperluan }}</span></td>
                    <td class="p-5">
                        @if($surat->status == 'Menunggu') <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-600">MENUNGGU</span>
                        @elseif($surat->status == 'Disetujui') <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600">DISETUJUI</span>
                        @else <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600">DITOLAK</span>
                        @endif
                    </td>
                    <td class="p-5 text-center flex justify-center gap-2">
                        @if($surat->status == 'Menunggu')
                        <button onclick="ubahStatusSurat({{ $surat->id }}, 'Disetujui')" class="bg-green-50 text-green-600 px-3 py-1.5 rounded-lg font-bold hover:bg-green-100">Setujui</button>
                        <button onclick="ubahStatusSurat({{ $surat->id }}, 'Ditolak')" class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100">Tolak</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-10 text-center text-gray-400 font-medium">Belum ada pengajuan surat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah-surat" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl w-[400px]">
        <h2 class="text-xl font-bold mb-4">Pengajuan Surat Manual</h2>
        <form id="form-tambah-surat" action="/surat-online/store" method="POST">
            @csrf
            <div class="space-y-4">
                <input type="text" name="nama_warga" placeholder="Nama Warga" class="w-full p-3 border rounded-xl" required>
                <select name="jenis_surat" class="w-full p-3 border rounded-xl" required>
                    <option value="Surat Pengantar Domisili">Surat Pengantar Domisili</option>
                    <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu (SKTM)</option>
                    <option value="Surat Pengantar Nikah">Surat Pengantar Nikah</option>
                </select>
                <textarea name="keperluan" placeholder="Tujuan / Keperluan pembuatan surat" class="w-full p-3 border rounded-xl" required></textarea>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-tambah-surat').classList.add('hidden')" class="w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-surat', 'surat-online')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function ubahStatusSurat(id, status) {
    if(!confirm('Yakin ingin merubah status surat ini menjadi ' + status + '?')) return;

    let formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/surat-online/update-status', {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(res => res.json()).then(data => {
        alert(data.message);
        switchPage('surat-online', document.querySelector('.menu-active'));
    });
}
</script>
