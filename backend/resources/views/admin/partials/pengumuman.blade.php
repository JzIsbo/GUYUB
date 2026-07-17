<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-bullhorn absolute -bottom-6 -right-4 text-[130px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn text-blue-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-blue-300/80">Pusat Informasi Lingkungan</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Pengumuman Warga</h1>
                <p class="text-sm text-white/50 font-medium mt-1">Imbauan resmi, jadwal kegiatan, dan pengumuman lingkungan RT</p>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Quick Stats Badge -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none">{{ count($list_pengumuman) }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-blue-300/70 mt-1">Total Siaran</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-pengumuman').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-400 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2.5 cursor-pointer text-sm shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 border border-blue-400/30">
                    <i class="fa-solid fa-plus-circle text-base"></i> Buat Pengumuman Baru
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Search Input Bar -->
    <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
        <input type="text" id="searchPengumuman" onkeyup="filterPengumuman()" placeholder="Cari judul atau isi pengumuman..." class="w-full bg-white border border-gray-100 pl-12 pr-6 py-3.5 rounded-2xl font-medium text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition">
    </div>

    <!-- Announcement Feed / Cards Container -->
    <div id="pengumumanFeedContainer" class="space-y-4">
        @forelse($list_pengumuman as $info)
        <div class="pengumuman-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden"
             data-search="{{ strtolower($info->judul . ' ' . $info->isi) }}">

            <div class="p-6 lg:p-7 flex flex-col md:flex-row md:items-start justify-between gap-5">
                <!-- Left Icon & Main Info -->
                <div class="flex items-start gap-4 min-w-0 flex-1">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20 mt-0.5">
                        <i class="fa-solid fa-bullhorn text-lg"></i>
                    </div>

                    <div class="min-w-0 flex-1">
                        <!-- Date Badge & Meta Tag -->
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[11px] font-bold">
                                <i class="fa-regular fa-calendar-check text-[10px]"></i>
                                {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('d M Y') }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold">
                                <i class="fa-solid fa-circle-check text-[8px]"></i> Pengumuman Resmi
                            </span>
                        </div>

                        <!-- Announcement Title -->
                        <h3 class="text-lg lg:text-xl font-black text-gray-800 tracking-tight leading-snug mb-3">
                            {{ $info->judul }}
                        </h3>

                        <!-- Announcement Content Box -->
                        <div class="bg-gray-50/80 border border-gray-100 rounded-xl p-4 lg:p-5 text-sm text-gray-700 font-normal leading-relaxed whitespace-pre-line">
                            {{ $info->isi }}
                        </div>
                    </div>
                </div>

                <!-- Right Actions -->
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <div class="shrink-0 flex md:flex-col items-center gap-2 justify-end self-start">
                    <button onclick="hapusPengumuman({{ $info->id }})" class="w-9 h-9 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer border border-red-100/50" title="Hapus Pengumuman">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-bullhorn text-2xl"></i>
            </div>
            <h3 class="text-lg font-black text-gray-800 mb-1">Belum Ada Pengumuman</h3>
            <p class="text-sm text-gray-400 font-medium max-w-md mx-auto">Saat ini belum terdapat pengumuman atau imbauan yang disiarkan di lingkungan RT.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Pengumuman -->
<div id="modal-tambah-pengumuman" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-pengumuman').classList.add('hidden')" class="absolute top-6 right-6 w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-bullhorn text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Buat Pengumuman Baru</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Informasi Publik RT</p>
            </div>
        </div>

        <form id="form-tambah-pengumuman" action="/pengumuman/store" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul Pengumuman</label>
                    <input type="text" name="judul" placeholder="Contoh: Kerja Bakti Kebersihan Lingkungan" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3.5 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi Pengumuman Lengkap</label>
                    <textarea name="isi" placeholder="Tuliskan detail informasi, tanggal, waktu, lokasi, dan instruksi penting untuk warga..." class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 py-3.5 px-4 rounded-2xl h-36 focus:outline-none focus:ring-2 focus:ring-blue-500 transition leading-relaxed" required></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('modal-tambah-pengumuman').classList.add('hidden')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-pengumuman', 'pengumuman')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/30 transition">Siarkan</button>
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
