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

    {{-- ========== TABLE CARD ========== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3">
        <div class="overflow-x-auto min-h-[150px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-400 text-[9px] uppercase tracking-widest">
                        <th class="px-2.5 py-2 rounded-l-xl font-bold">Tanggal</th>
                        <th class="px-2.5 py-2 font-bold">Keterangan</th>
                        <th class="px-2.5 py-2 font-bold text-center">Tipe</th>
                        <th class="px-2.5 py-2 font-bold text-right rounded-r-xl">Nominal</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse($list_transaksi as $item)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                            <td class="px-2.5 py-2 font-medium text-gray-500 text-[10px] whitespace-nowrap">{{ date('d/m/y', strtotime($item->tanggal)) }}</td>
                            <td class="px-2.5 py-2 font-bold text-gray-800 text-[11px]">
                                <div class="max-w-[120px] truncate">{{ $item->keterangan }}</div>
                                <span class="text-[8px] text-gray-400 font-semibold uppercase">{{ $item->kategori }}</span>
                            </td>

                            @if(strtolower($item->jenis) == 'pemasukan')
                                <td class="px-2.5 py-2 text-center">
                                    <span class="bg-[#DCFCE7] text-[#16A34A] px-2 py-1 rounded-full text-[8px] font-bold uppercase tracking-widest">
                                        <i class="fa-solid fa-arrow-down mr-0.5"></i> Masuk
                                    </span>
                                </td>
                                <td class="px-2.5 py-2 font-black text-[#16A34A] text-right tracking-tight text-[11px] whitespace-nowrap">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            @else
                                <td class="px-2.5 py-2 text-center">
                                    <span class="bg-[#FEE2E2] text-[#DC2626] px-2 py-1 rounded-full text-[8px] font-bold uppercase tracking-widest">
                                        <i class="fa-solid fa-arrow-up mr-0.5"></i> Keluar
                                    </span>
                                </td>
                                <td class="px-2.5 py-2 font-black text-[#DC2626] text-right tracking-tight text-[11px] whitespace-nowrap">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-6">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-folder-open text-2xl mb-2 text-gray-300"></i>
                                    <p class="font-medium italic text-xs">Belum ada riwayat transaksi...</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
