<div class="p-6 bg-white rounded-[2rem] shadow-sm border border-gray-100">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800">Manajemen Database</h2>
        <p class="text-sm text-gray-500">Lakukan backup data secara berkala dan restore database jika diperlukan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="p-6 border border-emerald-100 bg-emerald-50 rounded-2xl">
            <h3 class="font-bold text-emerald-800 mb-2">Backup Database</h3>
            <p class="text-xs text-emerald-600 mb-6">Unduh seluruh isi database dalam format ZIP.</p>
            <a href="{{ route('admin.backup') }}"
               class="block text-center w-full px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition">
               Unduh Backup (.zip)
            </a>
        </div>

        <div class="p-6 border border-blue-100 bg-blue-50 rounded-2xl">
            <h3 class="font-bold text-blue-800 mb-2">Restore Database</h3>
            <p class="text-xs text-blue-600 mb-4">Pilih file backup (.sql) untuk memulihkan data.</p>

            <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col gap-3">
                    <input type="file" name="sql_file" id="sql_file" class="hidden" onchange="this.form.submit()">
                    <label for="sql_file" class="block text-center w-full px-6 py-3 bg-white border-2 border-dashed border-blue-200 text-blue-600 rounded-xl font-bold hover:bg-blue-100 cursor-pointer transition">
                        Pilih File & Restore
                    </label>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-8 p-4 bg-yellow-50 rounded-xl border border-yellow-100 flex items-start gap-3">
        <span class="text-yellow-600 text-lg">⚠️</span>
        <p class="text-xs text-yellow-700">
            <strong>Perhatian:</strong> Proses restore akan menimpa (overwrite) data Anda saat ini.
            Pastikan Anda sudah melakukan backup sebelum melakukan restore agar data tidak hilang.
        </p>
    </div>
</div>
