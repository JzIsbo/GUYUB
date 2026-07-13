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
                <!-- Quick Stats Badge 1 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[90px] flex-1">
                    <p class="text-lg font-black text-white leading-none"><span class="text-[8px] font-normal">Rp</span> {{ number_format($total_santunan ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-amber-300/70 mt-0.5">Santunan Disalurkan</p>
                </div>

                <!-- Quick Stats Badge 2 -->
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

    <!-- Tabel Rukem -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-black text-gray-800 text-xs">Riwayat Berita Duka & Santunan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[8px] font-extrabold tracking-widest border-b border-gray-100">
                        <th class="py-2.5 px-3">Tanggal</th>
                        <th class="py-2.5 px-3">Almarhum/ah</th>
                        <th class="py-2.5 px-3">Ahli Waris</th>
                        <th class="py-2.5 px-3">Santunan</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_rukem ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-2.5 px-3 text-gray-500 whitespace-nowrap">{{ $item->tanggal_duka }}</td>
                        <td class="py-2.5 px-3 font-bold text-gray-800">{{ $item->nama_almarhum }}</td>
                        <td class="py-2.5 px-3 text-gray-600">{{ $item->keluarga_duka }}</td>
                        <td class="py-2.5 px-3 font-bold text-amber-600 whitespace-nowrap">Rp {{ number_format($item->santunan_diserahkan, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-3">
                            <span class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full text-[9px] font-bold">{{ $item->status_santunan }}</span>
                        </td>
                        <td class="py-2.5 px-3 text-right">
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                            <button onclick="hapusRukem({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                            @else
                            <span class="text-[9px] text-gray-400 italic">Tercatat</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">Belum ada catatan berita duka/rukem.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
