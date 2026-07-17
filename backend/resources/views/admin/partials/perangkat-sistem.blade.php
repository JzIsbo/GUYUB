@php
    $summary = [];
    foreach($list_perangkat ?? [] as $item) {
        $jenis = $item->jenis_perangkat ?: 'Lainnya';
        if(!isset($summary[$jenis])) {
            $summary[$jenis] = 0;
        }
        $summary[$jenis] += $item->jumlah;
    }
    $isOfficer = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'Bendahara RW', 'RT', 'Sekretaris RT', 'Bendahara RT']);
@endphp

<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#334155] rounded-[2.5rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-2xl border border-white/10">
        <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-emerald-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-2xl pointer-events-none"></div>
        <i class="fa-solid fa-boxes-packing absolute -bottom-8 -right-6 text-[150px] opacity-[0.03] rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 border border-blue-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-packing text-blue-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-blue-300/90">Inventaris & Fasilitas Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Inventaris, Peminjaman & Pengembalian Aset</h1>
                <p class="text-sm text-slate-300/80 font-medium mt-1">Pencatatan aset, pengajuan peminjaman, serta pemeriksaan kondisi pengembalian fisik barang</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <!-- Stat 1: Total Aset -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-3 text-center">
                    <p class="text-2xl font-black text-white leading-none">{{ collect($list_perangkat ?? [])->sum('jumlah') }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-blue-300/80 mt-1">Total Unit Aset</p>
                </div>
                <!-- Stat 2: Dipinjam Aktif -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-3 text-center">
                    <p class="text-2xl font-black text-amber-400 leading-none">{{ $total_pinjam_aktif ?? 0 }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-amber-300/80 mt-1">Dipinjam Aktif</p>
                </div>
                <!-- Stat 3: Menunggu Approval -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-4 py-3 text-center col-span-2 sm:col-span-1">
                    <p class="text-2xl font-black text-rose-400 leading-none">{{ $total_pending_approval ?? 0 }}</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-rose-300/80 mt-1">Perlu Approval</p>
                </div>
            </div>
        </div>

        <!-- Tab Nav & Action Controls -->
        <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 max-w-full">
                <button onclick="switchAsetTab('inventaris')" id="tab-btn-inventaris" class="tab-aset-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-blue-600 text-white shadow-lg shadow-blue-600/25">
                    <i class="fa-solid fa-boxes-stacked"></i> Inventaris Aset
                </button>
                <button onclick="switchAsetTab('peminjaman')" id="tab-btn-peminjaman" class="tab-aset-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-white/10 text-gray-300 hover:bg-white/20">
                    <i class="fa-solid fa-clipboard-check"></i> Approval Peminjaman
                </button>
                <button onclick="switchAsetTab('pengembalian')" id="tab-btn-pengembalian" class="tab-aset-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-white/10 text-gray-300 hover:bg-white/20">
                    <i class="fa-solid fa-rotate-left"></i> Pengembalian & Verifikasi Aset
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="document.getElementById('modal-ajukan-peminjaman-aset').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2.5 rounded-xl transition-all text-xs flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-hand-holding-box text-xs"></i> Form Ajukan Pinjam Aset
                </button>
            </div>
        </div>
    </div>

    <!-- ================= TAB 1: INVENTARIS ASET ================= -->
    <div id="tab-content-inventaris" class="tab-aset-content grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- SUMMARY SIDEBAR --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[2.5rem] border border-gray-150/40 shadow-sm">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4"><i class="fa-solid fa-chart-pie text-blue-600 mr-1.5"></i> Ringkasan Kategori Aset</h4>
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="text-gray-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="pb-2">Jenis Aset</th>
                            <th class="pb-2 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-bold divide-y divide-slate-50">
                        @forelse($summary as $jenis => $count)
                        <tr>
                            <td class="py-2.5">{{ $jenis }}</td>
                            <td class="py-2.5 text-right font-black text-blue-600">{{ $count }} Unit</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-4 text-center text-gray-400 italic">Belum ada data inventaris</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FORM & TABLE --}}
        <div class="lg:col-span-2 space-y-8">
            @if($isOfficer)
            {{-- TAMBAH ASET BARU --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20">
                        <i class="fa-solid fa-plus-circle text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight leading-tight">Tambah Aset Baru</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Pencatatan Inventarisasi Lingkungan</p>
                    </div>
                </div>

                <form id="form-perangkat" action="{{ url('/admin/perangkat/store') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-perangkat', 'perangkat-sistem')">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider"><i class="fa-solid fa-tag text-indigo-500 mr-1.5"></i> Nama Aset</label>
                            <input type="text" name="nama_perangkat" placeholder="Contoh: Tenda Hajatan 4x6M" required class="w-full bg-slate-50 border border-slate-200/80 p-3.5 rounded-2xl font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider"><i class="fa-solid fa-box text-indigo-500 mr-1.5"></i> Jenis Aset</label>
                            <select id="jenis_select" name="jenis_perangkat" class="w-full bg-slate-50 border border-slate-200/80 p-3.5 rounded-2xl font-bold text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none" onchange="handleJenisChange(this, 'jenis_manual')">
                                <option value="Elektronik">Elektronik</option>
                                <option value="Peralatan Hajatan / Acara">Peralatan Hajatan / Acara</option>
                                <option value="Peralatan Kantor">Peralatan Kantor</option>
                                <option value="Peralatan Rumah Tangga">Peralatan Rumah Tangga</option>
                                <option value="Mebel / Perabotan">Mebel / Perabotan</option>
                                <option value="Keamanan">Keamanan</option>
                                <option value="Lainnya">Lainnya (Kustom)</option>
                            </select>
                            <input type="text" id="jenis_manual" placeholder="Ketik Jenis Kustom..." class="hidden w-full bg-slate-50 border border-slate-200/80 p-3.5 rounded-2xl font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none mt-2">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider"><i class="fa-solid fa-heart-pulse text-indigo-500 mr-1.5"></i> Kondisi Fisik</label>
                            <select name="kondisi" class="w-full bg-slate-50 border border-slate-200/80 p-3.5 rounded-2xl font-bold text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none">
                                <option value="Baik">Baik (Berfungsi Normal)</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider"><i class="fa-solid fa-calculator text-indigo-500 mr-1.5"></i> Jumlah (Unit)</label>
                            <input type="number" name="jumlah" placeholder="Jumlah unit..." min="1" value="1" required class="w-full bg-slate-50 border border-slate-200/80 p-3.5 rounded-2xl font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-2xl transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Inventaris Aset
                    </button>
                </form>
            </div>
            @endif

            {{-- KATALOG ASET --}}
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-black text-gray-800">Daftar Inventaris Aset Warga</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 uppercase font-extrabold tracking-wider border-b border-gray-100">
                                <th class="py-4 px-6">Nama Aset</th>
                                <th class="py-4 px-6">Jenis</th>
                                <th class="py-4 px-6">Jumlah Available</th>
                                <th class="py-4 px-6">Kondisi</th>
                                <th class="py-4 px-6 text-right">Aksi Pinjam / Edit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                            @forelse($list_perangkat ?? [] as $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 font-bold text-gray-800">{{ $item->nama_perangkat }}</td>
                                <td class="py-4 px-6"><span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold">{{ $item->jenis_perangkat }}</span></td>
                                <td class="py-4 px-6 font-black text-slate-800">{{ $item->jumlah }} Unit</td>
                                <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $item->badge_class ?? 'bg-gray-50 text-gray-600' }}">{{ $item->kondisi }}</span></td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <button onclick="bukaPinjamSpesifik('{{ addslashes($item->nama_perangkat) }}')" class="px-3 py-1.5 bg-emerald-600 text-white rounded-xl font-bold text-[10px] shadow-sm hover:bg-emerald-700 transition">
                                        <i class="fa-solid fa-hand-holding-box mr-1"></i> Pinjam
                                    </button>
                                    @if($isOfficer)
                                    <button onclick="editPerangkat({{ json_encode($item) }})" class="p-1.5 text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen"></i></button>
                                    <button onclick="hapusPerangkat({{ $item->id }})" class="p-1.5 text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-8 text-center text-gray-400 italic">Belum ada inventaris aset.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: APPROVAL PEMINJAMAN ================= -->
    <div id="tab-content-peminjaman" class="tab-aset-content space-y-6 hidden">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-gray-800">Tabel Approval Peminjaman Aset</h3>
                    <p class="text-xs text-gray-400 font-medium">Persetujuan permohonan pinjam aset baru warga</p>
                </div>
                <button onclick="document.getElementById('modal-ajukan-peminjaman-aset').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-plus text-xs"></i> Ajukan Pinjam Aset
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 uppercase font-extrabold tracking-wider border-b border-gray-100">
                            <th class="py-3.5 px-6">Tgl Pengajuan</th>
                            <th class="py-3.5 px-6">Nama Pemohon</th>
                            <th class="py-3.5 px-6">Nama Aset</th>
                            <th class="py-3.5 px-6">Jumlah</th>
                            <th class="py-3.5 px-6">Rencana Tgl Pinjam - Kembali</th>
                            <th class="py-3.5 px-6">Keperluan</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                        @forelse($list_peminjaman ?? [] as $loan)
                        <tr>
                            <td class="py-3.5 px-6">{{ \Carbon\Carbon::parse($loan->created_at)->format('d M Y') }}</td>
                            <td class="py-3.5 px-6 font-bold text-gray-800">{{ $loan->nama_warga }}</td>
                            <td class="py-3.5 px-6 font-bold text-blue-600">{{ $loan->nama_aset }}</td>
                            <td class="py-3.5 px-6 font-black">{{ $loan->jumlah_unit }} Unit</td>
                            <td class="py-3.5 px-6 font-semibold">
                                {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-6 max-w-xs truncate">{{ $loan->keperluan }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $loan->status == 'Sudah Dikembalikan' ? 'bg-slate-100 text-slate-700' : ($loan->status == 'Disetujui' ? 'bg-emerald-100 text-emerald-700' : ($loan->status == 'Proses Pengembalian' ? 'bg-purple-100 text-purple-700' : ($loan->status == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'))) }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center space-x-1">
                                @if($isOfficer && $loan->status == 'Menunggu Approval')
                                <button onclick="prosesApprovalLoan({{ $loan->id }}, 'approve')" class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg font-bold text-[10px] hover:bg-emerald-700">Setujui</button>
                                <button onclick="prosesApprovalLoan({{ $loan->id }}, 'reject')" class="px-2.5 py-1 bg-red-500 text-white rounded-lg font-bold text-[10px] hover:bg-red-600">Tolak</button>
                                @endif

                                @if(($loan->status == 'Disetujui' || $loan->status == 'Sedang Dipinjam' || $loan->status == 'Proses Pengembalian'))
                                    <button onclick="bukaModalSubmitKembali({{ $loan->id }}, '{{ addslashes($loan->nama_aset) }}')" class="px-2.5 py-1 bg-indigo-600 text-white rounded-lg font-bold text-[10px] hover:bg-indigo-700">Kembalikan Aset</button>
                                    @if($isOfficer)
                                    <button onclick="bukaModalVerifikasiKembali({{ $loan->id }}, '{{ addslashes($loan->nama_warga) }}', '{{ addslashes($loan->nama_aset) }}')" class="px-2.5 py-1 bg-emerald-700 text-white rounded-lg font-bold text-[10px] hover:bg-emerald-800">Verifikasi FISIK</button>
                                    @endif
                                @endif

                                @if($isOfficer)
                                <button onclick="hapusLoan({{ $loan->id }})" class="p-1 text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-8 text-center text-gray-400 italic">Belum ada data peminjaman aset.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TAB 3: PENGEMBALIAN & VERIFIKASI ASET ================= -->
    <div id="tab-content-pengembalian" class="tab-aset-content space-y-6 hidden">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-gray-800">Riwayat Pengembalian & Pemeriksaan Fisik Aset</h3>
                    <p class="text-xs text-gray-400 font-medium">Laporan tanggal aktual pengembalian, pengecekan fisik barang, dan denda kerusakan (jika ada)</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 uppercase font-extrabold tracking-wider border-b border-gray-100">
                            <th class="py-3.5 px-6">Tgl Dikembalikan</th>
                            <th class="py-3.5 px-6">Nama Pemohon</th>
                            <th class="py-3.5 px-6">Nama Aset</th>
                            <th class="py-3.5 px-6">Kondisi Pengembalian</th>
                            <th class="py-3.5 px-6">Denda / Catatan Inspection</th>
                            <th class="py-3.5 px-6">Status Final</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                        @forelse(collect($list_peminjaman ?? [])->whereIn('status', ['Sudah Dikembalikan', 'Proses Pengembalian']) as $ret)
                        <tr>
                            <td class="py-3.5 px-6 font-bold">{{ $ret->tanggal_dikembalikan_aktual ? \Carbon\Carbon::parse($ret->tanggal_dikembalikan_aktual)->format('d M Y') : \Carbon\Carbon::parse($ret->updated_at)->format('d M Y') }}</td>
                            <td class="py-3.5 px-6 font-bold text-gray-800">{{ $ret->nama_warga }}</td>
                            <td class="py-3.5 px-6 font-bold text-blue-600">{{ $ret->nama_aset }} ({{ $ret->jumlah_unit }} Unit)</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ str_contains($ret->kondisi_pengembalian ?? 'Baik', 'Baik') ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $ret->kondisi_pengembalian ?? 'Baik (Sesuai)' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <p class="text-gray-700 font-semibold">{{ $ret->catatan_pengembalian ?? '-' }}</p>
                                @if(($ret->denda_kerusakan ?? 0) > 0)
                                <p class="text-red-600 font-bold text-[11px] mt-0.5">Denda: Rp {{ number_format($ret->denda_kerusakan, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $ret->status == 'Sudah Dikembalikan' ? 'bg-slate-100 text-slate-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $ret->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-400 italic">Belum ada riwayat pengembalian aset.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. Modal Form Ajukan Pinjam Aset -->
<div id="modal-ajukan-peminjaman-aset" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-ajukan-peminjaman-aset').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Form Peminjaman Aset Warga</h3>
        <form id="form-loan-aset" action="/admin/perangkat/loan/store" method="POST" onsubmit="simpanDataUmum(event, 'form-loan-aset', 'perangkat-sistem')">
            <div class="space-y-4">
                @if($isOfficer)
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pemohon / Warga</label>
                    <select name="warga_id" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        <option value="">-- Saya Sendiri ({{ Auth::user()->name }}) --</option>
                        @foreach($all_warga ?? [] as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Aset Yang Dipinjam</label>
                    <select name="nama_aset" id="loan-nama-aset" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        @foreach($list_perangkat ?? [] as $dev)
                        <option value="{{ $dev->nama_perangkat }}">{{ $dev->nama_perangkat }} (Stok Available: {{ $dev->jumlah }} Unit)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Unit Dipinjam</label>
                    <input type="number" name="jumlah_unit" value="1" min="1" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tgl Pinjam</label>
                        <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tgl Rencana Kembali</label>
                        <input type="date" name="tanggal_kembali" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keperluan / Acara</label>
                    <textarea name="keperluan" rows="3" placeholder="Jelaskan penggunaan aset (misal: hajatan keluarga, kerja bakti)..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 rounded-2xl"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-ajukan-peminjaman-aset').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Modal Pengajuan Pengembalian Aset (Oleh Warga) -->
<div id="modal-submit-kembali-aset" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-submit-kembali-aset').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-2">Form Pengembalian Aset</h3>
        <p id="submit-kembali-info" class="text-xs font-bold text-indigo-600 mb-6"></p>
        <form id="form-submit-kembali" action="/admin/perangkat/loan/submit-return" method="POST" onsubmit="simpanDataUmum(event, 'form-submit-kembali', 'perangkat-sistem')">
            <input type="hidden" name="id" id="submit-kembali-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Dikembalikan Aktual</label>
                    <input type="date" name="tanggal_dikembalikan_aktual" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan Pengembalian</label>
                    <textarea name="catatan_pengembalian" rows="3" placeholder="Aset telah dikembalikan ke pengurus/gudang dalam keadaan lengkap..." required class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 rounded-2xl"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-submit-kembali-aset').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200">Kirim Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Modal Verifikasi & Inspection Fisik Pengembalian Aset (Oleh Pengurus) -->
<div id="modal-verifikasi-kembali-aset" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-verifikasi-kembali-aset').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-2">Verifikasi Fisik Pengembalian Aset</h3>
        <p id="verifikasi-kembali-info" class="text-xs font-bold text-emerald-600 mb-6"></p>
        <form id="form-verifikasi-kembali" action="/admin/perangkat/loan/return" method="POST" onsubmit="simpanDataUmum(event, 'form-verifikasi-kembali', 'perangkat-sistem')">
            <input type="hidden" name="id" id="verifikasi-kembali-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kondisi Fisik Saat Dikembalikan</label>
                    <select name="kondisi_pengembalian" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        <option value="Baik (Sesuai)">Baik (Berfungsi Normal & Sesuai)</option>
                        <option value="Rusak Ringan">Rusak Ringan (Butuh Servis Kategori Ringan)</option>
                        <option value="Rusak Berat">Rusak Berat (Butuh Penggantian Komponen)</option>
                        <option value="Hilang / Tidak Lengkap">Hilang / Tidak Lengkap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Dikembalikan Aktual</label>
                    <input type="date" name="tanggal_dikembalikan_aktual" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Denda Kerusakan / Ganti Rugi (Rp)</label>
                    <input type="number" name="denda_kerusakan" value="0" min="0" step="10000" placeholder="0" class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan Verifikasi Pengurus</label>
                    <textarea name="catatan_pengembalian" rows="2" placeholder="Catatan hasil pengecekan fisik barang..." class="w-full bg-gray-50 border border-gray-200 font-medium text-gray-700 p-3 rounded-2xl"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-verifikasi-kembali-aset').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200">Verifikasi & Selesaikan</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Modal Edit Perangkat Inventaris -->
<div id="modal-edit-perangkat" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-edit-perangkat').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Edit Data Inventaris Aset</h3>
        <form id="form-edit-perangkat" action="{{ url('/admin/perangkat/update') }}" method="POST" onsubmit="simpanDataUmum(event, 'form-edit-perangkat', 'perangkat-sistem')">
            @csrf
            <input type="hidden" name="id" id="edit-perangkat-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Aset</label>
                    <input type="text" name="nama_perangkat" id="edit-nama-perangkat" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Aset</label>
                        <input type="text" name="jenis_perangkat" id="edit-jenis-perangkat" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Unit</label>
                        <input type="number" name="jumlah" id="edit-jumlah-perangkat" min="1" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kondisi Fisik</label>
                    <select name="kondisi" id="edit-kondisi-perangkat" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl">
                        <option value="Baik">Baik (Berfungsi Normal)</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-edit-perangkat').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchAsetTab(tabName) {
    document.querySelectorAll('.tab-aset-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-aset-btn').forEach(btn => {
        btn.className = 'tab-aset-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-white/10 text-gray-300 hover:bg-white/20';
    });
    
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.className = 'tab-aset-btn px-5 py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center gap-2 bg-blue-600 text-white shadow-lg shadow-blue-600/25';
}

function bukaPinjamSpesifik(namaAset) {
    document.getElementById('loan-nama-aset').value = namaAset;
    document.getElementById('modal-ajukan-peminjaman-aset').classList.remove('hidden');
}

function bukaModalSubmitKembali(id, namaAset) {
    document.getElementById('submit-kembali-id').value = id;
    document.getElementById('submit-kembali-info').innerText = 'Kembalikan Barang: ' + namaAset;
    document.getElementById('modal-submit-kembali-aset').classList.remove('hidden');
}

function bukaModalVerifikasiKembali(id, namaWarga, namaAset) {
    document.getElementById('verifikasi-kembali-id').value = id;
    document.getElementById('verifikasi-kembali-info').innerText = 'Pemeriksaan Pengembalian ' + namaAset + ' oleh ' + namaWarga;
    document.getElementById('modal-verifikasi-kembali-aset').classList.remove('hidden');
}

function prosesApprovalLoan(id, action) {
    const url = action === 'approve' ? '/admin/perangkat/loan/approve' : '/admin/perangkat/loan/reject';
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('perangkat-sistem'); }
    .then(r => r.json()).then(d => { alert(d.message); switchPage('perangkat-sistem', document.querySelector('.menu-active')); });
}

function hapusLoan(id) {
    if (!confirm('Hapus catatan peminjaman aset ini?')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('/admin/perangkat/loan/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('perangkat-sistem'); }
    .then(r => r.json()).then(d => { alert(d.message); switchPage('perangkat-sistem', document.querySelector('.menu-active')); });
}

function editPerangkat(item) {
    document.getElementById('edit-perangkat-id').value = item.id;
    document.getElementById('edit-nama-perangkat').value = item.nama_perangkat;
    document.getElementById('edit-jenis-perangkat').value = item.jenis_perangkat;
    document.getElementById('edit-jumlah-perangkat').value = item.jumlah;
    document.getElementById('edit-kondisi-perangkat').value = item.kondisi;
    document.getElementById('modal-edit-perangkat').classList.remove('hidden');
}

function hapusPerangkat(id) {
    if (!confirm('Hapus data aset inventaris ini?')) return;
    fetch('/admin/perangkat/delete/' + id, { method: 'POST', headers: { 'X-CSRF-TOKEN': window.csrfToken, 'X-Requested-With': 'XMLHttpRequest' } })
    if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('perangkat-sistem'); }
    .then(r => r.json()).then(d => { alert(d.message); switchPage('perangkat-sistem', document.querySelector('.menu-active')); });
}

function handleJenisChange(select, inputId) {
    const input = document.getElementById(inputId);
    if (select.value === 'Lainnya') input.classList.remove('hidden');
    else input.classList.add('hidden');
}
</script>
