<div class="p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                Posyandu Balita & Lansia RT
            </h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Jadwal pemeriksaan kesehatan, imunisasi, & suplemen gizi rutin warga.</p>
        </div>
        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
        <button onclick="document.getElementById('modal-tambah-posyandu').classList.remove('hidden')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-rose-200 transition-all flex items-center gap-2 cursor-pointer self-start md:self-auto text-sm">
            <i class="fa-solid fa-plus"></i> Tambah Jadwal Posyandu
        </button>
        @endif
    </div>

    <!-- Tabel Posyandu -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-black text-gray-800">Agenda Layanan Kesehatan Posyandu</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-extrabold tracking-widest border-b border-gray-100">
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Nama Kegiatan</th>
                        <th class="py-4 px-6">Target Peserta</th>
                        <th class="py-4 px-6">Lokasi</th>
                        <th class="py-4 px-6">Keterangan</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_posyandu ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 font-bold text-gray-800">{{ $item->tanggal }}</td>
                        <td class="py-4 px-6 font-bold text-rose-600">{{ $item->nama_kegiatan }}</td>
                        <td class="py-4 px-6"><span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-full text-xs font-bold">{{ $item->target_peserta }}</span></td>
                        <td class="py-4 px-6 text-gray-600"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> {{ $item->lokasi }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $item->keterangan ?? '-' }}</td>
                        <td class="py-4 px-6 text-right">
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                            <button onclick="hapusPosyandu({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            @else
                            <span class="text-xs text-gray-400 italic">Jadwal Terdaftar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 italic">Belum ada agenda Posyandu terjadwal.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-posyandu" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-posyandu').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Tambah Jadwal Posyandu</h3>
        <form id="form-posyandu" action="/posyandu/store" method="POST" onsubmit="simpanDataUmum(event, 'form-posyandu', 'posyandu')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Kegiatan Layanan</label>
                    <input type="text" name="nama_kegiatan" placeholder="Posyandu Rutin & Imunisasi" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Target Peserta</label>
                        <select name="target_peserta" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="Balita & Ibu Hamil">Balita & Ibu Hamil</option>
                            <option value="Lansia (Lanjut Usia)">Lansia (Lanjut Usia)</option>
                            <option value="Semua Warga">Semua Warga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Pos Pelayanan</label>
                    <input type="text" name="lokasi" placeholder="Pos Kamling / Balai Warga RT" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keterangan Tambahan / Layanan</label>
                    <textarea name="keterangan" rows="2" placeholder="Bawa buku KMS dan Kartu Identitas" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-posyandu').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-lg shadow-rose-200">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusPosyandu(id) {
    if (!confirm('Hapus agenda posyandu ini?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/posyandu/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('posyandu', document.querySelector('.menu-active')); });
}
</script>
