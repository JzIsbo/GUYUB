<div class="p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                Agenda Kegiatan & Event RT
            </h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Jadwal kerja bakti, rapat warga, pengajian, & acara kemasyarakatan.</p>
        </div>
        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
        <button onclick="document.getElementById('modal-tambah-kegiatan').classList.remove('hidden')" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-teal-200 transition-all flex items-center gap-2 cursor-pointer self-start md:self-auto text-sm">
            <i class="fa-solid fa-plus"></i> Buat Agenda Kegiatan
        </button>
        @endif
    </div>

    <!-- Grid Kegiatan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($list_kegiatan ?? [] as $item)
        <div class="bg-white rounded-[2.5rem] p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-teal-50 text-teal-600 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-clock mr-1"></i> {{ $item->waktu }}</span>
                    <span class="text-xs text-gray-400 font-bold">{{ $item->tanggal }}</span>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">{{ $item->nama_kegiatan }}</h3>
                <p class="text-xs font-bold text-gray-500 mb-3"><i class="fa-solid fa-location-dot text-teal-500 mr-1"></i> Lokasi: {{ $item->lokasi }}</p>
                <p class="text-xs text-gray-600 line-clamp-3 mb-6">{{ $item->deskripsi ?? 'Kegiatan kebersamaan warga RT.' }}</p>
            </div>
            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                <span class="text-xs font-bold text-teal-600">Terbuka untuk Warga</span>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="hapusKegiatan({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white p-12 rounded-[2.5rem] border border-gray-100 text-center text-gray-400 italic">
            Belum ada agenda kegiatan RT terjadwal saat ini.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-kegiatan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-kegiatan').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Tambah Agenda Kegiatan RT</h3>
        <form id="form-kegiatan" action="/kegiatan/store" method="POST" onsubmit="simpanDataUmum(event, 'form-kegiatan', 'kegiatan')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Kegiatan / Acara</label>
                    <input type="text" name="nama_kegiatan" placeholder="Kerja Bakti Bersih Lingkungan" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu / Jam</label>
                        <input type="text" name="waktu" placeholder="07:30 WIB - Selesai" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Tempat Pelaksanaan</label>
                    <input type="text" name="lokasi" placeholder="Lapangan Utama RT 01" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi & Imbauan Peralatan</label>
                    <textarea name="deskripsi" rows="3" placeholder="Harap warga membawa sapu dan cangkul..." class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-kegiatan').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-teal-200">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusKegiatan(id) {
    if (!confirm('Hapus agenda kegiatan ini?')) return;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
    fetch('/kegiatan/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json()).then(data => { alert(data.message); switchPage('kegiatan', document.querySelector('.menu-active')); });
}
</script>
