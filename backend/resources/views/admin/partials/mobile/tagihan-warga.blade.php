@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    $user         = Auth::user();
    $isAdmin      = in_array($user->role, ['Super Admin', 'RT', 'Bendahara']);
    $qris         = $qris_info ?? DB::table('qris_settings')->first();
    $gateway      = $gateway_info ?? DB::table('payment_gateways')->first();
    $isMidtransOn = $gateway && !empty($gateway->server_key) && !empty($gateway->client_key);

    $statTotal = $stat_total ?? count($tagihans);
    $statLunas = $stat_lunas ?? collect($tagihans)->where('status','lunas')->count();
    $statVerif = $stat_verif ?? collect($tagihans)->where('status','menunggu_verifikasi')->count();
    $statBelum = $stat_belum ?? collect($tagihans)->where('status','belum_bayar')->count();

    // Generate automatic periods: 12 months back, current month, 12 months forward
    $currentDate = \Carbon\Carbon::now();
    $periods = [];
    for ($i = -12; $i <= 12; $i++) {
        $date = (clone $currentDate)->addMonths($i);
        $periods[] = $date->translatedFormat('F Y');
    }
@endphp

<div class="px-3 py-4 space-y-4 pb-24">

    {{-- ══ HEADER ══ --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-black text-gray-800 dark:text-white">Tagihan & Iuran</h2>
            <p class="text-[11px] text-gray-400 dark:text-gray-500">Kelola iuran dan pembayaran RT</p>
        </div>
        @if($isAdmin)
        <div class="flex gap-2">
            <button onclick="document.getElementById('modal-generate-massal-m').classList.remove('hidden')"
                    class="bg-emerald-600 text-white px-3 py-2 rounded-xl font-bold text-xs flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-bolt"></i> Massal
            </button>
            <button onclick="document.getElementById('modal-tambah-tagihan-m').classList.remove('hidden')"
                    class="bg-blue-600 text-white px-3 py-2 rounded-xl font-bold text-xs flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Buat
            </button>
        </div>
        @else
        <div class="flex gap-2">
            <button onclick="window.bukaModalBayarWargaBaruM()"
                    class="bg-blue-600 text-white px-3 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow cursor-pointer">
                <i class="fa-solid fa-wallet"></i> Bayar Iuran
            </button>
        </div>
        @endif
    </div>

    {{-- ══ STAT MINI ══ --}}
    <div class="grid grid-cols-4 gap-2">
        @foreach([
            ['n'=>$statTotal,'lbl'=>'Total','color'=>'blue'],
            ['n'=>$statLunas,'lbl'=>'Lunas','color'=>'green'],
            ['n'=>$statVerif,'lbl'=>'Verif','color'=>'amber'],
            ['n'=>$statBelum,'lbl'=>'Belum','color'=>'red'],
        ] as $s)
        <div class="bg-white dark:bg-slate-800/60 rounded-xl border border-gray-100 dark:border-slate-700 p-2.5 text-center">
            <p class="text-base font-black text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400">{{ $s['n'] }}</p>
            <p class="text-[9px] text-gray-400 font-medium">{{ $s['lbl'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ══ FILTER SCROLL ══ --}}
    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
        <button onclick="window.filterTagihanM('semua')" id="mfilter-semua" class="mfilter-btn active flex-shrink-0 px-3 py-1.5 rounded-xl text-[11px] font-bold border transition cursor-pointer">Semua</button>
        <button onclick="window.filterTagihanM('belum_bayar')" id="mfilter-belum_bayar" class="mfilter-btn flex-shrink-0 px-3 py-1.5 rounded-xl text-[11px] font-bold border transition cursor-pointer">Belum Bayar</button>
        <button onclick="window.filterTagihanM('menunggu_verifikasi')" id="mfilter-menunggu_verifikasi" class="mfilter-btn flex-shrink-0 px-3 py-1.5 rounded-xl text-[11px] font-bold border transition cursor-pointer">Verifikasi</button>
        <button onclick="window.filterTagihanM('lunas')" id="mfilter-lunas" class="mfilter-btn flex-shrink-0 px-3 py-1.5 rounded-xl text-[11px] font-bold border transition cursor-pointer">Lunas</button>
    </div>

    {{-- ══ CARD LIST ══ --}}
    <div class="space-y-2.5" id="tagihan-card-list-m">
        @forelse($tagihans as $item)
        <div class="tagihan-card-m bg-white dark:bg-slate-800/60 rounded-2xl border border-gray-100 dark:border-slate-700 p-4 shadow-sm"
             data-status="{{ $item->status }}">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div class="flex-1 min-w-0">
                    <p class="font-black text-gray-800 dark:text-white text-sm truncate">{{ $item->nama_warga }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $item->jenis_tagihan }}@if($item->periode) • {{ $item->periode }}@endif</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-black text-gray-800 dark:text-white text-sm">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between">
                {{-- Badge status --}}
                @if($item->status === 'lunas')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> LUNAS
                    </span>
                @elseif($item->status === 'menunggu_verifikasi')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> VERIFIKASI
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> BELUM BAYAR
                    </span>
                @endif

                {{-- Action buttons --}}
                <div class="flex gap-1.5">
                    @if(!$isAdmin && $item->status === 'belum_bayar')
                    <button onclick="window.bukaModalBayarM({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', {{ $item->jumlah }}, '{{ $item->periode }}', '{{ $item->catatan }}')"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg cursor-pointer">
                        <i class="fa-solid fa-wallet mr-1"></i>Bayar
                    </button>
                    @endif
                    @if(!$isAdmin && $item->status === 'menunggu_verifikasi')
                    <span class="text-amber-600 text-[11px] font-semibold bg-amber-50 px-2.5 py-1.5 rounded-lg">⏳ Tunggu</span>
                    @endif
                    @if($isAdmin && $item->status === 'menunggu_verifikasi')
                    <button onclick="window.bukaModalVerifikasiM({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', {{ $item->jumlah }}, '{{ $item->bukti_bayar }}', '{{ $item->metode_bayar }}')"
                            class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg cursor-pointer">
                        <i class="fa-solid fa-check mr-1"></i>Verif
                    </button>
                    @endif
                    @if($isAdmin)
                    <button onclick="window.bukaModalEditM({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', '{{ addslashes($item->periode ?? '') }}', {{ $item->jumlah }}, '{{ $item->status }}', '{{ $item->batas_bayar }}', '{{ addslashes($item->catatan ?? '') }}')"
                            class="bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold text-[11px] px-2.5 py-1.5 rounded-lg cursor-pointer">Edit</button>
                    <button onclick="window.hapusTagihanM({{ $item->id }}, '{{ addslashes($item->nama_warga) }}')"
                            class="bg-red-50 dark:bg-red-950/30 text-red-500 dark:text-red-400 font-bold text-[11px] px-2.5 py-1.5 rounded-lg cursor-pointer">Hapus</button>
                    @endif
                    @if($item->bukti_bayar)
                    <button onclick="window.lihatBuktiM('{{ $item->bukti_bayar }}', '{{ addslashes($item->nama_warga) }}')"
                            class="bg-gray-50 dark:bg-slate-700 text-gray-500 font-bold text-[11px] px-2.5 py-1.5 rounded-lg cursor-pointer">
                        <i class="fa-solid fa-image"></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400 dark:text-gray-500">
            <i class="fa-solid fa-file-invoice text-3xl mb-3 opacity-30 block"></i>
            <p class="text-sm">Belum ada tagihan</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ═══ MODAL: BUAT TAGIHAN MOBILE ═══ --}}
