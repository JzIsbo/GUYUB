<div class="p-3 space-y-3 max-w-full mx-auto">

    {{-- ============ HERO BANNER ============ --}}
    <div class="relative bg-gradient-to-br from-[#312e81] via-[#3730a3] to-[#0f172a] rounded-2xl p-4 overflow-hidden shadow-lg">
        {{-- Decorative background icon --}}
        <div class="absolute -right-4 -bottom-4 text-white/[0.04] text-[6rem] rotate-12 pointer-events-none">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        {{-- Small decorative dots --}}
        <div class="absolute top-3 right-5 w-12 h-12 bg-white/[0.03] rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-5 left-1/2 w-16 h-16 bg-indigo-400/[0.06] rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-3">
            <div class="space-y-2.5">
                {{-- Category badge --}}
                <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-3 py-1">
                    <i class="fa-solid fa-envelope text-indigo-300 text-[10px]"></i>
                    <span class="text-[10px] font-semibold text-indigo-200 tracking-widest uppercase">Layanan Administrasi</span>
                </div>

                {{-- Title --}}
                <div>
                    <h2 class="text-lg font-extrabold text-white tracking-tight leading-tight">Surat Online</h2>
                    <p class="text-indigo-200/70 text-xs mt-1 font-medium">Kelola pengajuan surat pengantar</p>
                </div>

                {{-- Stats badge --}}
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-file-lines text-indigo-300 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-lg font-extrabold text-white leading-none">{{ count($list_surat) }}</p>
                            <p class="text-[9px] text-indigo-300/70 font-semibold uppercase tracking-wider">Total Pengajuan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action button --}}
            <div>
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-surat').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-400 text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all duration-200 shadow-lg shadow-indigo-500/25 w-full justify-center">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    Buat Pengajuan Manual
                </button>
                @else
                <button onclick="document.getElementById('modal-tambah-surat').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-400 text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all duration-200 shadow-lg shadow-indigo-500/25 w-full justify-center">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    Buat Pengajuan Surat
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ============ TABLE CARD ============ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card header --}}
        <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center">
                <i class="fa-solid fa-list-check text-indigo-500 text-[10px]"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-800">Daftar Pengajuan Surat</h3>
                <p class="text-[10px] text-gray-400 font-medium">Riwayat pengajuan surat pengantar</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-3 py-2.5 text-[9px] font-semibold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-3 py-2.5 text-[9px] font-semibold text-gray-400 uppercase tracking-widest">Nama</th>
                        <th class="px-3 py-2.5 text-[9px] font-semibold text-gray-400 uppercase tracking-widest">Jenis</th>
                        <th class="px-3 py-2.5 text-[9px] font-semibold text-gray-400 uppercase tracking-widest">Status</th>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                        <th class="px-3 py-2.5 text-[9px] font-semibold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs">
                    @forelse($list_surat as $surat)
                    <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                        <td class="px-3 py-3 text-gray-400 font-medium text-[10px]">
                            <div class="flex items-center gap-1.5">
                                <div class="w-5 h-5 rounded-md bg-gray-100 flex items-center justify-center">
                                    <i class="fa-regular fa-calendar text-gray-400 text-[8px]"></i>
                                </div>
                                {{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-3 py-3 font-bold text-gray-800 text-[11px]">{{ $surat->nama_warga }}</td>
                        <td class="px-3 py-3">
                            <span class="text-gray-700 font-semibold text-[11px]">{{ $surat->jenis_surat }}</span>
                            <br>
                            <span class="text-[10px] text-gray-400">{{ $surat->keperluan }}</span>
                        </td>
                        <td class="px-3 py-3">
                            @if($surat->status == 'Menunggu') <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-yellow-100 text-yellow-600">MENUNGGU</span>
                            @elseif($surat->status == 'Disetujui') <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-green-100 text-green-600">DISETUJUI</span>
                            @else <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-100 text-red-600">DITOLAK</span>
                            @endif
                        </td>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                        <td class="px-3 py-3 text-center">
                            <div class="flex justify-center gap-1">
                                @if($surat->status == 'Menunggu')
                                <button onclick="ubahStatusSurat({{ $surat->id }}, 'Disetujui')" class="w-7 h-7 rounded-lg bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100 transition-colors duration-150" title="Setujui">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </button>
                                <button onclick="ubahStatusSurat({{ $surat->id }}, 'Ditolak')" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition-colors duration-150" title="Tolak">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                    <i class="fa-solid fa-inbox text-gray-300 text-base"></i>
                                </div>
                                <p class="text-gray-400 font-semibold text-xs">Belum ada pengajuan surat.</p>
                                <p class="text-gray-300 text-[10px]">Pengajuan yang dibuat akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-tambah-surat" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm p-3">
    <div class="bg-white p-5 rounded-2xl max-w-[95vw] w-full">
        <h2 class="text-base font-bold mb-3">{{ in_array(Auth::user()->role, ['Super Admin', 'RT']) ? 'Pengajuan Surat Manual' : 'Buat Pengajuan Surat' }}</h2>
        <form id="form-tambah-surat" action="/surat-online/store" method="POST">
            @csrf
            <div class="space-y-3">
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <input type="text" name="nama_warga" placeholder="Nama Warga" class="w-full py-2 px-3 text-sm border rounded-xl" required>
                @else
                <input type="text" name="nama_warga" value="{{ Auth::user()->name }}" readonly class="w-full py-2 px-3 text-sm border rounded-xl bg-gray-50 text-gray-500 font-semibold" required>
                @endif
                <select name="jenis_surat" class="w-full py-2 px-3 text-sm border rounded-xl" required>
                    <option value="Surat Pengantar Domisili">Surat Pengantar Domisili</option>
                    <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu (SKTM)</option>
                    <option value="Surat Pengantar Nikah">Surat Pengantar Nikah</option>
                </select>
                <textarea name="keperluan" placeholder="Tujuan / Keperluan pembuatan surat" class="w-full py-2 px-3 text-sm border rounded-xl" required></textarea>
                <div class="flex gap-2 mt-4">
                    <button type="button" onclick="document.getElementById('modal-tambah-surat').classList.add('hidden')" class="w-full bg-gray-100 text-gray-600 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-200">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-surat', 'surat-online')" class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function ubahStatusSurat(id, status) {
    if(!confirm('Yakin ingin merubah status surat ini menjadi ' + status + '?')) return;

    let formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/surat-online/update-status', {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(res => res.json()).then(data => {
        alert(data.message);
        switchPage('surat-online', document.querySelector('.menu-active'));
    });
}
</script>
