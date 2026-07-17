<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#020617] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-slate-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-red-500/5 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-shield-halved absolute -bottom-6 -right-4 text-[130px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-500/20 border border-slate-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-slate-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-slate-300/80">Keamanan Lingkungan</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Keamanan & Ronda Malam</h1>
                <p class="text-sm text-white/50 font-medium mt-1">Jadwal siskamling ronda malam warga dan pusat pelaporan kejadian darurat.</p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <button onclick="document.getElementById('modal-laporkan-kejadian').classList.remove('hidden')" class="bg-red-500 hover:bg-red-400 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2 cursor-pointer text-sm shadow-lg shadow-red-500/30 hover:-translate-y-0.5 border border-red-400/30">
                    <i class="fa-solid fa-triangle-exclamation"></i> Lapor Darurat
                </button>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-ronda').classList.remove('hidden')" class="bg-slate-700 hover:bg-slate-600 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2.5 cursor-pointer text-sm border border-slate-600/30">
                    <i class="fa-solid fa-plus-circle text-base"></i> Tambah Ronda
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Jadwal Ronda -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-gray-50">
                    <h3 class="font-black text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-slate-700"></i> Jadwal Shift Ronda Malam
                    </h3>
                </div>
                <div class="divide-y divide-gray-50 p-6 space-y-4">
                    @forelse($list_ronda ?? [] as $item)
                    <div class="bg-gray-50/70 p-5 rounded-2xl flex items-center justify-between">
                        <div>
                            <span class="bg-slate-800 text-white px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">{{ $item->hari }}</span>
                            <span class="text-xs text-gray-500 font-bold ml-2">{{ $item->jam_shift }}</span>
                            <h4 class="font-bold text-gray-800 mt-2 text-sm"><i class="fa-solid fa-user-shield text-slate-500 mr-1"></i> Petugas: {{ $item->petugas_ronda }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Koordinator: {{ $item->koordinator }}</p>
                        </div>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                        <button onclick="hapusRonda({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400 italic">Belum ada jadwal ronda yang dibuat.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Laporan Kejadian / Emergency -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-gray-50">
                    <h3 class="font-black text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-bell-concierge text-red-500"></i> Catatan Laporan Kejadian Warga
                    </h3>
                </div>
                <div class="divide-y divide-gray-50 p-6 space-y-4">
                    @forelse($list_incidents ?? [] as $item)
                    <div class="bg-red-50/50 p-5 rounded-2xl border border-red-100 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="bg-red-100 text-red-700 font-bold px-3 py-1 rounded-full text-xs">{{ $item->jenis_kejadian }}</span>
                            <div class="text-right">
                                <span class="text-xs text-red-600 font-extrabold block"><i class="fa-regular fa-clock mr-1"></i> {{ $item->waktu_kejadian ?? \Carbon\Carbon::parse($item->created_at)->format('H:i') . ' WIB' }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <h4 class="font-black text-gray-800 text-sm">Pelapor: {{ $item->pelapor }}</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ $item->deskripsi }}</p>
                        
                        @if(!empty($item->foto))
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" title="Klik untuk membuka ukuran penuh">
                                <img src="{{ asset('storage/' . $item->foto) }}" 
                                     onerror="this.onerror=null; this.src='/storage/{{ $item->foto }}';"
                                     class="rounded-2xl max-h-56 w-full object-cover border-2 border-red-200 shadow-md hover:scale-[1.01] transition-transform duration-200" 
                                     alt="Foto Bukti Kejadian">
                            </a>
                        </div>
                        @endif

                        <div class="mt-3 flex justify-between items-center pt-2 border-t border-red-100/50">
                            <span class="text-[10px] uppercase font-black tracking-widest text-amber-600">{{ $item->status }}</span>
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                            <button onclick="hapusIncident({{ $item->id }})" class="text-xs text-red-500 hover:underline font-bold">Selesaikan & Hapus</button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400 italic">Situasi aman terkendali. Belum ada laporan kejadian.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Ronda -->
<div id="modal-tambah-ronda" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-ronda').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Tambah Jadwal Ronda Malam</h3>
        <form id="form-ronda" action="/ronda/store" method="POST" onsubmit="simpanDataUmum(event, 'form-ronda', 'keamanan')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Hari Shift</label>
                    <select name="hari" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-slate-500">
                        <option value="Senin Malam">Senin Malam</option>
                        <option value="Selasa Malam">Selasa Malam</option>
                        <option value="Rabu Malam">Rabu Malam</option>
                        <option value="Kamis Malam">Kamis Malam</option>
                        <option value="Jumat Malam">Jumat Malam</option>
                        <option value="Sabtu Malam">Sabtu Malam</option>
                        <option value="Minggu Malam">Minggu Malam</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Daftar Petugas Ronda (Nama Warga)</label>
                    <input type="hidden" name="petugas_ronda" id="petugas_ronda_hidden" required>
                    <div id="selected_petugas_tags" class="flex flex-wrap gap-1.5 mb-2"></div>
                    <div class="relative">
                        <input type="text" id="petugas_search_input" placeholder="🔍 Cari & tambah nama petugas warga..." 
                               onfocus="showDropdown('petugas_dropdown')" 
                               onkeyup="filterCustomDropdown('petugas_search_input', 'petugas_dropdown')" 
                               autocomplete="off"
                               class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>

                        <div id="petugas_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-30 max-h-48 overflow-y-auto divide-y divide-gray-50">
                            @foreach($all_warga ?? [] as $w)
                                <div onclick="tambahPetugasOption('{{ addslashes($w->nama_lengkap) }}')" 
                                     class="dropdown-item px-4 py-2 hover:bg-slate-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                    <span>{{ $w->nama_lengkap }}</span>
                                    <span class="text-[10px] text-gray-400">Blok {{ $w->blok_rumah }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Koordinator Shift</label>
                        <div class="relative">
                            <input type="hidden" name="koordinator" id="koordinator_hidden" required>
                            <div class="relative">
                                <input type="text" id="koordinator_search_input" placeholder="🔍 Cari koordinator..." 
                                       onfocus="showDropdown('koordinator_dropdown')" 
                                       onkeyup="filterCustomDropdown('koordinator_search_input', 'koordinator_dropdown')" 
                                       autocomplete="off"
                                       class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-8 rounded-2xl focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>

                            <div id="koordinator_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-30 max-h-48 overflow-y-auto divide-y divide-gray-50">
                                @foreach($all_warga ?? [] as $w)
                                    <div onclick="selectKoordinatorOption('{{ addslashes($w->nama_lengkap) }}')" 
                                         class="dropdown-item px-4 py-2 hover:bg-slate-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                        <span>{{ $w->nama_lengkap }}</span>
                                        <span class="text-[10px] text-gray-400">Blok {{ $w->blok_rumah }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jam Shift</label>
                        <input type="text" name="jam_shift" value="22:00 - 04:00 WIB" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-slate-500">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-ronda').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-2xl shadow-lg shadow-slate-200">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Lapor Kejadian -->
<div id="modal-laporkan-kejadian" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-laporkan-kejadian').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6 text-red-600 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Buat Laporan Kejadian / Darurat</h3>
        <form id="form-incident" action="/incident/store" method="POST" enctype="multipart/form-data" onsubmit="simpanDataUmum(event, 'form-incident', 'keamanan')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Pelapor</label>
                    <div class="relative">
                        <input type="hidden" name="pelapor" id="nama_pelapor_hidden" required>
                        <div class="relative">
                            <input type="text" id="pelapor_search_input" placeholder="🔍 Cari & pilih nama pelapor..." 
                                   onfocus="showDropdown('pelapor_dropdown')" 
                                   onkeyup="filterCustomDropdown('pelapor_search_input', 'pelapor_dropdown')" 
                                   autocomplete="off"
                                   class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        </div>

                        <div id="pelapor_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-30 max-h-56 overflow-y-auto divide-y divide-gray-50">
                            @foreach($all_warga ?? [] as $w)
                                <div onclick="selectPelaporOption('{{ addslashes($w->nama_lengkap) }}')" 
                                     class="dropdown-item px-4 py-2.5 hover:bg-red-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                    <div>
                                        <span class="block font-bold">{{ $w->nama_lengkap }}</span>
                                        <span class="text-[10px] text-gray-400 font-normal">Blok {{ $w->blok_rumah }}</span>
                                    </div>
                                    <span class="text-[10px] text-red-600 bg-red-50 px-2 py-0.5 rounded-full font-bold">{{ $w->umur ? $w->umur.' Thn' : '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Kejadian</label>
                        <select name="jenis_kejadian" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="Pencurian / Mencurigakan">Pencurian / Mencurigakan</option>
                            <option value="Kebakaran / Potensi Bahaya">Kebakaran / Potensi Bahaya</option>
                            <option value="Keributan / Keramaian">Keributan / Keramaian</option>
                            <option value="Kerusakan Fasilitas Publik">Kerusakan Fasilitas Publik</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu Kejadian</label>
                        <input type="text" name="waktu_kejadian" value="{{ date('H:i') }} WIB" placeholder="Contoh: 02:30 WIB" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Foto Kejadian / Bukti (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2.5 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Kronologi / Lokasi Detail</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan lokasi kejadian dan kebutuhan bantuan..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-laporkan-kejadian').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-lg shadow-red-200">Kirim Laporan Darurat</button>
            </div>
        </form>
    </div>
</div>

<script>
function showDropdown(id) {
    document.getElementById(id).classList.remove('hidden');
}

function filterCustomDropdown(inputId, dropdownId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const dropdown = document.getElementById(dropdownId);
    dropdown.classList.remove('hidden');
    
    const items = dropdown.getElementsByClassName('dropdown-item');
    for (let i = 0; i < items.length; i++) {
        const txt = items[i].textContent || items[i].innerText;
        if (txt.toLowerCase().includes(filter)) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}

let selectedPetugasList = [];

function updatePetugasDisplay() {
    const container = document.getElementById('selected_petugas_tags');
    const hiddenInput = document.getElementById('petugas_ronda_hidden');
    if (!container || !hiddenInput) return;

    container.innerHTML = '';
    selectedPetugasList.forEach((nama, index) => {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-1 bg-slate-800 text-white text-xs font-bold px-2.5 py-1 rounded-xl shadow-sm';
        tag.innerHTML = `<span>${nama}</span><button type="button" onclick="hapusPetugasTag(${index})" class="hover:text-rose-300 text-gray-300 ml-1 font-black text-xs">&times;</button>`;
        container.appendChild(tag);
    });
    hiddenInput.value = selectedPetugasList.join(', ');
}

function tambahPetugasOption(nama) {
    if (!selectedPetugasList.includes(nama)) {
        selectedPetugasList.push(nama);
        updatePetugasDisplay();
    }
    document.getElementById('petugas_search_input').value = '';
    document.getElementById('petugas_dropdown').classList.add('hidden');
}

function hapusPetugasTag(index) {
    selectedPetugasList.splice(index, 1);
    updatePetugasDisplay();
}

function selectKoordinatorOption(nama) {
    document.getElementById('koordinator_search_input').value = nama;
    document.getElementById('koordinator_hidden').value = nama;
    document.getElementById('koordinator_dropdown').classList.add('hidden');
}

function selectPelaporOption(nama) {
    document.getElementById('pelapor_search_input').value = nama;
    document.getElementById('nama_pelapor_hidden').value = nama;
    document.getElementById('pelapor_dropdown').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    ['pelapor', 'petugas', 'koordinator'].forEach(prefix => {
        const input = document.getElementById(prefix + '_search_input');
        const drop = document.getElementById(prefix + '_dropdown');
        if (input && drop && !input.contains(e.target) && !drop.contains(e.target)) {
            drop.classList.add('hidden');
        }
    });
});

function hapusRonda(id) {
    Swal.fire({
        title: 'Hapus Jadwal Ronda?',
        text: "Jadwal shift ronda malam ini akan dihapus dari sistem.",
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
            fetch('/ronda/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json()).then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Jadwal ronda telah dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl p-6 font-sans' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('keamanan'); }
                switchPage('keamanan', document.querySelector('.menu-active')); 
            });
        }
    });
}

function hapusIncident(id) {
    Swal.fire({
        title: 'Selesaikan & Hapus Laporan?',
        text: "Laporan kejadian ini akan ditandai selesai dan dihapus dari daftar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Selesaikan',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-3xl p-6 shadow-2xl font-sans',
            confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs',
            cancelButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
            fetch('/incident/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json()).then(data => { 
                Swal.fire({ title: 'Laporan Selesai!', text: 'Laporan kejadian telah diselesaikan & dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl p-6 font-sans' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('keamanan'); }
                switchPage('keamanan', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
