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
            background-color: #F8FAFC;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode Override classes */
        html.dark, html.dark body { 
            background-color: #0B0F19 !important; 
            color: #F8FAFC !important; 
        }
        
        html.dark .bg-\[\#f5f8ff\] { 
            background-color: #0B0F19 !important; 
        }

        html.dark .login-card { 
            background: rgba(30, 41, 59, 0.75) !important; 
            border-color: rgba(255, 255, 255, 0.08) !important; 
            color: #F8FAFC !important; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
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
            background-color: rgba(15, 23, 42, 0.6) !important; 
            color: #F8FAFC !important; 
            border-color: rgba(255, 255, 255, 0.12) !important; 
        }

        html.dark .input-custom:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.2) !important;
        }

        html.dark .login-icon-wrapper {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9)) !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05), 0 10px 20px rgba(0,0,0,0.2) !important;
        }

        html.dark .login-icon {
            color: #60a5fa !important;
        }

        html.dark .btn-login {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            box-shadow: 0 8px 20px -5px rgba(37, 99, 235, 0.4) !important;
        }

        html.dark .btn-login:hover {
            box-shadow: 0 12px 24px -5px rgba(37, 99, 235, 0.6) !important;
        }

        /* Layout styles */
        .left-section {
            background:
                linear-gradient(
                    to bottom,
                    rgba(15, 23, 42, 0.92),
                    rgba(30, 41, 59, 0.8)
                ),
                url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .curve-left {
            border-top-right-radius: 64px;
            border-bottom-right-radius: 64px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 28px;
            padding: 24px 32px;
            box-shadow: 
                0 20px 40px -15px rgba(15, 23, 42, 0.05),
                0 15px 25px -5px rgba(37, 99, 235, 0.03);
            transition: all 0.3s ease;
        }

        .login-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 8px 16px rgba(37, 99, 235, 0.05);
        }

        .login-icon {
            font-size: 36px;
        }

        .login-title {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 13px;
            line-height: 20px;
            margin-top: 6px;
        }

        .login-label {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .input-custom {
            height: 46px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            background-color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .input-custom::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .input-icon {
            font-size: 13px;
        }

        .btn-login {
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            font-size: 14px;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 6px 16px -4px rgba(37, 99, 235, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px -4px rgba(37, 99, 235, 0.4);
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

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .form-spacing {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        /* Screen-height adaptive sizing */
        @media (max-height: 700px) {
            .login-card {
                padding: 16px 24px;
            }
            .form-spacing {
                margin-top: 14px;
            }
            .form-group {
                margin-bottom: 10px;
            }
            .login-title {
                font-size: 22px;
            }
        }

        @media (max-width: 1280px) {
            .left-section {
                display: none;
            }
        }
                border-radius: 28px;
            }

            .login-title {
                font-size: 28px;
            }

            .login-subtitle {
                font-size: 13px;
                line-height: 24px;
            }

            .login-icon-wrapper {
                width: 78px;
                height: 78px;
            }

            .login-icon {
                font-size: 34px;
            }

            .input-custom {
                height: 50px;
                border-radius: 16px;
                font-size: 13px;
            }

            .btn-login {
                height: 50px;
                border-radius: 16px;
                font-size: 15px;
            }
        }

        @media (max-height: 720px) and (min-width: 768px) {
            .login-card {
                padding: 30px 36px;
            }

            .login-icon-wrapper {
                width: 78px;
                height: 78px;
            }

            .login-icon {
                font-size: 34px;
            }

            .login-title {
                font-size: 30px;
            }

            .form-spacing {
                margin-top: 24px;
            }

            .form-group {
                margin-bottom: 16px;
            }
        }
    </style>
</head>

<body>

<div class="flex min-h-screen w-full">

    {{-- LEFT --}}
    <div class="hidden xl:flex w-[36%] left-section curve-left relative overflow-hidden">

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
    <div class="relative flex min-h-screen w-full items-center justify-center overflow-hidden bg-[#f5f8ff] px-5 py-6 xl:w-[64%]">

        <!-- Decorative blurred gradient blobs for rich modern glassmorphism -->
        <div class="absolute -left-20 -top-20 h-[380px] w-[380px] rounded-full bg-gradient-to-tr from-blue-300/40 to-indigo-300/40 blur-3xl dark:from-blue-900/30 dark:to-indigo-900/30 opacity-70"></div>
        <div class="absolute -bottom-20 -right-20 h-[380px] w-[380px] rounded-full bg-gradient-to-tr from-purple-300/30 to-blue-300/30 blur-3xl dark:from-purple-900/20 dark:to-blue-900/20 opacity-70"></div>
        <div class="absolute left-[20%] top-[30%] hidden h-32 w-32 rounded-full bg-blue-400/20 blur-3xl dark:bg-blue-800/10 md:block"></div>

        {{-- LOGIN CARD --}}
        <div class="relative z-10 flex w-full justify-center">

            <div class="login-card login-shadow relative overflow-hidden">

                {{-- TOP UTILITY BAR (Integrated cleanly inside the card to prevent overlap) --}}
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800/60 pb-3 mb-4">
                    <a href="{{ route('welcome') }}" class="text-[11.5px] font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                    <button onclick="toggleTheme()" id="theme-toggle-btn" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500 dark:text-amber-400 hover:text-amber-500 transition cursor-pointer" title="Ubah Tema">
                        <i class="fa-solid fa-moon text-xs" id="theme-toggle-icon"></i>
                    </button>
                </div>

                {{-- DOTS --}}
                <div class="absolute right-6 top-[72px] grid grid-cols-5 gap-1 opacity-20 dark:opacity-10">
                    @for($i = 0; $i < 25; $i++)
                        <div class="h-[3px] w-[3px] rounded-full bg-blue-500"></div>
                    @endfor
                </div>

                {{-- HEADING & COMPACT ICON --}}
                <div class="text-center">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-xl mb-2.5 shadow-inner">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <h1 class="login-title text-[#132b63]">
                        Selamat Datang!
                    </h1>
                    <p class="login-subtitle text-[#66708c]">
                        Silakan masuk untuk melanjutkan ke GUYUB.
                    </p>
                </div>

                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-red-100 bg-red-50 dark:bg-red-950/20 dark:border-red-900/30 px-3.5 py-2.5 text-xs font-medium text-red-600 dark:text-red-400">
                        <div class="flex gap-2.5">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <div>
                                Email, nomor WhatsApp, atau kata sandi yang Anda masukkan belum sesuai.
                            </div>
                        </div>
                    </div>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('login') }}" class="form-spacing">
                    @csrf

                    {{-- EMAIL --}}
                    <div class="form-group">
                        <label for="email" class="login-label text-[#1d2942]">
                            Email atau Nomor WhatsApp
                        </label>
                        <div class="relative">
                            <i class="fa-regular fa-user input-icon absolute left-4.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
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
                            <p class="mt-1.5 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group">
                        <label for="password" class="login-label text-[#1d2942]">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-lock input-icon absolute left-4.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
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
                                class="absolute right-4.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 transition hover:text-blue-600"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <i class="fa-regular fa-eye text-xs" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- FORGOT --}}
                    <div class="flex justify-end mt-1">
                        <a href="/welcome#kontak" class="text-[12px] font-semibold text-blue-600 dark:text-blue-400 transition hover:text-blue-750 dark:hover:text-blue-300 hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <button type="submit" class="btn-login mt-4.5 w-full font-bold text-white flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        Masuk
                    </button>

                    {{-- INFO REGISTER --}}
                    <div class="mt-3.5 text-center">
                        <p class="text-[12px] text-gray-500 dark:text-slate-400">
                            Belum punya akun?
                            <a href="/welcome#kontak" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline transition">
                                Hubungi Pengurus RT
                            </a>
                        </p>
                    </div>
                </form>

                {{-- QUICK LOGIN SHORTCUTS --}}
                <div class="mt-4 border-t border-gray-100 dark:border-slate-800/80 pt-4">
                    <p class="text-center text-[9.5px] font-extrabold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">
                        Akses Cepat Login (Demo)
                    </p>
                    <div class="grid grid-cols-2 gap-1.5">
                        <button type="button" onclick="quickLogin('superadmin@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-2 bg-red-50 hover:bg-red-100/80 dark:bg-red-950/20 dark:hover:bg-red-950/40 border border-red-100/50 dark:border-red-900/30 text-red-600 dark:text-red-400 rounded-xl text-[11px] font-bold transition-all active:scale-95 cursor-pointer">
                            👑 <span class="hidden sm:inline">Super</span> Admin
                        </button>
                        <button type="button" onclick="quickLogin('rt@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-2 bg-blue-50 hover:bg-blue-100/80 dark:bg-blue-950/20 dark:hover:bg-blue-950/40 border border-blue-100/50 dark:border-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl text-[11px] font-bold transition-all active:scale-95 cursor-pointer">
                            👥 Ketua RT
                        </button>
                        <button type="button" onclick="quickLogin('bendahara@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-2 bg-emerald-50 hover:bg-emerald-100/80 dark:bg-emerald-950/20 dark:hover:bg-emerald-950/40 border border-emerald-100/50 dark:border-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl text-[11px] font-bold transition-all active:scale-95 cursor-pointer">
                            💵 Bendahara
                        </button>
                        <button type="button" onclick="quickLogin('warga@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/40 dark:hover:bg-slate-800/60 border border-slate-200/50 dark:border-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl text-[11px] font-bold transition-all active:scale-95 cursor-pointer">
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
