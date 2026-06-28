<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tagihan Warga</h2>
            <p class="text-sm text-gray-500">Menampilkan data real-time dari database</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-tagihan').classList.remove('hidden')"
                class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-sm">
            + Tambah Tagihan
        </button>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="p-5">ID Tagihan</th>
                    <th class="p-5">Nama Warga</th>
                    <th class="p-5">Jenis</th>
                    <th class="p-5">Jumlah</th>
                    <th class="p-5">Status</th>
                    <th class="p-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($tagihans as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-5 font-mono text-gray-500">#TG-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-5 font-bold text-gray-800">{{ $item->nama_warga }}</td>
                    <td class="p-5 text-gray-600">{{ $item->jenis_tagihan }}</td>
                    <td class="p-5 font-bold text-gray-800">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="p-5">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold
                            {{ strtolower($item->status) == 'berhasil' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                    <td class="p-5 text-center">
                        <button onclick="bukaModalDetail({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', {{ $item->jumlah }}, '{{ $item->batas_bayar }}', '{{ $item->status }}')"
                                class="text-blue-600 font-bold hover:underline bg-blue-50 px-3 py-1.5 rounded-lg">
                            Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-400 font-medium">Belum ada data tagihan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah-tagihan" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl w-[400px] shadow-xl">
        <h2 class="text-xl font-bold mb-4">Buat Tagihan Baru</h2>
        <form id="form-tambah-tagihan" action="/tagihan/store" method="POST">
            @csrf
            <div class="space-y-4">
                <input type="text" name="nama_warga" placeholder="Nama Warga" class="w-full p-3 border rounded-xl bg-gray-50 focus:bg-white" required>
                <input type="text" name="jenis_tagihan" placeholder="Jenis Tagihan (e.g. Iuran Kebersihan)" class="w-full p-3 border rounded-xl bg-gray-50 focus:bg-white" required>
                <input type="number" name="jumlah" placeholder="Jumlah (Rp)" class="w-full p-3 border rounded-xl bg-gray-50 focus:bg-white" required>
                <div>
                    <label class="text-xs text-gray-500 font-bold ml-1 mb-1 block">Batas Bayar</label>
                    <input type="date" name="batas_bayar" class="w-full p-3 border rounded-xl bg-gray-50 focus:bg-white" required>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-tambah-tagihan').classList.add('hidden')" class="w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-tagihan', 'tagihan-warga')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal-detail-tagihan" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl w-[400px] shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Detail Tagihan</h2>
            <button onclick="hapusTagihan()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus Tagihan">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>

        <form id="form-edit-tagihan" action="/tagihan/update" method="POST">
            @csrf
            <input type="hidden" name="id" id="detail_id">
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-500 font-bold ml-1 mb-1 block">Nama Warga</label>
                    <input type="text" name="nama_warga" id="detail_nama" class="w-full p-3 border rounded-xl bg-gray-50" required>
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-bold ml-1 mb-1 block">Jenis Tagihan</label>
                    <input type="text" name="jenis_tagihan" id="detail_jenis" class="w-full p-3 border rounded-xl bg-gray-50" required>
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-bold ml-1 mb-1 block">Jumlah Tagihan (Rp)</label>
                    <input type="number" name="jumlah" id="detail_jumlah" class="w-full p-3 border rounded-xl bg-gray-50" required>
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-bold ml-1 mb-1 block">Status Pembayaran</label>
                    <select name="status" id="detail_status" class="w-full p-3 border rounded-xl bg-gray-50 font-bold" required>
                        <option value="menunggu">Menunggu</option>
                        <option value="berhasil">Berhasil</option>
                    </select>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-detail-tagihan').classList.add('hidden')" class="w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition">Tutup</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-edit-tagihan', 'tagihan-warga')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk mengisi dan membuka Modal Detail
    function bukaModalDetail(id, nama, jenis, jumlah, batas, status) {
        document.getElementById('detail_id').value = id;
        document.getElementById('detail_nama').value = nama;
        document.getElementById('detail_jenis').value = jenis;
        document.getElementById('detail_jumlah').value = jumlah;

        let statusSelect = document.getElementById('detail_status');
        statusSelect.value = status.toLowerCase();

        // Ubah warna select otomatis berdasarkan status
        if(status.toLowerCase() === 'berhasil') {
            statusSelect.className = "w-full p-3 border rounded-xl bg-green-50 text-green-700 font-bold";
        } else {
            statusSelect.className = "w-full p-3 border rounded-xl bg-yellow-50 text-yellow-700 font-bold";
        }

        document.getElementById('modal-detail-tagihan').classList.remove('hidden');
    }

    // Fungsi khusus untuk menghapus data via AJAX
    function hapusTagihan() {
        let id = document.getElementById('detail_id').value;
        if (!confirm('Apakah Anda yakin ingin menghapus tagihan ini secara permanen?')) return;

        let formData = new FormData();
        formData.append('id', id);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch('/tagihan/delete', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            document.getElementById('modal-detail-tagihan').classList.add('hidden');

            // Refresh halaman partial via AJAX
            if (typeof switchPage === "function") {
                switchPage('tagihan-warga', document.querySelector('.menu-active'));
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            alert("Terjadi kesalahan saat menghapus data.");
            console.error(error);
        });
    }
</script>
