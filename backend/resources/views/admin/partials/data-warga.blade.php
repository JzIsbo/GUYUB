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
    $cardsPerPage = 8;
@endphp

<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Stats Card -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-cyan-500/10 rounded-full translate-y-1/2 -translate-x-1/4"></div>
        <i class="fa-solid fa-users absolute -bottom-6 -right-4 text-[120px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-id-card-clip text-blue-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-blue-300/70">Sistem Kependudukan</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Data Warga RT</h1>
                <p class="text-sm text-white/40 font-medium mt-1">Database keluarga berbasis Kartu Keluarga digital</p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-4 py-3 text-center min-w-[80px]">
                        <p class="text-2xl font-black text-white leading-none">{{ $totalKK }}</p>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-blue-300/60 mt-1">Keluarga</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-4 py-3 text-center min-w-[80px]">
                        <p class="text-2xl font-black text-white leading-none">{{ $totalWarga }}</p>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-blue-300/60 mt-1">Jiwa</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-4 py-3 text-center min-w-[80px]">
                        <p class="text-2xl font-black text-emerald-400 leading-none">{{ $totalTetap }}</p>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-blue-300/60 mt-1">Tetap</p>
                    </div>
                </div>

                @if($isAdmin)
                <button type="button" onclick="bukaModal('modal-form-warga', 'Tambah Warga / KK Baru', true)" class="bg-blue-500 hover:bg-blue-400 text-white font-bold px-5 py-3 rounded-2xl transition-all flex items-center gap-2 cursor-pointer text-sm shadow-lg shadow-blue-500/30 hover:-translate-y-0.5">
                    <i class="fa-solid fa-user-plus"></i> Tambah Warga
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Search + Pagination Controls -->
    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
            <input type="text" id="searchWarga" onkeyup="filterWarga()" placeholder="Cari berdasarkan nama, NIK, atau blok rumah..." class="w-full bg-white border border-gray-100 pl-12 pr-6 py-3.5 rounded-2xl font-medium text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-2xl px-4 py-2 shadow-sm shrink-0">
            <button onclick="prevPage()" class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-blue-600 hover:text-white text-gray-400 transition inline-flex items-center justify-center cursor-pointer" id="btnPrev">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
            <span class="text-sm font-bold text-gray-600 min-w-[100px] text-center" id="pageInfo">1 / 1</span>
            <button onclick="nextPage()" class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-blue-600 hover:text-white text-gray-400 transition inline-flex items-center justify-center cursor-pointer" id="btnNext">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Family Cards - SINGLE COLUMN for full width -->
    <div id="familyCardsContainer" class="space-y-4">
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

            <div class="family-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden"
                 data-search="{{ strtolower($anggota->pluck('nama_lengkap')->join(' ') . ' ' . $current_kk . ' ' . $current_blok . ' ' . $anggota->pluck('nik')->join(' ')) }}">

                <!-- Compact Header Strip -->
                <div class="bg-gradient-to-r from-{{ $c }}-600 to-{{ $c }}-700 px-5 py-3 text-white flex items-center justify-between cursor-pointer select-none" onclick="toggleFamilyCard('card-members-{{ $loop->index }}', this)">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/10">
                            <i class="fa-solid fa-house-chimney text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-black tracking-tight truncate">{{ $kepala->nama_lengkap }}</h3>
                            <p class="text-[10px] text-white/50 font-medium">{{ $current_blok }} · KK: {{ $current_kk }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="bg-white/10 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-white/10">{{ $jumlahAnggota }} Anggota</span>
                        @if($isAdmin)
                        <button type="button" onclick="event.stopPropagation(); bukaModalAnggota('{{ $current_kk }}', '{{ $current_blok }}')" class="bg-white/10 hover:bg-white/20 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition flex items-center gap-1 cursor-pointer border border-white/10">
                            <i class="fa-solid fa-plus text-[8px]"></i> Anggota
                        </button>
                        @endif
                        <button type="button" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition border border-white/10 focus:outline-none" title="Minimize / Maximize">
                            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300 card-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Members as compact rows -->
                <div id="card-members-{{ $loop->index }}" class="divide-y divide-gray-50 transition-all duration-300">
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
                    <div class="px-5 py-2.5 flex items-center justify-between hover:bg-gray-50/50 transition group/member">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-7 h-7 rounded-lg bg-{{ $avatarColor }}-50 text-{{ $avatarColor }}-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $avatarIcon }} text-[10px]"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-bold text-gray-800 text-sm truncate">{{ $w->nama_lengkap }}</p>
                                    @if($w->status_keluarga == 'Kepala Keluarga')
                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-blue-100 text-blue-700">{{ $statusLabel }}</span>
                                    @elseif($w->status_keluarga == 'Istri')
                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-pink-100 text-pink-700">{{ $statusLabel }}</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700">{{ $statusLabel }}</span>
                                    @endif
                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-bold {{ $w->status_domisili == 'Tetap' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">{{ $w->status_domisili }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-medium text-gray-400">{{ $w->nik }}</span>
                                    @if($w->no_telepon)
                                    <span class="text-[10px] font-medium text-gray-300">·</span>
                                    <span class="text-[10px] font-medium text-gray-400"><i class="fa-solid fa-phone text-[7px]"></i> {{ $w->no_telepon }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($isAdmin)
                        <div class="flex items-center gap-1 opacity-0 group-hover/member:opacity-100 transition shrink-0 ml-2">
                            <button type="button" onclick="bukaModalEdit({{ $w->id }}, '{{ $w->nomor_kk }}', '{{ $w->nik }}', '{{ addslashes($w->nama_lengkap) }}', '{{ $w->no_telepon }}', '{{ $w->blok_rumah }}', '{{ $w->status_keluarga }}', '{{ $w->status_domisili }}')" class="w-6 h-6 rounded-md bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer">
                                <i class="fa-solid fa-pen text-[9px]"></i>
                            </button>
                            <button type="button" onclick="hapusWarga({{ $w->id }})" class="w-6 h-6 rounded-md bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer">
                                <i class="fa-solid fa-trash text-[9px]"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white p-12 rounded-[2rem] border border-gray-50 shadow-sm text-center">
                <i class="fa-solid fa-folder-open text-gray-200 text-5xl mb-3"></i>
                <h3 class="text-lg font-black text-gray-800 tracking-tight mb-1">Belum Ada Warga</h3>
                <p class="text-sm text-gray-500 font-medium">Sistem belum memiliki data warga.</p>
            </div>
        @endforelse
    </div>

    <!-- Bottom Pagination -->
    <div class="flex items-center justify-center gap-3 pt-2" id="bottomPagination">
        <button onclick="prevPage()" class="px-4 py-2.5 rounded-xl bg-white border border-gray-100 hover:bg-blue-600 hover:text-white hover:border-blue-600 text-gray-500 font-bold text-xs transition flex items-center gap-2 cursor-pointer shadow-sm">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Sebelumnya
        </button>
        <span class="text-sm font-bold text-gray-500" id="pageInfoBottom">1 / 1</span>
        <button onclick="nextPage()" class="px-4 py-2.5 rounded-xl bg-white border border-gray-100 hover:bg-blue-600 hover:text-white hover:border-blue-600 text-gray-500 font-bold text-xs transition flex items-center gap-2 cursor-pointer shadow-sm">
            Selanjutnya <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </button>
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
                    <input type="text" id="blok_rumah" name="blok_rumah" required placeholder="Cth: A1" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Keluarga</label>
                    <select id="status_keluarga" name="status_keluarga" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                        <option value="Kepala Keluarga">Kepala Keluarga (Suami)</option>
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

    // ========== TOGGLE MINIMIZE / MAXIMIZE FAMILY CARD ==========
    function toggleFamilyCard(id, headerEl) {
        var body = document.getElementById(id);
        if (!body) return;
        var isHidden = body.classList.toggle('hidden');
        var icon = headerEl.querySelector('.card-toggle-icon');
        if (icon) {
            if (isHidden) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        }
    }

    // ========== PAGINATION ==========
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

        // Hide all
        allCards.forEach(c => c.style.display = 'none');

        // Show current page
        var start = (page - 1) * cardsPerPage;
        var end = start + cardsPerPage;
        filteredCards.slice(start, end).forEach(c => c.style.display = '');

        // Update page info
        var info = page + ' / ' + totalPages;
        document.getElementById('pageInfo').textContent = info;
        document.getElementById('pageInfoBottom').textContent = 'Halaman ' + info;

        // Scroll to top of cards
        if (page > 1) {
            document.getElementById('familyCardsContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function nextPage() { showPage(currentPage + 1); }
    function prevPage() { showPage(currentPage - 1); }

    // ========== SEARCH ==========
    function filterWarga() {
        var query = document.getElementById('searchWarga').value.toLowerCase();
        if (query === '') {
            filteredCards = [...allCards];
        } else {
            filteredCards = allCards.filter(card => card.getAttribute('data-search').includes(query));
        }
        showPage(1);
    }

    // Init on load
    document.addEventListener('DOMContentLoaded', initPagination);
    // Also init when AJAX loads the partial
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initPagination, 100);
    }

    // ========== MODAL FUNCTIONS ==========
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
