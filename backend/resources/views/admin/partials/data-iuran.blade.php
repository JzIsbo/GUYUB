<div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] relative">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Master Data Iuran</h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Konfigurasi jenis dan tarif iuran warga</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-iuran').classList.remove('hidden')" class="bg-[#EFF6FF] text-[#2563EB] px-6 py-3 rounded-2xl font-bold hover:scale-[1.03] transition shadow-sm flex items-center shrink-0">
            <i class="fa-solid fa-plus-circle mr-2 text-lg"></i> Tambah Jenis Iuran
        </button>
    </div>

    <div class="overflow-x-auto min-h-[200px]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-gray-400 text-[10px] uppercase tracking-widest">
                    <th class="p-4 rounded-l-2xl font-bold">Nama Iuran</th>
                    <th class="p-4 font-bold">Periode Penagihan</th>
                    <th class="p-4 font-bold text-center">Sifat</th>
                    <th class="p-4 rounded-r-2xl font-bold text-right">Tarif / Nominal</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($list_iuran as $item)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4">
                            <p class="font-bold text-gray-800">{{ $item->nama_iuran }}</p>
                            <p class="text-[10px] text-gray-400 font-medium tracking-wide mt-0.5">{{ $item->deskripsi ?? '-' }}</p>
                        </td>
                        <td class="p-4 font-bold text-gray-600">{{ $item->periode_penagihan }}</td>
                        <td class="p-4 text-center">
                            @if($item->sifat == 'Wajib')
                                <span class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">Wajib</span>
                            @else
                                <span class="bg-green-50 text-green-600 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">Sukarela</span>
                            @endif
                        </td>
                        <td class="p-4 font-black text-gray-900 text-right tracking-tight">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-10">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fa-solid fa-wallet text-4xl mb-3 text-gray-300"></i>
                                <p class="font-medium italic">Belum ada jenis penagihan iuran yang diatur...</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="modal-tambah-iuran" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative m-4">

            <button type="button" onclick="document.getElementById('modal-tambah-iuran').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <h3 class="text-2xl font-black text-gray-800 mb-6">Tambah Master Iuran Baru</h3>

            <form id="form-iuran" action="{{ route('iuran.store') }}" onsubmit="simpanDataUmum(event, 'form-iuran', 'data-iuran')">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama / Label Iuran</label>
                        <input type="text" name="nama_iuran" placeholder="Contoh: Iuran Keamanan RT" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Periode Penagihan</label>
                            <select name="periode_penagihan" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="Per Bulan">Per Bulan</option>
                                <option value="Per Tahun">Per Tahun</option>
                                <option value="Kondisional / Insidental">Kondisional</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Sifat Iuran</label>
                            <select name="sifat" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="Wajib">Wajib Dibayar</option>
                                <option value="Sukarela">Sukarela / Bebas</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nominal Tarif (Rp)</label>
                        <input type="number" name="nominal" placeholder="Contoh: 35000" min="0" required class="w-full bg-gray-50 border border-gray-200 text-sm font-black text-gray-800 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Keterangan Tambahan</label>
                        <input type="text" name="deskripsi" placeholder="Untuk keperluan operasional..." class="w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full mt-8 bg-[#2563EB] text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Master Iuran
                </button>
            </form>
        </div>
    </div>
</div>
