<div class="p-3 space-y-3">
    <!-- Hero Banner -->
    <div class="bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#020617] rounded-2xl p-4 text-white relative overflow-hidden shadow-xl">
        <i class="fa-solid fa-shield-halved absolute -bottom-4 -right-2 text-[5rem] opacity-[0.03] rotate-12"></i>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-6 h-6 rounded-lg bg-slate-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-shield-halved text-slate-300 text-[10px]"></i>
                </div>
                <span class="text-[8px] font-black uppercase tracking-[2px] text-slate-300/80">Keamanan</span>
            </div>
            <h1 class="text-lg font-black tracking-tight">Keamanan & Ronda</h1>
            <div class="flex gap-2 mt-3">
                <button onclick="document.getElementById('modal-laporkan-kejadian').classList.remove('hidden')" class="flex-1 bg-red-500 hover:bg-red-400 text-white font-bold py-2.5 rounded-xl text-[10px] flex items-center justify-center gap-1.5 shadow-lg shadow-red-500/30">
                    <i class="fa-solid fa-triangle-exclamation"></i> Lapor Darurat
                </button>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-ronda').classList.remove('hidden')" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-2.5 rounded-xl text-[10px] flex items-center justify-center gap-1">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Ronda
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Shifts -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3">
        <h3 class="font-black text-gray-800 text-xs flex items-center gap-1.5 mb-2.5">
            <i class="fa-solid fa-calendar-days text-slate-700"></i> Jadwal Ronda Malam
        </h3>
        <div class="space-y-2">
            @forelse($list_ronda ?? [] as $item)
            <div class="bg-gray-50/70 p-3 rounded-xl flex items-center justify-between text-xs">
                <div>
                    <span class="bg-slate-800 text-white px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider">{{ $item->hari }}</span>
                    <span class="text-[10px] text-gray-500 font-bold ml-1.5">{{ $item->jam_shift }}</span>
                    <h4 class="font-bold text-gray-800 mt-1 text-[11px]"><i class="fa-solid fa-user-shield text-slate-500 mr-1"></i> Petugas: {{ $item->petugas_ronda }}</h4>
                    <p class="text-[10px] text-gray-400">Koord: {{ $item->koordinator }}</p>
                </div>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="hapusRonda({{ $item->id }})" class="w-6 h-6 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-trash text-[10px]"></i>
                </button>
                @endif
            </div>
            @empty
            <div class="text-center py-4 text-gray-400 text-xs italic">Belum ada jadwal ronda.</div>
            @endforelse
        </div>
    </div>

    <!-- Laporan Darurat -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3">
        <h3 class="font-black text-gray-800 text-xs flex items-center gap-1.5 mb-2.5">
            <i class="fa-solid fa-bell-concierge text-red-500"></i> Laporan Kejadian Warga
        </h3>
        <div class="space-y-2">
            @forelse($list_incidents ?? [] as $item)
            <div class="bg-red-50/50 p-3 rounded-xl border border-red-100 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded text-[9px]">{{ $item->jenis_kejadian }}</span>
                    <div class="text-right">
                        <span class="text-[10px] text-red-600 font-extrabold block"><i class="fa-regular fa-clock mr-0.5"></i> {{ $item->waktu_kejadian ?? \Carbon\Carbon::parse($item->created_at)->format('H:i') . ' WIB' }}</span>
                        <span class="text-[8px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                    </div>
                </div>
                <h4 class="font-black text-gray-800 text-[11px]">Pelapor: {{ $item->pelapor }}</h4>
                <p class="text-[10px] text-gray-600 leading-normal">{{ $item->deskripsi }}</p>
                
                @if(!empty($item->foto))
                <div class="mt-1.5">
                    <a href="{{ asset('storage/' . $item->foto) }}" target="_blank">
                        <img src="{{ asset('storage/' . $item->foto) }}" 
                             onerror="this.onerror=null; this.src='/storage/{{ $item->foto }}';"
                             class="rounded-xl max-h-44 w-full object-cover border-2 border-red-200 shadow-md" 
                             alt="Foto Bukti Kejadian">
                    </a>
                </div>
                @endif

                <div class="mt-2 flex justify-between items-center pt-1.5 border-t border-red-100/50">
                    <span class="text-[9px] uppercase font-black text-amber-600">{{ $item->status }}</span>
                    @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                    <button onclick="hapusIncident({{ $item->id }})" class="text-[9px] text-red-500 font-bold">Selesai & Hapus</button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-gray-400 text-xs italic">Situasi aman terkendali.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Tambah Ronda -->
