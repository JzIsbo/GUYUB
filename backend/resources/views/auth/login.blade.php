{{-- resources/views/auth/login.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GUYUB</title>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            overflow: hidden;
            background-color: #F1F5F9;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode */
        html.dark, html.dark body { 
            background-color: #0B0F19 !important; 
            color: #F8FAFC !important; 
        }

        html.dark .bg-\[\#f5f8ff\] { 
            background-color: #0B0F19 !important; 
        }

        html.dark .login-card { 
            background: rgba(30, 41, 59, 0.45) !important; 
            border-color: rgba(255, 255, 255, 0.06) !important; 
            color: #F8FAFC !important; 
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.5) !important;
        }

        html.dark .login-title { 
            color: #F8FAFC !important; 
        }

        html.dark .login-subtitle { 
            color: #94A3B8 !important; 
        }

        html.dark .login-label { 
            color: #E2E8F0 !important; 
        }

        html.dark .input-custom { 
            background-color: rgba(15, 23, 42, 0.5) !important; 
            color: #F8FAFC !important; 
            border-color: rgba(255, 255, 255, 0.08) !important; 
        }

        html.dark .input-custom:focus {
            background-color: rgba(15, 23, 42, 0.7) !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2) !important;
        }

        html.dark .btn-login {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            box-shadow: 0 8px 20px -5px rgba(37, 99, 235, 0.4) !important;
        }

        /* Layout styles */
        .left-section {
            background:
                linear-gradient(
                    to bottom,
                    rgba(15, 23, 42, 0.94),
                    rgba(30, 41, 59, 0.8)
                ),
                url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            padding: 20px 26px;
            box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-title {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #0F172A;
        }

        .login-subtitle {
            font-size: 13px;
            color: #475569;
            margin-top: 4px;
        }

        .login-label {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
        }

        .input-custom {
            height: 44px;
            border-radius: 12px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            background-color: rgba(241, 245, 249, 0.5);
            font-size: 13px;
            font-weight: 500;
            color: #0F172A;
            transition: all 0.2s ease;
        }

        .input-custom::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-custom:focus {
            background-color: #FFFFFF;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .input-icon {
            font-size: 13px;
        }

        .btn-login {
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            font-size: 14px;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .left-title {
            font-size: 40px;
            font-weight: 900;
            letter-spacing: -1px;
            line-height: 1;
        }

        .left-desc {
            font-size: 14.5px;
            line-height: 26px;
        }

        .security-title {
            font-size: 16px;
            font-weight: 800;
        }

        .security-desc {
            font-size: 12.5px;
            line-height: 20px;
        }

        .form-spacing {
            margin-top: 16px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        @keyframes pulseBeat {
            0%, 100% {
                transform: scale(1);
            }
            15% {
                transform: scale(1.12);
            }
            30% {
                transform: scale(1);
            }
            45% {
                transform: scale(1.15);
            }
        }
        .animated-welcome-icon {
            animation: pulseBeat 2s ease-in-out infinite;
        }

        /* Responsive */
        @media (max-width: 1280px) {
            .left-section {
                display: none;
            }
        }

        @media (max-width: 640px) {
            body {
                overflow: hidden !important;
                height: 100vh !important;
            }
            .right-section {
                min-height: 100vh !important;
                height: 100vh !important;
                overflow: hidden !important;
                padding: 12px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .login-card {
                width: 94% !important;
                max-width: 345px !important;
                padding: 16px 18px !important;
                border-radius: 20px !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            }
            .animated-welcome-icon {
                display: inline-flex !important;
                height: 38px !important;
                width: 38px !important;
                border-radius: 10px !important;
                font-size: 16px !important;
                margin-bottom: 8px !important;
            }
            .login-title {
                font-size: 20px !important;
            }
            .login-subtitle {
                font-size: 11.5px !important;
                margin-top: 2px !important;
            }
            .form-spacing {
                margin-top: 12px !important;
            }
            .form-group {
                margin-bottom: 10px !important;
            }
            .login-label {
                font-size: 10.5px !important;
                margin-bottom: 3px !important;
            }
            .input-custom {
                height: 40px !important;
                border-radius: 10px !important;
                font-size: 12px !important;
            }
            .btn-login {
                height: 40px !important;
                border-radius: 10px !important;
                font-size: 13.5px !important;
                margin-top: 10px !important;
            }
            .form-spacing .mt-3 {
                margin-top: 8px !important;
            }
            .form-spacing p {
                font-size: 11px !important;
            }
            .border-t {
                margin-top: 10px !important;
                padding-top: 8px !important;
            }
            .border-t p {
                margin-bottom: 6px !important;
            }
            /* Change demo grid to 4 columns on mobile */
            .grid-cols-2 {
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                gap: 4px !important;
            }
            .grid-cols-2 button {
                font-size: 8.5px !important;
                padding: 5px 2px !important;
                border-radius: 8px !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
        }

        @media (max-height: 720px) and (min-width: 768px) {
            .login-card {
                padding: 20px 26px;
            }

            .login-title {
                font-size: 24px;
            }

            .form-spacing {
                margin-top: 16px;
            }

            .form-group {
                margin-bottom: 12px;
            }
        }
    </style>
</head>

<body>

<div class="flex min-h-screen w-full">

    {{-- LEFT --}}
    <div class="hidden xl:flex w-[36%] left-section relative overflow-hidden border-r border-white/5">

        <div class="absolute inset-0 bg-gradient-to-b from-blue-950/40 to-blue-800/20"></div>

        <div class="absolute -top-32 -right-32 h-[350px] w-[350px] rounded-full bg-blue-400/20"></div>

        <div class="relative z-10 flex h-full flex-col justify-between px-10 py-12 text-white">

            <div>
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500/20 to-indigo-600/30 border border-white/20 shadow-lg text-white text-3xl">
                    <i class="fa-solid fa-house-chimney-window text-blue-300"></i>
                </div>

                <h1 class="left-title mt-8 font-black uppercase tracking-tighter">
                    GU<span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">YUB</span>
                </h1>

                <p class="left-desc mt-4 max-w-[300px] text-blue-100/90 leading-relaxed font-medium">
                    Solusi digital untuk lingkungan yang lebih terhubung, transparan, dan harmonis.
                </p>
            </div>

            <div class="w-[310px] rounded-[2rem] border border-white/10 bg-white/5 p-5 backdrop-blur-md shadow-2xl hover:bg-white/10 transition-all duration-300">
                <div class="flex gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-blue-500/20">
                        <i class="fa-solid fa-shield-halved text-lg text-blue-300"></i>
                    </div>

                    <div>
                        <h3 class="security-title text-white">
                            Keamanan Terjamin
                        </h3>

                        <p class="security-desc mt-2 text-blue-100/80">
                            Data warga aman bersama kami dengan sistem terenkripsi.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="right-section relative flex min-h-screen w-full items-center justify-center overflow-y-auto bg-[#F1F5F9] dark:bg-[#0B0F19] px-4 py-6 xl:w-[64%]">

        <!-- Top navigation links (theme toggle) -->
        <button onclick="toggleTheme()" id="theme-toggle-btn" class="absolute right-6 sm:right-8 top-6 sm:top-8 z-50 text-slate-500 hover:text-amber-500 dark:text-slate-400 dark:hover:text-amber-400 transition flex items-center justify-center w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer" title="Ubah Tema">
            <i class="fa-solid fa-moon text-sm" id="theme-toggle-icon"></i>
        </button>

        <!-- Decorative blurred gradient blobs for rich modern glassmorphism -->
        <div class="absolute -left-20 -top-20 h-[380px] w-[380px] rounded-full bg-gradient-to-tr from-blue-300/40 to-indigo-300/40 blur-3xl dark:from-blue-900/30 dark:to-indigo-900/30 opacity-70 pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 h-[380px] w-[380px] rounded-full bg-gradient-to-tr from-purple-300/30 to-blue-300/30 blur-3xl dark:from-purple-900/20 dark:to-blue-900/20 opacity-70 pointer-events-none"></div>
        <div class="absolute left-[20%] top-[30%] hidden h-32 w-32 rounded-full bg-blue-400/20 blur-3xl dark:bg-blue-800/10 md:block pointer-events-none"></div>

        {{-- LOGIN CARD --}}
        <div class="relative z-10 flex w-full justify-center">

            <div class="login-card login-shadow relative overflow-hidden">

                {{-- HEADING & ANIMATED WELCOME ICON --}}
                <div class="text-center mt-0.5 flex flex-col items-center">
                    {{-- TOMBOL KEMBALI KE HALAMAN PUBLIK (DI ATAS ICON SELAMAT DATANG) --}}
                    <a href="{{ route('welcome') }}" class="mb-3 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-100/90 dark:bg-slate-800/80 hover:bg-blue-50 dark:hover:bg-blue-950/40 border border-slate-200/80 dark:border-white/10 text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 font-bold text-[11px] shadow-sm hover:shadow-md transition-all group cursor-pointer">
                        <i class="fa-solid fa-arrow-left text-blue-600 dark:text-blue-400 group-hover:-translate-x-1 transition-transform text-[11px]"></i>
                        <span>Kembali ke Halaman Publik</span>
                    </a>

                    {{-- ICON SELAMAT DATANG BERGERAK (ANIMATED FLOATING ICON) --}}
                    <div class="animated-welcome-icon inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-100 to-indigo-50 dark:from-blue-950/60 dark:to-indigo-900/40 text-blue-600 dark:text-blue-400 text-xl mb-2.5 shadow-md border border-blue-200/60 dark:border-blue-800/40">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>

                    <h1 class="login-title text-[#132b63]">
                        Selamat Datang!
                    </h1>
                    <p class="login-subtitle text-[#66708c]">
                        Silakan masuk untuk melanjutkan ke GUYUB.
                    </p>
                </div>

                {{-- SUCCESS MESSAGE --}}
                @if (session('success_message'))
                    <div class="mt-4 rounded-xl border border-emerald-100 bg-emerald-50 dark:bg-emerald-950/20 dark:border-emerald-900/30 px-3.5 py-2.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                        <div class="flex gap-2.5">
                            <i class="fa-solid fa-circle-check mt-0.5"></i>
                            <div>
                                {{ session('success_message') }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-red-100 bg-red-50 dark:bg-red-950/20 dark:border-red-900/30 px-3.5 py-2.5 text-xs font-medium text-red-600 dark:text-red-400">
                        <div class="flex gap-2.5">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <div>
                                @if ($errors->has('email'))
                                    {{ $errors->first('email') }}
                                @else
                                    Email, nomor WhatsApp, atau kata sandi yang Anda masukkan belum sesuai.
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('login') }}" class="form-spacing">
                    @csrf

                    {{-- EMAIL --}}
                    <div class="form-group">
                        <label for="email" class="login-label">
                            Email atau Nomor WhatsApp
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-gray-400 dark:text-gray-500 pointer-events-none text-sm">
                                <i class="fa-regular fa-user"></i>
                            </span>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email atau nomor WhatsApp"
                                class="input-custom w-full pl-12 pr-4 focus:outline-none"
                                autocomplete="username"
                                autofocus
                                required
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group">
                        <label for="password" class="login-label">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-gray-400 dark:text-gray-500 pointer-events-none text-sm">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan kata sandi"
                                class="input-custom w-full pl-12 pr-12 focus:outline-none"
                                autocomplete="current-password"
                                required
                            >
                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-gray-400 dark:text-gray-500 transition hover:text-blue-600 cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <i class="fa-regular fa-eye text-sm" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- FORGOT --}}
                    <div class="flex justify-end mt-1">
                        <a href="/welcome#kontak" class="text-[12px] font-semibold text-blue-600 dark:text-blue-400 transition hover:text-blue-700 dark:hover:text-blue-300 hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <button type="submit" class="btn-login mt-4 w-full font-bold text-white flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        Masuk
                    </button>

                    {{-- INFO REGISTER --}}
                    <div class="mt-3 text-center">
                        <p class="text-[12px] text-gray-500 dark:text-slate-400">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline transition">
                                Daftar Akun Baru
                            </a>
                        </p>
                    </div>
                </form>

                {{-- QUICK LOGIN SHORTCUTS --}}
                <div class="mt-3 border-t border-gray-150 dark:border-slate-800/80 pt-3">
                    <p class="text-center text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">
                        Akses Cepat Login (Demo)
                    </p>
                    <div class="grid grid-cols-2 gap-1 text-[9.5px]">
                        <button type="button" onclick="quickLogin('superadmin@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-red-50 hover:bg-red-100 border border-red-100 text-red-600 rounded-xl font-bold transition-all">
                            👑 Super Admin
                        </button>
                        <button type="button" onclick="quickLogin('rw@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 text-indigo-600 rounded-xl font-bold transition-all">
                            🏛️ Ketua RW
                        </button>
                        <button type="button" onclick="quickLogin('sekretarisrw@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-violet-50 hover:bg-violet-100 border border-violet-100 text-violet-600 rounded-xl font-bold transition-all">
                            📑 Sekr. RW
                        </button>
                        <button type="button" onclick="quickLogin('bendahararw@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-cyan-50 hover:bg-cyan-100 border border-cyan-100 text-cyan-600 rounded-xl font-bold transition-all">
                            💳 Bend. RW
                        </button>
                        <button type="button" onclick="quickLogin('rt@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-blue-50 hover:bg-blue-100 border border-blue-100 text-blue-600 rounded-xl font-bold transition-all">
                            👥 Ketua RT
                        </button>
                        <button type="button" onclick="quickLogin('sekretaris@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-purple-50 hover:bg-purple-100 border border-purple-100 text-purple-600 rounded-xl font-bold transition-all">
                            📝 Sekr. RT
                        </button>
                        <button type="button" onclick="quickLogin('bendahara@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-600 rounded-xl font-bold transition-all">
                            💵 Bend. RT
                        </button>
                        <button type="button" onclick="quickLogin('warga@gmail.com', 'password')" class="flex items-center justify-center gap-1 py-1 px-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl font-bold transition-all">
                            🏡 Warga
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (!password || !eyeIcon) {
            return;
        }

        if (password.type === 'password') {
            password.type = 'text';

            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';

            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    function quickLogin(email, password) {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        if (emailInput && passwordInput) {
            emailInput.value = email;
            passwordInput.value = password;
            
            // Trigger submit after briefly showing the fill effect
            setTimeout(() => {
                emailInput.closest('form').submit();
            }, 100);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        applyTheme(isDark);
    });

    function toggleTheme() {
        const isCurrentDark = document.documentElement.classList.contains('dark');
        const newDarkState = !isCurrentDark;
        applyTheme(newDarkState);
        localStorage.setItem('theme', newDarkState ? 'dark' : 'light');
    }

    function applyTheme(isDark) {
        const icon = document.getElementById('theme-toggle-icon');
        const btn = document.getElementById('theme-toggle-btn');
        if (isDark) {
            document.documentElement.classList.add('dark');
            if (icon) icon.className = 'fa-solid fa-sun text-sm text-amber-400';
            if (btn) btn.title = 'Ubah ke Mode Terang';
        } else {
            document.documentElement.classList.remove('dark');
            if (icon) icon.className = 'fa-solid fa-moon text-sm text-slate-500';
            if (btn) btn.title = 'Ubah ke Mode Gelap';
        }
    }
</script>

</body>
</html>
