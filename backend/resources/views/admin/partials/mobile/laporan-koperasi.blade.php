<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg border border-white/10">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <i class="fa-solid fa-chart-line absolute -bottom-4 -right-2 text-[70px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-2.5">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-5 h-5 rounded-md bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-calculator text-blue-300 text-[9px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-blue-300/80">Laporan & Transparansi</span>
                </div>
                <h1 class="text-base font-black tracking-tight leading-tight">Laporan Keuangan Koperasi</h1>
                <p class="text-[10px] text-white/60 font-medium">Transparansi arus kas masuk & keluar koperasi warga.</p>
            </div>

            <!-- Quick Stats Cards (Mobile) -->
            <div class="grid grid-cols-3 gap-1.5 text-center">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-emerald-300/70 uppercase">Masuk</p>
                    <p class="stat-counter text-[10px] font-black text-emerald-400" data-value="{{ $total_pemasukan_kop ?? 0 }}" data-type="currency">Rp 0</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-red-300/70 uppercase">Keluar</p>
                    <p class="stat-counter text-[10px] font-black text-red-400" data-value="{{ $total_pengeluaran_kop ?? 0 }}" data-type="currency">Rp 0</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-2">
                    <p class="text-[8px] font-bold text-indigo-300/70 uppercase">Saldo</p>
                    <p class="stat-counter text-[10px] font-black text-indigo-300" data-value="{{ $saldo_bersih_kop ?? 0 }}" data-type="currency">Rp 0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons Bar for Bendahara -->
    @if(in_array(Auth::user()->role, ['Super Admin', 'Bendahara RW', 'Bendahara RT']))
    <div class="px-1">
        <button onclick="bukaModalKeuangan()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-plus-circle text-xs"></i> Catat Transaksi Baru
        </button>
    </div>
    @endif

    <!-- Export Action Buttons (Mobile) -->
    <div class="grid grid-cols-2 gap-2 px-1">
        <a href="{{ route('admin.export', ['tipe' => 'koperasi', 'format' => 'excel']) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl text-[10px] shadow-sm flex items-center justify-center gap-1">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('admin.export', ['tipe' => 'koperasi', 'format' => 'pdf']) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 rounded-xl text-[10px] shadow-sm flex items-center justify-center gap-1">
            <i class="fa-solid fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    <!-- Card List of Finances -->
    <div class="space-y-2">
        <h3 class="text-xs font-black text-gray-800 px-1">Riwayat Transaksi Kas</h3>

        @forelse($list_finances ?? [] as $fin)
        <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex flex-col gap-1.5">
            <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase {{ $fin->tipe == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $fin->tipe }}
                </span>
                <span class="text-[9px] text-gray-400 font-medium"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($fin->tanggal)->format('d M Y') }}</span>
            </div>
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1">
                    <span class="bg-gray-100 text-gray-700 font-bold px-1.5 py-0.5 rounded text-[8px] uppercase">{{ $fin->kategori }}</span>
                    <p class="text-[10px] text-gray-600 mt-1 leading-tight">{{ $fin->keterangan }}</p>
                </div>
                <div class="text-right shrink-0 ml-2">
                    <p class="text-[11px] font-black {{ $fin->tipe == 'pemasukan' ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $fin->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($fin->nominal, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-gray-50 mt-1">
                <div>
                    @if($fin->bukti_transaksi)
                    <a href="/{{ $fin->bukti_transaksi }}" target="_blank" class="text-[9px] text-indigo-600 font-bold"><i class="fa-solid fa-receipt mr-0.5"></i> Lihat Bukti</a>
                    @else
                    <span class="text-[9px] text-gray-400 italic">Tidak ada bukti</span>
                    @endif
                </div>
                @if(in_array(Auth::user()->role, ['Super Admin', 'Bendahara RW', 'Bendahara RT']))
                <div class="flex items-center gap-1.5">
                    <button onclick="editKeuanganKoperasi({{ $fin->id }}, '{{ $fin->tanggal }}', '{{ $fin->tipe }}', '{{ $fin->kategori }}', {{ $fin->nominal }}, '{{ addslashes($fin->keterangan ?? '') }}')" class="w-6 h-6 rounded bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-pen text-[8px]"></i>
                    </button>
                    <button onclick="hapusKeuanganKoperasi({{ $fin->id }})" class="w-6 h-6 rounded bg-red-50 text-red-500 flex items-center justify-center">
                        <i class="fa-solid fa-trash text-[8px]"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl p-6 text-center text-gray-400 italic text-xs">Belum ada catatan keuangan koperasi.</div>
        @endforelse
    </div>

</div>

<!-- Modal Tambah / Edit Keuangan Koperasi -->
<div id="modal-keuangan-koperasi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-keuangan-koperasi').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 id="modal-keuangan-title" class="text-base font-black text-gray-800 mb-4">Catat Transaksi Keuangan</h3>
        <form id="form-keuangan-koperasi" action="/koperasi/finance/store" method="POST" enctype="multipart/form-data" onsubmit="simpanLaporanKoperasi(event)">
            <input type="hidden" name="id" id="finance-id">
            <div class="space-y-3.5">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" id="finance-tanggal" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2.5 px-3.5 rounded-xl text-xs focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Tipe Transaksi</label>
                    <select name="tipe" id="finance-tipe" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2.5 px-3.5 rounded-xl text-xs focus:outline-none">
                        <option value="pemasukan">Pemasukan (Debit)</option>
                        <option value="pengeluaran">Pengeluaran (Kredit)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Kategori</label>
                    <select name="kategori" id="finance-kategori" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2.5 px-3.5 rounded-xl text-xs focus:outline-none">
                        <option value="Simpanan Warga">Simpanan Warga</option>
                        <option value="Angsuran Pinjaman">Angsuran Pinjaman</option>
                        <option value="Pembelian Sembako">Pembelian Sembako</option>
                        <option value="Bantuan Modal UMKM">Bantuan Modal UMKM</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" id="finance-nominal" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2.5 px-3.5 rounded-xl text-xs focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Keterangan</label>
                    <textarea name="keterangan" id="finance-keterangan" rows="2" placeholder="Detail deskripsi..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-2.5 rounded-xl text-xs focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Bukti Transaksi (Opsional)</label>
                    <input type="file" name="bukti_transaksi" id="finance-bukti" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-2 px-3 rounded-xl text-xs focus:outline-none">
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2.5">
                <button type="button" onclick="document.getElementById('modal-keuangan-koperasi').classList.add('hidden')" class="px-4 py-2.5 rounded-xl font-bold text-gray-500 text-xs hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan</button>
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
