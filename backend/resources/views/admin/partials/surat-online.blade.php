<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    {{-- ============ HERO BANNER ============ --}}
    <div class="relative bg-gradient-to-br from-[#312e81] via-[#3730a3] to-[#0f172a] rounded-[2rem] p-8 lg:p-10 overflow-hidden shadow-xl">
        {{-- Decorative background icon --}}
        <div class="absolute -right-6 -bottom-6 text-white/[0.04] text-[12rem] rotate-12 pointer-events-none">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        {{-- Small decorative dots --}}
        <div class="absolute top-6 right-10 w-20 h-20 bg-white/[0.03] rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-10 left-1/2 w-32 h-32 bg-indigo-400/[0.06] rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div class="space-y-4">
                {{-- Category badge --}}
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-4 py-1.5">
                    <i class="fa-solid fa-envelope text-indigo-300 text-xs"></i>
                    <span class="text-[11px] font-semibold text-indigo-200 tracking-widest uppercase">Layanan Administrasi</span>
                </div>

                {{-- Title --}}
                <div>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">Surat Online</h2>
                    <p class="text-indigo-200/70 text-sm mt-1.5 font-medium">Kelola pengajuan surat pengantar dari warga</p>
                </div>

                {{-- Stats badge --}}
                <div class="flex items-center gap-3 mt-2">
                    <div class="flex items-center gap-2.5 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-file-lines text-indigo-300 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold text-white leading-none">{{ count($list_surat) }}</p>
                            <p class="text-[10px] text-indigo-300/70 font-semibold uppercase tracking-wider">Total Pengajuan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action button --}}
            <div class="flex-shrink-0">
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <button onclick="document.getElementById('modal-tambah-surat').classList.remove('hidden')" class="inline-flex items-center gap-2.5 bg-indigo-500 hover:bg-indigo-400 text-white px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-400/30 hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Buat Pengajuan Manual
                </button>
                @else
                <button onclick="document.getElementById('modal-tambah-surat').classList.remove('hidden')" class="inline-flex items-center gap-2.5 bg-indigo-500 hover:bg-indigo-400 text-white px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-400/30 hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Buat Pengajuan Surat
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ============ TABLE CARD ============ --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card header --}}
        <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="fa-solid fa-list-check text-indigo-500 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800">Daftar Pengajuan Surat</h3>
                <p class="text-[11px] text-gray-400 font-medium">Riwayat seluruh pengajuan surat pengantar</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-[10px] font-semibold text-gray-400 uppercase tracking-widest rounded-tl-xl">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Nama Warga</th>
                        <th class="px-6 py-4 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Jenis Surat</th>
                        <th class="px-6 py-4 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Status</th>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                        <th class="px-6 py-4 text-[10px] font-semibold text-gray-400 uppercase tracking-widest text-center rounded-tr-xl">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($list_surat as $surat)
                    <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                        <td class="px-6 py-5 text-gray-400 font-medium text-xs">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="fa-regular fa-calendar text-gray-400 text-[10px]"></i>
                                </div>
                                {{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-5 font-bold text-gray-800">{{ $surat->nama_warga }}</td>
                        <td class="px-6 py-5">
                            <span class="text-gray-700 font-semibold text-sm">{{ $surat->jenis_surat }}</span>
                            <br>
                            <span class="text-xs text-gray-400">{{ $surat->keperluan }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @if($surat->status == 'Menunggu') <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-600">MENUNGGU</span>
                            @elseif($surat->status == 'Disetujui') <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600">DISETUJUI</span>
                            @else <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600">DITOLAK</span>
                            @endif
                        </td>
                        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center gap-2">
                                @if($surat->status == 'Menunggu')
                                <button onclick="ubahStatusSurat({{ $surat->id }}, 'Disetujui')" class="inline-flex items-center gap-1.5 bg-green-50 text-green-600 px-3.5 py-1.5 rounded-xl text-xs font-bold hover:bg-green-100 transition-colors duration-150">
                                    <i class="fa-solid fa-check text-[10px]"></i> Setujui
                                </button>
                                <button onclick="ubahStatusSurat({{ $surat->id }}, 'Ditolak')" class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 px-3.5 py-1.5 rounded-xl text-xs font-bold hover:bg-red-100 transition-colors duration-150">
                                    <i class="fa-solid fa-xmark text-[10px]"></i> Tolak
                                </button>
                                @endif
                                <button onclick="hapusSurat({{ $surat->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center" title="Hapus Pengajuan">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <i class="fa-solid fa-inbox text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-400 font-semibold text-sm">Belum ada pengajuan surat.</p>
                                <p class="text-gray-300 text-xs">Pengajuan yang dibuat akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-tambah-surat" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl w-[400px]">
        <h2 class="text-xl font-bold mb-4">{{ in_array(Auth::user()->role, ['Super Admin', 'RT']) ? 'Pengajuan Surat Manual' : 'Buat Pengajuan Surat' }}</h2>
        <form id="form-tambah-surat" action="/surat-online/store" method="POST">
            @csrf
            <div class="space-y-4">
                @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
                <select name="nama_warga" class="w-full p-3 border rounded-xl" required>
                    <option value="" disabled selected>Pilih Warga...</option>
                    @foreach($all_warga ?? [] as $w)
                        <option value="{{ $w->nama_lengkap }}">{{ $w->nama_lengkap }} (Blok {{ $w->blok_rumah ?? '-' }})</option>
                    @endforeach
                </select>
                @else
                <input type="text" name="nama_warga" value="{{ Auth::user()->name }}" readonly class="w-full p-3 border rounded-xl bg-gray-50 text-gray-500 font-semibold" required>
                @endif
                <select name="jenis_surat" class="w-full p-3 border rounded-xl" required>
                    <option value="Surat Pengantar Domisili">Surat Pengantar Domisili</option>
                    <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu (SKTM)</option>
                    <option value="Surat Pengantar Nikah">Surat Pengantar Nikah</option>
                </select>
                <textarea name="keperluan" placeholder="Tujuan / Keperluan pembuatan surat" class="w-full p-3 border rounded-xl" required></textarea>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-tambah-surat').classList.add('hidden')" class="w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-surat', 'surat-online')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function ubahStatusSurat(id, status) {
    Swal.fire({
        title: 'Ubah Status Surat?',
        text: 'Yakin ingin merubah status surat ini menjadi ' + status + '?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-3xl p-6 shadow-2xl font-sans',
            confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs',
            cancelButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('/surat-online/update-status', {
                method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(res => res.json()).then(data => {
                alert(data.message);
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('surat-online'); }
                switchPage('surat-online', document.querySelector('.menu-active'));
            });
        }
    });
}

function hapusSurat(id) {
    Swal.fire({
        title: 'Hapus Pengajuan Surat?',
        text: 'Apakah Anda yakin ingin menghapus pengajuan surat ini secara permanen?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-3xl p-6 shadow-2xl font-sans',
            confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs',
            cancelButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('id', id);
            formData.append('_token', window.csrfToken || '{{ csrf_token() }}');

            fetch('/surat-online/delete', {
                method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(res => res.json()).then(data => {
                alert(data.message);
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('surat-online'); }
                switchPage('surat-online', document.querySelector('.menu-active') || document.querySelector('[data-page="surat-online"]'));
            });
        }
    });
}
</script>
