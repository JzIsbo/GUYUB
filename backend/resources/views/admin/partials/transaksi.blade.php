<div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)]">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Riwayat Transaksi Global</h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Semua aktivitas kas masuk & keluar</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('export.laporan', ['tipe' => 'all', 'format' => 'excel']) }}"
               class="bg-[#EFF6FF] text-[#2563EB] px-5 py-3 rounded-2xl font-bold hover:scale-[1.03] transition shadow-sm flex items-center text-sm">
                <i class="fa-solid fa-file-excel mr-2"></i> Export Excel
            </a>
            <a href="{{ route('export.laporan', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank"
               class="bg-slate-800 text-white px-5 py-3 rounded-2xl font-bold hover:scale-[1.03] transition shadow-sm flex items-center text-sm">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>
    </div>

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
