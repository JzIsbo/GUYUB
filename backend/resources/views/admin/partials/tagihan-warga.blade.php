@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    $user           = Auth::user();
    $isAdmin        = in_array($user->role, ['Super Admin', 'RT', 'Bendahara']);
    $qris           = $qris_info ?? DB::table('qris_settings')->first();
    $gateway        = $gateway_info ?? DB::table('payment_gateways')->first();
    $isMidtransOn   = $gateway && !empty($gateway->server_key) && !empty($gateway->client_key);

    $statTotal = $stat_total ?? count($tagihans);
    $statLunas = $stat_lunas ?? collect($tagihans)->where('status','lunas')->count();
    $statVerif = $stat_verif ?? collect($tagihans)->where('status','menunggu_verifikasi')->count();
    $statBelum = $stat_belum ?? collect($tagihans)->where('status','belum_bayar')->count();

    $totalNominalBelum = collect($tagihans)->where('status','belum_bayar')->sum('jumlah');
    $totalNominalLunas = collect($tagihans)->where('status','lunas')->sum('jumlah');

    // Generate automatic periods: 12 months back, current month, 12 months forward
    $currentDate = \Carbon\Carbon::now();
    $periods = [];
    for ($i = -12; $i <= 12; $i++) {
        $date = (clone $currentDate)->addMonths($i);
        $periods[] = $date->translatedFormat('F Y');
    }
@endphp

