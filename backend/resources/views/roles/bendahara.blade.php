<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">
    <div class="w-64 bg-[#0e1b35] min-h-screen text-white p-6 shrink-0 flex flex-col">
        <div class="flex items-center mb-10">
            <span class="bg-blue-500 p-2 rounded mr-2">🏠</span>
            <h1 class="text-xl font-bold italic">SIPERWARA</h1>
        </div>
        <nav class="space-y-4 flex-1">
            <p class="text-xs text-gray-500 uppercase font-bold">Menu Utama</p>
            <a href="#" class="block p-3 bg-blue-600 rounded-lg font-bold italic">📊 Dashboard</a>
            <p class="text-xs text-gray-500 uppercase font-bold pt-4">Transaksi</p>
            <a href="#" class="block p-3 hover:bg-gray-800 rounded-lg text-gray-400">💵 Iuran Warga</a>
            <a href="#" class="block p-3 hover:bg-gray-800 rounded-lg text-gray-400">📥 Kas Masuk</a>
            <a href="#" class="block p-3 hover:bg-gray-800 rounded-lg text-gray-400">📤 Kas Keluar</a>
        </nav>

        <div class="border-t border-gray-700 pt-4 mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full p-3 text-red-400 hover:bg-red-900/20 rounded-lg transition">
                    <span class="mr-3">🚪</span> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat datang, {{ Auth::user()->name }} 👋</h2>
                <p class="text-gray-500 text-sm italic">Kelola keuangan RT dengan teliti dan transparan.</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-800">{{ date('d M Y') }}</p>
                <p class="text-xs text-gray-400 font-bold uppercase">Role: Bendahara</p>
            </div>
        </header>

        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-500">
                <p class="text-gray-400 text-xs font-bold uppercase">Pemasukan</p>
                <p class="text-2xl font-bold">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-red-500">
                <p class="text-gray-400 text-xs font-bold uppercase">Pengeluaran</p>
                <p class="text-2xl font-bold">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500">
                <p class="text-gray-400 text-xs font-bold uppercase">Saldo Kas</p>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-yellow-500">
                <p class="text-gray-400 text-xs font-bold uppercase">Tunggakan</p>
                <p class="text-2xl font-bold text-yellow-600 italic text-sm italic">Cek Laporan Iuran</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-700">Transaksi Terbaru</h3>
                <button class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-100 transition">Lihat Semua</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="text-gray-400 text-xs uppercase text-left border-b">
                        <th class="pb-3">Keterangan</th>
                        <th class="pb-3 text-center">Tanggal</th>
                        <th class="pb-3 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($transaksi as $t)
                    <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                        <td class="py-4 font-bold text-gray-700">{{ $t->keterangan }}</td>
                        <td class="py-4 text-center text-gray-500">{{ date('d M Y', strtotime($t->tanggal)) }}</td>
                        <td class="py-4 text-right font-bold {{ $t->type == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $t->type == 'masuk' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
