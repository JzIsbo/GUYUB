@php
    $totalMasuk = $list_kas->where('jenis', 'pemasukan')->sum('nominal');
    $totalKeluar = $list_kas->where('jenis', 'pengeluaran')->sum('nominal');
    $saldoAkhir = count($list_kas) > 0 ? $list_kas->last()->saldo_akhir : 0;
@endphp

<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg border border-white/10">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <i class="fa-solid fa-coins absolute -bottom-4 -right-2 text-[70px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-2.5">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-5 h-5 rounded-md bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-trend-up text-blue-300 text-[9px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-blue-300/80">Laporan Arus Kas</span>
                </div>
                <h1 class="text-base font-black tracking-tight leading-tight">Laporan Arus Kas RT</h1>
                <p class="text-[10px] text-white/60 font-medium">Jurnal mutasi kas dan perkembangan saldo.</p>
            </div>

            <!-- Quick Stats Cards (Mobile) -->
            <div class="grid grid-cols-3 gap-1.5 text-center">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-emerald-300/70 uppercase">Masuk</p>
                    <p class="stat-counter text-[10px] font-black text-emerald-400" data-value="{{ $totalMasuk }}" data-type="currency">Rp 0</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-red-300/70 uppercase">Keluar</p>
                    <p class="stat-counter text-[10px] font-black text-red-400" data-value="{{ $totalKeluar }}" data-type="currency">Rp 0</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-indigo-300/70 uppercase">Saldo</p>
                    <p class="stat-counter text-[10px] font-black text-indigo-300" data-value="{{ $saldoAkhir }}" data-type="currency">Rp 0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Action Buttons (Mobile) -->
    <div class="grid grid-cols-2 gap-2 px-1">
        <a href="{{ route('admin.export', ['tipe' => 'kas', 'format' => 'excel']) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl text-[10px] shadow-sm flex items-center justify-center gap-1">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('admin.export', ['tipe' => 'kas', 'format' => 'pdf']) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 rounded-xl text-[10px] shadow-sm flex items-center justify-center gap-1">
            <i class="fa-solid fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    <!-- Card List of Cash Movement -->
    <div class="space-y-2">
        <h3 class="text-xs font-black text-gray-800 px-1">Jurnal Mutasi Kas</h3>

        @forelse($list_kas as $item)
        <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex flex-col gap-1.5">
            <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase {{ $item->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $item->jenis }}
                </span>
                <span class="text-[9px] text-gray-400 font-medium"><i class="fa-regular fa-calendar mr-1"></i> {{ date('d M Y', strtotime($item->tanggal)) }}</span>
            </div>
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] text-gray-700 font-bold leading-tight">{{ $item->keterangan }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($item->jenis == 'pemasukan')
                        <span class="text-[10px] font-black text-emerald-600">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        @else
                        <span class="text-[10px] font-black text-red-600">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
                <div class="text-right shrink-0 ml-2">
                    <p class="text-[8px] text-gray-400 uppercase font-bold">Saldo Akhir</p>
                    <p class="text-[11px] font-black text-indigo-700">Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl p-6 text-center text-gray-400 italic text-xs">Belum ada mutasi arus kas tercatat.</div>
        @endforelse
    </div>

</div>
