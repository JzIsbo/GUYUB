<div class="p-6 bg-white rounded-[2rem] shadow-sm border border-gray-100">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Laporan Arus Kas RT</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Uraian</th>
                    <th class="px-6 py-3 text-right">Masuk</th>
                    <th class="px-6 py-3 text-right">Keluar</th>
                    <th class="px-6 py-3 text-right font-bold">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list_kas as $item)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                    <td class="px-6 py-4">{{ $item->keterangan }}</td>
                    <td class="px-6 py-4 text-right text-emerald-600">
                        {{ $item->jenis == 'pemasukan' ? number_format($item->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-right text-red-600">
                        {{ $item->jenis == 'pengeluaran' ? number_format($item->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-right font-bold">
                        Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
