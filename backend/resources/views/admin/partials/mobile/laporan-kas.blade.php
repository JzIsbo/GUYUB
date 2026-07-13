<div class="p-3">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-sm font-bold text-gray-800 mb-3">Laporan Arus Kas</h2>
        <div class="overflow-x-auto -mx-4">
            <table class="w-full text-[10px] text-left min-w-[400px]">
                <thead class="bg-gray-50 text-[9px] text-gray-600 uppercase">
                    <tr>
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">Uraian</th>
                        <th class="px-3 py-2 text-right">Masuk</th>
                        <th class="px-3 py-2 text-right">Keluar</th>
                        <th class="px-3 py-2 text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_kas as $item)
                    <tr class="border-b">
                        <td class="px-3 py-2 whitespace-nowrap">{{ date('d/m/y', strtotime($item->tanggal)) }}</td>
                        <td class="px-3 py-2 truncate max-w-[100px]">{{ $item->keterangan }}</td>
                        <td class="px-3 py-2 text-right text-emerald-600 whitespace-nowrap">{{ $item->jenis == 'pemasukan' ? number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right text-red-600 whitespace-nowrap">{{ $item->jenis == 'pengeluaran' ? number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-2 text-right font-bold whitespace-nowrap">{{ number_format($item->saldo_akhir, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
