<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-store absolute -bottom-4 -right-2 text-[70px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-3">
            <div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <div class="w-6 h-6 rounded-lg bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-store text-blue-300 text-[10px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-blue-300/80">Layanan Warga</span>
                </div>
                <h1 class="text-lg font-black tracking-tight leading-tight">Koperasi Warga RT</h1>
                <p class="text-[10px] text-white/50 font-medium mt-0.5">Simpan pinjam & produk sembako warga.</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[70px]">
                    <p class="text-lg font-black text-white leading-none">{{ count($list_koperasi ?? []) }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-blue-300/70 mt-0.5">Barang</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[70px]">
                    <p class="text-lg font-black text-white leading-none">{{ $warga ?? 0 }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-blue-300/70 mt-0.5">Anggota</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                <button onclick="document.getElementById('modal-tambah-koperasi').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-400 text-white font-bold px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer text-xs shadow-lg shadow-blue-500/30 border border-blue-400/30 ml-auto">
                    <i class="fa-solid fa-plus-circle text-xs"></i> Tambah
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== CARD LIST ========== --}}
    <div class="space-y-2">
        @forelse($list_koperasi ?? [] as $item)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-gray-800 text-[12px] truncate">{{ $item->nama_produk }}</p>
                            <span class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded text-[8px] font-bold shrink-0">{{ $item->kategori }}</span>
                        </div>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-[11px] font-bold text-emerald-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            <span class="text-[9px] text-gray-500">Stok: {{ $item->stok }} Pcs</span>
                        </div>
                        <p class="text-[9px] text-gray-400 mt-0.5"><i class="fa-solid fa-user text-[7px] mr-0.5"></i> {{ $item->penjual }}</p>
                    </div>
                    <div class="shrink-0">
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                        <button onclick="hapusKoperasi({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                            <i class="fa-solid fa-trash text-[9px]"></i>
                        </button>
                        @else
                        <span class="text-[9px] text-gray-400 italic">Lihat</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada produk koperasi terdaftar.
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-koperasi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-koperasi').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
        <h3 class="text-sm font-black text-gray-800 mb-4">Tambah Produk Koperasi</h3>
        <form id="form-koperasi" action="/koperasi/store" method="POST" onsubmit="simpanDataUmum(event, 'form-koperasi', 'koperasi')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Kategori</label>
                        <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Sembako">Sembako</option>
                            <option value="Makanan & Minuman">Makanan & Minuman</option>
                            <option value="Kerajinan">Kerajinan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Harga (Rp)</label>
                        <input type="number" name="harga" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Stok</label>
                        <input type="number" name="stok" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Penjual / Unit</label>
                        <input type="text" name="penjual" value="Koperasi RT" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-koperasi').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 text-xs">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusKoperasi(id) {
    if (!confirm('Hapus produk ini dari koperasi?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/koperasi/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('koperasi', document.querySelector('.menu-active')); });
}
</script>