<div id="modal-tambah-tagihan-m" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-t-[2rem] w-full max-w-lg p-6 pb-8 relative shadow-2xl border-t border-gray-100 dark:border-slate-800 max-h-[92vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-tambah-tagihan-m').classList.add('hidden')"
                class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 dark:text-white mb-4">Buat Tagihan Baru</h3>
        <form id="form-tambah-tagihan-m" onsubmit="simpanTagihanM(event)">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Warga</label>
                    <input type="hidden" name="nama_warga" id="tagihan_warga_hidden_m">
                    <div class="relative">
                        <input type="text" id="tagihan_warga_search_m" placeholder="🔍 Cari warga..."
                               onfocus="showDropdown('tagihan_warga_dropdown_m')"
                               onkeyup="filterCustomDropdown('tagihan_warga_search_m','tagihan_warga_dropdown_m')"
                               autocomplete="off"
                               class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-semibold text-gray-700 dark:text-gray-200 py-2.5 px-3 pr-8 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        <div id="tagihan_warga_dropdown_m" class="hidden absolute left-0 right-0 top-full mt-1 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 max-h-48 overflow-y-auto">
                            @foreach($all_warga ?? [] as $w)
                            <div onclick="selectTagihanWargaM('{{ addslashes($w->nama_lengkap) }}')"
                                 class="dropdown-item-m px-3 py-2.5 hover:bg-blue-50 dark:hover:bg-blue-950/30 cursor-pointer transition text-sm">
                                <span class="font-bold text-gray-800 dark:text-gray-100 block">{{ $w->nama_lengkap }}</span>
                                <span class="text-[10px] text-gray-400">Blok {{ $w->blok_rumah }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis Iuran</label>
                        <select name="jenis_tagihan" id="tagihan_jenis_m" onchange="autoFillNominalM()" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl focus:outline-none text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($contributions ?? [] as $c)
                            <option value="{{ $c->nama_iuran }}" data-nominal="{{ $c->nominal }}">{{ $c->nama_iuran }}</option>
                            @endforeach
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Periode Bulan</label>
                        <select name="periode" id="tagihan_periode_m" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl focus:outline-none text-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ $p == $currentDate->translatedFormat('F Y') ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" id="tagihan_jumlah_m" placeholder="25000" min="1000" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl focus:outline-none text-sm">
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah-tagihan-m').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 bg-gray-100 dark:bg-slate-800 text-sm cursor-pointer">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm cursor-pointer">
                    Buat Tagihan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL: GENERATE MASSAL MOBILE ═══ --}}
