<div class="p-3 space-y-3 max-w-full mx-auto">

    {{-- ========== HERO BANNER ========== --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] p-4">
        {{-- Decorative background icon --}}
        <div class="absolute -right-4 -bottom-4 opacity-[0.04]">
            <i class="fa-solid fa-shuffle text-[6rem] text-white transform rotate-12"></i>
        </div>

        <div class="relative z-10 flex flex-col gap-3">
            {{-- Title area --}}
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 text-white/80 text-[8px] font-bold uppercase tracking-[0.2em] px-2.5 py-1 rounded-full">
                    <i class="fa-solid fa-shuffle text-[8px]"></i>
                    RIWAYAT KEUANGAN
                </div>
                <h1 class="text-lg font-black text-white tracking-tight">Riwayat Transaksi Global</h1>
                <p class="text-[11px] text-blue-200/60 font-medium">Semua aktivitas kas masuk & keluar</p>
            </div>

            {{-- Stats badge + Buttons --}}
            <div class="flex items-center gap-2">
                {{-- Stats badge --}}
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center">
                    <p class="text-lg font-black text-white leading-tight">{{ count($list_transaksi) }}</p>
                    <p class="text-[8px] font-bold text-blue-200/50 uppercase tracking-widest">Total Transaksi</p>
                </div>

                {{-- Export buttons --}}
                <div class="flex gap-1.5 ml-auto">
                    <a href="{{ route('export.laporan', ['tipe' => 'all', 'format' => 'excel']) }}"
                       class="bg-white/10 backdrop-blur-md border border-white/10 text-white px-3 py-2 rounded-xl font-bold hover:bg-white/20 transition-all shadow-sm flex items-center text-xs gap-1.5">
                        <i class="fa-solid fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('export.laporan', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank"
                       class="bg-white/15 backdrop-blur-md border border-white/20 text-white px-3 py-2 rounded-xl font-bold hover:bg-white/25 transition-all shadow-sm flex items-center text-xs gap-1.5">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== CARD LIST ========== --}}
    <div class="space-y-2">
        @forelse($list_transaksi as $item)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-gray-800 text-[11px] truncate">{{ $item->keterangan }}</span>
                        <span class="text-[8px] text-gray-400 font-semibold uppercase bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->kategori }}</span>
                    </div>
                    <p class="text-[9px] text-gray-400 mt-1 font-medium"><i class="fa-regular fa-calendar mr-0.5"></i> {{ date('d M Y', strtotime($item->tanggal)) }}</p>
                </div>
                <div class="text-right shrink-0">
                    @if(strtolower($item->jenis) == 'pemasukan')
                        <p class="font-black text-[#16A34A] text-xs">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        <span class="inline-block bg-[#DCFCE7] text-[#16A34A] px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-wider mt-0.5">Masuk</span>
                    @else
                        <p class="font-black text-[#DC2626] text-xs">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        <span class="inline-block bg-[#FEE2E2] text-[#DC2626] px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-wider mt-0.5">Keluar</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada riwayat transaksi...
            </div>
        @endforelse
    </div>

</div>
