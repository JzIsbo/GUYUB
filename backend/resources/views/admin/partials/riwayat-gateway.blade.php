<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Gateway</h2>
            <p class="text-sm text-gray-500">Log notifikasi webhook (callback) dari Payment Gateway secara real-time</p>
        </div>
        <button onclick="bersihkanLogGateway()" class="bg-red-50 text-red-600 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-100 transition shadow-sm">
            <i class="fa-solid fa-trash-can mr-1"></i> Bersihkan Log
        </button>
    </div>

    <div class="bg-slate-900 rounded-[2rem] shadow-xl border border-slate-800 overflow-hidden p-6 max-h-[700px] overflow-y-auto">
        <div class="space-y-6 font-mono text-sm">
            @forelse($logs as $log)
            <div class="border-b border-slate-800 pb-4 last:border-0 last:pb-0">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    @if($log->status_code == '200')
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded text-xs font-bold">[{{ $log->status_code }} OK]</span>
                    @else
                        <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-0.5 rounded text-xs font-bold">[{{ $log->status_code }} ERROR]</span>
                    @endif

                    <span class="text-slate-500 text-xs">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</span>
                    <span class="text-blue-400 font-bold text-xs">{{ $log->method }}</span>
                    <span class="text-slate-400 text-xs">{{ $log->endpoint }}</span>
                    @if($log->order_id)
                        <span class="text-amber-400 text-xs font-bold">Order ID: {{ $log->order_id }}</span>
                    @endif
                </div>
                <pre class="text-xs text-slate-300 bg-slate-950 p-4 rounded-xl overflow-x-auto border border-slate-800/50 leading-relaxed">{{ $log->payload }}</pre>
            </div>
            @empty
            <div class="text-center py-12 text-slate-500">
                <i class="fa-solid fa-terminal text-4xl mb-3 block"></i>
                Belum ada lalu lintas data webhook dari Payment Gateway.
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function bersihkanLogGateway() {
        if (!confirm('Apakah Anda yakin ingin menghapus seluruh log riwayat gateway? Tindakan ini tidak dapat dibatalkan.')) return;

        fetch('/payment/logs/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            // Refresh halaman parsial via AJAX
            if(typeof switchPage === 'function') {
                switchPage('riwayat-gateway', document.querySelector('.menu-active'));
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            alert('Gagal membersihkan log.');
            console.error(error);
        });
    }
</script>
