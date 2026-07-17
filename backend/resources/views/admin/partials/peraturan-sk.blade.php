{{-- resources/views/admin/partials/peraturan-sk.blade.php --}}
@php
    $canManage = in_array(Auth::user()->role, ['Super Admin', 'RW', 'Sekretaris RW', 'RT', 'Sekretaris RT']);
@endphp

<div class="space-y-6">
    {{-- Header Banner --}}
    <div class="relative bg-gradient-to-br from-[#1e1b4b] via-[#312e81] to-[#0f172a] rounded-[2.5rem] p-6 lg:p-8 overflow-hidden shadow-2xl border border-indigo-500/20">
        <div class="absolute -right-10 -bottom-10 text-white/[0.03] text-[14rem] rotate-12 pointer-events-none">
            <i class="fa-solid fa-scroll"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-indigo-500/20 backdrop-blur-md border border-indigo-400/20 rounded-full px-4 py-1.5">
                    <i class="fa-solid fa-scale-balanced text-indigo-300 text-xs"></i>
                    <span class="text-xs font-semibold text-indigo-200 tracking-wider uppercase">Tata Tertib & Legalitas Lingkungan</span>
                </div>
                <h2 class="text-2xl lg:text-3xl font-black text-white tracking-tight">Peraturan & Surat Keputusan (SK) RT/RW</h2>
                <p class="text-indigo-200/70 text-sm max-w-2xl">Repositori dokumen resmi, pedoman tata tertib warga, dan SK penetapan pengurus lingkungan RT/RW.</p>
            </div>
            
            @if($canManage)
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('modal-tambah-peraturan').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-3 rounded-2xl font-bold text-xs flex items-center gap-2.5 transition-all shadow-lg shadow-indigo-600/30 hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-file-circle-plus text-sm"></i>
                    <span>Tambah Peraturan / SK</span>
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-3 items-center justify-between">
        <div class="relative w-full md:w-96">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
            <input type="text" id="search-peraturan" placeholder="Cari judul, nomor SK, atau kata kunci..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium transition-all" onkeyup="filterPeraturan(this.value)">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <button onclick="filterKategoriPeraturan('')" class="filter-btn active text-xs font-bold px-3.5 py-2 rounded-xl border transition-all bg-indigo-50 text-indigo-600 border-indigo-200">Semua</button>
            <button onclick="filterKategoriPeraturan('Peraturan RT')" class="filter-btn text-xs font-bold px-3.5 py-2 rounded-xl border transition-all text-gray-500 border-gray-100 hover:bg-gray-50">Peraturan RT</button>
            <button onclick="filterKategoriPeraturan('Peraturan RW')" class="filter-btn text-xs font-bold px-3.5 py-2 rounded-xl border transition-all text-gray-500 border-gray-100 hover:bg-gray-50">Peraturan RW</button>
            <button onclick="filterKategoriPeraturan('SK Pengurus')" class="filter-btn text-xs font-bold px-3.5 py-2 rounded-xl border transition-all text-gray-500 border-gray-100 hover:bg-gray-50">SK Pengurus</button>
        </div>
    </div>

    {{-- Cards Grid Table --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="peraturan-grid">
        @forelse($list_peraturan ?? [] as $item)
        <div class="peraturan-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between"
             data-kategori="{{ $item->kategori }}"
             data-search="{{ strtolower($item->judul . ' ' . $item->nomor_dokumen . ' ' . $item->kategori . ' ' . $item->keterangan) }}">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider
                        {{ $item->kategori == 'Peraturan RT' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                        {{ $item->kategori == 'Peraturan RW' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : '' }}
                        {{ $item->kategori == 'SK Pengurus' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                        {{ $item->kategori == 'Himbauan Lingkungan' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}">
                        {{ $item->kategori }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $item->status == 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ strtoupper($item->status) }}
                    </span>
                </div>

                <div>
                    <h3 class="font-extrabold text-gray-800 text-sm leading-snug hover:text-indigo-600 transition-colors">{{ $item->judul }}</h3>
                    <p class="text-[11px] text-gray-400 font-medium mt-1"><i class="fa-solid fa-hashtag text-[9px] mr-1"></i>{{ $item->nomor_dokumen ?? 'Tanpa Nomor' }}</p>
                </div>

                <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">{{ $item->keterangan ?? 'Tidak ada uraian tambahan.' }}</p>
            </div>

            <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-[10px] text-gray-400 font-semibold">
                    <i class="fa-regular fa-calendar-check mr-1"></i>Berlaku: {{ \Carbon\Carbon::parse($item->tanggal_berlaku)->format('d M Y') }}
                </span>

                <div class="flex items-center gap-1.5">
                    @if($item->file_path)
                    <a href="{{ asset($item->file_path) }}" target="_blank" class="px-2.5 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-[10px] font-bold flex items-center gap-1 transition">
                        <i class="fa-solid fa-file-pdf"></i> Unduh
                    </a>
                    @endif

                    @if($canManage)
                    <button onclick="openEditPeraturanModal({{ json_encode($item) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <button onclick="deletePeraturan({{ $item->id }}, '{{ addslashes($item->judul) }}')" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
            <i class="fa-solid fa-folder-open text-gray-300 text-4xl mb-3"></i>
            <p class="text-sm font-bold text-gray-500">Belum ada dokumen Peraturan atau SK yang terdaftar.</p>
            <p class="text-xs text-gray-400 mt-1">Gunakan tombol 'Tambah Peraturan / SK' untuk mengunggah tata tertib lingkungan.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH PERATURAN --}}
<div id="modal-tambah-peraturan" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 relative shadow-2xl">
        <div class="flex items-center justify-between mb-5 border-b pb-3">
            <div>
                <h3 class="text-base font-extrabold text-gray-800">Tambah Peraturan / SK Baru</h3>
                <p class="text-xs text-gray-400">Publikasikan pedoman legalitas lingkungan RT/RW</p>
            </div>
            <button onclick="document.getElementById('modal-tambah-peraturan').classList.add('hidden')" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="form-tambah-peraturan" onsubmit="savePeraturan(event, 'form-tambah-peraturan', '/peraturan-sk/store')">
            @csrf
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Judul Dokumen / SK</label>
                    <input type="text" name="judul" required placeholder="Contoh: Tata Tertib Wajib Lapor Tamu 1x24 Jam" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Nomor Dokumen/SK</label>
                        <input type="text" name="nomor_dokumen" placeholder="PER-RT05/01/2026" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Kategori</label>
                        <select name="kategori" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                            <option value="Peraturan RT">Peraturan RT</option>
                            <option value="Peraturan RW">Peraturan RW</option>
                            <option value="SK Pengurus">SK Pengurus</option>
                            <option value="Himbauan Lingkungan">Himbauan Lingkungan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Berlaku</label>
                        <input type="date" name="tanggal_berlaku" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Status Dokumen</label>
                        <select name="status" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                            <option value="Aktif">Aktif</option>
                            <option value="Arsip">Arsip</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Uraian Ringkas / Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Tuliskan pokok-pokok isi peraturan..." class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Upload File PDF / Salinan Dokumen (Opsional)</label>
                    <input type="file" name="file_dokumen" accept=".pdf,.doc,.docx,.jpg,.png" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-600 file:font-bold hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="flex gap-2 mt-6 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-tambah-peraturan').classList.add('hidden')" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-500 shadow-md">Simpan Dokumen</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PERATURAN --}}
<div id="modal-edit-peraturan" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 relative shadow-2xl">
        <div class="flex items-center justify-between mb-5 border-b pb-3">
            <div>
                <h3 class="text-base font-extrabold text-gray-800">Edit Peraturan / SK</h3>
                <p class="text-xs text-gray-400">Perbarui data atau status dokumen legalitas</p>
            </div>
            <button onclick="document.getElementById('modal-edit-peraturan').classList.add('hidden')" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="form-edit-peraturan" onsubmit="savePeraturan(event, 'form-edit-peraturan', '/peraturan-sk/update')">
            @csrf
            <input type="hidden" name="id" id="edit-peraturan-id">

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Judul Dokumen / SK</label>
                    <input type="text" name="judul" id="edit-peraturan-judul" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Nomor Dokumen/SK</label>
                        <input type="text" name="nomor_dokumen" id="edit-peraturan-nomor" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Kategori</label>
                        <select name="kategori" id="edit-peraturan-kategori" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                            <option value="Peraturan RT">Peraturan RT</option>
                            <option value="Peraturan RW">Peraturan RW</option>
                            <option value="SK Pengurus">SK Pengurus</option>
                            <option value="Himbauan Lingkungan">Himbauan Lingkungan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Berlaku</label>
                        <input type="date" name="tanggal_berlaku" id="edit-peraturan-tanggal" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Status Dokumen</label>
                        <select name="status" id="edit-peraturan-status" required class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl font-semibold">
                            <option value="Aktif">Aktif</option>
                            <option value="Arsip">Arsip</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Uraian Ringkas / Keterangan</label>
                    <textarea name="keterangan" id="edit-peraturan-keterangan" rows="3" class="w-full py-2.5 px-3.5 bg-gray-50 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Ganti File Dokumen (Biarkan kosong jika tidak diganti)</label>
                    <input type="file" name="file_dokumen" accept=".pdf,.doc,.docx,.jpg,.png" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-600 file:font-bold hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="flex gap-2 mt-6 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-edit-peraturan').classList.add('hidden')" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-500 shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function savePeraturan(e, formId, url) {
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
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('peraturan-sk'); }
            switchPage('peraturan-sk');
        })
        .catch(() => alert('Terjadi kesalahan saat menyimpan data.'));
    }

    function openEditPeraturanModal(item) {
        document.getElementById('edit-peraturan-id').value = item.id;
        document.getElementById('edit-peraturan-judul').value = item.judul;
        document.getElementById('edit-peraturan-nomor').value = item.nomor_dokumen || '';
        document.getElementById('edit-peraturan-kategori').value = item.kategori;
        document.getElementById('edit-peraturan-tanggal').value = item.tanggal_berlaku;
        document.getElementById('edit-peraturan-status').value = item.status;
        document.getElementById('edit-peraturan-keterangan').value = item.keterangan || '';
        document.getElementById('modal-edit-peraturan').classList.remove('hidden');
    }

    function deletePeraturan(id, title) {
        if (!confirm('Apakah Anda yakin ingin menghapus peraturan/SK "' + title + '"?')) return;
        const fd = new FormData();
        fd.append('id', id);
        fd.append('_token', window.csrfToken);
        fetch('/peraturan-sk/delete', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            alert(d.message);
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('peraturan-sk'); }
            switchPage('peraturan-sk');
        })
        .catch(() => alert('Gagal menghapus dokumen.'));
    }

    function filterPeraturan(q) {
        const query = q.toLowerCase().trim();
        document.querySelectorAll('.peraturan-card').forEach(card => {
            const data = card.getAttribute('data-search') || '';
            card.style.display = (!query || data.includes(query)) ? '' : 'none';
        });
    }

    function filterKategoriPeraturan(kat) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-50', 'text-indigo-600', 'border-indigo-200');
            btn.classList.add('text-gray-500', 'border-gray-100');
        });
        event.currentTarget.classList.add('bg-indigo-50', 'text-indigo-600', 'border-indigo-200');

        document.querySelectorAll('.peraturan-card').forEach(card => {
            const kategori = card.getAttribute('data-kategori') || '';
            card.style.display = (!kat || kategori === kat) ? '' : 'none';
        });
    }
</script>
