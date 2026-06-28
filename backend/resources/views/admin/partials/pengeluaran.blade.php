<div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] relative">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Kas Pengeluaran</h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Catatan Dana Keluar RT</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-pengeluaran').classList.remove('hidden')" class="bg-[#FEF2F2] text-[#DC2626] px-6 py-3 rounded-2xl font-bold hover:scale-[1.03] transition shadow-sm flex items-center shrink-0">
            <i class="fa-solid fa-minus mr-2 text-lg"></i> Tambah Pengeluaran
        </button>
    </div>

    <div class="overflow-x-auto min-h-[200px]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-gray-400 text-[10px] uppercase tracking-widest">
                    <th class="p-4 rounded-l-2xl font-bold">Tanggal</th>
                    <th class="p-4 font-bold">Kategori</th>
                    <th class="p-4 font-bold">Keterangan</th>
                    <th class="p-4 font-bold text-right">Jumlah (Rp)</th>
                    <th class="p-4 rounded-r-2xl font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($list_pengeluaran ?? [] as $item)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4 font-bold text-gray-800">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="p-4 font-bold text-[#DC2626]">{{ $item->kategori }}</td>
                        <td class="p-4 text-gray-600 font-medium">{{ $item->keterangan }}</td>
                        <td class="p-4 font-black text-gray-800 text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                        <td class="p-4 text-center flex justify-center gap-2">
                            <button onclick="siapkanEditPengeluaran('{{ $item->id }}', '{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}', '{{ $item->kategori }}', '{{ addslashes($item->keterangan) }}', '{{ $item->nominal }}')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button onclick="hapusTransaksi({{ $item->id }}, 'pengeluaran')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-10 text-gray-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
                            <p class="font-medium italic">Belum ada data pengeluaran...</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah-pengeluaran" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all p-4">
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative">
        <button type="button" onclick="document.getElementById('modal-tambah-pengeluaran').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-2xl font-black text-gray-800 mb-6">Tambah Pengeluaran</h3>

        <form id="form-pengeluaran" action="{{ route('transaksi.store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-pengeluaran', 'pengeluaran')">
            @csrf
            <input type="hidden" name="jenis" value="pengeluaran">
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Kategori</label>
                    <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($kategori_pengeluaran ?? [] as $kat)
                            <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Bayar Listrik Posko" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" placeholder="Contoh: 50000" min="1" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
            <button type="submit" class="w-full mt-8 bg-[#DC2626] text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-700 hover:scale-[1.02] transition-all flex items-center justify-center">
                <i class="fa-solid fa-save mr-2"></i> Simpan Pengeluaran
            </button>
        </form>
    </div>
</div>

<div id="modal-edit-pengeluaran" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all p-4">
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative">
        <button type="button" onclick="document.getElementById('modal-edit-pengeluaran').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-2xl font-black text-gray-800 mb-6">Edit Pengeluaran</h3>

        <form id="form-edit-pengeluaran" action="{{ route('transaksi.update') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-edit-pengeluaran', 'pengeluaran')">
            @csrf
            <input type="hidden" name="id" id="edit-pengeluaran-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Tanggal</label>
                    <input type="date" id="edit-pengeluaran-tanggal" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Kategori</label>
                    <select id="edit-pengeluaran-kategori" name="kategori" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @foreach($kategori_pengeluaran ?? [] as $kat)
                            <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Keterangan</label>
                    <input type="text" id="edit-pengeluaran-keterangan" name="keterangan" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Jumlah (Rp)</label>
                    <input type="number" id="edit-pengeluaran-jumlah" name="jumlah" min="1" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
            <button type="submit" class="w-full mt-8 bg-amber-500 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-amber-500/20 hover:bg-amber-600 hover:scale-[1.02] transition-all flex items-center justify-center">
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
