@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Nama Pengguna</th>
            <th>Email</th>
            <th>Hak Akses / Role</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><span class="badge">{{ $user->role }}</span></td>
            <td><span class="text-success">● Aktif</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

<form action="{{ route('pengguna.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Nama" required>
    <input type="email" name="email" placeholder="Email" required>
    <select name="role">
        <option value="Super Admin">Super Admin</option>
        <option value="Admin">Admin</option>
        <option value="Bendahara">Bendahara</option>
        <option value="Warga">Warga</option>
    </select>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Tambah Pengguna</button>
</form>
