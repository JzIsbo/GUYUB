<style>
/* CSS overrides for Dark Mode on Sembako Koperasi Cards */
html.dark .koperasi-sembako-card {
    background: #1e293b !important;
    border-color: #334155 !important;
}
html.dark .koperasi-sembako-card h4 {
    color: #f8fafc !important;
}
html.dark .koperasi-sembako-card p {
    color: #94a3b8 !important;
}
html.dark .koperasi-sembako-card .harga-text {
    color: #60a5fa !important;
}
</style>

<div class="p-4 lg:p-8 space-y-8 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2.5rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-2xl border border-white/10">
        <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-emerald-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-2xl pointer-events-none"></div>
        <i class="fa-solid fa-building-columns absolute -bottom-8 -right-6 text-[150px] opacity-[0.03] rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-building-columns text-blue-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-blue-300/90">Layanan Ekonomi Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Koperasi Warga RT & RW</h1>
                <p class="text-sm text-blue-200/70 font-medium mt-1">Penyedia Kebutuhan Pokok, Layanan Simpan Pinjam, dan Akses Permodalan UMKM</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <!-- Stat 1: Sembako -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-3 text-center">
                    <p class="text-xl font-black text-white leading-none">{{ count($list_koperasi ?? []) }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-blue-300/80 mt-1">Produk Sembako</p>
                </div>
                <!-- Stat 2: Simpanan -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-3 text-center">
                    <p class="stat-counter text-xl font-black text-emerald-400 leading-none" data-value="{{ $total_simpanan ?? 0 }}" data-type="currency">Rp 0</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-emerald-300/80 mt-1">Total Simpanan</p>
                </div>
                <!-- Stat 3: Pinjaman -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-3 text-center">
                    <p class="stat-counter text-xl font-black text-amber-400 leading-none" data-value="{{ $total_pinjaman ?? 0 }}" data-type="currency">Rp 0</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-amber-300/80 mt-1">Pinjaman Aktif</p>
                </div>
                <!-- Stat 4: Permodalan UMKM -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-3 text-center">
                    <p class="stat-counter text-xl font-black text-cyan-400 leading-none" data-value="{{ $total_permodalans ?? 0 }}" data-type="currency">Rp 0</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-cyan-300/80 mt-1">Modal UMKM</p>
                </div>
            </div>
        </div>

        <!-- Tab Controls Header -->
        <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 max-w-full">
                <button onclick="switchKoperasiTab('sembako')" id="tab-btn-sembako" class="tab-kop-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-blue-500 text-white shadow-lg shadow-blue-500/25">
                    <i class="fa-solid fa-wheat-awn"></i> Kebutuhan Pokok (Sembako)
                </button>
                <button onclick="switchKoperasiTab('simpanan')" id="tab-btn-simpanan" class="tab-kop-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-white/10 text-gray-300 hover:bg-white/20">
                    <i class="fa-solid fa-piggy-bank"></i> Simpanan Warga
                </button>
                <button onclick="switchKoperasiTab('pinjaman')" id="tab-btn-pinjaman" class="tab-kop-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-white/10 text-gray-300 hover:bg-white/20">
                    <i class="fa-solid fa-hand-holding-dollar"></i> Simpan Pinjam
                </button>
                <button onclick="switchKoperasiTab('permodalan')" id="tab-btn-permodalan" class="tab-kop-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-white/10 text-gray-300 hover:bg-white/20">
                    <i class="fa-solid fa-rocket"></i> Permodalan UMKM
                </button>
            </div>

            <!-- Context Actions -->
            <div class="flex items-center gap-2">
                @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                <button onclick="document.getElementById('modal-tambah-koperasi').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2 rounded-xl transition-all text-xs flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Produk Sembako
                </button>
                @endif
                <button onclick="document.getElementById('modal-setor-simpanan').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2 rounded-xl transition-all text-xs flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-plus-circle text-xs"></i> Setor Simpanan
                </button>
                <button onclick="document.getElementById('modal-ajukan-pinjaman').classList.remove('hidden')" class="bg-amber-600 hover:bg-amber-500 text-white font-bold px-4 py-2 rounded-xl transition-all text-xs flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-hand-holding-dollar text-xs"></i> Ajukan Pinjaman
                </button>
                <button onclick="document.getElementById('modal-ajukan-permodalan').classList.remove('hidden')" class="bg-purple-600 hover:bg-purple-500 text-white font-bold px-4 py-2 rounded-xl transition-all text-xs flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-lightbulb text-xs"></i> Ajukan Modal UMKM
                </button>
            </div>
        </div>
    </div>

    <!-- ================= TAB 1: KEBUTUHAN POKOK (SEMBAKO) ================= -->
    <div id="tab-content-sembako" class="tab-kop-content space-y-6">
        <!-- Grid Produk Sembako -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-gray-800 tracking-tight">Katalog Kebutuhan Pokok Sembako</h3>
                    <p class="text-xs text-gray-400 font-medium">Bahan pangan pokok harga terjangkau subsidi Koperasi RT/RW</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($list_koperasi ?? [] as $item)
                <div class="koperasi-sembako-card bg-gradient-to-b from-gray-50 to-white rounded-3xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                    <div>
                        <!-- Gambar Produk -->
                        <div class="relative w-full h-44 rounded-2xl overflow-hidden mb-4 bg-gray-100 border border-gray-100 group">
                            <img src="{{ $item->foto ? $item->foto : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-2.5 left-2.5">
                                <span class="bg-blue-600/90 text-white backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm">{{ $item->kategori }}</span>
                            </div>
                            <div class="absolute top-2.5 right-2.5">
                                <span class="text-xs font-bold {{ $item->stok > 0 ? 'text-emerald-700 bg-emerald-100/90 border border-emerald-300/30' : 'text-red-700 bg-red-100/90 border border-red-300/30' }} backdrop-blur-md px-2.5 py-1 rounded-lg shadow-sm">
                                    {{ $item->stok > 0 ? 'Stok: '.$item->stok.' '.$item->satuan : 'Stok Habis' }}
                                </span>
                            </div>
                        </div>
                        <h4 class="text-base font-black text-gray-800 leading-snug">{{ $item->nama_produk }}</h4>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $item->deskripsi ?? 'Penyedia bahan pangan pokok kualitas terjamin.' }}</p>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Harga Per {{ $item->satuan }}</p>
                            <p class="harga-text text-lg font-black text-blue-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                            <button onclick="editSembako({{ $item->id }}, '{{ addslashes($item->nama_produk) }}', '{{ $item->kategori }}', {{ $item->harga }}, {{ $item->stok }}, '{{ addslashes($item->satuan) }}', '{{ addslashes($item->deskripsi ?? '') }}')" class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center justify-center" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button onclick="hapusGeneral('/koperasi/delete', {{ $item->id }}, 'Hapus produk sembako ini?')" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition flex items-center justify-center" title="Hapus">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            @endif
                            <button onclick="bukaModalPesan({{ $item->id }}, '{{ addslashes($item->nama_produk) }}', {{ $item->harga }}, {{ $item->stok }})" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition flex items-center gap-1.5 shadow-sm" {{ $item->stok <= 0 ? 'disabled' : '' }}>
                                <i class="fa-solid fa-cart-shopping text-xs"></i> Beli
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-gray-400 italic font-medium">Belum ada katalog kebutuhan pokok terdaftar.</div>
                @endforelse
            </div>
        </div>

        <!-- Tabel Riwayat Pesanan Sembako Warga -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-black text-gray-800 text-sm">Daftar Transaksi Pemesanan Sembako</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100">
                            <th class="py-3 px-6">Tgl Pesan</th>
                            <th class="py-3 px-6">Pemesan</th>
                            <th class="py-3 px-6">Nama Produk</th>
                            <th class="py-3 px-6">Jumlah</th>
                            <th class="py-3 px-6">Total Pembayaran</th>
                            <th class="py-3 px-6">Metode</th>
                            <th class="py-3 px-6">Status</th>
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                            <th class="py-3 px-6 text-center">Aksi Pengurus</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                        @forelse($list_orders ?? [] as $order)
                        <tr>
                            <td class="py-3 px-6">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</td>
                            <td class="py-3 px-6 font-bold text-gray-800">{{ $order->nama_warga }}</td>
                            <td class="py-3 px-6 font-semibold">{{ $order->nama_produk }}</td>
                            <td class="py-3 px-6">{{ $order->jumlah }} Unit</td>
                            <td class="py-3 px-6 font-bold text-blue-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-6">{{ $order->metode_pembayaran }}</td>
                            <td class="py-3 px-6">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $order->status == 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($order->status == 'Diproses' ? 'bg-blue-100 text-blue-700' : ($order->status == 'Dibatalkan' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700')) }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                            <td class="py-3 px-6 text-center">
                                <select onchange="updateOrderStatus({{ $order->id }}, this.value)" class="bg-gray-50 border border-gray-200 rounded-lg text-[11px] font-bold py-1 px-2">
                                    <option value="Menunggu" {{ $order->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-8 text-center text-gray-400 italic">Belum ada riwayat pemesanan sembako.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: SIMPANAN WARGA ================= -->
    <div id="tab-content-simpanan" class="tab-kop-content space-y-6 hidden">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-gray-800">Catatan & Riwayat Simpanan Anggota Warga</h3>
                    <p class="text-xs text-gray-400 font-medium">Simpanan Pokok, Wajib, dan Sukarela untuk ketahanan finansial warga</p>
                </div>
                <button onclick="document.getElementById('modal-setor-simpanan').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-plus text-xs"></i> Setor Simpanan
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100">
                            <th class="py-3.5 px-6">Tanggal Setor</th>
                            <th class="py-3.5 px-6">Nama Anggota / Warga</th>
                            <th class="py-3.5 px-6">Jenis Simpanan</th>
                            <th class="py-3.5 px-6">Jumlah (Rp)</th>
                            <th class="py-3.5 px-6">Metode Bayar</th>
                            <th class="py-3.5 px-6">Status Verifikasi</th>
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                            <th class="py-3.5 px-6 text-center">Aksi Verifikasi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                        @forelse($list_simpanan ?? [] as $simp)
                        <tr>
                            <td class="py-3.5 px-6">{{ \Carbon\Carbon::parse($simp->created_at)->format('d M Y') }}</td>
                            <td class="py-3.5 px-6 font-bold text-gray-800">{{ $simp->nama_warga }}</td>
                            <td class="py-3.5 px-6"><span class="bg-emerald-50 text-emerald-700 font-bold px-2.5 py-1 rounded-md text-[10px]">{{ $simp->jenis_simpanan }}</span></td>
                            <td class="py-3.5 px-6 font-black text-emerald-600">Rp {{ number_format($simp->jumlah, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6">{{ $simp->metode_pembayaran }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $simp->status == 'Disetujui' ? 'bg-emerald-100 text-emerald-700' : ($simp->status == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $simp->status }}
                                </span>
                            </td>
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                            <td class="py-3.5 px-6 text-center space-x-1">
                                @if($simp->status != 'Disetujui')
                                <button onclick="prosesSimpanan({{ $simp->id }}, 'Disetujui')" class="px-2.5 py-1 bg-emerald-500 text-white rounded-lg font-bold text-[10px] hover:bg-emerald-600">Setujui</button>
                                @endif
                                @if($simp->status != 'Ditolak')
                                <button onclick="prosesSimpanan({{ $simp->id }}, 'Ditolak')" class="px-2.5 py-1 bg-red-500 text-white rounded-lg font-bold text-[10px] hover:bg-red-600">Tolak</button>
                                @endif
                                <button onclick="hapusGeneral('/koperasi/simpanan/delete', {{ $simp->id }}, 'Hapus data simpanan ini?')" class="p-1 text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="7" class="py-8 text-center text-gray-400 italic">Belum ada catatan simpanan warga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TAB 3: SIMPAN PINJAM (PINJAMAN WARGA) ================= -->
    <div id="tab-content-pinjaman" class="tab-kop-content space-y-6 hidden">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-gray-800">Daftar Pengajuan Pinjaman Dana Koperasi</h3>
                    <p class="text-xs text-gray-400 font-medium">Bantuan pinjaman dana darurat & produktif warga dengan tenor fleksibel</p>
                </div>
                <button onclick="document.getElementById('modal-ajukan-pinjaman').classList.remove('hidden')" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-hand-holding-dollar text-xs"></i> Ajukan Pinjaman
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100">
                            <th class="py-3.5 px-6">Tgl Pengajuan</th>
                            <th class="py-3.5 px-6">Nama Pemohon</th>
                            <th class="py-3.5 px-6">Jumlah Pinjaman</th>
                            <th class="py-3.5 px-6">Tenor</th>
                            <th class="py-3.5 px-6">Cicilan / Bln</th>
                            <th class="py-3.5 px-6">Keperluan</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-center">Aksi & Pelunasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                        @forelse($list_pinjaman ?? [] as $pinj)
                        <tr>
                            <td class="py-3.5 px-6">{{ \Carbon\Carbon::parse($pinj->created_at)->format('d M Y') }}</td>
                            <td class="py-3.5 px-6 font-bold text-gray-800">{{ $pinj->nama_warga }}</td>
                            <td class="py-3.5 px-6 font-black text-amber-600">Rp {{ number_format($pinj->jumlah_pinjaman, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6 font-bold">{{ $pinj->tenor_bulan }} Bulan</td>
                            <td class="py-3.5 px-6 font-semibold">Rp {{ number_format($pinj->angsuran_per_bulan, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6 max-w-xs truncate">{{ $pinj->keperluan }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $pinj->status == 'Lunas' ? 'bg-emerald-100 text-emerald-700' : ($pinj->status == 'Disetujui' ? 'bg-blue-100 text-blue-700' : ($pinj->status == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700')) }}">
                                    {{ $pinj->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center space-x-1">
                                @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                                    @if($pinj->status == 'Menunggu')
                                    <button onclick="prosesPinjaman({{ $pinj->id }}, 'approve')" class="px-2.5 py-1 bg-emerald-500 text-white rounded-lg font-bold text-[10px] hover:bg-emerald-600">Setujui</button>
                                    <button onclick="prosesPinjaman({{ $pinj->id }}, 'reject')" class="px-2.5 py-1 bg-red-500 text-white rounded-lg font-bold text-[10px] hover:bg-red-600">Tolak</button>
                                    @endif
                                @endif
                                @if($pinj->status == 'Disetujui')
                                <button onclick="bayarPinjaman({{ $pinj->id }})" class="px-2.5 py-1 bg-blue-600 text-white rounded-lg font-bold text-[10px] hover:bg-blue-700">Tandai Lunas</button>
                                @endif
                                @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'RT', 'Bendahara RT']))
                                <button onclick="hapusGeneral('/koperasi/pinjaman/delete', {{ $pinj->id }}, 'Hapus catatan pinjaman ini?')" class="p-1 text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-8 text-center text-gray-400 italic">Belum ada pengajuan simpan pinjam.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TAB 4: AKSES PERMODALAN UMKM ================= -->
    <div id="tab-content-permodalan" class="tab-kop-content space-y-6 hidden">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-gray-800">Bantuan Akses Permodalan Usaha UMKM Warga</h3>
                    <p class="text-xs text-gray-400 font-medium">Pengajuan bantuan dana pendampingan & permodalan rintisan usaha warga</p>
                </div>
                <button onclick="document.getElementById('modal-ajukan-permodalan').classList.remove('hidden')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-rocket text-xs"></i> Ajukan Modal UMKM
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100">
                            <th class="py-3.5 px-6">Tgl Pengajuan</th>
                            <th class="py-3.5 px-6">Nama Pemohon</th>
                            <th class="py-3.5 px-6">Nama Usaha</th>
                            <th class="py-3.5 px-6">Kategori</th>
                            <th class="py-3.5 px-6">Nominal Pengajuan</th>
                            <th class="py-3.5 px-6">Deskripsi Usaha</th>
                            <th class="py-3.5 px-6">Status Pencairan</th>
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                            <th class="py-3.5 px-6 text-center">Aksi Pengurus</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                        @forelse($list_permodalans ?? [] as $perm)
                        <tr>
                            <td class="py-3.5 px-6">{{ \Carbon\Carbon::parse($perm->created_at)->format('d M Y') }}</td>
                            <td class="py-3.5 px-6 font-bold text-gray-800">{{ $perm->nama_warga }}</td>
                            <td class="py-3.5 px-6 font-bold text-purple-700">{{ $perm->nama_usaha }}</td>
                            <td class="py-3.5 px-6"><span class="bg-purple-50 text-purple-700 font-bold px-2 py-0.5 rounded text-[10px]">{{ $perm->kategori_umkm }}</span></td>
                            <td class="py-3.5 px-6 font-black text-purple-600">Rp {{ number_format($perm->nominal_pengajuan, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6 max-w-xs truncate">{{ $perm->deskripsi_usaha }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $perm->status == 'Dicairkan' ? 'bg-purple-100 text-purple-700' : ($perm->status == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $perm->status }}
                                </span>
                            </td>
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                            <td class="py-3.5 px-6 text-center space-x-1">
                                @if($perm->status == 'Menunggu')
                                <button onclick="prosesPermodalan({{ $perm->id }}, 'approve')" class="px-2.5 py-1 bg-purple-600 text-white rounded-lg font-bold text-[10px] hover:bg-purple-700">Setujui & Cairkan</button>
                                <button onclick="prosesPermodalan({{ $perm->id }}, 'reject')" class="px-2.5 py-1 bg-red-500 text-white rounded-lg font-bold text-[10px] hover:bg-red-600">Tolak</button>
                                @endif
                                <button onclick="hapusGeneral('/koperasi/permodalan/delete', {{ $perm->id }}, 'Hapus pengajuan permodalan ini?')" class="p-1 text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-8 text-center text-gray-400 italic">Belum ada pengajuan permodalan UMKM.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. Modal Tambah / Edit Produk Sembako -->
<div id="modal-tambah-koperasi" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-koperasi').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 id="modal-sembako-title" class="text-xl font-black text-gray-800 mb-6">Tambah Produk Kebutuhan Pokok</h3>
        <form id="form-koperasi" action="/koperasi/store" method="POST" enctype="multipart/form-data" onsubmit="simpanDataUmum(event, 'form-koperasi', 'koperasi')">
            <input type="hidden" name="id" id="sembako-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Produk / Sembako</label>
                    <input type="text" name="nama_produk" id="sembako-nama" placeholder="Contoh: Beras Premium Rajawali 5kg" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                        <select name="kategori" id="sembako-kategori" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Sembako">Sembako</option>
                            <option value="Bahan Makanan">Bahan Makanan</option>
                            <option value="Kebutuhan Rumah Tangga">Kebutuhan Rumah Tangga</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" id="sembako-harga" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Stok Tersedia</label>
                        <input type="number" name="stok" id="sembako-stok" min="0" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Satuan (kg/liter/pcs/karung)</label>
                        <input type="text" name="satuan" id="sembako-satuan" placeholder="kg" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Foto Produk (Opsional)</label>
                    <input type="file" name="foto" id="sembako-foto" accept="image/*" class="w-full bg-gray-50 border border-gray-200 font-medium text-xs text-gray-700 p-2.5 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Produk (Opsional)</label>
                    <textarea name="deskripsi" id="sembako-deskripsi" rows="2" class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Informasi detail barang..."></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-koperasi').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Modal Beli / Pesan Sembako -->
<div id="modal-pesan-sembako" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-pesan-sembako').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-2">Pesan Sembako Koperasi</h3>
        <p id="pesan-nama-produk" class="text-sm font-bold text-blue-600 mb-6"></p>
        <form id="form-pesan-sembako" action="/koperasi/order" method="POST" onsubmit="simpanDataUmum(event, 'form-pesan-sembako', 'koperasi')">
            <input type="hidden" name="item_id" id="pesan-item-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Beli</label>
                    <input type="number" name="jumlah" id="pesan-jumlah" value="1" min="1" oninput="hitungTotalOrder()" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Tunai Saat Ambil">Tunai Saat Pengambilan</option>
                        <option value="Potong Saldo Koperasi">Potong Saldo Simpanan Sukarela</option>
                    </select>
                </div>
                <div class="p-4 bg-blue-50 rounded-2xl flex items-center justify-between">
                    <span class="text-xs font-bold text-blue-600 uppercase">Total Bayar:</span>
                    <span id="pesan-total-bayar" class="text-lg font-black text-blue-700">Rp 0</span>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-pesan-sembako').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200">Konfirmasi Pesan</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Modal Setor Simpanan Warga -->
<div id="modal-setor-simpanan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-setor-simpanan').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Setor Simpanan Warga</h3>
        <form id="form-simpanan" action="/koperasi/simpanan/store" method="POST" onsubmit="simpanDataUmum(event, 'form-simpanan', 'koperasi')">
            <div class="space-y-4">
                @if(in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']))
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Anggota Warga</label>
                    <select name="warga_id" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        <option value="">-- Saya Sendiri ({{ Auth::user()->name }}) --</option>
                        @foreach($all_warga ?? [] as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Simpanan</label>
                    <select name="jenis_simpanan" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        <option value="Simpanan Wajib">Simpanan Wajib (Bulanan)</option>
                        <option value="Simpanan Pokok">Simpanan Pokok (Awal Keanggotaan)</option>
                        <option value="Simpanan Sukarela">Simpanan Sukarela (Tabungan Bebas)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Setoran (Rp)</label>
                    <input type="number" name="jumlah" min="10000" step="5000" placeholder="50000" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        <option value="Transfer Bank / QRIS">Transfer Bank / QRIS Kas RT-RW</option>
                        <option value="Tunai via Bendahara">Tunai via Bendahara Lingkungan</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-setor-simpanan').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200">Kirim Setoran</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Modal Ajukan Pinjaman -->
<div id="modal-ajukan-pinjaman" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-ajukan-pinjaman').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Pengajuan Simpan Pinjam Warga</h3>
        <form id="form-pinjaman" action="/koperasi/pinjaman/store" method="POST" onsubmit="simpanDataUmum(event, 'form-pinjaman', 'koperasi')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Nominal Pinjaman (Rp)</label>
                    <input type="number" name="jumlah_pinjaman" id="pinj-nominal" min="100000" step="50000" placeholder="1000000" oninput="hitungSimulasiPinjaman()" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jangka Waktu / Tenor (Bulan)</label>
                    <select name="tenor_bulan" id="pinj-tenor" onchange="hitungSimulasiPinjaman()" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        <option value="3">3 Bulan</option>
                        <option value="6" selected>6 Bulan</option>
                        <option value="12">12 Bulan</option>
                        <option value="24">24 Bulan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tujuan / Keperluan Pinjaman</label>
                    <textarea name="keperluan" rows="2" placeholder="Biaya kesehatan, renovasi ringan, pendidikan..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 rounded-2xl"></textarea>
                </div>
                <div class="p-4 bg-amber-50 rounded-2xl flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700 uppercase">Simulasi Cicilan/Bln:</span>
                    <span id="pinj-simulasi" class="text-base font-black text-amber-800">Rp 0 / bln</span>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-ajukan-pinjaman').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-200">Kirim Permohonan</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Modal Ajukan Permodalan UMKM -->
<div id="modal-ajukan-permodalan" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-ajukan-permodalan').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Pengajuan Akses Permodalan UMKM</h3>
        <form id="form-permodalan" action="/koperasi/permodalan/store" method="POST" onsubmit="simpanDataUmum(event, 'form-permodalan', 'koperasi')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Usaha / Bisnis Rintisan</label>
                    <input type="text" name="nama_usaha" placeholder="Warung Kopi & Camilan Warga" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori UMKM</label>
                        <select name="kategori_umkm" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                            <option value="Kuliner">Kuliner / Makanan</option>
                            <option value="Kerajinan & Seni">Kerajinan & Seni</option>
                            <option value="Jasa & Keterampilan">Jasa & Keterampilan</option>
                            <option value="Pertanian & Peternakan">Pertanian & Peternakan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Pengajuan (Rp)</label>
                        <input type="number" name="nominal_pengajuan" min="500000" step="100000" placeholder="2000000" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rencana Usaha & Penggunaan Modal</label>
                    <textarea name="deskripsi_usaha" rows="3" placeholder="Jelaskan kebutuhan etalase, alat usaha, atau stok barang..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 rounded-2xl"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-ajukan-permodalan').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-2xl shadow-lg shadow-purple-200">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<script>
let activeHargaPerUnit = 0;

function switchKoperasiTab(tabName) {
    document.querySelectorAll('.tab-kop-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-kop-btn').forEach(btn => {
        btn.className = 'tab-kop-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-white/10 text-gray-300 hover:bg-white/20';
    });
    
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.className = 'tab-kop-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-blue-500 text-white shadow-lg shadow-blue-500/25';
}

function bukaModalPesan(id, nama, harga, stok) {
    activeHargaPerUnit = harga;
    document.getElementById('pesan-item-id').value = id;
    document.getElementById('pesan-nama-produk').innerText = nama + " (Harga: Rp " + harga.toLocaleString('id-ID') + ")";
    document.getElementById('pesan-jumlah').value = 1;
    document.getElementById('pesan-jumlah').max = stok;
    hitungTotalOrder();
    document.getElementById('modal-pesan-sembako').classList.remove('hidden');
}

function hitungTotalOrder() {
    const qty = parseInt(document.getElementById('pesan-jumlah').value) || 1;
    const total = activeHargaPerUnit * qty;
    document.getElementById('pesan-total-bayar').innerText = 'Rp ' + total.toLocaleString('id-ID');
}

function editSembako(id, nama, kategori, harga, stok, satuan, deskripsi) {
    document.getElementById('modal-sembako-title').innerText = 'Edit Produk Kebutuhan Pokok';
    document.getElementById('form-koperasi').action = '/koperasi/update';
    document.getElementById('sembako-id').value = id;
    document.getElementById('sembako-nama').value = nama;
    document.getElementById('sembako-kategori').value = kategori;
    document.getElementById('sembako-harga').value = harga;
    document.getElementById('sembako-stok').value = stok;
    document.getElementById('sembako-satuan').value = satuan;
    document.getElementById('sembako-deskripsi').value = deskripsi;
    document.getElementById('modal-tambah-koperasi').classList.remove('hidden');
}

function updateOrderStatus(id, status) {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('status', status);
    fd.append('_token', window.csrfToken);
    fetch('/koperasi/order/status', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => alert(d.message || 'Status diperbarui'));
}

function reloadKoperasiPage() {
    if (typeof window.invalidatePageCache === 'function') {
        window.invalidatePageCache('koperasi');
    }
    const navLink = document.querySelector(".menu-link[onclick*='koperasi']") || document.querySelector('.menu-active');
    switchPage('koperasi', navLink);
}

function prosesSimpanan(id, status) {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('status', status);
    fd.append('_token', window.csrfToken);
    fetch('/koperasi/simpanan/approve', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { alert(d.message); reloadKoperasiPage(); });
}

function hitungSimulasiPinjaman() {
    const nom = parseFloat(document.getElementById('pinj-nominal').value) || 0;
    const tenor = parseInt(document.getElementById('pinj-tenor').value) || 6;
    if (nom > 0) {
        const cicilan = Math.ceil(nom / tenor);
        document.getElementById('pinj-simulasi').innerText = 'Rp ' + cicilan.toLocaleString('id-ID') + ' / bln';
    } else {
        document.getElementById('pinj-simulasi').innerText = 'Rp 0 / bln';
    }
}

function prosesPinjaman(id, action) {
    const url = action === 'approve' ? '/koperasi/pinjaman/approve' : '/koperasi/pinjaman/reject';
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { alert(d.message); reloadKoperasiPage(); });
}

function bayarPinjaman(id) {
    if (!confirm('Tandai pinjaman ini sebagai LUNAS?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/koperasi/pinjaman/pay', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { alert(d.message); reloadKoperasiPage(); });
}

function prosesPermodalan(id, action) {
    const url = action === 'approve' ? '/koperasi/permodalan/approve' : '/koperasi/permodalan/reject';
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { alert(d.message); reloadKoperasiPage(); });
}

function hapusGeneral(url, id, msg) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Konfirmasi Aksi',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl p-6 shadow-2xl font-sans',
                confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs',
                cancelButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                execHapusGeneral(url, id);
            }
        });
    } else {
        if (window.confirm(msg)) {
            execHapusGeneral(url, id);
        }
    }
}

function execHapusGeneral(url, id) {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Berhasil!',
                text: d.message || 'Data terhapus.',
                icon: 'success',
                confirmButtonColor: '#2563eb',
                customClass: { popup: 'rounded-3xl p-6 font-sans' }
            }).then(() => reloadKoperasiPage());
        } else {
            alert(d.message);
            reloadKoperasiPage();
        }
    });
}
</script>
