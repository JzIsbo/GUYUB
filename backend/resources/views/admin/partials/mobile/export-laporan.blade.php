<div class="p-3 space-y-3">
    <!-- Header -->
    <div class="flex items-center gap-2 mb-1">
        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <i class="fa-solid fa-file-export text-xs"></i>
        </div>
        <div>
            <h1 class="text-sm font-black text-gray-800">Export & Cetak</h1>
            <p class="text-[10px] text-gray-500">Ekspor laporan ke Excel atau PDF</p>
        </div>
    </div>

    <!-- Export Cards -->
    <div class="space-y-3">
        <!-- Kas -->
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm"><i class="fa-solid fa-money-bill-transfer"></i></div>
                <div>
                    <h3 class="text-xs font-black text-gray-800">Laporan Kas</h3>
                    <p class="text-[9px] text-gray-500">Kas masuk & keluar</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.export', ['tipe' => 'kas', 'format' => 'excel']) }}" class="flex-1 py-2 bg-emerald-600 text-white font-bold rounded-lg text-center text-[10px] flex items-center justify-center gap-1"><i class="fa-solid fa-file-excel text-[8px]"></i> Excel</a>
                <a href="{{ route('admin.export', ['tipe' => 'kas', 'format' => 'pdf']) }}" target="_blank" class="flex-1 py-2 bg-slate-800 text-white font-bold rounded-lg text-center text-[10px] flex items-center justify-center gap-1"><i class="fa-solid fa-file-pdf text-[8px]"></i> PDF</a>
            </div>
        </div>

        <!-- Iuran -->
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                <div>
                    <h3 class="text-xs font-black text-gray-800">Laporan Iuran</h3>
                    <p class="text-[9px] text-gray-500">Setoran iuran warga</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'excel']) }}" class="flex-1 py-2 bg-blue-600 text-white font-bold rounded-lg text-center text-[10px] flex items-center justify-center gap-1"><i class="fa-solid fa-file-excel text-[8px]"></i> Excel</a>
                <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'pdf']) }}" target="_blank" class="flex-1 py-2 bg-slate-800 text-white font-bold rounded-lg text-center text-[10px] flex items-center justify-center gap-1"><i class="fa-solid fa-file-pdf text-[8px]"></i> PDF</a>
            </div>
        </div>

        <!-- All -->
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center text-sm"><i class="fa-solid fa-table-list"></i></div>
                <div>
                    <h3 class="text-xs font-black text-gray-800">Laporan Lengkap</h3>
                    <p class="text-[9px] text-gray-500">Semua transaksi</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'excel']) }}" class="flex-1 py-2 bg-violet-600 text-white font-bold rounded-lg text-center text-[10px] flex items-center justify-center gap-1"><i class="fa-solid fa-file-excel text-[8px]"></i> Excel</a>
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank" class="flex-1 py-2 bg-slate-800 text-white font-bold rounded-lg text-center text-[10px] flex items-center justify-center gap-1"><i class="fa-solid fa-file-pdf text-[8px]"></i> PDF</a>
            </div>
        </div>
    </div>
</div>