<div class="p-6 space-y-6">

    {{-- ══ HEADER ══ --}}
    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-white">Tagihan & Iuran Warga</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola tagihan, verifikasi pembayaran, dan pantau status iuran RT</p>
        </div>
        @if($isAdmin)
        <div class="flex gap-2.5 flex-wrap">
            <button onclick="window.document.getElementById('modal-generate-massal').classList.remove('hidden')"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition shadow flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-bolt"></i> Generate Massal
            </button>
            <button onclick="window.document.getElementById('modal-tambah-tagihan').classList.remove('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition shadow flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Buat Tagihan
            </button>
        </div>
        @else
        <div class="flex gap-2.5 flex-wrap">
            <button onclick="window.bukaModalBayarWargaBaru()"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-extrabold text-sm transition shadow-lg flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-wallet text-base"></i> Bayar Iuran / Tagihan
            </button>
        </div>
        @endif
    </div>

    {{-- ══ STAT CARDS ══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900/40 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center">
                <i class="fa-solid fa-file-invoice text-blue-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Total Tagihan</p>
                <p class="text-xl font-black text-gray-800 dark:text-white">{{ $statTotal }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900/40 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-950/40 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-green-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Lunas</p>
                <p class="text-xl font-black text-green-600 dark:text-green-400">{{ $statLunas }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900/40 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center">
                <i class="fa-solid fa-clock text-amber-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Verifikasi</p>
                <p class="text-xl font-black text-amber-600 dark:text-amber-400">{{ $statVerif }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900/40 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Belum Bayar</p>
                <p class="text-xl font-black text-red-600 dark:text-red-400">{{ $statBelum }}</p>
            </div>
        </div>
    </div>

    {{-- ══ FILTER BAR ══ --}}
    <div class="flex flex-wrap gap-2 items-center">
        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Filter:</span>
        <button onclick="filterTagihan('semua')" id="filter-semua"
                class="filter-btn active px-3.5 py-1.5 rounded-xl text-xs font-bold border transition cursor-pointer">Semua ({{ $statTotal }})</button>
        <button onclick="filterTagihan('belum_bayar')" id="filter-belum_bayar"
                class="filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition cursor-pointer">Belum Bayar ({{ $statBelum }})</button>
        <button onclick="filterTagihan('menunggu_verifikasi')" id="filter-menunggu_verifikasi"
                class="filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition cursor-pointer">Menunggu Verifikasi ({{ $statVerif }})</button>
        <button onclick="filterTagihan('lunas')" id="filter-lunas"
                class="filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition cursor-pointer">Lunas ({{ $statLunas }})</button>
        <div class="ml-auto">
            <input type="text" id="search-tagihan" onkeyup="searchTagihan()" placeholder="🔍 Cari nama / jenis..." 
                   class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 py-1.5 px-3 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
        </div>
    </div>

    {{-- ══ TABEL TAGIHAN ══ --}}
    <div class="bg-white dark:bg-slate-900/40 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-slate-800/60 overflow-hidden">
        <table class="w-full text-left border-collapse" id="tabel-tagihan">
            <thead class="bg-gray-50 dark:bg-slate-800/40 text-gray-500 dark:text-gray-400 text-[11px] uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-4">ID</th>
                    <th class="px-5 py-4">Warga</th>
                    <th class="px-5 py-4">Jenis & Periode</th>
                    <th class="px-5 py-4">Jumlah</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-slate-800/60 text-sm" id="tbody-tagihan">
                @forelse($tagihans as $item)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/20 transition tagihan-row" data-status="{{ $item->status }}">
                    <td class="px-5 py-4 font-mono text-gray-400 dark:text-gray-500 text-xs">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-4">
                        <span class="font-bold text-gray-800 dark:text-white block">{{ $item->nama_warga }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-gray-700 dark:text-gray-300 font-semibold">{{ $item->jenis_tagihan }}</span>
                        @if($item->periode)
                        <span class="block text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $item->periode }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 font-bold text-gray-800 dark:text-white">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="px-5 py-4">
                        @if($item->status === 'lunas')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-900">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> LUNAS
                            </span>
                        @elseif($item->status === 'menunggu_verifikasi')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> VERIFIKASI
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> BELUM BAYAR
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex gap-1.5 justify-center flex-wrap">
                            {{-- Warga: tombol bayar jika belum bayar --}}
                            @if(!$isAdmin && $item->status === 'belum_bayar')
                            <button onclick="window.bukaModalBayar({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', {{ $item->jumlah }}, '{{ $item->periode }}', '{{ $item->catatan }}')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3 py-1.5 rounded-lg transition cursor-pointer">
                                <i class="fa-solid fa-wallet mr-1"></i>Bayar
                            </button>
                            @endif
                            {{-- Warga: tagihan sudah diupload, tunggu verifikasi --}}
                            @if(!$isAdmin && $item->status === 'menunggu_verifikasi')
                            <span class="text-amber-600 text-xs font-semibold bg-amber-50 px-3 py-1.5 rounded-lg">⏳ Menunggu</span>
                            @endif
                            {{-- Admin: tombol verifikasi kalau ada bukti --}}
                            @if($isAdmin && $item->status === 'menunggu_verifikasi')
                            <button onclick="window.bukaModalVerifikasi({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', {{ $item->jumlah }}, '{{ $item->bukti_bayar }}', '{{ $item->metode_bayar }}')"
                                    class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs px-3 py-1.5 rounded-lg transition cursor-pointer">
                                <i class="fa-solid fa-check-to-slot mr-1"></i>Verifikasi
                            </button>
                            @endif
                            {{-- Admin: edit & detail --}}
                            @if($isAdmin)
                            <button onclick="window.bukaModalEdit({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', '{{ addslashes($item->periode ?? '') }}', {{ $item->jumlah }}, '{{ $item->status }}', '{{ $item->batas_bayar }}', '{{ addslashes($item->catatan ?? '') }}')"
                                    class="text-blue-600 dark:text-blue-400 font-bold hover:underline text-xs px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/30 transition cursor-pointer">
                                Edit
                            </button>
                            <button onclick="window.hapusTagihan({{ $item->id }}, '{{ addslashes($item->nama_warga) }}')"
                                    class="text-red-500 dark:text-red-400 font-bold hover:underline text-xs px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-950/30 transition cursor-pointer">
                                Hapus
                            </button>
                            @endif
                            {{-- Detail bukti (semua role kalau lunas/verifikasi) --}}
                            @if($item->status !== 'belum_bayar' && $item->bukti_bayar)
                            <button onclick="window.lihatBukti('{{ $item->bukti_bayar }}', '{{ addslashes($item->nama_warga) }}')"
                                    class="text-gray-500 dark:text-gray-400 font-bold text-xs px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-slate-800 transition cursor-pointer">
                                <i class="fa-solid fa-image"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="empty-tagihan">
                    <td colspan="7" class="p-12 text-center text-gray-400 dark:text-gray-500 font-medium">
                        <i class="fa-solid fa-file-invoice text-3xl mb-3 opacity-30 block"></i>
                        Belum ada data tagihan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL: BUAT TAGIHAN (Admin)
══════════════════════════════════ --}}
<div id="modal-tambah-tagihan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100 dark:border-slate-800">
        <button onclick="document.getElementById('modal-tambah-tagihan').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 dark:text-white mb-1">Buat Tagihan Baru</h3>
        <p class="text-xs text-gray-400 dark:text-gray-500 mb-6">Tagihan untuk satu warga tertentu</p>
        <form id="form-tambah-tagihan" onsubmit="simpanTagihan(event)">
            @csrf
            <div class="space-y-4">
                {{-- Nama Warga --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Nama Warga</label>
                    <input type="hidden" name="nama_warga" id="tagihan_warga_hidden">
                    <div class="relative">
                        <input type="text" id="tagihan_warga_search" placeholder="🔍 Ketik untuk cari warga..."
                               onfocus="showDropdown('tagihan_warga_dropdown')"
                               onkeyup="filterCustomDropdown('tagihan_warga_search','tagihan_warga_dropdown')"
                               autocomplete="off"
                               class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-semibold text-gray-700 dark:text-gray-200 py-3 px-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        <div id="tagihan_warga_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 max-h-52 overflow-y-auto divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($all_warga ?? [] as $w)
                            <div onclick="selectTagihanWarga('{{ addslashes($w->nama_lengkap) }}')"
                                 class="dropdown-item px-4 py-2.5 hover:bg-blue-50 dark:hover:bg-blue-950/30 cursor-pointer transition flex items-center justify-between text-sm">
                                <div>
                                    <span class="block font-bold text-gray-800 dark:text-gray-100">{{ $w->nama_lengkap }}</span>
                                    <span class="text-[10px] text-gray-400">Blok {{ $w->blok_rumah }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- Jenis & Periode --}}
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Jenis Iuran</label>
                        <select name="jenis_tagihan" id="tagihan_jenis_select" onchange="autoFillNominal()" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($contributions ?? [] as $c)
                            <option value="{{ $c->nama_iuran }}" data-nominal="{{ $c->nominal }}">{{ $c->nama_iuran }}</option>
                            @endforeach
                            <option value="Lainnya">Lainnya (Manual)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Periode Bulan</label>
                        <select name="periode" id="tagihan_periode" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ $p == $currentDate->translatedFormat('F Y') ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" id="tagihan_jumlah" placeholder="25000" min="1000" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-tagihan').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 cursor-pointer text-sm">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow transition text-sm cursor-pointer">
                    <i class="fa-solid fa-plus mr-1.5"></i>Buat Tagihan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL: GENERATE MASSAL (Admin)
══════════════════════════════════ --}}
<div id="modal-generate-massal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100 dark:border-slate-800">
        <button onclick="document.getElementById('modal-generate-massal').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <i class="fa-solid fa-bolt text-emerald-600"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-gray-800 dark:text-white">Generate Tagihan Massal</h3>
                <p class="text-xs text-gray-400">Untuk semua warga sekaligus</p>
            </div>
        </div>
        <p class="text-xs text-amber-600 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-xl px-3 py-2 mb-6 mt-4">
            ⚡ Warga yang sudah memiliki tagihan aktif (belum bayar / verifikasi) untuk jenis dan periode yang sama akan dilewati otomatis.
        </p>
        <form id="form-generate-massal" onsubmit="generateTagihanMassal(event)">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Jenis Iuran</label>
                        <select name="jenis_tagihan" id="massal_jenis" onchange="autoFillMassalNominal()" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($contributions ?? [] as $c)
                            <option value="{{ $c->nama_iuran }}" data-nominal="{{ $c->nominal }}">{{ $c->nama_iuran }}</option>
                            @endforeach
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Periode Bulan</label>
                        <select name="periode" id="massal_periode" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ $p == $currentDate->translatedFormat('F Y') ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" id="massal_jumlah" placeholder="25000" min="1000" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-generate-massal').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 cursor-pointer text-sm">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow transition text-sm cursor-pointer">
                    <i class="fa-solid fa-bolt mr-1.5"></i>Generate Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL: EDIT TAGIHAN (Admin)
══════════════════════════════════ --}}
<div id="modal-edit-tagihan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100 dark:border-slate-800">
        <button onclick="document.getElementById('modal-edit-tagihan').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 dark:text-white mb-6">Edit Tagihan</h3>
        <form id="form-edit-tagihan" onsubmit="updateTagihan(event)">
            @csrf
            <input type="hidden" name="id" id="edit_tagihan_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Nama Warga</label>
                    <input type="text" name="nama_warga" id="edit_nama_warga" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                {{-- Jenis & Periode --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Jenis Tagihan</label>
                        <input type="text" name="jenis_tagihan" id="edit_jenis" required
                               class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Periode Bulan</label>
                        <select name="periode" id="edit_periode" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Jumlah --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" id="edit_jumlah" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                {{-- Hidden Jatuh Tempo (keep ID for JS compatibility) --}}
                <input type="hidden" name="batas_bayar" id="edit_batas">
                {{-- Status & Catatan --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Status</label>
                        <select name="status" id="edit_status" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Catatan</label>
                        <input type="text" name="catatan" id="edit_catatan" placeholder="Opsional..."
                               class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-semibold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-edit-tagihan').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 cursor-pointer text-sm">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow transition text-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL: BAYAR (Warga)
══════════════════════════════════ --}}
<div id="modal-bayar" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100 dark:border-slate-800 max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-bayar').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 dark:text-white mb-1">Bayar Tagihan</h3>
        <div id="bayar-tagihan-info" class="text-xs text-gray-500 dark:text-gray-400 mb-5"></div>

        {{-- Catatan dari admin (jika ada) --}}
        <div id="bayar-catatan-admin" class="hidden text-xs text-red-600 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 rounded-xl px-3 py-2 mb-4"></div>

        {{-- Form upload bukti --}}
        <div id="form-upload-bukti">
            {{-- Info rekening / QRIS --}}
            @if($qris)
            <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900 rounded-xl p-4 mb-4 text-xs">
                <p class="font-bold text-blue-700 dark:text-blue-300 mb-2">📋 Rekening & QRIS Tujuan Transfer</p>
                @if($qris->bank_1_name)
                <p class="text-gray-600 dark:text-gray-300">🏦 <strong>{{ $qris->bank_1_name }}</strong>: {{ $qris->bank_1_number }} a/n {{ $qris->bank_1_owner }}</p>
                @endif
                @if($qris->bank_2_name)
                <p class="text-gray-600 dark:text-gray-300 mt-1">🏦 <strong>{{ $qris->bank_2_name }}</strong>: {{ $qris->bank_2_number }} a/n {{ $qris->bank_2_owner }}</p>
                @endif
                <div class="mt-3 pt-3 border-t border-blue-100 dark:border-blue-900/40 text-center">
                    <p class="font-bold text-blue-700 dark:text-blue-300 mb-1.5">📱 QRIS KAS RT</p>
                    <img id="modal-qris-img-tagihan" src="{{ $qris->qris_image ? asset($qris->qris_image) : 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode($qris->qris_data ?? 'KOSONG') }}" alt="QRIS" class="w-32 h-32 object-contain mx-auto my-2 rounded-xl border border-gray-200 p-1 bg-white">
                    <a href="javascript:void(0)" onclick="downloadQRISFromModal('modal-qris-img-tagihan')" class="text-blue-600 hover:text-blue-700 font-bold text-xs inline-flex items-center gap-1.5 bg-blue-100/50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition mt-1">
                        <i class="fa-solid fa-download"></i> Unduh QRIS
                    </a>
                </div>
            </div>
            @endif
            <form id="form-upload-transfer" onsubmit="uploadBuktiBayar(event)">
                @csrf
                <input type="hidden" name="tagihan_id" id="upload_tagihan_id">
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Bukti Pembayaran (Foto)</label>
                    <input type="file" name="bukti_bayar" id="input_bukti_bayar" required accept="image/*"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 py-2.5 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maks 3MB)</p>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Catatan (Opsional)</label>
                    <input type="text" name="catatan" placeholder="cth: Transfer via BCA tanggal 15 Juli"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-medium text-gray-700 dark:text-gray-200 py-2.5 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow cursor-pointer">
                    <i class="fa-solid fa-upload mr-2"></i>Upload Bukti Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL: VERIFIKASI BUKTI (Admin)
══════════════════════════════════ --}}
<div id="modal-verifikasi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100 dark:border-slate-800">
        <button onclick="document.getElementById('modal-verifikasi').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 dark:text-white mb-1">Verifikasi Pembayaran</h3>
        <p id="verif-info" class="text-xs text-gray-400 dark:text-gray-500 mb-5"></p>

        {{-- Preview bukti bayar --}}
        <div id="verif-bukti-wrap" class="mb-5">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Bukti Pembayaran</p>
            <img id="verif-bukti-img" src="" alt="Bukti Bayar"
                 class="w-full max-h-64 object-contain rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 cursor-pointer"
                 onclick="window.open(this.src,'_blank')">
            <p class="text-[10px] text-gray-400 text-center mt-1">Klik gambar untuk buka di tab baru</p>
        </div>

        <form id="form-verifikasi" onsubmit="verifikasiTagihanSubmit(event)">
            @csrf
            <input type="hidden" name="id" id="verif_tagihan_id">
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Catatan (opsional)</label>
                <input type="text" name="catatan" id="verif_catatan" placeholder="cth: Bukti valid, pembayaran disetujui"
                       class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-medium text-gray-700 dark:text-gray-200 py-2.5 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="submitVerifikasi('tolak')"
                        class="flex-1 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 text-red-600 dark:text-red-400 font-bold py-3 rounded-xl transition cursor-pointer border border-red-200 dark:border-red-900">
                    <i class="fa-solid fa-xmark mr-2"></i>Tolak
                </button>
                <button type="button" onclick="submitVerifikasi('setujui')"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow transition cursor-pointer">
                    <i class="fa-solid fa-check mr-2"></i>Setujui & Catat ke Kas RT
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL: LIHAT BUKTI
══════════════════════════════════ --}}
<div id="modal-lihat-bukti" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center backdrop-blur-sm p-4" onclick="document.getElementById('modal-lihat-bukti').classList.add('hidden')">
    <div class="relative max-w-2xl w-full" onclick="event.stopPropagation()">
        <button onclick="document.getElementById('modal-lihat-bukti').classList.add('hidden')"
                class="absolute -top-10 right-0 text-white/70 hover:text-white cursor-pointer">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <p id="bukti-nama-label" class="text-white/80 text-xs text-center mb-2"></p>
        <img id="modal-bukti-img" src="" alt="Bukti Bayar" class="w-full rounded-2xl">
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL: BAYAR IURAN LANGSUNG (Warga)
══════════════════════════════════ --}}
<div id="modal-bayar-warga-proaktif" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100 dark:border-slate-800 max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-bayar-warga-proaktif').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/40 flex items-center justify-center">
                <i class="fa-solid fa-wallet text-blue-600 dark:text-blue-400 text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-gray-800 dark:text-white">Bayar Iuran RT</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Pilih iuran dan kirim bukti transfer</p>
            </div>
        </div>

        <form id="form-bayar-proaktif" onsubmit="window.prosesBayarProaktif(event)">
            @csrf
            <input type="hidden" name="nama_warga" value="{{ Auth::user()->name }}">
            
            <div class="space-y-4 mt-5">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Pilih Jenis Iuran</label>
                    <select name="jenis_tagihan" id="proaktif_jenis" onchange="window.autoFillProaktifNominal()" required
                            class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">-- Pilih Jenis Iuran --</option>
                        @foreach($contributions ?? [] as $c)
                        <option value="{{ $c->nama_iuran }}" data-nominal="{{ $c->nominal }}">{{ $c->nama_iuran }} (Rp {{ number_format($c->nominal, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Periode Bulan/Thn</label>
                        <select name="periode" id="proaktif_periode" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ $p == $currentDate->translatedFormat('F Y') ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Nominal (Rp)</label>
                        <input type="number" name="jumlah" id="proaktif_jumlah" placeholder="25000" min="1000" required
                               class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                @if($qris)
                <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900 rounded-xl p-3 text-xs">
                    <p class="font-bold text-blue-700 dark:text-blue-300 mb-1">📋 Rekening & QRIS Tujuan Transfer</p>
                    @if($qris->bank_1_name)<p class="text-gray-600 dark:text-gray-300">🏦 <strong>{{ $qris->bank_1_name }}</strong>: {{ $qris->bank_1_number }} a/n {{ $qris->bank_1_owner }}</p>@endif
                    @if($qris->bank_2_name)<p class="text-gray-600 dark:text-gray-300 mt-1">🏦 <strong>{{ $qris->bank_2_name }}</strong>: {{ $qris->bank_2_number }} a/n {{ $qris->bank_2_owner }}</p>@endif
                    <div class="mt-2.5 pt-2.5 border-t border-blue-100 dark:border-blue-900/40 text-center">
                        <p class="font-bold text-blue-700 dark:text-blue-300 mb-1">📱 QRIS KAS RT</p>
                        <img id="modal-qris-img-iuran" src="{{ $qris->qris_image ? asset($qris->qris_image) : 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode($qris->qris_data ?? 'KOSONG') }}" alt="QRIS" class="w-32 h-32 object-contain mx-auto my-1.5 rounded-xl border border-gray-200 p-1 bg-white">
                        <a href="javascript:void(0)" onclick="downloadQRISFromModal('modal-qris-img-iuran')" class="text-blue-600 hover:text-blue-700 font-bold text-xs inline-flex items-center gap-1.5 bg-blue-100/50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition mt-0.5">
                            <i class="fa-solid fa-download"></i> Unduh QRIS
                        </a>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Upload Bukti Transfer (Foto)</label>
                    <input type="file" name="bukti_bayar" id="proaktif_bukti" required accept="image/*"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 py-2.5 px-4 rounded-xl text-xs file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 cursor-pointer">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Catatan (Opsional)</label>
                    <input type="text" name="catatan" placeholder="cth: Transfer via BCA"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-medium text-gray-700 dark:text-gray-200 py-2 px-3 rounded-xl text-sm">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-bayar-warga-proaktif').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 cursor-pointer text-sm">Batal</button>
                <button type="submit" id="btn-proaktif-submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow transition text-sm cursor-pointer">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i>Kirim Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ STYLES ══ --}}
<style>
.filter-btn { background: white; color: #6b7280; border-color: #e5e7eb; }
.dark .filter-btn { background: transparent; color: #9ca3af; border-color: #374151; }
.filter-btn.active { background: #2563eb; color: white; border-color: #2563eb; }
.metode-btn.selected { border-color: #2563eb !important; background: #eff6ff; }
.dark .metode-btn.selected { background: rgba(37,99,235,0.1); }
</style>

{{-- ══ SCRIPTS ══ --}}
<script>
window.showNotif = function(type, msg) {
    if (typeof showToast === 'function') {
        showToast(type, msg);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type === 'error' ? 'error' : (type === 'success' ? 'success' : 'info'),
            title: msg,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        alert((type === 'success' ? '✅ ' : (type === 'error' ? '❌ ' : 'ℹ️ ')) + msg);
    }
};

window.currentFilter = 'semua';
window.currentTagihanId = null;
window.currentMetode = null;

window.filterTagihan = function(status) {
    window.currentFilter = status;
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('filter-' + status)?.classList.add('active');

    document.querySelectorAll('.tagihan-row').forEach(row => {
        const rowStatus = row.dataset.status;
        const visible = status === 'semua' || rowStatus === status;
        row.style.display = visible ? '' : 'none';
    });
    window.updateEmptyState();
};

window.searchTagihan = function() {
    const q = document.getElementById('search-tagihan').value.toLowerCase();
    document.querySelectorAll('.tagihan-row').forEach(row => {
        const text = row.innerText.toLowerCase();
        const statusOk = window.currentFilter === 'semua' || row.dataset.status === window.currentFilter;
        row.style.display = (text.includes(q) && statusOk) ? '' : 'none';
    });
    window.updateEmptyState();
};

window.updateEmptyState = function() {
    const visible = Array.from(document.querySelectorAll('.tagihan-row')).some(r => r.style.display !== 'none');
    const emptyRow = document.getElementById('empty-tagihan');
    if (emptyRow) emptyRow.style.display = visible ? 'none' : '';
};

window.autoFillNominal = function() {
    const sel = document.getElementById('tagihan_jenis_select');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.nominal) document.getElementById('tagihan_jumlah').value = opt.dataset.nominal;
};

window.autoFillMassalNominal = function() {
    const sel = document.getElementById('massal_jenis');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.nominal) document.getElementById('massal_jumlah').value = opt.dataset.nominal;
};

window.selectTagihanWarga = function(nama) {
    document.getElementById('tagihan_warga_hidden').value = nama;
    document.getElementById('tagihan_warga_search').value = nama;
    document.getElementById('tagihan_warga_dropdown').classList.add('hidden');
};

window.simpanTagihan = function(e) {
    e.preventDefault();
    const nama = document.getElementById('tagihan_warga_hidden').value;
    if (!nama) { window.showNotif('error', 'Pilih nama warga terlebih dahulu!'); return; }
    const fd = new FormData(document.getElementById('form-tambah-tagihan'));
    if (!fd.get('nama_warga')) fd.set('nama_warga', nama);

    window.fetchPost('/tagihan/store', fd, () => {
        document.getElementById('modal-tambah-tagihan').classList.add('hidden');
        document.getElementById('form-tambah-tagihan').reset();
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.generateTagihanMassal = function(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-generate-massal'));
    window.fetchPost('/tagihan/generate-massal', fd, () => {
        document.getElementById('modal-generate-massal').classList.add('hidden');
        document.getElementById('form-generate-massal').reset();
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.bukaModalEdit = function(id, namaWarga, jenis, periode, jumlah, status, batas, catatan) {
    document.getElementById('edit_tagihan_id').value  = id;
    document.getElementById('edit_nama_warga').value  = namaWarga;
    document.getElementById('edit_jenis').value       = jenis;
    document.getElementById('edit_periode').value     = periode || '';
    document.getElementById('edit_jumlah').value      = jumlah;
    document.getElementById('edit_status').value      = status;
    document.getElementById('edit_batas').value       = batas || '';
    document.getElementById('edit_catatan').value     = catatan || '';
    document.getElementById('modal-edit-tagihan').classList.remove('hidden');
};

window.updateTagihan = function(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-edit-tagihan'));
    window.fetchPost('/tagihan/update', fd, () => {
        document.getElementById('modal-edit-tagihan').classList.add('hidden');
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.hapusTagihan = function(id, nama) {
    if (!confirm(`Yakin hapus tagihan milik "${nama}"? Tindakan ini tidak dapat dibatalkan.`)) return;
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
    window.fetchPost('/tagihan/delete', { id }, () => switchPage('tagihan-warga'));
};

window.bukaModalBayar = function(id, nama, jenis, jumlah, periode, catatan) {
    window.currentTagihanId = id;
    window.currentMetode = null;

    const elId = document.getElementById('upload_tagihan_id');
    if (elId) elId.value = id;

    const elInfo = document.getElementById('bayar-tagihan-info');
    if (elInfo) {
        elInfo.innerHTML =
            `<strong class="text-gray-700 dark:text-gray-200">${jenis}</strong>${periode ? ' — ' + periode : ''} ` +
            `<span class="font-bold text-blue-600 ml-2">Rp ${Number(jumlah).toLocaleString('id-ID')}</span>`;
    }

    const catatanDiv = document.getElementById('bayar-catatan-admin');
    if (catatanDiv) {
        if (catatan && catatan.trim()) {
            catatanDiv.classList.remove('hidden');
            catatanDiv.innerHTML = '⚠️ Catatan pengurus: ' + catatan;
        } else {
            catatanDiv.classList.add('hidden');
        }
    }

    document.getElementById('modal-bayar').classList.remove('hidden');
    window.pilihMetode('transfer');
};

window.pilihMetode = function(metode) {
    window.currentMetode = metode;
    document.querySelectorAll('.metode-btn').forEach(b => b.classList.remove('selected'));
    document.getElementById('btn-metode-' + metode)?.classList.add('selected');

    if (metode === 'transfer') {
        document.getElementById('form-upload-bukti').classList.remove('hidden');
    } else if (metode === 'midtrans') {
        document.getElementById('form-upload-bukti').classList.add('hidden');
        window.bayarMidtrans();
    }
};

window.uploadBuktiBayar = function(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-upload-transfer'));
    const btn = e.submitter;
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Mengupload...'; }

    fetch('/tagihan/bayar-manual', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' },
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            window.showNotif('success', res.message);
            document.getElementById('modal-bayar').classList.add('hidden');
            document.getElementById('form-upload-transfer').reset();
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
            switchPage('tagihan-warga');
        } else {
            window.showNotif('error', res.message || 'Gagal upload bukti.');
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-upload mr-2"></i>Upload Bukti Pembayaran'; }
        }
    })
    .catch(() => {
        window.showNotif('error', 'Terjadi kesalahan jaringan.');
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-upload mr-2"></i>Upload Bukti Pembayaran'; }
    });
};

window.bayarMidtrans = function() {
    if (!window.currentTagihanId) return;
    window.showNotif('info', 'Membuka halaman pembayaran online...');
    fetch('/tagihan/bayar-midtrans', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({ tagihan_id: window.currentTagihanId })
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success' && res.snap_token) {
            window.snap?.pay(res.snap_token, {
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
                onSuccess: () => { window.showNotif('success', 'Pembayaran berhasil!'); switchPage('tagihan-warga'); },
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
                onPending: () => { window.showNotif('info', 'Pembayaran pending, menunggu konfirmasi bank.'); switchPage('tagihan-warga'); },
                onError:   () => { window.showNotif('error', 'Pembayaran gagal. Coba lagi.'); },
                onClose:   () => { window.showNotif('info', 'Pembayaran dibatalkan.'); },
            });
        } else {
            window.showNotif('error', res.message || 'Gagal memulai pembayaran online.');
        }
    })
    .catch(() => window.showNotif('error', 'Gagal terhubung ke server.'));
};

window.bukaModalVerifikasi = function(id, nama, jenis, jumlah, bukti, metode) {
    document.getElementById('verif_tagihan_id').value = id;
    document.getElementById('verif-info').innerHTML =
        `<strong>${nama}</strong> — ${jenis} | <strong class="text-blue-600">Rp ${Number(jumlah).toLocaleString('id-ID')}</strong> | Metode: ${metode || 'manual'}`;

    const img = document.getElementById('verif-bukti-img');
    if (bukti) {
        img.src = '/' + bukti;
        img.onerror = () => { img.src = ''; img.parentElement.innerHTML = '<p class="text-red-400 text-xs text-center py-4">Bukti tidak dapat dimuat</p>'; };
        document.getElementById('verif-bukti-wrap').classList.remove('hidden');
    } else {
        document.getElementById('verif-bukti-wrap').classList.add('hidden');
    }

    document.getElementById('verif_catatan').value = '';
    document.getElementById('modal-verifikasi').classList.remove('hidden');
};

window.submitVerifikasi = function(aksi) {
    const id      = document.getElementById('verif_tagihan_id').value;
    const catatan = document.getElementById('verif_catatan').value;

    if (aksi === 'tolak' && !confirm('Yakin tolak pembayaran ini? Status akan kembali ke Belum Bayar.')) return;

    window.fetchPost('/tagihan/verifikasi', { id, aksi, catatan }, () => {
        document.getElementById('modal-verifikasi').classList.add('hidden');
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.bukaModalBayarWargaBaru = function() {
    const modal = document.getElementById('modal-bayar-warga-proaktif');
    if (modal) modal.classList.remove('hidden');
};

window.lihatBukti = function(path, nama) {
    const cleanPath = path ? (path.startsWith('/') ? path : '/' + path) : '';
    const img = document.getElementById('modal-bukti-img');
    img.src = cleanPath;
    img.onerror = () => {
        window.showNotif('error', 'Gagal memuat foto bukti bayar.');
    };
    document.getElementById('bukti-nama-label').textContent = 'Bukti bayar: ' + nama;
    document.getElementById('modal-lihat-bukti').classList.remove('hidden');
};

window.autoFillProaktifNominal = function() {
    const sel = document.getElementById('proaktif_jenis');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.nominal) {
        document.getElementById('proaktif_jumlah').value = opt.dataset.nominal;
    }
};

window.prosesBayarProaktif = function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-proaktif-submit');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i>Memproses...'; }

    const fd = new FormData(document.getElementById('form-bayar-proaktif'));

    fetch('/tagihan/bayar-langsung', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' },
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            window.showNotif('success', res.message);
            document.getElementById('modal-bayar-warga-proaktif').classList.add('hidden');
            document.getElementById('form-bayar-proaktif').reset();
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
            switchPage('tagihan-warga');
        } else {
            window.showNotif('error', res.message || 'Gagal memproses pembayaran.');
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane mr-1.5"></i>Kirim Pembayaran'; }
        }
    })
    .catch(() => {
        window.showNotif('error', 'Terjadi kesalahan jaringan.');
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane mr-1.5"></i>Kirim Pembayaran'; }
    });
};

window.fetchPost = function(url, data, onSuccess) {
    const isFormData = data instanceof FormData;
    const headers = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' };
    if (!isFormData) headers['Content-Type'] = 'application/json';

    fetch(url, {
        method: 'POST',
        headers,
        body: isFormData ? data : JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        window.showNotif(res.status === 'success' ? 'success' : 'error', res.message || 'Terjadi kesalahan.');
        if (res.status === 'success' && onSuccess) onSuccess(res);
    })
    .catch(() => window.showNotif('error', 'Gagal terhubung ke server.'));
};

window.downloadQRISFromModal = function(imgId) {
    const imageUrl = document.getElementById(imgId).src;
    fetch(imageUrl).then(r => r.blob()).then(blob => {
        const a = document.createElement('a');
        a.href = window.URL.createObjectURL(blob);
        a.download = 'QRIS-Kas-RT.png';
        document.body.appendChild(a); a.click();
        window.URL.revokeObjectURL(a.href); document.body.removeChild(a);
    }).catch(() => alert('Gagal mengunduh QRIS.'));
};
</script>
