<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">QRIS & Virtual Account</h2>
        <p class="text-sm text-gray-500">Informasi rekening utama kas RT untuk pembayaran manual</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <h3 class="text-lg font-bold text-gray-800 mb-2">QRIS KAS RT</h3>
            <p class="text-xs text-gray-500 mb-6">Scan menggunakan aplikasi M-Banking atau E-Wallet apapun</p>

            <div class="p-4 bg-white rounded-3xl border border-gray-200 mb-6 shadow-sm overflow-hidden">
                <img id="qris-image" src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qris->qris_data ?? 'KOSONG') }}"
                     alt="QRIS Barcode" class="w-[200px] h-[200px] object-cover">
            </div>

            <button onclick="downloadQRIS()" class="bg-blue-50 text-blue-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-100 transition w-full">
                <i class="fa-solid fa-download mr-2"></i> Download QRIS
            </button>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-6">Rekening Bank Pengurus</h3>

                <div class="space-y-4">
                    <div class="p-4 border rounded-2xl bg-gray-50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-100 rounded-bl-full -mr-4 -mt-4 z-0"></div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ $qris->bank_1_name }}</p>
                            <h4 class="text-xl font-mono font-bold text-gray-800 tracking-widest mb-1">{{ $qris->bank_1_number }}</h4>
                            <p class="text-sm font-bold text-blue-600">{{ $qris->bank_1_owner }}</p>
                        </div>
                    </div>

                    <div class="p-4 border rounded-2xl bg-gray-50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-orange-100 rounded-bl-full -mr-4 -mt-4 z-0"></div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ $qris->bank_2_name }}</p>
                            <h4 class="text-xl font-mono font-bold text-gray-800 tracking-widest mb-1">{{ $qris->bank_2_number }}</h4>
                            <p class="text-sm font-bold text-orange-600">{{ $qris->bank_2_owner }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <button onclick="document.getElementById('modal-edit-rekening').classList.remove('hidden')" class="mt-6 bg-gray-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-900 transition w-full shadow-md">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Rekening
            </button>
        </div>
    </div>
</div>

<div id="modal-edit-rekening" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Data Pembayaran Manual</h2>

        <form id="form-edit-qris" action="/qris-va/update" method="POST">
            @csrf

            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <label class="text-xs text-blue-800 font-bold uppercase tracking-wider mb-2 block">Data Payload QRIS</label>
                    <input type="text" name="qris_data" value="{{ $qris->qris_data }}" placeholder="Link URL atau Payload Teks Barcode" class="w-full p-3 border rounded-xl bg-white focus:ring-2 focus:ring-blue-300 outline-none" required>
                    <p class="text-[10px] text-blue-600 mt-1">*Masukkan payload asli QRIS Anda atau teks/link yang akan dijadikan barcode.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1 block">Nama Bank 1</label>
                        <input type="text" name="bank_1_name" value="{{ $qris->bank_1_name }}" class="w-full p-3 border rounded-xl bg-gray-50" required>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1 block">No Rekening</label>
                        <input type="text" name="bank_1_number" value="{{ $qris->bank_1_number }}" class="w-full p-3 border rounded-xl bg-gray-50" required>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1 block">Atas Nama</label>
                        <input type="text" name="bank_1_owner" value="{{ $qris->bank_1_owner }}" class="w-full p-3 border rounded-xl bg-gray-50" required>
                    </div>
                </div>

                <hr class="border-gray-100 my-2">

                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1 block">Nama Bank 2</label>
                        <input type="text" name="bank_2_name" value="{{ $qris->bank_2_name }}" class="w-full p-3 border rounded-xl bg-gray-50" required>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1 block">No Rekening</label>
                        <input type="text" name="bank_2_number" value="{{ $qris->bank_2_number }}" class="w-full p-3 border rounded-xl bg-gray-50" required>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1 block">Atas Nama</label>
                        <input type="text" name="bank_2_owner" value="{{ $qris->bank_2_owner }}" class="w-full p-3 border rounded-xl bg-gray-50" required>
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-4">
                    <button type="button" onclick="document.getElementById('modal-edit-rekening').classList.add('hidden')" class="w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-edit-qris', 'qris-va')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function downloadQRIS() {
        // Ambil URL gambar dari tag img
        const imageUrl = document.getElementById('qris-image').src;

        // Mengubah gambar menjadi blob agar bisa diunduh langsung tanpa buka tab baru
        fetch(imageUrl)
            .then(response => response.blob())
            .then(blob => {
                const blobUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = blobUrl;
                a.download = 'QRIS-Kas-RT.png'; // Nama file default

                document.body.appendChild(a);
                a.click();

                window.URL.revokeObjectURL(blobUrl);
                document.body.removeChild(a);
            })
            .catch(() => alert('Gagal mengunduh QRIS.'));
    }
</script>
