<div class="p-3">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-sm font-bold text-gray-800 mb-3">Laporan Arus Kas</h2>
        <div class="space-y-2">
            @foreach($list_kas as $item)
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-gray-800 text-[12px] truncate">{{ $item->keterangan }}</p>
                        <p class="text-[9px] text-gray-400 mt-0.5"><i class="fa-regular fa-calendar mr-0.5"></i> {{ date('d/m/Y', strtotime($item->tanggal)) }}</p>
                        <div class="flex items-center gap-3 mt-1.5">
                            @if($item->jenis == 'pemasukan')
                            <span class="text-[10px] font-bold text-emerald-600"><i class="fa-solid fa-arrow-up text-[8px] mr-0.5"></i> +Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                            @else
                            <span class="text-[10px] font-bold text-red-500"><i class="fa-solid fa-arrow-down text-[8px] mr-0.5"></i> -Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[8px] text-gray-400 uppercase font-bold">Saldo</p>
                        <p class="text-[12px] font-black text-gray-800">{{ number_format($item->saldo_akhir, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
