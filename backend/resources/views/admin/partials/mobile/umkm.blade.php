<div class="p-3 space-y-3">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="fa-solid fa-shop text-sm"></i></div>
            <div>
                <h1 class="text-sm font-black text-gray-800">Direktori UMKM</h1>
                <p class="text-[9px] text-gray-500">Usaha mikro warga RT</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-tambah-umkm').classList.remove('hidden')" class="bg-indigo-600 text-white font-bold px-3 py-2 rounded-xl text-[10px] shadow-sm"><i class="fa-solid fa-plus text-[8px]"></i> Daftar Usaha</button>
    </div>

    <!-- Grid Cards -->
    <div class="space-y-3">
        @forelse($list_umkm ?? [] as $item)
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-[8px] font-bold">{{ $item->kategori }}</span>
                    <span class="text-[9px] text-emerald-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle text-[6px]"></i> {{ $item->status }}</span>
                </div>
                <h3 class="text-sm font-black text-gray-800 mb-0.5">{{ $item->nama_usaha }}</h3>
                <p class="text-[10px] font-bold text-gray-400 mb-2"><i class="fa-solid fa-user-tie text-[8px]"></i> Pemilik: {{ $item->pemilik }}</p>
                <p class="text-[10px] text-gray-600 line-clamp-2 mb-4">{{ $item->deskripsi ?? 'Usaha lokal warga RT.' }}</p>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:underline flex items-center gap-1">
                    <i class="fa-brands fa-whatsapp text-xs"></i> Hubungi WA
                </a>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']) || Auth::user()->name == $item->pemilik)
                <button onclick="hapusUmkm({{ $item->id }})" class="w-6 h-6 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-trash text-[9px]"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white p-6 rounded-xl border border-gray-100 text-center text-gray-400 italic text-xs">
            Belum ada usaha UMKM warga.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-umkm" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl">
        <button onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xs font-black text-gray-800 mb-4">Daftarkan UMKM Warga</h3>
        <form id="form-umkm" action="/umkm/store" method="POST" onsubmit="simpanDataUmum(event, 'form-umkm', 'umkm')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Usaha / Toko</label>
                    <input type="text" name="nama_usaha" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Pemilik</label>
                        <input type="text" name="pemilik" value="{{ Auth::user()->name }}" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Kategori Usaha</label>
                        <select name="kategori" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                            <option value="Kuliner & Makanan">Kuliner & Makanan</option>
                            <option value="Jasa & Perbaikan">Jasa & Perbaikan</option>
                            <option value="Fashion & Pakaian">Fashion & Pakaian</option>
                            <option value="Kelontong & Sembako">Kelontong & Sembako</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Kontak / WA</label>
                    <input type="text" name="kontak" placeholder="08123456789" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm"></textarea>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 text-xs">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-xs">Daftar</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusUmkm(id) {
    if (!confirm('Hapus daftar UMKM ini?')) return;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
    fetch('/umkm/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json()).then(data => { alert(data.message); switchPage('umkm', document.querySelector('.menu-active')); });
}
</script>
