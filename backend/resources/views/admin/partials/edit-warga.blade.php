<div class="p-6 bg-white shadow-lg rounded-2xl border border-gray-100">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Ubah Data Diri Warga</h2>

    <form id="form-edit-warga" onsubmit="updateWarga(event, {{ $warga->id }})">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ $warga->nama_lengkap }}" class="w-full p-3 border rounded-xl" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
                <input type="text" name="nik" value="{{ $warga->nik }}" class="w-full p-3 border rounded-xl" readonly>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Blok Rumah</label>
                <input type="text" name="blok_rumah" value="{{ $warga->blok_rumah }}" class="w-full p-3 border rounded-xl">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Domisili</label>
                <select name="status_domisili" class="w-full p-3 border rounded-xl">
                    <option value="Tetap" {{ $warga->status_domisili == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                    <option value="Kontrak" {{ $warga->status_domisili == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                </select>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <button type="button" onclick="loadContent('data-warga')" class="px-6 py-3 text-gray-600 font-bold">Batal</button>
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
function updateWarga(e, id) {
    e.preventDefault();
    $.post("/admin/update-warga/" + id, $(e.target).serialize(), function(res) {
        alert(res.message);
        loadContent('data-warga');
    });
}
</script>
