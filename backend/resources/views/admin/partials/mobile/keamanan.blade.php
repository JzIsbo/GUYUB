<div class="p-3 space-y-3">
    <!-- Hero Banner -->
    <div class="bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#020617] rounded-2xl p-4 text-white relative overflow-hidden shadow-xl">
        <i class="fa-solid fa-shield-halved absolute -bottom-4 -right-2 text-[5rem] opacity-[0.03] rotate-12"></i>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-6 h-6 rounded-lg bg-slate-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-shield-halved text-slate-300 text-[10px]"></i>
                </div>
                <span class="text-[8px] font-black uppercase tracking-[2px] text-slate-300/80">Keamanan</span>
            </div>
            <h1 class="text-lg font-black tracking-tight">Keamanan & Ronda</h1>
            <div class="flex gap-2 mt-3">
                <button onclick="document.getElementById('modal-laporkan-kejadian').classList.remove('hidden')" class="flex-1 bg-red-500 hover:bg-red-400 text-white font-bold py-2.5 rounded-xl text-[10px] flex items-center justify-center gap-1.5 shadow-lg shadow-red-500/30">
                    <i class="fa-solid fa-triangle-exclamation"></i> Lapor Darurat
                </button>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-ronda').classList.remove('hidden')" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-2.5 rounded-xl text-[10px] flex items-center justify-center gap-1">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Ronda
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Shifts -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3">
        <h3 class="font-black text-gray-800 text-xs flex items-center gap-1.5 mb-2.5">
            <i class="fa-solid fa-calendar-days text-slate-700"></i> Jadwal Ronda Malam
        </h3>
        <div class="space-y-2">
            @forelse($list_ronda ?? [] as $item)
            <div class="bg-gray-50/70 p-3 rounded-xl flex items-center justify-between text-xs">
                <div>
                    <span class="bg-slate-800 text-white px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider">{{ $item->hari }}</span>
                    <span class="text-[10px] text-gray-500 font-bold ml-1.5">{{ $item->jam_shift }}</span>
                    <h4 class="font-bold text-gray-800 mt-1 text-[11px]"><i class="fa-solid fa-user-shield text-slate-500 mr-1"></i> Petugas: {{ $item->petugas_ronda }}</h4>
                    <p class="text-[10px] text-gray-400">Koord: {{ $item->koordinator }}</p>
                </div>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="hapusRonda({{ $item->id }})" class="w-6 h-6 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-trash text-[10px]"></i>
                </button>
                @endif
            </div>
            @empty
            <div class="text-center py-4 text-gray-400 text-xs italic">Belum ada jadwal ronda.</div>
            @endforelse
        </div>
    </div>

    <!-- Laporan Darurat -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3">
        <h3 class="font-black text-gray-800 text-xs flex items-center gap-1.5 mb-2.5">
            <i class="fa-solid fa-bell-concierge text-red-500"></i> Laporan Kejadian Warga
        </h3>
        <div class="space-y-2">
            @forelse($list_incidents ?? [] as $item)
            <div class="bg-red-50/50 p-3 rounded-xl border border-red-100 text-xs">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded text-[9px]">{{ $item->jenis_kejadian }}</span>
                    <span class="text-[9px] text-gray-400">{{ $item->created_at }}</span>
                </div>
                <h4 class="font-black text-gray-800 text-[11px]">Pelapor: {{ $item->pelapor }}</h4>
                <p class="text-[10px] text-gray-600 mt-0.5">{{ $item->deskripsi }}</p>
                <div class="mt-2 flex justify-between items-center pt-1.5 border-t border-red-100/50">
                    <span class="text-[9px] uppercase font-black text-amber-600">{{ $item->status }}</span>
                    @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                    <button onclick="hapusIncident({{ $item->id }})" class="text-[9px] text-red-500 font-bold">Selesai & Hapus</button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-gray-400 text-xs italic">Situasi aman terkendali.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Tambah Ronda -->
<div id="modal-tambah-ronda" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl">
        <button onclick="document.getElementById('modal-tambah-ronda').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-sm font-black text-gray-800 mb-4">Tambah Jadwal Ronda</h3>
        <form id="form-ronda" action="/ronda/store" method="POST" onsubmit="simpanDataUmum(event, 'form-ronda', 'keamanan')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Hari Shift</label>
                    <select name="hari" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                        <option value="Senin Malam">Senin Malam</option>
                        <option value="Selasa Malam">Selasa Malam</option>
                        <option value="Rabu Malam">Rabu Malam</option>
                        <option value="Kamis Malam">Kamis Malam</option>
                        <option value="Jumat Malam">Jumat Malam</option>
                        <option value="Sabtu Malam">Sabtu Malam</option>
                        <option value="Minggu Malam">Minggu Malam</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Daftar Petugas</label>
                    <input type="text" name="petugas_ronda" placeholder="Bpk Budi, Bpk Agus" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Koordinator</label>
                        <input type="text" name="koordinator" placeholder="Bpk RT" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Jam Shift</label>
                        <input type="text" name="jam_shift" value="22:00 - 04:00 WIB" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-ronda').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-xs">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-slate-800 text-white font-bold rounded-xl text-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Lapor Kejadian -->
<div id="modal-laporkan-kejadian" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl">
        <button onclick="document.getElementById('modal-laporkan-kejadian').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-sm font-black text-red-600 mb-4 flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Lapor Kejadian / Darurat</h3>
        <form id="form-incident" action="/incident/store" method="POST" onsubmit="simpanDataUmum(event, 'form-incident', 'keamanan')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Pelapor</label>
                    <input type="text" name="pelapor" value="{{ Auth::user()->name }}" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Jenis Kejadian</label>
                    <select name="jenis_kejadian" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                        <option value="Pencurian / Mencurigakan">Pencurian / Mencurigakan</option>
                        <option value="Kebakaran / Potensi Bahaya">Kebakaran / Potensi Bahaya</option>
                        <option value="Keributan / Keramaian">Keributan / Keramaian</option>
                        <option value="Kerusakan Fasilitas Publik">Kerusakan Fasilitas Publik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Kronologi / Lokasi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan lokasi kejadian..." required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm"></textarea>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-laporkan-kejadian').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-xs">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white font-bold rounded-xl text-xs">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusRonda(id) {
    if (!confirm('Hapus jadwal ronda ini?')) return;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
    fetch('/ronda/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json()).then(data => { alert(data.message); switchPage('keamanan', document.querySelector('.menu-active')); });
}
function hapusIncident(id) {
    if (!confirm('Tandai laporan selesai dan hapus?')) return;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
    fetch('/incident/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json()).then(data => { alert(data.message); switchPage('keamanan', document.querySelector('.menu-active')); });
}
</script>
