<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">
    @php
        $isAdmin = in_array(Auth::user()->role, ['Super Admin', 'RT']);
        $userName = Auth::user()->name;
    @endphp

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#4c0519] via-[#881337] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-rose-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-heart-pulse absolute -bottom-6 -right-4 text-[130px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/20 border border-rose-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-heart-pulse text-rose-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-rose-300/80">Layanan Kesehatan Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Posyandu Balita & Lansia</h1>
                <p class="text-sm text-white/50 font-medium mt-1">Jadwal pemeriksaan kesehatan, imunisasi, & suplemen gizi rutin warga.</p>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Quick Stats Badge -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none">{{ count($list_posyandu ?? []) }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-rose-300/70 mt-1">Total Jadwal</p>
                </div>

                @if($isAdmin)
                <button onclick="document.getElementById('modal-tambah-posyandu').classList.remove('hidden')" class="bg-rose-500 hover:bg-rose-400 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2.5 cursor-pointer text-sm shadow-lg shadow-rose-500/30 hover:-translate-y-0.5 border border-rose-400/30">
                    <i class="fa-solid fa-plus-circle text-base"></i> Tambah Jadwal
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Jadwal Posyandu Cards -->
    @forelse($list_posyandu ?? [] as $item)
    @php
        $pendaftaranItem = ($list_pendaftaran ?? collect())->where('posyandu_id', $item->id);
        $isBalita = str_contains(strtolower($item->target_peserta), 'balita') || str_contains(strtolower($item->target_peserta), 'ibu');
        $colorClass = $isBalita ? 'rose' : 'violet';
        $icon = $isBalita ? 'fa-baby' : 'fa-person-cane';
    @endphp
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <!-- Jadwal Header -->
        <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 text-lg">{{ $item->nama_kegiatan }}</h3>
                    <div class="flex flex-wrap items-center gap-3 mt-1">
                        <span class="text-xs font-bold text-gray-500"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                        @if($item->waktu_mulai)
                        <span class="text-xs font-bold text-gray-500">
                            <i class="fa-regular fa-clock mr-1"></i> 
                            {{ substr($item->waktu_mulai, 0, 5) }}{{ $item->waktu_selesai ? ' - ' . substr($item->waktu_selesai, 0, 5) : ' - Selesai' }} WIB
                        </span>
                        @endif
                        <span class="bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">{{ $item->target_peserta }}</span>
                        <span class="text-xs font-medium text-gray-400"><i class="fa-solid fa-location-dot text-{{ $colorClass }}-400 mr-1"></i> {{ $item->lokasi }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="bukaModalDaftar({{ $item->id }}, '{{ addslashes($item->nama_kegiatan) }}', '{{ $item->target_peserta }}')" class="bg-{{ $colorClass }}-600 hover:bg-{{ $colorClass }}-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition-all flex items-center gap-2 cursor-pointer shadow-lg shadow-{{ $colorClass }}-200">
                    <i class="fa-solid fa-user-plus"></i> Daftarkan Peserta
                </button>
                @if($isAdmin)
                <button onclick="hapusPosyandu({{ $item->id }})" class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer" title="Hapus Jadwal">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
                @endif
            </div>
        </div>

        <!-- Keterangan -->
        @if($item->keterangan)
        <div class="px-6 py-3 bg-{{ $colorClass }}-50/30 border-b border-gray-50">
            <p class="text-xs text-gray-500 font-medium"><i class="fa-solid fa-info-circle text-{{ $colorClass }}-400 mr-1"></i> {{ $item->keterangan }}</p>
        </div>
        @endif

        <!-- Peserta Terdaftar -->
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-users text-{{ $colorClass }}-400"></i>
                    Peserta Terdaftar
                    <span class="bg-{{ $colorClass }}-100 text-{{ $colorClass }}-700 px-2 py-0.5 rounded-full text-[10px] font-black">{{ $pendaftaranItem->count() }}</span>
                </h4>
            </div>

            @if($pendaftaranItem->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($pendaftaranItem as $peserta)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-{{ $colorClass }}-200 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $colorClass }}-100 text-{{ $colorClass }}-600 flex items-center justify-center text-sm font-black">
                            {{ strtoupper(substr($peserta->nama_peserta, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $peserta->nama_peserta }}</p>
                            <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                                <span class="text-[10px] text-gray-400 font-medium">{{ $peserta->kategori }} · {{ $peserta->usia ?? '-' }} · {{ $peserta->jenis_kelamin }}</span>
                                @if($peserta->tinggi_badan)
                                <span class="text-[10px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded font-bold">TB: {{ $peserta->tinggi_badan }} cm</span>
                                @endif
                                @if($peserta->berat_badan)
                                <span class="text-[10px] bg-green-50 text-green-600 px-1.5 py-0.5 rounded font-bold">BB: {{ $peserta->berat_badan }} kg</span>
                                @endif
                                <span class="text-[10px] text-gray-400 font-medium">· oleh {{ $peserta->nama_pendaftar }} ({{ $peserta->hubungan }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($peserta->status == 'Terdaftar')
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-100 text-blue-600">Terdaftar</span>
                        @elseif($peserta->status == 'Hadir')
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-green-100 text-green-600">Hadir ✓</span>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-red-100 text-red-500">Tidak Hadir</span>
                        @endif

                        @if($isAdmin)
                        <select onchange="ubahStatusPeserta({{ $peserta->id }}, this.value)" class="text-[10px] font-bold border border-gray-200 rounded-lg px-2 py-1 bg-white cursor-pointer">
                            <option value="Terdaftar" {{ $peserta->status == 'Terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                            <option value="Hadir" {{ $peserta->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="Tidak Hadir" {{ $peserta->status == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                        </select>
                        @endif

                        @if($isAdmin || $peserta->nama_pendaftar == $userName)
                        <button onclick="hapusPendaftaran({{ $peserta->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer" title="Batalkan Pendaftaran">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fa-solid fa-clipboard-list text-gray-200 text-4xl mb-3"></i>
                <p class="text-sm text-gray-400 font-medium">Belum ada peserta terdaftar untuk jadwal ini.</p>
                <p class="text-xs text-gray-300 mt-1">Klik tombol "Daftarkan Peserta" untuk mendaftar.</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white p-12 rounded-[2.5rem] border border-gray-50 shadow-sm text-center">
        <i class="fa-solid fa-heart-pulse text-gray-200 text-6xl mb-4"></i>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mb-2">Belum Ada Agenda Posyandu</h3>
        <p class="text-sm text-gray-500 font-medium">Sistem belum memiliki jadwal posyandu untuk ditampilkan.</p>
    </div>
    @endforelse
</div>

<!-- Modal Tambah Jadwal (Admin/RT Only) -->
<div id="modal-tambah-posyandu" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-posyandu').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Tambah Jadwal Posyandu</h3>
        <form id="form-posyandu" action="/posyandu/store" method="POST" onsubmit="simpanDataUmum(event, 'form-posyandu', 'posyandu')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Kegiatan Layanan</label>
                    <input type="text" name="nama_kegiatan" placeholder="Posyandu Rutin & Imunisasi" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Target Peserta</label>
                        <select name="target_peserta" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="Balita (0-5 Tahun)">Balita (0-5 Tahun)</option>
                            <option value="Lansia (>60 Tahun)">Lansia (>60 Tahun)</option>
                            <option value="Ibu Hamil">Ibu Hamil</option>
                            <option value="Semua Warga">Semua Warga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Pos Pelayanan</label>
                    <input type="text" name="lokasi" placeholder="Pos Kamling / Balai Warga RT" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="2" placeholder="Bawa buku KMS dan Kartu Identitas" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-posyandu').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-lg shadow-rose-200">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Daftar Peserta (Semua Role) -->
<div id="modal-daftar-peserta" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-daftar-peserta').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 id="daftar-modal-title" class="text-xl font-black text-gray-800 mb-2">Daftarkan Peserta</h3>
        <p id="daftar-modal-subtitle" class="text-sm text-gray-400 font-medium mb-6">-</p>
        <form id="form-daftar-peserta" action="/posyandu/daftar" method="POST" onsubmit="simpanDataUmum(event, 'form-daftar-peserta', 'posyandu')">
            <input type="hidden" name="posyandu_id" id="daftar_posyandu_id">
            <div class="space-y-4">
            <div class="space-y-4">
                <!-- Integrated Searchable Select Peserta -->
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Peserta (Warga)</label>
                    <input type="hidden" name="nama_peserta" id="nama_peserta_hidden" required>
                    
                    <div class="relative">
                        <input type="text" id="peserta_search_input" placeholder="🔍 Cari & pilih nama peserta..." 
                               onfocus="showDropdown('peserta_dropdown')" 
                               onkeyup="filterCustomDropdown('peserta_search_input', 'peserta_dropdown')" 
                               autocomplete="off"
                               class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>

                    <div id="peserta_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-30 max-h-56 overflow-y-auto divide-y divide-gray-50">
                        @foreach($all_warga ?? [] as $w)
                            <div onclick="selectPesertaOption('{{ addslashes($w->nama_lengkap) }}', '{{ $w->umur }}', '{{ addslashes($w->status_keluarga) }}', '{{ $w->nomor_kk }}', '{{ $w->jenis_kelamin }}')" 
                                 class="dropdown-item px-4 py-2.5 hover:bg-rose-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                <div>
                                    <span class="block font-bold">{{ $w->nama_lengkap }}</span>
                                    <span class="text-[10px] text-gray-400 font-normal">{{ $w->status_keluarga }} · Blok {{ $w->blok_rumah }}</span>
                                </div>
                                <span class="text-[10px] text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full font-bold">{{ $w->umur ? $w->umur.' Thn' : '-' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Usia (Otomatis)</label>
                        <input type="text" name="usia" id="daftar_usia_input" placeholder="Cth: 2 Tahun / 65 Tahun" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="daftar_jenis_kelamin" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500 text-xs">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                        <select name="kategori" id="daftar_kategori" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500 text-xs">
                            <option value="Balita">Balita</option>
                            <option value="Lansia">Lansia</option>
                            <option value="Ibu Hamil">Ibu Hamil</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tinggi Badan (cm)</label>
                        <input type="text" name="tinggi_badan" placeholder="Cth: 75" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Berat Badan (kg)</label>
                        <input type="text" name="berat_badan" placeholder="Cth: 8.5" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Pendaftar (Satu KK)</label>
                        <!-- Integrated Searchable Select Pendaftar -->
                        <div class="relative">
                            <input type="hidden" name="nama_pendaftar" id="nama_pendaftar_hidden" value="" required>
                            <div class="relative">
                                <input type="text" id="pendaftar_search_input" value="" placeholder="🔍 Pilih pendaftar se-KK..." 
                                       onfocus="showDropdown('pendaftar_dropdown')" 
                                       onkeyup="filterCustomDropdown('pendaftar_search_input', 'pendaftar_dropdown')" 
                                       autocomplete="off"
                                       class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-8 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>

                            <div id="pendaftar_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-30 max-h-48 overflow-y-auto divide-y divide-gray-50">
                                @foreach($all_warga ?? [] as $w)
                                    <div onclick="selectPendaftarOption('{{ addslashes($w->nama_lengkap) }}', '{{ addslashes($w->status_keluarga) }}')" 
                                         data-kk="{{ $w->nomor_kk }}"
                                         class="dropdown-item pendaftar-item-kk px-4 py-2 hover:bg-rose-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                        <span>{{ $w->nama_lengkap }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $w->status_keluarga }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Hubungan</label>
                        <select name="hubungan" id="daftar_hubungan" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="Ibu">Ibu</option>
                            <option value="Ayah">Ayah</option>
                            <option value="Nenek">Nenek</option>
                            <option value="Kakek">Kakek</option>
                            <option value="Cucu">Cucu</option>
                            <option value="Anak">Anak</option>
                            <option value="Suami">Suami</option>
                            <option value="Istri">Istri</option>
                            <option value="Diri Sendiri">Diri Sendiri</option>
                            <option value="Keluarga">Keluarga</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan Tambahan</label>
                    <textarea name="catatan" rows="2" placeholder="Alergi, riwayat penyakit, atau catatan khusus" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-daftar-peserta').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-lg shadow-rose-200">Daftarkan Peserta</button>
            </div>
        </form>
    </div>
</div>

<script>
function showDropdown(id) {
    document.getElementById(id).classList.remove('hidden');
}

function hideDropdown(id) {
    setTimeout(() => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }, 200);
}

function filterCustomDropdown(inputId, dropdownId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const dropdown = document.getElementById(dropdownId);
    dropdown.classList.remove('hidden');
    
    const items = dropdown.getElementsByClassName('dropdown-item');
    for (let i = 0; i < items.length; i++) {
        if (items[i].classList.contains('hidden-by-kk')) continue;
        const txt = items[i].textContent || items[i].innerText;
        if (txt.toLowerCase().includes(filter)) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}

const allWargaList = @json($all_warga ?? []);
let selectedPesertaStatus = '';

function selectPesertaOption(nama, umur, statusKeluarga, nomorKk, jk) {
    document.getElementById('peserta_search_input').value = nama;
    document.getElementById('nama_peserta_hidden').value = nama;
    document.getElementById('peserta_dropdown').classList.add('hidden');

    const usiaInput = document.getElementById('daftar_usia_input');
    const jkSelect = document.getElementById('daftar_jenis_kelamin');
    const pendaftarInputHidden = document.getElementById('nama_pendaftar_hidden');
    const pendaftarInputSearch = document.getElementById('pendaftar_search_input');

    selectedPesertaStatus = statusKeluarga;

    // 1. Isikan Jenis Kelamin
    if (jk && jk !== '') {
        jkSelect.value = jk;
    }

    // 1. Isikan Usia
    if (umur && umur !== '') {
        usiaInput.value = umur + ' Tahun';
    } else {
        usiaInput.value = '';
    }

    // 2. Filter opsi pendaftar HANYA untuk anggota keluarga se-KK yang sama
    const pendaftarItems = document.querySelectorAll('#pendaftar_dropdown .pendaftar-item-kk');
    pendaftarItems.forEach(item => {
        const itemKk = item.getAttribute('data-kk');
        if (itemKk == nomorKk) {
            item.classList.remove('hidden-by-kk');
            item.style.display = '';
        } else {
            item.classList.add('hidden-by-kk');
            item.style.display = 'none';
        }
    });

    // 3. Auto-select Pendaftar default (Kepala Keluarga / Ibu dalam 1 KK yang sama)
    const familyMembers = allWargaList.filter(w => w.nomor_kk == nomorKk);
    let pendaftarObj = null;

    if (statusKeluarga === 'Kepala Keluarga') {
        pendaftarObj = familyMembers.find(w => w.status_keluarga === 'Kepala Keluarga') || familyMembers[0];
    } else if (statusKeluarga === 'Istri') {
        pendaftarObj = familyMembers.find(w => w.status_keluarga === 'Kepala Keluarga') || familyMembers.find(w => w.status_keluarga === 'Istri');
    } else {
        // Anak / Lainnya: Pendaftar utamanya adalah Ibu (Istri) atau Ayah (Kepala Keluarga)
        pendaftarObj = familyMembers.find(w => w.status_keluarga === 'Istri') || familyMembers.find(w => w.status_keluarga === 'Kepala Keluarga') || familyMembers[0];
    }

    if (pendaftarObj) {
        const pendaftarNama = pendaftarObj.nama_lengkap;
        if (pendaftarInputHidden) pendaftarInputHidden.value = pendaftarNama;
        if (pendaftarInputSearch) pendaftarInputSearch.value = pendaftarNama;

        // Auto-select hubungan
        updateHubunganAuto(nama, pendaftarNama, statusKeluarga, pendaftarObj.status_keluarga);
    }
}

function selectPendaftarOption(pendaftarNama, pendaftarStatus) {
    document.getElementById('pendaftar_search_input').value = pendaftarNama;
    document.getElementById('nama_pendaftar_hidden').value = pendaftarNama;
    document.getElementById('pendaftar_dropdown').classList.add('hidden');

    const pesertaNama = document.getElementById('nama_peserta_hidden').value;
    updateHubunganAuto(pesertaNama, pendaftarNama, selectedPesertaStatus, pendaftarStatus);
}

function updateHubunganAuto(pesertaNama, pendaftarNama, pesertaStatus, pendaftarStatus) {
    const hubunganSelect = document.getElementById('daftar_hubungan');
    if (!hubunganSelect) return;

    if (pesertaNama === pendaftarNama) {
        hubunganSelect.value = 'Diri Sendiri';
    } else if (pesertaStatus === 'Anak') {
        if (pendaftarStatus === 'Istri') {
            hubunganSelect.value = 'Ibu';
        } else if (pendaftarStatus === 'Kepala Keluarga') {
            hubunganSelect.value = 'Ayah';
        } else {
            hubunganSelect.value = 'Keluarga';
        }
    } else if (pesertaStatus === 'Istri') {
        if (pendaftarStatus === 'Kepala Keluarga') {
            hubunganSelect.value = 'Suami';
        } else if (pendaftarStatus === 'Anak') {
            hubunganSelect.value = 'Anak';
        } else {
            hubunganSelect.value = 'Keluarga';
        }
    } else if (pesertaStatus === 'Kepala Keluarga') {
        if (pendaftarStatus === 'Istri') {
            hubunganSelect.value = 'Istri';
        } else if (pendaftarStatus === 'Anak') {
            hubunganSelect.value = 'Anak';
        } else {
            hubunganSelect.value = 'Keluarga';
        }
    } else {
        hubunganSelect.value = 'Keluarga';
    }
}

// Global click event to close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    const pInput = document.getElementById('peserta_search_input');
    const pDrop = document.getElementById('peserta_dropdown');
    if (pInput && pDrop && !pInput.contains(e.target) && !pDrop.contains(e.target)) {
        pDrop.classList.add('hidden');
    }

    const dInput = document.getElementById('pendaftar_search_input');
    const dDrop = document.getElementById('pendaftar_dropdown');
    if (dInput && dDrop && !dInput.contains(e.target) && !dDrop.contains(e.target)) {
        dDrop.classList.add('hidden');
    }
});

function bukaModalDaftar(posyanduId, namaKegiatan, targetPeserta) {
    document.getElementById('form-daftar-peserta').reset();
    document.getElementById('daftar_posyandu_id').value = posyanduId;
    document.getElementById('daftar-modal-title').textContent = 'Daftarkan Peserta';
    document.getElementById('daftar-modal-subtitle').textContent = namaKegiatan + ' — ' + targetPeserta;

    const pSearch = document.getElementById('peserta_search_input');
    const pHidden = document.getElementById('nama_peserta_hidden');
    const dSearch = document.getElementById('pendaftar_search_input');
    const dHidden = document.getElementById('nama_pendaftar_hidden');

    if (pSearch) pSearch.value = '';
    if (pHidden) pHidden.value = '';
    if (dSearch) dSearch.value = '';
    if (dHidden) dHidden.value = '';

    // Show all items initially
    const pendaftarItems = document.querySelectorAll('#pendaftar_dropdown .pendaftar-item-kk');
    pendaftarItems.forEach(item => {
        item.classList.remove('hidden-by-kk');
        item.style.display = '';
    });

    // Auto-set kategori based on target peserta
    const kategoriSelect = document.getElementById('daftar_kategori');
    if (targetPeserta.toLowerCase().includes('lansia')) kategoriSelect.value = 'Lansia';
    else if (targetPeserta.toLowerCase().includes('hamil')) kategoriSelect.value = 'Ibu Hamil';
    else kategoriSelect.value = 'Balita';

    document.getElementById('modal-daftar-peserta').classList.remove('hidden');
}

function hapusPosyandu(id) {
    if (!confirm('Hapus agenda posyandu ini beserta semua peserta terdaftar?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('posyandu'); }
    fetch('/posyandu/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('posyandu', document.querySelector('.menu-active')); });
}

function hapusPendaftaran(id) {
    if (!confirm('Batalkan pendaftaran peserta ini?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('posyandu'); }
    fetch('/posyandu/daftar/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('posyandu', document.querySelector('.menu-active')); });
}

function ubahStatusPeserta(id, status) {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('status', status);
    fd.append('_token', window.csrfToken);
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('posyandu'); }
    fetch('/posyandu/daftar/status', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => { alert(data.message); switchPage('posyandu', document.querySelector('.menu-active')); });
}
</script>
