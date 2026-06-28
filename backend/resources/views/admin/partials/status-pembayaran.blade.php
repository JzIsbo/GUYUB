<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Status Pembayaran</h2>
            <p class="text-sm text-gray-500">Pantau transaksi warga yang sedang dalam proses (Pending / Expired)</p>
        </div>
        <button onclick="refreshDataPembayaran()" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-gray-50 transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-rotate-right"></i> Sinkronisasi
        </button>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="p-5">Order ID</th>
                    <th class="p-5">Nama Warga</th>
                    <th class="p-5">Metode</th>
                    <th class="p-5">Nominal</th>
                    <th class="p-5">Status Gateway</th>
                    <th class="p-5 text-center">Waktu Dibuat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($payments as $pay)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-5 font-mono text-gray-500 font-bold">{{ $pay->order_id }}</td>
                    <td class="p-5 font-bold text-gray-800">{{ $pay->nama_pembayar }}</td>
                    <td class="p-5 text-gray-600">{{ $pay->metode_pembayaran ?? 'Belum Dipilih' }}</td>
                    <td class="p-5 font-bold text-gray-800">Rp {{ number_format($pay->nominal, 0, ',', '.') }}</td>
                    <td class="p-5">
                        @if(strtolower($pay->status) == 'settlement' || strtolower($pay->status) == 'success')
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600 border border-green-200">BERHASIL</span>
                        @elseif(strtolower($pay->status) == 'pending')
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-600 border border-yellow-200">PENDING</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 border border-red-200">{{ strtoupper($pay->status) }}</span>
                        @endif
                    </td>
                    <td class="p-5 text-center text-gray-500 text-xs">
                        {{ \Carbon\Carbon::parse($pay->created_at)->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-400 font-medium">Belum ada transaksi pembayaran online.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function refreshDataPembayaran() {
        let btn = document.querySelector('button[onclick="refreshDataPembayaran()"]');
        let icon = btn ? btn.querySelector('i') : null;

        // 1. Nyalakan animasi loading muter
        if (icon) icon.classList.add('fa-spin');
        if (btn) btn.disabled = true;

        // 2. Tembak data ke Controller
        fetch('/payment/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                // Gunakan fungsi bawaan Laravel langsung agar tidak error mencari meta tag
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            // 3. Munculkan pesan sukses
            alert(data.message);

            // 4. Refresh tampilan tabel secara otomatis
            if (typeof switchPage === 'function') {
                let activeMenu = document.querySelector('.menu-active') || document.querySelector('[onclick*="status-pembayaran"]');
                if (activeMenu) {
                    switchPage('status-pembayaran', activeMenu);
                } else {
                    window.location.reload(); // Fallback jika menu tidak terdeteksi
                }
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            alert('Gagal melakukan sinkronisasi! Pastikan route /payment/sync sudah dibuat di web.php.');
            console.error('Error details:', error);

            // Matikan animasi loading jika gagal
            if (icon) icon.classList.remove('fa-spin');
            if (btn) btn.disabled = false;
        });
    }
</script>
