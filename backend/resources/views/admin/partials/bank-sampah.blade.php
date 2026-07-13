<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#064e3b] via-[#065f46] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-recycle absolute -bottom-6 -right-4 text-[130px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-recycle text-emerald-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-emerald-300/80">Layanan Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Bank Sampah RT</h1>
                <p class="text-sm text-white/50 font-medium mt-1">Kelola penimbangan setoran daur ulang plastik, kertas, & logam warga.</p>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Quick Stats Badge 1 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none">{{ number_format($total_berat ?? 0, 1, ',', '.') }} <span class="text-xs font-normal">Kg</span></p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-emerald-300/70 mt-1">Terkumpul</p>
                </div>

                <!-- Quick Stats Badge 2 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none"><span class="text-xs font-normal">Rp</span> {{ number_format($total_rupiah ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-emerald-300/70 mt-1">Nilai Tabungan</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                <button onclick="document.getElementById('modal-tambah-sampah').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2.5 cursor-pointer text-sm shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 border border-emerald-400/30">
                    <i class="fa-solid fa-plus-circle text-base"></i> Catat Setoran
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Setoran -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-black text-gray-800">Riwayat Setoran Sampah Warga</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-extrabold tracking-widest border-b border-gray-100">
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Nama Warga</th>
                        <th class="py-4 px-6">Kategori Sampah</th>
                        <th class="py-4 px-6">Berat (Kg)</th>
                        <th class="py-4 px-6">Nilai Rupiah</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_deposit ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-gray-500">{{ $item->tanggal }}</td>
                        <td class="py-4 px-6 font-bold text-gray-800">{{ $item->nama_warga }}</td>
                        <td class="py-4 px-6"><span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold">{{ $item->jenis_sampah }}</span></td>
                        <td class="py-4 px-6 font-bold">{{ $item->berat_kg }} Kg</td>
                        <td class="py-4 px-6 font-bold text-emerald-600">Rp {{ number_format($item->total_rupiah, 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-right">
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                            <button onclick="hapusBankSampah({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            @else
                            <span class="text-xs text-gray-400 italic">Tercatat</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 italic">Belum ada catatan setoran bank sampah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-sampah" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-sampah').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Catat Setoran Bank Sampah</h3>
        <form id="form-bank-sampah" action="/bank-sampah/store" method="POST" onsubmit="simpanDataUmum(event, 'form-bank-sampah', 'bank-sampah')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Warga Penyetor</label>
                    <input type="text" name="nama_warga" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Sampah</label>
                        <select name="jenis_sampah" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="Botol Plastik">Botol Plastik</option>
                            <option value="Kardus & Kertas">Kardus & Kertas</option>
                            <option value="Besi & Logam">Besi & Logam</option>
                            <option value="Minyak Jelantah">Minyak Jelantah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Setor</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Berat Total (Kg)</label>
                        <input type="number" step="0.1" name="berat_kg" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Konversi Rupiah (Rp)</label>
                        <input type="number" name="total_rupiah" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-sampah').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200">Simpan Setoran</button>
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
