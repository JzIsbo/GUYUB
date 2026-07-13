<div class="p-3 space-y-3 max-w-full mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#78350f] via-[#92400e] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg">
        <div class="absolute top-0 right-0 w-40 h-40 bg-amber-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-yellow-500/5 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-hands-holding-child absolute -bottom-4 -right-2 text-[80px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-3">
            <div>
                <div class="flex items-center gap-1.5 mb-2">
                    <div class="w-6 h-6 rounded-lg bg-amber-500/20 border border-amber-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-hands-holding-child text-amber-300 text-[10px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-amber-300/80">Layanan Sosial Warga</span>
                </div>
                <h1 class="text-lg font-black tracking-tight">Rukun Kematian (Rukem)</h1>
                <p class="text-[11px] text-white/50 font-medium mt-0.5">Santunan duka cita & kas duka warga.</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[90px] flex-1">
                    <p class="text-lg font-black text-white leading-none"><span class="text-[8px] font-normal">Rp</span> {{ number_format($total_santunan ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-amber-300/70 mt-0.5">Santunan Disalurkan</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[70px]">
                    <p class="text-lg font-black text-white leading-none">{{ count($list_rukem ?? []) }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-amber-300/70 mt-0.5">Kejadian Duka</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-rukem').classList.remove('hidden')" class="bg-amber-500 hover:bg-amber-400 text-white font-bold px-4 py-2.5 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer text-xs shadow-lg shadow-amber-500/30 border border-amber-400/30 w-full justify-center">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Catat Santunan
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== CARD LIST ========== --}}
    <div class="space-y-2">
        @forelse($list_rukem ?? [] as $item)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-gray-800 text-[12px] truncate">{{ $item->nama_almarhum }}</p>
                        <p class="text-[9px] text-gray-500 mt-0.5"><i class="fa-solid fa-users text-[7px] mr-0.5"></i> {{ $item->keluarga_duka }}</p>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <span class="text-[11px] font-bold text-amber-600">Rp {{ number_format($item->santunan_diserahkan, 0, ',', '.') }}</span>
                            <span class="bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded text-[8px] font-bold">{{ $item->status_santunan }}</span>
                        </div>
                        <p class="text-[9px] text-gray-400 mt-0.5"><i class="fa-regular fa-calendar text-[7px] mr-0.5"></i> {{ $item->tanggal_duka }}</p>
                    </div>
                    <div class="shrink-0">
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                        <button onclick="hapusRukem({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                            <i class="fa-solid fa-trash text-[9px]"></i>
                        </button>
                        @else
                        <span class="text-[9px] text-gray-400 italic">Tercatat</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada catatan berita duka/rukem.
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-rukem" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-rukem').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 mb-4">Catat Santunan Berita Duka</h3>
        <form id="form-rukem" action="/rukem/store" method="POST" onsubmit="simpanDataUmum(event, 'form-rukem', 'rukem')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Nama Almarhum / Almarhumah</label>
                    <input type="text" name="nama_almarhum" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Keluarga Ahli Waris / Penerima</label>
                    <input type="text" name="keluarga_duka" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Tanggal Duka</label>
                        <input type="date" name="tanggal_duka" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Nominal (Rp)</label>
                        <input type="number" name="santunan_diserahkan" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Status Santunan</label>
                    <select name="status_santunan" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="Tersalurkan Langsung">Tersalurkan Langsung</option>
                        <option value="Dalam Proses Penyiapan">Dalam Proses Penyiapan</option>
                    </select>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-rukem').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-200 text-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusRukem(id) {
    if (!confirm('Hapus data duka cita ini?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/rukem/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('rukem', document.querySelector('.menu-active')); });
}
</script>
