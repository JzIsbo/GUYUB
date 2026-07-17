<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    {{-- ============ HERO BANNER ============ --}}
    <div class="bg-gradient-to-br from-[#1e3a5f] via-[#1a2e4a] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 relative overflow-hidden">

        {{-- Decorative background icon --}}
        <i class="fa-solid fa-wallet absolute -right-6 -bottom-6 text-[10rem] text-white/[0.03] rotate-12 pointer-events-none"></i>
        <i class="fa-solid fa-wallet absolute right-20 top-4 text-[4rem] text-white/[0.04] -rotate-12 pointer-events-none"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            {{-- Left: Text content --}}
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 text-blue-200 text-[10px] font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full">
                    <i class="fa-solid fa-wallet text-xs"></i>
                    KONFIGURASI IURAN
                </div>
                <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight">Master Data Iuran</h1>
                <p class="text-sm text-blue-200/70 font-medium max-w-md">Konfigurasi jenis dan tarif iuran warga</p>
            </div>

            {{-- Right: Stats badge + Action button --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Stats badge --}}
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[100px]">
                    <p class="text-2xl font-black text-white">{{ count($list_iuran) }}</p>
                    <p class="text-[10px] font-bold text-blue-300/60 uppercase tracking-widest mt-0.5">Jenis Iuran</p>
                </div>

                {{-- Add button --}}
                <button onclick="document.getElementById('modal-tambah-iuran').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-bold transition-all hover:scale-[1.03] shadow-lg shadow-blue-500/25 flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-plus-circle text-lg"></i> Tambah Jenis Iuran
                </button>
            </div>
        </div>
    </div>

    {{-- ============ TABLE CARD ============ --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm">
        <div class="p-6 lg:p-8">
            <div class="overflow-x-auto min-h-[200px]">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-400 text-[10px] uppercase tracking-widest">
                            <th class="p-4 rounded-l-2xl font-bold">Nama Iuran</th>
                            <th class="p-4 font-bold">Periode Penagihan</th>
                            <th class="p-4 font-bold text-center">Sifat</th>
                            <th class="p-4 font-bold text-right">Tarif / Nominal</th>
                            <th class="p-4 rounded-r-2xl font-bold text-center">Aksi</th>
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
                                <td class="p-4 text-center">
                                    @if(in_array(Auth::user()->role, ['Super Admin', 'Bendahara']))
                                    <button onclick="hapusIuran({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all inline-flex items-center justify-center cursor-pointer" title="Hapus Master Iuran">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-10">
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
        </div>
    </div>

    {{-- ============ MODAL (unchanged) ============ --}}
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

<script>
function hapusIuran(id) {
    Swal.fire({
        title: 'Hapus Master Iuran?',
        text: "Kategori iuran warga ini akan dihapus dari konfigurasi.",
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
            const fd = new FormData();
            fd.append('id', id);
            fd.append('_token', window.csrfToken);
            fetch('/admin/iuran/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Master iuran telah dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl p-6 font-sans' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('data-iuran'); }
                switchPage('data-iuran', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
