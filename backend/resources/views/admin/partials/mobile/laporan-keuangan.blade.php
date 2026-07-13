<div class="p-3">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-gray-800">Laporan Keuangan</h2>
            <div class="flex gap-1">
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'excel']) }}" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-[10px] font-bold flex items-center gap-1">
                    <i class="fa-solid fa-file-excel text-[8px]"></i> Excel
                </a>
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg text-[10px] font-bold flex items-center gap-1">
                    <i class="fa-solid fa-file-pdf text-[8px]"></i> PDF
                </a>
            </div>
        </div>
        <div class="overflow-x-auto -mx-4">
            <table class="w-full text-[10px] text-left min-w-[380px]">
                <thead class="text-[9px] text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">Keterangan</th>
                        <th class="px-3 py-2">Jenis</th>
                        <th class="px-3 py-2 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_transaksi as $t)
                    <tr class="bg-white border-b">
                        <td class="px-3 py-2 whitespace-nowrap">{{ date('d/m/y', strtotime($t->tanggal)) }}</td>
                        <td class="px-3 py-2 truncate max-w-[100px]">{{ $t->keterangan }}</td>
                        <td class="px-3 py-2">
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold {{ $t->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($t->jenis) }}</span>
                        </td>
                        <td class="px-3 py-2 text-right font-bold whitespace-nowrap">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
