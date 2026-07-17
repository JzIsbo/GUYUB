@php
    $user = Auth::user();
    $warga = DB::table('wargas')->where('nama_lengkap', $user->name)->first();
@endphp

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-tight">Pengaturan Data Diri Pengguna</h2>
        <p class="text-gray-500 dark:text-slate-400 text-sm mt-1">Ubah biodata pribadi, identitas kependudukan, kontak, dan keamanan akun Anda.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-800/80">
        <form id="form-profil-baru" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 1. FOTO PROFIL --}}
            <div class="mb-8 p-6 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/10 flex flex-col sm:flex-row items-center gap-6">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full border-4 border-white dark:border-slate-700 shadow-md overflow-hidden bg-gray-50 flex items-center justify-center">
                        <img id="avatar-preview" src="{{ $user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="flex-1 text-center sm:text-left">
                    <label class="block text-sm font-extrabold text-gray-800 dark:text-white mb-1">Foto Profil / Foto Diri Anda</label>
                    <p class="text-xs text-gray-400 dark:text-slate-400 mb-3">Mendukung file foto JPG, JPEG, PNG, GIF, atau SVG. Ukuran maksimal 2MB.</p>
                    
                    <div class="inline-block relative">
                        <input type="file" name="avatar_file" id="avatar_file" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                        <button type="button" onclick="document.getElementById('avatar_file').click()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            Pilih Foto Baru
                        </button>
                    </div>
                </div>
            </div>

            {{-- 2. IDENTITAS KEPENDUDUKAN --}}
            <div class="mb-8">
                <h3 class="text-base font-extrabold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400"></i> Identitas Utama & Kependudukan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Nama Lengkap Pengguna <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">NIK (No. Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ $warga->nik ?? '' }}" maxlength="16" placeholder="3275000011223344" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Nomor Kartu Keluarga (No. KK)</label>
                        <input type="text" name="nomor_kk" value="{{ $warga->nomor_kk ?? '' }}" maxlength="16" placeholder="3275880022334455" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                    </div>
                </div>
            </div>

            {{-- 3. KONTAK & BIODATA PERSONAL --}}
            <div class="mb-8 pt-6 border-t border-gray-100 dark:border-slate-800/80">
                <h3 class="text-base font-extrabold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-emerald-600 dark:text-emerald-400"></i> Kontak & Biodata Personal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_telepon" value="{{ $warga->no_telepon ?? '' }}" placeholder="081234567890" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Usia / Umur (Tahun)</label>
                        <input type="number" name="umur" value="{{ $warga->umur ?? '' }}" min="1" max="120" placeholder="30" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                            <option value="Laki-laki" {{ ($warga->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ ($warga->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Agama</label>
                        <select name="agama" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm)
                                <option value="{{ $agm }}" {{ ($warga->agama ?? '') === $agm ? 'selected' : '' }}>{{ $agm }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Alamat Domisili / Blok Rumah</label>
                        <input type="text" name="blok_rumah" value="{{ $warga->blok_rumah ?? '' }}" placeholder="Blok A No. 12" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Status Keluarga</label>
                        <select name="status_keluarga" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">
                            @foreach(['Kepala Keluarga', 'Istri', 'Anak', 'Orang Tua', 'Lainnya'] as $st)
                                <option value="{{ $st }}" {{ ($warga->status_keluarga ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 4. KEAMANAN AKUN --}}
            <div class="mb-8 pt-6 border-t border-gray-100 dark:border-slate-800/80">
                <h3 class="text-base font-extrabold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-amber-500"></i> Keamanan Akun
                </h3>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2">Ganti Password Baru <span class="text-gray-400 font-normal">(Biarkan jika tidak ingin mengubah)</span></label>
                    <div class="relative">
                        <input type="password" id="input-password" name="password" placeholder="Masukkan password baru..." class="w-full px-4 py-3.5 pr-12 rounded-2xl border border-gray-200 dark:border-slate-800 dark:bg-slate-950/60 text-gray-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-semibold">

                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-blue-600 transition-colors focus:outline-none border-none bg-transparent">
                            <i class="fa-solid fa-eye" id="icon-mata"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-slate-800/80">
                <button type="button" onclick="simpanProfil(this)" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 px-8 rounded-2xl transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2 cursor-pointer border-none">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan Data Diri
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.togglePassword = function() {
        let inputPass = document.getElementById('input-password');
        let iconMata = document.getElementById('icon-mata');
        if (inputPass.type === "password") {
            inputPass.type = "text";
            iconMata.className = "fa-solid fa-eye-slash";
        } else {
            inputPass.type = "password";
            iconMata.className = "fa-solid fa-eye";
        }
    };

    window.previewAvatar = function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('avatar-preview');
            if (preview) {
                preview.src = reader.result;
            }
        }
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    };

    window.simpanProfil = function(btn) {
        let form = document.getElementById('form-profil-baru');
        let formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Menyimpan Data Diri...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Data diri & profil pengguna berhasil diperbarui!');
            if (typeof window.invalidatePageCache === 'function') {
                window.invalidatePageCache('pengaturan');
                window.invalidatePageCache('dashboard');
            }
            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
        })
        .catch(err => {
            alert('Gagal menyimpan data diri, silakan periksa inputan Anda.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Perubahan Data Diri';
        });
    };
</script>
