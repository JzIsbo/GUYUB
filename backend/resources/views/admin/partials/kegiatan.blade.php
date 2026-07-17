<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- ============================================================== -->
    <!-- HERO BANNER                                                    -->
    <!-- ============================================================== -->
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#134e4a] via-[#115e59] to-[#0f172a] shadow-xl">
        <!-- Decorative background icon -->
        <i class="fa-solid fa-calendar-check absolute -right-6 -bottom-6 text-[11rem] text-white/[0.04] rotate-12 pointer-events-none select-none"></i>
        <i class="fa-solid fa-calendar-check absolute right-28 top-4 text-[5rem] text-white/[0.06] -rotate-6 pointer-events-none select-none"></i>

        <div class="relative z-10 px-6 py-8 sm:px-10 sm:py-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <!-- Left: text content -->
            <div class="space-y-4">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-4 py-1.5">
                    <i class="fa-solid fa-calendar-check text-teal-300 text-xs"></i>
                    <span class="text-[11px] font-bold tracking-widest text-teal-100 uppercase">Agenda Kemasyarakatan</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                    Agenda Kegiatan & Event
                </h1>
                <p class="text-sm text-teal-100/80 font-medium max-w-xl leading-relaxed">
                    Jadwal kerja bakti, rapat warga, pengajian, & acara kemasyarakatan
                </p>

                <!-- Stats badges -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <div class="flex items-center gap-2.5 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-2.5">
                        <div class="w-8 h-8 rounded-xl bg-teal-400/20 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-day text-teal-300 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-lg font-black text-white leading-none">{{ count($list_kegiatan ?? []) }}</p>
                            <p class="text-[10px] text-teal-200/70 font-semibold uppercase tracking-wider">Agenda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-400/20 flex items-center justify-center">
                            <i class="fa-solid fa-users text-emerald-300 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-lg font-black text-white leading-none">Aktif</p>
                            <p class="text-[10px] text-teal-200/70 font-semibold uppercase tracking-wider">Status</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: action button -->
            @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
            <div class="flex-shrink-0">
                <button onclick="document.getElementById('modal-tambah-kegiatan').classList.remove('hidden')" class="bg-teal-500 hover:bg-teal-400 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-teal-900/30 hover:shadow-teal-400/30 transition-all flex items-center gap-2.5 cursor-pointer text-sm group">
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition">
                        <i class="fa-solid fa-plus text-sm"></i>
                    </div>
                    Buat Agenda Kegiatan
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- GRID KEGIATAN                                                  -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($list_kegiatan ?? [] as $item)
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
            @if(!empty($item->gambar))
            @php
                $imgUrl = \Illuminate\Support\Str::startsWith($item->gambar, ['http://', 'https://']) 
                    ? $item->gambar 
                    : asset('storage/' . $item->gambar);
            @endphp
            <div class="h-44 w-full overflow-hidden relative border-b border-gray-100">
                <a href="{{ $imgUrl }}" target="_blank" title="Klik untuk gambar penuh">
                    <img src="{{ $imgUrl }}" 
                         onerror="this.onerror=null; this.src='/storage/{{ $item->gambar }}';"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                         alt="{{ $item->nama_kegiatan }}">
                </a>
            </div>
            @endif
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-teal-50 text-teal-700 px-3.5 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-clock text-[10px]"></i> {{ $item->waktu }}
                    </span>
                    <span class="text-xs text-gray-400 font-bold bg-gray-50 px-3 py-1.5 rounded-full">{{ $item->tanggal }}</span>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2 leading-snug">{{ $item->nama_kegiatan }}</h3>
                <p class="text-xs font-bold text-gray-500 mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-location-dot text-teal-500"></i> Lokasi: {{ $item->lokasi }}
                </p>
                <p class="text-xs text-gray-500 line-clamp-3 mb-6 leading-relaxed">{{ $item->deskripsi ?? 'Kegiatan kebersamaan warga RT.' }}</p>
            </div>
            <div class="px-6 pb-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs font-bold text-teal-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Terbuka untuk Warga
                </span>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="hapusKegiatan({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all inline-flex items-center justify-center cursor-pointer" title="Hapus agenda">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-16 rounded-[2rem] border border-gray-100 shadow-sm text-center">
            <div class="flex flex-col items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-xmark text-2xl text-teal-300"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-400">Belum ada agenda kegiatan</p>
                    <p class="text-sm text-gray-300 mt-1">Jadwal kegiatan yang dibuat akan tampil di sini</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Tambah -->
<div id="modal-tambah-kegiatan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-kegiatan').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Tambah Agenda Kegiatan</h3>
        <form id="form-kegiatan" action="/kegiatan/store" method="POST" enctype="multipart/form-data" onsubmit="simpanDataUmum(event, 'form-kegiatan', 'kegiatan')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Kegiatan / Acara</label>
                    <input type="text" name="nama_kegiatan" placeholder="Kerja Bakti Bersih Lingkungan" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu / Jam</label>
                        <input type="text" name="waktu" placeholder="07:30 WIB - Selesai" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Tempat Pelaksanaan</label>
                    <input type="text" name="lokasi" placeholder="Lapangan Utama RT 01" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Poster / Foto Banner Kegiatan (Opsional)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2.5 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi & Imbauan Peralatan</label>
                    <textarea name="deskripsi" rows="3" placeholder="Harap warga membawa sapu dan cangkul..." class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-kegiatan').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-teal-200">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusKegiatan(id) {
    Swal.fire({
        title: 'Hapus Agenda Kegiatan?',
        text: "Agenda kegiatan ini akan dihapus dari daftar.",
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
            const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
            fetch('/kegiatan/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json()).then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Agenda kegiatan telah dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl p-6 font-sans' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('kegiatan'); }
                switchPage('kegiatan', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
