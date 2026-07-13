<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    {{-- ============ HERO BANNER ============ --}}
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 relative overflow-hidden">

        {{-- Decorative background icon --}}
        <i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-[6rem] text-white/[0.03] rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col gap-3">
            {{-- Left: Text content --}}
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 text-blue-200 text-[9px] font-bold uppercase tracking-[0.15em] px-2.5 py-1 rounded-full">
                    <i class="fa-solid fa-wallet text-[10px]"></i>
                    KONFIGURASI IURAN
                </div>
                <h1 class="text-lg font-black text-white tracking-tight">Master Data Iuran</h1>
                <p class="text-xs text-blue-200/70 font-medium">Konfigurasi jenis dan tarif iuran warga</p>
            </div>

            {{-- Right: Stats badge + Action button --}}
            <div class="flex items-center gap-2">
                {{-- Stats badge --}}
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 text-center min-w-[70px]">
                    <p class="text-lg font-black text-white">{{ count($list_iuran) }}</p>
                    <p class="text-[9px] font-bold text-blue-300/60 uppercase tracking-widest">Jenis Iuran</p>
                </div>

                {{-- Add button --}}
                <button onclick="document.getElementById('modal-tambah-iuran').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-lg shadow-blue-500/25 flex items-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-plus-circle text-sm"></i> Tambah Iuran
                </button>
            </div>
        </div>
    </div>

    {{-- ============ TABLE CARD ============ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-3">
            <div class="overflow-x-auto min-h-[120px]">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-400 text-[9px] uppercase tracking-widest">
                            <th class="px-3 py-2 rounded-l-xl font-bold">Nama Iuran</th>
                            <th class="px-3 py-2 font-bold">Periode</th>
                            <th class="px-3 py-2 font-bold text-center">Sifat</th>
                            <th class="px-3 py-2 rounded-r-xl font-bold text-right">Tarif</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @forelse($list_iuran as $item)
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                                <td class="px-3 py-2">
                                    <p class="font-bold text-gray-800 text-xs">{{ $item->nama_iuran }}</p>
                                    <p class="text-[9px] text-gray-400 font-medium tracking-wide mt-0.5 line-clamp-1">{{ $item->deskripsi ?? '-' }}</p>
                                </td>
                                <td class="px-3 py-2 font-bold text-gray-600 text-[10px]">{{ $item->periode_penagihan }}</td>
                                <td class="px-3 py-2 text-center">
                                    @if($item->sifat == 'Wajib')
                                        <span class="bg-red-50 text-red-600 px-2 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider">Wajib</span>
                                    @else
                                        <span class="bg-green-50 text-green-600 px-2 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider">Sukarela</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 font-black text-gray-900 text-right text-[11px] tracking-tight">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-6">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-wallet text-2xl mb-2 text-gray-300"></i>
                                        <p class="font-medium italic text-xs">Belum ada jenis penagihan iuran yang diatur...</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============ MODAL (unchanged logic) ============ --}}
    <div id="modal-tambah-iuran" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all">
        <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 shadow-2xl relative m-2">

            <button type="button" onclick="document.getElementById('modal-tambah-iuran').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>

            <h3 class="text-base font-black text-gray-800 mb-4">Tambah Master Iuran Baru</h3>

            <form id="form-iuran" action="{{ route('iuran.store') }}" onsubmit="simpanDataUmum(event, 'form-iuran', 'data-iuran')">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama / Label Iuran</label>
                        <input type="text" name="nama_iuran" placeholder="Contoh: Iuran Keamanan RT" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Periode Penagihan</label>
                            <select name="periode_penagihan" required class="w-full bg-gray-50 border border-gray-200 text-xs font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="Per Bulan">Per Bulan</option>
                                <option value="Per Tahun">Per Tahun</option>
                                <option value="Kondisional / Insidental">Kondisional</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Sifat Iuran</label>
                            <select name="sifat" required class="w-full bg-gray-50 border border-gray-200 text-xs font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="Wajib">Wajib Dibayar</option>
                                <option value="Sukarela">Sukarela / Bebas</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nominal Tarif (Rp)</label>
                        <input type="number" name="nominal" placeholder="Contoh: 35000" min="0" required class="w-full bg-gray-50 border border-gray-200 text-sm font-black text-gray-800 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Keterangan Tambahan</label>
                        <input type="text" name="deskripsi" placeholder="Untuk keperluan operasional..." class="w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full mt-5 bg-[#2563EB] text-white px-4 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Master Iuran
                </button>
            </form>
        </div>
    </div>

</div>
