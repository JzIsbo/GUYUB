<div class="p-3 space-y-3">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-gray-800">Tagihan Warga</h2>
            <p class="text-[10px] text-gray-500">Data real-time tagihan</p>
        </div>
        @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
        <button onclick="document.getElementById('modal-tambah-tagihan').classList.remove('hidden')" class="bg-blue-600 text-white px-3 py-2 rounded-xl font-bold text-[10px] shadow-sm">
            + Tagihan
        </button>
        @endif
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto -mx-3">
            <table class="w-full text-left min-w-[360px]">
                <thead>
                    <tr class="bg-gray-50 text-[9px] text-gray-600 uppercase tracking-wider">
                        <th class="px-3 py-2">ID</th>
                        <th class="px-3 py-2">Warga</th>
                        <th class="px-3 py-2">Jumlah</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[10px]">
                    @forelse($tagihans as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-2 font-mono text-gray-500">#{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-3 py-2">
                            <p class="font-bold text-gray-800 truncate max-w-[80px]">{{ $item->nama_warga }}</p>
                            <p class="text-[8px] text-gray-400 truncate max-w-[80px]">{{ $item->jenis_tagihan }}</p>
                        </td>
                        <td class="px-3 py-2 font-bold text-gray-800 whitespace-nowrap">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="px-3 py-2">
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold {{ strtolower($item->status) == 'berhasil' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button onclick="bukaModalDetail({{ $item->id }}, '{{ addslashes($item->nama_warga) }}', '{{ addslashes($item->jenis_tagihan) }}', {{ $item->jumlah }}, '{{ $item->batas_bayar }}', '{{ $item->status }}')" class="text-blue-600 font-bold hover:underline bg-blue-50 px-2 py-1 rounded text-[9px]">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400 text-xs">Belum ada tagihan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-tagihan" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm p-3">
    <div class="bg-white p-5 rounded-2xl w-full max-w-[95vw] shadow-xl">
        <h2 class="text-sm font-bold mb-3">Buat Tagihan Baru</h2>
        <form id="form-tambah-tagihan" action="/tagihan/store" method="POST">
            @csrf
            <div class="space-y-3">
                <input type="text" name="nama_warga" placeholder="Nama Warga" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                <input type="text" name="jenis_tagihan" placeholder="Jenis Tagihan (e.g. Kebersihan)" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                <input type="number" name="jumlah" placeholder="Jumlah (Rp)" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                <div>
                    <label class="text-[9px] text-gray-500 font-bold mb-1 block">Batas Bayar</label>
                    <input type="date" name="batas_bayar" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-tambah-tagihan').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-600 py-2.5 rounded-xl font-bold text-xs">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-tagihan', 'tagihan-warga')" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl font-bold text-xs">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $isAdminTagihan = in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']);
    $readonlyTagihan = !$isAdminTagihan ? 'readonly disabled' : '';
@endphp

<!-- Modal Detail -->
<div id="modal-detail-tagihan" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm p-3">
    <div class="bg-white p-5 rounded-2xl w-full max-w-[95vw] shadow-xl">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-sm font-bold">Detail Tagihan</h2>
            @if($isAdminTagihan)
            <button onclick="hapusTagihan()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg" title="Hapus"><i class="fa-solid fa-trash text-sm"></i></button>
            @endif
        </div>

        <form id="form-edit-tagihan" action="/tagihan/update" method="POST">
            @csrf
            <input type="hidden" name="id" id="detail_id">
            <div class="space-y-3">
                <div>
                    <label class="text-[9px] text-gray-500 font-bold mb-1 block">Nama Warga</label>
                    <input type="text" name="nama_warga" id="detail_nama" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" {!! $readonlyTagihan !!} required>
                </div>
                <div>
                    <label class="text-[9px] text-gray-500 font-bold mb-1 block">Jenis Tagihan</label>
                    <input type="text" name="jenis_tagihan" id="detail_jenis" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" {!! $readonlyTagihan !!} required>
                </div>
                <div>
                    <label class="text-[9px] text-gray-500 font-bold mb-1 block">Jumlah Tagihan (Rp)</label>
                    <input type="number" name="jumlah" id="detail_jumlah" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" {!! $readonlyTagihan !!} required>
                </div>
                <div>
                    <label class="text-[9px] text-gray-500 font-bold mb-1 block">Status</label>
                    <select name="status" id="detail_status" class="w-full py-2 px-3 border rounded-xl bg-gray-50 font-bold text-sm" {!! $readonlyTagihan !!} required>
                        <option value="menunggu">Menunggu</option>
                        <option value="berhasil">Berhasil</option>
                    </select>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-detail-tagihan').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-600 py-2.5 rounded-xl font-bold text-xs">Tutup</button>
                    @if($isAdminTagihan)
                    <button type="button" onclick="simpanDataUmum(event, 'form-edit-tagihan', 'tagihan-warga')" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl font-bold text-xs">Update</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalDetail(id, nama, jenis, jumlah, batas, status) {
        document.getElementById('detail_id').value = id;
        document.getElementById('detail_nama').value = nama;
        document.getElementById('detail_jenis').value = jenis;
        document.getElementById('detail_jumlah').value = jumlah;
        let statusSelect = document.getElementById('detail_status');
        statusSelect.value = status.toLowerCase();
        if(status.toLowerCase() === 'berhasil') {
            statusSelect.className = "w-full py-2 px-3 border rounded-xl bg-green-50 text-green-700 font-bold text-sm";
        } else {
            statusSelect.className = "w-full py-2 px-3 border rounded-xl bg-yellow-50 text-yellow-700 font-bold text-sm";
        }
        document.getElementById('modal-detail-tagihan').classList.remove('hidden');
    }
    function hapusTagihan() {
        let id = document.getElementById('detail_id').value;
        if (!confirm('Hapus tagihan ini secara permanen?')) return;
        let formData = new FormData();
        formData.append('id', id);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        fetch('/tagihan/delete', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            alert(data.message); document.getElementById('modal-detail-tagihan').classList.add('hidden');
            if (typeof switchPage === "function") switchPage('tagihan-warga', document.querySelector('.menu-active'));
            else window.location.reload();
        }).catch(error => alert("Terjadi kesalahan."));
    }
</script>
