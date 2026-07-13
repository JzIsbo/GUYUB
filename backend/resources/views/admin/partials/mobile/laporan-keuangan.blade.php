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
        <div class="space-y-2">
            @foreach($list_transaksi as $t)
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-gray-800 text-[12px] truncate">{{ $t->keterangan }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="text-[9px] text-gray-400"><i class="fa-regular fa-calendar mr-0.5"></i> {{ date('d/m/Y', strtotime($t->tanggal)) }}</p>
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold {{ $t->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($t->jenis) }}</span>
                        </div>
                    </div>
                    <span class="text-[12px] font-bold whitespace-nowrap shrink-0 {{ $t->jenis == 'pemasukan' ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $t->jenis == 'pemasukan' ? '+' : '-' }}Rp {{ number_format($t->nominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
