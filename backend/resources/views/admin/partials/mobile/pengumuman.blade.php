<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-bullhorn absolute -bottom-4 -right-2 text-[80px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-3">
            <div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <div class="w-6 h-6 rounded-lg bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn text-blue-300 text-[10px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-blue-300/80">Pusat Informasi</span>
                </div>
                <h1 class="text-lg font-black tracking-tight">Pengumuman Warga</h1>
                <p class="text-[11px] text-white/50 font-medium mt-0.5">Imbauan, jadwal & pengumuman RT</p>
            </div>

            <div class="flex items-center gap-2.5">
                <!-- Quick Stats Badge -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3.5 py-2 text-center min-w-[80px]">
                    <p class="text-lg font-black text-white leading-none">{{ count($list_pengumuman) }}</p>
                    <p class="text-[8px] font-bold uppercase tracking-widest text-blue-300/70 mt-0.5">Total Siaran</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-pengumuman').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-400 text-white font-bold px-4 py-2.5 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer text-xs shadow-lg shadow-blue-500/30 border border-blue-400/30">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Buat Baru
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Search Input Bar -->
    <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
        <input type="text" id="searchPengumuman" onkeyup="filterPengumuman()" placeholder="Cari pengumuman..." class="w-full bg-white border border-gray-100 pl-9 pr-4 py-2.5 rounded-xl font-medium text-gray-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition">
    </div>

    <!-- Announcement Feed / Cards Container -->
    <div id="pengumumanFeedContainer" class="space-y-3">
        @forelse($list_pengumuman as $info)
        <div class="pengumuman-card bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
             data-search="{{ strtolower($info->judul . ' ' . $info->isi) }}">

            <div class="p-3.5 flex flex-col gap-3">
                <!-- Top Row: Icon + Meta -->
                <div class="flex items-start gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-bullhorn text-sm"></i>
                    </div>

                    <div class="min-w-0 flex-1">
                        <!-- Date Badge & Meta Tag -->
                        <div class="flex items-center gap-1.5 flex-wrap mb-1.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold">
                                <i class="fa-regular fa-calendar-check text-[8px]"></i>
                                {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('d M Y') }}
                            </span>
                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[9px] font-bold">
                                <i class="fa-solid fa-circle-check text-[7px]"></i> Resmi
                            </span>
                        </div>

                        <!-- Announcement Title -->
                        <h3 class="text-sm font-black text-gray-800 tracking-tight leading-snug mb-2">
                            {{ $info->judul }}
                        </h3>
                    </div>

                    <!-- Delete Action -->
                    @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                    <button onclick="hapusPengumuman({{ $info->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer border border-red-100/50 shrink-0" title="Hapus Pengumuman">
                        <i class="fa-solid fa-trash text-[10px]"></i>
                    </button>
                    @endif
                </div>

                <!-- Announcement Content Box -->
                <div class="bg-gray-50/80 border border-gray-100 rounded-lg p-3 text-xs text-gray-700 font-normal leading-relaxed whitespace-pre-line">
                    {{ $info->isi }}
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center shadow-sm">
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-bullhorn text-lg"></i>
            </div>
            <h3 class="text-sm font-black text-gray-800 mb-1">Belum Ada Pengumuman</h3>
            <p class="text-xs text-gray-400 font-medium max-w-xs mx-auto">Saat ini belum terdapat pengumuman atau imbauan yang disiarkan di lingkungan RT.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Pengumuman -->
<div id="modal-tambah-pengumuman" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center p-0 backdrop-blur-sm">
    <div class="bg-white rounded-t-2xl w-full max-w-[95vw] p-5 relative shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-tambah-pengumuman').classList.add('hidden')" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-gray-50 hover:bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>

        <div class="flex items-center gap-2.5 mb-4">
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-bullhorn text-sm"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-gray-800 tracking-tight">Buat Pengumuman Baru</h3>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Informasi Publik RT</p>
            </div>
        </div>

        <form id="form-tambah-pengumuman" action="/pengumuman/store" method="POST">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Judul Pengumuman</label>
                    <input type="text" name="judul" placeholder="Contoh: Kerja Bakti Kebersihan" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Isi Pengumuman Lengkap</label>
                    <textarea name="isi" placeholder="Tuliskan detail informasi, tanggal, waktu, lokasi..." class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 py-2 px-3 text-sm rounded-xl h-28 focus:outline-none focus:ring-2 focus:ring-blue-500 transition leading-relaxed" required></textarea>
                </div>

                <div class="flex gap-2.5 pt-3">
                    <button type="button" onclick="document.getElementById('modal-tambah-pengumuman').classList.add('hidden')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-pengumuman', 'pengumuman')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/30 transition">Siarkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function filterPengumuman() {
    const query = document.getElementById('searchPengumuman').value.toLowerCase();
    document.querySelectorAll('.pengumuman-card').forEach(card => {
        const data = card.getAttribute('data-search');
        card.style.display = data.includes(query) ? '' : 'none';
    });
}

function hapusPengumuman(id) {
    if(!confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) return;

    let formData = new FormData();
    formData.append('id', id);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/pengumuman/delete', {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(res => res.json()).then(data => {
        alert(data.message);
        if (typeof switchPage === 'function') {
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('pengumuman'); }
            switchPage('pengumuman', document.querySelector('.menu-active'));
        } else {
            window.location.reload();
        }
    });
}
</script>
