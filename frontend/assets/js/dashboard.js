/* dashboard.js - Script for SPA Dashboard Admin Panel */

let currentUser = null;
window.pageCache = {};

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

// ==========================================
// 1. INLINE CUSTOM ALERT MODAL OVERRIDE
// ==========================================
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
            html.dark .guyub-modal-card {
                background: #1e293b !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
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
                letter-spacing: -0.02em;
            }
            html.dark .guyub-modal-title {
                color: #ffffff !important;
            }
            .guyub-modal-message {
                margin: 0.65rem 0 0 0;
                font-size: 11px;
                font-weight: 600;
                color: #64748b;
                line-height: 1.55;
            }
            html.dark .guyub-modal-message {
                color: #94a3b8 !important;
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

// ==========================================
// 2. SESSION CHECKER & RBAC FILTER
// ==========================================
document.addEventListener("DOMContentLoaded", () => {
    // Show top loading progress bar immediately to match backend dashboard loading
    showLoadingBar();

    // Apply theme
    const isDark = localStorage.getItem('theme') === 'dark';
    applyTheme(isDark);

    // Initialize global CSRF token from meta tag if present
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        window.csrfToken = metaTag.getAttribute('content');
    }

    // Render cached user session instantly in the header to eliminate "..." and "Halo..." placeholders
    const cachedUser = localStorage.getItem('guyub_user');
    if (cachedUser) {
        try {
            const user = JSON.parse(cachedUser);
            window.updateUserProfileUI(user);
            applyRoleBasedAccess(user.role);
        } catch (e) {
            console.error("Error loading cached user:", e);
        }
    }

    // Render cached last active page instantly (SWR) from localStorage to eliminate all loading screens on boot
    const mainContent = document.getElementById('main-content');
    const lastActivePage = sessionStorage.getItem('guyub_active_page') || 'dashboard';
    
    if (mainContent) {
        const cachedHTML = localStorage.getItem('guyub_cache_' + lastActivePage);
        if (cachedHTML) {
            mainContent.innerHTML = cachedHTML;
            executeScripts(mainContent);
            if (lastActivePage === 'dashboard' && typeof window.renderDashboard === 'function') {
                window.renderDashboard();
            }
            if (typeof window.runGlobalCounterAnimation === 'function') {
                window.runGlobalCounterAnimation();
            }
        } else {
            mainContent.innerHTML = `
                <div class="flex flex-col items-center justify-center min-h-[400px] w-full text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-blue-500 mb-3"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">Memuat Halaman...</p>
                </div>
            `;
        }
    }

    const checkSession = (url) => {
        return fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => {
            if (!res.ok) throw new Error("Unauthorized");
            return res.json();
        });
    };

    const mainSessionApi = `${CONFIG.API_BASE_URL}/api/auth/user`;
    const fallbackSessionApi = `${CONFIG.API_FALLBACK_URL}/api/auth/user`;

    checkSession(mainSessionApi)
        .catch(() => checkSession(fallbackSessionApi))
        .then(data => {
            currentUser = data.user;
            localStorage.setItem('guyub_user', JSON.stringify(currentUser)); // Save user session to cache
            window.updateUserProfileUI(currentUser);
            applyRoleBasedAccess(currentUser.role);
            
            // Set user name inside meta and set window.csrfToken globally
            const metaCsrf = document.querySelector('meta[name="csrf-token"]');
            if (data.csrf_token) {
                window.csrfToken = data.csrf_token;
                if (metaCsrf) {
                    metaCsrf.content = data.csrf_token;
                }
            } else if (metaCsrf) {
                window.csrfToken = metaCsrf.getAttribute('content');
            }

            // Start fetching notifications periodically
            window.fetchNotifications();
            setInterval(window.fetchNotifications, 20000);

            // Load default page or last active page from sessionStorage
            const lastActivePage = sessionStorage.getItem('guyub_active_page') || 'dashboard';
            const activeLink = document.querySelector(`[data-page="${lastActivePage}"]`) || document.querySelector('[data-page="dashboard"]');
            switchPage(lastActivePage, activeLink);
            hideLoadingBar();
        })
        .catch(err => {
            hideLoadingBar();
            console.error("Session verification failed:", err);
            localStorage.removeItem('guyub_user');
            for (let i = localStorage.length - 1; i >= 0; i--) {
                const key = localStorage.key(i);
                if (key && key.startsWith('guyub_cache_')) {
                    localStorage.removeItem(key);
                }
            }
            sessionStorage.removeItem('guyub_active_page');
            window.location.href = 'login.html';
        });
});