<div id="modal-generate-massal-m" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-t-[2rem] w-full max-w-lg p-6 pb-8 relative shadow-2xl border-t border-gray-100 dark:border-slate-800">
        <button onclick="document.getElementById('modal-generate-massal-m').classList.add('hidden')"
                class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 dark:text-white mb-4">⚡ Generate Massal</h3>
        <form id="form-generate-massal-m" onsubmit="generateTagihanMassalM(event)">
            @csrf
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis Iuran</label>
                        <select name="jenis_tagihan" id="massal_jenis_m" onchange="autoFillMassalNominalM()" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl focus:outline-none text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($contributions ?? [] as $c)
                            <option value="{{ $c->nama_iuran }}" data-nominal="{{ $c->nominal }}">{{ $c->nama_iuran }}</option>
                            @endforeach
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Periode Bulan</label>
                        <select name="periode" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl focus:outline-none text-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ $p == $currentDate->translatedFormat('F Y') ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" id="massal_jumlah_m" placeholder="25000" min="1000" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl focus:outline-none text-sm">
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-generate-massal-m').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 bg-gray-100 dark:bg-slate-800 text-sm cursor-pointer">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm cursor-pointer">
                    Generate Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL: EDIT MOBILE ═══ --}}
<div id="modal-edit-tagihan-m" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-t-[2rem] w-full max-w-lg p-6 pb-8 relative shadow-2xl border-t border-gray-100 dark:border-slate-800 max-h-[92vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-edit-tagihan-m').classList.add('hidden')"
                class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 dark:text-white mb-4">Edit Tagihan</h3>
        <form id="form-edit-tagihan-m" onsubmit="updateTagihanM(event)">
            @csrf
            <input type="hidden" name="id" id="edit_tagihan_id_m">
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Warga</label>
                    <input type="text" name="nama_warga" id="edit_nama_warga_m" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-sm py-2.5 px-3 rounded-xl focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis</label>
                        <input type="text" name="jenis_tagihan" id="edit_jenis_m" required
                               class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-sm py-2.5 px-3 rounded-xl focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Periode Bulan</label>
                        <select name="periode" id="edit_periode_m" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-sm py-2.5 px-3 rounded-xl focus:outline-none">
                            @foreach($periods as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" id="edit_jumlah_m" required
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-sm py-2.5 px-3 rounded-xl focus:outline-none">
                </div>
                {{-- Hidden Jatuh Tempo (keep ID for JS compatibility) --}}
                <input type="hidden" name="batas_bayar" id="edit_batas_m">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status</label>
                        <select name="status" id="edit_status_m" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-bold text-sm py-2.5 px-3 rounded-xl focus:outline-none">
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Catatan</label>
                        <input type="text" name="catatan" id="edit_catatan_m"
                               class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-medium text-sm py-2.5 px-3 rounded-xl focus:outline-none">
                    </div>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-tagihan-m').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-xl font-bold text-gray-500 bg-gray-100 dark:bg-slate-800 text-sm cursor-pointer">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm cursor-pointer">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL: BAYAR MOBILE ═══ --}}
<div id="modal-bayar-m" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-t-[2rem] w-full max-w-lg p-6 pb-8 relative shadow-2xl border-t border-gray-100 dark:border-slate-800 max-h-[92vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-bayar-m').classList.add('hidden')"
                class="absolute top-5 right-5 text-gray-400 cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 dark:text-white mb-1">Bayar Tagihan</h3>
        <div id="bayar-info-m" class="text-xs text-gray-500 dark:text-gray-400 mb-4"></div>
        <div id="bayar-catatan-m" class="hidden text-xs text-red-600 bg-red-50 dark:bg-red-950/30 border border-red-200 rounded-xl px-3 py-2 mb-3"></div>

        <div id="form-upload-bukti-m">
            @if($qris)
            <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900 rounded-xl p-3 mb-3 text-[11px]">
                <p class="font-bold text-blue-700 dark:text-blue-300 mb-1.5">📋 Rekening & QRIS Tujuan Transfer</p>
                @if($qris->bank_1_name)
                <p class="text-gray-600 dark:text-gray-300">🏦 <strong>{{ $qris->bank_1_name }}</strong>: {{ $qris->bank_1_number }} a/n {{ $qris->bank_1_owner }}</p>
                @endif
                @if($qris->bank_2_name)
                <p class="text-gray-600 dark:text-gray-300">🏦 <strong>{{ $qris->bank_2_name }}</strong>: {{ $qris->bank_2_number }} a/n {{ $qris->bank_2_owner }}</p>
                @endif
                <div class="mt-2 pt-2 border-t border-blue-100 dark:border-blue-900/40 text-center">
                    <p class="font-bold text-blue-700 dark:text-blue-300 mb-1">📱 QRIS KAS RT</p>
                    <img id="modal-qris-img-tagihan-m" src="{{ $qris->qris_image ? asset($qris->qris_image) : 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode($qris->qris_data ?? 'KOSONG') }}" alt="QRIS" class="w-28 h-28 object-contain mx-auto my-1.5 rounded-xl border border-gray-200 p-1 bg-white">
                    <a href="javascript:void(0)" onclick="downloadQRISFromModalM('modal-qris-img-tagihan-m')" class="text-blue-600 hover:text-blue-700 font-bold text-[10px] inline-flex items-center gap-1 bg-blue-100/50 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition mt-0.5">
                        <i class="fa-solid fa-download"></i> Unduh QRIS
                    </a>
                </div>
            </div>
            @endif
            <form id="form-upload-transfer-m" onsubmit="uploadBuktiBayarM(event)">
                @csrf
                <input type="hidden" name="tagihan_id" id="upload_tagihan_id_m">
                <div class="mb-3">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bukti Pembayaran (Foto)</label>
                    <input type="file" name="bukti_bayar" required accept="image/*"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 py-2 px-3 rounded-xl text-sm file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-600">
                </div>
                <div class="mb-4">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Catatan (Opsional)</label>
                    <input type="text" name="catatan" placeholder="cth: Transfer BCA tanggal 15"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-medium text-gray-700 dark:text-gray-200 py-2 px-3 rounded-xl text-sm">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition cursor-pointer text-sm">
                    <i class="fa-solid fa-upload mr-2"></i>Upload Bukti
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ═══ MODAL: VERIFIKASI MOBILE ═══ --}}
<div id="modal-verifikasi-m" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-t-[2rem] w-full max-w-lg p-6 pb-8 relative shadow-2xl border-t border-gray-100 dark:border-slate-800">
        <button onclick="document.getElementById('modal-verifikasi-m').classList.add('hidden')"
                class="absolute top-5 right-5 text-gray-400 cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-base font-black text-gray-800 dark:text-white mb-1">Verifikasi Pembayaran</h3>
        <p id="verif-info-m" class="text-[11px] text-gray-400 dark:text-gray-500 mb-4"></p>
        <div id="verif-bukti-wrap-m" class="mb-4">
            <img id="verif-bukti-img-m" src="" alt="Bukti" class="w-full max-h-52 object-contain rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800">
            <p class="text-[10px] text-gray-400 text-center mt-1">Klik untuk buka penuh</p>
        </div>
        <div class="mb-4">
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Catatan</label>
            <input type="text" id="verif_catatan_m" placeholder="Opsional..."
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 font-medium text-gray-700 dark:text-gray-200 py-2 px-3 rounded-xl text-sm">
        </div>
        <input type="hidden" id="verif_tagihan_id_m">
        <div class="flex gap-2">
            <button onclick="submitVerifikasiM('tolak')"
                    class="flex-1 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 font-bold py-3 rounded-xl border border-red-200 dark:border-red-900 cursor-pointer text-sm">
                <i class="fa-solid fa-xmark mr-1.5"></i>Tolak
            </button>
            <button onclick="submitVerifikasiM('setujui')"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl cursor-pointer text-sm">
                <i class="fa-solid fa-check mr-1.5"></i>Setujui
            </button>
        </div>
    </div>
</div>

{{-- ═══ MODAL: LIHAT BUKTI MOBILE ═══ --}}
<div id="modal-lihat-bukti-m" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" onclick="document.getElementById('modal-lihat-bukti-m').classList.add('hidden')">
    <div class="w-full max-w-sm" onclick="event.stopPropagation()">
        <button onclick="document.getElementById('modal-lihat-bukti-m').classList.add('hidden')" class="block text-white/70 mb-2 ml-auto cursor-pointer">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <img id="modal-bukti-img-m" src="" alt="Bukti" class="w-full rounded-2xl">
    </div>
</div>

{{-- ═══ MODAL: BAYAR IURAN PROAKTIF MOBILE ═══ --}}
<div id="modal-bayar-warga-proaktif" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-t-[2rem] w-full max-w-lg p-6 pb-8 relative shadow-2xl border-t border-gray-100 dark:border-slate-800 max-h-[92vh] overflow-y-auto">
        <button onclick="document.getElementById('modal-bayar-warga-proaktif').classList.add('hidden')"
                class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center gap-2.5 mb-1">
            <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-950/40 flex items-center justify-center">
                <i class="fa-solid fa-wallet text-blue-600 dark:text-blue-400 text-base"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-gray-800 dark:text-white">Bayar Iuran RT</h3>
                <p class="text-[11px] text-gray-400">Pilih iuran & upload resi transfer</p>
            </div>
        </div>

        <form id="form-bayar-proaktif-m" onsubmit="window.prosesBayarProaktifM(event)">
            @csrf
            <input type="hidden" name="nama_warga" value="{{ Auth::user()->name }}">
            
            <div class="space-y-3 mt-4 text-xs">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pilih Jenis Iuran</label>
                    <select name="jenis_tagihan" id="proaktif_jenis_m" onchange="window.autoFillProaktifNominalM()" required
                            class="w-full bg-gray-50 dark:bg-slate-800 border font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl text-xs">
                        <option value="">-- Pilih Jenis Iuran --</option>
                        @foreach($contributions ?? [] as $c)
                        <option value="{{ $c->nama_iuran }}" data-nominal="{{ $c->nominal }}">{{ $c->nama_iuran }} (Rp {{ number_format($c->nominal, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Periode</label>
                        <select name="periode" id="proaktif_periode_m" required
                                class="w-full bg-gray-50 dark:bg-slate-800 border font-bold text-gray-700 dark:text-gray-200 py-2.5 px-3 rounded-xl text-xs">
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ $p == $currentDate->translatedFormat('F Y') ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Nominal (Rp)</label>
                        <input type="number" name="jumlah" id="proaktif_jumlah_m" placeholder="25000" min="1000" required
                               class="w-full bg-gray-50 dark:bg-slate-800 border font-bold py-2.5 px-3 rounded-xl text-xs">
                    </div>
                </div>

                @if($qris)
                <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 rounded-xl p-2.5 text-[11px]">
                    <p class="font-bold text-blue-700 mb-0.5">📋 Rekening & QRIS Tujuan Transfer</p>
                    @if($qris->bank_1_name)<p class="text-gray-600">🏦 <strong>{{ $qris->bank_1_name }}</strong>: {{ $qris->bank_1_number }} a/n {{ $qris->bank_1_owner }}</p>@endif
                    @if($qris->bank_2_name)<p class="text-gray-600">🏦 <strong>{{ $qris->bank_2_name }}</strong>: {{ $qris->bank_2_number }} a/n {{ $qris->bank_2_owner }}</p>@endif
                    <div class="mt-2 pt-2 border-t border-blue-100 text-center">
                        <p class="font-bold text-blue-750 text-blue-700 mb-1">📱 QRIS KAS RT</p>
                        <img id="modal-qris-img-iuran-m" src="{{ $qris->qris_image ? asset($qris->qris_image) : 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode($qris->qris_data ?? 'KOSONG') }}" alt="QRIS" class="w-28 h-28 object-contain mx-auto my-1.5 rounded-xl border border-gray-200 p-1 bg-white">
                        <a href="javascript:void(0)" onclick="downloadQRISFromModalM('modal-qris-img-iuran-m')" class="text-blue-600 hover:text-blue-700 font-bold text-[10px] inline-flex items-center gap-1 bg-blue-100/50 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition mt-0.5">
                            <i class="fa-solid fa-download"></i> Unduh QRIS
                        </a>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Bukti Transfer (Foto)</label>
                    <input type="file" name="bukti_bayar" id="proaktif_bukti_m" required accept="image/*"
                           class="w-full bg-gray-50 dark:bg-slate-800 border py-2 px-3 rounded-xl text-xs">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Catatan</label>
                    <input type="text" name="catatan" placeholder="cth: Transfer BCA"
                           class="w-full bg-gray-50 dark:bg-slate-800 border py-2 px-3 rounded-xl text-xs">
                </div>
            </div>

            <div class="mt-5 flex gap-2">
                <button type="button" onclick="document.getElementById('modal-bayar-warga-proaktif').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-600 font-bold py-2.5 rounded-xl text-xs cursor-pointer">Batal</button>
                <button type="submit" id="btn-proaktif-submit-m" class="flex-1 bg-blue-600 text-white font-bold py-2.5 rounded-xl text-xs shadow cursor-pointer">
                    <i class="fa-solid fa-paper-plane mr-1"></i>Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.mfilter-btn { background: white; color: #6b7280; border-color: #e5e7eb; }
.dark .mfilter-btn { background: transparent; color: #9ca3af; border-color: #374151; }
.mfilter-btn.active { background: #2563eb; color: white; border-color: #2563eb; }
.metode-btn-m.selected { border-color: #2563eb; background: #eff6ff; }
.dark .metode-btn-m.selected { background: rgba(37,99,235,0.1); }
</style>

<script>
window.currentFilterM = 'semua';
window.currentTagihanIdM = null;

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

window.filterTagihanM = function(status) {
    window.currentFilterM = status;
    document.querySelectorAll('.mfilter-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('mfilter-' + status)?.classList.add('active');
    document.querySelectorAll('.tagihan-card-m').forEach(card => {
        card.style.display = (status === 'semua' || card.dataset.status === status) ? '' : 'none';
    });
};

window.autoFillNominalM = function() {
    const sel = document.getElementById('tagihan_jenis_m');
    const opt = sel.options[sel.selectedIndex];
    if (opt?.dataset.nominal) document.getElementById('tagihan_jumlah_m').value = opt.dataset.nominal;
};

window.autoFillMassalNominalM = function() {
    const sel = document.getElementById('massal_jenis_m');
    const opt = sel.options[sel.selectedIndex];
    if (opt?.dataset.nominal) document.getElementById('massal_jumlah_m').value = opt.dataset.nominal;
};

window.selectTagihanWargaM = function(nama) {
    document.getElementById('tagihan_warga_hidden_m').value = nama;
    document.getElementById('tagihan_warga_search_m').value = nama;
    document.getElementById('tagihan_warga_dropdown_m').classList.add('hidden');
};

window.simpanTagihanM = function(e) {
    e.preventDefault();
    const nama = document.getElementById('tagihan_warga_hidden_m').value;
    if (!nama) { window.showNotif('error', 'Pilih nama warga terlebih dahulu!'); return; }
    const fd = new FormData(document.getElementById('form-tambah-tagihan-m'));
    window.fetchPostM('/tagihan/store', fd, () => {
        document.getElementById('modal-tambah-tagihan-m').classList.add('hidden');
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.generateTagihanMassalM = function(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-generate-massal-m'));
    window.fetchPostM('/tagihan/generate-massal', fd, () => {
        document.getElementById('modal-generate-massal-m').classList.add('hidden');
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.bukaModalEditM = function(id, nama, jenis, periode, jumlah, status, batas, catatan) {
    document.getElementById('edit_tagihan_id_m').value    = id;
    document.getElementById('edit_nama_warga_m').value    = nama;
    document.getElementById('edit_jenis_m').value         = jenis;
    document.getElementById('edit_periode_m').value       = periode || '';
    document.getElementById('edit_jumlah_m').value        = jumlah;
    document.getElementById('edit_status_m').value        = status;
    document.getElementById('edit_batas_m').value         = batas || '';
    document.getElementById('edit_catatan_m').value       = catatan || '';
    document.getElementById('modal-edit-tagihan-m').classList.remove('hidden');
};

window.updateTagihanM = function(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-edit-tagihan-m'));
    window.fetchPostM('/tagihan/update', fd, () => {
        document.getElementById('modal-edit-tagihan-m').classList.add('hidden');
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.hapusTagihanM = function(id, nama) {
    if (!confirm(`Hapus tagihan milik "${nama}"?`)) return;
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
    window.fetchPostM('/tagihan/delete', { id }, () => switchPage('tagihan-warga'));
};

window.bukaModalBayarM = function(id, nama, jenis, jumlah, periode, catatan) {
    window.currentTagihanIdM = id;
    document.getElementById('upload_tagihan_id_m').value = id;
    document.getElementById('bayar-info-m').innerHTML =
        `<strong class="text-gray-700 dark:text-gray-200">${jenis}</strong>${periode ? ' — ' + periode : ''} <span class="font-bold text-blue-600">Rp ${Number(jumlah).toLocaleString('id-ID')}</span>`;
    const cDiv = document.getElementById('bayar-catatan-m');
    if (catatan?.trim()) { cDiv.classList.remove('hidden'); cDiv.innerHTML = '⚠️ ' + catatan; }
    else cDiv.classList.add('hidden');
    document.getElementById('modal-bayar-m').classList.remove('hidden');
    window.pilihMetodeM('transfer');
};

window.pilihMetodeM = function(metode) {
    document.querySelectorAll('.metode-btn-m').forEach(b => b.classList.remove('selected'));
    document.getElementById('btn-metode-' + metode + '-m')?.classList.add('selected');
    if (metode === 'transfer') document.getElementById('form-upload-bukti-m').classList.remove('hidden');
    else if (metode === 'midtrans') { document.getElementById('form-upload-bukti-m').classList.add('hidden'); window.bayarMidtransM(); }
};

window.uploadBuktiBayarM = function(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('form-upload-transfer-m'));
    const btn = e.submitter;
    if (btn) { btn.disabled = true; btn.textContent = 'Mengupload...'; }
    fetch('/tagihan/bayar-manual', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' },
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        window.showNotif(res.status, res.message);
        if (res.status === 'success') {
            document.getElementById('modal-bayar-m').classList.add('hidden');
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
            switchPage('tagihan-warga');
        }
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-upload mr-2"></i>Upload Bukti'; }
    })
    .catch(() => { window.showNotif('error', 'Gagal upload.'); if (btn) btn.disabled = false; });
};

window.bayarMidtransM = function() {
    if (!window.currentTagihanIdM) return;
    fetch('/tagihan/bayar-midtrans', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' },
        body: JSON.stringify({ tagihan_id: window.currentTagihanIdM })
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success' && res.snap_token) {
            window.snap?.pay(res.snap_token, {
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
                onSuccess: () => { window.showNotif('success', 'Pembayaran berhasil!'); switchPage('tagihan-warga'); },
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
                onPending: () => { window.showNotif('info', 'Pembayaran pending.'); switchPage('tagihan-warga'); },
                onError: () => window.showNotif('error', 'Pembayaran gagal.'),
                onClose: () => window.showNotif('info', 'Dibatalkan.'),
            });
        } else window.showNotif('error', res.message);
    });
};

window.bukaModalVerifikasiM = function(id, nama, jenis, jumlah, bukti, metode) {
    document.getElementById('verif_tagihan_id_m').value = id;
    document.getElementById('verif-info-m').innerHTML = `<strong>${nama}</strong> — ${jenis} | <strong class="text-blue-600">Rp ${Number(jumlah).toLocaleString('id-ID')}</strong>`;
    const img = document.getElementById('verif-bukti-img-m');
    if (bukti) { img.src = '/' + bukti; img.onclick = () => window.open('/' + bukti, '_blank'); }
    document.getElementById('verif_catatan_m').value = '';
    document.getElementById('modal-verifikasi-m').classList.remove('hidden');
};

window.submitVerifikasiM = function(aksi) {
    const id = document.getElementById('verif_tagihan_id_m').value;
    const catatan = document.getElementById('verif_catatan_m').value;
    if (aksi === 'tolak' && !confirm('Tolak pembayaran ini?')) return;
    window.fetchPostM('/tagihan/verifikasi', { id, aksi, catatan }, () => {
        document.getElementById('modal-verifikasi-m').classList.add('hidden');
        if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
        switchPage('tagihan-warga');
    });
};

window.lihatBuktiM = function(path, nama) {
    if (!path) {
        window.showNotif('error', 'Bukti bayar belum diunggah.');
        return;
    }
    const cleanPath = path.startsWith('/') ? path : '/' + path;
    const img = document.getElementById('modal-bukti-img-m');
    if (img) {
        img.onerror = null;
        img.src = cleanPath;
        img.onerror = function() { window.showNotif('error', 'Gagal memuat foto bukti bayar.'); };
    }
    const modal = document.getElementById('modal-lihat-bukti-m');
    if (modal) modal.classList.remove('hidden');
};

window.bukaModalBayarWargaBaruM = function() {
    const modal = document.getElementById('modal-bayar-warga-proaktif') || document.getElementById('modal-tambah-tagihan-m');
    if (modal) {
        modal.classList.remove('hidden');
    } else {
        alert('Modal bayar tidak ditemukan.');
    }
};

window.autoFillProaktifNominalM = function() {
    const sel = document.getElementById('proaktif_jenis_m');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.nominal) {
        document.getElementById('proaktif_jumlah_m').value = opt.dataset.nominal;
    }
};

window.prosesBayarProaktifM = function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-proaktif-submit-m');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Memproses...'; }

    const fd = new FormData(document.getElementById('form-bayar-proaktif-m'));

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
            document.getElementById('form-bayar-proaktif-m').reset();
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('tagihan-warga'); }
            switchPage('tagihan-warga');
        } else {
            window.showNotif('error', res.message || 'Gagal memproses pembayaran.');
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane mr-1"></i>Kirim'; }
        }
    })
    .catch(() => {
        window.showNotif('error', 'Terjadi kesalahan jaringan.');
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane mr-1"></i>Kirim'; }
    });
};

window.fetchPostM = function(url, data, onSuccess) {
    const isFormData = data instanceof FormData;
    const headers = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' };
    if (!isFormData) headers['Content-Type'] = 'application/json';
    fetch(url, { method: 'POST', headers, body: isFormData ? data : JSON.stringify(data) })
    .then(r => r.json())
    .then(res => {
        window.showNotif(res.status === 'success' ? 'success' : 'error', res.message || 'Terjadi kesalahan.');
        if (res.status === 'success' && onSuccess) onSuccess(res);
    })
    .catch(() => window.showNotif('error', 'Gagal terhubung.'));
};

window.downloadQRISFromModalM = function(imgId) {
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
