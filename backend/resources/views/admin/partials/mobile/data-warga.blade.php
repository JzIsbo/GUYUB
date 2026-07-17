@php
    $isAdmin = in_array(Auth::user()->role, ['Super Admin', 'RT']);
    $totalWarga = 0;
    $totalKK = 0;
    $totalTetap = 0;
    $totalKontrak = 0;
    if(isset($warga_grouped)) {
        $totalKK = $warga_grouped->count();
        foreach($warga_grouped as $anggota) {
            $totalWarga += $anggota->count();
            foreach($anggota as $w) {
                if($w->status_domisili == 'Tetap') $totalTetap++;
                else $totalKontrak++;
            }
        }
    }
    $cardsPerPage = 6;
@endphp

<div class="p-3 space-y-3">

    <!-- Hero Banner Compact -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden">
        <i class="fa-solid fa-users absolute -bottom-3 -right-2 text-[5rem] opacity-[0.04] rotate-12"></i>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-6 h-6 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-id-card-clip text-blue-300 text-[10px]"></i>
                </div>
                <span class="text-[8px] font-black uppercase tracking-[2px] text-blue-300/70">Kependudukan</span>
            </div>
            <h1 class="text-lg font-black tracking-tight">Data Warga RT</h1>
            <p class="text-[10px] text-white/40 font-medium mt-0.5">Database keluarga berbasis KK digital</p>

            <div class="flex items-center gap-2 mt-3">
                <div class="grid grid-cols-3 gap-2 flex-1">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-2 py-1.5 text-center">
                        <p class="text-sm font-black text-white leading-none">{{ $totalKK }}</p>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-blue-300/60 mt-0.5">Keluarga</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-2 py-1.5 text-center">
                        <p class="text-sm font-black text-white leading-none">{{ $totalWarga }}</p>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-blue-300/60 mt-0.5">Jiwa</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-2 py-1.5 text-center">
                        <p class="text-sm font-black text-emerald-400 leading-none">{{ $totalTetap }}</p>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-blue-300/60 mt-0.5">Tetap</p>
                    </div>
                </div>
            </div>

            @if($isAdmin)
            <button type="button" onclick="bukaModal('modal-form-warga', 'Tambah Warga / KK Baru', true)" class="mt-3 w-full bg-blue-500 hover:bg-blue-400 text-white font-bold px-4 py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer text-xs shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-user-plus text-[10px]"></i> Tambah Warga
            </button>
            @endif
        </div>
    </div>

    <!-- Search + Pagination -->
    <div class="space-y-2">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
            <input type="text" id="searchWarga" onkeyup="filterWarga()" placeholder="Cari nama, NIK, blok..." class="w-full bg-white border border-gray-100 pl-9 pr-4 py-2.5 rounded-xl font-medium text-gray-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
        </div>
        <div class="flex items-center justify-center gap-2 bg-white border border-gray-100 rounded-xl px-3 py-1.5 shadow-sm">
            <button onclick="prevPage()" class="w-7 h-7 rounded-lg bg-gray-50 hover:bg-blue-600 hover:text-white text-gray-400 transition inline-flex items-center justify-center cursor-pointer" id="btnPrev">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </button>
            <span class="text-xs font-bold text-gray-600 min-w-[60px] text-center" id="pageInfo">1 / 1</span>
            <button onclick="nextPage()" class="w-7 h-7 rounded-lg bg-gray-50 hover:bg-blue-600 hover:text-white text-gray-400 transition inline-flex items-center justify-center cursor-pointer" id="btnNext">
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </button>
        </div>
    </div>

    <!-- Family Cards -->
    <div id="familyCardsContainer" class="space-y-2.5">
        @forelse($warga_grouped ?? [] as $group_key => $anggota)
            @php
                $kepala = $anggota->firstWhere('status_keluarga', 'Kepala Keluarga') ?? $anggota->first();
                $current_kk = $anggota->first()->nomor_kk ?? 'belum-ada-kk';
                $current_blok = $anggota->first()->blok_rumah ?? 'Tanpa Blok';
                $jumlahAnggota = $anggota->count();
                $colors = ['blue', 'violet', 'emerald', 'amber', 'rose', 'cyan', 'indigo', 'teal'];
                $colorIdx = crc32($current_kk) % count($colors);
                $c = $colors[abs($colorIdx)];
            @endphp

            <div class="family-card bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 data-search="{{ strtolower($anggota->pluck('nama_lengkap')->join(' ') . ' ' . $current_kk . ' ' . $current_blok . ' ' . $anggota->pluck('nik')->join(' ')) }}">

                <!-- Header Strip -->
                <div class="bg-gradient-to-r from-{{ $c }}-600 to-{{ $c }}-700 px-3 py-2 text-white flex items-center justify-between cursor-pointer select-none" onclick="toggleFamilyCard('card-members-{{ $loop->index }}', this)">
                    <div class="flex items-center gap-2 min-w-0 flex-1">
                        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-house-chimney text-[10px]"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-xs font-black tracking-tight truncate">{{ $kepala->nama_lengkap }}</h3>
                            <p class="text-[8px] text-white/50 font-medium">{{ $current_blok }} · {{ $jumlahAnggota }} org</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @if($isAdmin)
                        <button type="button" onclick="event.stopPropagation(); bukaModalAnggota('{{ $current_kk }}', '{{ $current_blok }}')" class="w-6 h-6 rounded-md bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i>
                        </button>
                        @endif
                        <button type="button" class="w-6 h-6 rounded-md bg-white/10 text-white flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-[8px] transition-transform duration-300 card-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Members -->
                <div id="card-members-{{ $loop->index }}" class="divide-y divide-gray-50 transition-all duration-300 hidden">
                    @foreach($anggota as $index => $w)
                    @php
                        $avatarIcon = 'fa-user';
                        $avatarColor = 'gray';
                        $statusLabel = $w->status_keluarga;
                        if($w->status_keluarga == 'Kepala Keluarga') {
                            $avatarIcon = 'fa-user-tie'; $avatarColor = 'blue'; $statusLabel = 'Suami';
                        } elseif($w->status_keluarga == 'Istri') {
                            $avatarIcon = 'fa-user'; $avatarColor = 'pink'; $statusLabel = 'Istri';
                        } else {
                            $avatarIcon = 'fa-child'; $avatarColor = 'emerald'; $statusLabel = 'Anak';
                        }
                    @endphp
                    <div class="px-3 py-2 flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <div class="w-6 h-6 rounded-md bg-{{ $avatarColor }}-50 text-{{ $avatarColor }}-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $avatarIcon }} text-[8px]"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1 flex-wrap">
                                    <p class="font-bold text-gray-800 text-[11px] truncate max-w-[120px]">{{ $w->nama_lengkap }}</p>
                                    @if($w->status_keluarga == 'Kepala Keluarga')
                                        <span class="px-1 py-0.5 rounded text-[7px] font-black uppercase bg-blue-100 text-blue-700">{{ $statusLabel }}</span>
                                    @elseif($w->status_keluarga == 'Istri')
                                        <span class="px-1 py-0.5 rounded text-[7px] font-black uppercase bg-pink-100 text-pink-700">{{ $statusLabel }}</span>
                                    @else
                                        <span class="px-1 py-0.5 rounded text-[7px] font-black uppercase bg-emerald-100 text-emerald-700">{{ $statusLabel }}</span>
                                    @endif
                                    @if($w->umur >= 60)
                                        <span class="px-1 py-0.5 rounded text-[7px] font-black uppercase bg-amber-100 text-amber-700">Lansia</span>
                                    @endif
                                    <span class="px-1 py-0.5 rounded text-[7px] font-bold bg-indigo-50 text-indigo-600">{{ $w->jenis_kelamin }}</span>
                                </div>
                                <div class="flex items-center gap-1 flex-wrap">
                                    <p class="text-[8px] font-medium text-gray-400">{{ $w->nik }}</p>
                                    @if($w->umur)
                                    <span class="text-[7px] text-gray-300">·</span>
                                    <span class="text-[8px] text-gray-400">{{ $w->umur }} thn</span>
                                    @endif
                                    @if($w->agama)
                                    <span class="text-[7px] text-gray-300">·</span>
                                    <span class="text-[8px] text-gray-400">{{ $w->agama }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($isAdmin)
                        <div class="flex items-center gap-1 shrink-0 ml-1">
                            <button type="button" onclick="bukaModalEdit({{ $w->id }}, '{{ $w->nomor_kk }}', '{{ $w->nik }}', '{{ addslashes($w->nama_lengkap) }}', '{{ $w->jenis_kelamin }}', '{{ $w->umur }}', '{{ $w->agama }}', '{{ $w->no_telepon }}', '{{ $w->blok_rumah }}', '{{ $w->status_keluarga }}', '{{ $w->status_domisili }}')" class="w-6 h-6 rounded-md bg-blue-50 text-blue-500 inline-flex items-center justify-center cursor-pointer">
                                <i class="fa-solid fa-pen text-[8px]"></i>
                            </button>
                            <button type="button" onclick="hapusWarga({{ $w->id }})" class="w-6 h-6 rounded-md bg-red-50 text-red-500 inline-flex items-center justify-center cursor-pointer">
                                <i class="fa-solid fa-trash text-[8px]"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white p-6 rounded-xl border border-gray-50 shadow-sm text-center">
                <i class="fa-solid fa-folder-open text-gray-200 text-2xl mb-2"></i>
                <h3 class="text-sm font-black text-gray-800 mb-0.5">Belum Ada Warga</h3>
                <p class="text-xs text-gray-500">Sistem belum memiliki data warga.</p>
            </div>
        @endforelse
    </div>

    <!-- Bottom Pagination -->
    <div class="flex items-center justify-center gap-2 pt-1" id="bottomPagination">
        <button onclick="prevPage()" class="px-3 py-2 rounded-xl bg-white border border-gray-100 text-gray-500 font-bold text-[10px] transition flex items-center gap-1 cursor-pointer shadow-sm">
            <i class="fa-solid fa-arrow-left text-[8px]"></i> Prev
        </button>
        <span class="text-xs font-bold text-gray-500" id="pageInfoBottom">1 / 1</span>
        <button onclick="nextPage()" class="px-3 py-2 rounded-xl bg-white border border-gray-100 text-gray-500 font-bold text-[10px] transition flex items-center gap-1 cursor-pointer shadow-sm">
            Next <i class="fa-solid fa-arrow-right text-[8px]"></i>
        </button>
    </div>
</div>

<!-- Modal Form Warga -->
<div id="modal-form-warga" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-3 backdrop-blur-sm">
    <div class="bg-white p-5 rounded-2xl w-full max-w-[95vw] shadow-2xl max-h-[90vh] overflow-y-auto">
        <h3 id="modal-title" class="font-black text-base mb-4 text-gray-800">Form Warga</h3>

        <form id="formWarga" onsubmit="simpanWarga(event)">
            <input type="hidden" id="warga_id" name="id">

            <div class="space-y-3 mb-4">
                <div>
                    <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Nomor KK</label>
                    <input type="number" id="nomor_kk" name="nomor_kk" required class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">NIK</label>
                    <input type="number" id="nik" name="nik" required class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Umur (Tahun)</label>
                        <input type="number" id="umur" name="umur" min="0" max="150" placeholder="Cth: 35" class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Agama</label>
                        <select id="agama" name="agama" class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Blok Rumah</label>
                        <input type="text" id="blok_rumah" name="blok_rumah" required placeholder="Cth: A1" class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Status Keluarga</label>
                        <select id="status_keluarga" name="status_keluarga" required class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500">
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Domisili</label>
                        <select id="status_domisili" name="status_domisili" required class="w-full bg-gray-50 border border-gray-200 py-2 px-3 rounded-xl mt-1 font-bold text-gray-700 text-sm focus:outline-none focus:border-blue-500">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Kos">Kos</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="tutupModal('modal-form-warga')" class="flex-1 bg-gray-100 py-3 rounded-xl font-black text-gray-500 text-xs uppercase tracking-widest hover:bg-gray-200 transition">Batal</button>
                <button type="submit" id="btn-submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    var csrfToken = window.csrfToken || (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}');

    function toggleFamilyCard(id, headerEl) {
        var body = document.getElementById(id);
        if (!body) return;
        var isHidden = body.classList.toggle('hidden');
        var icon = headerEl.querySelector('.card-toggle-icon');
        if (icon) {
            if (isHidden) { icon.classList.remove('fa-chevron-up'); icon.classList.add('fa-chevron-down'); }
            else { icon.classList.remove('fa-chevron-down'); icon.classList.add('fa-chevron-up'); }
        }
    }

    var currentPage = 1;
    var cardsPerPage = {{ $cardsPerPage }};
    var allCards = [];
    var filteredCards = [];

    function initPagination() {
        allCards = Array.from(document.querySelectorAll('.family-card'));
        filteredCards = [...allCards];
        showPage(1);
    }

    function showPage(page) {
        var totalPages = Math.max(1, Math.ceil(filteredCards.length / cardsPerPage));
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;
        currentPage = page;
        allCards.forEach(c => c.style.display = 'none');
        var start = (page - 1) * cardsPerPage;
        filteredCards.slice(start, start + cardsPerPage).forEach(c => c.style.display = '');
        var info = page + ' / ' + totalPages;
        document.getElementById('pageInfo').textContent = info;
        document.getElementById('pageInfoBottom').textContent = info;
        if (page > 1) document.getElementById('familyCardsContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function nextPage() { showPage(currentPage + 1); }
    function prevPage() { showPage(currentPage - 1); }

    function filterWarga() {
        var query = document.getElementById('searchWarga').value.toLowerCase();
        filteredCards = query === '' ? [...allCards] : allCards.filter(card => card.getAttribute('data-search').includes(query));
        showPage(1);
    }

    document.addEventListener('DOMContentLoaded', initPagination);
    if (document.readyState === 'complete' || document.readyState === 'interactive') { setTimeout(initPagination, 100); }

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
        inputKK.value = no_kk; inputKK.readOnly = true; inputKK.classList.add('bg-gray-200');
        inputBlok.value = blok; inputBlok.readOnly = true; inputBlok.classList.add('bg-gray-200');
        document.getElementById('status_keluarga').value = 'Anak';
    }

    function bukaModalEdit(id, kk, nik, nama, jk, umur, agama, telepon, blok, status, domisili) {
        bukaModal('modal-form-warga', 'Edit Data Warga', false);
        document.getElementById('warga_id').value = id;
        document.getElementById('nomor_kk').value = kk;
        document.getElementById('nik').value = nik;
        document.getElementById('nama_lengkap').value = nama;
        document.getElementById('jenis_kelamin').value = jk || 'Laki-laki';
        document.getElementById('umur').value = umur || '';
        document.getElementById('agama').value = agama || 'Islam';
        document.getElementById('no_telepon').value = telepon;
        document.getElementById('blok_rumah').value = blok;
        document.getElementById('status_keluarga').value = status;
        document.getElementById('status_domisili').value = domisili;
        document.getElementById('nomor_kk').readOnly = false;
        document.getElementById('blok_rumah').readOnly = false;
        document.getElementById('nomor_kk').classList.remove('bg-gray-200');
        document.getElementById('blok_rumah').classList.remove('bg-gray-200');
    }

    function tutupModal(idModal) { document.getElementById(idModal).classList.add('hidden'); }

    function simpanWarga(event) {
        event.preventDefault();
        let form = document.getElementById('formWarga');
        let formData = new FormData(form);
        let id = document.getElementById('warga_id').value;
        let url = id ? '/admin/warga/update' : '/admin/warga/store';
        let btn = document.getElementById('btn-submit');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        btn.disabled = true;
        fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: formData })
        .then(response => response.json())
        .then(data => {
            if(data.success === true) { tutupModal('modal-form-warga'); location.reload(); }
            else { alert('Peringatan: ' + (data.message || 'Mohon periksa kembali input Anda.')); btn.innerHTML = 'Simpan'; btn.disabled = false; }
        })
        .catch(error => { alert('Terjadi kesalahan pada jaringan atau server.'); btn.innerHTML = 'Simpan'; btn.disabled = false; });
    }

    function hapusWarga(id) {
        const doDelete = () => {
            const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            fetch('/admin/warga/delete/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP error ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Data warga berhasil dihapus.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        alert(data.message || 'Data warga berhasil dihapus.');
                    }
                    
                    // Invalidate caches
                    if (typeof window.invalidatePageCache === 'function') {
                        window.invalidatePageCache('data-warga');
                        window.invalidatePageCache('data-keluarga');
                    }
                    
                    // Reload current page view in SPA
                    if (typeof switchPage === 'function') {
                        switchPage('data-warga', document.querySelector('.menu-active'));
                    } else {
                        location.reload();
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Gagal!', data.message || 'Gagal menghapus data.', 'error');
                    } else {
                        alert(data.message || 'Gagal menghapus data.');
                    }
                }
            })
            .catch(error => {
                console.error(error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error!', 'Terjadi kesalahan pada server atau jaringan.', 'error');
                } else {
                    alert('Terjadi kesalahan pada jaringan atau server.');
                }
            });
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Data Warga?',
                text: 'Data warga ini beserta seluruh rekam jejak iurannya akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl p-4 font-sans text-xs'
                }
            }).then(result => {
                if (result.isConfirmed) {
                    doDelete();
                }
            });
        } else {
            if (confirm('Yakin ingin menghapus data warga ini?')) {
                doDelete();
            }
        }
    }
</script>
