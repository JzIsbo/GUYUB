<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    {{-- ============ HERO BANNER ============ --}}
    <div class="bg-gradient-to-br from-[#7f1d1d] via-[#991b1b] to-[#0f172a] rounded-2xl p-4 relative overflow-hidden shadow-lg">

        {{-- Decorative background icon --}}
        <div class="absolute -right-4 -bottom-4 opacity-[0.07] pointer-events-none">
            <i class="fa-solid fa-arrow-trend-down text-[6rem] text-white rotate-12"></i>
        </div>
        <div class="absolute right-12 top-3 opacity-[0.05] pointer-events-none">
            <i class="fa-solid fa-receipt text-[3rem] text-white -rotate-12"></i>
        </div>

        <div class="relative z-10 flex flex-col gap-3">

            {{-- Left: Label + Title --}}
            <div class="space-y-2">
                <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 text-red-200 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full">
                    <i class="fa-solid fa-circle-arrow-up text-red-300 text-[9px]"></i>
                    KAS KELUAR RT
                </div>
                <h1 class="text-lg font-black text-white tracking-tight leading-tight">Kas Pengeluaran</h1>
                <p class="text-red-200/70 text-xs font-medium leading-relaxed">Kelola catatan pengeluaran dana kas RT.</p>

                {{-- Stats badge --}}
                <div class="flex flex-wrap gap-2 pt-0.5">
                    <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-1.5">
                        <div class="w-6 h-6 rounded-lg bg-red-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice-dollar text-red-300 text-[10px]"></i>
                        </div>
                        <div>
                            <p class="text-[8px] uppercase tracking-widest text-red-300/60 font-bold">Total Data</p>
                            <p class="text-white font-black text-sm leading-tight">{{ count($list_pengeluaran ?? []) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action button --}}
            <div class="shrink-0">
                <button onclick="document.getElementById('modal-tambah-pengeluaran').classList.remove('hidden')" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-lg shadow-red-900/30 flex items-center gap-2 w-full justify-center">
                    <i class="fa-solid fa-minus text-sm"></i>
                    Tambah Pengeluaran
                </button>
            </div>
        </div>
    </div>

    {{-- ============ CARD LIST ============ --}}
    <div class="space-y-2">
        @forelse($list_pengeluaran ?? [] as $item)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-gray-800 text-[11px] truncate">{{ $item->keterangan }}</span>
                        <span class="text-[8px] text-[#DC2626] font-bold bg-[#FEE2E2] px-1.5 py-0.5 rounded">{{ $item->kategori }}</span>
                    </div>
                    <p class="text-[9px] text-gray-400 mt-1 font-medium"><i class="fa-regular fa-calendar mr-0.5"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</p>
                    <p class="font-black text-gray-800 text-[11px] mt-1">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button onclick="siapkanEditPengeluaran('{{ $item->id }}', '{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}', '{{ $item->kategori }}', '{{ addslashes($item->keterangan) }}', '{{ $item->nominal }}')" class="w-7 h-7 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                        <i class="fa-solid fa-pen text-[9px]"></i>
                    </button>
                    <button onclick="hapusTransaksi({{ $item->id }}, 'pengeluaran')" class="w-7 h-7 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
                        <i class="fa-solid fa-trash text-[9px]"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white p-6 rounded-xl border border-gray-100 text-center text-gray-400 italic text-xs">
                Belum ada data pengeluaran...
            </div>
        @endforelse
    </div>

</div>

<div id="modal-tambah-pengeluaran" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all p-2">
    <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 shadow-2xl relative">
        <button type="button" onclick="document.getElementById('modal-tambah-pengeluaran').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 bg-gray-50 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-lg font-black text-gray-800 mb-4">Tambah Pengeluaran</h3>

        <form id="form-pengeluaran" action="{{ route('transaksi.store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-pengeluaran', 'pengeluaran')">
            @csrf
            <input type="hidden" name="jenis" value="pengeluaran">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</label>
                    <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($kategori_pengeluaran ?? [] as $kat)
                            <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Keterangan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Bayar Listrik Posko" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" placeholder="Contoh: 50000" min="1" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
            <button type="submit" class="w-full mt-5 bg-[#DC2626] text-white px-4 py-3 rounded-xl font-bold text-sm shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all flex items-center justify-center">
                <i class="fa-solid fa-save mr-2"></i> Simpan Pengeluaran
            </button>
        </form>
    </div>
</div>

<div id="modal-edit-pengeluaran" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all p-2">
    <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 shadow-2xl relative">
        <button type="button" onclick="document.getElementById('modal-edit-pengeluaran').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 bg-gray-50 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-lg font-black text-gray-800 mb-4">Edit Pengeluaran</h3>

        <form id="form-edit-pengeluaran" action="{{ route('transaksi.update') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-edit-pengeluaran', 'pengeluaran')">
            @csrf
            <input type="hidden" name="id" id="edit-pengeluaran-id">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal</label>
                    <input type="date" id="edit-pengeluaran-tanggal" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</label>
                    <select id="edit-pengeluaran-kategori" name="kategori" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @foreach($kategori_pengeluaran ?? [] as $kat)
                            <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Keterangan</label>
                    <input type="text" id="edit-pengeluaran-keterangan" name="keterangan" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jumlah (Rp)</label>
                    <input type="number" id="edit-pengeluaran-jumlah" name="jumlah" min="1" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
            <button type="submit" class="w-full mt-5 bg-amber-500 text-white px-4 py-3 rounded-xl font-bold text-sm shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all flex items-center justify-center">
                <i class="fa-solid fa-save mr-2"></i> Update Pengeluaran
            </button>
        </form>
    </div>
</div>

<script>
    // PERBAIKAN: Fungsi Edit Instan
    function siapkanEditPengeluaran(id, tanggal, kategori, keterangan, nominal) {
        document.getElementById('edit-pengeluaran-id').value = id;
        document.getElementById('edit-pengeluaran-tanggal').value = tanggal;
        document.getElementById('edit-pengeluaran-kategori').value = kategori;
        document.getElementById('edit-pengeluaran-keterangan').value = keterangan;
        document.getElementById('edit-pengeluaran-jumlah').value = nominal;
        document.getElementById('modal-edit-pengeluaran').classList.remove('hidden');
    }

    if (typeof hapusTransaksi !== 'function') {
        window.hapusTransaksi = function(id, pageName) {
            if (confirm('Apakah Anda yakin ingin menghapus data transaksi ini?')) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('/admin/transaksi/delete', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Transaksi berhasil dihapus!');
                    switchPage(pageName, document.querySelector(`.menu-link[onclick*='${pageName}']`));
                }).catch(err => alert("Gagal terhubung ke server."));
            }
        };
    }
</script>
