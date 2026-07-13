<div class="p-3">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-sm font-bold text-gray-800 mb-1">Manajemen Database</h2>
        <p class="text-[10px] text-gray-500 mb-4">Backup & restore database sistem.</p>

        <div class="space-y-3">
            <div class="p-4 border border-emerald-100 bg-emerald-50 rounded-xl">
                <h3 class="font-bold text-emerald-800 text-xs mb-1">Backup Database</h3>
                <p class="text-[10px] text-emerald-600 mb-3">Unduh seluruh isi database dalam format ZIP.</p>
                <a href="{{ route('admin.backup') }}" class="block text-center w-full px-4 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 transition">Unduh Backup (.zip)</a>
            </div>

            <div class="p-4 border border-blue-100 bg-blue-50 rounded-xl">
                <h3 class="font-bold text-blue-800 text-xs mb-1">Restore Database</h3>
                <p class="text-[10px] text-blue-600 mb-3">Pilih file backup (.sql) untuk memulihkan data.</p>
                <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="sql_file" id="sql_file" class="hidden" onchange="this.form.submit()">
                    <label for="sql_file" class="block text-center w-full px-4 py-2.5 bg-white border-2 border-dashed border-blue-200 text-blue-600 rounded-xl font-bold text-xs hover:bg-blue-100 cursor-pointer transition">Pilih File & Restore</label>
                </form>
            </div>
        </div>

        <div class="mt-4 p-3 bg-yellow-50 rounded-xl border border-yellow-100 flex items-start gap-2">
            <span class="text-yellow-600 text-sm">⚠️</span>
            <p class="text-[10px] text-yellow-700"><strong>Perhatian:</strong> Restore akan menimpa data saat ini. Pastikan sudah backup terlebih dahulu.</p>
        </div>
    </div>
</div>
