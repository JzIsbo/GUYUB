@php
    $isAdmin = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']);
    $isWarga = in_array(Auth::user()->role, ['Warga', 'Bendahara RT', 'Bendahara RW']);
@endphp

<div class="space-y-4 max-w-[600px] mx-auto pb-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-black text-gray-900 tracking-tight">Data Keluarga</h2>
            <p class="text-[10px] text-gray-400 font-medium">Kelola data Kartu Keluarga</p>
        </div>
        <div class="flex gap-2 shrink-0">
            <span class="bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-xl text-[10px] font-extrabold text-indigo-700">{{ $total_kk ?? 0 }} KK</span>
            @if(!$isWarga || ($total_kk == 0))
            <button onclick="openModalTambahKk()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-[10px] px-3 py-1.5 rounded-xl shadow-sm flex items-center gap-1">
                <i class="fa-solid fa-plus text-[9px]"></i> Tambah
            </button>
            @endif
        </div>
    </div>

    {{-- Search --}}
    @if(!$isWarga)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
            <input type="text" id="m-search-keluarga" placeholder="Cari nama KK, No. KK, blok..." class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition-all" onkeyup="filterKeluargaMobile(this.value)">
        </div>
    </div>
    @endif

    {{-- Family Cards --}}
    <div id="m-keluarga-container" class="space-y-3">
        @forelse($keluarga_list ?? [] as $kk)
        <div id="m-kk-card-{{ $kk->nomor_kk }}" class="m-keluarga-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" data-search="{{ strtolower($kk->kepala_keluarga . ' ' . $kk->nomor_kk . ' ' . $kk->blok_rumah) }}">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 via-indigo-900 to-slate-900 p-4 text-white relative overflow-hidden">
                <div class="flex items-start justify-between relative z-10">
                    <div class="min-w-0 flex-1">
                        <span class="bg-white/15 text-[7px] font-extrabold uppercase tracking-[1.5px] px-2 py-0.5 rounded-full border border-white/10">Kartu Keluarga</span>
                        <h3 class="text-sm font-black tracking-tight mt-1.5 truncate">{{ $kk->kepala_keluarga }}</h3>
                        <p class="text-blue-200 text-[10px] font-mono mt-0.5">KK: {{ $kk->nomor_kk }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 shrink-0 ml-2">
                        <div class="bg-white/10 px-2.5 py-1 rounded-lg border border-white/10 text-center">
                            <p class="text-[7px] text-blue-200 font-bold uppercase">Anggota</p>
                            <p class="text-base font-black leading-none">{{ $kk->total_anggota }}</p>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="openModalEditKk('{{ $kk->nomor_kk }}', '{{ $kk->blok_rumah }}')" class="w-6 h-6 bg-white/15 border border-white/10 rounded-md text-white flex items-center justify-center"><i class="fa-solid fa-pen text-[9px]"></i></button>
                            <button onclick="hapusKk('{{ $kk->nomor_kk }}')" class="w-6 h-6 bg-red-500/20 border border-red-500/30 rounded-md text-red-300 flex items-center justify-center"><i class="fa-solid fa-trash text-[9px]"></i></button>
                        </div>
                    </div>
                </div>
                <i class="fa-solid fa-id-card absolute -bottom-4 -right-4 text-white/5 text-[70px] pointer-events-none"></i>
            </div>

            {{-- Location Bar --}}
            <div class="px-4 py-2 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-medium">
                <div class="flex items-center gap-3">
                    <span><i class="fa-solid fa-location-dot text-indigo-400 mr-1"></i>{{ $kk->blok_rumah }}</span>
                    <span><i class="fa-solid fa-phone text-emerald-400 mr-1"></i>{{ $kk->no_telepon ?: '-' }}</span>
                </div>
                <button onclick="openModalTambahMember('{{ $kk->nomor_kk }}', '{{ $kk->blok_rumah }}')" class="text-indigo-600 font-extrabold text-[10px] flex items-center gap-0.5">
                    <i class="fa-solid fa-plus-circle"></i> Anggota
                </button>
            </div>

            {{-- Members --}}
            <div class="p-3 space-y-1.5">
                @foreach($kk->members as $m)
                <div id="m-member-row-{{ $m->id }}" class="flex items-center justify-between p-2 rounded-xl {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-50/70' : 'bg-gray-50/70' }} text-xs">
                    <div class="flex items-center gap-2 min-w-0 flex-1">
                        <div class="w-7 h-7 rounded-lg {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-600' : ($m->status_keluarga == 'Istri' ? 'bg-pink-500' : 'bg-blue-500') }} text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                            {{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-gray-800 text-[11px] truncate leading-tight">{{ $m->nama_lengkap }}</p>
                            <div class="flex flex-wrap gap-x-2 gap-y-0.5 mt-0.5 text-[8px] text-gray-400 font-medium">
                                <span class="font-mono">NIK: {{ $m->nik }}</span>
                                <span>Gender: {{ $m->jenis_kelamin }}</span>
                                <span>Blok: {{ $m->blok_rumah }}</span>
                                <span>Agama: {{ $m->agama ?: '-' }}</span>
                                <span>Domisili: {{ $m->status_domisili }}</span>
                                <span>Telp: {{ $m->no_telepon ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0 ml-2">
                        <span class="px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase {{ $m->status_keluarga == 'Kepala Keluarga' ? 'bg-indigo-100 text-indigo-700' : ($m->status_keluarga == 'Istri' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ $m->status_keluarga }}
                        </span>
                        <button onclick="openModalEditMember({{ json_encode($m) }})" class="w-6 h-6 rounded bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-pen text-[8px]"></i></button>
                        @if($m->status_keluarga !== 'Kepala Keluarga')
                        <button onclick="hapusMember({{ $m->id }}, '{{ $m->nomor_kk }}')" class="w-6 h-6 rounded bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash text-[8px]"></i></button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
            <i class="fa-solid fa-house-circle-xmark text-3xl text-gray-200 mb-3"></i>
            <p class="text-gray-400 font-bold text-xs">Belum ada data keluarga.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- MODALS DEFINITIONS --}}
{{-- ➕ MOBILE MODAL TAMBAH KK BARU --}}
<div id="modal-tambah-kk" class="hidden fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-0">
    <div class="bg-white rounded-t-2xl p-5 w-full max-w-[95vw] shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-black text-gray-800">Tambah Kartu Keluarga</h3>
            <button onclick="tutupModalKk('modal-tambah-kk')" class="w-7 h-7 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-tambah-kk" onsubmit="simpanKk(event)">
            @csrf
            <div class="space-y-3 text-xs">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Nomor KK</label>
                        <input type="number" name="nomor_kk" required class="w-full p-2.5 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Blok Rumah</label>
                        <input type="text" name="blok_rumah" required class="w-full p-2.5 border rounded-xl" placeholder="Contoh: A1">
                    </div>
                </div>
                <hr class="border-gray-100">
                <p class="font-extrabold text-indigo-600 text-[9px] uppercase tracking-wider">Identitas Kepala Keluarga</p>
                <div>
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">NIK Kepala Keluarga</label>
                    <input type="number" name="nik" required class="w-full p-2.5 border rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required class="w-full p-2.5 border rounded-xl">
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Umur</label>
                        <input type="number" name="umur" class="w-full p-2.5 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Agama</label>
                        <select name="agama" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Domisili</label>
                        <select name="status_domisili" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">No. Telepon</label>
                    <input type="text" name="no_telepon" class="w-full p-2.5 border rounded-xl">
                </div>
            </div>
            <button type="submit" class="w-full mt-4 bg-indigo-600 text-white font-bold py-2.5 rounded-xl text-xs">Simpan KK</button>
        </form>
    </div>
</div>

{{-- ✏️ MOBILE MODAL EDIT KK --}}
<div id="modal-edit-kk" class="hidden fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-0">
    <div class="bg-white rounded-t-2xl p-5 w-full max-w-[95vw] shadow-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-black text-gray-800">Ubah Data Kartu Keluarga</h3>
            <button onclick="tutupModalKk('modal-edit-kk')" class="w-7 h-7 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-edit-kk" onsubmit="simpanEditKk(event)">
            @csrf
            <input type="hidden" name="old_nomor_kk" id="edit-old-nomor-kk">
            <div class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Nomor KK Baru</label>
                    <input type="number" name="nomor_kk" id="edit-nomor-kk" required class="w-full p-2.5 border rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Blok Rumah Baru</label>
                    <input type="text" name="blok_rumah" id="edit-blok-rumah" required class="w-full p-2.5 border rounded-xl">
                </div>
            </div>
            <button type="submit" class="w-full mt-4 bg-indigo-600 text-white font-bold py-2.5 rounded-xl text-xs">Update KK</button>
        </form>
    </div>
</div>

{{-- 👪 MOBILE MODAL TAMBAH ANGGOTA KELUARGA --}}
<div id="modal-tambah-member" class="hidden fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-0">
    <div class="bg-white rounded-t-2xl p-5 w-full max-w-[95vw] shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-sm font-black text-gray-800">Tambah Anggota Keluarga</h3>
            <button onclick="tutupModalKk('modal-tambah-member')" class="w-7 h-7 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <p class="text-[9px] text-gray-400 font-semibold uppercase tracking-wider mb-4">KK: <span id="label-kk-member" class="text-indigo-600 font-mono"></span></p>
        <form id="form-tambah-member" onsubmit="simpanMember(event)">
            @csrf
            <input type="hidden" name="nomor_kk" id="member-nomor-kk">
            <input type="hidden" name="blok_rumah" id="member-blok-rumah">
            <div class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">NIK Anggota</label>
                    <input type="number" name="nik" required class="w-full p-2.5 border rounded-xl">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required class="w-full p-2.5 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Hubungan</label>
                        <select name="status_keluarga" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Umur</label>
                        <input type="number" name="umur" class="w-full p-2.5 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Domisili</label>
                        <select name="status_domisili" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Agama</label>
                        <select name="agama" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">No. Telepon</label>
                        <input type="text" name="no_telepon" class="w-full p-2.5 border rounded-xl">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full mt-4 bg-indigo-600 text-white font-bold py-2.5 rounded-xl text-xs">Tambah Anggota</button>
        </form>
    </div>
</div>

{{-- ✏️ MOBILE MODAL EDIT ANGGOTA KELUARGA --}}
<div id="modal-edit-member" class="hidden fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-0">
    <div class="bg-white rounded-t-2xl p-5 w-full max-w-[95vw] shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-black text-gray-800">Ubah Data Anggota</h3>
            <button onclick="tutupModalKk('modal-edit-member')" class="w-7 h-7 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-edit-member" onsubmit="simpanEditMember(event)">
            @csrf
            <input type="hidden" name="id" id="edit-member-id">
            <input type="hidden" name="nomor_kk" id="edit-member-nomor-kk">
            <div class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">NIK Anggota</label>
                    <input type="number" name="nik" id="edit-member-nik" required class="w-full p-2.5 border rounded-xl">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit-member-name" required class="w-full p-2.5 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit-member-jenis-kelamin" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Hubungan</label>
                        <select name="status_keluarga" id="edit-member-status" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Umur</label>
                        <input type="number" name="umur" id="edit-member-umur" class="w-full p-2.5 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Domisili</label>
                        <select name="status_domisili" id="edit-member-domisili" required class="w-full p-2.5 border rounded-xl font-bold">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Blok Rumah</label>
                        <input type="text" name="blok_rumah" id="edit-member-blok" required class="w-full p-2.5 border rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">Agama</label>
                        <select name="agama" id="edit-member-agama" required class="w-full p-2.5 border rounded-xl font-bold">
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
                    <label class="block font-bold text-gray-400 text-[9px] uppercase tracking-wider mb-0.5">No. Telepon</label>
                    <input type="text" name="no_telepon" id="edit-member-phone" class="w-full p-2.5 border rounded-xl">
                </div>
            </div>
            <button type="submit" class="w-full mt-4 bg-indigo-600 text-white font-bold py-2.5 rounded-xl text-xs">Update Anggota</button>
        </form>
    </div>
</div>

<script>
function filterKeluargaMobile(query) {
    const q = query.toLowerCase().trim();
    document.querySelectorAll('.m-keluarga-card').forEach(card => {
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
    const fd = new FormData(e.target);
    
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
    .catch(() => alert('Terjadi kesalahan koneksi.'));
}

function simpanEditKk(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    
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
                const el = document.getElementById(`m-kk-card-${nomorKk}`);
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
            text: 'Seluruh anggota keluarga pada KK ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' }
        }).then(res => {
            if (res.isConfirmed) doDelete();
        });
    } else {
        if (confirm('Yakin ingin menghapus seluruh anggota pada KK ini?')) doDelete();
    }
}

function simpanMember(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    
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
    const fd = new FormData(e.target);
    
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
                const el = document.getElementById(`m-member-row-${id}`);
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
            customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' }
        }).then(res => {
            if (res.isConfirmed) doDelete();
        });
    } else {
        if (confirm('Yakin ingin menghapus anggota keluarga ini?')) doDelete();
    }
}
</script>
