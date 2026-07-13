<div class="p-3">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-sm font-bold text-gray-800 mb-4">Ubah Data Diri</h2>
        <form id="form-edit-warga" onsubmit="updateWarga(event, {{ $warga->id }})">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ $warga->nama_lengkap }}" class="w-full py-2 px-3 border rounded-xl text-sm font-bold text-gray-700" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ $warga->nik }}" class="w-full py-2 px-3 border rounded-xl text-sm bg-gray-100 text-gray-500" readonly>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Blok Rumah</label>
                        <input type="text" name="blok_rumah" value="{{ $warga->blok_rumah }}" class="w-full py-2 px-3 border rounded-xl text-sm font-bold text-gray-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Domisili</label>
                        <select name="status_domisili" class="w-full py-2 px-3 border rounded-xl text-sm font-bold text-gray-700">
                            <option value="Tetap" {{ $warga->status_domisili == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="Kontrak" {{ $warga->status_domisili == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <button type="button" onclick="loadContent('data-warga')" class="flex-1 py-2.5 text-gray-600 font-bold text-xs rounded-xl bg-gray-100">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl font-bold text-xs">Simpan</button>
            </div>
        </form>
    </div>
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
