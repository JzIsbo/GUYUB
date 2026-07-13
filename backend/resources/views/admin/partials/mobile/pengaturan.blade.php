@php
    $avatars = [
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Felix',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Aneka',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Jack',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Jocelyn',
        'https://api.dicebear.com/8.x/adventurer/svg?seed=Liliana',
        'https://api.dicebear.com/8.x/bottts/svg?seed=Robot'
    ];
@endphp

<div class="p-3">
    <h2 class="text-sm font-bold text-gray-800 mb-1">Pengaturan Akun</h2>
    <p class="text-[10px] text-gray-500 mb-3">Ubah identitas, password, dan avatar.</p>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form id="form-profil-baru" action="{{ route('settings.update') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-700 mb-2">Pilih Avatar</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($avatars as $avatar)
                    <label class="cursor-pointer relative">
                        <input type="radio" name="avatar" value="{{ $avatar }}" class="peer hidden" {{ $user->photo == $avatar ? 'checked' : '' }}>
                        <div class="w-10 h-10 rounded-full border-3 border-transparent peer-checked:border-blue-600 peer-checked:scale-110 transition-all bg-gray-50 overflow-hidden shadow-sm">
                            <img src="{{ $avatar }}" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 bg-blue-600 text-white rounded-full w-4 h-4 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                            <i class="fa-solid fa-check text-[6px]"></i>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="space-y-3 mb-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-700 mb-1">Nama Akun</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm font-medium" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm font-medium" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-700 mb-1">Password Baru <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <div class="relative">
                        <input type="password" id="input-password" name="password" placeholder="Password baru..." class="w-full px-3 py-2 pr-10 rounded-xl border border-gray-200 text-sm font-medium">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <i class="fa-solid fa-eye text-xs" id="icon-mata"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" onclick="simpanProfil(this)" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-xl text-xs">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    window.togglePassword = function() {
        let inputPass = document.getElementById('input-password');
        let iconMata = document.getElementById('icon-mata');
        if (inputPass.type === "password") { inputPass.type = "text"; iconMata.className = "fa-solid fa-eye-slash text-xs"; }
        else { inputPass.type = "password"; iconMata.className = "fa-solid fa-eye text-xs"; }
    };

    window.simpanProfil = function(btn) {
        let form = document.getElementById('form-profil-baru');
        let formData = new FormData(form);
        let selected = document.querySelector('input[name="avatar"]:checked');
        if (selected) formData.set('avatar', selected.value);
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...';
        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(res => res.json())
        .then(data => { alert(data.message || 'Profil diperbarui!'); window.location.href = window.location.pathname + '?t=' + new Date().getTime(); })
        .catch(err => { alert('Gagal menyimpan.'); btn.disabled = false; btn.innerHTML = 'Simpan Perubahan'; });
    };
</script>
