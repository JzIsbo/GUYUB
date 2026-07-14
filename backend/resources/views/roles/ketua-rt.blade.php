<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <div class="w-64 bg-[#0e1b35] text-white flex flex-col shrink-0 p-6">
        <h1 class="text-xl font-bold mb-10 italic">🏠 GUYUB</h1>
        <nav class="space-y-4 flex-1">
            <a href="#" class="block p-3 bg-blue-600 rounded-xl font-bold">📊 Dashboard</a>
            <a href="#" class="block p-3 text-gray-400 hover:bg-gray-800 rounded-xl">🏘️ Data Warga</a>
            <a href="#" class="block p-3 text-gray-400 hover:bg-gray-800 rounded-xl">📝 Surat-surat</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-700 pt-4">
            @csrf
            <button class="text-red-400 w-full text-left p-3 hover:bg-red-900/20 rounded-xl font-bold">🚪 Logout</button>
        </form>
    </div>

    <div class="flex-1 p-8 overflow-y-auto">
        <h2 class="text-2xl font-bold mb-8 text-gray-800">Monitor Lingkungan RT 🔎</h2>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-blue-600 text-white p-8 rounded-[30px] shadow-lg">
                <p class="text-blue-100 text-xs font-bold uppercase">Saldo Kas Saat Ini</p>
                <h3 class="text-3xl font-black">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-8 rounded-[30px] shadow-sm border-b-4 border-yellow-500 text-center">
                <p class="text-gray-400 text-xs font-bold uppercase">Total Warga</p>
                <h3 class="text-3xl font-black text-gray-800">{{ $totalWarga }} KK</h3>
            </div>
            <div class="bg-white p-8 rounded-[30px] shadow-sm border-b-4 border-red-500 text-center">
                <p class="text-gray-400 text-xs font-bold uppercase">Permohonan Surat</p>
                <h3 class="text-3xl font-black text-red-500">3 Baru</h3>
            </div>
        </div>
    </div>
</body>
</html>
