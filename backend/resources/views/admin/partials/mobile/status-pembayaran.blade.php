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

    <!-- Card List -->
    <div class="space-y-2">
        @forelse($payments as $pay)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-gray-800 text-[11px] truncate">{{ $pay->nama_pembayar }}</span>
                        <span class="text-[8px] text-gray-400 font-semibold bg-gray-100 px-1.5 py-0.5 rounded font-mono">{{ $pay->order_id }}</span>
                    </div>
                    <p class="text-[10px] text-gray-500 font-medium mt-0.5">{{ $pay->metode_pembayaran ?? 'Belum Dipilih' }}</p>
                    <p class="text-[9px] text-gray-400 mt-1 font-medium"><i class="fa-regular fa-clock mr-0.5"></i> {{ \Carbon\Carbon::parse($pay->created_at)->diffForHumans() }}</p>
                </div>
                <div class="text-right shrink-0 flex flex-col items-end gap-1">
                    <p class="font-black text-gray-800 text-xs">Rp {{ number_format($pay->nominal, 0, ',', '.') }}</p>
                    @if(strtolower($pay->status) == 'settlement' || strtolower($pay->status) == 'success')
                        <span class="bg-green-50 text-green-600 px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider">Berhasil</span>
                    @elseif(strtolower($pay->status) == 'pending')
                        <span class="bg-yellow-50 text-yellow-600 px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider">Pending</span>
                    @else
                        <span class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider">{{ strtoupper($pay->status) }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada transaksi pembayaran online.
            </div>
        @endforelse
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
