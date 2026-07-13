<div class="p-3 space-y-3 max-w-full mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#312e81] via-[#3730a3] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg">
        <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <i class="fa-solid fa-comment-dots absolute -bottom-4 -right-3 text-[70px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-3">
            <div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <div class="w-6 h-6 rounded-lg bg-indigo-500/20 border border-indigo-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-comment-dots text-indigo-300 text-[10px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-indigo-300/80">Layanan Warga</span>
                </div>
                <h1 class="text-lg font-black tracking-tight">Aspirasi & Keluhan</h1>
                <p class="text-[10px] text-white/50 font-medium mt-0.5 leading-tight">Sampaikan aspirasi, saran, dan keluhan warga.</p>
            </div>

            <div class="flex items-center gap-2">
                <!-- Quick Stats Badge -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[70px]">
                    <p class="text-lg font-black text-white leading-none">{{ count($list_aspirasi ?? []) }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-indigo-300/70 mt-0.5">Total Aspirasi</p>
                </div>

                <button onclick="document.getElementById('modal-tambah-aspirasi').classList.remove('hidden')" class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold px-4 py-2.5 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer text-xs shadow-lg shadow-indigo-500/30 border border-indigo-400/30">
                    <i class="fa-solid fa-paper-plane text-xs"></i> Sampaikan
                </button>
            </div>
        </div>
    </div>

    <!-- Grid Aspirasi -->
    <div class="grid grid-cols-1 gap-3">
        @forelse($list_aspirasi ?? [] as $item)
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="bg-indigo-50 text-indigo-600 px-2.5 py-0.5 rounded-full text-[10px] font-bold">{{ $item->status }}</span>
                    <span class="text-[10px] text-gray-400 font-bold">{{ $item->created_at }}</span>
                </div>
                <h3 class="text-sm font-black text-gray-800 mb-0.5">{{ $item->topik }}</h3>
                <p class="text-[10px] font-bold text-gray-400 mb-2"><i class="fa-solid fa-user mr-1"></i> Dari: {{ $item->nama_warga }}</p>
                <div class="bg-gray-50 p-3 rounded-xl text-[11px] text-gray-700 font-medium mb-3 whitespace-pre-line">
                    {{ $item->isi_aspirasi }}
                </div>
                
                @if($item->tanggapan_rt)
                <div class="bg-emerald-50/50 border border-emerald-100 p-3 rounded-xl text-[11px] text-emerald-800 font-medium">
                    <p class="font-bold text-[9px] uppercase tracking-wider text-emerald-700 mb-0.5"><i class="fa-solid fa-reply"></i> Tanggapan RT:</p>
                    {{ $item->tanggapan_rt }}
                </div>
                @else
                <p class="text-[10px] text-gray-400 italic mb-1">Belum ada tanggapan resmi dari RT.</p>
                @endif
            </div>

            <div class="pt-3 border-t border-gray-50 flex items-center justify-end gap-1.5 mt-3">
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="bukaTanggapan({{ $item->id }}, '{{ addslashes($item->tanggapan_rt ?? '') }}', '{{ $item->status }}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[10px] shadow shadow-emerald-200 transition">
                    Tanggapan
                </button>
                <button onclick="hapusAspirasi({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                    <i class="fa-solid fa-trash text-[10px]"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center text-gray-400 italic text-xs">
            Belum ada aspirasi / keluhan warga yang masuk.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Sampaikan Aspirasi -->
<div id="modal-tambah-aspirasi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-2">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-aspirasi').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 mb-4">Sampaikan Aspirasi</h3>
        <form id="form-aspirasi" action="/aspirasi/store" method="POST" onsubmit="simpanDataUmum(event, 'form-aspirasi', 'aspirasi')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Nama Pengirim</label>
                    <input type="text" name="nama_warga" value="{{ Auth::user()->name }}" placeholder="Boleh diisi Anonim" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 text-sm py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Topik / Subjek Masukan</label>
                    <input type="text" name="topik" placeholder="Saran Perbaikan Fasilitas" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 text-sm py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Isi Saran / Aspirasi</label>
                    <textarea name="isi_aspirasi" rows="3" placeholder="Jelaskan aspirasi atau masukan Anda..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 text-sm p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-aspirasi').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 text-sm">Kirim</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tanggapan RT -->
<div id="modal-tanggapan-rt" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-2">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tanggapan-rt').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 mb-4">Tanggapan Resmi RT</h3>
        <form id="form-tanggapan-rt" action="/aspirasi/respond" method="POST" onsubmit="simpanDataUmum(event, 'form-tanggapan-rt', 'aspirasi')">
            <input type="hidden" name="id" id="tanggapan-id">
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Tanggapan Ketua RT</label>
                    <textarea name="tanggapan_rt" id="tanggapan-teks" rows="3" required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 text-sm p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Status Masukan</label>
                    <select name="status" id="tanggapan-status" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 text-sm py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Diterima & Ditindaklanjuti">Diterima & Ditindaklanjuti</option>
                        <option value="Selesai Diakomodasi">Selesai Diakomodasi</option>
                        <option value="Ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-tanggapan-rt').classList.add('hidden')" class="px-4 py-2 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 text-sm">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaTanggapan(id, tanggapan, status) {
    document.getElementById('tanggapan-id').value = id;
    document.getElementById('tanggapan-teks').value = tanggapan || '';
    document.getElementById('tanggapan-status').value = status || 'Diterima & Ditindaklanjuti';
    document.getElementById('modal-tanggapan-rt').classList.remove('hidden');
}
function hapusAspirasi(id) {
    if (!confirm('Hapus aspirasi ini dari daftar?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/aspirasi/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('aspirasi', document.querySelector('.menu-active')); });
}
</script>
