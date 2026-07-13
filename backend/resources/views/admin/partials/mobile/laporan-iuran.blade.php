<div class="p-3">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-sm font-bold text-gray-800 mb-3">Laporan Iuran</h2>
        @if(isset($list_laporan_iuran) && count($list_laporan_iuran) > 0)
        <div class="space-y-2">
            @foreach($list_laporan_iuran as $item)
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-gray-800 text-[12px] truncate">{{ $item->keterangan }}</p>
                        <p class="text-[9px] text-gray-400 mt-0.5"><i class="fa-regular fa-calendar mr-0.5"></i> {{ date('d/m/Y', strtotime($item->tanggal)) }}</p>
                    </div>
                    <span class="text-[12px] font-bold text-emerald-600 whitespace-nowrap shrink-0">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <p class="text-center py-6 text-gray-500 text-xs">Belum ada data iuran.</p>
        @endif
    </div>
</div>
