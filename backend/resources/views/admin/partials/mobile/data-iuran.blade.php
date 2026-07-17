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

    {{-- ============ CARD LIST ============ --}}
    <div class="space-y-2">
        @forelse($list_iuran as $item)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-gray-800 text-[12px] truncate">{{ $item->nama_iuran }}</span>
                        @if($item->sifat == 'Wajib')
                            <span class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider">Wajib</span>
                        @else
                            <span class="bg-green-50 text-green-600 px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider">Sukarela</span>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-500 font-medium mt-0.5 truncate">{{ $item->deskripsi ?? '-' }}</p>
                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Periode: <span class="text-gray-600 font-extrabold">{{ $item->periode_penagihan }}</span></p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <div class="text-right">
                        <p class="font-black text-gray-900 text-xs font-mono">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                    </div>
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Bendahara']))
                    <button onclick="hapusIuran({{ $item->id }})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center cursor-pointer" title="Hapus">
                        <i class="fa-solid fa-trash text-[10px]"></i>
                    </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada jenis penagihan iuran yang diatur...
            </div>
        @endforelse
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

<script>
function hapusIuran(id) {
    Swal.fire({
        title: 'Hapus Master Iuran?',
        text: "Master iuran ini akan dihapus.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-2xl p-4 shadow-xl font-sans text-xs',
            confirmButton: 'rounded-xl font-bold px-4 py-2 text-[11px]',
            cancelButton: 'rounded-xl font-bold px-4 py-2 text-[11px]'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('id', id);
            fd.append('_token', window.csrfToken);
            fetch('/admin/iuran/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Master iuran dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-2xl p-4 font-sans text-xs' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('data-iuran'); }
                switchPage('data-iuran', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
