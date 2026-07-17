<div class="p-4 lg:p-8 space-y-6 max-w-[1400px] mx-auto">

    <!-- Hero Banner & Stats Header -->
    <div class="bg-gradient-to-br from-[#064e3b] via-[#065f46] to-[#0f172a] rounded-[2rem] p-6 lg:p-8 text-white relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-lg"></div>
        <i class="fa-solid fa-recycle absolute -bottom-6 -right-4 text-[130px] opacity-[0.03] rotate-12"></i>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-recycle text-emerald-300 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-emerald-300/80">Layanan Warga</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight">Bank Sampah</h1>
                <p class="text-sm text-white/50 font-medium mt-1">Kelola penimbangan setoran daur ulang plastik, kertas, & logam warga.</p>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Quick Stats Badge 1 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="text-2xl font-black text-white leading-none">{{ number_format($total_berat ?? 0, 1, ',', '.') }} <span class="text-xs font-normal">Kg</span></p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-emerald-300/70 mt-1">Terkumpul</p>
                </div>

                <!-- Quick Stats Badge 2 -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                    <p class="stat-counter text-2xl font-black text-white leading-none" data-value="{{ $total_rupiah ?? 0 }}" data-type="currency">Rp 0</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-emerald-300/70 mt-1">Nilai Tabungan</p>
                </div>

                @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                <button onclick="document.getElementById('modal-tambah-sampah').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-6 py-3.5 rounded-2xl transition-all flex items-center gap-2.5 cursor-pointer text-sm shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 border border-emerald-400/30">
                    <i class="fa-solid fa-plus-circle text-base"></i> Catat Setoran
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Setoran -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-black text-gray-800">Riwayat Setoran Sampah Warga</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-extrabold tracking-widest border-b border-gray-100">
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Nama Warga</th>
                        <th class="py-4 px-6">Kategori Sampah</th>
                        <th class="py-4 px-6">Berat (Kg)</th>
                        <th class="py-4 px-6">Nilai Rupiah</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                    @forelse($list_deposit ?? [] as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-gray-500">{{ $item->tanggal }}</td>
                        <td class="py-4 px-6 font-bold text-gray-800">{{ $item->nama_warga }}</td>
                        <td class="py-4 px-6"><span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold">{{ $item->jenis_sampah }}</span></td>
                        <td class="py-4 px-6 font-bold">{{ $item->berat_kg }} Kg</td>
                        <td class="py-4 px-6 font-bold text-emerald-600">Rp {{ number_format($item->total_rupiah, 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-right">
                            @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'Bendahara']))
                            <button onclick="hapusBankSampah({{ $item->id }})" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            @else
                            <span class="text-xs text-gray-400 italic">Tercatat</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 italic">Belum ada catatan setoran bank sampah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-sampah" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 relative shadow-2xl border border-gray-100">
        <button onclick="document.getElementById('modal-tambah-sampah').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="text-xl font-black text-gray-800 mb-6">Catat Setoran Bank Sampah</h3>
        <form id="form-bank-sampah" action="/bank-sampah/store" method="POST" onsubmit="simpanDataUmum(event, 'form-bank-sampah', 'bank-sampah')">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Warga Penyetor</label>
                    <div class="relative">
                        <input type="hidden" name="nama_warga" id="nama_warga_penyetor_hidden" required>
                        <div class="relative">
                            <input type="text" id="penyetor_search_input" placeholder="🔍 Cari & pilih nama warga..." 
                                   onfocus="showDropdown('penyetor_dropdown')" 
                                   onkeyup="filterCustomDropdown('penyetor_search_input', 'penyetor_dropdown')" 
                                   autocomplete="off"
                                   class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 pr-10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                        </div>

                        <div id="penyetor_dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl z-30 max-h-56 overflow-y-auto divide-y divide-gray-50">
                            @foreach($all_warga ?? [] as $w)
                                <div onclick="selectPenyetorOption('{{ addslashes($w->nama_lengkap) }}')" 
                                     class="dropdown-item px-4 py-2.5 hover:bg-emerald-50 cursor-pointer transition flex items-center justify-between text-xs font-semibold text-gray-700">
                                    <div>
                                        <span class="block font-bold">{{ $w->nama_lengkap }}</span>
                                        <span class="text-[10px] text-gray-400 font-normal">Blok {{ $w->blok_rumah }}</span>
                                    </div>
                                    <span class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full font-bold">{{ $w->umur ? $w->umur.' Thn' : '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jenis Sampah</label>
                        <select name="jenis_sampah" id="jenis_sampah_select" onchange="hitungKonversiRupiah()" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="Botol Plastik" data-harga="3000">Botol Plastik (Rp 3.000 / Kg)</option>
                            <option value="Kardus & Kertas" data-harga="2500">Kardus & Kertas (Rp 2.500 / Kg)</option>
                            <option value="Besi & Logam" data-harga="7000">Besi & Logam (Rp 7.000 / Kg)</option>
                            <option value="Minyak Jelantah" data-harga="6000">Minyak Jelantah (Rp 6.000 / L)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Setor</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Berat Total (Kg)</label>
                        <input type="number" step="0.1" name="berat_kg" id="berat_kg_input" oninput="hitungKonversiRupiah()" placeholder="Contoh: 2.5" required class="w-full bg-gray-50 border border-gray-200 font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Konversi Rupiah (Rp)</label>
                        <input type="number" name="total_rupiah" id="total_rupiah_input" required class="w-full bg-emerald-50 border border-emerald-200 font-extrabold text-emerald-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Otomatis terhitung">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-sampah').classList.add('hidden')" class="px-6 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200">Simpan Setoran</button>
            </div>
        </form>
    </div>
</div>

<script>
function hitungKonversiRupiah() {
    const jenisSelect = document.getElementById('jenis_sampah_select');
    const beratInput = document.getElementById('berat_kg_input');
    const rupiahInput = document.getElementById('total_rupiah_input');
    if (!jenisSelect || !beratInput || !rupiahInput) return;

    const selectedOption = jenisSelect.options[jenisSelect.selectedIndex];
    const hargaPerKg = parseFloat(selectedOption ? selectedOption.getAttribute('data-harga') : 3000) || 3000;
    const berat = parseFloat(beratInput.value) || 0;

    const total = Math.round(berat * hargaPerKg);
    rupiahInput.value = total > 0 ? total : '';
}

function showDropdown(id) {
    document.getElementById(id).classList.remove('hidden');
}

function filterCustomDropdown(inputId, dropdownId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const dropdown = document.getElementById(dropdownId);
    dropdown.classList.remove('hidden');
    
    const items = dropdown.getElementsByClassName('dropdown-item');
    for (let i = 0; i < items.length; i++) {
        const txt = items[i].textContent || items[i].innerText;
        if (txt.toLowerCase().includes(filter)) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}

function selectPenyetorOption(nama) {
    document.getElementById('penyetor_search_input').value = nama;
    document.getElementById('nama_warga_penyetor_hidden').value = nama;
    document.getElementById('penyetor_dropdown').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    const pInput = document.getElementById('penyetor_search_input');
    const pDrop = document.getElementById('penyetor_dropdown');
    if (pInput && pDrop && !pInput.contains(e.target) && !pDrop.contains(e.target)) {
        pDrop.classList.add('hidden');
    }
});

function hapusBankSampah(id) {
    Swal.fire({
        title: 'Hapus Setoran Sampah?',
        text: "Data riwayat setoran sampah warga ini akan dihapus.",
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
            fetch('/bank-sampah/delete', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => { 
                Swal.fire({ title: 'Berhasil!', text: 'Setoran sampah telah dihapus.', icon: 'success', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-3xl p-6 font-sans' } });
                if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('bank-sampah'); }
                switchPage('bank-sampah', document.querySelector('.menu-active')); 
            });
        }
    });
}
</script>
