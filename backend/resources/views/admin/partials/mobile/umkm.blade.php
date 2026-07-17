<div class="p-3 space-y-3">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="fa-solid fa-shop text-sm"></i></div>
            <div>
                <h1 class="text-sm font-black text-gray-800">Direktori UMKM</h1>
                <p class="text-[9px] text-gray-500">Usaha mikro warga</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-tambah-umkm').classList.remove('hidden')" class="bg-indigo-600 text-white font-bold px-3 py-2 rounded-xl text-[10px] shadow-sm"><i class="fa-solid fa-plus text-[8px]"></i> Daftar Usaha</button>
    </div>

    <!-- Grid Cards -->
    <div class="space-y-3">
        @forelse($list_umkm ?? [] as $item)
        <div class="bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Image Header Mobile -->
                <div class="relative h-32 w-full bg-slate-100 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ $item->gambar }}" alt="{{ $item->nama_usaha }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-indigo-50/30">
                            <i class="fa-solid fa-store text-2xl mb-1 opacity-50"></i>
                            <span class="text-[9px] font-semibold">Tidak Ada Foto</span>
                        </div>
                    @endif
                    <div class="absolute top-2 left-2 right-2 flex items-center justify-between pointer-events-none">
                        <span class="bg-white/90 backdrop-blur-md text-indigo-700 px-2 py-0.5 rounded-full text-[8px] font-extrabold shadow-sm border border-white/50">{{ $item->kategori }}</span>
                        <span class="bg-emerald-500/90 backdrop-blur-md text-white px-2 py-0.5 rounded-full text-[7px] font-extrabold shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-circle text-[5px]"></i> {{ $item->status }}
                        </span>
                    </div>
                </div>

                <div class="p-3">
                    <h3 class="text-sm font-black text-gray-800 mb-0.5">{{ $item->nama_usaha }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 mb-1.5 flex items-center gap-1 flex-wrap">
                        <span><i class="fa-solid fa-user-tie text-[8px] text-indigo-400"></i> {{ $item->pemilik }}</span>
                        @if($item->lokasi)
                            <span class="text-gray-300">•</span>
                            <span class="text-indigo-600 font-semibold bg-indigo-50/80 px-1.5 py-0.5 rounded text-[8px]"><i class="fa-solid fa-location-dot text-[7px]"></i> {{ $item->lokasi }}</span>
                        @endif
                    </p>
                    <p class="text-[10px] text-gray-600 line-clamp-2">{{ $item->deskripsi ?? 'Usaha lokal warga RT.' }}</p>
                </div>
            </div>

            <div class="p-3 pt-2 border-t border-gray-50 flex items-center justify-between">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:underline flex items-center gap-1 bg-emerald-50 px-2.5 py-1.5 rounded-lg">
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
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xs font-black text-gray-800 mb-4">Daftarkan UMKM Warga</h3>
        <form id="form-umkm" onsubmit="simpanUmkmWithFile(event)">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Usaha / Toko</label>
                    <input type="text" name="nama_usaha" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Pemilik</label>
                        <input type="hidden" name="pemilik" id="umkm_pemilik_hidden_mobile" value="{{ Auth::user()->name }}">
                        <div class="relative">
                            <input type="text" id="umkm_pemilik_search_input_mobile" value="{{ Auth::user()->name }}" placeholder="🔍 Cari & pilih nama..." 
                                   onfocus="showDropdown('umkm_pemilik_dropdown_mobile')" 
                                   onkeyup="filterCustomDropdown('umkm_pemilik_search_input_mobile', 'umkm_pemilik_dropdown_mobile')" 
                                   autocomplete="off"
                                   class="w-full bg-gray-50 border py-2 px-3 pr-7 rounded-xl text-sm font-bold text-gray-700">
                            <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>

                            <div id="umkm_pemilik_dropdown_mobile" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl z-50 max-h-52 overflow-y-auto divide-y divide-gray-50">
                                @foreach($all_warga ?? [] as $w)
                                    <div onclick="selectUmkmPemilikOptionMobile('{{ addslashes($w->nama_lengkap) }}')" 
                                         class="dropdown-item-m px-3 py-2 hover:bg-indigo-50 cursor-pointer transition flex items-center justify-between text-[11px] font-semibold text-gray-700">
                                        <div>
                                            <span class="block font-bold">{{ $w->nama_lengkap }}</span>
                                            <span class="text-[9px] text-gray-400 font-normal">Blok {{ $w->blok_rumah }}</span>
                                        </div>
                                        <span class="text-[9px] text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded-full font-bold">{{ $w->umur ? $w->umur.' Thn' : '-' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Foto Produk / Usaha</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full bg-gray-50 border font-medium text-gray-700 py-1.5 px-3 rounded-xl text-xs file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-indigo-50 file:text-indigo-600">
                    <p class="text-[8px] text-gray-400 mt-0.5">*Format: JPG, PNG, WEBP (Maks 2MB)</p>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Kontak / WA</label>
                        <input type="text" name="kontak" placeholder="08123456789" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Lokasi / Alamat Toko</label>
                        <input type="text" name="lokasi" placeholder="Cth: Blok A1 No. 12" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm"></textarea>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-umkm').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 text-xs">Batal</button>
                <button type="submit" id="btn-submit-umkm" class="flex-1 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-xs">Daftar</button>
            </div>
        </form>
    </div>
</div>

<script>
function selectUmkmPemilikOptionMobile(nama) {
    document.getElementById('umkm_pemilik_search_input_mobile').value = nama;
    document.getElementById('umkm_pemilik_hidden_mobile').value = nama;
    document.getElementById('umkm_pemilik_dropdown_mobile').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    const pInputM = document.getElementById('umkm_pemilik_search_input_mobile');
    const pDropM = document.getElementById('umkm_pemilik_dropdown_mobile');
    if (pInputM && pDropM && !pInputM.contains(e.target) && !pDropM.contains(e.target)) {
        pDropM.classList.add('hidden');
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
        btn.innerHTML = 'Daftar';
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
        btn.innerHTML = 'Daftar';
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
            popup: 'rounded-2xl p-4 shadow-xl font-sans text-xs',
            confirmButton: 'rounded-xl font-bold px-4 py-2 text-[11px]',
            cancelButton: 'rounded-xl font-bold px-4 py-2 text-[11px]'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
            fetch('/umkm/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json()).then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Data UMKM dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('umkm'); }
                switchPage('umkm', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
