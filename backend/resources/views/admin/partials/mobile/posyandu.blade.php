<div class="p-3 space-y-3">
    <!-- Hero Banner -->
    <div class="bg-gradient-to-br from-[#4c0519] via-[#881337] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-xl">
        <i class="fa-solid fa-heart-pulse absolute -bottom-4 -right-2 text-[5rem] opacity-[0.03] rotate-12"></i>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-6 h-6 rounded-lg bg-rose-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-heart-pulse text-rose-300 text-[10px]"></i>
                </div>
                <span class="text-[8px] font-black uppercase tracking-[2px] text-rose-300/80">Kesehatan</span>
            </div>
            <h1 class="text-lg font-black tracking-tight">Posyandu Balita & Lansia</h1>
            <div class="flex items-center justify-between mt-3 gap-2">
                <div class="bg-white/5 border border-white/10 rounded-xl px-3 py-1.5 text-center flex-1">
                    <p class="text-lg font-black text-white leading-none">{{ count($list_posyandu ?? []) }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-wider text-rose-300/70 mt-0.5">Total Jadwal</p>
                </div>
                @if($isAdmin)
                <button onclick="document.getElementById('modal-tambah-posyandu').classList.remove('hidden')" class="bg-rose-500 text-white font-bold px-4 py-2.5 rounded-xl text-[10px] flex items-center gap-1 shadow-lg shadow-rose-500/30">
                    <i class="fa-solid fa-plus-circle text-xs"></i> Tambah Jadwal
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Posyandu List -->
    @forelse($list_posyandu ?? [] as $item)
    @php
        $pendaftaranItem = ($list_pendaftaran ?? collect())->where('posyandu_id', $item->id);
        $isBalita = str_contains(strtolower($item->target_peserta), 'balita') || str_contains(strtolower($item->target_peserta), 'ibu');
        $colorClass = $isBalita ? 'rose' : 'violet';
        $icon = $isBalita ? 'fa-baby' : 'fa-person-cane';
    @endphp
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden space-y-2">
        <div class="p-3 border-b border-gray-50 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-10 h-10 rounded-lg bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="font-black text-gray-800 text-[11px] truncate">{{ $item->nama_kegiatan }}</h3>
                    <p class="text-[8px] text-gray-400 mt-0.5"><i class="fa-regular fa-calendar mr-0.5"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/y') }} · {{ $item->lokasi }}</p>
                </div>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button onclick="bukaModalDaftar({{ $item->id }}, '{{ addslashes($item->nama_kegiatan) }}', '{{ $item->target_peserta }}')" class="bg-{{ $colorClass }}-600 text-white font-bold w-7 h-7 rounded-lg text-[10px] flex items-center justify-center" title="Daftar"><i class="fa-solid fa-user-plus text-[9px]"></i></button>
                @if($isAdmin)
                <button onclick="hapusPosyandu({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center" title="Hapus"><i class="fa-solid fa-trash text-[9px]"></i></button>
                @endif
            </div>
        </div>

        @if($item->keterangan)
        <div class="px-3 py-1.5 bg-{{ $colorClass }}-50/30 border-b border-gray-50 text-[9px] text-gray-500 font-medium">
            <i class="fa-solid fa-info-circle text-{{ $colorClass }}-400 mr-0.5"></i> {{ $item->keterangan }}
        </div>
        @endif

        <div class="p-3">
            <h4 class="font-bold text-gray-700 text-[10px] mb-2 flex items-center gap-1">
                <i class="fa-solid fa-users text-{{ $colorClass }}-400"></i>
                Peserta Terdaftar
                <span class="bg-{{ $colorClass }}-100 text-{{ $colorClass }}-700 px-1.5 py-0.2 rounded-full text-[8px] font-black">{{ $pendaftaranItem->count() }}</span>
            </h4>

            @if($pendaftaranItem->count() > 0)
            <div class="space-y-1.5">
                @foreach($pendaftaranItem as $peserta)
                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50/80 border border-gray-100 text-[10px]">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-gray-800 text-[10px] truncate">{{ $peserta->nama_peserta }}</p>
                        <p class="text-[8px] text-gray-400 mt-0.2 truncate">{{ $peserta->kategori }} · {{ $peserta->usia ?? '-' }} · {{ $peserta->tinggi_badan ? 'TB: '.$peserta->tinggi_badan.'cm' : '' }} {{ $peserta->berat_badan ? 'BB: '.$peserta->berat_badan.'kg' : '' }}</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0 ml-1">
                        @if($peserta->status == 'Terdaftar')
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-blue-50 text-blue-600">Daftar</span>
                        @elseif($peserta->status == 'Hadir')
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-green-50 text-green-600">Hadir</span>
                        @else
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-50 text-red-500">Absen</span>
                        @endif

                        @if($isAdmin)
                        <select onchange="ubahStatusPeserta({{ $peserta->id }}, this.value)" class="text-[8px] font-bold border rounded px-1 py-0.5 bg-white">
                            <option value="Terdaftar" {{ $peserta->status == 'Terdaftar' ? 'selected' : '' }}>Daftar</option>
                            <option value="Hadir" {{ $peserta->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="Tidak Hadir" {{ $peserta->status == 'Tidak Hadir' ? 'selected' : '' }}>Absen</option>
                        </select>
                        @endif

                        @if($isAdmin || $peserta->nama_pendaftar == $userName)
                        <button onclick="hapusPendaftaran({{ $peserta->id }})" class="w-5 h-5 rounded bg-red-50 text-red-400 flex items-center justify-center" title="Batalkan"><i class="fa-solid fa-xmark text-[8px]"></i></button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-[9px] text-gray-400 italic text-center py-2">Belum ada peserta.</p>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white p-6 rounded-xl border border-gray-50 shadow-sm text-center">
        <i class="fa-solid fa-heart-pulse text-gray-200 text-3xl mb-2"></i>
        <h3 class="text-sm font-black text-gray-800 mb-0.5">Belum Ada Agenda</h3>
    </div>
    @endforelse
</div>

<!-- Modal Tambah Jadwal -->
<div id="modal-tambah-posyandu" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl">
        <button onclick="document.getElementById('modal-tambah-posyandu').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xs font-black text-gray-800 mb-4">Tambah Jadwal Posyandu</h3>
        <form id="form-posyandu" action="/posyandu/store" method="POST" onsubmit="simpanDataUmum(event, 'form-posyandu', 'posyandu')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" placeholder="Posyandu" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Target Peserta</label>
                        <select name="target_peserta" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                            <option value="Balita (0-5 Tahun)">Balita (0-5 Tahun)</option>
                            <option value="Lansia (>60 Tahun)">Lansia (>60 Tahun)</option>
                            <option value="Ibu Hamil">Ibu Hamil</option>
                            <option value="Semua Warga">Semua Warga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Balai Warga" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Bawa buku KMS" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm"></textarea>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-posyandu').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 text-xs">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-rose-600 text-white font-bold rounded-xl text-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Daftar Peserta -->
<div id="modal-daftar-peserta" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-daftar-peserta').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 id="daftar-modal-title" class="text-xs font-black text-gray-800 mb-1">Daftarkan Peserta</h3>
        <p id="daftar-modal-subtitle" class="text-[9px] text-gray-400 mb-4">-</p>
        <form id="form-daftar-peserta" action="/posyandu/daftar" method="POST" onsubmit="simpanDataUmum(event, 'form-daftar-peserta', 'posyandu')">
            <input type="hidden" name="posyandu_id" id="daftar_posyandu_id">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Peserta</label>
                    <input type="text" name="nama_peserta" placeholder="Nama" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Usia</label>
                        <input type="text" name="usia" placeholder="2 Tahun / 65 Tahun" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Kategori</label>
                        <select name="kategori" id="daftar_kategori" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                            <option value="Balita">Balita</option>
                            <option value="Lansia">Lansia</option>
                            <option value="Ibu Hamil">Ibu Hamil</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Tinggi Badan (cm)</label>
                        <input type="text" name="tinggi_badan" placeholder="75" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Berat Badan (kg)</label>
                        <input type="text" name="berat_badan" placeholder="8.5" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Pendaftar</label>
                        @if(Auth::user()->role === 'Warga')
                        <input type="text" name="nama_pendaftar" value="{{ Auth::user()->name }}" readonly class="w-full bg-gray-100 border py-2 px-3 rounded-xl text-sm text-gray-500">
                        @else
                        <input type="text" name="nama_pendaftar" placeholder="Nama" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                        @endif
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Hubungan</label>
                        <select name="hubungan" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                            <option value="Ibu">Ibu</option><option value="Ayah">Ayah</option><option value="Nenek">Nenek</option><option value="Kakek">Kakek</option><option value="Cucu">Cucu</option><option value="Anak">Anak</option><option value="Keluarga">Keluarga</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Riwayat alergi / sakit" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm"></textarea>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-daftar-peserta').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 text-xs">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-rose-600 text-white font-bold rounded-xl text-xs">Daftar</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalDaftar(posyanduId, namaKegiatan, targetPeserta) {
    document.getElementById('daftar_posyandu_id').value = posyanduId;
    document.getElementById('daftar-modal-title').textContent = 'Daftarkan Peserta';
    document.getElementById('daftar-modal-subtitle').textContent = namaKegiatan;
    const kategoriSelect = document.getElementById('daftar_kategori');
    if (targetPeserta.toLowerCase().includes('lansia')) kategoriSelect.value = 'Lansia';
    else if (targetPeserta.toLowerCase().includes('hamil')) kategoriSelect.value = 'Ibu Hamil';
    else kategoriSelect.value = 'Balita';
    document.getElementById('modal-daftar-peserta').classList.remove('hidden');
}
function hapusPosyandu(id) {
    if (!confirm('Hapus agenda posyandu ini?')) return;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
    fetch('/posyandu/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json()).then(data => { alert(data.message); switchPage('posyandu', document.querySelector('.menu-active')); });
}
function hapusPendaftaran(id) {
    if (!confirm('Batalkan pendaftaran peserta?')) return;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
    fetch('/posyandu/daftar/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json()).then(data => { alert(data.message); switchPage('posyandu', document.querySelector('.menu-active')); });
}
function ubahStatusPeserta(id, status) {
    const fd = new FormData(); fd.append('id', id); fd.append('status', status); fd.append('_token', window.csrfToken);
    fetch('/posyandu/daftar/status', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json()).then(data => { alert(data.message); switchPage('posyandu', document.querySelector('.menu-active')); });
}
</script>
