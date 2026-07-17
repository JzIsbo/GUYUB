<style>
/* CSS overrides for Dark Mode on Koperasi Financial Report page */
html.dark .kop-card-masuk {
    background: linear-gradient(to bottom right, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.02)) !important;
    border-color: rgba(16, 185, 129, 0.2) !important;
}
html.dark .kop-card-keluar {
    background: linear-gradient(to bottom right, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.02)) !important;
    border-color: rgba(239, 68, 68, 0.2) !important;
}
html.dark .kop-card-saldo {
    background: linear-gradient(to bottom right, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.02)) !important;
    border-color: rgba(99, 102, 241, 0.2) !important;
}
html.dark .kop-card-masuk h3 { color: #34d399 !important; }
html.dark .kop-card-keluar h3 { color: #f87171 !important; }
html.dark .kop-card-saldo h3 { color: #818cf8 !important; }

html.dark .kop-card-masuk p { color: rgba(52, 211, 153, 0.8) !important; }
html.dark .kop-card-keluar p { color: rgba(248, 113, 113, 0.8) !important; }
html.dark .kop-card-saldo p { color: rgba(129, 140, 248, 0.8) !important; }
</style>

<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2.5rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-2xl border border-white/10">
        <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-emerald-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-2xl pointer-events-none"></div>
        <i class="fa-solid fa-chart-line absolute -bottom-8 -right-6 text-[150px] opacity-[0.03] rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-calculator text-blue-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-blue-300/90">Laporan & Transparansi</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Laporan Keuangan Koperasi</h1>
                <p class="text-sm text-blue-200/70 font-medium mt-1">Transparansi arus kas masuk & keluar unit usaha Koperasi Warga</p>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                <a href="{{ route('admin.export', ['tipe' => 'koperasi', 'format' => 'excel']) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.export', ['tipe' => 'koperasi', 'format' => 'pdf']) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                </a>
                @if(in_array(Auth::user()->role, ['Super Admin', 'Bendahara RW', 'Bendahara RT']))
                <button onclick="bukaModalKeuangan()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2.5 rounded-xl transition-all text-xs flex items-center gap-2 shadow-lg shadow-blue-600/25">
                    <i class="fa-solid fa-plus text-xs"></i> Catat Transaksi
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="kop-card-masuk bg-gradient-to-br from-emerald-50 to-white rounded-3xl border border-emerald-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Total Pemasukan Koperasi</p>
                <h3 class="stat-counter text-2xl font-black text-emerald-700" data-value="{{ $total_pemasukan_kop ?? 0 }}" data-type="currency">Rp 0</h3>
                <p class="text-[10px] text-emerald-500 font-semibold mt-1">Simpanan, angsuran, penjualan sembako</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-600 text-lg">
                <i class="fa-solid fa-arrow-down-long"></i>
            </div>
        </div>
        <div class="kop-card-keluar bg-gradient-to-br from-red-50 to-white rounded-3xl border border-red-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Total Pengeluaran Koperasi</p>
                <h3 class="stat-counter text-2xl font-black text-red-700" data-value="{{ $total_pengeluaran_kop ?? 0 }}" data-type="currency">Rp 0</h3>
                <p class="text-[10px] text-red-500 font-semibold mt-1">Pembelian sembako, permodalan UMKM</p>
            </div>
            <div class="w-12 h-12 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center justify-center text-red-600 text-lg">
                <i class="fa-solid fa-arrow-up-long"></i>
            </div>
        </div>
        <div class="kop-card-saldo bg-gradient-to-br from-indigo-50 to-white rounded-3xl border border-indigo-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">Saldo Bersih Koperasi</p>
                <h3 class="stat-counter text-2xl font-black text-indigo-700" data-value="{{ $saldo_bersih_kop ?? 0 }}" data-type="currency">Rp 0</h3>
                <p class="text-[10px] text-indigo-500 font-semibold mt-1">Kas yang saat ini tersedia</p>
            </div>
            <div class="w-12 h-12 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-600 text-lg">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
        </div>
    </div>

    <!-- Table Finances -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-gray-800">Catatan Kas Masuk & Keluar Koperasi</h3>
                <p class="text-xs text-gray-400 font-medium">Daftar riwayat audit keuangan koperasi warga secara menyeluruh</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6">Tipe</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Nominal (Rp)</th>
                        <th class="py-3.5 px-6">Keterangan</th>
                        <th class="py-3.5 px-6 text-center">Bukti</th>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'Bendahara RW', 'Bendahara RT']))
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_finances ?? [] as $fin)
                    <tr>
                        <td class="py-3.5 px-6">{{ \Carbon\Carbon::parse($fin->tanggal)->format('d M Y') }}</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $fin->tipe == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ strtoupper($fin->tipe) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6"><span class="bg-gray-100 text-gray-700 font-bold px-2 py-0.5 rounded text-[10px]">{{ $fin->kategori }}</span></td>
                        <td class="py-3.5 px-6 font-black {{ $fin->tipe == 'pemasukan' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $fin->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($fin->nominal, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-6 max-w-xs truncate">{{ $fin->keterangan }}</td>
                        <td class="py-3.5 px-6 text-center">
                            @if($fin->bukti_transaksi)
                            <a href="/{{ $fin->bukti_transaksi }}" target="_blank" class="inline-flex items-center gap-1 bg-gray-50 hover:bg-gray-100 text-indigo-600 font-bold px-2.5 py-1 rounded-lg border border-gray-200">
                                <i class="fa-solid fa-receipt"></i> Lihat Bukti
                            </a>
                            @else
                            <span class="text-gray-400 italic text-[11px]">Tidak Ada</span>
                            @endif
                        </td>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'Bendahara RW', 'Bendahara RT']))
                        <td class="py-3.5 px-6 text-center space-x-1.5">
                            <button onclick="editKeuanganKoperasi({{ $fin->id }}, '{{ $fin->tanggal }}', '{{ $fin->tipe }}', '{{ $fin->kategori }}', {{ $fin->nominal }}, '{{ addslashes($fin->keterangan ?? '') }}')" class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                            </button>
                            <button onclick="hapusKeuanganKoperasi({{ $fin->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="7" class="py-8 text-center text-gray-400 italic">Belum ada catatan keuangan koperasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- Modal Tambah / Edit Keuangan Koperasi -->
