{{-- resources/views/admin/partials/mobile/peraturan-sk.blade.php --}}
@php
    $canManage = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']);
@endphp

<div class="p-3 space-y-3 max-w-full mx-auto">
    {{-- Hero Banner Mobile --}}
    <div class="relative bg-gradient-to-br from-[#1e1b4b] via-[#312e81] to-[#0f172a] rounded-2xl p-4 overflow-hidden shadow-lg">
        <div class="absolute -right-4 -bottom-4 text-white/[0.04] text-[6rem] rotate-12 pointer-events-none">
            <i class="fa-solid fa-scroll"></i>
        </div>
        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-3 py-0.5">
                <i class="fa-solid fa-scale-balanced text-indigo-300 text-[10px]"></i>
                <span class="text-[9px] font-semibold text-indigo-200 tracking-wider uppercase">Tata Tertib & SK</span>
            </div>
            <div>
                <h2 class="text-base font-extrabold text-white tracking-tight leading-tight">Peraturan & SK RT/RW</h2>
                <p class="text-indigo-200/70 text-[10px] mt-0.5">Pedoman legalitas dan aturan lingkungan</p>
            </div>

            @if($canManage)
            <button onclick="document.getElementById('m-modal-tambah-peraturan').classList.remove('hidden')" class="w-full mt-2 py-2 bg-indigo-500 hover:bg-indigo-400 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-md">
                <i class="fa-solid fa-plus text-[10px]"></i> Tambah Peraturan / SK
            </button>
            @endif
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="relative w-full">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-magnifying-glass text-[10px]"></i></span>
        <input type="text" placeholder="Cari dokumen, nomor SK..." class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500" onkeyup="filterPeraturanMobile(this.value)">
    </div>

    {{-- Mobile Cards List --}}
    <div class="space-y-2.5">
        @forelse($list_peraturan ?? [] as $item)
        <div class="m-peraturan-card bg-white rounded-xl border border-gray-100 p-3 shadow-sm"
             data-search="{{ strtolower($item->judul . ' ' . $item->nomor_dokumen . ' ' . $item->kategori) }}">
            <div class="flex items-start justify-between gap-2 mb-1.5">
                <span class="px-2 py-0.5 rounded text-[8px] font-extrabold uppercase
                    {{ $item->kategori == 'Peraturan RT' ? 'bg-blue-50 text-blue-600' : '' }}
                    {{ $item->kategori == 'Peraturan RW' ? 'bg-indigo-50 text-indigo-600' : '' }}
                    {{ $item->kategori == 'SK Pengurus' ? 'bg-amber-50 text-amber-600' : '' }}
                    {{ $item->kategori == 'Himbauan Lingkungan' ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    {{ $item->kategori }}
                </span>
                <span class="px-1.5 py-0.5 rounded-full text-[8px] font-bold {{ $item->status == 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ strtoupper($item->status) }}
                </span>
            </div>

            <h3 class="font-bold text-gray-800 text-[12px] leading-snug">{{ $item->judul }}</h3>
            <p class="text-[9px] text-gray-400 mt-0.5">No: {{ $item->nomor_dokumen ?? '-' }}</p>
            <p class="text-[10px] text-gray-600 mt-1 line-clamp-2">{{ $item->keterangan ?? '-' }}</p>

            <div class="flex items-center justify-between mt-2.5 pt-2 border-t text-[9px] text-gray-400">
                <span>Berlaku: {{ \Carbon\Carbon::parse($item->tanggal_berlaku)->format('d/m/Y') }}</span>
                
                <div class="flex items-center gap-1">
                    @if($item->file_path)
                    <a href="{{ asset($item->file_path) }}" target="_blank" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-[9px] font-bold">
                        PDF
                    </a>
                    @endif
                    @if($canManage)
                    <button onclick="editPeraturanMobile({{ json_encode($item) }})" class="w-6 h-6 rounded bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-pen text-[9px]"></i></button>
                    <button onclick="deletePeraturan({{ $item->id }}, '{{ addslashes($item->judul) }}')" class="w-6 h-6 rounded bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash text-[9px]"></i></button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 p-6 text-center text-gray-400 text-xs italic">
            Belum ada data Peraturan / SK.
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH MOBILE --}}
<div id="m-modal-tambah-peraturan" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-3 hidden">
    <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 shadow-2xl">
        <div class="flex items-center justify-between mb-3 border-b pb-2">
            <h3 class="text-sm font-bold text-gray-800">Tambah Peraturan / SK</h3>
            <button onclick="document.getElementById('m-modal-tambah-peraturan').classList.add('hidden')" class="w-7 h-7 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form id="m-form-tambah-peraturan" onsubmit="savePeraturan(event, 'm-form-tambah-peraturan', '/peraturan-sk/store')">
            @csrf
            <div class="space-y-2.5 text-xs">
                <input type="text" name="judul" required placeholder="Judul Dokumen" class="w-full py-2 px-3 border rounded-xl">
                <input type="text" name="nomor_dokumen" placeholder="Nomor SK/Dokumen" class="w-full py-2 px-3 border rounded-xl">
                <select name="kategori" required class="w-full py-2 px-3 border rounded-xl">
                    <option value="Peraturan RT">Peraturan RT</option>
                    <option value="Peraturan RW">Peraturan RW</option>
                    <option value="SK Pengurus">SK Pengurus</option>
                    <option value="Himbauan Lingkungan">Himbauan Lingkungan</option>
                </select>
                <input type="date" name="tanggal_berlaku" value="{{ date('Y-m-d') }}" required class="w-full py-2 px-3 border rounded-xl">
                <select name="status" required class="w-full py-2 px-3 border rounded-xl">
                    <option value="Aktif">Aktif</option>
                    <option value="Arsip">Arsip</option>
                </select>
                <textarea name="keterangan" rows="2" placeholder="Uraian ringkas..." class="w-full py-2 px-3 border rounded-xl"></textarea>
                <input type="file" name="file_dokumen" class="w-full text-[10px]">
            </div>
            <div class="flex gap-2 mt-4 pt-2 border-t">
                <button type="button" onclick="document.getElementById('m-modal-tambah-peraturan').classList.add('hidden')" class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold">Batal</button>
                <button type="submit" class="flex-1 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterPeraturanMobile(q) {
        const query = q.toLowerCase().trim();
        document.querySelectorAll('.m-peraturan-card').forEach(card => {
            const data = card.getAttribute('data-search') || '';
            card.style.display = (!query || data.includes(query)) ? '' : 'none';
        });
    }

    function editPeraturanMobile(item) {
        openEditPeraturanModal(item);
    }
</script>