// ==========================================
// 3. APPLY ROLE-BASED ACCESS CONTROL (RBAC) ON SIDEBAR
// ==========================================
const menuMeta = {
    'dashboard': { icon: 'fa-solid fa-th-large', label: 'Dashboard' },
    'pemasukan': { icon: 'fa-solid fa-circle-arrow-down', label: 'Pemasukan' },
    'pengeluaran': { icon: 'fa-solid fa-circle-arrow-up', label: 'Pengeluaran' },
    'transaksi': { icon: 'fa-solid fa-shuffle', label: 'Transaksi' },
    'kategori': { icon: 'fa-solid fa-folder-tree', label: 'Kategori' },
    'surat-online': { icon: 'fa-solid fa-envelope', label: 'Surat Online' },
    'pengumuman': { icon: 'fa-solid fa-bullhorn', label: 'Pengumuman' },
    'koperasi': { icon: 'fa-solid fa-store', label: 'Koperasi' },
    'bank-sampah': { icon: 'fa-solid fa-recycle', label: 'Bank Sampah' },
    'umkm': { icon: 'fa-solid fa-shop', label: 'UMKM Warga' },
    'posyandu': { icon: 'fa-solid fa-heart-pulse', label: 'Posyandu' },
    'keamanan': { icon: 'fa-solid fa-shield-halved', label: 'Keamanan' },
    'kegiatan': { icon: 'fa-solid fa-calendar-check', label: 'Kegiatan' },
    'rukem': { icon: 'fa-solid fa-hands-holding-child', label: 'Rukem' },
    'peraturan-sk': { icon: 'fa-solid fa-file-signature', label: 'Peraturan & SK' },
    'kerja-bakti': { icon: 'fa-solid fa-people-carry-box', label: 'Kerja Bakti' },
    'aspirasi': { icon: 'fa-solid fa-comment-dots', label: 'Aspirasi' },
    'data-warga': { icon: 'fa-solid fa-users', label: 'Data Warga' },
    'data-keluarga': { icon: 'fa-solid fa-people-roof', label: 'Data Keluarga' },
    'data-iuran': { icon: 'fa-solid fa-wallet', label: 'Data Iuran' },
    'data-pengurus-rt': { icon: 'fa-solid fa-user-tie', label: 'Data Pengurus' },
    'data-rt': { icon: 'fa-solid fa-building-user', label: 'Data RT RW' },
    'pengguna': { icon: 'fa-solid fa-user-gear', label: 'Pengguna' },
    'approval-warga': { icon: 'fa-solid fa-user-check', label: 'Persetujuan' },
    'perangkat-sistem': { icon: 'fa-solid fa-display', label: 'Aset/Inventaris' },
    'pengaturan': { icon: 'fa-solid fa-gears', label: 'Pengaturan' },
    'backup-restore': { icon: 'fa-solid fa-cloud-arrow-up', label: 'Backup & Restore' },
    'aktivitas-pengguna': { icon: 'fa-solid fa-clock-rotate-left', label: 'Aktivitas' },
    'qris-va': { icon: 'fa-solid fa-qrcode', label: 'QRIS & Rekening' },
    'laporan-keuangan': { icon: 'fa-solid fa-chart-pie', label: 'Lap. Keuangan' },
    'laporan-iuran': { icon: 'fa-solid fa-file-invoice-dollar', label: 'Lap. Iuran' },
    'laporan-kas': { icon: 'fa-solid fa-vault', label: 'Lap. Kas' },
    'laporan-koperasi': { icon: 'fa-solid fa-basket-shopping', label: 'Lap. Koperasi' },
    'export-laporan': { icon: 'fa-solid fa-file-export', label: 'Export Laporan' }
};

