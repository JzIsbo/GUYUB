<div class="p-3 space-y-3">
    <h2 class="text-sm font-bold text-gray-800">QRIS & Virtual Account</h2>
    <p class="text-[10px] text-gray-500 mb-1">Rekening kas RT untuk pembayaran manual</p>

    <!-- QRIS -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
        <h3 class="text-xs font-bold text-gray-800 mb-1">QRIS KAS RT</h3>
        <p class="text-[9px] text-gray-500 mb-3">Scan via M-Banking / E-Wallet</p>
        <div class="p-2 bg-white rounded-xl border border-gray-200 mb-3 inline-block shadow-sm">
            <img id="qris-image" src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qris->qris_data ?? 'KOSONG') }}" alt="QRIS" class="w-[150px] h-[150px] object-cover">
        </div>
        <button onclick="downloadQRIS()" class="w-full bg-blue-50 text-blue-600 px-4 py-2 rounded-lg font-bold text-[10px]">
            <i class="fa-solid fa-download mr-1"></i> Download QRIS
        </button>
    </div>

    <!-- Bank Info -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-xs font-bold text-gray-800 mb-3">Rekening Bank</h3>
        <div class="space-y-2">
            <div class="p-3 border rounded-xl bg-gray-50">
                <p class="text-[9px] font-bold text-gray-500 uppercase">{{ $qris->bank_1_name }}</p>
                <h4 class="text-sm font-mono font-bold text-gray-800 tracking-wider">{{ $qris->bank_1_number }}</h4>
                <p class="text-[10px] font-bold text-blue-600">{{ $qris->bank_1_owner }}</p>
            </div>
            <div class="p-3 border rounded-xl bg-gray-50">
                <p class="text-[9px] font-bold text-gray-500 uppercase">{{ $qris->bank_2_name }}</p>
                <h4 class="text-sm font-mono font-bold text-gray-800 tracking-wider">{{ $qris->bank_2_number }}</h4>
                <p class="text-[10px] font-bold text-orange-600">{{ $qris->bank_2_owner }}</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-edit-rekening').classList.remove('hidden')" class="mt-3 w-full bg-gray-800 text-white px-4 py-2 rounded-lg font-bold text-[10px]">
            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Rekening
        </button>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-rekening" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm p-3">
    <div class="bg-white p-5 rounded-2xl w-full max-w-[95vw] shadow-xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-sm font-bold mb-3 text-gray-800">Edit Pembayaran</h2>
        <form id="form-edit-qris" action="/qris-va/update" method="POST">
            @csrf
            <div class="space-y-3">
                <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                    <label class="text-[9px] text-blue-800 font-bold uppercase block mb-1">QRIS Payload</label>
                    <input type="text" name="qris_data" value="{{ $qris->qris_data }}" class="w-full py-2 px-3 border rounded-xl bg-white text-sm" required>
                </div>
                <div>
                    <label class="text-[9px] text-gray-500 font-bold uppercase block mb-1">Bank 1</label>
                    <input type="text" name="bank_1_name" value="{{ $qris->bank_1_name }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm mb-1" required>
                    <div class="grid grid-cols-2 gap-1">
                        <input type="text" name="bank_1_number" value="{{ $qris->bank_1_number }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                        <input type="text" name="bank_1_owner" value="{{ $qris->bank_1_owner }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                    </div>
                </div>
                <div>
                    <label class="text-[9px] text-gray-500 font-bold uppercase block mb-1">Bank 2</label>
                    <input type="text" name="bank_2_name" value="{{ $qris->bank_2_name }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm mb-1" required>
                    <div class="grid grid-cols-2 gap-1">
                        <input type="text" name="bank_2_number" value="{{ $qris->bank_2_number }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                        <input type="text" name="bank_2_owner" value="{{ $qris->bank_2_owner }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm" required>
                    </div>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-edit-rekening').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-600 py-2.5 rounded-xl font-bold text-xs">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-edit-qris', 'qris-va')" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl font-bold text-xs">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function downloadQRIS() {
        const imageUrl = document.getElementById('qris-image').src;
        fetch(imageUrl).then(r => r.blob()).then(blob => {
            const a = document.createElement('a');
            a.href = window.URL.createObjectURL(blob);
            a.download = 'QRIS-Kas-RT.png';
            document.body.appendChild(a); a.click();
            window.URL.revokeObjectURL(a.href); document.body.removeChild(a);
        }).catch(() => alert('Gagal mengunduh QRIS.'));
    }
</script>
