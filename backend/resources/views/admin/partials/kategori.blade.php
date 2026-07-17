<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    {{-- ============ HERO BANNER ============ --}}
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 relative overflow-hidden">
        {{-- Decorative background icon --}}
        <i class="fa-solid fa-coins absolute -right-6 -bottom-6 text-[10rem] text-white/[0.04] rotate-12 pointer-events-none"></i>
        <i class="fa-solid fa-coins absolute right-24 top-4 text-[4rem] text-white/[0.03] -rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            {{-- Left: Title area --}}
            <div class="space-y-3">
                {{-- Small badge --}}
                <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-full px-4 py-1.5">
                    <i class="fa-solid fa-coins text-amber-400 text-xs"></i>
                    <span class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Klasifikasi Keuangan</span>
                </div>

                <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight">Manajemen Kategori</h1>
                <p class="text-sm text-blue-200/70 font-medium">Klasifikasi sumber dana & pengeluaran</p>

                {{-- Stats badge --}}
                <div class="flex items-center gap-3 pt-1">
                    <div class="inline-flex items-center gap-2.5 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-2.5">
                        <div class="w-8 h-8 bg-amber-500/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-amber-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-lg font-black text-white leading-none">{{ count($list_kategori ?? []) }}</p>
                            <p class="text-[10px] text-blue-300/60 font-semibold uppercase tracking-wider">Total Kategori</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Action button --}}
            <div class="flex-shrink-0">
                <button onclick="document.getElementById('modal-tambah-kategori').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-bold hover:scale-[1.03] transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2">
                    <i class="fa-solid fa-plus text-sm"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ============ TABLE CARD ============ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto min-h-[200px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-400 text-[10px] uppercase tracking-widest">
                        <th class="p-4 rounded-l-2xl font-bold">Nama Kategori</th>
                        <th class="p-4 font-bold">Deskripsi</th>
                        <th class="p-4 font-bold text-center">Peruntukan</th>
                        <th class="p-4 font-bold text-center">Total Transaksi</th>
                        <th class="p-4 font-bold text-center rounded-r-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($list_kategori ?? [] as $item)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors group">
                            <td class="p-4 font-bold text-gray-800">
                                <i class="fa-solid fa-money-bill-wave text-emerald-400 mr-2"></i> {{ $item->nama }}
                            </td>
                            <td class="p-4 font-medium text-gray-500 text-xs">{{ $item->deskripsi ?? '-' }}</td>
                            <td class="p-4 text-center">
                                @if($item->tipe == 'pemasukan')
                                    <span class="bg-[#DCFCE7] text-[#16A34A] px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Pemasukan</span>
                                @else
                                    <span class="bg-[#FEE2E2] text-[#DC2626] px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-gray-600 text-center">{{ $item->total_dipakai ?? 0 }}x Dipakai</td>

                            <td class="p-4 text-center">
                                <button onclick="hapusKategori({{ $item->id }}, 'kategori')" class="text-red-500 hover:text-red-700 transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center p-8 italic text-gray-400">Belum ada kategori yang ditambahkan...</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============ MODAL TAMBAH KATEGORI ============ --}}
    <div id="modal-tambah-kategori" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-all">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl relative m-4">
            <button type="button" onclick="document.getElementById('modal-tambah-kategori').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <h3 class="text-2xl font-black text-gray-800 mb-6">Tambah Kategori</h3>

            <form id="form-kategori" action="{{ route('kategori.store') }}" onsubmit="simpanDataUmum(event, 'form-kategori', 'kategori')">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama Kategori</label>
                        <input type="text" name="nama" placeholder="Contoh: Dana Sosial" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Tipe (Peruntukan)</label>
                        <select name="tipe" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pemasukan">Untuk Pemasukan Kas</option>
                            <option value="pengeluaran">Untuk Pengeluaran Kas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Deskripsi Singkat</label>
                        <input type="text" name="deskripsi" placeholder="Opsional..." class="w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full mt-8 bg-[#2563EB] text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Kategori
                </button>
            </form>
        </div>
    </div>

</div>
