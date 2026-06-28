<div class="p-8 space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-file-export"></i>
            </div>
            Export & Cetak Laporan
        </h1>
        <p class="text-sm text-gray-500 font-medium mt-1">Ekspor laporan keuangan RT Anda ke dalam format spreadsheet Excel premium atau cetak langsung ke dokumen PDF siap pakai.</p>
    </div>

    <!-- Export Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Card 1: Laporan Kas RT -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between min-h-[250px]">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-6 shadow-sm">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">Laporan Kas Masuk & Keluar</h3>
                <p class="text-xs text-gray-500 font-medium leading-relaxed mb-6">Mengunduh atau mencetak ringkasan detail seluruh transaksi kas masuk dan pengeluaran operasional RT.</p>
            </div>
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('admin.export', ['tipe' => 'kas', 'format' => 'excel']) }}" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-100 text-center text-xs flex items-center justify-center gap-2 transition duration-200">
                    <i class="fa-solid fa-file-excel text-sm"></i> Download Excel
                </a>
                <a href="{{ route('admin.export', ['tipe' => 'kas', 'format' => 'pdf']) }}" target="_blank" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl shadow-lg shadow-slate-100 text-center text-xs flex items-center justify-center gap-2 transition duration-200">
                    <i class="fa-solid fa-file-pdf text-sm"></i> Cetak / Save PDF
                </a>
            </div>
        </div>

        <!-- Card 2: Laporan Iuran Warga -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between min-h-[250px]">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-6 shadow-sm">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">Laporan Pembayaran Iuran</h3>
                <p class="text-xs text-gray-500 font-medium leading-relaxed mb-6">Mengunduh atau mencetak laporan spesifik yang berkaitan dengan setoran iuran rutin bulanan dan iuran wajib warga.</p>
            </div>
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'excel']) }}" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 text-center text-xs flex items-center justify-center gap-2 transition duration-200">
                    <i class="fa-solid fa-file-excel text-sm"></i> Download Excel
                </a>
                <a href="{{ route('admin.export', ['tipe' => 'iuran', 'format' => 'pdf']) }}" target="_blank" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl shadow-lg shadow-slate-100 text-center text-xs flex items-center justify-center gap-2 transition duration-200">
                    <i class="fa-solid fa-file-pdf text-sm"></i> Cetak / Save PDF
                </a>
            </div>
        </div>

        <!-- Card 3: Laporan Keseluruhan (All) -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between min-h-[250px]">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl mb-6 shadow-sm">
                    <i class="fa-solid fa-table-list"></i>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">Laporan Keseluruhan (All)</h3>
                <p class="text-xs text-gray-500 font-medium leading-relaxed mb-6">Mengunduh atau mencetak basis data seluruh catatan riwayat transaksi pembukuan tanpa filter kategori.</p>
            </div>
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'excel']) }}" class="w-full py-3 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-2xl shadow-lg shadow-violet-100 text-center text-xs flex items-center justify-center gap-2 transition duration-200">
                    <i class="fa-solid fa-file-excel text-sm"></i> Download Excel
                </a>
                <a href="{{ route('admin.export', ['tipe' => 'all', 'format' => 'pdf']) }}" target="_blank" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl shadow-lg shadow-slate-100 text-center text-xs flex items-center justify-center gap-2 transition duration-200">
                    <i class="fa-solid fa-file-pdf text-sm"></i> Cetak / Save PDF
                </a>
            </div>
        </div>

    </div>
</div>
