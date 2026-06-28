<div class="p-6 bg-white rounded-[2rem] shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Laporan Keuangan</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'excel']) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Keterangan</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Jenis</th>
                    <th class="px-6 py-3 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list_transaksi as $t)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ date('d M Y', strtotime($t->tanggal)) }}</td>
                    <td class="px-6 py-4">{{ $t->keterangan }}</td>
                    <td class="px-6 py-4">{{ $t->kategori }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-lg {{ $t->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($t->jenis) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
