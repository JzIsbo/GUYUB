<div class="p-3">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-sm font-bold text-gray-800">Riwayat Gateway</h2>
            <p class="text-[10px] text-gray-500">Log webhook Payment Gateway</p>
        </div>
        <button onclick="bersihkanLogGateway()" class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold text-[10px]">
            <i class="fa-solid fa-trash-can mr-1"></i> Hapus Log
        </button>
    </div>

    <div class="bg-slate-900 rounded-xl shadow-xl border border-slate-800 overflow-hidden p-3 max-h-[500px] overflow-y-auto">
        <div class="space-y-3 font-mono text-[10px]">
            @forelse($logs as $log)
            <div class="border-b border-slate-800 pb-3 last:border-0 last:pb-0">
                <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                    @if($log->status_code == '200')
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-1.5 py-0.5 rounded text-[9px] font-bold">[{{ $log->status_code }}]</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-1.5 py-0.5 rounded text-[9px] font-bold">[{{ $log->status_code }}]</span>
                    @endif
                    <span class="text-slate-500 text-[9px]">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m H:i') }}</span>
                    <span class="text-blue-400 font-bold text-[9px]">{{ $log->method }}</span>
                    @if($log->order_id)
                        <span class="text-amber-400 text-[9px] font-bold">{{ $log->order_id }}</span>
                    @endif
                </div>
                <pre class="text-[9px] text-slate-300 bg-slate-950 p-2 rounded-lg overflow-x-auto border border-slate-800/50 leading-relaxed">{{ $log->payload }}</pre>
            </div>
            @empty
            <div class="text-center py-6 text-slate-500 text-xs">
                <i class="fa-solid fa-terminal text-2xl mb-2 block"></i>
                Belum ada log webhook.
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function bersihkanLogGateway() {
        if (!confirm('Yakin ingin menghapus seluruh log?')) return;
        fetch('/payment/logs/clear', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => { alert(data.message); if(typeof switchPage === 'function') { switchPage('riwayat-gateway', document.querySelector('.menu-active')); } else { window.location.reload(); } })
        .catch(error => { alert('Gagal membersihkan log.'); });
    }
</script>
