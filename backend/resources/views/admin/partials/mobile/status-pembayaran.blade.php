<div class="p-3">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-sm font-bold text-gray-800">Status Pembayaran</h2>
            <p class="text-[10px] text-gray-500">Pantau transaksi Pending/Expired</p>
        </div>
        <button onclick="refreshDataPembayaran()" class="bg-white border border-gray-200 text-gray-600 px-3 py-1.5 rounded-lg font-bold text-[10px] flex items-center gap-1">
            <i class="fa-solid fa-rotate-right text-[8px]"></i> Sync
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[400px]">
                <thead class="bg-gray-50 text-[9px] text-gray-600 uppercase tracking-wider">
                    <tr>
                        <th class="px-3 py-2">Order ID</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Nominal</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[10px]">
                    @forelse($payments as $pay)
                    <tr>
                        <td class="px-3 py-2 font-mono text-gray-500 font-bold truncate max-w-[80px]">{{ $pay->order_id }}</td>
                        <td class="px-3 py-2 font-bold text-gray-800 truncate max-w-[80px]">{{ $pay->nama_pembayar }}</td>
                        <td class="px-3 py-2 font-bold text-gray-800 whitespace-nowrap">{{ number_format($pay->nominal, 0, ',', '.') }}</td>
                        <td class="px-3 py-2">
                            @if(strtolower($pay->status) == 'settlement' || strtolower($pay->status) == 'success')
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-green-100 text-green-600">OK</span>
                            @elseif(strtolower($pay->status) == 'pending')
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-yellow-100 text-yellow-600">PENDING</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-red-100 text-red-600">{{ strtoupper($pay->status) }}</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-gray-500 text-[9px] whitespace-nowrap">{{ \Carbon\Carbon::parse($pay->created_at)->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400 text-xs">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function refreshDataPembayaran() {
        let btn = document.querySelector('button[onclick="refreshDataPembayaran()"]');
        let icon = btn ? btn.querySelector('i') : null;
        if (icon) icon.classList.add('fa-spin');
        if (btn) btn.disabled = true;
        fetch('/payment/sync', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => { if (!response.ok) throw new Error('Network error'); return response.json(); })
        .then(data => { alert(data.message); if (typeof switchPage === 'function') { switchPage('status-pembayaran', document.querySelector('.menu-active')); } else { window.location.reload(); } })
        .catch(error => { alert('Gagal sinkronisasi!'); if (icon) icon.classList.remove('fa-spin'); if (btn) btn.disabled = false; });
    }
</script>
