<div class="p-4 lg:p-8 space-y-8 max-w-[1400px] mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest mb-2">
                <i class="fa-solid fa-bullhorn text-blue-500"></i> Informasi Lingkungan
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                Pengumuman Warga
            </h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Pusat informasi resmi, imbauan, dan pengumuman kegiatan warga RT.</p>
        </div>

        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
        <button onclick="document.getElementById('modal-tambah-pengumuman').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3.5 rounded-2xl shadow-lg shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 cursor-pointer text-sm shrink-0">
            <i class="fa-solid fa-plus-circle text-base"></i> Buat Pengumuman Baru
        </button>
        @endif
    </div>

    <!-- Announcement Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($list_pengumuman as $info)
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden relative group">

            <!-- Decorative Top Gradient Accent -->
            <div class="h-2 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-500"></div>

            <div class="p-6 lg:p-7 flex-1">
                <!-- Date & Action Header -->
                <div class="flex items-center justify-between gap-2 mb-4">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 text-gray-500 rounded-xl text-[11px] font-bold border border-gray-100">
                        <i class="fa-regular fa-calendar-check text-blue-500"></i>
                        {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('d M Y') }}
                    </div>

                    @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                    <button onclick="hapusPengumuman({{ $info->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer opacity-80 group-hover:opacity-100" title="Hapus Pengumuman">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                    @endif
                </div>

                <!-- Title -->
                <h3 class="text-lg font-black text-gray-800 leading-snug mb-3 group-hover:text-blue-600 transition-colors">
                    {{ $info->judul }}
                </h3>

                <!-- Divider -->
                <div class="w-10 h-1 bg-blue-100 rounded-full mb-4"></div>

                <!-- Content Body -->
                <p class="text-sm text-gray-600 font-normal leading-relaxed whitespace-pre-line">
                    {{ $info->isi }}
                </p>
            </div>

            <!-- Card Footer Pin Info -->
            <div class="px-6 lg:px-7 py-3.5 bg-gray-50/70 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400 font-medium">
                <span class="flex items-center gap-1.5 text-blue-600 font-bold">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Pengumuman Resmi
                </span>
                <span class="text-[11px] text-gray-400 font-semibold">
                    <i class="fa-solid fa-bullhorn mr-1 text-[10px]"></i> RT/RW
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[2.5rem] border border-gray-100 p-12 text-center shadow-sm">
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
            switchPage('pengumuman', document.querySelector('.menu-active'));
        } else {
            window.location.reload();
        }
    });
}
</script>
