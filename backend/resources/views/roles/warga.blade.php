<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden font-sans">
    <div class="w-64 bg-[#0e1b35] text-white flex flex-col shrink-0 p-6">
        <h1 class="text-xl font-bold mb-10 italic uppercase tracking-tighter">🏠 SIPERWARA</h1>
        <nav class="space-y-4 flex-1">
            <a href="#" class="block p-3 bg-blue-600 rounded-xl font-bold">🏠 Beranda</a>
            <a href="#" class="block p-3 text-gray-400 hover:bg-gray-800 rounded-xl">💳 Iuran Saya</a>
            <a href="#" class="block p-3 text-gray-400 hover:bg-gray-800 rounded-xl">📄 Ajukan Surat</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-700 pt-4">
            @csrf
            <button class="text-red-400 w-full text-left p-3 hover:bg-red-900/20 rounded-xl font-bold">🚪 Keluar</button>
        </form>
    </div>

    <div class="flex-1 p-8 overflow-y-auto">
        <div class="bg-gradient-to-br from-indigo-600 to-blue-500 rounded-[40px] p-10 text-white mb-10 shadow-xl">
            <h2 class="text-3xl font-black mb-2 italic tracking-tight">Halo, {{ Auth::user()->name }} 👋</h2>
            <p class="opacity-80">Terakhir bayar iuran: <span class="font-bold underline">Mei 2026</span></p>
        </div>

        <div class="grid grid-cols-4 gap-6">
            <div class="bg-white p-8 rounded-[35px] text-center shadow-sm hover:shadow-md transition cursor-pointer">
                <span class="text-4xl block mb-4">💳</span>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Bayar Iuran</p>
            </div>
            <div class="bg-white p-8 rounded-[35px] text-center shadow-sm hover:shadow-md transition cursor-pointer border-2 border-blue-500">
                <span class="text-4xl block mb-4">📄</span>
                <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Minta Surat</p>
            </div>
            <div class="bg-white p-8 rounded-[35px] text-center shadow-sm hover:shadow-md transition cursor-pointer">
                <span class="text-4xl block mb-4">📋</span>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Riwayat Kas</p>
            </div>
            <div class="bg-white p-8 rounded-[35px] text-center shadow-sm hover:shadow-md transition cursor-pointer">
                <span class="text-4xl block mb-4">👤</span>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Profil</p>
            </div>
        </div>
    </div>
</body>
</html>
