<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#064e3b] via-[#065f46] to-[#0f172a] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg">
        <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-teal-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-arrow-trend-up absolute -bottom-4 -right-2 text-[80px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col gap-3">
            <div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500/20 border border-emerald-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-circle-arrow-down text-emerald-300 text-[10px]"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-emerald-300/80">KAS MASUK RT</span>
                </div>
                <h1 class="text-lg font-black tracking-tight leading-tight">Kas Pemasukan</h1>
                <p class="text-[10px] text-white/50 font-medium mt-0.5">Catatan dana masuk kas RT</p>
            </div>

            <div class="flex items-center gap-2">
                <!-- Quick Stats Badge -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[70px]">
                    <p class="text-lg font-black text-white leading-none">{{ count($list_pemasukan ?? []) }}</p>
                    <p class="text-[7px] font-bold uppercase tracking-widest text-emerald-300/70 mt-0.5">Total Transaksi</p>
                </div>

                <button onclick="document.getElementById('modal-tambah-pemasukan').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-4 py-2.5 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer text-xs shadow-lg shadow-emerald-500/30 border border-emerald-400/30 flex-1 justify-center">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Tambah
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3">
        <div class="overflow-x-auto min-h-[120px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-400 text-[8px] uppercase tracking-widest">
                        <th class="px-2 py-2 rounded-l-xl font-bold">Tanggal</th>
                        <th class="px-2 py-2 font-bold">Kategori</th>
                        <th class="px-2 py-2 font-bold hidden sm:table-cell">Keterangan</th>
                        <th class="px-2 py-2 font-bold text-right">Jumlah</th>
                        <th class="px-2 py-2 rounded-r-xl font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[10px]">
                    @forelse($list_pemasukan ?? [] as $item)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                            <td class="px-2 py-2 font-bold text-gray-800 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-2 py-2 font-bold text-[#059669]">{{ $item->kategori }}</td>
                            <td class="px-2 py-2 text-gray-600 font-medium hidden sm:table-cell">{{ $item->keterangan }}</td>
                            <td class="px-2 py-2 font-black text-gray-800 text-right whitespace-nowrap">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td class="px-2 py-2 text-center">
                                <div class="flex justify-center gap-1">
                                    <button onclick="siapkanEditPemasukan('{{ $item->id }}', '{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}', '{{ $item->kategori }}', '{{ addslashes($item->keterangan) }}', '{{ $item->nominal }}')" class="w-7 h-7 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-pen text-[9px]"></i>
                                    </button>
                                    <button onclick="hapusTransaksi({{ $item->id }}, 'pemasukan')" class="w-7 h-7 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-trash text-[9px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-6 text-gray-400">
                                <i class="fa-solid fa-folder-open text-2xl mb-2 text-gray-300"></i>
                                <p class="font-medium italic text-xs">Belum ada data pemasukan...</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-tambah-pemasukan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end sm:items-center justify-center backdrop-blur-sm transition-all p-0 sm:p-4">
    <div class="bg-white w-full max-w-[95vw] sm:max-w-lg rounded-t-2xl sm:rounded-2xl p-5 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="document.getElementById('modal-tambah-pemasukan').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-lg font-black text-gray-800 mb-4">Tambah Pemasukan</h3>

        <form id="form-pemasukan" action="{{ route('transaksi.store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-pemasukan', 'pemasukan')">
            @csrf
            <input type="hidden" name="jenis" value="pemasukan">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</label>
                    <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($kategori_pemasukan ?? [] as $kat)
                            <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Keterangan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Iuran Warga Blok A" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" placeholder="Contoh: 150000" min="1" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
            <button type="submit" class="w-full mt-5 bg-[#059669] text-white px-4 py-3 rounded-xl font-bold shadow-lg shadow-green-900/20 hover:bg-green-700 transition-all flex items-center justify-center text-sm">
                <i class="fa-solid fa-save mr-2"></i> Simpan Pemasukan
            </button>
        </form>
    </div>
</div>

<div id="modal-edit-pemasukan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end sm:items-center justify-center backdrop-blur-sm transition-all p-0 sm:p-4">
    <div class="bg-white w-full max-w-[95vw] sm:max-w-lg rounded-t-2xl sm:rounded-2xl p-5 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="document.getElementById('modal-edit-pemasukan').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <h3 class="text-lg font-black text-gray-800 mb-4">Edit Pemasukan</h3>

        <form id="form-edit-pemasukan" action="{{ route('transaksi.update') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-edit-pemasukan', 'pemasukan')">
            @csrf
            <input type="hidden" name="id" id="edit-pemasukan-id">
            <div class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal</label>
                    <input type="date" id="edit-pemasukan-tanggal" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</label>
                    <select id="edit-pemasukan-kategori" name="kategori" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @foreach($kategori_pemasukan ?? [] as $kat)
                            <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Keterangan</label>
                    <input type="text" id="edit-pemasukan-keterangan" name="keterangan" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jumlah (Rp)</label>
                    <input type="number" id="edit-pemasukan-jumlah" name="jumlah" min="1" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
            <button type="submit" class="w-full mt-5 bg-amber-500 text-white px-4 py-3 rounded-xl font-bold shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all flex items-center justify-center text-sm">
                <i class="fa-solid fa-save mr-2"></i> Update Pemasukan
            </button>
        </form>
    </div>
</div>

<script>
    // PERBAIKAN: Fungsi Edit Instan Tanpa Loading 404
    function siapkanEditPemasukan(id, tanggal, kategori, keterangan, nominal) {
        document.getElementById('edit-pemasukan-id').value = id;
        document.getElementById('edit-pemasukan-tanggal').value = tanggal;
        document.getElementById('edit-pemasukan-kategori').value = kategori;
        document.getElementById('edit-pemasukan-keterangan').value = keterangan;
        document.getElementById('edit-pemasukan-jumlah').value = nominal;
        document.getElementById('modal-edit-pemasukan').classList.remove('hidden');
    }

    function hapusTransaksi(id, pageName) {
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
    }
</script>
