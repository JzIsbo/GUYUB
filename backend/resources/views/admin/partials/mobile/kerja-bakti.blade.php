{{-- resources/views/admin/partials/mobile/kerja-bakti.blade.php --}}
@php
    $canManage = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']);
@endphp

<div class="p-3 space-y-3 max-w-full mx-auto">
    {{-- Hero Banner Mobile --}}
    <div class="relative bg-gradient-to-br from-[#064e3b] via-[#047857] to-[#0f172a] rounded-2xl p-4 overflow-hidden shadow-lg">
        <div class="absolute -right-4 -bottom-4 text-white/[0.04] text-[6rem] rotate-12 pointer-events-none">
            <i class="fa-solid fa-person-digging"></i>
        </div>
        <div class="space-y-2 relative z-10">
            <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-md border border-white/10 rounded-full px-3 py-0.5">
                <i class="fa-solid fa-users-gear text-emerald-300 text-[10px]"></i>
                <span class="text-[9px] font-semibold text-emerald-200 tracking-wider uppercase">Gotong Royong</span>
            </div>
            <div>
                <h2 class="text-base font-extrabold text-white tracking-tight leading-tight">Kerja Bakti Lingkungan</h2>
                <p class="text-emerald-200/70 text-[10px] mt-0.5">Jadwal pembersihan & penataan warga</p>
            </div>

            @if($canManage)
            <button onclick="document.getElementById('m-modal-tambah-kerja-bakti').classList.remove('hidden')" class="w-full mt-2 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-md">
                <i class="fa-solid fa-plus text-[10px]"></i> Tambah Kerja Bakti
            </button>
            @endif
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="relative w-full">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-magnifying-glass text-[10px]"></i></span>
        <input type="text" placeholder="Cari kegiatan, lokasi..." class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500" onkeyup="filterKerjaBaktiMobile(this.value)">
    </div>

    {{-- Mobile Cards List --}}
    <div class="space-y-2.5">
        @forelse($list_kerja_bakti ?? [] as $kb)
        <div class="m-kb-card bg-white rounded-xl border border-gray-100 p-3 shadow-sm"
             data-search="{{ strtolower($kb->nama_kegiatan . ' ' . $kb->lokasi . ' ' . $kb->perlengkapan) }}">
            <div class="flex items-start justify-between gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-emerald-50 text-emerald-700">
                    <i class="fa-solid fa-clock mr-0.5"></i>{{ $kb->waktu_mulai }} - {{ $kb->waktu_selesai }}
                </span>
                <span class="px-1.5 py-0.5 rounded-full text-[8px] font-bold
                    {{ $kb->status == 'Mendatang' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $kb->status == 'Selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $kb->status == 'Dibatalkan' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ strtoupper($kb->status) }}
                </span>
            </div>

            <h3 class="font-bold text-gray-800 text-[12px] leading-snug">{{ $kb->nama_kegiatan }}</h3>
            <p class="text-[9px] text-gray-500 mt-0.5"><i class="fa-solid fa-location-dot text-emerald-500 mr-0.5"></i>{{ $kb->lokasi }}</p>
            
            <div class="bg-gray-50 p-2 rounded-lg border border-gray-100 mt-1.5">
                <p class="text-[8px] text-gray-400 font-bold uppercase"><i class="fa-solid fa-screwdriver-wrench mr-0.5"></i>Membawa:</p>
                <p class="text-[10px] font-semibold text-gray-700">{{ $kb->perlengkapan }}</p>
            </div>

            <div class="flex items-center justify-between mt-2.5 pt-2 border-t text-[9px] text-gray-400">
                <span>Tgl: {{ \Carbon\Carbon::parse($kb->tanggal)->format('d/m/Y') }}</span>
                @if($canManage)
                <div class="flex items-center gap-1">
                    <button onclick="editKerjaBaktiMobile({{ json_encode($kb) }})" class="w-6 h-6 rounded bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-pen text-[9px]"></i></button>
                    <button onclick="deleteKerjaBakti({{ $kb->id }}, '{{ addslashes($kb->nama_kegiatan) }}')" class="w-6 h-6 rounded bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash text-[9px]"></i></button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 p-6 text-center text-gray-400 text-xs italic">
            Belum ada agenda Kerja Bakti.
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH MOBILE --}}
<div id="m-modal-tambah-kerja-bakti" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-3 hidden">
    <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 shadow-2xl">
        <div class="flex items-center justify-between mb-3 border-b pb-2">
            <h3 class="text-sm font-bold text-gray-800">Tambah Kerja Bakti</h3>
            <button onclick="document.getElementById('m-modal-tambah-kerja-bakti').classList.add('hidden')" class="w-7 h-7 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form id="m-form-tambah-kerja-bakti" onsubmit="saveKerjaBakti(event, 'm-form-tambah-kerja-bakti', '/kerja-bakti/store')">
            @csrf
            <div class="space-y-2.5 text-xs">
                <input type="text" name="nama_kegiatan" required placeholder="Nama Kegiatan" class="w-full py-2 px-3 border rounded-xl">
                <div class="grid grid-cols-3 gap-2">
                    <input type="date" name="tanggal" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="w-full py-2 px-2 border rounded-xl text-[10px]">
                    <input type="text" name="waktu_mulai" value="07:00" placeholder="07:00" required class="w-full py-2 px-2 border rounded-xl text-[10px]">
                    <input type="text" name="waktu_selesai" value="11:00" placeholder="11:00" required class="w-full py-2 px-2 border rounded-xl text-[10px]">
                </div>
                <input type="text" name="lokasi" required placeholder="Lokasi" class="w-full py-2 px-3 border rounded-xl">
                <input type="text" name="perlengkapan" required placeholder="Perlengkapan Dibawa" class="w-full py-2 px-3 border rounded-xl">
                <select name="status" required class="w-full py-2 px-3 border rounded-xl">
                    <option value="Mendatang">Mendatang</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
                <textarea name="keterangan" rows="2" placeholder="Keterangan..." class="w-full py-2 px-3 border rounded-xl"></textarea>
            </div>
            <div class="flex gap-2 mt-4 pt-2 border-t">
                <button type="button" onclick="document.getElementById('m-modal-tambah-kerja-bakti').classList.add('hidden')" class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold">Batal</button>
                <button type="submit" class="flex-1 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterKerjaBaktiMobile(q) {
        const query = q.toLowerCase().trim();
        document.querySelectorAll('.m-kb-card').forEach(card => {
            const data = card.getAttribute('data-search') || '';
            card.style.display = (!query || data.includes(query)) ? '' : 'none';
        });
    }

    function editKerjaBaktiMobile(kb) {
        openEditKerjaBaktiModal(kb);
    }
</script>
