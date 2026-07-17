<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- ============================================================== -->
    <!-- HERO BANNER                                                    -->
    <!-- ============================================================== -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#134e4a] via-[#115e59] to-[#0f172a] shadow-lg">
        <!-- Decorative background icon -->
        <i class="fa-solid fa-calendar-check absolute -right-4 -bottom-4 text-[6rem] text-white/[0.04] rotate-12 pointer-events-none select-none"></i>

        <div class="relative z-10 px-4 py-4 flex flex-col gap-3">
            <!-- Left: text content -->
            <div class="space-y-2">
                <!-- Badge -->
                <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-3 py-1">
                    <i class="fa-solid fa-calendar-check text-teal-300 text-[10px]"></i>
                    <span class="text-[10px] font-bold tracking-widest text-teal-100 uppercase">Agenda Kemasyarakatan</span>
                </div>

                <h1 class="text-lg font-black text-white tracking-tight leading-tight">
                    Agenda Kegiatan & Event
                </h1>
                <p class="text-xs text-teal-100/80 font-medium leading-relaxed">
                    Jadwal kerja bakti, rapat warga, pengajian, & acara kemasyarakatan
                </p>

                <!-- Stats badges -->
                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <div class="flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-1.5">
                        <div class="w-6 h-6 rounded-lg bg-teal-400/20 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-day text-teal-300 text-[10px]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-white leading-none">{{ count($list_kegiatan ?? []) }}</p>
                            <p class="text-[9px] text-teal-200/70 font-semibold uppercase tracking-wider">Agenda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-1.5">
                        <div class="w-6 h-6 rounded-lg bg-emerald-400/20 flex items-center justify-center">
                            <i class="fa-solid fa-users text-emerald-300 text-[10px]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-white leading-none">Aktif</p>
                            <p class="text-[9px] text-teal-200/70 font-semibold uppercase tracking-wider">Status</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: action button -->
            @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
            <div class="flex-shrink-0">
                <button onclick="document.getElementById('modal-tambah-kegiatan').classList.remove('hidden')" class="bg-teal-500 hover:bg-teal-400 text-white font-bold px-4 py-2 rounded-xl shadow-lg shadow-teal-900/30 hover:shadow-teal-400/30 transition-all flex items-center gap-2 cursor-pointer text-xs group w-full justify-center">
                    <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition">
                        <i class="fa-solid fa-plus text-xs"></i>
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
    <div class="grid grid-cols-1 gap-3">
        @forelse($list_kegiatan ?? [] as $item)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition-all duration-200 overflow-hidden">
            @if(!empty($item->gambar))
            @php
                $imgUrl = \Illuminate\Support\Str::startsWith($item->gambar, ['http://', 'https://']) 
                    ? $item->gambar 
                    : asset('storage/' . $item->gambar);
            @endphp
            <div class="h-36 w-full overflow-hidden relative border-b border-gray-100">
                <a href="{{ $imgUrl }}" target="_blank">
                    <img src="{{ $imgUrl }}" 
                         onerror="this.onerror=null; this.src='/storage/{{ $item->gambar }}';"
                         class="w-full h-full object-cover" 
                         alt="{{ $item->nama_kegiatan }}">
                </a>
            </div>
            @endif
            <div class="p-3.5">
                <div class="flex items-center justify-between mb-2">
                    <span class="bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1">
                        <i class="fa-solid fa-clock text-[9px]"></i> {{ $item->waktu }}
                    </span>
                    <span class="text-[10px] text-gray-400 font-bold bg-gray-50 px-2.5 py-1 rounded-full">{{ $item->tanggal }}</span>
                </div>
                <h3 class="text-sm font-black text-gray-800 mb-1.5 leading-snug">{{ $item->nama_kegiatan }}</h3>
                <p class="text-[10px] font-bold text-gray-500 mb-2 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-teal-500"></i> Lokasi: {{ $item->lokasi }}
                </p>
                <p class="text-[10px] text-gray-500 line-clamp-2 mb-3 leading-relaxed">{{ $item->deskripsi ?? 'Kegiatan kebersamaan warga RT.' }}</p>
            </div>
            <div class="px-3.5 pb-3.5 pt-2.5 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[10px] font-bold text-teal-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[9px]"></i> Terbuka untuk Warga
                </span>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="hapusKegiatan({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all inline-flex items-center justify-center cursor-pointer" title="Hapus agenda">
                    <i class="fa-solid fa-trash text-[10px]"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-xl border border-gray-100 shadow-sm text-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-xmark text-lg text-teal-300"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-400 text-sm">Belum ada agenda kegiatan</p>
                    <p class="text-xs text-gray-300 mt-1">Jadwal kegiatan yang dibuat akan tampil di sini</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Tambah -->
<div id="modal-tambah-kegiatan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center backdrop-blur-sm p-0">
    <div class="bg-white rounded-t-2xl w-full max-w-[95vw] p-5 relative shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-tambah-kegiatan').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-base"></i></button>
        <h3 class="text-base font-black text-gray-800 mb-4">Tambah Agenda Kegiatan</h3>
        <form id="form-kegiatan" action="/kegiatan/store" method="POST" enctype="multipart/form-data" onsubmit="simpanDataUmum(event, 'form-kegiatan', 'kegiatan')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Nama Kegiatan / Acara</label>
                    <input type="text" name="nama_kegiatan" placeholder="Kerja Bakti Bersih Lingkungan" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Waktu / Jam</label>
                        <input type="text" name="waktu" placeholder="07:30 WIB - Selesai" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Lokasi Tempat Pelaksanaan</label>
                    <input type="text" name="lokasi" placeholder="Lapangan Utama RT 01" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Poster / Foto Banner (Opsional)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 py-2 px-3 text-xs rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Deskripsi & Imbauan Peralatan</label>
                    <textarea name="deskripsi" rows="2" placeholder="Harap warga membawa sapu dan cangkul..." class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-kegiatan').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg shadow-teal-200 text-xs">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

<script>
function hapusKegiatan(id) {
    Swal.fire({
        title: 'Hapus Agenda?',
        text: "Agenda kegiatan ini akan dihapus.",
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
            fetch('/kegiatan/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json()).then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Agenda kegiatan dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('kegiatan'); }
                switchPage('kegiatan', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
