/* dashboard.js - Script for SPA Dashboard Admin Panel */

let currentUser = null;

// ==========================================
// 1. SESSION CHECKER & RBAC FILTER
// ==========================================
document.addEventListener("DOMContentLoaded", () => {
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
            document.getElementById('welcome-message').innerText = `Halo, ${currentUser.name} 👋`;
            document.getElementById('profile-name').innerText = currentUser.name;
            document.getElementById('profile-role').innerText = currentUser.role;
            document.getElementById('profile-avatar').src = currentUser.photo || `https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.name)}&background=2563EB&color=fff`;

            applyRoleBasedAccess(currentUser.role);
            
            // Load default page (dashboard)
            switchPage('dashboard', document.querySelector('[data-page="dashboard"]'));
        })
        .catch(err => {
            console.error("Session verification failed:", err);
            window.location.href = 'login.html';
        });
});

// ==========================================
// 2. APPLY ROLE-BASED ACCESS CONTROL (RBAC) ON SIDEBAR
// ==========================================
function applyRoleBasedAccess(role) {
    const allPages = [
        'dashboard', 'pemasukan', 'pengeluaran', 'transaksi', 'kategori',
        'data-warga', 'data-iuran', 'data-pengurus-rt', 'data-rt', 'pengguna', 'perangkat-sistem',
        'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan', 'backup-restore', 'aktivitas-pengguna',
        'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
        'surat-online', 'pengumuman',
        'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
    ];

    const aksesHalaman = {
        'Super Admin': allPages,
        'RT': [
            'dashboard', 'data-warga', 'data-pengurus-rt', 'data-rt', 'perangkat-sistem',
            'surat-online', 'pengumuman', 'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
            'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'pengaturan',
            'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
        ],
        'Bendahara': [
            'dashboard', 'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
            'pemasukan', 'pengeluaran', 'transaksi', 'kategori', 'surat-online', 'pengumuman', 'data-iuran',
            'laporan-keuangan', 'laporan-iuran', 'laporan-kas', 'export-laporan', 'pengaturan',
            'koperasi', 'bank-sampah', 'rukem', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'aspirasi'
        ],
        'Warga': [
            'dashboard', 'tagihan-warga', 'pembayaran-online', 'status-pembayaran', 'riwayat-gateway', 'qris-va',
            'surat-online', 'pengumuman', 'data-warga', 'data-pengurus-rt', 'pengaturan',
            'koperasi', 'bank-sampah', 'umkm', 'posyandu', 'keamanan', 'kegiatan', 'rukem', 'aspirasi'
        ]
    };

    const allowed = aksesHalaman[role] || [];

    // Filter single links
    document.querySelectorAll('.menu-link[data-page]').forEach(link => {
        const page = link.getAttribute('data-page');
        if (!allowed.includes(page)) {
            link.style.display = 'none';
        }
    });

    // Filter category titles & parent dropdown menus
    document.querySelectorAll('.parent-menu').forEach(parent => {
        const pages = parent.getAttribute('data-pages').split(',');
        const hasAccess = pages.some(p => allowed.includes(p));
        if (!hasAccess) {
            parent.style.display = 'none';
            // Hide next sibling if it's the dropdown menu container
            if (parent.nextElementSibling && parent.nextElementSibling.tagName === 'DIV') {
                parent.nextElementSibling.style.display = 'none';
            }
        }
    });
}

// Execute script elements in partial HTML content
function executeScripts(parent) {
    const scripts = Array.from(parent.querySelectorAll('script'));
    scripts.forEach(oldScript => {
        const newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
        if (oldScript.src) {
            newScript.src = oldScript.src;
            document.head.appendChild(newScript);
        } else {
            newScript.text = oldScript.innerHTML;
            document.body.appendChild(newScript);
            document.body.removeChild(newScript);
        }
        oldScript.remove();
    });
}

// Page routing switcher
function switchPage(pageName, element) {
    const mainContent = document.getElementById('main-content');

    // Reset active menu styles
    document.querySelectorAll('.menu-link').forEach(item => {
        item.classList.remove('menu-active', 'text-white');
        item.classList.add('hover:bg-white/5', 'hover:text-white');
    });

    if (element) {
        element.classList.add('menu-active', 'text-white');
        element.classList.remove('hover:bg-white/5', 'hover:text-white');
    }

    mainContent.innerHTML = `
        <div class="flex flex-col items-center justify-center h-full min-h-[400px] space-y-4">
            <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-gray-400 font-bold italic animate-pulse tracking-widest uppercase text-xs">MEMUAT MODUL ${pageName.replace(/-/g, ' ').toUpperCase()}...</p>
        </div>
    `;

    // Fetch partial template with automatic fallback for Artisan Serve
    const fetchPartial = (url) => {
        return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(async response => {
            if (response.status === 401) {
                window.location.href = 'login.html';
                return;
            }
            const text = await response.text();
            if (!response.ok) {
                throw new Error(text || `HTTP Error ${response.status}`);
            }
            return text;
        });
    };

    fetchPartial(`${CONFIG.API_BASE_URL}/${pageName}`)
    .catch(() => fetchPartial(`${CONFIG.API_FALLBACK_URL}/${pageName}`))
    .then(html => {
        if (!html) return;
        mainContent.innerHTML = html;

        // Execute scripts in template
        executeScripts(mainContent);

        // Run page specific logic
        if (pageName === 'dashboard' && typeof window.renderDashboard === 'function') {
            window.renderDashboard();
        }
    })
    .catch(error => {
        mainContent.innerHTML = `
            <div class="p-10 bg-white rounded-[2.5rem] border border-red-100 shadow-sm text-center min-h-[400px] flex flex-col justify-center items-center">
                <i class="fa-solid fa-triangle-exclamation text-5xl text-red-400 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800">Gagal Memuat Halaman</h3>
                <p class="text-gray-500 mt-2 max-w-lg">${error.message || 'Pastikan server backend XAMPP / PHP Artisan Anda aktif.'}</p>
            </div>`;
    });
}

// Dropdown menu toggler
function toggleDropdown(id) {
    const menu = document.getElementById(id);
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// AJAX form submission handler for all admin panels
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
        // Fallback endpoint if main fails
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

        if (pageToReload === 'pengaturan') {
            window.location.reload();
        } else {
            switchPage(pageToReload, document.querySelector(`[data-page="${pageToReload}"]`));
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

// Logout confirmation modal and handler
function handleLogout() {
    if (typeof Swal === 'undefined') {
        // Fallback without SweetAlert
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
            popup: 'rounded-[2.5rem] p-6 shadow-2xl border border-gray-100 bg-white',
            title: 'text-xl font-extrabold text-gray-800 tracking-tight',
            htmlContainer: 'text-sm font-semibold text-gray-500 mt-2',
            confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-red-200 transition-all text-sm cursor-pointer mr-3',
            cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 px-8 rounded-2xl transition-all text-sm cursor-pointer'
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
                window.location.href = 'login.html';
            })
            .catch(err => {
                window.location.href = 'login.html';
            });
        }
    });
}
