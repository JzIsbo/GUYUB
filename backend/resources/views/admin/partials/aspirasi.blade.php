<div class="p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-comment-dots"></i>
                </div>
                Aspirasi & Keluhan Warga
            </h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Sampaikan aspirasi, saran pembangunan, keluhan fasilitas umum, dan tanggapan dari Ketua RT.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-aspirasi').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer self-start md:self-auto text-sm">
            <i class="fa-solid fa-paper-plane"></i> Sampaikan Aspirasi
        </button>
    </div>

    <!-- Grid Aspirasi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($list_aspirasi ?? [] as $item)
        <div class="bg-white rounded-[2.5rem] p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold">{{ $item->status }}</span>
                    <span class="text-xs text-gray-400 font-bold">{{ $item->created_at }}</span>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-1">{{ $item->topik }}</h3>
                <p class="text-xs font-bold text-gray-400 mb-3"><i class="fa-solid fa-user mr-1"></i> Dari: {{ $item->nama_warga }}</p>
                <div class="bg-gray-50 p-4 rounded-2xl text-xs text-gray-700 font-medium mb-4 whitespace-pre-line">
                    {{ $item->isi_aspirasi }}
                </div>
                
                @if($item->tanggapan_rt)
                <div class="bg-emerald-50/50 border border-emerald-100 p-4 rounded-2xl text-xs text-emerald-800 font-medium">
                    <p class="font-bold text-[10px] uppercase tracking-wider text-emerald-700 mb-1"><i class="fa-solid fa-reply"></i> Tanggapan RT:</p>
                    {{ $item->tanggapan_rt }}
                </div>
                @else
                <p class="text-xs text-gray-400 italic mb-2">Belum ada tanggapan resmi dari RT.</p>
                @endif
            </div>

            <div class="pt-4 border-t border-gray-50 flex items-center justify-end gap-2 mt-4">
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="bukaTanggapan({{ $item->id }}, '{{ addslashes($item->tanggapan_rt ?? '') }}', '{{ $item->status }}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow shadow-emerald-200 transition">
                    Beri Tanggapan
                </button>
                <button onclick="hapusAspirasi({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white p-12 rounded-[2.5rem] border border-gray-100 text-center text-gray-400 italic">
            Belum ada aspirasi / keluhan warga yang masuk.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Sampaikan Aspirasi -->
<div id="modal-tambah-aspirasi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-aspirasi').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Sampaikan Aspirasi / Masukan</h3>
        <form id="form-aspirasi" action="/aspirasi/store" method="POST" onsubmit="simpanDataUmum(event, 'form-aspirasi', 'aspirasi')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Pengirim</label>
                    <input type="text" name="nama_warga" value="{{ Auth::user()->name }}" placeholder="Boleh diisi Anonim" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Topik / Subjek Masukan</label>
                    <input type="text" name="topik" placeholder="Saran Perbaikan Fasilitas Pos Kamling" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi Saran / Aspirasi secara Detail</label>
                    <textarea name="isi_aspirasi" rows="4" placeholder="Jelaskan aspirasi atau masukan Anda demi kebaikan lingkungan RT..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-aspirasi').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200">Kirim Aspirasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tanggapan RT -->
<div id="modal-tanggapan-rt" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tanggapan-rt').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Beri Tanggapan Resmi RT</h3>
        <form id="form-tanggapan-rt" action="/aspirasi/respond" method="POST" onsubmit="simpanDataUmum(event, 'form-tanggapan-rt', 'aspirasi')">
            <input type="hidden" name="id" id="tanggapan-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggapan Ketua RT / Pengurus</label>
                    <textarea name="tanggapan_rt" id="tanggapan-teks" rows="4" required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Masukan</label>
                    <select name="status" id="tanggapan-status" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Diterima & Ditindaklanjuti">Diterima & Ditindaklanjuti</option>
                        <option value="Selesai Diakomodasi">Selesai Diakomodasi</option>
                        <option value="Ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tanggapan-rt').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200">Kirim Tanggapan</button>
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