function applyRoleBasedAccess(role) {
    const allPages = [
        'dashboard', 'pemasukan', 'pengeluaran', 'transaksi', 'kategori',
        'data-warga', 'data-keluarga', 'data-iuran', 'data-pengurus-rt', 'data-rt', 'pengguna', 'perangkat-sistem',
        'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'laporan-koperasi', 'pengaturan', 'backup-restore', 'aktivitas-pengguna',
        'tagihan-warga', 'pembayaran-online', 'riwayat-gateway', 'qris-va',
        'surat-online', 'pengumuman',
        'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi', 'approval-warga',
        'peraturan-sk', 'kerja-bakti'
    ];

    const aksesHalaman = {
        'Super Admin': allPages,
        'RW': [
            'dashboard', 'data-warga', 'data-keluarga', 'data-pengurus-rt', 'data-rt', 'peraturan-sk', 'kerja-bakti',
            'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'laporan-koperasi', 'pengumuman', 'aspirasi', 'keamanan', 'kegiatan',
            'posyandu', 'umkm', 'bank-sampah', 'koperasi', 'rukem', 'approval-warga'
        ],
        'Sekretaris RW': [
            'dashboard', 'data-warga', 'data-keluarga', 'data-pengurus-rt', 'peraturan-sk', 'kerja-bakti',
            'surat-online', 'pengumuman', 'aspirasi', 'approval-warga', 'kegiatan', 'laporan-koperasi'
        ],
        'Bendahara RW': [
            'dashboard', 'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'laporan-keuangan', 'laporan-iuran',
            'laporan-kas', 'export-laporan', 'laporan-koperasi', 'tagihan-warga', 'pembayaran-online', 'riwayat-gateway', 'qris-va', 'koperasi'
        ],
        'RT': [
            'dashboard', 'data-warga', 'data-keluarga', 'data-pengurus-rt', 'data-rt', 'perangkat-sistem',
            'surat-online', 'pengumuman', 'tagihan-warga', 'pembayaran-online', 'riwayat-gateway', 'qris-va',
            'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'laporan-koperasi', 'pengaturan',
            'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi',
            'approval-warga', 'peraturan-sk', 'kerja-bakti'
        ],
        'Sekretaris RT': [
            'dashboard', 'data-warga', 'data-keluarga', 'data-pengurus-rt', 'perangkat-sistem',
            'surat-online', 'pengumuman', 'tagihan-warga', 'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'laporan-koperasi', 'pengaturan',
            'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi',
            'approval-warga', 'peraturan-sk', 'kerja-bakti'
        ],
        'Bendahara RT': [
            'dashboard', 'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'data-keluarga', 'data-iuran',
            'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'laporan-koperasi', 'export-laporan',
            'tagihan-warga', 'pembayaran-online', 'riwayat-gateway', 'qris-va',
            'surat-online', 'pengumuman', 'pengaturan',
            'koperasi', 'bank-sampah', 'rukem', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'aspirasi',
            'peraturan-sk', 'kerja-bakti'
        ],
        'Warga': [
            'dashboard', 'data-keluarga', 'data-pengurus-rt', 'surat-online', 'pengumuman',
            'tagihan-warga', 'pembayaran-online', 'pengaturan',
            'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi',
            'peraturan-sk', 'kerja-bakti', 'laporan-koperasi'
        ]
    };

    const allowed = aksesHalaman[role] || [];

    // Filter desktop sidebar links
    document.querySelectorAll('.menu-link[data-page], .submenu-container a[data-page]').forEach(link => {
        const page = link.getAttribute('data-page');
        if (!allowed.includes(page)) {
            link.style.display = 'none';
        } else {
            link.style.display = '';
            
            // Re-label dynamically
            if (page === 'tagihan-warga') {
                link.innerText = (role === 'Warga') ? 'Tagihan Saya' : ((role === 'RT') ? 'Daftar Tagihan Warga' : 'Kelola Tagihan');
            } else if (page === 'qris-va') {
                link.innerText = (role === 'Warga') ? 'Rekening & QRIS RT' : ((role === 'RT') ? 'Daftar Rekening & QRIS' : 'Rekening & QRIS');
            }
        }
    });

    // Filter dropdown groups
    document.querySelectorAll('.parent-menu').forEach(parent => {
        const pages = parent.getAttribute('data-pages').split(',');
        const hasAccess = pages.some(p => allowed.includes(p));
        if (!hasAccess) {
            parent.style.display = 'none';
            if (parent.nextElementSibling && parent.nextElementSibling.classList.contains('submenu-container')) {
                parent.nextElementSibling.style.display = 'none';
            }
        } else {
            parent.style.display = '';
        }
    });

    // Populate Mobile Floating Bottom Tabs
    const tabTagihan = document.getElementById('mobile-tab-tagihan');
    const tabSurat = document.getElementById('mobile-tab-surat');
    const tabKeluarga = document.getElementById('mobile-tab-keluarga');

    if (tabTagihan) tabTagihan.style.display = allowed.includes('tagihan-warga') ? '' : 'none';
    if (tabSurat) tabSurat.style.display = allowed.includes('surat-online') ? '' : 'none';
    if (tabKeluarga) tabKeluarga.style.display = allowed.includes('data-keluarga') ? '' : 'none';

    // Populate Mobile Sheet Menu Content Grid
    const sheetContainer = document.getElementById('mobile-sheet-content');
    if (sheetContainer) {
        let sheetHtml = `<h4 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-4">Semua Fitur Layanan</h4>`;
        sheetHtml += `<div class="grid grid-cols-3 gap-3">`;

        allPages.forEach(page => {
            if (allowed.includes(page) && page !== 'dashboard') {
                const meta = menuMeta[page] || { icon: 'fa-solid fa-circle', label: page };
                sheetHtml += `
                    <button onclick="switchPage('${page}', document.querySelector('[data-page=${page}]')); toggleMobileMenuSheet(false);" class="flex flex-col items-center justify-center p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100 transition border border-slate-100 text-slate-700 cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-1.5 text-sm">
                            <i class="${meta.icon}"></i>
                        </div>
                        <span class="text-[9px] font-bold text-center truncate w-full">${meta.label}</span>
                    </button>
                `;
            }
        });

        sheetHtml += `</div>`;
        sheetContainer.innerHTML = sheetHtml;
    }
}

