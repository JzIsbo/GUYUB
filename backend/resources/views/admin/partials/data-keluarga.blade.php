@php
    $isAdmin = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']);
    $isWarga = in_array(Auth::user()->role, ['Warga', 'Bendahara RT', 'Bendahara RW']);
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Data Keluarga</h2>
            <p class="text-sm text-gray-400 font-medium mt-1">Kelola data Kartu Keluarga warga RT</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-2xl flex items-center gap-2">
                <i class="fa-solid fa-house-user text-indigo-600 text-sm"></i>
                <span class="text-xs font-extrabold text-indigo-700">{{ $total_kk ?? 0 }} KK</span>
            </div>
            <div class="bg-blue-50 border border-blue-100 px-4 py-2 rounded-2xl flex items-center gap-2">
                <i class="fa-solid fa-users text-blue-600 text-sm"></i>
                <span class="text-xs font-extrabold text-blue-700">{{ $total_warga_keluarga ?? 0 }} Jiwa</span>
            </div>
            @if(!$isWarga || ($total_kk == 0))
            <button onclick="openModalTambahKk()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs px-5 py-3 rounded-2xl shadow-lg shadow-indigo-500/20 hover:scale-[1.02] transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus-circle text-sm"></i> Tambah KK Baru
            </button>
            @endif
        </div>
    </div>

    {{-- Search --}}
    @if(!$isWarga)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
            <input type="text" id="search-keluarga" placeholder="Cari berdasarkan nama kepala keluarga, No. KK, atau blok rumah..." class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all" onkeyup="filterKeluarga(this.value)">
        </div>
    </div>
    @endif

    {{-- Family Cards --}}
    <div id="keluarga-container" class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @forelse($keluarga_list ?? [] as $kk)
        <div id="kk-card-{{ $kk->nomor_kk }}" class="keluarga-card bg-white rounded-[2rem] border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-lg transition-shadow duration-300" data-search="{{ strtolower($kk->kepala_keluarga . ' ' . $kk->nomor_kk . ' ' . $kk->blok_rumah) }}">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 via-indigo-900 to-slate-900 p-5 text-white relative overflow-hidden">
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-white/15 text-[9px] font-extrabold uppercase tracking-[2px] px-2.5 py-0.5 rounded-full border border-white/10">Kartu Keluarga</span>
                        </div>
                        <h3 class="text-lg font-black tracking-tight" id="kk-kepala-title-{{ $kk->nomor_kk }}">{{ $kk->kepala_keluarga }}</h3>
                        <p class="text-blue-200 text-xs font-mono mt-1">No. KK: <span id="kk-nomor-title-{{ $kk->nomor_kk }}">{{ $kk->nomor_kk }}</span></p>
                    </div>
                    <div class="flex flex-col items-end gap-2 shrink-0">
                        <div class="bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 text-center">
                            <p class="text-[9px] text-blue-200 font-bold uppercase tracking-widest">Anggota</p>
                            <p class="text-lg font-black leading-none mt-0.5" id="kk-total-{{ $kk->nomor_kk }}">{{ $kk->total_anggota }}</p>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="openModalEditKk('{{ $kk->nomor_kk }}', '{{ $kk->blok_rumah }}')" class="w-7 h-7 bg-white/15 hover:bg-white/25 border border-white/10 rounded-lg text-white flex items-center justify-center transition-all" title="Edit Data KK"><i class="fa-solid fa-pen text-[10px]"></i></button>
                            <button onclick="hapusKk('{{ $kk->nomor_kk }}')" class="w-7 h-7 bg-red-500/20 hover:bg-red-500/40 border border-red-500/30 rounded-lg text-red-300 flex items-center justify-center transition-all" title="Hapus Seluruh KK"><i class="fa-solid fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                </div>
                <i class="fa-solid fa-id-card absolute -bottom-6 -right-6 text-white/5 text-[100px] pointer-events-none"></i>
            </div>

            {{-- Card Info --}}
            <div class="px-5 py-3 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between text-xs text-gray-500 font-medium">
                <div class="flex items-center gap-4">
                    <span><i class="fa-solid fa-location-dot text-indigo-400 mr-1.5"></i><span id="kk-blok-title-{{ $kk->nomor_kk }}">{{ $kk->blok_rumah }}</span></span>
                    <span><i class="fa-solid fa-phone text-emerald-400 mr-1.5"></i>{{ $kk->no_telepon ?: '-' }}</span>
                </div>
                <button onclick="openModalTambahMember('{{ $kk->nomor_kk }}', '{{ $kk->blok_rumah }}')" class="text-indigo-600 hover:text-indigo-800 font-extrabold flex items-center gap-1">
                    <i class="fa-solid fa-plus-circle"></i> Anggota
                </button>
            </div>

            {{-- Members List --}}
            <div class="p-5 space-y-2.5" id="kk-members-list-{{ $kk->nomor_kk }}">
                @foreach($kk->members as $m)
                <div id="member-row-{{ $m->id }}" class="flex items-center justify-between p-3 rounded-xl {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-50/70 border border-indigo-100' : 'bg-gray-50/70 border border-gray-100' }} transition-all hover:shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-600' : ($m->status_keluarga == 'Istri' ? 'bg-pink-500' : 'bg-blue-500') }} text-white flex items-center justify-center font-extrabold text-xs shrink-0 shadow-sm">
                            {{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-extrabold text-gray-800 text-sm truncate">{{ $m->nama_lengkap }}</p>
                            <div class="flex flex-wrap gap-x-3 gap-y-1 mt-1 text-[10px] text-gray-400 font-semibold">
                                <span class="font-mono"><i class="fa-solid fa-id-card text-slate-400 mr-1"></i>NIK: {{ $m->nik }}</span>
                                <span><i class="fa-solid fa-venus-mars text-purple-400 mr-1"></i>Gender: {{ $m->jenis_kelamin }}</span>
                                <span><i class="fa-solid fa-location-dot text-indigo-400 mr-1"></i>Blok: {{ $m->blok_rumah }}</span>
                                <span><i class="fa-solid fa-book-open text-orange-400 mr-1"></i>Agama: {{ $m->agama ?: '-' }}</span>
                                <span><i class="fa-solid fa-house-chimney text-emerald-400 mr-1"></i>Domisili: {{ $m->status_domisili }}</span>
                                <span><i class="fa-solid fa-phone text-blue-400 mr-1"></i>Telp: {{ $m->no_telepon ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if($m->umur)<span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-md">{{ $m->umur }} Thn</span>@endif
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-100 text-indigo-700' : ($m->status_keluarga == 'Istri' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ $m->status_keluarga }}
                        </span>
                        <div class="flex gap-1 ml-1">
                            <button onclick="openModalEditMember({{ json_encode($m) }})" class="w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-all"><i class="fa-solid fa-pen text-[9px]"></i></button>
                            @if($m->status_keluarga !== 'Kepala Keluarga')
                            <button onclick="hapusMember({{ $m->id }}, '{{ $m->nomor_kk }}')" class="w-6 h-6 rounded-md bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-all"><i class="fa-solid fa-trash text-[9px]"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <i class="fa-solid fa-house-circle-xmark text-4xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold">Belum ada data keluarga yang terdaftar.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- MODALS DEFINITIONS --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- ➕ MODAL TAMBAH KK BARU --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="modal-tambah-kk" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-lg shadow-2xl relative">
        <button onclick="tutupModalKk('modal-tambah-kk')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="text-xl font-black text-gray-800 mb-6">Tambah Kartu Keluarga Baru</h2>
        <form id="form-tambah-kk" onsubmit="simpanKk(event)">
            @csrf
            <div class="space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Nomor KK</label>
                        <input type="number" name="nomor_kk" required class="w-full p-3 border rounded-xl" placeholder="16 Digit Nomor KK">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Blok Rumah</label>
                        <input type="text" name="blok_rumah" required class="w-full p-3 border rounded-xl" placeholder="Contoh: Blok B1">
                    </div>
                </div>
                <hr class="border-gray-100 my-2">
                <p class="font-extrabold text-indigo-600 text-[10px] uppercase tracking-wider">Identitas Kepala Keluarga</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">NIK Kepala Keluarga</label>
                        <input type="number" name="nik" required class="w-full p-3 border rounded-xl" placeholder="16 Digit NIK">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required class="w-full p-3 border rounded-xl" placeholder="Nama Kepala Keluarga">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Umur</label>
                        <input type="number" name="umur" class="w-full p-3 border rounded-xl" placeholder="Tahun">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Agama</label>
                        <select name="agama" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Domisili</label>
                        <select name="status_domisili" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-gray-500 mb-1">No. Telepon / WA</label>
                    <input type="text" name="no_telepon" class="w-full p-3 border rounded-xl" placeholder="08xxxx">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="tutupModalKk('modal-tambah-kk')" class="flex-1 p-3.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 p-3.5 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 transition">Simpan KK</button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- ✏️ MODAL EDIT KK (NOMOR & BLOK) --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="modal-edit-kk" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-md shadow-2xl relative">
        <button onclick="tutupModalKk('modal-edit-kk')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="text-xl font-black text-gray-800 mb-6">Ubah Data Kartu Keluarga</h2>
        <form id="form-edit-kk" onsubmit="simpanEditKk(event)">
            @csrf
            <input type="hidden" name="old_nomor_kk" id="edit-old-nomor-kk">
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-500 mb-1">Nomor KK Baru</label>
                    <input type="number" name="nomor_kk" id="edit-nomor-kk" required class="w-full p-3 border rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-gray-500 mb-1">Blok Rumah Baru</label>
                    <input type="text" name="blok_rumah" id="edit-blok-rumah" required class="w-full p-3 border rounded-xl">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="tutupModalKk('modal-edit-kk')" class="flex-1 p-3.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 p-3.5 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 transition">Update Data</button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- 👪 MODAL TAMBAH ANGGOTA KELUARGA --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="modal-tambah-member" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-lg shadow-2xl relative">
        <button onclick="tutupModalKk('modal-tambah-member')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="text-xl font-black text-gray-800 mb-4">Tambah Anggota Keluarga</h2>
        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-6">KK: <span id="label-kk-member" class="text-indigo-600 font-mono"></span></p>
        <form id="form-tambah-member" onsubmit="simpanMember(event)">
            @csrf
            <input type="hidden" name="nomor_kk" id="member-nomor-kk">
            <input type="hidden" name="blok_rumah" id="member-blok-rumah">
            <div class="space-y-4 text-xs">
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">NIK Anggota</label>
                        <input type="number" name="nik" required class="w-full p-3 border rounded-xl" placeholder="16 Digit NIK">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required class="w-full p-3 border rounded-xl" placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Hubungan</label>
                        <select name="status_keluarga" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Umur</label>
                        <input type="number" name="umur" class="w-full p-3 border rounded-xl" placeholder="Tahun">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Domisili</label>
                        <select name="status_domisili" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Agama</label>
                        <select name="agama" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">No. Telepon</label>
                        <input type="text" name="no_telepon" class="w-full p-3 border rounded-xl" placeholder="Optional">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="tutupModalKk('modal-tambah-member')" class="flex-1 p-3.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 p-3.5 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 transition">Tambah Anggota</button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- ✏️ MODAL EDIT ANGGOTA KELUARGA --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="modal-edit-member" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-lg shadow-2xl relative">
        <button onclick="tutupModalKk('modal-edit-member')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="text-xl font-black text-gray-800 mb-6">Ubah Data Anggota Keluarga</h2>
        <form id="form-edit-member" onsubmit="simpanEditMember(event)">
            @csrf
            <input type="hidden" name="id" id="edit-member-id">
            <input type="hidden" name="nomor_kk" id="edit-member-nomor-kk">
            <div class="space-y-4 text-xs">
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">NIK Anggota</label>
                        <input type="number" name="nik" id="edit-member-nik" required class="w-full p-3 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit-member-name" required class="w-full p-3 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit-member-jenis-kelamin" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Hubungan</label>
                        <select name="status_keluarga" id="edit-member-status" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Umur</label>
                        <input type="number" name="umur" id="edit-member-umur" class="w-full p-3 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Domisili</label>
                        <select name="status_domisili" id="edit-member-domisili" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Blok Rumah</label>
                        <input type="text" name="blok_rumah" id="edit-member-blok" required class="w-full p-3 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 mb-1">Agama</label>
                        <select name="agama" id="edit-member-agama" required class="w-full p-3 border rounded-xl font-bold">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-gray-500 mb-1">No. Telepon</label>
                    <input type="text" name="no_telepon" id="edit-member-phone" class="w-full p-3 border rounded-xl">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="tutupModalKk('modal-edit-member')" class="flex-1 p-3.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 p-3.5 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 transition">Update Anggota</button>
            </div>
        </form>
    </div>
</div>

<script>
function filterKeluarga(query) {
    const q = query.toLowerCase().trim();
    document.querySelectorAll('.keluarga-card').forEach(card => {
        const data = card.getAttribute('data-search') || '';
        card.style.display = (!q || data.includes(q)) ? '' : 'none';
    });
}
function openModalTambahKk() {
    document.getElementById('form-tambah-kk').reset();
    document.getElementById('modal-tambah-kk').classList.remove('hidden');
}

function openModalEditKk(nomorKk, blokRumah) {
    document.getElementById('edit-old-nomor-kk').value = nomorKk;
    document.getElementById('edit-nomor-kk').value = nomorKk;
    document.getElementById('edit-blok-rumah').value = blokRumah;
    document.getElementById('modal-edit-kk').classList.remove('hidden');
}

function openModalTambahMember(nomorKk, blokRumah) {
    document.getElementById('form-tambah-member').reset();
    document.getElementById('member-nomor-kk').value = nomorKk;
    document.getElementById('member-blok-rumah').value = blokRumah;
    document.getElementById('label-kk-member').textContent = nomorKk;
    document.getElementById('modal-tambah-member').classList.remove('hidden');
}

function openModalEditMember(member) {
    document.getElementById('edit-member-id').value = member.id;
    document.getElementById('edit-member-nomor-kk').value = member.nomor_kk;
    document.getElementById('edit-member-nik').value = member.nik;
    document.getElementById('edit-member-name').value = member.nama_lengkap;
    document.getElementById('edit-member-jenis-kelamin').value = member.jenis_kelamin || 'Laki-laki';
    document.getElementById('edit-member-status').value = member.status_keluarga;
    document.getElementById('edit-member-umur').value = member.umur || '';
    document.getElementById('edit-member-domisili').value = member.status_domisili;
    document.getElementById('edit-member-blok').value = member.blok_rumah;
    document.getElementById('edit-member-agama').value = member.agama || '';
    document.getElementById('edit-member-phone').value = member.no_telepon || '';
    document.getElementById('modal-edit-member').classList.remove('hidden');
}

function tutupModalKk(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function simpanKk(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-tambah-kk'));
    
    fetch("{{ route('keluarga.storeKk') }}", {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') {
            tutupModalKk('modal-tambah-kk');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Berhasil!', text: d.message, icon: 'success', timer: 1500, showConfirmButton: false });
            } else {
                alert(d.message);
            }
            if (typeof window.invalidatePageCache === 'function') {
                window.invalidatePageCache('data-keluarga');
                window.invalidatePageCache('data-warga');
            }
            if (typeof switchPage === 'function') switchPage('data-keluarga', document.querySelector('.menu-active'));
        } else {
            alert('Gagal: ' + d.message);
        }
    })
    .catch(() => alert('Terjadi kesalahan koneksi sistem.'));
}

function simpanEditKk(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-edit-kk'));
    
    fetch("{{ route('keluarga.updateKk') }}", {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') {
            tutupModalKk('modal-edit-kk');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Berhasil!', text: d.message, icon: 'success', timer: 1500, showConfirmButton: false });
            } else {
                alert(d.message);
            }
            if (typeof window.invalidatePageCache === 'function') {
                window.invalidatePageCache('data-keluarga');
                window.invalidatePageCache('data-warga');
            }
            if (typeof switchPage === 'function') switchPage('data-keluarga', document.querySelector('.menu-active'));
        } else {
            alert('Gagal: ' + d.message);
        }
    })
    .catch(() => alert('Terjadi kesalahan koneksi.'));
}

function hapusKk(nomorKk) {
    const doDelete = () => {
        const fd = new FormData();
        fd.append('_token', '{{ csrf_token() }}');
        
        fetch(`/keluarga/delete-kk/${nomorKk}`, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                const el = document.getElementById(`kk-card-${nomorKk}`);
                if (el) el.remove();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ title: 'Terhapus!', text: d.message, icon: 'success', timer: 1500, showConfirmButton: false });
                } else {
                    alert(d.message);
                }
                if (typeof window.invalidatePageCache === 'function') {
                    window.invalidatePageCache('data-keluarga');
                    window.invalidatePageCache('data-warga');
                }
                if (typeof switchPage === 'function') switchPage('data-keluarga', document.querySelector('.menu-active'));
            } else {
                alert('Gagal: ' + d.message);
            }
        })
        .catch(() => alert('Gagal menghapus Kartu Keluarga.'));
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus Kartu Keluarga?',
            text: 'Seluruh anggota keluarga yang terdaftar pada KK ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-3xl p-6 font-sans' }
        }).then(res => {
            if (res.isConfirmed) doDelete();
        });
    } else {
        if (confirm('Yakin ingin menghapus seluruh anggota pada KK ini?')) doDelete();
    }
}

