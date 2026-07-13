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

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">
        <h2 class="text-xs font-black text-gray-800 mb-2">Daftar Pembayaran</h2>
        <div class="overflow-x-auto -mx-3">
            <table class="w-full min-w-[350px]">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 px-3 text-[9px] text-gray-400 uppercase">Invoice</th>
                        <th class="text-left py-2 px-3 text-[9px] text-gray-400 uppercase">Nama</th>
                        <th class="text-left py-2 px-3 text-[9px] text-gray-400 uppercase">Jumlah</th>
                        <th class="text-left py-2 px-3 text-[9px] text-gray-400 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tagihan as $item)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 px-3 font-bold text-gray-700 text-[10px] truncate max-w-[70px]">{{ $item->invoice_id }}</td>
                        <td class="py-2 px-3 text-[10px] truncate max-w-[70px]">{{ $item->nama }}</td>
                        <td class="py-2 px-3 font-bold text-blue-600 text-[10px] whitespace-nowrap">Rp{{ number_format($item->jumlah) }}</td>
                        <td class="py-2 px-3">
                            @if($item->status == 'success')
                                <span class="bg-green-100 text-green-600 px-2 py-0.5 rounded text-[8px] font-bold">OK</span>
                            @elseif($item->status == 'failed')
                                <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-[8px] font-bold">FAIL</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded text-[8px] font-bold">PEND</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