// ==========================================
// 4. TOP LOADING PROGRESS BAR
// ==========================================
function showLoadingBar() {
    const bar = document.getElementById('top-loading-bar');
    if (bar) {
        bar.style.opacity = '1';
        bar.style.width = '30%';
        setTimeout(() => {
            if (bar.style.width === '30%') bar.style.width = '70%';
        }, 150);
    }
}

function hideLoadingBar() {
    const bar = document.getElementById('top-loading-bar');
    if (bar) {
        bar.style.width = '100%';
        bar.style.opacity = '0';
        setTimeout(() => {
            bar.style.width = '0%';
        }, 300);
    }
}

window.runGlobalCounterAnimation = function() {
    const counters = document.querySelectorAll('.stat-counter');
    counters.forEach(counter => {
        if (counter.dataset.animationId) {
            cancelAnimationFrame(parseInt(counter.dataset.animationId));
        }

        const rawValue = counter.getAttribute('data-value') || '';
        const target = parseFloat(rawValue.replace(/[^0-9.-]+/g, '')) || 0;
        const type = counter.getAttribute('data-type') || 'currency';
        
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
// 5. PAGE ROUTING & PREFETCHING
// ==========================================
// Prefetch all menu pages for instant navigation (zero delay)
function initPrefetch() {
    const links = document.querySelectorAll('[onclick*="switchPage"]');
    const uniqueUrls = new Set();
    links.forEach(link => {
        const onclickAttr = link.getAttribute('onclick') || '';
        const match = onclickAttr.match(/switchPage\(['"]([^'"]+)['"]/);
        if (match && match[1]) {
            uniqueUrls.add(match[1]);
        }
    });
    
    const currentMode = window.innerWidth < 768 ? 'mobile' : 'desktop';
    
    let delay = 500;
    uniqueUrls.forEach(pageName => {
        if (pageName !== 'dashboard') {
            setTimeout(() => {
                const mainUrl = `${CONFIG.API_BASE_URL}/${pageName}?mode=${currentMode}&_t=${Date.now()}`;
                const fallbackUrl = `${CONFIG.API_FALLBACK_URL}/${pageName}?mode=${currentMode}&_t=${Date.now()}`;
                
                const fetchPrefetch = (url) => {
                    return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => {
                        if (res.ok) return res.text();
                    });
                };
                
                fetchPrefetch(mainUrl)
                .catch(() => fetchPrefetch(fallbackUrl))
                .then(html => {
                    if (html) {
                        window.pageCache[pageName] = { html: html, mode: currentMode };
                        localStorage.setItem('guyub_cache_' + pageName, html);
                    }
                })
                .catch(() => {});
            }, delay);
            delay += 600; // Space out requests by 600ms to prevent bottlenecking single-threaded PHP server
        }
    });

    // Add mouseenter and touchstart listener to prefetch hovered/touched links even faster
    links.forEach(link => {
        const startPrefetch = () => {
            const onclickAttr = link.getAttribute('onclick') || '';
            const match = onclickAttr.match(/switchPage\(['"]([^'"]+)['"]/);
            if (match && match[1]) {
                const pageName = match[1];
                if (!window.pageCache[pageName] || window.pageCache[pageName].mode !== currentMode) {
                    const mainUrl = `${CONFIG.API_BASE_URL}/${pageName}?mode=${currentMode}`;
                    const fallbackUrl = `${CONFIG.API_FALLBACK_URL}/${pageName}?mode=${currentMode}`;
                    
                    const fetchHover = (url) => {
                        return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => {
                            if (res.ok) return res.text();
                        });
                    };
                    
                    fetchHover(mainUrl)
                    .catch(() => fetchHover(fallbackUrl))
                    .then(html => {
                        if (html) {
                            window.pageCache[pageName] = { html: html, mode: currentMode };
                            localStorage.setItem('guyub_cache_' + pageName, html);
                        }
                    })
                    .catch(() => {});
                }
            }
        };
        link.addEventListener('mouseenter', startPrefetch);
        link.addEventListener('touchstart', startPrefetch, { passive: true });
    });
}

function switchPage(pageName, element) {
    const mainContent = document.getElementById('main-content');
    if (!mainContent) return;

    // Save current active page to sessionStorage so refresh persists the view
    sessionStorage.setItem('guyub_active_page', pageName);

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
    } catch (e) {
        console.error("Error toggling sidebar or closing flyout:", e);
    }

    // Reset active styles on sidebar & bottom tabs
    document.querySelectorAll('.menu-link, .bottom-tab-link').forEach(item => {
        item.classList.remove('menu-active', 'text-white', 'text-blue-600');
        if (item.classList.contains('menu-link')) {
            item.classList.add('hover:bg-white/5', 'hover:text-white');
        } else {
            item.classList.add('text-gray-400');
        }
    });

    if (element) {
        element.classList.add('menu-active');
        if (element.classList.contains('menu-link')) {
            element.classList.add('text-white');
            element.classList.remove('hover:bg-white/5', 'hover:text-white');
        } else {
            element.classList.add('text-blue-600');
            element.classList.remove('text-gray-400');
        }

        // Auto-expand parent dropdown if the element is inside a submenu
        const parentSubmenu = element.closest('.submenu-container');
        if (parentSubmenu) {
            parentSubmenu.classList.remove('hidden');
            const dropdownGroup = parentSubmenu.closest('.dropdown-group');
            if (dropdownGroup) {
                const btn = dropdownGroup.querySelector('button');
                if (btn) {
                    const arrow = btn.querySelector('.fa-chevron-down');
                    if (arrow) arrow.classList.add('rotate-180');
                }
            }
        }
    }

    const currentMode = window.innerWidth < 768 ? 'mobile' : 'desktop';

    // Fetch dynamic partial view helper
    const fetchPage = (url) => {
        return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(async res => {
            if (res.status === 401) {
                window.location.href = 'login.html';
                return null;
            }
            const text = await res.text();
            if (!res.ok) throw new Error(text || `HTTP Error ${res.status}`);
            return text;
        });
    };

    const mainUrl = `${CONFIG.API_BASE_URL}/${pageName}?mode=${currentMode}&_t=${Date.now()}`;
    const fallbackUrl = `${CONFIG.API_FALLBACK_URL}/${pageName}?mode=${currentMode}&_t=${Date.now()}`;

    // 1. Read localStorage cache first to load without loading spinner (instantly 0ms)
    const cachedHTML = localStorage.getItem('guyub_cache_' + pageName);
    if (cachedHTML) {
        mainContent.innerHTML = cachedHTML;
        executeScripts(mainContent);
        if (pageName === 'dashboard' && typeof window.renderDashboard === 'function') {
            window.renderDashboard();
        }
        if (typeof window.runGlobalCounterAnimation === 'function') {
            window.runGlobalCounterAnimation();
        }

        // Fetch silent update in background to keep data fresh
        fetchPage(mainUrl)
            .catch(() => fetchPage(fallbackUrl))
            .then(html => {
                if (html && html !== cachedHTML) {
                    localStorage.setItem('guyub_cache_' + pageName, html);
                    window.pageCache[pageName] = { html: html, mode: currentMode };
                    mainContent.innerHTML = html;
                    executeScripts(mainContent);
                    if (pageName === 'dashboard' && typeof window.renderDashboard === 'function') {
                        window.renderDashboard();
                    }
                    if (typeof window.runGlobalCounterAnimation === 'function') {
                        window.runGlobalCounterAnimation();
                    }
                }
            })
            .catch(() => {});

        return;
    }

    // 2. If not cached, do NOT show the spinner! Just run the top loading progress bar
    showLoadingBar();

    fetchPage(mainUrl)
        .catch(() => fetchPage(fallbackUrl))
        .then(html => {
            hideLoadingBar();
            if (!html) return;
            window.pageCache[pageName] = { html: html, mode: currentMode }; // Save cache with mode
            localStorage.setItem('guyub_cache_' + pageName, html);
            if (pageName === 'dashboard') {
                localStorage.setItem('guyub_cache_dashboard', html);
            }
            mainContent.innerHTML = html;
            executeScripts(mainContent);

            if (pageName === 'dashboard' && typeof window.renderDashboard === 'function') {
                window.renderDashboard();
            }
            if (typeof window.runGlobalCounterAnimation === 'function') {
                window.runGlobalCounterAnimation();
            }

            // Start prefetching safely in the background after the main content is rendered
            if (!window.prefetchInitialized) {
                window.prefetchInitialized = true;
                setTimeout(initPrefetch, 1000);
            }
        })
        .catch(error => {
            mainContent.innerHTML = `
                <div class="p-10 bg-white rounded-[2.5rem] border border-red-100 shadow-sm text-center min-h-[400px] flex flex-col justify-center items-center">
                    <i class="fa-solid fa-triangle-exclamation text-5xl text-red-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800">Gagal Memuat Halaman</h3>
                    <p class="text-gray-500 mt-2 max-w-lg">${error.message || 'Pastikan server backend XAMPP / PHP Artisan Anda aktif.'}</p>
                    <button onclick="switchPage('${pageName}', document.querySelector('[data-page=\\'${pageName}\\']'))" class="mt-4 px-5 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 cursor-pointer">
                        <i class="fa-solid fa-sync mr-1.5"></i> Coba Lagi
                    </button>
                </div>`;
        });
}

// Invalidate page cache (called after form submit)
window.invalidatePageCache = function(pageName) {
    if (window.pageCache[pageName]) {
        delete window.pageCache[pageName];
    }
    localStorage.removeItem('guyub_cache_' + pageName);
};

function executeScripts(parent) {
    const scripts = Array.from(parent.querySelectorAll('script'));
    scripts.forEach(oldScript => {
        try {
            if (oldScript.src) {
                if (!document.querySelector(`script[src="${oldScript.src}"]`)) {
                    const newScript = document.createElement('script');
                    newScript.src = oldScript.src;
                    newScript.async = false;
                    document.head.appendChild(newScript);
                }
            } else {
                const newScript = document.createElement('script');
                newScript.text = oldScript.innerHTML;
                document.body.appendChild(newScript).parentNode.removeChild(newScript);
            }
            oldScript.parentNode?.removeChild(oldScript);
        } catch (e) {
            console.error("Gagal mengeksekusi script dynamic:", e);
        }
    });
}

// ==========================================
// 6. COLLAPSED SIDEBAR & FLYOUT NAVIGATION
// ==========================================
window.toggleSidebar = function() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar) return;

    if (window.innerWidth < 768) {
        // Mobile toggle
        const isClosed = sidebar.classList.contains('-translate-x-full');
        if (isClosed) {
            sidebar.classList.remove('-translate-x-full');
            if (backdrop) backdrop.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
        }
    } else {
        // Desktop collapse toggle
        sidebar.classList.toggle('sidebar-collapsed');
    }
};