function simpanMember(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-tambah-member'));
    
    fetch("{{ route('keluarga.storeMember') }}", {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') {
            tutupModalKk('modal-tambah-member');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Berhasil!', text: d.message, icon: 'success', timer: 1500, showConfirmButton: false });
            } else {
                alert(d.message);
            }
            if (typeof window.invalidatePageCache === 'function') {
                window.invalidatePageCache('data-keluarga');
                window.invalidatePageCache('data-warga');
            }
            if (typeof switchPage === 'function') switchPage('data-keluarga', document.querySelector('.menu-active'));
        } else {
            alert('Gagal: ' + d.message);
        }
    })
    .catch(() => alert('Terjadi kesalahan.'));
}

function simpanEditMember(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-edit-member'));
    
    fetch("{{ route('keluarga.updateMember') }}", {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') {
            tutupModalKk('modal-edit-member');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Berhasil!', text: d.message, icon: 'success', timer: 1500, showConfirmButton: false });
            } else {
                alert(d.message);
            }
            if (typeof window.invalidatePageCache === 'function') {
                window.invalidatePageCache('data-keluarga');
                window.invalidatePageCache('data-warga');
            }
            if (typeof switchPage === 'function') switchPage('data-keluarga', document.querySelector('.menu-active'));
        } else {
            alert('Gagal: ' + d.message);
        }
    })
    .catch(() => alert('Terjadi kesalahan.'));
}

function hapusMember(id, nomorKk) {
    const doDelete = () => {
        const fd = new FormData();
        fd.append('_token', '{{ csrf_token() }}');
        
        fetch(`/keluarga/delete-member/${id}`, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                const el = document.getElementById(`member-row-${id}`);
                if (el) el.remove();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ title: 'Berhasil!', text: d.message, icon: 'success', timer: 1500, showConfirmButton: false });
                } else {
                    alert(d.message);
                }
                if (typeof window.invalidatePageCache === 'function') {
                    window.invalidatePageCache('data-keluarga');
                    window.invalidatePageCache('data-warga');
                }
                if (typeof switchPage === 'function') switchPage('data-keluarga', document.querySelector('.menu-active'));
            } else {
                alert('Gagal: ' + d.message);
            }
        })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus Anggota Keluarga?',
            text: 'Data anggota keluarga ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-3xl p-6 font-sans' }
        }).then(res => {
            if (res.isConfirmed) doDelete();
        });
    } else {
        if (confirm('Yakin ingin menghapus anggota keluarga ini?')) doDelete();
    }
}
</script>
