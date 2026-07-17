{{-- resources/views/auth/register.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Warga - Aplikasi Kas RT</title>

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

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        };
    </script>

    <style>
        body {
            background-color: #F1F5F9;
            color: #1e293b;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        html.dark body {
            background: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(9, 14, 28) 100%);
            color: #f8fafc;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
        }
        html.dark .glass-card {
            background: rgba(19, 27, 46, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.5);
        }
        .left-col {
            background-color: rgba(243, 244, 246, 0.8);
            border-right: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        html.dark .left-col {
            background-color: rgba(26, 43, 76, 0.7);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        .input-dark {
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.12);
            color: #0f172a;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            background-color: #ffffff !important;
        }
        .input-dark::placeholder {
            color: #94a3b8;
        }
        html.dark .input-dark {
            background-color: rgba(13, 19, 33, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.07);
            color: #f8fafc;
        }
        html.dark .input-dark:focus {
            background-color: rgba(15, 23, 42, 0.9) !important;
        }
        html.dark .input-dark::placeholder {
            color: #475569;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 md:p-8">

    <button onclick="toggleTheme()" id="theme-toggle-btn" class="absolute right-8 top-8 text-slate-500 hover:text-amber-500 dark:text-slate-400 dark:hover:text-amber-400 transition flex items-center justify-center w-9 h-9 rounded-xl hover:bg-slate-200 dark:hover:bg-white/5 cursor-pointer z-50" title="Ubah Tema">
        <i class="fa-solid fa-moon text-sm" id="theme-toggle-icon"></i>
    </button>

    <div class="glass-card w-full max-w-5xl rounded-[2rem] overflow-hidden flex flex-col md:flex-row min-h-[640px]">
        
        {{-- LEFT COLUMN --}}
        <div class="left-col w-full md:w-[40%] p-8 md:p-10 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 space-y-8">
                {{-- Logo Header --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600/15 dark:bg-blue-600/25 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-500/20">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-blue-900 dark:text-white text-sm tracking-tight leading-none">Kas RT 01</h4>
                        <span class="text-[9px] text-blue-700 dark:text-blue-300 font-semibold tracking-wider uppercase mt-1 block">RT 01 / RW 05 Perumahan</span>
                    </div>
                </div>

                {{-- Welcome Text --}}
                <div class="space-y-3">
                    <h2 class="text-2xl md:text-3xl font-black text-blue-900 dark:text-white leading-tight">Selamat Datang Warga Baru!</h2>
                    <p class="text-xs text-slate-600 dark:text-blue-100/70 leading-relaxed font-medium">
                        Bergabunglah dengan portal digital RT 01 untuk kemudahan administrasi, transparansi keuangan, dan info posyandu terpadu.
                    </p>
                </div>

                {{-- Features List --}}
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-slate-800 dark:text-white">Manajemen Iuran Mandiri</h5>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-normal mt-0.5">Bayar iuran dan lihat status tagihan bulanan langsung dari HP Anda.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-slate-800 dark:text-white">Pengajuan Surat Praktis</h5>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-normal mt-0.5">Buat permohonan surat pengantar RT tanpa perlu datang secara langsung.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-slate-800 dark:text-white">Info UMKM & Koperasi</h5>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-normal mt-0.5">Dukung perekonomian lokal dengan fitur UMKM dan Koperasi bersama.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold mt-10 md:mt-0">
                System Powered by Laravel 12 & XAMPP MySQL
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="w-full md:w-[60%] p-8 md:p-10 flex flex-col justify-between max-h-[85vh] md:max-h-[700px] overflow-y-auto">
            
            <div>
                {{-- Header --}}
                <div class="mb-6">
                    <h2 class="text-xl font-extrabold text-slate-800 dark:text-white">Pendaftaran Akun Warga</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Isi formulir di bawah ini dengan data kependudukan yang valid</p>
                </div>

                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 dark:border-red-900/30 bg-red-50 dark:bg-red-950/20 px-3.5 py-2.5 text-xs font-medium text-red-600 dark:text-red-400 flex gap-2">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                {{-- FORM --}}
                <form id="registerForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- 🔑 KREDENSIAL AKUN --}}
                    <div>
                        <div class="flex items-center gap-1.5 text-amber-600 dark:text-amber-500 text-[10px] font-black uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-key"></i> Kredensial Akun
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rian Hidayat" class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Alamat Email</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-regular fa-envelope"></i></span>
                                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: rian@email.com" class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold focus:outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Kata Sandi</label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" name="password" required placeholder="Minimal 6 karakter" class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Konfirmasi Kata Sandi</label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" name="password_confirmation" required placeholder="Ketik ulang kata sandi" class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 🎫 DATA KEPENDUDUKAN --}}
                    <div>
                        <div class="flex items-center gap-1.5 text-amber-600 dark:text-amber-500 text-[10px] font-black uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-id-card"></i> Data Kependudukan
                        </div>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Nomor Kartu Keluarga (KK)</label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-address-card"></i></span>
                                        <input type="text" name="nomor_kk" value="{{ old('nomor_kk') }}" required placeholder="16 Digit Nomor KK" minlength="16" maxlength="16" class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Status Warga</label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-circle-info"></i></span>
                                        <select name="status_warga" required class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-bold focus:outline-none appearance-none cursor-pointer">
                                            <option value="Tetap" {{ old('status_warga') == 'Tetap' ? 'selected' : '' }}>Warga Tetap</option>
                                            <option value="Kontrak" {{ old('status_warga') == 'Kontrak' ? 'selected' : '' }}>Warga Kontrak</option>
                                            <option value="Kos" {{ old('status_warga') == 'Kos' ? 'selected' : '' }}>Warga Kos</option>
                                        </select>
                                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 pointer-events-none text-xs"><i class="fa-solid fa-chevron-down"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Alamat Rumah (No. Blok)</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-house"></i></span>
                                    <input type="text" name="blok_rumah" value="{{ old('blok_rumah') }}" required placeholder="Contoh: Blok B3 No. 12" class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Foto Kartu Tanda Penduduk (KTP)</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-camera"></i></span>
                                    <input type="file" name="foto_ktp" required accept="image/*" class="input-dark w-full pl-10 pr-4 py-2 rounded-xl text-xs font-semibold focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-600/10 dark:file:bg-blue-600/20 file:text-blue-600 dark:file:text-blue-400 cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 👤 INFORMASI PRIBADI --}}
                    <div>
                        <div class="flex items-center gap-1.5 text-amber-600 dark:text-amber-500 text-[10px] font-black uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-circle-user"></i> Informasi Pribadi
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Umur (Tahun)</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-regular fa-calendar"></i></span>
                                    <input type="number" name="umur" value="{{ old('umur') }}" required placeholder="Contoh: 30" min="1" max="120" class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">Status Hubungan dalam KK</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-users"></i></span>
                                    <select name="status_keluarga" required class="input-dark w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-bold focus:outline-none appearance-none cursor-pointer">
                                        <option value="Kepala Keluarga" {{ old('status_keluarga') == 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                        <option value="Istri" {{ old('status_keluarga') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                        <option value="Anak" {{ old('status_keluarga') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                        <option value="Lainnya" {{ old('status_keluarga') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 pointer-events-none text-xs"><i class="fa-solid fa-chevron-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT & REDIRECT --}}
                    <div class="pt-2 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('login') }}" class="w-full sm:w-1/3 py-3 bg-slate-200 dark:bg-white/5 hover:bg-slate-300/60 dark:hover:bg-white/10 text-slate-700 dark:text-slate-200 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-300 text-center flex items-center justify-center cursor-pointer border border-slate-300/40 dark:border-white/5">
                            <i class="fa-solid fa-arrow-left mr-1.5"></i> Batal
                        </a>
                        <button type="submit" class="w-full sm:w-2/3 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-300 shadow-lg shadow-blue-900/20 active:scale-[0.99] cursor-pointer border-none">
                            <i class="fa-solid fa-user-plus mr-1.5"></i> Daftar Akun Baru
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <script>
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
                if (btn) btn.className = 'absolute right-8 top-8 text-amber-400 hover:text-amber-500 transition flex items-center justify-center w-9 h-9 rounded-xl hover:bg-white/5 cursor-pointer z-50';
                if (btn) btn.title = 'Ubah ke Mode Terang';
            } else {
                document.documentElement.classList.remove('dark');
                if (icon) icon.className = 'fa-solid fa-moon text-sm text-slate-500';
                if (btn) btn.className = 'absolute right-8 top-8 text-slate-500 hover:text-slate-600 transition flex items-center justify-center w-9 h-9 rounded-xl hover:bg-slate-200 cursor-pointer z-50';
                if (btn) btn.title = 'Ubah ke Mode Gelap';
            }
        }
    </script>

</body>
</html>