// ==========================================
// 7. NOTIFIKASI AKTIVITAS PENGGUNA (BELL NOTIFICATION)
// ==========================================
window.latestActivityTimestamp = null;

window.fetchNotifications = function() {
    const url = `${CONFIG.API_BASE_URL}/aktivitas/data?_t=` + new Date().getTime();
    const fallbackUrl = `${CONFIG.API_FALLBACK_URL}/aktivitas/data?_t=` + new Date().getTime();

    const fetchNotif = (targetUrl) => {
        return fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json());
    };

    fetchNotif(url)
        .catch(() => fetchNotif(fallbackUrl))
        .then(data => {
            if (!data || data.length === 0) return;
            
            const lastViewed = localStorage.getItem('last_viewed_activity') || '';
            let unreadCount = 0;
            let listHtml = '';
            
            data.slice(0, 5).forEach((item) => {
                const isUnread = lastViewed ? (new Date(item.created_at) > new Date(lastViewed)) : true;
                if (isUnread) unreadCount++;
                
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
    window.invalidatePageCache('aktivitas-pengguna');
    switchPage('aktivitas-pengguna', targetLink);
};

// Close dropdown if clicked outside
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

// ==========================================
// 8. MOBILE SHEETS & TABS
// ==========================================
window.toggleMobileMenuSheet = function(show) {
    const sheet = document.getElementById('mobile-menu-sheet');
    if (!sheet) return;

    const content = sheet.querySelector('.relative.w-full');
    if (show) {
        sheet.classList.remove('hidden');
        setTimeout(() => {
            if (content) content.classList.remove('translate-y-full');
        }, 10);
    } else {
        if (content) content.classList.add('translate-y-full');
        setTimeout(() => {
            sheet.classList.add('hidden');
        }, 300);
    }
};

// ==========================================
// 9. THEME AND DROPDOWNS
// ==========================================
function toggleDropdown(id) {
    const sidebar = document.getElementById('sidebar');
    const menu = document.getElementById(id);
    if (!menu) return;
    const isCollapsed = sidebar && sidebar.classList.contains('sidebar-collapsed');

    if (isCollapsed) {
        const fp = document.getElementById('flyout-popup');
        if (!fp) return;

        if (window.activeFlyoutId === id) {
            closeFlyout();
            return;
        }

        fp.innerHTML = '';
        const links = menu.querySelectorAll('a');
        links.forEach(link => {
            const clone = link.cloneNode(true);
            const onclickAttr = link.getAttribute('onclick');
            if (onclickAttr) {
                clone.setAttribute('onclick', onclickAttr);
            }
            fp.appendChild(clone);
        });

        const btn = menu.closest('.dropdown-group').querySelector('button');
        if (btn) {
            const btnRect = btn.getBoundingClientRect();
            fp.style.top = btnRect.top + 'px';
            fp.classList.add('open');
            document.body.classList.add('flyout-active');

            const fpRect = fp.getBoundingClientRect();
            if (fpRect.bottom > window.innerHeight - 10) {
                fp.style.top = Math.max(10, window.innerHeight - fpRect.height - 10) + 'px';
            }
        }
        window.activeFlyoutId = id;
    } else {
        closeFlyout();
        menu.classList.toggle('hidden');
    }
}

function closeFlyout() {
    const fp = document.getElementById('flyout-popup');
    if (fp) {
        fp.classList.remove('open');
        fp.innerHTML = '';
    }
    document.body.classList.remove('flyout-active');
    window.activeFlyoutId = null;
}

document.addEventListener('click', function(e) {
    if (e.target.closest('.dropdown-group') || e.target.closest('#flyout-popup')) return;
    closeFlyout();
});
window.addEventListener('resize', closeFlyout);

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
}

// ==========================================
// 10. AJAX FORM SUBMISSION & LOGOUT HANDLER
// ==========================================
window.simpanDataUmum = function(event, formId, pageToReload) {
    event.preventDefault();
    const form = document.getElementById(formId);
    if (!form) return;

    if (!form.reportValidity()) return;

    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('button[onclick*="simpanDataUmum"]');
    const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Simpan';
    let targetUrl = form.getAttribute('action');
    
    if (targetUrl && !targetUrl.startsWith('http') && !targetUrl.includes('backend/public')) {
        const cleanPath = targetUrl.startsWith('/') ? targetUrl.substring(1) : targetUrl;
        targetUrl = `${CONFIG.API_BASE_URL}/${cleanPath}`;
    }

    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Menyimpan...';
        submitBtn.disabled = true;
    }

    const submitRequest = (url) => {
        return fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw err; });
            return response.json();
        });
    };

    submitRequest(targetUrl)
    .catch(err => {
        if (targetUrl.includes(CONFIG.API_BASE_URL)) {
            const fallbackUrl = targetUrl.replace(CONFIG.API_BASE_URL, CONFIG.API_FALLBACK_URL);
            return submitRequest(fallbackUrl);
        }
        throw err;
    })
    .then(data => {
        const modal = form.closest('[id^="modal-"]');
        if (modal) modal.classList.add('hidden');

        form.reset();
        alert(data.message || 'Data berhasil disimpan!');
        window.invalidatePageCache(pageToReload);

        if (pageToReload === 'pengaturan') {
            if (data.user) {
                currentUser = data.user;
                localStorage.setItem('guyub_user', JSON.stringify(currentUser));
                window.updateUserProfileUI(currentUser);
            }
            const linkElement = document.querySelector(`[data-page="pengaturan"]`);
            switchPage('pengaturan', linkElement);
        } else {
            const linkElement = document.querySelector(`[data-page="${pageToReload}"]`);
            switchPage(pageToReload, linkElement);
        }
    })
    .catch(error => {
        if (error.errors) {
            alert("Gagal menyimpan:\n" + Object.values(error.errors).flat().join('\n'));
        } else {
            alert(error.message || "Gagal terhubung ke server.");
        }
        console.error(error);
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });
};

