<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#064e3b] via-[#065f46] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg">
        <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-recycle absolute -bottom-4 -right-2 text-[80px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-3">
            <div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500/20 border border-emerald-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-recycle text-emerald-300 text-[10px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-emerald-300/80">Layanan Warga</span>
                </div>
                <h1 class="text-lg font-black tracking-tight">Bank Sampah RT</h1>
                <p class="text-[10px] text-white/50 font-medium mt-0.5">Kelola setoran daur ulang warga.</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <!-- Quick Stats Badge 1 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[85px]">
                    <p class="text-lg font-black text-white leading-none">{{ number_format($total_berat ?? 0, 1, ',', '.') }} <span class="text-[9px] font-normal">Kg</span></p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-emerald-300/70 mt-0.5">Terkumpul</p>
                </div>

                <!-- Quick Stats Badge 2 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[85px]">
                    <p class="text-lg font-black text-white leading-none"><span class="text-[9px] font-normal">Rp</span> {{ number_format($total_rupiah ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-emerald-300/70 mt-0.5">Nilai Tabungan</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                <button onclick="document.getElementById('modal-tambah-sampah').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer text-xs shadow-lg shadow-emerald-500/30 border border-emerald-400/30">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Catat Setoran
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Setoran -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-3 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-black text-gray-800 text-xs">Riwayat Setoran Sampah Warga</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[8px] font-extrabold tracking-widest border-b border-gray-100">
                        <th class="py-2.5 px-3">Tanggal</th>
                        <th class="py-2.5 px-3">Nama</th>
                        <th class="py-2.5 px-3">Kategori</th>
                        <th class="py-2.5 px-3">Berat</th>
                        <th class="py-2.5 px-3">Nilai</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_deposit ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-2.5 px-3 text-gray-500 whitespace-nowrap">{{ $item->tanggal }}</td>
                        <td class="py-2.5 px-3 font-bold text-gray-800 max-w-[80px] truncate">{{ $item->nama_warga }}</td>
                        <td class="py-2.5 px-3"><span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full text-[9px] font-bold">{{ $item->jenis_sampah }}</span></td>
                        <td class="py-2.5 px-3 font-bold whitespace-nowrap">{{ $item->berat_kg }} Kg</td>
                        <td class="py-2.5 px-3 font-bold text-emerald-600 whitespace-nowrap">Rp {{ number_format($item->total_rupiah, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-3 text-right">
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                            <button onclick="hapusBankSampah({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                            @else
                            <span class="text-[9px] text-gray-400 italic">Tercatat</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">Belum ada catatan setoran bank sampah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-sampah" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-2">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-sampah').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 mb-4">Catat Setoran Bank Sampah</h3>
        <form id="form-bank-sampah" action="/bank-sampah/store" method="POST" onsubmit="simpanDataUmum(event, 'form-bank-sampah', 'bank-sampah')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Nama Warga Penyetor</label>
                    <input type="text" name="nama_warga" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Jenis Sampah</label>
                        <select name="jenis_sampah" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="Botol Plastik">Botol Plastik</option>
                            <option value="Kardus & Kertas">Kardus & Kertas</option>
                            <option value="Besi & Logam">Besi & Logam</option>
                            <option value="Minyak Jelantah">Minyak Jelantah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Tanggal Setor</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Berat Total (Kg)</label>
                        <input type="number" step="0.1" name="berat_kg" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Konversi Rupiah (Rp)</label>
                        <input type="number" name="total_rupiah" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-sampah').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 text-sm">Simpan Setoran</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusBankSampah(id) {
    if (!confirm('Hapus riwayat setoran ini?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/bank-sampah/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('bank-sampah', document.querySelector('.menu-active')); });
}
</script>
