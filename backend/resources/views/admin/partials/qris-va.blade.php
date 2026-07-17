<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">QRIS & Virtual Account</h2>
        <p class="text-sm text-gray-500">Informasi rekening utama kas RT untuk pembayaran manual</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <h3 class="text-lg font-bold text-gray-800 mb-2">QRIS KAS RT</h3>
            <p class="text-xs text-gray-500 mb-6">Scan menggunakan aplikasi M-Banking atau E-Wallet apapun</p>

            <div class="p-4 bg-white rounded-3xl border border-gray-200 mb-6 shadow-sm overflow-hidden flex items-center justify-center">
                <img id="qris-image" src="{{ $qris->qris_image ? asset($qris->qris_image) : 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qris->qris_data ?? 'KOSONG') }}"
                     alt="QRIS Barcode" class="max-w-[200px] max-h-[200px] object-contain">
            </div>

            @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
            <div class="flex gap-3 w-full">
                <button onclick="downloadQRIS()" class="flex-1 bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-100 transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-download"></i> Download QRIS
                </button>
                <button onclick="document.getElementById('direct-qris-input').click()" class="flex-1 bg-emerald-550 bg-emerald-50 text-emerald-600 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-100 transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Ganti QRIS
                </button>
                <input type="file" id="direct-qris-input" accept="image/*" class="hidden" onchange="uploadDirectQris(this)">
            </div>
            @else
            <button onclick="downloadQRIS()" class="bg-blue-50 text-blue-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-100 transition w-full">
                <i class="fa-solid fa-download mr-2"></i> Download QRIS
            </button>
            @endif
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

            @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
            <button onclick="document.getElementById('modal-edit-rekening').classList.remove('hidden')" class="mt-6 bg-gray-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-900 transition w-full shadow-md">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Rekening
            </button>
            @endif
        </div>
    </div>
</div>

@if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
<div id="modal-edit-rekening" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Data Pembayaran Manual</h2>

        <form id="form-edit-qris" action="/qris-va/update" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4">
                {{-- Custom QRIS Image Upload --}}
                <div class="bg-slate-50 p-4 rounded-xl border border-gray-200">
                    <label class="text-xs text-gray-700 font-bold uppercase tracking-wider mb-2 block">Upload Gambar QRIS (Kustom)</label>
                    <input type="file" name="qris_image" accept="image/*" class="w-full p-2.5 border rounded-xl bg-white text-xs">
                    @if($qris->qris_image)
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="clear_qris_image" value="1" id="clear_qris_image" class="rounded text-blue-600 focus:ring-blue-500">
                        <label for="clear_qris_image" class="text-[10px] font-semibold text-red-500 uppercase cursor-pointer">Hapus Gambar Kustom Saat Ini</label>
                    </div>
                    @endif
                    <p class="text-[10px] text-gray-400 mt-1.5">*Jika diunggah, gambar QRIS kustom akan ditampilkan secara prioritas daripada payload barcode generator.</p>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <label class="text-xs text-blue-800 font-bold uppercase tracking-wider mb-2 block">Data Payload QRIS (Fallback)</label>
                    <input type="text" name="qris_data" value="{{ $qris->qris_data }}" placeholder="Link URL atau Payload Teks Barcode" class="w-full p-3 border rounded-xl bg-white focus:ring-2 focus:ring-blue-300 outline-none" required>
                    <p class="text-[10px] text-blue-600 mt-1">*Digunakan sebagai fallback jika Anda tidak mengunggah gambar QRIS kustom.</p>
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
@endif

<script>
    function uploadDirectQris(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        const formData = new FormData();
        formData.append('qris_image', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Show spinner / loading overlay if possible, or simple alert
        const btn = document.querySelector('button[onclick*="direct-qris-input"]');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';

        fetch('/qris-va/upload-direct', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => {
            if (!res.ok) return res.json().then(e => { throw e; });
            return res.json();
        })
        .then(data => {
            alert('Gambar QRIS kustom berhasil diunggah!');
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('qris-va'); }
            switchPage('qris-va');
        })
        .catch(err => {
            alert('Gagal mengunggah QRIS: ' + (err.message || 'Terjadi kesalahan sistem.'));
            btn.disabled = false;
            btn.innerHTML = origText;
        });
    }

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