function handleLogout() {
    if (typeof Swal === 'undefined') {
        if (confirm('Apakah Anda yakin ingin keluar dari sistem digital RT?')) {
            window.location.href = 'login.html';
        }
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: 'Apakah Anda yakin ingin keluar dari sistem digital RT?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-[2.5rem] p-6 shadow-2xl border border-gray-100 bg-white dark:bg-[#1E293B] dark:border-slate-800',
            title: 'text-xl font-extrabold text-gray-800 dark:text-white tracking-tight',
            htmlContainer: 'text-sm font-semibold text-gray-500 dark:text-slate-400 mt-2',
            confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-red-200 dark:shadow-none transition-all text-sm cursor-pointer mr-3',
            cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-600 dark:bg-slate-800 dark:text-slate-300 font-bold py-3 px-8 rounded-2xl transition-all text-sm cursor-pointer'
        }
    }).then(result => {
        if (result.isConfirmed) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const logoutRequest = (url) => {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
            };

            logoutRequest(`${CONFIG.API_BASE_URL}/logout`)
            .catch(() => logoutRequest(`${CONFIG.API_FALLBACK_URL}/logout`))
            .then(() => {
                sessionStorage.removeItem('guyub_active_page');
                for (let i = localStorage.length - 1; i >= 0; i--) {
                    const key = localStorage.key(i);
                    if (key && key.startsWith('guyub_cache_')) {
                        localStorage.removeItem(key);
                    }
                }
                window.location.href = 'login.html';
            })
            .catch(() => {
                sessionStorage.removeItem('guyub_active_page');
                for (let i = localStorage.length - 1; i >= 0; i--) {
                    const key = localStorage.key(i);
                    if (key && key.startsWith('guyub_cache_')) {
                        localStorage.removeItem(key);
                    }
                }
                window.location.href = 'login.html';
            });
        }
    });
}

// Global AJAX Form Submission Helpers
window.simpanDataUmum = function(event, formId, pageToReload) {
    event.preventDefault();

    let form = document.getElementById(formId);
    if (!form) return;
    if (!form.reportValidity()) {
        return;
    }

    let formData = new FormData(form);
    let submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('button[onclick*="simpanDataUmum"]');
    let originalBtnText = submitBtn ? submitBtn.innerHTML : 'Simpan';
    
    let formAction = form.getAttribute('action') || '';
    let targetUrl = formAction.startsWith('http') ? formAction : `${CONFIG.API_BASE_URL}${formAction}`;

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
};

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
