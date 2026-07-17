@php
    $totalNominalIuran = isset($list_laporan_iuran) ? $list_laporan_iuran->sum('nominal') : 0;
    $totalJumlahIuran = isset($list_laporan_iuran) ? count($list_laporan_iuran) : 0;
@endphp

<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg border border-white/10">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <i class="fa-solid fa-receipt absolute -bottom-4 -right-2 text-[70px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-2.5">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-5 h-5 rounded-md bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-hand-holding-dollar text-blue-300 text-[9px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-blue-300/80">Laporan Iuran Warga</span>
                </div>
                <h1 class="text-base font-black tracking-tight leading-tight">Laporan Iuran RT</h1>
                <p class="text-[10px] text-white/60 font-medium">Histori pembayaran iuran terverifikasi.</p>
            </div>

            <!-- Quick Stats Cards (Mobile) -->
            <div class="grid grid-cols-2 gap-1.5 text-center">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-emerald-300/70 uppercase">Total Nominal</p>
                    <p class="stat-counter text-[10px] font-black text-emerald-400" data-value="{{ $totalNominalIuran }}" data-type="currency">Rp 0</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-blue-300/70 uppercase">Total Setoran</p>
                    <p class="text-[10px] font-black text-blue-300">{{ number_format($totalJumlahIuran, 0, ',', '.') }} Transaksi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Action Buttons (Mobile) -->
    <div class="grid grid-cols-2 gap-2 px-1">
        <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'excel']) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl text-[10px] shadow-sm flex items-center justify-center gap-1">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'pdf']) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 rounded-xl text-[10px] shadow-sm flex items-center justify-center gap-1">
            <i class="fa-solid fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    <!-- Card List of Iuran -->
    <div class="space-y-2">
        <h3 class="text-xs font-black text-gray-800 px-1">Histori Pembayaran Iuran</h3>

        @forelse($list_laporan_iuran ?? [] as $item)
        <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center justify-between gap-2">
            <div class="min-w-0 flex-1">
                <p class="font-bold text-gray-800 text-[11px] leading-tight">{{ $item->keterangan }}</p>
                <p class="text-[9px] text-gray-400 mt-1"><i class="fa-regular fa-calendar mr-1"></i> {{ date('d M Y', strtotime($item->tanggal)) }}</p>
            </div>
            <span class="text-[11px] font-black text-emerald-600 whitespace-nowrap shrink-0">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
        </div>
        @empty
        <div class="bg-white rounded-xl p-6 text-center text-gray-400 italic text-xs">Belum ada data iuran yang ditemukan.</div>
        @endforelse
    </div>

</div>
