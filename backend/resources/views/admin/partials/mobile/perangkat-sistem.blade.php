@php
    $isOfficer = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']);
@endphp

<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#334155] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg border border-white/10">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <i class="fa-solid fa-boxes-packing absolute -bottom-4 -right-2 text-[70px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-2">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-5 h-5 rounded-md bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-packing text-blue-300 text-[9px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-blue-300/80">Fasilitas Warga</span>
                </div>
                <h1 class="text-base font-black tracking-tight leading-tight">Inventaris & Pengembalian Aset</h1>
                <p class="text-[10px] text-slate-300/80 font-medium">Peminjaman, approval & pemeriksaan pengembalian.</p>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-3 gap-2 mt-1">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2 text-center">
                    <p class="text-xs font-black text-white leading-none">{{ collect($list_perangkat ?? [])->sum('jumlah') }}</p>
                    <p class="text-[8px] font-bold uppercase text-blue-300/70 mt-0.5">Total Aset</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2 text-center">
                    <p class="text-xs font-black text-amber-400 leading-none">{{ $total_pinjam_aktif ?? 0 }}</p>
                    <p class="text-[8px] font-bold uppercase text-amber-300/70 mt-0.5">Dipinjam</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2 text-center">
                    <p class="text-xs font-black text-rose-400 leading-none">{{ $total_pending_approval ?? 0 }}</p>
                    <p class="text-[8px] font-bold uppercase text-rose-300/70 mt-0.5">Approval</p>
                </div>
            </div>

            <!-- Tab Buttons -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 mt-1">
                <button onclick="switchAsetMobileTab('m-inventaris')" id="m-tab-btn-inventaris" class="m-aset-tab-btn px-2.5 py-1.5 rounded-lg font-bold text-[9px] shrink-0 bg-blue-600 text-white">
                    📦 Inventaris
                </button>
                <button onclick="switchAsetMobileTab('m-peminjaman')" id="m-tab-btn-peminjaman" class="m-aset-tab-btn px-2.5 py-1.5 rounded-lg font-bold text-[9px] shrink-0 bg-white/10 text-gray-300">
                    📋 Approval
                </button>
                <button onclick="switchAsetMobileTab('m-pengembalian')" id="m-tab-btn-pengembalian" class="m-aset-tab-btn px-2.5 py-1.5 rounded-lg font-bold text-[9px] shrink-0 bg-white/10 text-gray-300">
                    🔄 Pengembalian
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Action Button -->
    <button onclick="document.getElementById('modal-ajukan-peminjaman-aset').classList.remove('hidden')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl text-[11px] shadow-sm flex items-center justify-center gap-1.5">
        <i class="fa-solid fa-hand-holding-box text-[10px]"></i> Form Ajukan Pinjam Aset
    </button>

    <!-- TAB 1: INVENTARIS ASET (MOBILE) -->
    <div id="m-tab-inventaris" class="m-aset-tab-content space-y-2">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black text-gray-800">Daftar Inventaris Aset Warga</h3>
            @if($isOfficer)
            <button onclick="document.getElementById('modal-tambah-perangkat-m').classList.remove('hidden')" class="bg-indigo-600 text-white font-bold px-2 py-0.5 rounded text-[9px]">
                + Tambah Aset
            </button>
            @endif
        </div>

        @forelse($list_perangkat ?? [] as $item)
        <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm space-y-2">
            <div class="flex items-start justify-between">
                <div>
                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[8px] font-bold uppercase">{{ $item->jenis_perangkat }}</span>
                    <h4 class="font-bold text-gray-800 text-[12px] mt-1">{{ $item->nama_perangkat }}</h4>
                </div>
                <span class="px-2 py-0.5 rounded text-[8px] font-bold {{ $item->badge_class ?? 'bg-gray-50 text-gray-600' }}">
                    {{ $item->kondisi }}
                </span>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-gray-50 text-[10px]">
                <span class="font-bold text-slate-800">Stok: {{ $item->jumlah }} Unit</span>
                <div class="flex items-center gap-1.5">
                    <button onclick="bukaPinjamSpesifik('{{ addslashes($item->nama_perangkat) }}')" class="bg-emerald-600 text-white text-[9px] font-bold px-2.5 py-1 rounded-md">
                        Pinjam
                    </button>
                    @if($isOfficer)
                    <button onclick="editPerangkat({{ json_encode($item) }})" class="p-1 text-blue-600"><i class="fa-solid fa-pen text-[10px]"></i></button>
                    <button onclick="hapusPerangkat({{ $item->id }})" class="p-1 text-red-500"><i class="fa-solid fa-trash text-[10px]"></i></button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl p-6 text-center text-gray-400 italic text-xs">Belum ada inventaris aset.</div>
        @endforelse
    </div>

    <!-- TAB 2: APPROVAL (MOBILE) -->
    <div id="m-tab-peminjaman" class="m-aset-tab-content space-y-2 hidden">
        <h3 class="text-xs font-black text-gray-800 px-1">Riwayat & Approval Peminjaman</h3>
        @forelse($list_peminjaman ?? [] as $loan)
        <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm space-y-1.5">
            <div class="flex items-center justify-between">
                <span class="font-bold text-gray-800 text-[11px]">{{ $loan->nama_warga }}</span>
                <span class="px-2 py-0.5 rounded text-[8px] font-bold {{ $loan->status == 'Sudah Dikembalikan' ? 'bg-slate-100 text-slate-700' : ($loan->status == 'Disetujui' ? 'bg-emerald-100 text-emerald-700' : ($loan->status == 'Proses Pengembalian' ? 'bg-purple-100 text-purple-700' : ($loan->status == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'))) }}">
                    {{ $loan->status }}
                </span>
            </div>
            <p class="text-xs font-bold text-blue-600">{{ $loan->nama_aset }} ({{ $loan->jumlah_unit }} Unit)</p>
            <p class="text-[9px] text-gray-500"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d/m/Y') }}</p>
            <p class="text-[9px] text-gray-600 italic">"{{ $loan->keperluan }}"</p>

            <div class="flex items-center gap-1 pt-1.5 border-t border-gray-50 mt-1">
                @if($isOfficer && $loan->status == 'Menunggu Approval')
                <button onclick="prosesApprovalLoan({{ $loan->id }}, 'approve')" class="flex-1 bg-emerald-600 text-white font-bold text-[9px] py-1 rounded">Setujui</button>
                <button onclick="prosesApprovalLoan({{ $loan->id }}, 'reject')" class="flex-1 bg-red-500 text-white font-bold text-[9px] py-1 rounded">Tolak</button>
                @endif

                @if(($loan->status == 'Disetujui' || $loan->status == 'Sedang Dipinjam' || $loan->status == 'Proses Pengembalian'))
                <button onclick="bukaModalSubmitKembali({{ $loan->id }}, '{{ addslashes($loan->nama_aset) }}')" class="flex-1 bg-indigo-600 text-white font-bold text-[9px] py-1 rounded">Kembalikan</button>
                @if($isOfficer)
                <button onclick="bukaModalVerifikasiKembali({{ $loan->id }}, '{{ addslashes($loan->nama_warga) }}', '{{ addslashes($loan->nama_aset) }}')" class="flex-1 bg-emerald-700 text-white font-bold text-[9px] py-1 rounded">Verifikasi</button>
                @endif
                @endif

                @if($isOfficer)
                <button onclick="hapusLoan({{ $loan->id }})" class="p-1 text-red-500"><i class="fa-solid fa-trash text-[10px]"></i></button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl p-6 text-center text-gray-400 italic text-xs">Belum ada permohonan peminjaman aset.</div>
        @endforelse
    </div>

    <!-- TAB 3: PENGEMBALIAN ASET (MOBILE) -->
    <div id="m-tab-pengembalian" class="m-aset-tab-content space-y-2 hidden">
        <h3 class="text-xs font-black text-gray-800 px-1">Riwayat Inspection Pengembalian</h3>
        @forelse(collect($list_peminjaman ?? [])->whereIn('status', ['Sudah Dikembalikan', 'Proses Pengembalian']) as $ret)
        <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="font-bold text-gray-800 text-[11px]">{{ $ret->nama_warga }}</span>
                <span class="px-2 py-0.5 rounded text-[8px] font-bold {{ $ret->status == 'Sudah Dikembalikan' ? 'bg-slate-100 text-slate-700' : 'bg-purple-100 text-purple-700' }}">
                    {{ $ret->status }}
                </span>
            </div>
            <p class="text-xs font-bold text-blue-600">{{ $ret->nama_aset }} ({{ $ret->jumlah_unit }} Unit)</p>
            <div class="flex items-center justify-between text-[9px]">
                <span class="font-semibold text-gray-600">Kondisi: {{ $ret->kondisi_pengembalian ?? 'Baik' }}</span>
                @if(($ret->denda_kerusakan ?? 0) > 0)
                <span class="font-bold text-red-600">Denda: Rp {{ number_format($ret->denda_kerusakan, 0, ',', '.') }}</span>
                @endif
            </div>
            <p class="text-[8px] text-gray-400"><i class="fa-regular fa-calendar mr-1"></i> Dikembalikan: {{ $ret->tanggal_dikembalikan_aktual ? \Carbon\Carbon::parse($ret->tanggal_dikembalikan_aktual)->format('d M Y') : '-' }}</p>
        </div>
        @empty
        <div class="bg-white rounded-xl p-6 text-center text-gray-400 italic text-xs">Belum ada riwayat pengembalian aset.</div>
        @endforelse
    </div>

</div>

<script>
function switchAsetMobileTab(tabId) {
    document.querySelectorAll('.m-aset-tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.m-aset-tab-btn').forEach(btn => btn.className = 'm-aset-tab-btn px-2.5 py-1.5 rounded-lg font-bold text-[9px] shrink-0 bg-white/10 text-gray-300');
    
    document.getElementById(tabId).classList.remove('hidden');
    const activeBtn = document.getElementById(tabId.replace('m-tab-', 'm-tab-btn-'));
    if (activeBtn) activeBtn.className = 'm-aset-tab-btn px-2.5 py-1.5 rounded-lg font-bold text-[9px] shrink-0 bg-blue-600 text-white';
}
</script>
