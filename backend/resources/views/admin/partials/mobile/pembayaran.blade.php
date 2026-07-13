<div class="p-3 space-y-3">
    <!-- Header -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <h1 class="text-sm font-black text-gray-800">Payment Gateway</h1>
            <button class="bg-blue-600 text-white px-3 py-1.5 rounded-lg font-bold text-[10px]">+ Tagihan</button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-2">
        <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
            <p class="text-[9px] text-gray-400 font-bold">Total Tagihan</p>
            <h2 class="text-lg font-black text-gray-800 mt-0.5">{{ $tagihan->count() }}</h2>
        </div>
        <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
            <p class="text-[9px] text-gray-400 font-bold">Pending</p>
            <h2 class="text-lg font-black text-yellow-500 mt-0.5">{{ $tagihan->where('status','pending')->count() }}</h2>
        </div>
        <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
            <p class="text-[9px] text-gray-400 font-bold">Success</p>
            <h2 class="text-lg font-black text-green-500 mt-0.5">{{ $tagihan->where('status','success')->count() }}</h2>
        </div>
        <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
            <p class="text-[9px] text-gray-400 font-bold">Income</p>
            <h2 class="text-sm font-black text-blue-600 mt-0.5">Rp{{ number_format($tagihan->sum('jumlah')) }}</h2>
        </div>
    </div>

    <!-- Card List -->
    <div class="space-y-2">
        <h2 class="text-xs font-black text-gray-800 px-1">Daftar Pembayaran</h2>
        @forelse($tagihan as $item)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-gray-800 text-[11px] truncate">{{ $item->nama }}</span>
                        <span class="text-[8px] text-gray-400 font-semibold bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->invoice_id }}</span>
                    </div>
                    <p class="text-[10px] text-gray-500 font-medium mt-0.5">{{ $item->jenis }}</p>
                </div>
                <div class="text-right shrink-0 flex flex-col items-end gap-1">
                    <p class="font-black text-blue-600 text-xs">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</p>
                    @if($item->status == 'success')
                        <span class="bg-green-100 text-green-600 px-1.5 py-0.5 rounded text-[8px] font-bold">SUCCESS</span>
                    @elseif($item->status == 'failed')
                        <span class="bg-red-100 text-red-600 px-1.5 py-0.5 rounded text-[8px] font-bold">FAILED</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-600 px-1.5 py-0.5 rounded text-[8px] font-bold">PENDING</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada data pembayaran.
            </div>
        @endforelse
    </div>
</div>
