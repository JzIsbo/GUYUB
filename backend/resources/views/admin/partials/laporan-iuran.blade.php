<style>
/* CSS overrides for Dark Mode on Laporan Iuran page */
html.dark .iuran-card-total {
    background: linear-gradient(to bottom right, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.02)) !important;
    border-color: rgba(16, 185, 129, 0.2) !important;
}
html.dark .iuran-card-transaksi {
    background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.02)) !important;
    border-color: rgba(59, 130, 246, 0.2) !important;
}
html.dark .iuran-card-total h3 { color: #34d399 !important; }
html.dark .iuran-card-transaksi h3 { color: #60a5fa !important; }

html.dark .iuran-card-total p { color: rgba(52, 211, 153, 0.8) !important; }
html.dark .iuran-card-transaksi p { color: rgba(96, 165, 250, 0.8) !important; }
</style>

@php
    $totalNominalIuran = isset($list_laporan_iuran) ? $list_laporan_iuran->sum('nominal') : 0;
    $totalJumlahIuran = isset($list_laporan_iuran) ? count($list_laporan_iuran) : 0;
@endphp

<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2.5rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-2xl border border-white/10">
        <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-emerald-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-2xl pointer-events-none"></div>
        <i class="fa-solid fa-receipt absolute -bottom-8 -right-6 text-[150px] opacity-[0.03] rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-hand-holding-dollar text-blue-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-blue-300/90">Laporan Iuran Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Laporan Pembayaran Iuran</h1>
                <p class="text-sm text-blue-200/70 font-medium mt-1">Daftar penerimaan iuran bulanan, kebersihan, & keamanan warga</p>
            </div>

            <!-- Export buttons -->
            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'excel']) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'pdf']) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="iuran-card-total bg-gradient-to-br from-emerald-50 to-white rounded-3xl border border-emerald-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Total Penerimaan Iuran</p>
                <h3 class="stat-counter text-2xl font-black text-emerald-700" data-value="{{ $totalNominalIuran }}" data-type="currency">Rp 0</h3>
                <p class="text-[10px] text-emerald-500 font-semibold mt-1">Akumulasi seluruh setoran iuran warga</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-600 text-lg">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>
        <div class="iuran-card-transaksi bg-gradient-to-br from-blue-50 to-white rounded-3xl border border-blue-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total Setoran Iuran</p>
                <h3 class="text-2xl font-black text-blue-700">{{ number_format($totalJumlahIuran, 0, ',', '.') }} Transaksi</h3>
                <p class="text-[10px] text-blue-500 font-semibold mt-1">Jumlah kali pembayaran iuran tercatat</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-600 text-lg">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Table Iuran -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-gray-800">Daftar Catatan Iuran Terkumpul</h3>
                <p class="text-xs text-gray-400 font-medium">Histori pembayaran iuran yang telah terverifikasi masuk ke kas</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6">Keterangan / Uraian Iuran</th>
                        <th class="py-3.5 px-6 text-right">Nominal Setoran (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_laporan_iuran ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-6 whitespace-nowrap">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                        <td class="py-3.5 px-6 font-bold text-gray-800">{{ $item->keterangan }}</td>
                        <td class="py-3.5 px-6 text-right font-black text-emerald-600">
                            + Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-400 italic">Belum ada data iuran yang ditemukan di transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
