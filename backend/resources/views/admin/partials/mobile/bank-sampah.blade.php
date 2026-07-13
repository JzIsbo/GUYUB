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
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[85px]">
                    <p class="text-lg font-black text-white leading-none">{{ number_format($total_berat ?? 0, 1, ',', '.') }} <span class="text-[9px] font-normal">Kg</span></p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-emerald-300/70 mt-0.5">Terkumpul</p>
                </div>
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

    {{-- ========== CARD LIST ========== --}}
    <div class="space-y-2">
        @forelse($list_deposit ?? [] as $item)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-gray-800 text-[12px] truncate">{{ $item->nama_warga }}</p>
                            <span class="bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded text-[8px] font-bold shrink-0">{{ $item->jenis_sampah }}</span>
                        </div>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-[11px] font-bold text-gray-700">{{ $item->berat_kg }} Kg</span>
                            <span class="text-[11px] font-bold text-emerald-600">Rp {{ number_format($item->total_rupiah, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[9px] text-gray-400 mt-0.5"><i class="fa-regular fa-calendar text-[7px] mr-0.5"></i> {{ $item->tanggal }}</p>
                    </div>
                    <div class="shrink-0">
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                        <button onclick="hapusBankSampah({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
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
                Belum ada catatan setoran bank sampah.
            </div>
        @endforelse
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
