<div class="p-3 space-y-3">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-black text-gray-800">Akun Pengguna</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Hak akses sistem</p>
        </div>
        <button onclick="document.getElementById('modal-pengguna').classList.remove('hidden')" class="bg-blue-600 text-white px-3 py-2 rounded-xl font-bold text-[10px] flex items-center gap-1 shadow-md">
            <i class="fa-solid fa-user-plus text-xs"></i> Tambah
        </button>
    </div>

    {{-- ========== CARD LIST ========== --}}
    <div class="space-y-2">
        @forelse($list_pengguna ?? [] as $user)
            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[11px] font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-gray-800 text-[12px] truncate">{{ $user->name }}</p>
                            <p class="text-[9px] text-gray-400 truncate">{{ $user->email }}</p>
                            <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-bold border {{ $user->badge_class ?? 'bg-gray-50 text-gray-600 border-gray-100' }}">{{ $user->role }}</span>
                                <span class="inline-flex items-center gap-0.5 text-[8px] font-bold {{ ($user->is_aktif) ? 'text-emerald-600' : 'text-gray-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ ($user->is_aktif) ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                    {{ $user->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <button onclick="editPengguna({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}', '{{ $user->status ?? 'Aktif' }}')" class="w-7 h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-pen text-[9px]"></i></button>
                        @if($user->id !== Auth::id())
                        <button onclick="hapusPengguna({{ $user->id }}, '{{ addslashes($user->name) }}')" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash text-[9px]"></i></button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center text-gray-400 italic text-xs">
                Belum ada data pengguna.
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Registrasi -->
<div id="modal-pengguna" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-3 hidden">
    <div class="bg-white w-full max-w-[95vw] rounded-2xl p-5 relative overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-black text-gray-800">Registrasi Akun</h3>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-0.5">Tambah user baru</p>
            </div>
            <button onclick="document.getElementById('modal-pengguna').classList.add('hidden')" class="w-7 h-7 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="form-pengguna-lokal" onsubmit="simpanPengguna(event)">
            @csrf
            <div class="space-y-3 mb-4 text-xs">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Nama lengkap" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" required placeholder="email@domain.com" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Role</label>
                        <select name="role" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                            <option value="Super Admin">Super Admin</option>
                            <option value="RT">RT</option>
                            <option value="Bendahara">Bendahara</option>
                            <option value="Warga">Warga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" required class="w-full bg-gray-50 border py-2 px-3 rounded-xl text-sm font-bold text-gray-700">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#2563EB] text-white py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-1 shadow-lg">
                <i class="fa-solid fa-square-check text-xs"></i> Daftarkan Akun
            </button>
        </form>
    </div>
</div>

<script>
function simpanPengguna(event) {
    event.preventDefault();
    const form = document.getElementById('form-pengguna-lokal');
    const formData = new FormData(form);
    fetch("{{ route('user.store') }}", { method: "POST", body: formData, headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(res => { if (!res.ok) throw new Error('Server Error'); return res.json(); })
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('modal-pengguna').classList.add('hidden'); form.reset();
            alert('Berhasil! ' + data.message); switchPage('pengguna', document.querySelector('.menu-active'));
        } else { alert('Gagal: ' + data.message); }
    }).catch(err => alert('Terjadi kesalahan sistem.'));
}
function editPengguna(id, name, role, status) {
    const newRole = prompt('Ubah Role untuk "' + name + '"\n\nPilihan: Super Admin, RT, Bendahara, Warga\n\nRole saat ini: ' + role, role);
    if (!newRole || newRole === role) return;
    if (!['Super Admin', 'RT', 'Bendahara', 'Warga'].includes(newRole)) { alert('Role tidak valid.'); return; }
    const fd = new FormData(); fd.append('id', id); fd.append('role', newRole); fd.append('_token', window.csrfToken);
    fetch('{{ route("pengguna.update") }}', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { alert(d.message || 'Role diperbarui'); switchPage('pengguna', document.querySelector('.menu-active')); })
    .catch(() => alert('Gagal memperbarui role.'));
}
function hapusPengguna(id, name) {
    if (!confirm('Hapus akun "' + name + '"?')) return;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', window.csrfToken);
    fetch('{{ route("pengguna.delete") }}', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { alert(d.message || 'Akun dihapus'); switchPage('pengguna', document.querySelector('.menu-active')); })
    .catch(() => alert('Gagal menghapus akun.'));
}
</script>
