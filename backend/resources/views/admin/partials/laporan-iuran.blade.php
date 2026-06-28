<div class="p-6 bg-white rounded-[2rem] shadow-sm border border-gray-100">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Laporan Pembayaran Iuran</h2>

    @if(isset($list_laporan_iuran) && count($list_laporan_iuran) > 0)
    <table class="w-full text-sm text-left">
        <thead class="text-xs text-gray-500 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Keterangan</th>
                <th class="px-6 py-3 text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list_laporan_iuran as $item)
            <tr class="border-b">
                <td class="px-6 py-4">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                <td class="px-6 py-4">{{ $item->keterangan }}</td>
                <td class="px-6 py-4 text-right font-bold text-emerald-600">
                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="text-center py-10 text-gray-500">Belum ada data iuran yang ditemukan di transaksi.</p>
    @endif
</div>
