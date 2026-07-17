<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        // Check and set device mode cookie based on window width before any content renders
        (function() {
            const currentMode = window.innerWidth < 768 ? 'mobile' : 'desktop';
            const cookies = document.cookie.split('; ');
            const existingCookie = cookies.find(row => row.startsWith('device_mode='));
            const existingVal = existingCookie ? existingCookie.split('=')[1] : null;
            if (existingVal !== currentMode) {
                document.cookie = "device_mode=" + currentMode + "; path=/; max-age=31536000; SameSite=Lax";
                // Reload on initial load if the mode is wrong, to get correct server-side view
                if (!window.performance.navigation.type && window.history.length <= 1) {
                    window.location.reload();
                }
            }
        })();
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin - GUYUB</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.csrfToken = "{{ csrf_token() }}";
        
        window.alert = function(msg) {
            let strMsg = String(msg || '');
            let isError = /gagal|error|❌|peringatan|salah|terjadi kesalahan/i.test(strMsg);
            let isSuccess = /berhasil|sukses|lunas|✅|konfirmasi|diperbarui|disimpan|dihapus|ditambahkan/i.test(strMsg);
            
            let cleanMsg = strMsg.replace(/^[✅❌🎉]\s*/, '');
            
            if (!document.getElementById('guyub-modal-styles')) {
                let style = document.createElement('style');
                style.id = 'guyub-modal-styles';
                style.innerHTML = `
                    #guyub-modal-overlay {
                        position: fixed;
                        inset: 0;
                        z-index: 999999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: rgba(15, 23, 42, 0.4);
                        backdrop-filter: blur(8px);
                        -webkit-backdrop-filter: blur(8px);
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }
                    .guyub-modal-card {
                        background: #ffffff;
                        border: 1px solid rgba(241, 245, 249, 0.8);
                        box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.22);
                        border-radius: 2rem;
                        padding: 2.25rem 2rem 2rem 2rem;
                        width: calc(100% - 3rem);
                        max-width: 310px;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        text-align: center;
                        transform: scale(0.9) translateY(15px);
                        opacity: 0;
                        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }
                    .guyub-modal-icon-wrapper {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 3.5rem;
                        height: 3.5rem;
                        border-radius: 1.25rem;
                        margin-bottom: 1.25rem;
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
                    }
                    .guyub-icon-success {
                        background: linear-gradient(135deg, #10b981, #059669);
                        color: #ffffff;
                        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.35);
                    }
                    .guyub-icon-error {
                        background: linear-gradient(135deg, #f43f5e, #e11d48);
                        color: #ffffff;
                        box-shadow: 0 10px 20px -5px rgba(244, 63, 94, 0.35);
                    }
                    .guyub-icon-info {
                        background: linear-gradient(135deg, #3b82f6, #2563eb);
                        color: #ffffff;
                        box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.35);
                    }
                    .guyub-modal-title {
                        margin: 0;
                        font-size: 16px;
                        font-weight: 900;
                        color: #0f172a;
                        line-height: 1.2;
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        letter-spacing: -0.02em;
                    }
                    .guyub-modal-message {
                        margin: 0.65rem 0 0 0;
                        font-size: 11px;
                        font-weight: 600;
                        color: #64748b;
                        line-height: 1.55;
                        font-family: 'Plus Jakarta Sans', sans-serif;
                    }
                `;
                document.head.appendChild(style);
            }

            let overlay = document.createElement('div');
            overlay.id = 'guyub-modal-overlay';
            
            let iconClass = 'guyub-icon-info';
            let titleText = 'Informasi';
            let iconHtml = `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                </svg>
            `;

            if (isError) {
                iconClass = 'guyub-icon-error';
                titleText = 'Perhatian';
                iconHtml = `
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                    </svg>
                `;
            } else if (isSuccess) {
                iconClass = 'guyub-icon-success';
                titleText = 'Berhasil';
                iconHtml = `
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                `;

                // SaaS Action title and message mappings
                if (/kehadiran diperbarui|status.*kehadiran/i.test(cleanMsg)) {
                    titleText = 'Kehadiran Diperbarui';
                    cleanMsg = 'Status kehadiran peserta posyandu berhasil disimpan.';
                } else if (/posyandu.*tambah|posyandu.*jadwal|jadwal.*posyandu/i.test(cleanMsg)) {
                    titleText = 'Jadwal Posyandu Dibuat';
                    cleanMsg = 'Jadwal pelaksanaan posyandu baru berhasil diterbitkan.';
                } else if (/posyandu.*daftar|daftar.*posyandu/i.test(cleanMsg)) {
                    titleText = 'Peserta Terdaftar';
                    cleanMsg = 'Peserta baru berhasil didaftarkan ke kegiatan Posyandu.';
                } else if (/posyandu.*hapus|hapus.*posyandu/i.test(cleanMsg)) {
                    titleText = 'Pendaftaran Dibatalkan';
                    cleanMsg = 'Pendaftaran peserta posyandu berhasil dibatalkan.';
                } else if (/warga.*tambah|tambah.*warga/i.test(cleanMsg)) {
                    titleText = 'Warga Ditambahkan';
                    cleanMsg = 'Data profil warga baru berhasil disimpan ke database.';
                } else if (/warga.*diperbarui|update.*warga/i.test(cleanMsg)) {
                    titleText = 'Data Warga Diperbarui';
                    cleanMsg = 'Perubahan profil warga berhasil disimpan.';
                } else if (/warga.*hapus|hapus.*warga/i.test(cleanMsg)) {
                    titleText = 'Warga Dihapus';
                    cleanMsg = 'Data warga beserta seluruh rekam jejaknya telah dihapus.';
                } else if (/tagihan.*tambah|generate.*tagihan|tagihan.*buat/i.test(cleanMsg)) {
                    titleText = 'Tagihan Diterbitkan';
                    cleanMsg = 'Tagihan iuran bulanan warga berhasil diterbitkan.';
                } else if (/tagihan.*diperbarui|update.*tagihan/i.test(cleanMsg)) {
                    titleText = 'Tagihan Diperbarui';
                    cleanMsg = 'Perubahan rincian tagihan berhasil disimpan.';
                } else if (/tagihan.*hapus|hapus.*tagihan/i.test(cleanMsg)) {
                    titleText = 'Tagihan Dihapus';
                    cleanMsg = 'Data tagihan iuran warga telah dihapus dari sistem.';
                } else if (/verifikasi|pembayaran.*verifikasi/i.test(cleanMsg)) {
                    titleText = 'Pembayaran Diverifikasi';
                    cleanMsg = 'Pembayaran iuran warga berhasil diverifikasi.';
                } else if (/surat.*tambah|surat.*buat/i.test(cleanMsg)) {
                    titleText = 'Surat Pengantar Diajukan';
                    cleanMsg = 'Pengajuan surat online berhasil dikirim ke pengurus RT.';
                } else if (/status.*surat|surat.*status/i.test(cleanMsg)) {
                    titleText = 'Status Surat Diperbarui';
                    cleanMsg = 'Persetujuan status surat online berhasil disimpan.';
                } else if (/aspirasi.*kirim|aspirasi.*tambah/i.test(cleanMsg)) {
                    titleText = 'Aspirasi Dikirim';
                    cleanMsg = 'Aspirasi & masukan Anda berhasil dikirim ke pengurus RT.';
                } else if (/tanggapan.*aspirasi/i.test(cleanMsg)) {
                    titleText = 'Aspirasi Ditanggapi';
                    cleanMsg = 'Tanggapan pengurus RT terhadap aspirasi berhasil disimpan.';
                } else if (/kategori.*tambah|kategori.*buat/i.test(cleanMsg)) {
                    titleText = 'Kategori Ditambahkan';
                    cleanMsg = 'Kategori transaksi keuangan baru berhasil disimpan.';
                } else if (/kategori.*diperbarui|update.*kategori/i.test(cleanMsg)) {
                    titleText = 'Kategori Diperbarui';
                    cleanMsg = 'Perubahan kategori transaksi keuangan berhasil disimpan.';
                } else if (/kategori.*hapus|hapus.*kategori/i.test(cleanMsg)) {
                    titleText = 'Kategori Dihapus';
                    cleanMsg = 'Kategori transaksi berhasil dihapus dari sistem.';
                } else if (/sampah.*catat|sampah.*tambah/i.test(cleanMsg)) {
                    titleText = 'Setoran Sampah Dicatat';
                    cleanMsg = 'Setoran tabungan bank sampah warga berhasil dibukukan.';
                } else if (/pengumuman.*tambah|pengumuman.*siar/i.test(cleanMsg)) {
                    titleText = 'Pengumuman Disiarkan';
                    cleanMsg = 'Pengumuman warga berhasil diterbitkan dan disiarkan.';
                } else if (/perangkat.*simpan|perangkat.*tambah/i.test(cleanMsg)) {
                    titleText = 'Aset/Inventaris Ditambahkan';
                    cleanMsg = 'Inventaris aset baru berhasil disimpan.';
                } else if (/perangkat.*ubah|perangkat.*update/i.test(cleanMsg)) {
                    titleText = 'Aset/Inventaris Diperbarui';
                    cleanMsg = 'Perubahan data inventaris aset berhasil disimpan.';
                } else if (/perangkat.*hapus/i.test(cleanMsg)) {
                    titleText = 'Aset/Inventaris Dihapus';
                    cleanMsg = 'Data inventaris aset berhasil dihapus.';
                } else if (/koperasi.*tambah|koperasi.*produk/i.test(cleanMsg)) {
                    titleText = 'Produk Koperasi Ditambahkan';
                    cleanMsg = 'Produk sembako koperasi berhasil ditambahkan.';
                } else if (/umkm.*tambah|umkm.*buat/i.test(cleanMsg)) {
                    titleText = 'UMKM Warga Didaftarkan';
                    cleanMsg = 'Profil UMKM warga berhasil didaftarkan ke etalase.';
                }
            }

            overlay.innerHTML = `
                <div class="guyub-modal-card">
                    <div class="guyub-modal-icon-wrapper ${iconClass}">
                        ${iconHtml}
                    </div>
                    <h3 class="guyub-modal-title">${titleText}</h3>
                    <p class="guyub-modal-message">${cleanMsg}</p>
                </div>
            `;

            document.body.appendChild(overlay);

            requestAnimationFrame(() => {
                overlay.style.opacity = '1';
                let card = overlay.querySelector('.guyub-modal-card');
                if (card) {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1) translateY(0)';
                }
            });

            function dismissModal() {
                overlay.style.opacity = '0';
                let card = overlay.querySelector('.guyub-modal-card');
                if (card) {
                    card.style.transform = 'scale(0.9) translateY(15px)';
                    card.style.opacity = '0';
                }
                setTimeout(() => {
                    if (overlay.parentElement) overlay.remove();
                }, 300);
            }

            overlay.onclick = dismissModal;

            setTimeout(dismissModal, 2200);
        };

        // Anti-flicker theme init
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; transition: background-color 0.3s ease, color 0.3s ease; }
        .sidebar-scroll::-webkit-scrollbar { width: 3px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
        .menu-active { background-color: #2563EB !important; color: white !important; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4); font-weight: 700; }
        
        /* Top Loading Progress Bar */
        #top-loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(to right, #3b82f6, #60a5fa, #3b82f6);
            z-index: 999999;
            width: 0%;
            transition: width 0.4s ease, opacity 0.3s ease;
            opacity: 0;
            pointer-events: none;
        }
        
        /* ================= DARK MODE STYLING ================= */
        html.dark, html.dark body, html.dark #main-content { background-color: #0F172A !important; color: #F8FAFC !important; }
        html.dark header { background-color: #1E293B !important; border-color: rgba(255, 255, 255, 0.05) !important; }
        html.dark header h2, html.dark header p, html.dark header span, html.dark header button { color: #F8FAFC !important; }
        
        /* Containers & Cards */
        html.dark [class*="bg-white"], 
        html.dark [class*="bg-gray-50"], 
        html.dark [class*="bg-gray-100"], 
        html.dark [class*="bg-slate-50"], 
        html.dark [class*="bg-slate-100"], 
        html.dark div.bg-white,
        html.dark form.bg-white,
        html.dark div[class*="bg-white"] { 
            background-color: #1E293B !important; 
            color: #F8FAFC !important; 
            border-color: rgba(255, 255, 255, 0.08) !important; 
        }

        /* Tables & Table Headers */
        html.dark thead, html.dark th, html.dark tr th, html.dark tr.bg-gray-50\/80, html.dark tr.bg-gray-50, html.dark tr.bg-slate-50 { 
            background-color: #111827 !important; 
            color: #E2E8F0 !important; 
            border-color: rgba(255, 255, 255, 0.08) !important; 
        }
        html.dark td { 
            border-color: rgba(255, 255, 255, 0.05) !important; 
        }
        html.dark tr:hover { 
            background-color: rgba(255, 255, 255, 0.02) !important; 
        }

        /* Typography / Font Colors */
        html.dark .text-gray-900, html.dark .text-gray-800, html.dark .text-gray-700,
        html.dark .text-slate-900, html.dark .text-slate-800, html.dark .text-slate-700,
        html.dark h1, html.dark h2, html.dark h3, html.dark h4, html.dark h5, html.dark h6 { 
            color: #F8FAFC !important; 
        }
        
        html.dark .text-gray-600, html.dark .text-gray-500, html.dark .text-gray-400,
        html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-slate-400,
        html.dark p, html.dark span:not([class*="text-"]) { 
            color: #CBD5E1 !important; 
        }

        /* Colored Status Badges & Buttons in Dark Mode */
        html.dark [class*="bg-amber"], html.dark [class*="bg-yellow"] {
            background-color: rgba(217, 119, 6, 0.2) !important;
            color: #FCD34D !important;
            border-color: rgba(245, 158, 11, 0.3) !important;
        }

        html.dark [class*="bg-emerald"], html.dark [class*="bg-green"] {
            background-color: rgba(5, 150, 105, 0.2) !important;
            color: #6EE7B7 !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }

        html.dark [class*="bg-rose"], html.dark [class*="bg-red"] {
            background-color: rgba(225, 29, 72, 0.2) !important;
            color: #FCA5A5 !important;
            border-color: rgba(244, 63, 94, 0.3) !important;
        }

        html.dark [class*="bg-blue"], html.dark [class*="bg-indigo"] {
            background-color: rgba(37, 99, 235, 0.2) !important;
            color: #93C5FD !important;
            border-color: rgba(59, 130, 246, 0.3) !important;
        }

        /* Action Buttons Override in Dark Mode */
        html.dark button.bg-indigo-500, html.dark button.bg-blue-600 {
            background-color: #3B82F6 !important;
            color: #FFFFFF !important;
        }

        /* Borders */
        html.dark [class*="border-gray"], html.dark [class*="border-slate"] { 
            border-color: rgba(255, 255, 255, 0.08) !important; 
        }

        /* Inputs, Modals, Popups */
        html.dark input, html.dark select, html.dark textarea { background-color: #0F172A !important; color: #F8FAFC !important; border-color: rgba(255, 255, 255, 0.15) !important; }
        html.dark input::placeholder, html.dark textarea::placeholder { color: #64748B !important; }
        html.dark .swal2-popup { background-color: #1E293B !important; color: #F8FAFC !important; border-color: rgba(255, 255, 255, 0.1) !important; }
        html.dark .swal2-title, html.dark .swal2-html-container { color: #F8FAFC !important; }
        html.dark #notification-dropdown { background-color: #1E293B !important; border-color: rgba(255, 255, 255, 0.1) !important; }
        html.dark #theme-toggle-btn { background-color: #1E293B !important; border-color: rgba(255, 255, 255, 0.1) !important; color: #F59E0B !important; }
        
        #sidebar {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out !important;
        }

        @media (min-width: 768px) {
            /* Collapsed state */
            .sidebar-collapsed {
                width: 80px !important;
                overflow-x: hidden !important;
                overflow-y: auto !important;
            }
            .sidebar-collapsed .p-8 > div:last-child,
            .sidebar-collapsed .pt-8.pb-2.px-4,
            .sidebar-collapsed .menu-link span,
            .sidebar-collapsed .menu-link i.fa-chevron-down,
            .sidebar-collapsed .sticky.bottom-0 span,
            .sidebar-collapsed .sticky.bottom-0 i.fa-arrow-up-right-from-square,
            .sidebar-collapsed .sidebar-close-btn {
                display: none !important;
            }
            /* Submenu hidden by default in collapsed */
            .sidebar-collapsed .submenu-container {
                display: none !important;
            }
            /* Dropdown group relative */
            .sidebar-collapsed .dropdown-group {
                position: relative;
            }
        }
        /* Flyout popup - rendered outside sidebar on body */
        #flyout-popup {
            position: fixed;
            left: 80px;
            min-width: 220px;
            max-width: 260px;
            background: #1E293B;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 8px 0;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            z-index: 99999;
            display: none;
        }
        #flyout-popup.open {
            display: block;
        }
        #flyout-popup a {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            font-size: 12px;
            color: #94A3B8;
            border-radius: 8px;
            margin: 2px 6px;
            text-decoration: none;
            transition: all 0.15s;
            font-weight: 500;
        }
        #flyout-popup a:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }
        @media (min-width: 768px) {
            .sidebar-collapsed .p-8 {
                padding: 1.5rem 0.75rem !important;
                justify-content: center !important;
            }
            .sidebar-collapsed .p-8 div:first-child {
                margin-right: 0 !important;
            }
            .sidebar-collapsed nav.px-4 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            .sidebar-collapsed .menu-link {
                justify-content: center !important;
                padding: 0.75rem 0 !important;
                border-radius: 1rem !important;
                position: relative;
            }
            .sidebar-collapsed .menu-link i {
                margin-right: 0 !important;
                font-size: 1.15rem !important;
                width: auto !important;
            }
        /* Global Tooltip container (rendered at body level to bypass overflow clipping) */
        #global-tooltip {
            position: fixed;
            background: #0F172A;
            color: #F1F5F9;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            z-index: 999999;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.08);
            transition: opacity 0.15s ease;
            opacity: 0;
            display: none;
            transform: translateY(-50%);
        }
        #global-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #0F172A;
        }
            .sidebar-collapsed .sticky.bottom-0 {
                padding: 1rem 0.5rem !important;
            }
            .sidebar-collapsed .sticky.bottom-0 a,
            .sidebar-collapsed .sticky.bottom-0 button {
                justify-content: center !important;
                padding: 0.75rem 0 !important;
                border-radius: 1rem !important;
                position: relative;
            }
            .sidebar-collapsed .sticky.bottom-0 i {
                margin-right: 0 !important;
                font-size: 1.1rem !important;
            }
        }

        @media (max-width: 768px) {
            #mobile-menu-sheet button,
            #mobile-menu-sheet a {
                padding: 0.5rem !important;
                gap: 0.25rem !important;
            }
            #mobile-menu-sheet h4 {
                margin-bottom: 0.5rem !important;
            }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    @php
        // 1. LOGIC HAK AKSES SIDEBAR
        $aksesHalaman = [
            'Super Admin'   => [
                'dashboard', 'pemasukan', 'pengeluaran', 'transaksi', 'kategori',
                'data-warga', 'data-keluarga', 'data-iuran', 'data-pengurus-rt', 'data-rt', 'pengguna', 'perangkat-sistem',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan', 'backup-restore', 'aktivitas-pengguna',
                'tagihan-warga', 'pembayaran-online', 'riwayat-gateway', 'qris-va',
                'surat-online', 'pengumuman',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi', 'approval-warga',
                'peraturan-sk', 'kerja-bakti'
            ],
            'RW'            => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'qris-va',
                'surat-online', 'pengumuman', 'data-warga', 'data-keluarga', 'data-iuran', 'data-pengurus-rt', 'data-rt', 'perangkat-sistem',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi', 'approval-warga',
                'peraturan-sk', 'kerja-bakti'
            ],
            'Sekretaris RW' => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga',
                'surat-online', 'pengumuman', 'data-warga', 'data-keluarga', 'data-pengurus-rt',
                'kegiatan', 'aspirasi', 'approval-warga', 'peraturan-sk', 'kerja-bakti'
            ],
            'Bendahara RW'  => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'pembayaran-online', 'riwayat-gateway', 'qris-va',
                'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan',
                'koperasi'
            ],
            'RT'            => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'qris-va',
                'surat-online', 'pengumuman', 'data-warga', 'data-keluarga', 'data-iuran', 'data-pengurus-rt', 'data-rt', 'perangkat-sistem',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi', 'approval-warga',
                'peraturan-sk', 'kerja-bakti'
            ],
            'Sekretaris RT' => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga',
                'surat-online', 'pengumuman', 'data-warga', 'data-keluarga', 'data-pengurus-rt', 'data-rt', 'perangkat-sistem',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'pengaturan',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi', 'approval-warga',
                'peraturan-sk', 'kerja-bakti'
            ],
            'Bendahara RT'  => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'pembayaran-online', 'riwayat-gateway', 'qris-va',
                'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'surat-online', 'pengumuman', 'data-keluarga', 'data-iuran',
                'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan',
                'koperasi', 'bank-sampah', 'rukem', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'aspirasi',
                'peraturan-sk', 'kerja-bakti'
            ],
            'Warga'         => [
                'dashboard', 'pembayaran-menu', 'tagihan-warga', 'pembayaran-online',
                'surat-online', 'pengumuman', 'data-keluarga', 'data-pengurus-rt', 'pengaturan', 'perangkat-sistem',
                'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi',
                'peraturan-sk', 'kerja-bakti'
            ]
        ];

        $can = function($menu) use ($aksesHalaman) {
            $role = Auth::check() ? Auth::user()->role : 'Warga';
            if ($role === 'Super Admin') {
                return true;
            }
            return isset($aksesHalaman[$role]) && in_array($menu, $aksesHalaman[$role]);
        };
    @endphp

    <!-- Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity duration-300"></div>

    <aside id="sidebar" class="w-[300px] bg-[#0F172A] text-[#94A3B8] flex flex-col shrink-0 sidebar-scroll overflow-y-auto fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 md:static transition-all duration-300 ease-in-out sidebar-collapsed">
        <!-- Close button inside sidebar -->
        <button onclick="toggleSidebar()" class="sidebar-close-btn md:hidden p-2 text-gray-400 hover:text-white rounded-lg absolute top-6 right-6 focus:outline-none transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <a href="{{ route('welcome') }}" data-tooltip="Lihat Halaman Publik GUYUB" class="p-8 flex items-center shrink-0 hover:opacity-90 transition cursor-pointer group">
            <div class="bg-white p-2.5 rounded-2xl mr-4 shadow-sm flex items-center justify-center group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-house-chimney-window text-[#0F172A] text-xl"></i>
            </div>
            <div>
                <h1 class="text-white font-extrabold text-xl leading-none italic uppercase tracking-tighter group-hover:text-blue-400 transition-colors">GUYUB</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px] mt-1.5 text-nowrap">Super Admin Panel</p>
            </div>
        </a>

        <nav class="flex-1 px-4 space-y-1 mt-2 pb-10">
            @if($can('dashboard'))
            <a href="javascript:void(0)" onclick="switchPage('dashboard', this)" class="menu-link {{ isset($page) && $page == 'dashboard' ? 'menu-active text-white' : 'hover:bg-white/5 hover:text-white' }} flex items-center px-4 py-3.5 rounded-2xl transition-all" data-tooltip="Dashboard">
                <i class="fa-solid fa-th-large w-6 text-lg"></i> <span class="ml-3 text-sm font-bold">Dashboard</span>
            </a>
            @endif

            @if($can('tagihan-warga') || $can('pembayaran-online') || $can('riwayat-gateway') || $can('qris-va') || $can('pemasukan') || $can('pengeluaran') || $can('transaksi') || $can('kategori') || $can('laporan-keuangan') || $can('laporan-iuran') || $can('laporan-kas') || $can('export-laporan'))
            {{-- 1. KATEGORI: KEUANGAN & LAPORAN --}}
            <div class="pt-8 pb-2 px-4">
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Keuangan & Laporan</p>
            </div>

            {{-- ===== DROPDOWN: Pembayaran ===== --}}
            @if($can('tagihan-warga') || $can('pembayaran-online') || $can('riwayat-gateway') || $can('qris-va'))
            <div class="space-y-1 dropdown-group">
                <button onclick="toggleDropdown('pembayaran-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group" data-tooltip="Pembayaran">
                    <i class="fa-solid fa-wallet w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Pembayaran</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200"></i>
                </button>
                <div id="pembayaran-menu" class="submenu-container hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @php
                        $role = Auth::check() ? Auth::user()->role : 'Warga';
                        $labelTagihan = ($role == 'Warga') ? 'Tagihan Saya' : (($role == 'RT') ? 'Daftar Tagihan Warga' : 'Kelola Tagihan Warga');
                        $labelQris = ($role == 'Warga') ? 'Rekening & QRIS RT' : (($role == 'RT') ? 'Daftar Rekening & QRIS' : 'Kelola Rekening & QRIS');
                    @endphp
                    @if($can('tagihan-warga'))
                    <a href="javascript:void(0)" onclick="switchPage('tagihan-warga', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">{{ $labelTagihan }}</a>
                    @endif
                    @if($can('qris-va'))
                    <a href="javascript:void(0)" onclick="switchPage('qris-va', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">{{ $labelQris }}</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- ===== DROPDOWN: Transaksi ===== --}}
            @if($can('pemasukan') || $can('pengeluaran') || $can('transaksi') || $can('kategori'))
            <div class="space-y-1 dropdown-group">
                <button onclick="toggleDropdown('transaksi-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group" data-tooltip="Transaksi">
                    <i class="fa-solid fa-shuffle w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Transaksi</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200"></i>
                </button>
                <div id="transaksi-menu" class="submenu-container hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @if($can('pemasukan'))
                    <a href="javascript:void(0)" onclick="switchPage('pemasukan', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Pemasukan</a>
                    @endif
                    @if($can('pengeluaran'))
                    <a href="javascript:void(0)" onclick="switchPage('pengeluaran', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Pengeluaran</a>
                    @endif
                    @if($can('transaksi'))
                    <a href="javascript:void(0)" onclick="switchPage('transaksi', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Semua Transaksi</a>
                    @endif
                    @if($can('kategori'))
                    <a href="javascript:void(0)" onclick="switchPage('kategori', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Kategori</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- ===== DROPDOWN: Laporan ===== --}}
            @if($can('laporan-keuangan') || $can('laporan-iuran') || $can('laporan-kas') || $can('export-laporan') || $can('laporan-koperasi'))
            <div class="space-y-1 dropdown-group">
                <button onclick="toggleDropdown('laporan-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group" data-tooltip="Laporan">
                    <i class="fa-solid fa-chart-line w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Laporan</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200"></i>
                </button>
                <div id="laporan-menu" class="submenu-container hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @if($can('laporan-keuangan'))
                    <a href="javascript:void(0)" onclick="switchPage('laporan-keuangan', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Laporan Keuangan</a>
                    @endif
                    @if($can('laporan-iuran'))
                    <a href="javascript:void(0)" onclick="switchPage('laporan-iuran', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Laporan Iuran</a>
                    @endif
                    @if($can('laporan-kas'))
                    <a href="javascript:void(0)" onclick="switchPage('laporan-kas', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Laporan Kas</a>
                    @endif
                    @if($can('laporan-koperasi'))
                    <a href="javascript:void(0)" onclick="switchPage('laporan-koperasi', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Laporan Koperasi</a>
                    @endif
                    @if($can('export-laporan'))
                    <a href="javascript:void(0)" onclick="switchPage('export-laporan', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Export Laporan</a>
                    @endif
                </div>
            </div>
            @endif
            @endif

            <script>
                // Create flyout popup container on body (outside sidebar to avoid transform clipping)
                (function() {
                    if (!document.getElementById('flyout-popup')) {
                        const fp = document.createElement('div');
                        fp.id = 'flyout-popup';
                        document.body.appendChild(fp);
                    }
                    if (!document.getElementById('global-tooltip')) {
                        const gt = document.createElement('div');
                        gt.id = 'global-tooltip';
                        document.body.appendChild(gt);
                    }
                })();

                // Dynamic Tooltip Event Listeners
                document.addEventListener('mouseover', function(e) {
                    const sidebar = document.getElementById('sidebar');
                    if (!sidebar || !sidebar.classList.contains('sidebar-collapsed')) return;

                    const target = e.target.closest('#sidebar .menu-link, #sidebar .sticky.bottom-0 a, #sidebar .sticky.bottom-0 button, #sidebar [data-tooltip]');
                    if (!target) return;

                    // Prevent flickering when hovering child elements inside the link/button
                    if (e.relatedTarget && e.relatedTarget.closest('#sidebar .menu-link, #sidebar .sticky.bottom-0 a, #sidebar .sticky.bottom-0 button, #sidebar [data-tooltip]') === target) {
                        return;
                    }

                    if (document.body.classList.contains('flyout-active')) return;

                    const text = target.getAttribute('data-tooltip');
                    if (!text) return;

                    const tooltip = document.getElementById('global-tooltip');
                    if (tooltip) {
                        tooltip.textContent = text;
                        tooltip.style.display = 'block';
                        const rect = target.getBoundingClientRect();
                        tooltip.style.left = (rect.right + 10) + 'px';
                        tooltip.style.top = (rect.top + rect.height / 2) + 'px';
                        tooltip.style.opacity = '1';
                    }
                });

                document.addEventListener('mouseout', function(e) {
                    const target = e.target.closest('#sidebar .menu-link, #sidebar .sticky.bottom-0 a, #sidebar .sticky.bottom-0 button, #sidebar [data-tooltip]');
                    if (!target) return;

                    // Prevent hiding when moving mouse inside child elements of the link/button
                    if (e.relatedTarget && e.relatedTarget.closest('#sidebar .menu-link, #sidebar .sticky.bottom-0 a, #sidebar .sticky.bottom-0 button, #sidebar [data-tooltip]') === target) {
                        return;
                    }

                    const tooltip = document.getElementById('global-tooltip');
                    if (tooltip) {
                        tooltip.style.opacity = '0';
                        tooltip.style.display = 'none';
                    }
                });

                document.addEventListener('click', function() {
                    const tooltip = document.getElementById('global-tooltip');
                    if (tooltip) {
                        tooltip.style.opacity = '0';
                        tooltip.style.display = 'none';
                    }
                });

                let activeFlyoutId = null;

                function closeFlyout() {
                    const fp = document.getElementById('flyout-popup');
                    if (fp) {
                        fp.classList.remove('open');
                        fp.innerHTML = '';
                    }
                    document.body.classList.remove('flyout-active');
                    activeFlyoutId = null;
                }

                function toggleDropdown(id) {
                    const sidebar = document.getElementById('sidebar');
                    const menu = document.getElementById(id);
                    if (!menu) return;
                    const isCollapsed = sidebar && sidebar.classList.contains('sidebar-collapsed');

                    if (isCollapsed) {
                        const fp = document.getElementById('flyout-popup');
                        if (!fp) return;

                        // If this flyout is already open, close it
                        if (activeFlyoutId === id) {
                            closeFlyout();
                            return;
                        }

                        // Clone submenu links into flyout popup
                        fp.innerHTML = '';
                        const links = menu.querySelectorAll('a');
                        links.forEach(link => {
                            const clone = link.cloneNode(true);
                            // Re-attach onclick since cloneNode doesn't copy inline event handlers in all browsers
                            const onclickAttr = link.getAttribute('onclick');
                            if (onclickAttr) {
                                clone.setAttribute('onclick', onclickAttr);
                            }
                            fp.appendChild(clone);
                        });

                        // Position flyout at the button's vertical position
                        const btn = menu.closest('.dropdown-group').querySelector('button');
                        if (btn) {
                            const btnRect = btn.getBoundingClientRect();
                            fp.style.top = btnRect.top + 'px';
                            fp.classList.add('open');
                            document.body.classList.add('flyout-active');

                            // Clamp so flyout doesn't go off-screen bottom
                            const fpRect = fp.getBoundingClientRect();
                            if (fpRect.bottom > window.innerHeight - 10) {
                                fp.style.top = Math.max(10, window.innerHeight - fpRect.height - 10) + 'px';
                            }
                        }

                        activeFlyoutId = id;
                    } else {
                        // Expanded sidebar: normal inline toggle
                        closeFlyout();
                        menu.classList.toggle('hidden');
                    }
                }

                // Close flyout when clicking outside
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.dropdown-group') || e.target.closest('#flyout-popup')) return;
                    closeFlyout();
                });

                // Close flyout on window resize
                window.addEventListener('resize', closeFlyout);
            </script>

            {{-- 2. KATEGORI: LAYANAN & INTERAKSI --}}
            @if($can('surat-online') || $can('pengumuman') || $can('koperasi') || $can('bank-sampah') || $can('umkm') || $can('posyandu') || $can('keamanan') || $can('kegiatan') || $can('rukem') || $can('aspirasi'))
            <div class="pt-8 pb-2 px-4">
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Layanan & Interaksi</p>
            </div>

            {{-- ===== DROPDOWN: Interaksi Warga ===== --}}
            @if($can('surat-online') || $can('pengumuman') || $can('aspirasi'))
            <div class="space-y-1 dropdown-group">
                <button onclick="toggleDropdown('administrasi-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group" data-tooltip="Interaksi Warga">
                    <i class="fa-solid fa-envelope-open-text w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Interaksi Warga</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200"></i>
                </button>
                <div id="administrasi-menu" class="submenu-container hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @if($can('surat-online'))
                    <a href="javascript:void(0)" onclick="switchPage('surat-online', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Surat Online</a>
                    @endif
                    @if($can('pengumuman'))
                    <a href="javascript:void(0)" onclick="switchPage('pengumuman', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Pengumuman</a>
                    @endif
                    @if($can('aspirasi'))
                    <a href="javascript:void(0)" onclick="switchPage('aspirasi', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Aspirasi & Keluhan</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- ===== DROPDOWN: Layanan Warga ===== --}}
            @if($can('koperasi') || $can('bank-sampah') || $can('umkm') || $can('posyandu') || $can('keamanan') || $can('kegiatan') || $can('rukem'))
            <div class="space-y-1 dropdown-group">
                <button onclick="toggleDropdown('layanan-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group" data-tooltip="Layanan Warga">
                    <i class="fa-solid fa-hand-holding-heart w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Layanan Warga</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200"></i>
                </button>
                <div id="layanan-menu" class="submenu-container hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @if($can('koperasi'))
                    <a href="javascript:void(0)" onclick="switchPage('koperasi', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Koperasi Warga</a>
                    @endif
                    @if($can('bank-sampah'))
                    <a href="javascript:void(0)" onclick="switchPage('bank-sampah', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Bank Sampah</a>
                    @endif
                    @if($can('umkm'))
                    <a href="javascript:void(0)" onclick="switchPage('umkm', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">UMKM Warga</a>
                    @endif
                    @if($can('posyandu'))
                    <a href="javascript:void(0)" onclick="switchPage('posyandu', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Posyandu</a>
                    @endif
                    @if($can('keamanan'))
                    <a href="javascript:void(0)" onclick="switchPage('keamanan', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Keamanan & Ronda</a>
                    @endif
                    @if($can('kegiatan'))
                    <a href="javascript:void(0)" onclick="switchPage('kegiatan', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Kegiatan</a>
                    @endif
                    @if($can('rukem'))
                    <a href="javascript:void(0)" onclick="switchPage('rukem', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Rukem (Duka Cita)</a>
                    @endif
                    @if($can('peraturan-sk'))
                    <a href="javascript:void(0)" onclick="switchPage('peraturan-sk', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Peraturan & SK RT/RW</a>
                    @endif
                    @if($can('kerja-bakti'))
                    <a href="javascript:void(0)" onclick="switchPage('kerja-bakti', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Kerja Bakti</a>
                    @endif
                </div>
            </div>
            @endif
            @endif

            {{-- 3. KATEGORI: DATA & ASET/INVENTARIS --}}
            @if($can('data-warga') || $can('data-keluarga') || $can('data-iuran') || $can('data-pengurus-rt') || $can('data-rt') || $can('pengguna') || $can('perangkat-sistem'))
            <div class="pt-8 pb-2 px-4">
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Data & Aset/Inventaris</p>
            </div>

            {{-- ===== DROPDOWN: Data Master ===== --}}
            <div class="space-y-1 dropdown-group">
                <button onclick="toggleDropdown('data-master-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group" data-tooltip="Data Master">
                    <i class="fa-solid fa-database w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Data Master</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200"></i>
                </button>
                <div id="data-master-menu" class="submenu-container hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @if($can('data-warga'))
                    <a href="javascript:void(0)" onclick="switchPage('data-warga', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Data Warga</a>
                    @endif
                    @if($can('data-keluarga'))
                    <a href="javascript:void(0)" onclick="switchPage('data-keluarga', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Data Keluarga</a>
                    @endif
                    @if($can('data-iuran'))
                    <a href="javascript:void(0)" onclick="switchPage('data-iuran', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Data Iuran</a>
                    @endif
                    @if($can('data-pengurus-rt'))
                    <a href="javascript:void(0)" onclick="switchPage('data-pengurus-rt', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Data Pengurus RT & RW</a>
                    @endif
                    @if($can('data-rt'))
                    <a href="javascript:void(0)" onclick="switchPage('data-rt', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Data RT RW</a>
                    @endif
                    @if($can('pengguna'))
                    <a href="javascript:void(0)" onclick="switchPage('pengguna', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Pengguna</a>
                    @endif
                    @if($can('approval-warga'))
                    <a href="javascript:void(0)" onclick="switchPage('approval-warga', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Persetujuan Warga</a>
                    @endif
                    @if($can('perangkat-sistem'))
                    <a href="javascript:void(0)" onclick="switchPage('perangkat-sistem', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Aset/Inventaris</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- 4. KATEGORI: PENGATURAN SISTEM --}}
            @if($can('pengaturan') || $can('backup-restore') || $can('aktivitas-pengguna'))
            <div class="pt-8 pb-2 px-4">
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-[2.5px]">Pengaturan</p>
            </div>

            {{-- ===== DROPDOWN: Sistem ===== --}}
            <div class="space-y-1 dropdown-group">
                <button onclick="toggleDropdown('sistem-menu')" class="menu-link hover:bg-white/5 hover:text-white flex items-center w-full px-4 py-3 text-sm rounded-2xl transition-all group" data-tooltip="Sistem">
                    <i class="fa-solid fa-gears w-6 opacity-50 group-hover:opacity-100"></i>
                    <span class="ml-3 font-semibold flex-1 text-left">Sistem</span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200"></i>
                </button>
                <div id="sistem-menu" class="submenu-container hidden pl-10 space-y-1 mt-1 border-l border-white/10 ml-6">
                    @if($can('pengaturan'))
                    <a href="javascript:void(0)" onclick="switchPage('pengaturan', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Pengaturan</a>
                    @endif
                    @if($can('backup-restore'))
                    <a href="javascript:void(0)" onclick="switchPage('backup-restore', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Backup & Restore</a>
                    @endif
                    @if($can('aktivitas-pengguna'))
                    <a href="javascript:void(0)" onclick="switchPage('aktivitas-pengguna', this)" class="menu-link flex items-center px-4 py-2 text-xs font-medium text-gray-500 hover:text-white transition-all rounded-xl">Aktivitas Pengguna</a>
                    @endif
                </div>
            </div>
            @endif
        </nav>

        <div class="p-6 border-t border-white/5 bg-[#0F172A] sticky bottom-0 shrink-0 flex flex-col gap-3">
            {{-- Tombol logout --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full px-5 py-3 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-[24px] transition-all font-bold text-sm group" data-tooltip="Keluar Akun">
                    <i class="fa-solid fa-power-off mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-14 md:h-20 bg-white border-b border-gray-100 flex items-center justify-between px-4 md:px-10 shrink-0">
            <div class="flex items-center gap-3">
                <!-- Sidebar Toggle Button (Desktop Only) -->
                <button onclick="toggleSidebar()" class="hidden md:flex items-center justify-center px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl mr-2 focus:outline-none transition-all border border-slate-200 shadow-sm gap-2">
                    <i class="fa-solid fa-bars text-sm"></i>
                    <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline">Menu Halaman</span>
                </button>
                <div class="flex flex-col">
                    <h2 id="welcome-message" class="text-sm md:text-xl font-bold text-gray-800 tracking-tight italic line-clamp-1">Halo, {{ Auth::user()->name }} 👋</h2>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        <p class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase tracking-[1px] md:tracking-[1.5px]">Status: Verified Online</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <!-- Theme Toggle Button (Dark / Light Mode) -->
                <button onclick="toggleTheme()" id="theme-toggle-btn" class="bg-gray-50 p-2.5 rounded-xl text-gray-400 hover:text-amber-500 hover:bg-gray-100 transition focus:outline-none border border-gray-100 shadow-sm flex items-center justify-center cursor-pointer" title="Mode Gelap / Terang">
                    <i class="fa-solid fa-moon text-sm" id="theme-toggle-icon"></i>
                </button>

                <!-- Notification Bell -->
                <div class="relative hidden sm:block" id="notification-container">
                    <button onclick="toggleNotificationDropdown(event)" class="bg-gray-50 p-2.5 rounded-xl text-gray-400 hover:text-blue-600 hover:bg-gray-100 transition relative focus:outline-none border border-gray-100 shadow-sm flex items-center justify-center">
                        <i class="fa-solid fa-bell text-sm"></i>
                        <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 text-[8px] font-black flex items-center justify-center hidden">0</span>
                    </button>

                    <!-- Dropdown Panel -->
                    <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 hidden transition-all duration-200 transform scale-95 opacity-0 origin-top-right">
                        <div class="px-4 py-2 border-b border-gray-50 flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-800 uppercase tracking-widest">Notifikasi Aktivitas</span>
                            <span onclick="markNotificationsAsRead(event)" class="text-[9px] text-blue-600 font-extrabold hover:underline cursor-pointer">Tandai Dibaca</span>
                        </div>
                        <div id="notification-list" class="divide-y divide-gray-50 max-h-60 overflow-y-auto">
                            <!-- Dynamic Content -->
                            <div class="p-4 text-center text-gray-400 text-xs italic">
                                Belum ada notifikasi.
                            </div>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-50 text-center">
                            <a href="javascript:void(0)" onclick="goToActivityLog(event)" class="text-[9px] text-blue-600 font-black uppercase tracking-wider hover:underline">Lihat Semua Aktivitas</a>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4 pl-2 sm:pl-6 border-l border-gray-100">
                    <div onclick="switchPage('pengaturan', document.querySelector('.menu-link[onclick*=\'pengaturan\']') || document.querySelector('.bottom-tab-link[onclick*=\'pengaturan\']'))" class="text-right cursor-pointer hover:opacity-80 transition" title="Ke Pengaturan Akun">
                        <p id="profile-name" class="text-xs md:text-sm font-black text-gray-800 leading-none lowercase tracking-tighter truncate max-w-[80px] sm:max-w-none">{{ Auth::user()->name }}</p>
                        <p id="profile-role" class="text-[8px] md:text-[9px] text-blue-600 font-black uppercase mt-0.5 sm:mt-1 italic tracking-widest leading-none">{{ Auth::user()->role }}</p>
                    </div>
                    <img id="profile-avatar" src="{{ Auth::user()->photo ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563EB&color=fff' }}" onclick="openAvatarModal(this.src, '{{ addslashes(Auth::user()->name) }}')" class="h-10 w-10 md:h-11 md:w-11 rounded-2xl shadow-md border-2 border-white bg-gray-50 object-cover cursor-pointer hover:scale-105 active:scale-95 transition-transform" alt="Avatar" title="Lihat Foto Full">
                </div>
            </div>
        </header>

        <main id="main-content" class="flex-1 overflow-y-auto p-3 md:p-10 pb-28 bg-[#F8FAFC]">
            @php
                $currentView = $resolvedView ?? ('admin.partials.' . ($page ?? 'dashboard'));
            @endphp
            @if(view()->exists($currentView))
                @include($currentView)
            @else
                <div class="p-10 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 text-center min-h-[400px] flex flex-col justify-center items-center">
                    <i class="fa-solid fa-code text-5xl text-blue-200 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800">Konten Kosong</h3>
                    <p class="text-gray-500 mt-2">Silakan buat file <code class="text-red-400 bg-red-50 px-2 py-1 rounded">{{ str_replace('.', '/', $currentView) }}.blade.php</code> terlebih dahulu.</p>
                </div>
            @endif
        </main>
    </div>

    <!-- Floating Bottom Navigation Bar (Mobile Only) -->
    <div class="fixed bottom-0 left-0 right-0 z-40 p-3 md:hidden">
        <div class="max-w-md mx-auto bg-white/90 backdrop-blur-md border border-gray-100 rounded-2xl shadow-[0_-8px_30px_rgba(0,0,0,0.08)] flex items-center justify-around py-1.5 px-1">
            <!-- Tab 1: Dashboard -->
            <button onclick="switchPage('dashboard', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-blue-600">
                <i class="fa-solid fa-th-large text-base"></i>
                <span class="text-[9px] font-bold">Beranda</span>
            </button>
            
            <!-- Tab 2: Tagihan -->
            @if($can('tagihan-warga'))
            <button onclick="switchPage('tagihan-warga', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-wallet text-base"></i>
                <span class="text-[9px] font-bold">Tagihan</span>
            </button>
            @endif
            
            <!-- Tab 3: Semua Menu (Opens Sheet) -->
            <button onclick="toggleMobileMenuSheet(true)" class="flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-ellipsis text-base"></i>
                <span class="text-[9px] font-bold">Menu</span>
            </button>

            <!-- Tab 4: Surat Online -->
            @if($can('surat-online'))
            <button onclick="switchPage('surat-online', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-envelope text-base"></i>
                <span class="text-[9px] font-bold">Surat</span>
            </button>
            @endif
            
            <!-- Tab 5: Data Keluarga -->
            @if($can('data-keluarga'))
            <button onclick="switchPage('data-keluarga', this);" class="bottom-tab-link flex flex-col items-center gap-1 text-gray-400 hover:text-blue-600">
                <i class="fa-solid fa-people-roof text-base"></i>
                <span class="text-[9px] font-bold">Keluarga</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Floating Bottom Navigation Sheet (Mobile Only) -->
    <div id="mobile-menu-sheet" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden flex items-end justify-center transition-all duration-300">
        <!-- Sheet Backdrop -->
        <div class="absolute inset-0" onclick="toggleMobileMenuSheet(false)"></div>
        <!-- Sheet Content -->
        <div class="relative w-full max-w-md bg-white rounded-t-2xl p-4 shadow-2xl max-h-[80vh] overflow-y-auto transform translate-y-full transition-transform duration-300">
            <!-- Handle bar -->
            <div class="w-12 h-1 bg-gray-200 rounded-full mx-auto mb-3"></div>
            
            <div class="flex items-center justify-between mb-4 border-b border-gray-50 pb-2.5">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-grip text-xs"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-800 text-sm">Semua Fitur & Layanan</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Role: {{ Auth::user()->role }}</p>
                    </div>
                </div>
                <button onclick="toggleMobileMenuSheet(false)" class="w-7 h-7 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <!-- Section 1: Transaksi & Keuangan -->
                @if($can('tagihan-warga') || $can('qris-va') || $can('pemasukan') || $can('pengeluaran') || $can('transaksi') || $can('kategori'))
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Keuangan & Pembayaran</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @if($can('tagihan-warga'))
                        <button onclick="switchPage('tagihan-warga'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-wallet text-blue-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">{{ (Auth::user()->role == 'Warga') ? 'Tagihan Saya' : 'Tagihan Warga' }}</span>
                        </button>
                        @endif
                        @if($can('qris-va'))
                        <button onclick="switchPage('qris-va'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-qrcode text-blue-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">QRIS / VA</span>
                        </button>
                        @endif
                        @if($can('pemasukan'))
                        <button onclick="switchPage('pemasukan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-arrow-down-long text-emerald-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pemasukan</span>
                        </button>
                        @endif
                        @if($can('pengeluaran'))
                        <button onclick="switchPage('pengeluaran'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-arrow-up-long text-red-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengeluaran</span>
                        </button>
                        @endif
                        @if($can('transaksi'))
                        <button onclick="switchPage('transaksi'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-shuffle text-indigo-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Transaksi</span>
                        </button>
                        @endif
                        @if($can('kategori'))
                        <button onclick="switchPage('kategori'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-folder-tree text-amber-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Kategori</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 2: Layanan & Interaksi -->
                @if($can('surat-online') || $can('pengumuman') || $can('koperasi') || $can('bank-sampah') || $can('umkm') || $can('posyandu') || $can('keamanan') || $can('kegiatan') || $can('rukem') || $can('aspirasi'))
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Layanan & Interaksi Warga</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @if($can('surat-online'))
                        <button onclick="switchPage('surat-online'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-envelope-open-text text-blue-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Surat Online</span>
                        </button>
                        @endif
                        @if($can('pengumuman'))
                        <button onclick="switchPage('pengumuman'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-bullhorn text-amber-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengumuman</span>
                        </button>
                        @endif
                        @if($can('koperasi'))
                        <button onclick="switchPage('koperasi'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-store text-emerald-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Koperasi</span>
                        </button>
                        @endif
                        @if($can('bank-sampah'))
                        <button onclick="switchPage('bank-sampah'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-recycle text-green-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Bank Sampah</span>
                        </button>
                        @endif
                        @if($can('umkm'))
                        <button onclick="switchPage('umkm'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-shop text-rose-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">UMKM</span>
                        </button>
                        @endif
                        @if($can('posyandu'))
                        <button onclick="switchPage('posyandu'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-heart-pulse text-rose-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Posyandu</span>
                        </button>
                        @endif
                        @if($can('keamanan'))
                        <button onclick="switchPage('keamanan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-shield-halved text-slate-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Keamanan</span>
                        </button>
                        @endif
                        @if($can('kegiatan'))
                        <button onclick="switchPage('kegiatan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-calendar-check text-purple-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Kegiatan</span>
                        </button>
                        @endif
                        @if($can('rukem'))
                        <button onclick="switchPage('rukem'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-hands-holding-child text-amber-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Rukem</span>
                        </button>
                        @endif
                        @if($can('aspirasi'))
                        <button onclick="switchPage('aspirasi'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-comment-dots text-sky-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Aspirasi</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 3: Master Data & Laporan -->
                @if($can('data-warga') || $can('data-keluarga') || $can('data-iuran') || $can('data-pengurus-rt') || $can('data-rt') || $can('pengguna') || $can('perangkat-sistem') || $can('laporan-keuangan') || $can('laporan-iuran') || $can('laporan-kas') || $can('export-laporan'))
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Master Data & Laporan</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @if($can('data-warga'))
                        <button onclick="switchPage('data-warga'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-users text-teal-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Data Warga</span>
                        </button>
                        @endif
                        @if($can('data-keluarga'))
                        <button onclick="switchPage('data-keluarga'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-people-roof text-indigo-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Data Keluarga</span>
                        </button>
                        @endif
                        @if($can('data-iuran'))
                        <button onclick="switchPage('data-iuran'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-sack-dollar text-emerald-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Data Iuran</span>
                        </button>
                        @endif
                        @if($can('data-pengurus-rt'))
                        <button onclick="switchPage('data-pengurus-rt'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-user-tie text-blue-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengurus RT/RW</span>
                        </button>
                        @endif
                        @if($can('data-rt'))
                        <button onclick="switchPage('data-rt'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-database text-slate-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Data RT RW</span>
                        </button>
                        @endif
                        @if($can('pengguna'))
                        <button onclick="switchPage('pengguna'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-user-gear text-indigo-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengguna</span>
                        </button>
                        @endif
                        @if($can('approval-warga'))
                        <button onclick="switchPage('approval-warga'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-user-check text-emerald-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Persetujuan</span>
                        </button>
                        @endif
                        @if($can('peraturan-sk'))
                        <button onclick="switchPage('peraturan-sk'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-indigo-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-scale-balanced text-indigo-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Peraturan/SK</span>
                        </button>
                        @endif
                        @if($can('kerja-bakti'))
                        <button onclick="switchPage('kerja-bakti'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-emerald-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-person-digging text-emerald-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Kerja Bakti</span>
                        </button>
                        @endif
                        @if($can('perangkat-sistem'))
                        <button onclick="switchPage('perangkat-sistem'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-boxes-stacked text-amber-700 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Aset/Inventaris</span>
                        </button>
                        @endif
                        @if($can('laporan-keuangan'))
                        <button onclick="switchPage('laporan-keuangan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-chart-line text-blue-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Lap Keuangan</span>
                        </button>
                        @endif
                        @if($can('laporan-iuran'))
                        <button onclick="switchPage('laporan-iuran'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-file-invoice text-emerald-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Lap Iuran</span>
                        </button>
                        @endif
                        @if($can('laporan-kas'))
                        <button onclick="switchPage('laporan-kas'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-vault text-amber-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Lap Arus Kas</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 4: Sistem & Lainnya -->
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2.5">Sistem & Akun</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="switchPage('pengaturan'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-gears text-gray-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Pengaturan</span>
                        </button>
                        @if($can('backup-restore'))
                        <button onclick="switchPage('backup-restore'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-cloud-arrow-up text-cyan-600 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Backup</span>
                        </button>
                        @endif
                        @if($can('aktivitas-pengguna'))
                        <button onclick="switchPage('aktivitas-pengguna'); toggleMobileMenuSheet(false);" class="flex flex-col items-center text-center p-2.5 bg-gray-50 hover:bg-blue-50 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-clock-rotate-left text-slate-500 text-base"></i>
                            <span class="text-[9px] font-bold text-gray-700 truncate w-full">Log Aktivitas</span>
                        </button>
                        @endif
                        <a href="{{ route('welcome') }}" class="flex flex-col items-center justify-center text-center p-2.5 bg-blue-50 hover:bg-blue-100 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-globe text-blue-600 text-base"></i>
                            <span class="text-[9px] font-bold text-blue-700 truncate w-full">Web Publik</span>
                        </a>
                        <button onclick="document.getElementById('logout-form').submit();" class="flex flex-col items-center text-center p-2.5 bg-red-50 hover:bg-red-100 rounded-xl transition gap-1.5 min-w-0">
                            <i class="fa-solid fa-power-off text-red-600 text-base"></i>
                            <span class="text-[9px] font-bold text-red-700 truncate w-full">Keluar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Top Loading Progress Bar Functions
        window.startLoadingBar = function() {
            let bar = document.getElementById('top-loading-bar');
            if (!bar) {
                bar = document.createElement('div');
                bar.id = 'top-loading-bar';
                document.body.appendChild(bar);
            }
            bar.style.opacity = '1';
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = '70%';
            }, 10);
        };

        window.stopLoadingBar = function() {
            const bar = document.getElementById('top-loading-bar');
            if (bar) {
                bar.style.width = '100%';
                setTimeout(() => {
                    bar.style.opacity = '0';
                }, 200);
            }
        };

        window.runGlobalCounterAnimation = function() {
            const counters = document.querySelectorAll('.stat-counter');
            counters.forEach(counter => {
                // Cancel any existing running animation frame to prevent conflict
                if (counter.dataset.animationId) {
                    cancelAnimationFrame(parseInt(counter.dataset.animationId));
                }

                const rawValue = counter.getAttribute('data-value') || '';
                const target = parseFloat(rawValue.replace(/[^0-9.-]+/g, '')) || 0;
                const type = counter.getAttribute('data-type') || 'currency';
                
                // Only animate nominal currency and warga demographics
                if (type !== 'currency' && type !== 'warga') return;
                
                let current = 0;
                const duration = 1200; // 1.2 seconds animation
                const frameRate = 60;
                const totalFrames = Math.round(duration / (1000 / frameRate));
                let frame = 0;
                
                const animate = () => {
                    frame++;
                    const progress = 1 - Math.pow(1 - (frame / totalFrames), 3); // easeOutCubic
                    current = target * progress;
                    
                    if (type === 'currency') {
                        counter.textContent = 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Math.round(current));
                    } else if (type === 'warga') {
                        counter.textContent = Math.round(current) + ' Jiwa';
                    }
                    
                    if (frame < totalFrames) {
                        counter.dataset.animationId = requestAnimationFrame(animate);
                    } else {
                        if (type === 'currency') {
                            counter.textContent = 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(target);
                        } else if (type === 'warga') {
                            counter.textContent = target + ' Jiwa';
                        }
                        delete counter.dataset.animationId;
                    }
                };
                
                counter.dataset.animationId = requestAnimationFrame(animate);
            });
        };

        // ==========================================
        // AUTO-SET TOOLTIPS FOR COLLAPSED SIDEBAR
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Set data-tooltip from span text for all sidebar menu links
            document.querySelectorAll('#sidebar .menu-link').forEach(function(link) {
                const span = link.querySelector('span');
                if (span && span.textContent.trim()) {
                    link.setAttribute('data-tooltip', span.textContent.trim());
                }
            });
            // Set data-tooltip for footer links and buttons (like Logout)
            document.querySelectorAll('#sidebar .sticky.bottom-0 a, #sidebar .sticky.bottom-0 button').forEach(function(el) {
                const span = el.querySelector('span');
                if (span && span.textContent.trim()) {
                    el.setAttribute('data-tooltip', span.textContent.trim());
                }
            });

            // Load bell notifications on page ready
            if (typeof window.fetchNotifications === 'function') {
                window.fetchNotifications();
            }

            // Init Dark / Light Theme state
            if (typeof window.initTheme === 'function') {
                window.initTheme();
            }

            // Trigger global counter animation on initial load
            if (typeof window.runGlobalCounterAnimation === 'function') {
                window.runGlobalCounterAnimation();
            }

            // Render initials avatar if user photo is empty or using ui-avatars fallback
            const avatar = document.getElementById('profile-avatar');
            if (avatar) {
                const src = avatar.getAttribute('src');
                if (!src || src.includes('ui-avatars.com')) {
                    const name = "{{ Auth::user()->name }}";
                    avatar.src = getInitialsAvatar(name);
                }
            }

            // Prefetch all menu pages for instant navigation (zero delay)
            setTimeout(() => {
                const links = document.querySelectorAll('[onclick*="switchPage"]');
                const uniqueUrls = new Set();
                links.forEach(link => {
                    const onclickAttr = link.getAttribute('onclick') || '';
                    const match = onclickAttr.match(/switchPage\(['"]([^'"]+)['"]/);
                    if (match && match[1]) {
                        uniqueUrls.add(match[1]);
                    }
                });
                
                uniqueUrls.forEach(pageName => {
                    if (pageName !== 'dashboard') {
                        const currentMode = window.innerWidth < 768 ? 'mobile' : 'desktop';
                        fetch(`/${pageName}?mode=${currentMode}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => {
                            if (response.ok) return response.text();
                        })
                        .then(html => {
                            if (html) window.pageCache[pageName] = { html: html, mode: currentMode };
                        })
                        .catch(() => {});
                    }
                });

                // Add mouseenter and touchstart listener to prefetch hovered/touched links even faster
                links.forEach(link => {
                    const startPrefetch = () => {
                        const onclickAttr = link.getAttribute('onclick') || '';
                        const match = onclickAttr.match(/switchPage\(['"]([^'"]+)['"]/);
                        if (match && match[1]) {
                            const pageName = match[1];
                            const currentMode = window.innerWidth < 768 ? 'mobile' : 'desktop';
                            if (!window.pageCache[pageName] || window.pageCache[pageName].mode !== currentMode) {
                                fetch(`/${pageName}?mode=${currentMode}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                .then(response => {
                                    if (response.ok) return response.text();
                                })
                                .then(html => {
                                    if (html) window.pageCache[pageName] = { html: html, mode: currentMode };
                                })
                                .catch(() => {});
                            }
                        }
                    };
                    link.addEventListener('mouseenter', startPrefetch);
                    link.addEventListener('touchstart', startPrefetch, { passive: true });
                });
            }, 1000);

            // Cache the initial rendered page content
            const initialPage = '{{ $page }}';
            const mainContent = document.getElementById('main-content');
            if (mainContent && initialPage) {
                const currentMode = window.innerWidth < 768 ? 'mobile' : 'desktop';
                window.pageCache[initialPage] = { html: mainContent.innerHTML, mode: currentMode };
            }
        });

        // ==========================================
        // 0. FUNGSI TOGGLE SIDEBAR MOBILE & DESKTOP
        // ==========================================
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            // Close all open flyout submenus
            if (typeof closeFlyout === 'function') closeFlyout();

            if (window.innerWidth >= 768) {
                // Desktop: Toggle collapsed state
                sidebar.classList.toggle('sidebar-collapsed');
            } else {
                // Mobile: Toggle slide-in drawer state
                const backdrop = document.getElementById('sidebar-backdrop');
                const isClosed = sidebar.classList.contains('-translate-x-full');
                if (isClosed) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    if (backdrop) backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    if (backdrop) backdrop.classList.add('hidden');
                }
            }
        };

        // ==========================================
        // 0.5. FUNGSI TOGGLE MOBILE MENU SHEET
        // ==========================================
        window.toggleMobileMenuSheet = function(isOpen) {
            const sheet = document.getElementById('mobile-menu-sheet');
            if(!sheet) return;
            const content = sheet.querySelector('.relative');
            if (isOpen) {
                sheet.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('translate-y-full');
                    content.classList.add('translate-y-0');
                }, 10);
            } else {
                content.classList.remove('translate-y-0');
                content.classList.add('translate-y-full');
                setTimeout(() => {
                    sheet.classList.add('hidden');
                }, 300);
            }
        };

        // ==========================================
        // 1. FUNGSI NAVIGASI AJAX
        // ==========================================
        function executeScripts(container) {
            const scripts = Array.from(container.querySelectorAll('script'));
            scripts.forEach(oldScript => {
                try {
                    if (oldScript.src) {
                        if (!document.querySelector(`script[src="${oldScript.src}"]`)) {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            document.head.appendChild(newScript);
                        }
                    } else {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.text = oldScript.innerHTML;
                        document.body.appendChild(newScript);
                        document.body.removeChild(newScript);
                    }
                } catch (scriptError) {
                    console.warn('Inline script execution warning:', scriptError);
                }
                oldScript.remove();
            });
        }

        // Global page cache for fast navigation (SWR - Stale While Revalidate)
        function getInitialsAvatar(name) {
            const cleanName = (name || 'User').trim();
            const parts = cleanName.split(/\s+/);
            let initials = '';
            if (parts.length > 0) {
                initials += parts[0][0];
                if (parts.length > 1) {
                    initials += parts[parts.length - 1][0];
                }
            }
            initials = initials.toUpperCase();
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><defs><linearGradient id="avatar-grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#2563eb" /><stop offset="100%" stop-color="#1d4ed8" /></linearGradient></defs><rect width="100" height="100" rx="40" fill="url(#avatar-grad)" /><text x="50%" y="54%" font-family="'Plus Jakarta Sans', sans-serif" font-weight="800" font-size="38" fill="#ffffff" text-anchor="middle" dominant-baseline="middle">${initials}</text></svg>`;
            return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg.trim());
        }

        window.updateUserProfileUI = function(user) {
            if (!user) return;
            
            // Update welcome message
            const welcomeMsg = document.getElementById('welcome-message');
            if (welcomeMsg) {
                const expectedWelcome = `Halo, ${user.name} 👋`;
                if (welcomeMsg.innerText !== expectedWelcome) {
                    welcomeMsg.innerText = expectedWelcome;
                }
            }
            
            // Update profile name
            const profileName = document.getElementById('profile-name');
            if (profileName) {
                if (profileName.innerText !== user.name) {
                    profileName.innerText = user.name;
                }
            }
            
            // Update profile role
            const profileRole = document.getElementById('profile-role');
            if (profileRole) {
                if (profileRole.innerText !== user.role) {
                    profileRole.innerText = user.role;
                }
            }
            
            // Update profile avatar source without triggering a reload if unchanged
            const profileAvatar = document.getElementById('profile-avatar');
            if (profileAvatar) {
                const targetSrc = user.photo || getInitialsAvatar(user.name);
                if (profileAvatar.getAttribute('src') !== targetSrc) {
                    profileAvatar.src = targetSrc;
                }
            }
        };

        // Global page cache for fast navigation (SWR - Stale While Revalidate)
        window.pageCache = window.pageCache || {};

        // Invalidate cache for a page (+ dashboard since stats may change)
        window.invalidatePageCache = function(pageName) {
            delete window.pageCache[pageName];
            delete window.pageCache['dashboard'];
        };

        function switchPage(pageName, element) {
            // Auto collapse/hide sidebar on page switch (both mobile and desktop)
            try {
                // Close all open flyout submenus
                if (typeof closeFlyout === 'function') closeFlyout();

                // Clear any running realtime intervals
                if (window.realtimeInterval) {
                    clearInterval(window.realtimeInterval);
                    window.realtimeInterval = null;
                }

                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.add('sidebar-collapsed');
                    } else {
                        sidebar.classList.remove('translate-x-0');
                        sidebar.classList.add('-translate-x-full');
                        const backdrop = document.getElementById('sidebar-backdrop');
                        if (backdrop) backdrop.classList.add('hidden');
                    }
                }
            } catch(e) {}

            const mainContent = document.getElementById('main-content');
            if (!mainContent) return;

            // Reset desktop sidebar links
            document.querySelectorAll('.menu-link').forEach(item => {
                item.classList.remove('menu-active', 'text-white');
                item.classList.add('hover:bg-white/5', 'hover:text-white');
            });

            // Reset bottom nav tabs active state
            document.querySelectorAll('.bottom-tab-link').forEach(item => {
                item.classList.remove('text-blue-600');
                item.classList.add('text-gray-400');
            });

            // Find and activate the correct sidebar item
            const sidebarElement = element && element.classList.contains('menu-link') 
                ? element 
                : (document.querySelector(`.menu-link[onclick*="'${pageName}'"]`) || document.querySelector(`.menu-link[onclick*='"${pageName}"']`));
            if (sidebarElement) {
                sidebarElement.classList.add('menu-active', 'text-white');
                sidebarElement.classList.remove('hover:bg-white/5', 'hover:text-white');
            }

            // Find and activate the correct bottom nav tab
            const bottomTabElement = element && element.classList.contains('bottom-tab-link')
                ? element
                : (document.querySelector(`.bottom-tab-link[onclick*="'${pageName}'"]`) || document.querySelector(`.bottom-tab-link[onclick*='"${pageName}"']`));
            if (bottomTabElement) {
                bottomTabElement.classList.add('text-blue-600');
                bottomTabElement.classList.remove('text-gray-400');
            }

            // Helper to render and trigger page logic
            function renderPage(html) {
                mainContent.innerHTML = html;
                try {
                    executeScripts(mainContent);
                } catch (scriptErr) {
                    console.warn('Script execution notice:', scriptErr);
                }
                
                // Trigger count animation for any loaded page
                if (typeof window.runGlobalCounterAnimation === 'function') {
                    window.runGlobalCounterAnimation();
                }

                // Pemicu Grafik Dashboard
                if (pageName === 'dashboard' && typeof window.renderDashboard === 'function') {
                    try {
                        window.renderDashboard();
                    } catch (dashErr) {
                        console.warn('Dashboard render notice:', dashErr);
                    }
                }
            }

            // SWR: Stale-While-Revalidate Caching Logic
            // Skip cache if reloading the SAME page (mutation refresh from save/delete)
            const currentPage = window.location.pathname.replace(/^\//, '');
            const isSamePageReload = (currentPage === pageName);
            
            const currentMode = window.innerWidth < 768 ? 'mobile' : 'desktop';
            const cachedData = isSamePageReload ? null : window.pageCache[pageName];
            const cachedHtml = (cachedData && cachedData.mode === currentMode) ? cachedData.html : null;

            if (cachedHtml) {
                // Instant load from cache
                renderPage(cachedHtml);
                window.history.pushState({}, '', `/${pageName}`);
            } else {
                if (typeof window.startLoadingBar === 'function') window.startLoadingBar();
                // Cache miss: load in background without blocking screen
                fetch(`/${pageName}?mode=${currentMode}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(async response => {
                    if (response.status === 401) {
                        window.location.href = '/login';
                        return;
                    }
                    const text = await response.text();
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: Halaman tidak ditemukan atau terjadi kesalahan server.`);
                    }
                    return text;
                })
                .then(html => {
                    if (typeof window.stopLoadingBar === 'function') window.stopLoadingBar();
                    if (!html) return;
                    window.pageCache[pageName] = { html: html, mode: currentMode };
                    renderPage(html);
                    window.history.pushState({}, '', `/${pageName}`);
                })
                .catch(error => {
                    if (typeof window.stopLoadingBar === 'function') window.stopLoadingBar();
                    mainContent.innerHTML = `
                        <div class="p-10 bg-white rounded-[2.5rem] border border-red-100 shadow-sm text-center min-h-[400px] flex flex-col justify-center items-center">
                            <i class="fa-solid fa-triangle-exclamation text-5xl text-red-400 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-800">Gagal Memuat Halaman</h3>
                            <p class="text-gray-500 mt-2 max-w-lg">${error.message || 'Pastikan koneksi lancar dan route halaman sudah terdaftar.'}</p>
                            <button onclick="switchPage('${pageName}', document.querySelector('.bottom-tab-link[onclick*=\\'${pageName}\\']') || document.querySelector('.menu-active'))" class="mt-4 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-blue-500/20">
                                Coba Lagi
                            </button>
                        </div>`;
                });
            }

            // Refresh bell notifications on page navigation
            if (typeof window.fetchNotifications === 'function') {
                window.fetchNotifications();
            }
        }

        // ==========================================
        // 2. FUNGSI TOGGLE PASSWORD PENGATURAN
        // ==========================================
        window.togglePasswordPengaturan = function() {
            let inputPass = document.getElementById('input-password');
            let iconMata = document.getElementById('icon-mata');
            if(inputPass && iconMata) {
                if (inputPass.type === "password") {
                    inputPass.type = "text";
                    iconMata.classList.remove('fa-eye');
                    iconMata.classList.add('fa-eye-slash');
                } else {
                    inputPass.type = "password";
                    iconMata.classList.remove('fa-eye-slash');
                    iconMata.classList.add('fa-eye');
                }
            }
        };

        // ==========================================
        // 3. FUNGSI AJAX SIMPAN DATA UMUM
        // ==========================================
        function simpanDataUmum(event, formId, pageToReload) {
            event.preventDefault();

            let form = document.getElementById(formId);
            if (!form) return;
            if (!form.reportValidity()) {
                return;
            }

            let formData = new FormData(form);
            let submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('button[onclick*="simpanDataUmum"]');
            let originalBtnText = submitBtn ? submitBtn.innerHTML : 'Simpan';
            let targetUrl = form.getAttribute('action');

            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;
            }

            fetch(targetUrl, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                let modal = form.closest('[id^="modal-"]');
                if (modal) {
                    modal.classList.add('hidden');
                }

                form.reset();
                alert(data.message || 'Data berhasil disimpan!');

                if (pageToReload === 'pengaturan') {
                    if (data.user) {
                        window.updateUserProfileUI(data.user);
                    }
                    window.invalidatePageCache('pengaturan');
                    switchPage('pengaturan', document.querySelector(`.menu-link[onclick*='pengaturan']`) || document.querySelector(`.bottom-tab-link[onclick*='pengaturan']`));
                } else {
                    // Invalidate cache so switchPage forces a fresh fetch
                    window.invalidatePageCache(pageToReload);
                    switchPage(pageToReload, document.querySelector(`.menu-link[onclick*='${pageToReload}']`) || document.querySelector(`.bottom-tab-link[onclick*='${pageToReload}']`));
                }
            })
            .catch(error => {
                if (error.errors) {
                    let messages = Object.values(error.errors).flat().join('\n');
                    alert("Gagal menyimpan:\n" + messages);
                } else {
                    alert(error.message || "Gagal terhubung ke server / Terjadi kesalahan sistem.");
                }
                console.error(error);
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            });
        }

        // ==========================================
        // NOTIFIKASI AKTIVITAS PENGGUNA (BELL NOTIFICATION)
        // ==========================================
        window.latestActivityTimestamp = null;

        window.fetchNotifications = function() {
            let url = "{{ route('aktivitas.data') }}?_t=" + new Date().getTime();
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                if (!data || data.length === 0) return;
                
                const lastViewed = localStorage.getItem('last_viewed_activity') || '';
                let unreadCount = 0;
                let listHtml = '';
                
                data.slice(0, 5).forEach((item) => {
                    const isUnread = lastViewed ? (new Date(item.created_at) > new Date(lastViewed)) : true;
                    if (isUnread) unreadCount++;
                    
                    // Determin warna badge tindakan
                    let badgeStyle = 'bg-blue-50 text-blue-600 border-blue-100';
                    let act = (item.action || '').toUpperCase();
                    if (act.includes('BUAT') || act.includes('TAMBAH')) {
                        badgeStyle = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                    } else if (act.includes('UPDATE') || act.includes('RESPON') || act.includes('SETTING') || act.includes('SINKRONISASI')) {
                        badgeStyle = 'bg-amber-50 text-amber-600 border-amber-100';
                    } else if (act.includes('HAPUS') || act.includes('BERSIH') || act.includes('DELETE')) {
                        badgeStyle = 'bg-rose-50 text-rose-600 border-rose-100';
                    } else if (act.includes('LOGIN')) {
                        badgeStyle = 'bg-indigo-50 text-indigo-600 border-indigo-100';
                    }

                    listHtml += `
                    <div class="p-3 hover:bg-gray-50/50 transition-all duration-150 flex items-start gap-2.5 ${isUnread ? 'bg-blue-50/10' : ''}">
                        <img src="${item.photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name || 'User')}" class="w-7 h-7 rounded-full border border-gray-200 object-cover flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-1">
                                <span class="font-bold text-gray-800 text-[10px] truncate">${item.name || 'User'}</span>
                                <span class="text-[8px] text-gray-400 whitespace-nowrap">${item.waktu_berlalu || ''}</span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="px-1 py-0.2 rounded text-[7px] font-bold border ${badgeStyle} shrink-0">${item.action || '-'}</span>
                                <span class="text-[9px] text-gray-500 truncate">${item.description || ''}</span>
                            </div>
                        </div>
                    </div>`;
                });
                
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    if (unreadCount > 0) {
                        badge.innerText = unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                
                const listContainer = document.getElementById('notification-list');
                if (listContainer) {
                    listContainer.innerHTML = listHtml;
                }
                
                // Simpan timestamp terbaru
                if (data[0] && data[0].created_at) {
                    window.latestActivityTimestamp = data[0].created_at;
                }
            }).catch(err => console.warn('Gagal memuat notifikasi:', err));
        };

        window.toggleNotificationDropdown = function(event) {
            if (event) event.stopPropagation();
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown) {
                const isHidden = dropdown.classList.contains('hidden');
                if (isHidden) {
                    dropdown.classList.remove('hidden');
                    setTimeout(() => {
                        dropdown.classList.remove('scale-95', 'opacity-0');
                        dropdown.classList.add('scale-100', 'opacity-100');
                    }, 10);
                    
                    // Tandai dibaca saat membuka panel dropdown
                    if (window.latestActivityTimestamp) {
                        localStorage.setItem('last_viewed_activity', window.latestActivityTimestamp);
                        const badge = document.getElementById('notification-badge');
                        if (badge) badge.classList.add('hidden');
                    }
                } else {
                    dropdown.classList.remove('scale-100', 'opacity-100');
                    dropdown.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                    }, 200);
                }
            }
        };

        window.markNotificationsAsRead = function(event) {
            if (event) event.stopPropagation();
            if (window.latestActivityTimestamp) {
                localStorage.setItem('last_viewed_activity', window.latestActivityTimestamp);
                window.fetchNotifications();
            }
        };

        window.goToActivityLog = function(event) {
            if (event) event.stopPropagation();
            window.toggleNotificationDropdown();
            
            const targetLink = document.querySelector('.menu-link[onclick*="aktivitas-pengguna"]');
            if (typeof window.invalidatePageCache === 'function') { window.invalidatePageCache('aktivitas-pengguna'); }
            switchPage('aktivitas-pengguna', targetLink);
        };

        // Tutup notifikasi jika klik di luar
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown && !dropdown.classList.contains('hidden') && !event.target.closest('#notification-container')) {
                dropdown.classList.remove('scale-100', 'opacity-100');
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        });

        window.openAvatarModal = function(src, name) {
            const modal = document.getElementById('modal-full-avatar');
            const img = document.getElementById('full-avatar-img');
            const nameText = document.getElementById('full-avatar-name');
            if (modal && img) {
                img.src = src;
                if (nameText) nameText.innerText = name || 'Foto Profil';
                modal.classList.remove('hidden');
            }
        };

        window.closeAvatarModal = function() {
            const modal = document.getElementById('modal-full-avatar');
            if (modal) modal.classList.add('hidden');
        };

        // ==========================================
        // FITUR MODE GELAP & TERANG (DARK / LIGHT MODE)
        // ==========================================
        window.initTheme = function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = savedTheme ? (savedTheme === 'dark') : prefersDark;
            applyTheme(isDark);
        };

        window.toggleTheme = function() {
            const isCurrentDark = document.documentElement.classList.contains('dark');
            const newDarkState = !isCurrentDark;
            applyTheme(newDarkState);
            localStorage.setItem('theme', newDarkState ? 'dark' : 'light');
        };

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
            
            if (typeof window.renderDashboard === 'function') {
                setTimeout(window.renderDashboard, 100);
            }
            if (typeof window.renderDashboardMobile === 'function') {
                setTimeout(window.renderDashboardMobile, 100);
            }
            if (typeof window.renderDemografiChart === 'function') {
                setTimeout(window.renderDemografiChart, 100);
            }
            if (typeof window.renderDemografiChartMobile === 'function') {
                setTimeout(window.renderDemografiChartMobile, 100);
            }
        }

        window.showDropdown = function(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('hidden');
        };

        window.filterCustomDropdown = function(inputId, dropdownId) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            if (!input || !dropdown) return;
            const filter = input.value.toLowerCase();
            dropdown.classList.remove('hidden');
            
            const items = dropdown.querySelectorAll('.dropdown-item, .dropdown-item-m');
            items.forEach(item => {
                const txt = item.textContent || item.innerText;
                if (txt.toLowerCase().includes(filter)) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        };
    </script>

    <!-- Modal Full View Avatar -->
    <div id="modal-full-avatar" class="fixed inset-0 bg-black/75 backdrop-blur-md z-[100] flex flex-col items-center justify-center p-4 hidden transition-all duration-300" onclick="closeAvatarModal()">
        <div class="relative max-w-md w-full flex flex-col items-center justify-center animate-in fade-in zoom-in-95 duration-200" onclick="event.stopPropagation()">
            <button onclick="closeAvatarModal()" class="absolute -top-12 right-0 text-white hover:text-red-400 text-xl font-bold bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-md transition shadow-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="p-2 bg-white/10 rounded-3xl backdrop-blur-md shadow-2xl border border-white/20">
                <img id="full-avatar-img" src="" class="max-w-full max-h-[75vh] rounded-2xl object-cover bg-white shadow-inner">
            </div>
            <p id="full-avatar-name" class="mt-3 text-white font-extrabold text-sm tracking-wide bg-black/40 px-5 py-1.5 rounded-full backdrop-blur-md border border-white/10 shadow-md text-center"></p>
        </div>
    </div>
</body>
</html>
