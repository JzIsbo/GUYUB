{{-- resources/views/admin/partials/kerja-bakti.blade.php --}}
@php
    $canManage = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']);
@endphp

<div class="space-y-6">
    {{-- Header Banner --}}
    <div class="relative bg-gradient-to-br from-[#064e3b] via-[#047857] to-[#0f172a] rounded-[2.5rem] p-6 lg:p-8 overflow-hidden shadow-2xl border border-emerald-500/20">
        <div class="absolute -right-10 -bottom-10 text-white/[0.03] text-[14rem] rotate-12 pointer-events-none">
            <i class="fa-solid fa-person-digging"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/20 rounded-full px-4 py-1.5">
                    <i class="fa-solid fa-users-gear text-emerald-300 text-xs"></i>
                    <span class="text-xs font-semibold text-emerald-200 tracking-wider uppercase">Gotong Royong & Kerja Bakti</span>
                </div>
                <h2 class="text-2xl lg:text-3xl font-black text-white tracking-tight">Jadwal Kerja Bakti Lingkungan</h2>
                <p class="text-emerald-200/70 text-sm max-w-2xl">Koordinasi kegiatan kerja bakti, penataan lingkungan RT/RW, dan aksi gotong royong warga.</p>
            </div>
            
            @if($canManage)
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('modal-tambah-kerja-bakti').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-3 rounded-2xl font-bold text-xs flex items-center gap-2.5 transition-all shadow-lg shadow-emerald-600/30 hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-calendar-plus text-sm"></i>
                    <span>Tambah Jadwal Kerja Bakti</span>
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Search & Status Filter --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-3 items-center justify-between">
        <div class="relative w-full md:w-96">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
            <input type="text" id="search-kerja-bakti" placeholder="Cari nama kegiatan, lokasi, perlengkapan..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium transition-all" onkeyup="filterKerjaBakti(this.value)">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <button onclick="filterStatusKerjaBakti('')" class="filter-kb-btn active text-xs font-bold px-3.5 py-2 rounded-xl border transition-all bg-emerald-50 text-emerald-600 border-emerald-200">Semua</button>
            <button onclick="filterStatusKerjaBakti('Mendatang')" class="filter-kb-btn text-xs font-bold px-3.5 py-2 rounded-xl border transition-all text-gray-500 border-gray-100 hover:bg-gray-50">Mendatang</button>
            <button onclick="filterStatusKerjaBakti('Selesai')" class="filter-kb-btn text-xs font-bold px-3.5 py-2 rounded-xl border transition-all text-gray-500 border-gray-100 hover:bg-gray-50">Selesai</button>
            <button onclick="filterStatusKerjaBakti('Dibatalkan')" class="filter-kb-btn text-xs font-bold px-3.5 py-2 rounded-xl border transition-all text-gray-500 border-gray-100 hover:bg-gray-50">Dibatalkan</button>
        </div>
    </div>

    {{-- Grid List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="kerja-bakti-grid">
        @forelse($list_kerja_bakti ?? [] as $kb)
        <div class="kerja-bakti-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between"
             data-status="{{ $kb->status }}"
             data-search="{{ strtolower($kb->nama_kegiatan . ' ' . $kb->lokasi . ' ' . $kb->perlengkapan . ' ' . $kb->keterangan) }}">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <i class="fa-solid fa-clock mr-1"></i>{{ $kb->waktu_mulai }} - {{ $kb->waktu_selesai }} WIB
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold
                        {{ $kb->status == 'Mendatang' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $kb->status == 'Selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $kb->status == 'Dibatalkan' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ strtoupper($kb->status) }}
                    </span>
                </div>

                <div>
                    <h3 class="font-extrabold text-gray-800 text-sm leading-snug hover:text-emerald-600 transition-colors">{{ $kb->nama_kegiatan }}</h3>
                    <p class="text-[11px] text-gray-500 font-medium mt-1"><i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i>{{ $kb->lokasi }}</p>
                </div>

                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 space-y-1">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider"><i class="fa-solid fa-screwdriver-wrench mr-1"></i>Perlengkapan Dibawa:</p>
                    <p class="text-xs font-semibold text-gray-700">{{ $kb->perlengkapan }}</p>
                </div>

                @if($kb->keterangan)
                <p class="text-xs text-gray-500 line-clamp-2">{{ $kb->keterangan }}</p>
                @endif
            </div>

            <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-[10px] text-gray-500 font-bold">
                    <i class="fa-regular fa-calendar-days text-emerald-600 mr-1"></i>{{ \Carbon\Carbon::parse($kb->tanggal)->format('d M Y') }}
                </span>

                @if($canManage)
                <div class="flex items-center gap-1.5">
                    <button onclick="openEditKerjaBaktiModal({{ json_encode($kb) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <button onclick="deleteKerjaBakti({{ $kb->id }}, '{{ addslashes($kb->nama_kegiatan) }}')" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
            <i class="fa-solid fa-person-digging text-gray-300 text-4xl mb-3"></i>
            <p class="text-sm font-bold text-gray-500">Belum ada agenda Kerja Bakti & Gotong Royong.</p>
            <p class="text-xs text-gray-400 mt-1">Pengurus dapat menambahkan agenda kerja bakti baru untuk koordinasi warga.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH KERJA BAKTI --}}
<div id="modal-tambah-kerja-bakti" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 relative shadow-2xl">
        <div class="flex items-center justify-between mb-5 border-b pb-3">
            <div>
                <h3 class="text-base font-extrabold text-gray-800">Tambah Agenda Kerja Bakti</h3>
                <p class="text-xs text-gray-400">Jadwalkan kegiatan gotong royong warga lingkungan</p>
            </div>
            <button onclick="document.getElementById('modal-tambah-kerja-bakti').classList.add('hidden')" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="form-tambah-kerja-bakti" onsubmit="saveKerjaBakti(event, 'form-tambah-kerja-bakti', '/kerja-bakti/store')">
            @csrf
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Nama / Topik Kegiatan Kerja Bakti</label>
                    <input type="text" name="nama_kegiatan" required placeholder="Contoh: Gotong Royong Pembersihan Saluran Air & Drainase" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jam Mulai</label>
                        <input type="text" name="waktu_mulai" value="07:00" required placeholder="07:00" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jam Selesai</label>
                        <input type="text" name="waktu_selesai" value="11:00" required placeholder="11:00" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Lokasi Kegiatan</label>
                    <input type="text" name="lokasi" required placeholder="Lokasi spesifik di lingkungan RT/RW" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Perlengkapan yang Wajib Dibawa Warga</label>
                    <input type="text" name="perlengkapan" required placeholder="Contoh: Cangkul, Sekop, Karung Plastik, Sapu Lidi" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Status Kegiatan</label>
                        <select name="status" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                            <option value="Mendatang">Mendatang</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Keterangan / Instruksi Tambahan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan konsumsi, pembagian sektor lokasi, dll..." class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
            </div>

            <div class="flex gap-2 mt-6 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-tambah-kerja-bakti').classList.add('hidden')" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-500 shadow-md">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT KERJA BAKTI --}}
<div id="modal-edit-kerja-bakti" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 relative shadow-2xl">
        <div class="flex items-center justify-between mb-5 border-b pb-3">
            <div>
                <h3 class="text-base font-extrabold text-gray-800">Edit Agenda Kerja Bakti</h3>
                <p class="text-xs text-gray-400">Perbarui jadwal atau status gotong royong</p>
            </div>
            <button onclick="document.getElementById('modal-edit-kerja-bakti').classList.add('hidden')" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="form-edit-kerja-bakti" onsubmit="saveKerjaBakti(event, 'form-edit-kerja-bakti', '/kerja-bakti/update')">
            @csrf
            <input type="hidden" name="id" id="edit-kb-id">

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Nama / Topik Kegiatan Kerja Bakti</label>
                    <input type="text" name="nama_kegiatan" id="edit-kb-nama" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" id="edit-kb-tanggal" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jam Mulai</label>
                        <input type="text" name="waktu_mulai" id="edit-kb-mulai" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jam Selesai</label>
                        <input type="text" name="waktu_selesai" id="edit-kb-selesai" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Lokasi Kegiatan</label>
                    <input type="text" name="lokasi" id="edit-kb-lokasi" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Perlengkapan Dibawa Warga</label>
                    <input type="text" name="perlengkapan" id="edit-kb-perlengkapan" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Status Kegiatan</label>
                    <select name="status" id="edit-kb-status" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                        <option value="Mendatang">Mendatang</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Keterangan / Catatan</label>
                    <textarea name="keterangan" id="edit-kb-keterangan" rows="2" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
            </div>

            <div class="flex gap-2 mt-6 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-edit-kerja-bakti').classList.add('hidden')" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-500 shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function saveKerjaBakti(e, formId, url) {
        e.preventDefault();
        const fd = new FormData(document.getElementById(formId));
        fetch(url, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            alert(d.message);
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('kerja-bakti'); }
            switchPage('kerja-bakti');
        })
        .catch(() => alert('Terjadi kesalahan saat menyimpan data.'));
    }

    function openEditKerjaBaktiModal(kb) {
        document.getElementById('edit-kb-id').value = kb.id;
        document.getElementById('edit-kb-nama').value = kb.nama_kegiatan;
        document.getElementById('edit-kb-tanggal').value = kb.tanggal;
        document.getElementById('edit-kb-mulai').value = kb.waktu_mulai;
        document.getElementById('edit-kb-selesai').value = kb.waktu_selesai;
        document.getElementById('edit-kb-lokasi').value = kb.lokasi;
        document.getElementById('edit-kb-perlengkapan').value = kb.perlengkapan;
        document.getElementById('edit-kb-status').value = kb.status;
        document.getElementById('edit-kb-keterangan').value = kb.keterangan || '';
        document.getElementById('modal-edit-kerja-bakti').classList.remove('hidden');
    }

    function deleteKerjaBakti(id, title) {
        if (!confirm('Apakah Anda yakin ingin menghapus agenda kerja bakti "' + title + '"?')) return;
        const fd = new FormData();
        fd.append('id', id);
        fd.append('_token', window.csrfToken);
        fetch('/kerja-bakti/delete', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            alert(d.message);
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('kerja-bakti'); }
            switchPage('kerja-bakti');
        })
        .catch(() => alert('Gagal menghapus agenda.'));
    }

    function filterKerjaBakti(q) {
        const query = q.toLowerCase().trim();
        document.querySelectorAll('.kerja-bakti-card').forEach(card => {
            const data = card.getAttribute('data-search') || '';
            card.style.display = (!query || data.includes(query)) ? '' : 'none';
        });
    }

    function filterStatusKerjaBakti(st) {
        document.querySelectorAll('.filter-kb-btn').forEach(btn => {
            btn.classList.remove('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
            btn.classList.add('text-gray-500', 'border-gray-100');
        });
        event.currentTarget.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');

        document.querySelectorAll('.kerja-bakti-card').forEach(card => {
            const status = card.getAttribute('data-status') || '';
            card.style.display = (!st || status === st) ? '' : 'none';
        });
    }
</script>
