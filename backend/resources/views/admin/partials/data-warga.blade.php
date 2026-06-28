<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Warga RT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-4 lg:p-8 font-sans">

    @php
        $totalWarga = 0;
        $totalKK = 0;
        if(isset($warga_grouped)) {
            $totalKK = $warga_grouped->count();
            foreach($warga_grouped as $anggota) {
                $totalWarga += $anggota->count();
            }
        }
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative max-w-[1400px] !mx-auto !w-full !px-4">

        <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-8 rounded-[2.5rem] shadow-xl text-white h-fit relative overflow-hidden">
            <div class="relative z-10">
                <span class="bg-blue-500/10 text-blue-400 text-[10px] px-3 py-1.5 rounded-xl font-black border border-blue-500/20 uppercase tracking-widest">Sistem Kependudukan</span>
                <h2 class="text-3xl font-black mt-4 tracking-tight">Data Warga RT</h2>

                <div class="mt-8 pt-6 border-t border-white/5 space-y-6">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-none mb-2">Total Kelompok Keluarga</p>
                        <p class="text-4xl font-black text-white">{{ $totalKK }} <span class="text-sm text-gray-500 font-medium">KK</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-none mb-2">Total Penduduk</p>
                        <p class="text-4xl font-black text-white">{{ $totalWarga }} <span class="text-sm text-gray-500 font-medium">Jiwa</span></p>
                    </div>

                    <div class="pt-4 border-t border-white/5">
                        <button type="button" onclick="bukaModal('modal-form-warga', 'Tambah Warga / KK Baru', true)" class="inline-flex w-full items-center justify-center bg-blue-500 hover:bg-blue-400 text-white font-black text-[10px] uppercase tracking-widest py-3.5 px-6 rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(59,130,246,0.4)] hover:shadow-[0_0_25px_rgba(59,130,246,0.6)] hover:-translate-y-0.5 relative overflow-hidden group">
                            <div class="absolute inset-0 w-1/4 h-full bg-white/20 skew-x-12 -translate-x-full group-hover:translate-x-[400%] transition-transform duration-700"></div>
                            <i class="fa-solid fa-user-plus mr-2 text-sm"></i> Tambah Warga Baru
                        </button>
                    </div>
                </div>
            </div>
            <i class="fa-solid fa-users absolute -bottom-10 -right-10 text-[150px] opacity-5 rotate-12"></i>
        </div>

        <div class="lg:col-span-2 space-y-8">

            @forelse($warga_grouped ?? [] as $group_key => $anggota)
                @php
                    $current_kk = $anggota->first()->nomor_kk ?? 'belum-ada-kk';
                    $current_blok = $anggota->first()->blok_rumah ?? 'Tanpa Blok';
                @endphp

                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)]">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-gray-50 pb-6">
                        <div>
                            <h3 class="text-xl font-black text-gray-800 tracking-tight">Blok {{ $current_blok }}</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">NO. KK: {{ $current_kk }}</p>
                        </div>
                        <button type="button" onclick="bukaModalAnggota('{{ $current_kk }}', '{{ $current_blok }}')" class="bg-blue-50 text-blue-600 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition whitespace-nowrap">
                            + Tambah Anggota
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left whitespace-nowrap">
                            <thead class="text-gray-400 uppercase text-[10px] font-bold tracking-widest">
                                <tr class="border-b border-gray-100">
                                    <th class="pb-3 pr-4">No</th>
                                    <th class="pb-3 pr-4">Nama Lengkap</th>
                                    <th class="pb-3 pr-4">NIK</th>
                                    <th class="pb-3 pr-4">Telepon</th>
                                    <th class="pb-3 pr-4">Status</th>
                                    <th class="pb-3 pr-4">Domisili</th>
                                    <th class="pb-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 font-bold">
                                @foreach($anggota as $index => $w)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="py-4 pr-4 text-gray-400">{{ $index + 1 }}</td>
                                    <td class="py-4 pr-4">{{ $w->nama_lengkap }}</td>
                                    <td class="py-4 pr-4 font-medium text-gray-500">{{ $w->nik }}</td>
                                    <td class="py-4 pr-4 font-medium text-gray-500">{{ $w->no_telepon ?? '-' }}</td>
                                    <td class="py-4 pr-4">
                                        @if($w->status_keluarga == 'Kepala Keluarga')
                                            <span class="px-2 py-1 rounded-lg text-[10px] bg-blue-100 text-blue-700">Kepala Keluarga</span>
                                        @elseif($w->status_keluarga == 'Istri')
                                            <span class="px-2 py-1 rounded-lg text-[10px] bg-pink-100 text-pink-700">Istri</span>
                                        @else
                                            <span class="px-2 py-1 rounded-lg text-[10px] bg-emerald-100 text-emerald-700">Anak</span>
                                        @endif
                                    </td>
                                    <td class="py-4 pr-4">
                                        <span class="px-2 py-1 rounded-lg text-[10px] {{ $w->status_domisili == 'Tetap' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                            {{ $w->status_domisili }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-center flex justify-center gap-3">
                                        <button type="button" onclick="bukaModalEdit({{ $w->id }}, '{{ $w->nomor_kk }}', '{{ $w->nik }}', '{{ addslashes($w->nama_lengkap) }}', '{{ $w->no_telepon }}', '{{ $w->blok_rumah }}', '{{ $w->status_keluarga }}', '{{ $w->status_domisili }}')" class="text-blue-500 hover:text-blue-700 transition"><i class="fa-solid fa-pen"></i></button>
                                        <button type="button" onclick="hapusWarga({{ $w->id }})" class="text-red-500 hover:text-red-700 transition"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] text-center">
                    <i class="fa-solid fa-folder-open text-gray-200 text-6xl mb-4"></i>
                    <h3 class="text-xl font-black text-gray-800 tracking-tight mb-2">Belum Ada Warga</h3>
                    <p class="text-sm text-gray-500 font-medium">Sistem belum memiliki data warga untuk ditampilkan.</p>
                </div>
            @endforelse

        </div>
    </div>

    <div id="modal-form-warga" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white p-8 rounded-[2rem] w-full max-w-lg shadow-2xl">
            <h3 id="modal-title" class="font-black text-2xl mb-6 text-gray-800">Form Warga</h3>

            <form id="formWarga" onsubmit="simpanWarga(event)">
                <input type="hidden" id="warga_id" name="id">

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nomor KK</label>
                        <input type="number" id="nomor_kk" name="nomor_kk" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NIK</label>
                        <input type="number" id="nik" name="nik" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Blok Rumah</label>
                        <input type="text" id="blok_rumah" name="blok_rumah" required placeholder="Cth: Blok A1" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Keluarga</label>
                        <select id="status_keluarga" name="status_keluarga" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Domisili</label>
                        <select id="status_domisili" name="status_domisili" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl mt-1 font-bold text-gray-700 focus:outline-none focus:border-blue-500">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Kos">Kos</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="tutupModal('modal-form-warga')" class="flex-1 bg-gray-100 p-4 rounded-xl font-black text-gray-500 text-xs uppercase tracking-widest hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" id="btn-submit" class="flex-1 bg-blue-600 text-white p-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var csrfToken = window.csrfToken || (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}');

        // Fungsi Buka Modal Umum (Untuk Tambah Warga Baru)
        function bukaModal(idModal, judul, isBaru) {
            document.getElementById(idModal).classList.remove('hidden');
            document.getElementById('modal-title').innerText = judul;
            if(isBaru) {
                document.getElementById('formWarga').reset();
                document.getElementById('warga_id').value = '';
                document.getElementById('nomor_kk').readOnly = false;
                document.getElementById('blok_rumah').readOnly = false;
                document.getElementById('nomor_kk').classList.remove('bg-gray-200');
                document.getElementById('blok_rumah').classList.remove('bg-gray-200');
            }
        }

        // Fungsi Buka Modal Tambah Anggota (Lock KK & Blok)
        function bukaModalAnggota(no_kk, blok) {
            bukaModal('modal-form-warga', 'Tambah Anggota Keluarga', true);
            let inputKK = document.getElementById('nomor_kk');
            let inputBlok = document.getElementById('blok_rumah');

            inputKK.value = no_kk;
            inputKK.readOnly = true;
            inputKK.classList.add('bg-gray-200'); // Efek terkunci

            inputBlok.value = blok;
            inputBlok.readOnly = true;
            inputBlok.classList.add('bg-gray-200');

            // Set default status agar bukan kepala keluarga lagi
            document.getElementById('status_keluarga').value = 'Anak';
        }

        // Fungsi Buka Modal Edit
        function bukaModalEdit(id, kk, nik, nama, telepon, blok, status, domisili) {
            bukaModal('modal-form-warga', 'Edit Data Warga', false);
            document.getElementById('warga_id').value = id;
            document.getElementById('nomor_kk').value = kk;
            document.getElementById('nik').value = nik;
            document.getElementById('nama_lengkap').value = nama;
            document.getElementById('no_telepon').value = telepon;
            document.getElementById('blok_rumah').value = blok;
            document.getElementById('status_keluarga').value = status;
            document.getElementById('status_domisili').value = domisili;

            // Buka kunci input
            document.getElementById('nomor_kk').readOnly = false;
            document.getElementById('blok_rumah').readOnly = false;
            document.getElementById('nomor_kk').classList.remove('bg-gray-200');
            document.getElementById('blok_rumah').classList.remove('bg-gray-200');
        }

        // Fungsi Tutup Modal
        function tutupModal(idModal) {
            document.getElementById(idModal).classList.add('hidden');
        }

        // Fungsi Simpan/Update Data Realtime (Fetch API)
        function simpanWarga(event) {
            event.preventDefault();
            let form = document.getElementById('formWarga');
            let formData = new FormData(form);
            let id = document.getElementById('warga_id').value;
            let url = id ? '/admin/warga/update' : '/admin/warga/store';

            let btn = document.getElementById('btn-submit');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Cek secara ketat apakah data.success benar-benar bernilai true
                if(data.success === true) {
                    tutupModal('modal-form-warga');
                    location.reload(); // Notifikasi hijau dari template akan otomatis muncul setelah reload
                } else {
                    // Jika false, baru tampilkan alert peringatan
                    alert('Peringatan: ' + (data.message || 'Mohon periksa kembali input Anda.'));
                    btn.innerHTML = 'Simpan Data';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan pada jaringan atau server.');
                btn.innerHTML = 'Simpan Data';
                btn.disabled = false;
            });
        }

        // Fungsi Hapus Data Realtime
        function hapusWarga(id) {
            if(!confirm('Apakah Anda yakin ingin menghapus data warga ini? Data yang dihapus tidak bisa dikembalikan.')) return;

            fetch('/admin/warga/delete/' + id, {
                method: 'POST', // Gunakan DELETE jika route diset DELETE
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' }) // Spoofing Delete method
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus data.');
                }
            });
        }
    </script>
</body>
</html>
