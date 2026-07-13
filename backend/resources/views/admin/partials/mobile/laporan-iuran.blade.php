<div class="p-3">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-sm font-bold text-gray-800 mb-3">Laporan Iuran</h2>
        @if(isset($list_laporan_iuran) && count($list_laporan_iuran) > 0)
        <div class="overflow-x-auto -mx-4">
            <table class="w-full text-[10px] text-left">
                <thead class="text-[9px] text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">Keterangan</th>
                        <th class="px-3 py-2 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_laporan_iuran as $item)
                    <tr class="border-b">
                        <td class="px-3 py-2 whitespace-nowrap">{{ date('d/m/y', strtotime($item->tanggal)) }}</td>
                        <td class="px-3 py-2 truncate max-w-[120px]">{{ $item->keterangan }}</td>
                        <td class="px-3 py-2 text-right font-bold text-emerald-600 whitespace-nowrap">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-center py-6 text-gray-500 text-xs">Belum ada data iuran.</p>
        @endif
    </div>
</div>
