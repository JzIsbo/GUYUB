<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#312e81] via-[#3730a3] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500/5 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-comment-dots absolute -bottom-6 -right-4 text-[130px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/20 border border-indigo-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-comment-dots text-indigo-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-indigo-300/80">Layanan Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Aspirasi & Keluhan Warga</h1>
                <p class="text-sm text-white/50 font-medium mt-1">Sampaikan aspirasi, saran pembangunan, keluhan fasilitas umum, dan tanggapan dari Ketua RT.</p>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Quick Stats Badge -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none">{{ count($list_aspirasi ?? []) }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-indigo-300/70 mt-1">Total Aspirasi</p>
                </div>

                <button onclick="document.getElementById('modal-tambah-aspirasi').classList.remove('hidden')" class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2.5 cursor-pointer text-sm shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 border border-indigo-400/30">
                    <i class="fa-solid fa-paper-plane text-base"></i> Sampaikan Aspirasi
                </button>
            </div>
        </div>
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
                    <input type="hidden" name="nama_warga" id="aspirasi_pengirim_hidden" value="{{ Auth::user()->name }}">
                    <div class="relative">
                        <input type="text" id="aspirasi_pengirim_search_input" value="{{ Auth::user()->name }}" placeholder="🔍 Cari & pilih nama warga pengirim..." 
                               onfocus="showDropdown('aspirasi_pengirim_dropdown')" 
                               onkeyup="filterCustomDropdown('aspirasi_pengirim_search_input', 'aspirasi_pengirim_dropdown')" 
                               autocomplete="off"
                               class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>

                        <div id="aspirasi_pengirim_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 max-h-56 overflow-y-auto divide-y divide-gray-50">
                            <div onclick="selectAspirasiPengirimOption('Anonim')" 
                                 class="dropdown-item px-4 py-2.5 hover:bg-indigo-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                <div>
                                    <span class="block font-bold text-indigo-600">👤 Anonim (Hamba Allah)</span>
                                    <span class="text-[10px] text-gray-400 font-normal">Kirim tanpa menyertakan nama</span>
                                </div>
                            </div>
                            @foreach($all_warga ?? [] as $w)
                                <div onclick="selectAspirasiPengirimOption('{{ addslashes($w->nama_lengkap) }}')" 
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
function selectAspirasiPengirimOption(nama) {
    document.getElementById('aspirasi_pengirim_search_input').value = nama;
    document.getElementById('aspirasi_pengirim_hidden').value = nama;
    document.getElementById('aspirasi_pengirim_dropdown').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    const pInput = document.getElementById('aspirasi_pengirim_search_input');
    const pDrop = document.getElementById('aspirasi_pengirim_dropdown');
    if (pInput && pDrop && !pInput.contains(e.target) && !pDrop.contains(e.target)) {
        pDrop.classList.add('hidden');
    }
});

function bukaTanggapan(id, tanggapan, status) {
    document.getElementById('tanggapan-id').value = id;
    document.getElementById('tanggapan-teks').value = tanggapan || '';
    document.getElementById('tanggapan-status').value = status || 'Diterima & Ditindaklanjuti';
    document.getElementById('modal-tanggapan-rt').classList.remove('hidden');
}

function hapusAspirasi(id) {
    Swal.fire({
        title: 'Hapus Aspirasi?',
        text: "Masukan/aspirasi warga ini akan dihapus.",
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
            fetch('/aspirasi/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Aspirasi telah dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl p-6 font-sans' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('aspirasi'); }
                switchPage('aspirasi', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
