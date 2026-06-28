<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-[2rem] shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] border border-gray-100 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-[#DCFCE7] w-12 h-12 rounded-[14px] text-[#16A34A] flex items-center justify-center shadow-inner"><i class="fa-solid fa-download text-xl"></i></div>
            <span class="bg-green-50 text-green-600 text-[10px] px-2.5 py-1 rounded-lg font-black border border-green-100">Kas Masuk</span>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Pemasukan</p>
        <h3 class="text-2xl font-black text-gray-900 tracking-tighter">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] border border-gray-100 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-[#FEE2E2] w-12 h-12 rounded-[14px] text-[#DC2626] flex items-center justify-center shadow-inner"><i class="fa-solid fa-upload text-xl"></i></div>
            <span class="bg-red-50 text-red-600 text-[10px] px-2.5 py-1 rounded-lg font-black border border-red-100">Kas Keluar</span>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Pengeluaran</p>
        <h3 class="text-2xl font-black text-gray-900 tracking-tighter">Rp {{ number_format($pengeluaran ?? 0, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] border border-gray-100 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-[#EFF6FF] w-12 h-12 rounded-[14px] text-[#2563EB] flex items-center justify-center shadow-inner"><i class="fa-solid fa-wallet text-xl"></i></div>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Saldo Kas Global</p>
        <h3 class="text-2xl font-black text-gray-900 tracking-tighter">Rp {{ number_format($saldo_bersih ?? 0, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] border border-gray-100 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-[#FEF3C7] w-12 h-12 rounded-[14px] text-[#D97706] flex items-center justify-center shadow-inner"><i class="fa-solid fa-users text-xl"></i></div>
        </div>
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Warga Terdaftar</p>
        <h3 class="text-2xl font-black text-gray-900 tracking-tighter">{{ $warga ?? 128 }} Warga</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-[#1E40AF] to-[#0F172A] p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden flex flex-col justify-between min-h-[320px]">
        <div class="relative z-10">
            <p class="text-blue-200 text-sm font-medium">Saldo Kas Saat Ini</p>
            <h2 class="text-4xl font-black mt-2 tracking-tighter">Rp {{ number_format($saldo_bersih ?? 0, 0, ',', '.') }}</h2>
            <p class="text-blue-300 text-xs mt-2 font-medium italic">Tersedia di Kas Utama RT / RW</p>
        </div>
        <div class="relative z-10 mt-8">
            <p class="text-[10px] text-blue-200 uppercase tracking-[2px] font-bold">Pembaruan: Hari Ini</p>
        </div>
        <i class="fa-solid fa-building-columns absolute -bottom-10 -right-10 text-[180px] opacity-10 rotate-12"></i>
    </div>

    <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 text-lg">Grafik Kas Bulanan</h3>
            <div class="flex gap-4">
                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-green-500 rounded-full"></span><span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Masuk</span></div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-red-500 rounded-full"></span><span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Keluar</span></div>
            </div>
        </div>

        <div class="relative flex-1 w-full min-h-[250px]">
            <canvas id="kasChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)]">
        <div class="flex justify-between items-center mb-8">
            <h3 class="font-bold text-gray-800 text-lg">Transaksi Terbaru</h3>
            <a href="javascript:void(0)" onclick="switchPage('transaksi', document.querySelector('.menu-link[onclick*=\\\'transaksi\\\']'))" class="text-blue-600 text-xs font-bold hover:underline tracking-widest uppercase">Lihat Semua</a>
        </div>

        <div class="space-y-6">
            @forelse($transaksi_terbaru as $item)
                <div class="flex justify-between items-center pb-4 border-b border-gray-50 hover:bg-gray-50 transition-colors rounded-xl px-2 -mx-2">
                    <div class="flex items-center gap-4">
                        @if($item->jenis == 'pemasukan')
                            <div class="bg-[#DCFCE7] text-[#16A34A] w-12 h-12 rounded-[14px] flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-arrow-down"></i>
                            </div>
                        @else
                            <div class="bg-[#FEE2E2] text-[#DC2626] w-12 h-12 rounded-[14px] flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-arrow-up"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $item->keterangan }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $item->jenis }} - {{ $item->kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($item->jenis == 'pemasukan')
                            <p class="text-sm font-black text-[#16A34A] tracking-tight">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm font-black text-[#DC2626] tracking-tight">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        @endif
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ date('d M Y', strtotime($item->tanggal)) }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-4 italic">Belum ada transaksi terbaru.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] flex flex-col">
        <h3 class="font-bold text-gray-800 text-lg mb-8">Aksi Cepat Super Admin</h3>
        <div class="grid grid-cols-2 gap-4 flex-1">
            <button onclick="switchPage('pemasukan', document.querySelector('.menu-link[onclick*=\\\'pemasukan\\\']'))" class="bg-[#DCFCE7] border border-green-100 text-[#16A34A] rounded-[1.5rem] font-bold flex flex-col items-center justify-center hover:scale-[1.03] transition shadow-sm py-6">
                <i class="fa-solid fa-circle-plus text-3xl mb-3"></i>
                <span class="text-xs">Catat Pemasukan</span>
            </button>
            <button onclick="switchPage('pengeluaran', document.querySelector('.menu-link[onclick*=\\\'pengeluaran\\\']'))" class="bg-[#FEE2E2] border border-red-100 text-[#DC2626] rounded-[1.5rem] font-bold flex flex-col items-center justify-center hover:scale-[1.03] transition shadow-sm py-6">
                <i class="fa-solid fa-circle-minus text-3xl mb-3"></i>
                <span class="text-xs">Catat Pengeluaran</span>
            </button>
            <button onclick="switchPage('data-warga', document.querySelector('.menu-link[onclick*=\\\'data-warga\\\']'))" class="bg-[#EFF6FF] border border-blue-100 text-[#2563EB] rounded-[1.5rem] font-bold flex flex-col items-center justify-center hover:scale-[1.03] transition shadow-sm py-6">
                <i class="fa-solid fa-user-plus text-3xl mb-3"></i>
                <span class="text-xs">Tambah Warga</span>
            </button>
            <button onclick="switchPage('laporan-keuangan', document.querySelector('.menu-link[onclick*=\\\'laporan-keuangan\\\']'))" class="bg-[#FEF3C7] border border-yellow-100 text-[#D97706] rounded-[1.5rem] font-bold flex flex-col items-center justify-center hover:scale-[1.03] transition shadow-sm py-6">
                <i class="fa-solid fa-file-invoice text-3xl mb-3"></i>
                <span class="text-xs">Cetak Laporan</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Fungsi Global yang akan dipanggil oleh switchPage di super-admin
    window.renderDashboard = function() {
        const canvas = document.getElementById('kasChart');
        if(!canvas) return;

        if(window.kasChartInstance) {
            window.kasChartInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        window.kasChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Masuk',
                        data: {!! json_encode($chart_pemasukan ?? array_fill(0,12,0)) !!},
                        borderColor: '#22c55e', backgroundColor: 'rgba(34, 197, 94, 0.1)', tension: 0.4, fill: true
                    },
                    {
                        label: 'Keluar',
                        data: {!! json_encode($chart_pengeluaran ?? array_fill(0,12,0)) !!},
                        borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.1)', tension: 0.4, fill: true
                    }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    };

    // Jalankan ChartJS
    if (typeof Chart === 'undefined') {
        const s = document.createElement('script');
        s.src = "https://cdn.jsdelivr.net/npm/chart.js";
        s.onload = window.renderDashboard;
        document.head.appendChild(s);
    } else {
        window.renderDashboard();
    }
</script>
