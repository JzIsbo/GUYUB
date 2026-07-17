@php
    $user = Auth::user();
    $warga = DB::table('wargas')->where('nama_lengkap', $user->name)->first();
@endphp

<div class="p-3 max-w-[600px] mx-auto pb-8">
    <div class="mb-4">
        <h2 class="text-sm font-black text-gray-850 dark:text-white mb-0.5">Pengaturan Data Diri Pengguna</h2>
        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Ubah biodata pribadi, identitas kependudukan, kontak, dan keamanan akun Anda</p>
    </div>

    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800/80">
        <form id="form-profil-baru-m" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- FOTO PROFIL UPLOAD --}}
            <div class="mb-6 flex flex-col items-center gap-4 text-center">
                <div class="relative group">
                    <div class="w-20 h-20 rounded-full border-4 border-slate-50 dark:border-slate-850 shadow-md overflow-hidden bg-slate-50 flex items-center justify-center">
                        <img id="avatar-preview-m" src="{{ $user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Foto Profil Anda</label>
                    <p class="text-[9px] text-gray-400 leading-normal mb-3 font-semibold">Format JPG, PNG, GIF. Maksimal 2MB.</p>
                    
                    <div class="inline-block relative">
                        <input type="file" name="avatar_file" id="avatar_file_m" class="hidden" accept="image/*" onchange="previewAvatarM(event)">
                        <button type="button" onclick="document.getElementById('avatar_file_m').click()" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-xl text-[10px] font-black transition-all border border-slate-200/50 dark:border-slate-700 shadow-sm flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            Pilih File Foto
                        </button>
                    </div>
                </div>
            </div>

            {{-- INPUT FIELDS --}}
            <div class="space-y-4 mb-6">
                <div>
                    <h3 class="text-xs font-extrabold text-gray-800 dark:text-white flex items-center gap-2 mb-3 border-b border-gray-100 dark:border-slate-800/80 pb-1.5">
                        <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400"></i> Identitas Kependudukan
                    </h3>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">NIK (No. Induk Kependudukan)</label>
                    <input type="text" name="nik" value="{{ $warga->nik ?? '' }}" maxlength="16" placeholder="NIK 16 digit" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Nomor Kartu Keluarga (No. KK)</label>
                    <input type="text" name="nomor_kk" value="{{ $warga->nomor_kk ?? '' }}" maxlength="16" placeholder="No. KK 16 digit" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div>
                    <h3 class="text-xs font-extrabold text-gray-800 dark:text-white flex items-center gap-2 mb-3 border-b border-gray-100 dark:border-slate-800/80 pb-1.5 pt-2">
                        <i class="fa-solid fa-address-book text-emerald-600 dark:text-emerald-400"></i> Kontak & Biodata
                    </h3>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_telepon" value="{{ $warga->no_telepon ?? '' }}" placeholder="0812xxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Usia / Umur</label>
                    <input type="number" name="umur" value="{{ $warga->umur ?? '' }}" min="1" max="120" placeholder="Contoh: 30" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                        <option value="Laki-laki" {{ ($warga->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ ($warga->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Agama</label>
                    <select name="agama" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm)
                            <option value="{{ $agm }}" {{ ($warga->agama ?? '') === $agm ? 'selected' : '' }}>{{ $agm }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Alamat / Blok Rumah</label>
                    <input type="text" name="blok_rumah" value="{{ $warga->blok_rumah ?? '' }}" placeholder="Blok B No. 5" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Status Hubungan Keluarga</label>
                    <select name="status_keluarga" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                        @foreach(['Kepala Keluarga', 'Istri', 'Anak', 'Orang Tua', 'Lainnya'] as $st)
                            <option value="{{ $st }}" {{ ($warga->status_keluarga ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <h3 class="text-xs font-extrabold text-gray-800 dark:text-white flex items-center gap-2 mb-3 border-b border-gray-100 dark:border-slate-800/80 pb-1.5 pt-2">
                        <i class="fa-solid fa-lock text-amber-500"></i> Keamanan Akun
                    </h3>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">Password Baru <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <div class="relative">
                        <input type="password" id="input-password-m" name="password" placeholder="Password baru..." class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/40 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:border-blue-500 transition-all">
                        <button type="button" onclick="togglePasswordM()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 focus:outline-none border-none bg-transparent">
                            <i class="fa-solid fa-eye text-xs" id="icon-mata-m"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" onclick="simpanProfilM(this)" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow-md transition hover:scale-[1.01] active:scale-95 cursor-pointer border-none flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Perubahan Data Diri
            </button>
        </form>
    </div>
</div>

<script>
    window.togglePasswordM = function() {
        let inputPass = document.getElementById('input-password-m');
        let iconMata = document.getElementById('icon-mata-m');
        if (inputPass.type === "password") {
            inputPass.type = "text";
            iconMata.className = "fa-solid fa-eye-slash text-xs";
        } else {
            inputPass.type = "password";
            iconMata.className = "fa-solid fa-eye text-xs";
        }
    };

    window.previewAvatarM = function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('avatar-preview-m');
            if (preview) {
                preview.src = reader.result;
            }
        }
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    };

    window.simpanProfilM = function(btn) {
        let form = document.getElementById('form-profil-baru-m');
        let formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Menyimpan Data Diri...';

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
