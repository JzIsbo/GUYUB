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
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            background: #eef3ff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode for Login */
        html.dark, html.dark body { background: #0F172A !important; color: #F8FAFC !important; }
        html.dark .bg-\[\#f5f8ff\] { background-color: #0F172A !important; }
        html.dark .login-card { background: rgba(30, 41, 59, 0.95) !important; border-color: rgba(255, 255, 255, 0.1) !important; color: #F8FAFC !important; }
        html.dark .login-title { color: #F8FAFC !important; }
        html.dark .login-subtitle { color: #94A3B8 !important; }
        html.dark .login-label { color: #E2E8F0 !important; }
        html.dark .input-custom { background: #0F172A !important; color: #F8FAFC !important; border-color: rgba(255, 255, 255, 0.15) !important; }

        .left-section {
            background:
                linear-gradient(
                    to bottom,
                    rgba(6, 31, 95, .94),
                    rgba(18, 71, 176, .80)
                ),
                url('https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?q=80&w=1974&auto=format&fit=crop');

            background-size: cover;
            background-position: center;
        }

        .curve-left {
            border-top-right-radius: 96px;
            border-bottom-right-radius: 96px;
        }

        .login-card {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, .94);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .65);
            border-radius: 34px;
            padding: 44px;
        }

        .login-shadow {
            box-shadow:
                0 24px 70px rgba(15, 23, 42, .10),
                0 10px 26px rgba(37, 99, 235, .08);
        }

        .login-icon-wrapper {
            width: 96px;
            height: 96px;
            border-radius: 999px;
            background: linear-gradient(180deg, #eff6ff, #dbeafe);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .85);
        }

        .login-icon {
            font-size: 44px;
        }

        .login-title {
            font-size: 36px;
            line-height: 1.12;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .login-subtitle {
            max-width: 340px;
            margin: 14px auto 0;
            font-size: 15px;
            line-height: 28px;
        }

        .login-label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 700;
        }

        .input-custom {
            height: 56px;
            border-radius: 18px;
            border: 1px solid #dbe3f1;
            background: #ffffff;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            transition: .25s ease;
        }

        .input-custom::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 5px rgba(59, 130, 246, .12);
            outline: none;
        }

        .input-icon {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .btn-login {
            height: 56px;
            border-radius: 18px;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            font-size: 17px;
            transition: .25s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 34px rgba(37, 99, 235, .28);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(37, 99, 235, .22);
        }

        .left-title {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .left-desc {
            font-size: 15px;
            line-height: 32px;
        }

        .security-title {
            font-size: 18px;
            font-weight: 700;
        }

        .security-desc {
            font-size: 13px;
            line-height: 24px;
        }

        .floating {
            animation: floating 5s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .form-spacing {
            margin-top: 34px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        @media (max-width: 1366px) {
            .login-card {
                max-width: 460px;
                padding: 38px;
            }

            .login-icon-wrapper {
                width: 88px;
                height: 88px;
            }

            .login-icon {
                font-size: 40px;
            }

            .login-title {
                font-size: 32px;
            }

            .login-subtitle {
                font-size: 14px;
                line-height: 25px;
            }

            .input-custom {
                height: 52px;
            }

            .btn-login {
                height: 52px;
                font-size: 16px;
            }

            .form-spacing {
                margin-top: 30px;
            }

            .form-group {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 1280px) {
            .left-section {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .login-card {
                max-width: 100%;
                padding: 30px 22px;
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
                <div class="text-7xl leading-none">
                    🏠
                </div>

                <h1 class="left-title mt-6">
                    GU<span class="text-blue-400">YUB</span>
                </h1>

                <p class="left-desc mt-5 max-w-[300px] text-blue-100">
                    Solusi digital untuk lingkungan yang lebih terhubung dan harmonis.
                </p>
            </div>

            <div class="w-[300px] rounded-[24px] border border-white/10 bg-white/10 p-5 backdrop-blur-md">
                <div class="flex gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-blue-500/20">
                        <i class="fa-solid fa-shield-halved text-lg text-blue-300"></i>
                    </div>

                    <div>
                        <h3 class="security-title">
                            Keamanan Terjamin
                        </h3>

                        <p class="security-desc mt-2 text-blue-100">
                            Data warga aman bersama kami dengan sistem terenkripsi.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="relative flex min-h-screen w-full items-center justify-center overflow-hidden bg-[#f5f8ff] px-5 py-8 xl:w-[64%]">

        <!-- Back to Public Page Button -->
        <a href="{{ route('welcome') }}" class="absolute left-6 top-6 z-20 flex items-center gap-2 px-4 py-2.5 bg-white/80 dark:bg-slate-800/80 hover:bg-white text-gray-600 dark:text-gray-200 hover:text-blue-600 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm backdrop-blur-md transition-all text-xs font-bold">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>

        <!-- Theme Toggle Button -->
        <button onclick="toggleTheme()" id="theme-toggle-btn" class="absolute right-6 top-6 z-20 flex items-center justify-center w-10 h-10 bg-white/80 dark:bg-slate-800/80 hover:bg-white text-gray-600 dark:text-amber-400 hover:text-amber-500 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm backdrop-blur-md transition-all cursor-pointer" title="Mode Gelap / Terang">
            <i class="fa-solid fa-moon text-sm" id="theme-toggle-icon"></i>
        </button>

        <div class="absolute -left-20 -top-32 h-[360px] w-[360px] rounded-full bg-blue-100/75"></div>

        <div class="absolute -bottom-36 -right-24 h-[340px] w-[340px] rounded-full bg-blue-100/65"></div>

        <div class="absolute left-[14%] top-[18%] hidden h-16 w-16 rounded-full bg-blue-200/30 blur-xl md:block"></div>
        <div class="absolute bottom-[22%] right-[16%] hidden h-20 w-20 rounded-full bg-blue-300/20 blur-2xl md:block"></div>

        {{-- LOGIN CARD --}}
        <div class="relative z-10 flex w-full justify-center">

            <div class="login-card login-shadow relative overflow-hidden">

                {{-- DOTS --}}
                <div class="absolute right-9 top-9 grid grid-cols-5 gap-2 opacity-20">
                    @for($i = 0; $i < 25; $i++)
                        <div class="h-1.5 w-1.5 rounded-full bg-blue-500"></div>
                    @endfor
                </div>

                {{-- ICON --}}
                <div class="flex justify-center">
                    <div class="floating login-icon-wrapper flex items-center justify-center">
                        <i class="fa-solid fa-people-roof login-icon text-blue-600"></i>
                    </div>
                </div>

                {{-- HEADING --}}
                <div class="mt-6 text-center">
                    <h1 class="login-title text-[#132b63]">
                        Selamat Datang!
                    </h1>

                    <p class="login-subtitle text-[#66708c]">
                        Silakan masuk untuk melanjutkan ke GUYUB.
                    </p>
                </div>

                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                        <div class="flex gap-3">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                            <div>
                                Email, nomor WhatsApp, atau kata sandi yang Anda masukkan belum sesuai.
                            </div>
                        </div>
                    </div>
                @endif

                {{-- FORM --}}
                <form method="POST"
                      action="{{ route('login') }}"
                      class="form-spacing">

                    @csrf

                    {{-- EMAIL --}}
                    <div class="form-group">

                        <label for="email" class="login-label text-[#1d2942]">
                            Email atau Nomor WhatsApp
                        </label>

                        <div class="relative">
                            <i class="fa-regular fa-user input-icon absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

                            <input
                                type="text"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email atau nomor WhatsApp"
                                class="input-custom w-full pl-14 pr-5"
                                autocomplete="username"
                                autofocus
                                required
                            >
                        </div>

                        @error('email')
                            <p class="mt-2 text-xs font-medium text-red-500">
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
                            <i class="fa-solid fa-lock input-icon absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan kata sandi"
                                class="input-custom w-full pl-14 pr-14"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-blue-600"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- FORGOT --}}
                    <div class="flex justify-end">
                        <a href="/welcome#kontak"
                           class="text-[13px] font-semibold text-blue-600 transition hover:text-blue-700 hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <button
                        type="submit"
                        class="btn-login mt-7 w-full font-bold text-white"
                    >
                        <i class="fa-solid fa-right-to-bracket mr-2"></i>
                        Masuk
                    </button>

                    {{-- INFO REGISTER --}}
                    <div class="mt-6 text-center">
                        <p class="text-[12.5px] leading-6 text-gray-500">
                            Belum punya akun?

                            <a href="/welcome#kontak" class="font-semibold text-blue-600 hover:underline transition">
                                Hubungi Pengurus RT
                            </a>
                        </p>
                    </div>

                </form>

                {{-- QUICK LOGIN SHORTCUTS --}}
                <div class="mt-6 border-t border-gray-100 pt-5">
                    <p class="text-center text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">
                        Akses Cepat Login (Demo)
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="quickLogin('superadmin@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-3 bg-red-50 hover:bg-red-100 border border-red-100/50 text-red-600 rounded-xl text-xs font-bold transition-all active:scale-95">
                            👑 <span class="hidden sm:inline">Super</span> Admin
                        </button>
                        <button type="button" onclick="quickLogin('rt@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-3 bg-blue-50 hover:bg-blue-100 border border-blue-100/50 text-blue-600 rounded-xl text-xs font-bold transition-all active:scale-95">
                            👥 Ketua RT
                        </button>
                        <button type="button" onclick="quickLogin('bendahara@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100/50 text-emerald-600 rounded-xl text-xs font-bold transition-all active:scale-95">
                            💵 Bendahara
                        </button>
                        <button type="button" onclick="quickLogin('warga@gmail.com', 'password')" class="flex items-center justify-center gap-1.5 py-2 px-3 bg-gray-50 hover:bg-gray-100 border border-gray-200/50 text-gray-600 rounded-xl text-xs font-bold transition-all active:scale-95">
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
