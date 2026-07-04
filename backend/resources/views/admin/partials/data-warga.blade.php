@php
    $isAdmin = in_array(Auth::user()->role, ['Super Admin', 'RT']);
    $totalWarga = 0;
    $totalKK = 0;
    if(isset($warga_grouped)) {
        $totalKK = $warga_grouped->count();
        foreach($warga_grouped as $anggota) {
            $totalWarga += $anggota->count();
        }
    }
@endphp

<div class="p-4 lg:p-8 space-y-8 max-w-[1400px] mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
                Data Warga RT
            </h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Sistem kependudukan digital berbasis Kartu Keluarga.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Stats Badges -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2.5 rounded-2xl flex items-center gap-3 shadow-lg shadow-blue-200">
                <div class="text-center">
                    <p class="text-lg font-black leading-none">{{ $totalKK }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-70">KK</p>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div class="text-center">
                    <p class="text-lg font-black leading-none">{{ $totalWarga }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-70">Jiwa</p>
                </div>
            </div>
            @if($isAdmin)
            <button type="button" onclick="bukaModal('modal-form-warga', 'Tambah Warga / KK Baru', true)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-2xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2 cursor-pointer text-sm">
                <i class="fa-solid fa-user-plus"></i> Tambah Warga Baru
            </button>
            @endif
        </div>
    </div>

    <!-- Search Bar -->
    <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
        <input type="text" id="searchWarga" onkeyup="filterWarga()" placeholder="Cari berdasarkan nama, NIK, atau blok rumah..." class="w-full bg-white border border-gray-100 pl-12 pr-6 py-4 rounded-2xl font-medium text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
    </div>

    <!-- Family Cards Grid -->
    <div id="familyCardsContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($warga_grouped ?? [] as $group_key => $anggota)
            @php
                $kepala = $anggota->firstWhere('status_keluarga', 'Kepala Keluarga') ?? $anggota->first();
                $current_kk = $anggota->first()->nomor_kk ?? 'belum-ada-kk';
                $current_blok = $anggota->first()->blok_rumah ?? 'Tanpa Blok';
                $jumlahAnggota = $anggota->count();
                // Color palette rotation
                $colors = ['blue', 'violet', 'emerald', 'amber', 'rose', 'cyan', 'indigo', 'teal'];
                $colorIdx = crc32($current_kk) % count($colors);
                $c = $colors[abs($colorIdx)];
            @endphp

            <div class="family-card bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group"
                 data-search="{{ strtolower($anggota->pluck('nama_lengkap')->join(' ') . ' ' . $current_kk . ' ' . $current_blok . ' ' . $anggota->pluck('nik')->join(' ')) }}">

                <!-- Card Header -->
                <div class="bg-gradient-to-r from-{{ $c }}-600 to-{{ $c }}-700 p-5 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full"></div>
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/5 rounded-full"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-house-chimney text-white/60 text-xs"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest text-white/70">Blok {{ $current_blok }}</span>
                            </div>
                            <h3 class="text-lg font-black tracking-tight">{{ $kepala->nama_lengkap }}</h3>
                            <p class="text-[10px] font-bold text-white/50 mt-0.5">No. KK: {{ $current_kk }}</p>
                        </div>
                        <div class="text-right">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                                <span class="text-lg font-black">{{ $jumlahAnggota }}</span>
                            </div>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-white/50 mt-1">Anggota</p>
                        </div>
                    </div>
                </div>

                <!-- Members List -->
                <div class="divide-y divide-gray-50">
                    @foreach($anggota as $index => $w)
                    @php
                        $avatarIcon = 'fa-user';
                        $avatarColor = 'gray';
                        if($w->status_keluarga == 'Kepala Keluarga') { $avatarIcon = 'fa-user-tie'; $avatarColor = 'blue'; }
                        elseif($w->status_keluarga == 'Istri') { $avatarIcon = 'fa-user'; $avatarColor = 'pink'; }
                        else { $avatarIcon = 'fa-child'; $avatarColor = 'emerald'; }
                    @endphp
                    <div class="px-5 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition group/member">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-{{ $avatarColor }}-50 text-{{ $avatarColor }}-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $avatarIcon }} text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-800 text-sm truncate">{{ $w->nama_lengkap }}</p>
                                <div class="flex items-center gap-2 flex-wrap mt-0.5">
                                    <span class="text-[10px] font-medium text-gray-400">{{ $w->nik }}</span>
                                    @if($w->no_telepon)
                                    <span class="text-[10px] font-medium text-gray-400">· <i class="fa-solid fa-phone text-[8px]"></i> {{ $w->no_telepon }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($w->status_keluarga == 'Kepala Keluarga')
                                <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-blue-100 text-blue-700">KK</span>
                            @elseif($w->status_keluarga == 'Istri')
                                <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-pink-100 text-pink-700">Istri</span>
                            @else
                                <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700">Anak</span>
                            @endif
                            <span class="px-2 py-1 rounded-lg text-[9px] font-bold {{ $w->status_domisili == 'Tetap' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">{{ $w->status_domisili }}</span>

                            @if($isAdmin)
                            <div class="flex items-center gap-1 opacity-0 group-hover/member:opacity-100 transition">
                                <button type="button" onclick="bukaModalEdit({{ $w->id }}, '{{ $w->nomor_kk }}', '{{ $w->nik }}', '{{ addslashes($w->nama_lengkap) }}', '{{ $w->no_telepon }}', '{{ $w->blok_rumah }}', '{{ $w->status_keluarga }}', '{{ $w->status_domisili }}')" class="w-7 h-7 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                </button>
                                <button type="button" onclick="hapusWarga({{ $w->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Card Footer -->
                @if($isAdmin)
                <div class="px-5 py-3 bg-gray-50/50 border-t border-gray-100">
                    <button type="button" onclick="bukaModalAnggota('{{ $current_kk }}', '{{ $current_blok }}')" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Anggota Keluarga
                    </button>
                </div>
                @endif
            </div>
        @empty
            <div class="col-span-2 bg-white p-12 rounded-[2.5rem] border border-gray-50 shadow-sm text-center">
                <i class="fa-solid fa-folder-open text-gray-200 text-6xl mb-4"></i>
                <h3 class="text-xl font-black text-gray-800 tracking-tight mb-2">Belum Ada Warga</h3>
                <p class="text-sm text-gray-500 font-medium">Sistem belum memiliki data warga untuk ditampilkan.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Form Warga -->
<div id="modal-form-warga" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-[2rem] w-full max-w-lg shadow-2xl">
        <h3 id="modal-title" class="font-black text-2xl mb-6 text-gray-800">Form Warga</h3>

        <form id="formWarga" onsubmit="simpanWarga(event)">
            <input type="hidden" id="warga_id" name="id">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nomor KK</label>
                    <input type="number" id="nomor_kk" name="nomor_kk" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NIK</label>
                    <input type="number" id="nik" name="nik" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Blok Rumah</label>
                    <input type="text" id="blok_rumah" name="blok_rumah" required placeholder="Cth: Blok A1" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Keluarga</label>
                    <select id="status_keluarga" name="status_keluarga" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                        <option value="Kepala Keluarga">Kepala Keluarga</option>
                        <option value="Istri">Istri</option>
                        <option value="Anak">Anak</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Domisili</label>
                    <select id="status_domisili" name="status_domisili" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                        <option value="Tetap">Tetap</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Kos">Kos</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="tutupModal('modal-form-warga')" class="flex-1 bg-gray-100 p-4 rounded-xl font-black text-gray-500 text-xs uppercase tracking-widest hover:bg-gray-200 transition">Batal</button>
                <button type="submit" id="btn-submit" class="flex-1 bg-blue-600 text-white p-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    var csrfToken = window.csrfToken || (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}');

    // Search / Filter
    function filterWarga() {
        const query = document.getElementById('searchWarga').value.toLowerCase();
        document.querySelectorAll('.family-card').forEach(card => {
            const data = card.getAttribute('data-search');
            card.style.display = data.includes(query) ? '' : 'none';
        });
    }

    function bukaModal(idModal, judul, isBaru) {
        document.getElementById(idModal).classList.remove('hidden');
        document.getElementById('modal-title').innerText = judul;
        if(isBaru) {
            document.getElementById('formWarga').reset();
            document.getElementById('warga_id').value = '';
            document.getElementById('nomor_kk').readOnly = false;
            document.getElementById('blok_rumah').readOnly = false;
            document.getElementById('nomor_kk').classList.remove('bg-gray-200');
            document.getElementById('blok_rumah').classList.remove('bg-gray-200');
        }
    }

    function bukaModalAnggota(no_kk, blok) {
        bukaModal('modal-form-warga', 'Tambah Anggota Keluarga', true);
        let inputKK = document.getElementById('nomor_kk');
        let inputBlok = document.getElementById('blok_rumah');

        inputKK.value = no_kk;
        inputKK.readOnly = true;
        inputKK.classList.add('bg-gray-200');

        inputBlok.value = blok;
        inputBlok.readOnly = true;
        inputBlok.classList.add('bg-gray-200');

        document.getElementById('status_keluarga').value = 'Anak';
    }

    function bukaModalEdit(id, kk, nik, nama, telepon, blok, status, domisili) {
        bukaModal('modal-form-warga', 'Edit Data Warga', false);
        document.getElementById('warga_id').value = id;
        document.getElementById('nomor_kk').value = kk;
        document.getElementById('nik').value = nik;
        document.getElementById('nama_lengkap').value = nama;
        document.getElementById('no_telepon').value = telepon;
        document.getElementById('blok_rumah').value = blok;
        document.getElementById('status_keluarga').value = status;
        document.getElementById('status_domisili').value = domisili;

        document.getElementById('nomor_kk').readOnly = false;
        document.getElementById('blok_rumah').readOnly = false;
        document.getElementById('nomor_kk').classList.remove('bg-gray-200');
        document.getElementById('blok_rumah').classList.remove('bg-gray-200');
    }

    function tutupModal(idModal) {
        document.getElementById(idModal).classList.add('hidden');
    }

    function simpanWarga(event) {
        event.preventDefault();
        let form = document.getElementById('formWarga');
        let formData = new FormData(form);
        let id = document.getElementById('warga_id').value;
        let url = id ? '/admin/warga/update' : '/admin/warga/store';

        let btn = document.getElementById('btn-submit');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success === true) {
                tutupModal('modal-form-warga');
                location.reload();
            } else {
                alert('Peringatan: ' + (data.message || 'Mohon periksa kembali input Anda.'));
                btn.innerHTML = 'Simpan Data';
                btn.disabled = false;
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan pada jaringan atau server.');
            btn.innerHTML = 'Simpan Data';
            btn.disabled = false;
        });
    }

    function hapusWarga(id) {
        if(!confirm('Apakah Anda yakin ingin menghapus data warga ini? Data yang dihapus tidak bisa dikembalikan.')) return;

        fetch('/admin/warga/delete/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus data.');
            }
        });
    }
</script>