<div id="modal-keuangan-koperasi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-keuangan-koperasi').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 id="modal-keuangan-title" class="text-xl font-black text-gray-800 mb-6">Catat Transaksi Keuangan</h3>
        <form id="form-keuangan-koperasi" action="/koperasi/finance/store" method="POST" enctype="multipart/form-data" onsubmit="simpanLaporanKoperasi(event)">
            <input type="hidden" name="id" id="finance-id">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" id="finance-tanggal" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tipe Transaksi</label>
                        <select name="tipe" id="finance-tipe" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="pemasukan">Pemasukan (Debit)</option>
                            <option value="pengeluaran">Pengeluaran (Kredit)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                        <select name="kategori" id="finance-kategori" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="Simpanan Warga">Simpanan Warga</option>
                            <option value="Angsuran Pinjaman">Angsuran Pinjaman</option>
                            <option value="Pembelian Sembako">Pembelian Sembako</option>
                            <option value="Bantuan Modal UMKM">Bantuan Modal UMKM</option>
                            <option value="Operasional">Operasional</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal (Rp)</label>
                        <input type="number" name="nominal" id="finance-nominal" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keterangan</label>
                    <textarea name="keterangan" id="finance-keterangan" rows="2" placeholder="Detail deskripsi transaksi..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bukti Transaksi (Gambar, Opsional)</label>
                    <input type="file" name="bukti_transaksi" id="finance-bukti" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2.5 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-[10px] text-gray-400 mt-1">Ukuran maksimal 2MB (Format: JPG, PNG, JPEG)</p>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-keuangan-koperasi').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalKeuangan() {
    document.getElementById('modal-keuangan-title').innerText = 'Catat Transaksi Keuangan';
    document.getElementById('form-keuangan-koperasi').action = '/koperasi/finance/store';
    document.getElementById('finance-id').value = '';
    document.getElementById('finance-tanggal').value = new Date().toISOString().split('T')[0];
    document.getElementById('finance-tipe').value = 'pemasukan';
    document.getElementById('finance-kategori').value = 'Simpanan Warga';
    document.getElementById('finance-nominal').value = '';
    document.getElementById('finance-keterangan').value = '';
    document.getElementById('finance-bukti').value = '';
    document.getElementById('modal-keuangan-koperasi').classList.remove('hidden');
}

function editKeuanganKoperasi(id, tanggal, tipe, kategori, nominal, keterangan) {
    document.getElementById('modal-keuangan-title').innerText = 'Edit Transaksi Keuangan';
    document.getElementById('form-keuangan-koperasi').action = '/koperasi/finance/update';
    document.getElementById('finance-id').value = id;
    document.getElementById('finance-tanggal').value = tanggal;
    document.getElementById('finance-tipe').value = tipe;
    document.getElementById('finance-kategori').value = kategori;
    document.getElementById('finance-nominal').value = nominal;
    document.getElementById('finance-keterangan').value = keterangan;
    document.getElementById('finance-bukti').value = '';
    document.getElementById('modal-keuangan-koperasi').classList.remove('hidden');
}

function simpanLaporanKoperasi(event) {
    event.preventDefault();
    const form = document.getElementById('form-keuangan-koperasi');
    const fd = new FormData(form);
    fd.append('_token', window.csrfToken);
    
    fetch(form.action, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message || 'Berhasil menyimpan transaksi');
        document.getElementById('modal-keuangan-koperasi').classList.add('hidden');
        if (typeof window.invalidatePageCache === 'function') window.invalidatePageCache('laporan-koperasi');
        switchPage('laporan-koperasi', document.querySelector('.menu-active'));
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat menyimpan.');
    });
}

function hapusKeuanganKoperasi(id) {
    if (!confirm('Hapus transaksi keuangan koperasi ini?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    
    fetch('/koperasi/finance/delete', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message || 'Berhasil menghapus');
        if (typeof window.invalidatePageCache === 'function') window.invalidatePageCache('laporan-koperasi');
        switchPage('laporan-koperasi', document.querySelector('.menu-active'));
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menghapus transaksi.');
    });
}
</script>
