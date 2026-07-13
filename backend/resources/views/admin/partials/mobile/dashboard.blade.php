<!-- resources/views/admin/partials/mobile/dashboard.blade.php -->
<div class="space-y-5 max-w-[600px] mx-auto pb-8">

    <!-- Compact Gradient Info Card -->
    <div class="bg-gradient-to-br from-[#1E40AF] to-[#0F172A] p-6 rounded-[1.8rem] shadow-lg text-white relative overflow-hidden flex flex-col justify-between min-h-[160px]">
        <div class="relative z-10">
            <p class="text-blue-200 text-xs font-semibold tracking-wide">Saldo Kas Saat Ini</p>
            <h2 class="text-3xl font-black mt-1 tracking-tight">Rp {{ number_format($saldo_bersih ?? 0, 0, ',', '.') }}</h2>
            <div class="flex items-center gap-1.5 mt-2 bg-white/10 w-fit px-2.5 py-1 rounded-full border border-white/5">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-[10px] text-blue-200 font-semibold tracking-wide uppercase">Kas Utama RT / RW</span>
            </div>
        </div>
        <i class="fa-solid fa-wallet absolute -bottom-6 -right-6 text-9xl opacity-5 rotate-12"></i>
    </div>

    <!-- Quick Actions (2x2 Grid) -->
    <div class="bg-white p-5 rounded-[1.8rem] border border-gray-100 shadow-sm">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3.5">Aksi Cepat</h4>
        <div class="grid grid-cols-2 gap-3">
            <button onclick="switchPage('pemasukan', document.querySelector('.menu-link[onclick*=\'pemasukan\\\']'))" class="bg-[#ECFDF5] border border-emerald-100 text-[#059669] rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-sm">
                <i class="fa-solid fa-circle-plus text-xl mb-1.5"></i>
                <span class="text-[10px]">Pemasukan</span>
            </button>
            <button onclick="switchPage('pengeluaran', document.querySelector('.menu-link[onclick*=\'pengeluaran\\\']'))" class="bg-[#FEF2F2] border border-red-100 text-[#DC2626] rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-sm">
                <i class="fa-solid fa-circle-minus text-xl mb-1.5"></i>
                <span class="text-[10px]">Pengeluaran</span>
            </button>
            <button onclick="switchPage('data-warga', document.querySelector('.menu-link[onclick*=\'data-warga\\\']'))" class="bg-[#EFF6FF] border border-blue-100 text-[#2563EB] rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-sm">
                <i class="fa-solid fa-user-plus text-xl mb-1.5"></i>
                <span class="text-[10px]">Tambah Warga</span>
            </button>
            <button onclick="switchPage('laporan-keuangan', document.querySelector('.menu-link[onclick*=\'laporan-keuangan\\\']'))" class="bg-[#FEF3C7] border border-yellow-100 text-[#D97706] rounded-2xl font-bold flex flex-col items-center justify-center p-3 hover:scale-[1.02] active:scale-95 transition-all shadow-sm">
                <i class="fa-solid fa-file-invoice text-xl mb-1.5"></i>
                <span class="text-[10px]">Laporan</span>
            </button>
        </div>
    </div>

    <!-- Compact Stats Grid (2x2 Layout) -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Card 1 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-[#DCFCE7] w-8 h-8 rounded-lg text-[#16A34A] flex items-center justify-center shadow-inner"><i class="fa-solid fa-download text-sm"></i></div>
                <span class="bg-green-50 text-green-600 text-[8px] px-1.5 py-0.5 rounded font-black border border-green-100">Masuk</span>
            </div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider mb-0.5">Total Pemasukan</p>
            <h3 class="text-sm font-black text-gray-800 tracking-tight">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</h3>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-[#FEE2E2] w-8 h-8 rounded-lg text-[#DC2626] flex items-center justify-center shadow-inner"><i class="fa-solid fa-upload text-sm"></i></div>
                <span class="bg-red-50 text-red-600 text-[8px] px-1.5 py-0.5 rounded font-black border border-red-100">Keluar</span>
            </div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider mb-0.5">Total Pengeluaran</p>
            <h3 class="text-sm font-black text-gray-800 tracking-tight">Rp {{ number_format($pengeluaran ?? 0, 0, ',', '.') }}</h3>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-[#EFF6FF] w-8 h-8 rounded-lg text-[#2563EB] flex items-center justify-center shadow-inner"><i class="fa-solid fa-wallet text-sm"></i></div>
            </div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider mb-0.5">Kas Global</p>
            <h3 class="text-sm font-black text-gray-800 tracking-tight">Rp {{ number_format($saldo_bersih ?? 0, 0, ',', '.') }}</h3>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-2.5">
                <div class="bg-[#FEF3C7] w-8 h-8 rounded-lg text-[#D97706] flex items-center justify-center shadow-inner"><i class="fa-solid fa-users text-sm"></i></div>
            </div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider mb-0.5">Total Warga</p>
            <h3 class="text-sm font-black text-gray-800 tracking-tight">{{ $warga ?? 128 }} Warga</h3>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="bg-white p-5 rounded-[1.8rem] border border-gray-100 shadow-sm flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-black text-gray-800 text-xs uppercase tracking-widest">Grafik Kas</h3>
            <div class="flex gap-2.5">
                <div class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span><span class="text-[8px] text-gray-400 font-bold uppercase">Masuk</span></div>
                <div class="flex items-center gap-1"><span class="w-2 h-2 bg-red-500 rounded-full"></span><span class="text-[8px] text-gray-400 font-bold uppercase">Keluar</span></div>
            </div>
        </div>
        <div class="relative w-full h-[180px]">
            <canvas id="kasChartMobile"></canvas>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white p-5 rounded-[1.8rem] border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-black text-gray-800 text-xs uppercase tracking-widest">Transaksi Terbaru</h3>
            <a href="javascript:void(0)" onclick="switchPage('transaksi', document.querySelector('.menu-link[onclick*=\'transaksi\\\']'))" class="text-blue-600 text-[9px] font-black hover:underline uppercase tracking-wider">Semua</a>
        </div>

        <div class="space-y-4">
            @forelse($transaksi_terbaru as $item)
                <div class="flex justify-between items-center pb-3 border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors rounded-xl px-1.5 -mx-1.5">
                    <div class="flex items-center gap-3">
                        @if($item->jenis == 'pemasukan')
                            <div class="bg-[#DCFCE7] text-[#16A34A] w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-arrow-down text-xs"></i>
                            </div>
                        @else
                            <div class="bg-[#FEE2E2] text-[#DC2626] w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-arrow-up text-xs"></i>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $item->keterangan }}</p>
                            <p class="text-[8px] text-gray-400 font-black uppercase mt-0.5">{{ $item->kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if($item->jenis == 'pemasukan')
                            <p class="text-xs font-black text-[#16A34A] tracking-tight">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @else
                            <p class="text-xs font-black text-[#DC2626] tracking-tight">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @endif
                        <p class="text-[8px] text-gray-400 font-bold mt-0.5">{{ date('d M Y', strtotime($item->tanggal)) }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-3 text-xs italic">Belum ada transaksi terbaru.</div>
            @endforelse
        </div>
    </div>

</div>

<script>
    window.renderDashboardMobile = function() {
        const canvas = document.getElementById('kasChartMobile');
        if(!canvas) return;

        if(window.kasChartMobileInstance) {
            window.kasChartMobileInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        window.kasChartMobileInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Masuk',
                        data: {!! json_encode($chart_pemasukan ?? array_fill(0,12,0)) !!},
                        borderColor: '#22c55e', backgroundColor: 'rgba(34, 197, 94, 0.05)', tension: 0.4, fill: true, borderWidth: 2, pointRadius: 1
                    },
                    {
                        label: 'Keluar',
                        data: {!! json_encode($chart_pengeluaran ?? array_fill(0,12,0)) !!},
                        borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.05)', tension: 0.4, fill: true, borderWidth: 2, pointRadius: 1
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 8 } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 8 } }
                    }
                }
            }
        });
    };

    // Jalankan ChartJS
    if (typeof Chart === 'undefined') {
        const s = document.createElement('script');
        s.src = "https://cdn.jsdelivr.net/npm/chart.js";
        s.onload = window.renderDashboardMobile;
        document.head.appendChild(s);
    } else {
        window.renderDashboardMobile();
    }
</script>
