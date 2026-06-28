<div class="p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-shop"></i>
                </div>
                Direktori UMKM Warga RT
            </h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Daftar usaha mikro, kuliner, & wirausaha mandiri buatan warga lingkungan RT.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-umkm').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer self-start md:self-auto text-sm">
            <i class="fa-solid fa-plus"></i> Daftarkan Usaha Saya
        </button>
    </div>

    <!-- Grid Usaha -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($list_umkm ?? [] as $item)
        <div class="bg-white rounded-[2.5rem] p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold">{{ $item->kategori }}</span>
                    <span class="text-xs text-emerald-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle text-[8px]"></i> {{ $item->status }}</span>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-1">{{ $item->nama_usaha }}</h3>
                <p class="text-xs font-bold text-gray-400 mb-3"><i class="fa-solid fa-user-tie mr-1"></i> Pemilik: {{ $item->pemilik }}</p>
                <p class="text-xs text-gray-600 line-clamp-3 mb-6">{{ $item->deskripsi ?? 'Usaha lokal warga RT.' }}</p>
            </div>
            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak) }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:underline flex items-center gap-1.5">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi WhatsApp
                </a>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="hapusUmkm({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white p-12 rounded-[2.5rem] border border-gray-100 text-center text-gray-400 italic">
            Belum ada usaha UMKM warga yang terdaftar. Yuk daftarkan usaha Anda!
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-umkm" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Daftarkan UMKM Warga</h3>
        <form id="form-umkm" action="/umkm/store" method="POST" onsubmit="simpanDataUmum(event, 'form-umkm', 'umkm')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Usaha / Toko</label>
                    <input type="text" name="nama_usaha" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Pemilik</label>
                        <input type="text" name="pemilik" value="{{ Auth::user()->name }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori Usaha</label>
                        <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="Kuliner & Makanan">Kuliner & Makanan</option>
                            <option value="Jasa & Perbaikan">Jasa & Perbaikan</option>
                            <option value="Fashion & Pakaian">Fashion & Pakaian</option>
                            <option value="Kelontong & Sembako">Kelontong & Sembako</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Kontak / WhatsApp</label>
                    <input type="text" name="kontak" placeholder="08123456789" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Usaha / Produk</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200">Daftarkan Usaha</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusUmkm(id) {
    if (!confirm('Hapus daftar UMKM ini?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/umkm/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('umkm', document.querySelector('.menu-active')); });
}
</script>
