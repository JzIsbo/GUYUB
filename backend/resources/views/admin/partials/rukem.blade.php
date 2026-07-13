<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#78350f] via-[#92400e] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-amber-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-yellow-500/5 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-hands-holding-child absolute -bottom-6 -right-4 text-[130px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/20 border border-amber-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-hands-holding-child text-amber-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-amber-300/80">Layanan Sosial Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Rukun Kematian (Rukem)</h1>
                <p class="text-sm text-white/50 font-medium mt-1">Sistem informasi santunan duka cita, kepedulian sosial, & kas duka warga.</p>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Quick Stats Badge 1 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none"><span class="text-xs font-normal">Rp</span> {{ number_format($total_santunan ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-amber-300/70 mt-1">Santunan Disalurkan</p>
                </div>

                <!-- Quick Stats Badge 2 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none">{{ count($list_rukem ?? []) }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-amber-300/70 mt-1">Kejadian Duka</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-rukem').classList.remove('hidden')" class="bg-amber-500 hover:bg-amber-400 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2.5 cursor-pointer text-sm shadow-lg shadow-amber-500/30 hover:-translate-y-0.5 border border-amber-400/30">
                    <i class="fa-solid fa-plus-circle text-base"></i> Catat Santunan Duka
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Rukem -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-black text-gray-800">Riwayat Berita Duka & Santunan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-extrabold tracking-widest border-b border-gray-100">
                        <th class="py-4 px-6">Tanggal Duka</th>
                        <th class="py-4 px-6">Nama Almarhum / Almarhumah</th>
                        <th class="py-4 px-6">Keluarga Ahli Waris</th>
                        <th class="py-4 px-6">Nominal Santunan</th>
                        <th class="py-4 px-6">Status Penyaluran</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_rukem ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-gray-500">{{ $item->tanggal_duka }}</td>
                        <td class="py-4 px-6 font-bold text-gray-800">{{ $item->nama_almarhum }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ $item->keluarga_duka }}</td>
                        <td class="py-4 px-6 font-bold text-amber-600">Rp {{ number_format($item->santunan_diserahkan, 0, ',', '.') }}</td>
                        <td class="py-4 px-6">
                            <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">{{ $item->status_santunan }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                            <button onclick="hapusRukem({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            @else
                            <span class="text-xs text-gray-400 italic">Tercatat</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 italic">Belum ada catatan berita duka/rukem.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-rukem" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-rukem').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Catat Santunan Berita Duka</h3>
        <form id="form-rukem" action="/rukem/store" method="POST" onsubmit="simpanDataUmum(event, 'form-rukem', 'rukem')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Almarhum / Almarhumah</label>
                    <input type="text" name="nama_almarhum" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keluarga Ahli Waris / Penerima Santunan</label>
                    <input type="text" name="keluarga_duka" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Berita Duka</label>
                        <input type="date" name="tanggal_duka" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Santunan (Rp)</label>
                        <input type="number" name="santunan_diserahkan" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Santunan</label>
                    <select name="status_santunan" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="Tersalurkan Langsung">Tersalurkan Langsung</option>
                        <option value="Dalam Proses Penyiapan">Dalam Proses Penyiapan</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-rukem').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-200">Simpan Data</button>
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
