<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    {{-- ========== HERO BANNER ========== --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] p-8 lg:p-10">
        {{-- Decorative background icon --}}
        <div class="absolute -right-6 -bottom-6 opacity-[0.04]">
            <i class="fa-solid fa-shuffle text-[12rem] text-white transform rotate-12"></i>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
            {{-- Left: Title area --}}
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 text-white/80 text-[10px] font-bold uppercase tracking-[0.2em] px-4 py-2 rounded-full">
                    <i class="fa-solid fa-shuffle text-[10px]"></i>
                    RIWAYAT KEUANGAN
                </div>
                <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight">Riwayat Transaksi Global</h1>
                <p class="text-sm text-blue-200/60 font-medium">Semua aktivitas kas masuk & keluar</p>
            </div>

            {{-- Right: Stats badge + Buttons --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                {{-- Stats badge --}}
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center">
                    <p class="text-2xl font-black text-white">{{ count($list_transaksi) }}</p>
                    <p class="text-[10px] font-bold text-blue-200/50 uppercase tracking-widest">Total Transaksi</p>
                </div>

                {{-- Export buttons --}}
                <div class="flex gap-2">
                    <a href="{{ route('export.laporan', ['tipe' => 'all', 'format' => 'excel']) }}"
                       class="bg-white/10 backdrop-blur-md border border-white/10 text-white px-5 py-3 rounded-2xl font-bold hover:bg-white/20 hover:scale-[1.03] transition-all shadow-sm flex items-center text-sm gap-2">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('export.laporan', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank"
                       class="bg-white/15 backdrop-blur-md border border-white/20 text-white px-5 py-3 rounded-2xl font-bold hover:bg-white/25 hover:scale-[1.03] transition-all shadow-sm flex items-center text-sm gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== TABLE CARD ========== --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 lg:p-8">
        <div class="overflow-x-auto min-h-[200px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-400 text-[10px] uppercase tracking-widest">
                        <th class="p-4 rounded-l-2xl font-bold">Tanggal</th>
                        <th class="p-4 font-bold">Keterangan</th>
                        <th class="p-4 font-bold">Kategori</th>
                        <th class="p-4 font-bold text-center">Tipe</th>
                        <th class="p-4 font-bold text-right rounded-r-2xl">Nominal</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($list_transaksi as $item)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                            <td class="p-4 font-medium text-gray-500 text-xs">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                            <td class="p-4 font-bold text-gray-800">{{ $item->keterangan }}</td>
                            <td class="p-4">
                                <span class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">{{ $item->kategori }}</span>
                            </td>

                            @if(strtolower($item->jenis) == 'pemasukan')
                                <td class="p-4 text-center">
                                    <span class="bg-[#DCFCE7] text-[#16A34A] px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fa-solid fa-arrow-down mr-1"></i> Masuk
                                    </span>
                                </td>
                                <td class="p-4 font-black text-[#16A34A] text-right tracking-tight">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            @else
                                <td class="p-4 text-center">
                                    <span class="bg-[#FEE2E2] text-[#DC2626] px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> Keluar
                                    </span>
                                </td>
                                <td class="p-4 font-black text-[#DC2626] text-right tracking-tight">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-10">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                    <p class="font-medium italic">Belum ada riwayat transaksi...</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
