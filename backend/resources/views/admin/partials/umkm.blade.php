<div class="p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-shop"></i>
                </div>
                Direktori UMKM Warga
            </h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Daftar usaha mikro, kuliner, & wirausaha mandiri buatan warga lingkungan.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-umkm').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer self-start md:self-auto text-sm">
            <i class="fa-solid fa-plus"></i> Daftarkan Usaha Saya
        </button>
    </div>

    <!-- Grid Usaha -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($list_umkm ?? [] as $item)
        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition group">
            <div>
                <!-- Image Header -->
                <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ $item->gambar }}" alt="{{ $item->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-indigo-50/30">
                            <i class="fa-solid fa-store text-4xl mb-2 opacity-50"></i>
                            <span class="text-xs font-semibold">Tidak Ada Foto</span>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between pointer-events-none">
                        <span class="bg-white/90 backdrop-blur-md text-indigo-700 px-3 py-1 rounded-full text-xs font-extrabold shadow-sm border border-white/50">{{ $item->kategori }}</span>
                        <span class="bg-emerald-500/90 backdrop-blur-md text-white px-2.5 py-1 rounded-full text-[10px] font-extrabold shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-circle text-[6px]"></i> {{ $item->status }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-black text-gray-800 mb-1 leading-snug">{{ $item->nama_usaha }}</h3>
                    <p class="text-xs font-bold text-gray-400 mb-2 flex items-center gap-1.5 flex-wrap">
                        <span><i class="fa-solid fa-user-tie text-indigo-400"></i> Pemilik: {{ $item->pemilik }}</span>
                        @if($item->lokasi)
                            <span class="text-gray-300">•</span>
                            <span class="text-indigo-600 font-semibold bg-indigo-50/80 px-2 py-0.5 rounded-lg border border-indigo-100/50"><i class="fa-solid fa-location-dot text-[10px]"></i> {{ $item->lokasi }}</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">{{ $item->deskripsi ?? 'Usaha lokal warga RT.' }}</p>
                </div>
            </div>

            <div class="px-6 pb-6 pt-4 border-t border-gray-50 flex items-center justify-between">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak) }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-1.5 bg-emerald-50 px-3 py-2 rounded-xl transition">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi WhatsApp
                </a>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']) || Auth::user()->name == $item->pemilik)
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
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Daftarkan UMKM Warga</h3>
        <form id="form-umkm" onsubmit="simpanUmkmWithFile(event)">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Usaha / Toko</label>
                    <input type="text" name="nama_usaha" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Pemilik</label>
                        <input type="hidden" name="pemilik" id="umkm_pemilik_hidden" value="{{ Auth::user()->name }}">
                        <div class="relative">
                            <input type="text" id="umkm_pemilik_search_input" value="{{ Auth::user()->name }}" placeholder="🔍 Cari & pilih nama warga..." 
                                   onfocus="showDropdown('umkm_pemilik_dropdown')" 
                                   onkeyup="filterCustomDropdown('umkm_pemilik_search_input', 'umkm_pemilik_dropdown')" 
                                   autocomplete="off"
                                   class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>

                            <div id="umkm_pemilik_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 max-h-56 overflow-y-auto divide-y divide-gray-50">
                                @foreach($all_warga ?? [] as $w)
                                    <div onclick="selectUmkmPemilikOption('{{ addslashes($w->nama_lengkap) }}')" 
                                         class="dropdown-item px-4 py-2.5 hover:bg-indigo-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                        <div>
                                            <span class="block font-bold">{{ $w->nama_lengkap }}</span>
                                            <span class="text-[10px] text-gray-400 font-normal">Blok {{ $w->blok_rumah }}</span>
                                        </div>
                                        <span class="text-[10px] text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-bold">{{ $w->umur ? $w->umur.' Thn' : '-' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Foto Produk / Usaha</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 py-2.5 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                    <p class="text-[10px] text-gray-400 mt-1">*Format: JPG, PNG, WEBP (Maks 2MB)</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Kontak / WhatsApp</label>
                        <input type="text" name="kontak" placeholder="08123456789" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi / Alamat Toko</label>
                        <input type="text" name="lokasi" placeholder="Cth: Blok A1 No. 12" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Usaha / Produk</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" id="btn-submit-umkm" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200">Daftarkan Usaha</button>
            </div>
        </form>
    </div>
</div>

<script>
function selectUmkmPemilikOption(nama) {
    document.getElementById('umkm_pemilik_search_input').value = nama;
    document.getElementById('umkm_pemilik_hidden').value = nama;
    document.getElementById('umkm_pemilik_dropdown').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    const pInput = document.getElementById('umkm_pemilik_search_input');
    const pDrop = document.getElementById('umkm_pemilik_dropdown');
    if (pInput && pDrop && !pInput.contains(e.target) && !pDrop.contains(e.target)) {
        pDrop.classList.add('hidden');
    }
});

function simpanUmkmWithFile(event) {
    event.preventDefault();
    const form = document.getElementById('form-umkm');
    const formData = new FormData(form);
    const btn = document.getElementById('btn-submit-umkm');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...';

    fetch('/umkm/store', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = 'Daftarkan Usaha';
        if (data.status === 'success') {
            alert(data.message);
            document.getElementById('modal-tambah-umkm').classList.add('hidden');
            form.reset();
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('umkm'); }
            switchPage('umkm', document.querySelector('.menu-active'));
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = 'Daftarkan Usaha';
        alert('Terjadi kesalahan saat mengunggah data.');
    });
}

function hapusUmkm(id) {
    Swal.fire({
        title: 'Hapus UMKM Warga?',
        text: "Data usaha warga ini akan dihapus dari direktori.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-3xl p-6 shadow-2xl font-sans',
            confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs',
            cancelButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('id', id);
            fd.append('_token', window.csrfToken);
            fetch('/umkm/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Data UMKM telah dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl p-6 font-sans' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('umkm'); }
                switchPage('umkm', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
