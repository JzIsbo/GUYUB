@php
    // Kumpulan Avatar Kartun Keren (Langsung jadi tanpa perlu download gambar)
    $avatars = [
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Felix',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Aneka',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Jack',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Jocelyn',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Liliana',
        'https://api.dicebear.com/8.x/bottts/svg?seed=Robot' // Tambahan 1 robot lucu
    ];
@endphp

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Pengaturan Akun</h2>
        <p class="text-gray-500 text-sm mt-1">Ubah identitas, password, dan pilih avatar Anda.</p>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <form id="form-profil-baru" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-8 flex flex-col sm:flex-row items-center gap-6">
                <!-- Current Profile Image Preview -->
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-50 flex items-center justify-center">
                        <img id="avatar-preview" src="{{ $user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Upload details -->
                <div class="flex-1 text-center sm:text-left">
                    <label class="block text-sm font-extrabold text-gray-700 mb-1">Foto Profil</label>
                    <p class="text-xs text-gray-400 mb-3">Mendukung file JPG, JPEG, PNG, GIF, atau SVG. Ukuran maksimal 2MB.</p>
                    
                    <div class="inline-block relative">
                        <input type="file" name="avatar_file" id="avatar_file" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                        <button type="button" onclick="document.getElementById('avatar_file').click()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold transition-all border border-gray-200/50 shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            Pilih File Foto
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Akun</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium" required>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Password <span class="text-gray-400 font-normal">(Biarkan jika tidak ingin mengubah)</span></label>
                <div class="relative">
                    <input type="password" id="input-password" name="password" placeholder="Masukkan password baru..." class="w-full px-5 py-3.5 pr-12 rounded-2xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">

                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-blue-600 transition-colors focus:outline-none">
                        <i class="fa-solid fa-eye" id="icon-mata"></i>
                    </button>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="simpanProfil(this)" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 px-8 rounded-2xl transition-all shadow-lg shadow-blue-500/30">
                    Simpan Perubahan
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

    // Gunakan fungsi klik langsung agar lebih stabil
    window.simpanProfil = function(btn) {
        let form = document.getElementById('form-profil-baru');
        let formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Menyimpan...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Profil berhasil diperbarui!');
            // Invalidasi page cache sebelum berpindah/refresh agar perubahan instan terlihat
            if (typeof window.invalidatePageCache === 'function') {
                window.invalidatePageCache('pengaturan');
                // Invalidasi dashboard juga agar avatarnya terupdate
                window.invalidatePageCache('dashboard');
            }
            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
        })
        .catch(err => {
            alert('Gagal menyimpan, silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = 'Simpan Perubahan';
        });
    };
</script>