<div id="modal-tambah-ronda" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl">
        <button onclick="document.getElementById('modal-tambah-ronda').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-sm font-black text-gray-800 mb-4">Tambah Jadwal Ronda</h3>
        <form id="form-ronda" action="/ronda/store" method="POST" onsubmit="simpanDataUmum(event, 'form-ronda', 'keamanan')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Hari Shift</label>
                    <select name="hari" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
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
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Daftar Petugas Ronda (Nama Warga)</label>
                    <input type="hidden" name="petugas_ronda" id="petugas_ronda_hidden_mobile" required>
                    <div id="selected_petugas_tags_mobile" class="flex flex-wrap gap-1 mb-1.5"></div>
                    <div class="relative">
                        <input type="text" id="petugas_search_input_mobile" placeholder="🔍 Cari & tambah nama..." 
                               onfocus="showDropdown('petugas_dropdown_mobile')" 
                               onkeyup="filterCustomDropdown('petugas_search_input_mobile', 'petugas_dropdown_mobile')" 
                               autocomplete="off"
                               class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[9px]"></i>

                        <div id="petugas_dropdown_mobile" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl z-30 max-h-40 overflow-y-auto divide-y divide-gray-50">
                            @foreach($all_warga ?? [] as $w)
                                <div onclick="tambahPetugasOptionMobile('{{ addslashes($w->nama_lengkap) }}')" 
                                     class="dropdown-item-m px-3 py-1.5 hover:bg-slate-50 cursor-pointer transition flex items-center justify-between text-[11px] font-semibold text-gray-700">
                                    <span>{{ $w->nama_lengkap }}</span>
                                    <span class="text-[9px] text-gray-400">Blok {{ $w->blok_rumah }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Koordinator</label>
                        <div class="relative">
                            <input type="hidden" name="koordinator" id="koordinator_hidden_mobile" required>
                            <div class="relative">
                                <input type="text" id="koordinator_search_input_mobile" placeholder="🔍 Cari..." 
                                       onfocus="showDropdown('koordinator_dropdown_mobile')" 
                                       onkeyup="filterCustomDropdown('koordinator_search_input_mobile', 'koordinator_dropdown_mobile')" 
                                       autocomplete="off"
                                       class="w-full bg-gray-50 border py-2 px-2.5 pr-6 rounded-xl font-bold text-gray-700 text-xs focus:outline-none focus:ring-2 focus:ring-slate-500">
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[9px]"></i>
                            </div>

                            <div id="koordinator_dropdown_mobile" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl z-30 max-h-40 overflow-y-auto divide-y divide-gray-50">
                                @foreach($all_warga ?? [] as $w)
                                    <div onclick="selectKoordinatorOptionMobile('{{ addslashes($w->nama_lengkap) }}')" 
                                         class="dropdown-item-m px-3 py-1.5 hover:bg-slate-50 cursor-pointer transition flex items-center justify-between text-[11px] font-semibold text-gray-700">
                                        <span>{{ $w->nama_lengkap }}</span>
                                        <span class="text-[9px] text-gray-400">Blok {{ $w->blok_rumah }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Jam Shift</label>
                        <input type="text" name="jam_shift" value="22:00 - 04:00 WIB" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-xs font-bold text-gray-700">
                    </div>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-ronda').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-xs">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-slate-800 text-white font-bold rounded-xl text-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Lapor Kejadian -->
<div id="modal-laporkan-kejadian" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-3">
    <div class="bg-white rounded-2xl w-full max-w-[95vw] p-5 relative shadow-2xl">
        <button onclick="document.getElementById('modal-laporkan-kejadian').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-sm font-black text-red-600 mb-4 flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Lapor Kejadian / Darurat</h3>
        <form id="form-incident" action="/incident/store" method="POST" enctype="multipart/form-data" onsubmit="simpanDataUmum(event, 'form-incident', 'keamanan')">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Pelapor</label>
                    <div class="relative">
                        <input type="hidden" name="pelapor" id="nama_pelapor_hidden_mobile" required>
                        <div class="relative">
                            <input type="text" id="pelapor_search_input_mobile" placeholder="🔍 Cari & pilih nama pelapor..." 
                                   onfocus="showDropdown('pelapor_dropdown_mobile')" 
                                   onkeyup="filterCustomDropdown('pelapor_search_input_mobile', 'pelapor_dropdown_mobile')" 
                                   autocomplete="off"
                                   class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 text-xs rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[9px]"></i>
                        </div>

                        <div id="pelapor_dropdown_mobile" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl z-30 max-h-48 overflow-y-auto divide-y divide-gray-50">
                            @foreach($all_warga ?? [] as $w)
                                <div onclick="selectPelaporOptionMobile('{{ addslashes($w->nama_lengkap) }}')" 
                                     class="dropdown-item-m px-3 py-2 hover:bg-red-50 cursor-pointer transition flex items-center justify-between text-[11px] font-semibold text-gray-700">
                                    <div>
                                        <span class="block font-bold">{{ $w->nama_lengkap }}</span>
                                        <span class="text-[9px] text-gray-400 font-normal">Blok {{ $w->blok_rumah }}</span>
                                    </div>
                                    <span class="text-[9px] text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full font-bold">{{ $w->umur ? $w->umur.' Thn' : '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Jenis Kejadian</label>
                        <select name="jenis_kejadian" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                            <option value="Pencurian / Mencurigakan">Pencurian / Mencurigakan</option>
                            <option value="Kebakaran / Potensi Bahaya">Kebakaran / Potensi Bahaya</option>
                            <option value="Keributan / Keramaian">Keributan / Keramaian</option>
                            <option value="Kerusakan Fasilitas Publik">Kerusakan Fasilitas Publik</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Waktu Kejadian</label>
                        <input type="text" name="waktu_kejadian" value="{{ date('H:i') }} WIB" placeholder="Contoh: 02:30 WIB" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-xs font-bold text-gray-700">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Foto Kejadian (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-xs text-gray-600 font-medium">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Kronologi / Lokasi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan lokasi kejadian..." required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm"></textarea>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-laporkan-kejadian').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-xs">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white font-bold rounded-xl text-xs">Kirim Laporan</button>
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
    
    const items = dropdown.getElementsByClassName('dropdown-item-m');
    for (let i = 0; i < items.length; i++) {
        const txt = items[i].textContent || items[i].innerText;
        if (txt.toLowerCase().includes(filter)) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}

let selectedPetugasListMobile = [];

function updatePetugasDisplayMobile() {
    const container = document.getElementById('selected_petugas_tags_mobile');
    const hiddenInput = document.getElementById('petugas_ronda_hidden_mobile');
    if (!container || !hiddenInput) return;

    container.innerHTML = '';
    selectedPetugasListMobile.forEach((nama, index) => {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-1 bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-lg shadow-sm';
        tag.innerHTML = `<span>${nama}</span><button type="button" onclick="hapusPetugasTagMobile(${index})" class="hover:text-rose-300 text-gray-300 ml-1 font-black text-xs">&times;</button>`;
        container.appendChild(tag);
    });
    hiddenInput.value = selectedPetugasListMobile.join(', ');
}

function tambahPetugasOptionMobile(nama) {
    if (!selectedPetugasListMobile.includes(nama)) {
        selectedPetugasListMobile.push(nama);
        updatePetugasDisplayMobile();
    }
    document.getElementById('petugas_search_input_mobile').value = '';
    document.getElementById('petugas_dropdown_mobile').classList.add('hidden');
}

function hapusPetugasTagMobile(index) {
    selectedPetugasListMobile.splice(index, 1);
    updatePetugasDisplayMobile();
}

function selectKoordinatorOptionMobile(nama) {
    document.getElementById('koordinator_search_input_mobile').value = nama;
    document.getElementById('koordinator_hidden_mobile').value = nama;
    document.getElementById('koordinator_dropdown_mobile').classList.add('hidden');
}

function selectPelaporOptionMobile(nama) {
    document.getElementById('pelapor_search_input_mobile').value = nama;
    document.getElementById('nama_pelapor_hidden_mobile').value = nama;
    document.getElementById('pelapor_dropdown_mobile').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    ['pelapor', 'petugas', 'koordinator'].forEach(prefix => {
        const inputM = document.getElementById(prefix + '_search_input_mobile');
        const dropM = document.getElementById(prefix + '_dropdown_mobile');
        if (inputM && dropM && !inputM.contains(e.target) && !dropM.contains(e.target)) {
            dropM.classList.add('hidden');
        }
    });
});

function hapusRonda(id) {
    Swal.fire({
        title: 'Hapus Ronda?',
        text: "Shift ronda malam ini akan dihapus.",
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
            fetch('/ronda/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json()).then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Jadwal ronda dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('keamanan'); }
                switchPage('keamanan', document.querySelector('.menu-active')); 
            });
        }
    });
}

function hapusIncident(id) {
    Swal.fire({
        title: 'Selesaikan Laporan?',
        text: "Laporan kejadian ini akan diselesaikan & dihapus.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Selesaikan',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-2xl p-4 shadow-xl font-sans text-xs',
            confirmButton: 'rounded-xl font-bold px-4 py-2 text-[11px]',
            cancelButton: 'rounded-xl font-bold px-4 py-2 text-[11px]'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
            fetch('/incident/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json()).then(data => { 
                Swal.fire({ title: 'Laporan Selesai!', text: 'Laporan telah diselesaikan.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('keamanan'); }
                switchPage('keamanan', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
