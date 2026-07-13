<div class="p-3 space-y-3 max-w-[100vw] mx-auto">

    {{-- ============ HERO BANNER ============ --}}
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-2xl p-4 relative overflow-hidden">
        {{-- Decorative background icon --}}
        <i class="fa-solid fa-coins absolute -right-4 -bottom-4 text-[6rem] text-white/[0.04] rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col gap-3">
            {{-- Title area --}}
            <div class="space-y-2">
                {{-- Small badge --}}
                <div class="inline-flex items-center gap-1.5 bg-white/5 backdrop-blur-md border border-white/10 rounded-full px-3 py-1">
                    <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>
                    <span class="text-[9px] font-bold text-blue-200 uppercase tracking-widest">Klasifikasi Keuangan</span>
                </div>

                <h1 class="text-lg font-black text-white tracking-tight">Manajemen Kategori</h1>
                <p class="text-xs text-blue-200/70 font-medium">Klasifikasi sumber dana & pengeluaran</p>

                {{-- Stats badge --}}
                <div class="flex items-center gap-2 pt-0.5">
                    <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-1.5">
                        <div class="w-6 h-6 bg-amber-500/20 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-amber-400 text-[10px]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-white leading-none">{{ count($list_kategori) }}</p>
                            <p class="text-[9px] text-blue-300/60 font-semibold uppercase tracking-wider">Total Kategori</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action button --}}
            <div>
                <button onclick="document.getElementById('modal-tambah-kategori').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2.5 rounded-xl font-bold text-xs hover:scale-[1.03] transition-all shadow-lg shadow-blue-500/25 flex items-center gap-1.5 w-full justify-center">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ============ TABLE CARD ============ --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto min-h-[150px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-400 text-[9px] uppercase tracking-widest">
                        <th class="px-3 py-2 rounded-l-xl font-bold">Kategori</th>
                        <th class="px-3 py-2 font-bold">Deskripsi</th>
                        <th class="px-3 py-2 font-bold text-center">Tipe</th>
                        <th class="px-3 py-2 font-bold text-center">Transaksi</th>
                        <th class="px-3 py-2 font-bold text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse($list_kategori as $item)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                            <td class="px-3 py-2 font-bold text-gray-800 text-[11px]">
                                <i class="fa-solid fa-money-bill-wave text-emerald-400 mr-1 text-[10px]"></i> {{ $item->nama }}
                            </td>
                            <td class="px-3 py-2 font-medium text-gray-500 text-[10px] max-w-[100px] truncate">{{ $item->deskripsi ?? '-' }}</td>
                            <td class="px-3 py-2 text-center">
                                @if($item->tipe == 'pemasukan')
                                    <span class="bg-[#DCFCE7] text-[#16A34A] px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider">Pemasukan</span>
                                @else
                                    <span class="bg-[#FEE2E2] text-[#DC2626] px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 font-bold text-gray-600 text-center text-[10px]">{{ $item->total_dipakai ?? 0 }}x</td>

                            <td class="px-3 py-2 text-center">
                                <button onclick="hapusKategori({{ $item->id }}, 'kategori')" class="text-red-500 hover:text-red-700 transition w-7 h-7 inline-flex items-center justify-center rounded-lg hover:bg-red-50">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center p-5 italic text-gray-400 text-xs">Belum ada kategori yang ditambahkan...</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============ MODAL TAMBAH KATEGORI ============ --}}
    <div id="modal-tambah-kategori" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all">
        <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 shadow-2xl relative m-3">
            <button type="button" onclick="document.getElementById('modal-tambah-kategori').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 bg-gray-50 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>

            <h3 class="text-lg font-black text-gray-800 mb-4">Tambah Kategori</h3>

            <form id="form-kategori" action="{{ route('kategori.store') }}" onsubmit="simpanDataUmum(event, 'form-kategori', 'kategori')">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Kategori</label>
                        <input type="text" name="nama" placeholder="Contoh: Dana Sosial" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipe (Peruntukan)</label>
                        <select name="tipe" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pemasukan">Untuk Pemasukan Kas</option>
                            <option value="pengeluaran">Untuk Pengeluaran Kas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Deskripsi Singkat</label>
                        <input type="text" name="deskripsi" placeholder="Opsional..." class="w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-2 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full mt-5 bg-[#2563EB] text-white px-4 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-save mr-1.5 text-xs"></i> Simpan Kategori
                </button>
            </form>
        </div>
    </div>

</div>
