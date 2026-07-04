<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Manajemen Akun Pengguna</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Mengatur hak akses dan kredensial sistem</p>
        </div>
        <button onclick="document.getElementById('modal-pengguna').classList.remove('hidden')" class="bg-blue-600 text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-blue-900/10 hover:bg-blue-700 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus text-base"></i> Tambah Pengguna Baru
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Pengguna</th>
                        <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hak Akses / Role</th>
                        <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="p-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($list_pengguna ?? [] as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-6 text-sm font-bold text-gray-800">{{ $user->name }}</td>
                        <td class="p-6 text-sm font-medium text-gray-500">{{ $user->email }}</td>
                        <td class="p-6">
                            <span class="px-3 py-1.5 rounded-xl text-xs font-bold border {{ $user->badge_class ?? 'bg-gray-50 text-gray-600 border-gray-100' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-6">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ ($user->is_aktif) ? 'text-emerald-600' : 'text-gray-400' }}">
                                <span class="w-2 h-2 rounded-full {{ ($user->is_aktif) ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                {{ $user->status }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editPengguna({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}', '{{ $user->status ?? 'Aktif' }}')" class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer" title="Edit Role">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                @if($user->id !== Auth::id())
                                <button onclick="hapusPengguna({{ $user->id }}, '{{ addslashes($user->name) }}')" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition inline-flex items-center justify-center cursor-pointer" title="Hapus Akun">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-sm font-medium text-gray-400">Belum ada data akun pengguna terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-pengguna" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl border border-gray-50 p-8 transform transition-all relative overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Registrasi Akun</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Tambah hak akses pengurus/warga</p>
                </div>
                <button onclick="document.getElementById('modal-pengguna').classList.add('hidden')" class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="form-pengguna-lokal" onsubmit="simpanPengguna(event)">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Masukkan nama lengkap" class="w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Alamat Email</label>
                        <input type="email" name="email" required placeholder="contoh@email.com" class="w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Password Akun</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Hak Akses (Role)</label>
                            <select name="role" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="Super Admin">Super Admin</option>
                                <option value="RT">RT</option> <option value="Bendahara">Bendahara</option>
                                <option value="Warga">Warga</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Status Akun</label>
                            <select name="status" required class="w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#2563EB] text-white px-6 py-4 rounded-2xl font-bold shadow-lg hover:bg-blue-700 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-square-check"></i> Daftarkan Akun Pengguna
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function simpanPengguna(event) {
    event.preventDefault();
    const form = document.getElementById('form-pengguna-lokal');
    const formData = new FormData(form);

    fetch("{{ route('user.store') }}", {
        method: "POST",
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => {
        if (!res.ok) throw new Error('Server Error');
        return res.json();
    })
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('modal-pengguna').classList.add('hidden');
            form.reset();
            alert('Berhasil! ' + data.message);
            switchPage('pengguna', document.querySelector('.menu-active'));
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan sistem saat menghubungi server.');
    });
}

function editPengguna(id, name, role, status) {
    const newRole = prompt('Ubah Role untuk "' + name + '"\n\nPilihan: Super Admin, RT, Bendahara, Warga\n\nRole saat ini: ' + role, role);
    if (!newRole || newRole === role) return;
    if (!['Super Admin', 'RT', 'Bendahara', 'Warga'].includes(newRole)) {
        alert('Role tidak valid. Pilih: Super Admin, RT, Bendahara, atau Warga');
        return;
    }
    const fd = new FormData();
    fd.append('id', id);
    fd.append('role', newRole);
    fd.append('_token', window.csrfToken);
    fetch('{{ route("pengguna.update") }}', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => { alert(d.message || 'Role diperbarui'); switchPage('pengguna', document.querySelector('.menu-active')); })
    .catch(() => alert('Gagal memperbarui role.'));
}

function hapusPengguna(id, name) {
    if (!confirm('Hapus akun "' + name + '"? Akun yang dihapus tidak bisa dikembalikan.')) return;
    const fd = new FormData();
    fd.append('id', id);
    fd.append('_token', window.csrfToken);
    fetch('{{ route("pengguna.delete") }}', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => { alert(d.message || 'Akun dihapus'); switchPage('pengguna', document.querySelector('.menu-active')); })
    .catch(() => alert('Gagal menghapus akun.'));
}
</script>
