/* landing.js - Script for index.html (Landing & Public Portal) */

let publicData = null;

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initial Theme Application
    const isDark = document.documentElement.classList.contains('dark');
    applyTheme(isDark);

    // Helper to render auth buttons dynamically based on user session status
    const renderAuthButtons = (userData) => {
        const authContainer = document.getElementById('auth-buttons-container');
        const mobileContainer = document.getElementById('auth-mobile-container');
        const heroActions = document.getElementById('hero-actions-container');

        if (userData && userData.status === 'success' && userData.user) {
            // User is authenticated
            if (authContainer) {
                authContainer.innerHTML = `
                    <a href="dashboard.html" class="px-4 py-2 rounded-xl bg-white text-blue-700 text-xs sm:text-sm font-semibold hover:bg-blue-50 transition flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-gauge-high text-[10px] sm:text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                `;
            }
            if (mobileContainer) {
                mobileContainer.innerHTML = `
                    <a href="dashboard.html" onclick="toggleMobileNavbar()" class="hover:text-blue-300 transition py-2 text-center bg-white text-blue-700 rounded-xl font-bold mt-2 shadow-lg">Dashboard</a>
                `;
            }
            if (heroActions) {
                heroActions.innerHTML = `
                    <a href="dashboard.html" class="btn-primary px-5 py-3 rounded-xl text-white text-xs sm:text-sm font-semibold transition-all duration-300">
                        <i class="fa-solid fa-gauge-high mr-1.5"></i>
                        Buka Dashboard
                    </a>
                    <a href="#fitur" class="px-5 py-3 rounded-xl glass text-white text-xs sm:text-sm font-semibold hover:bg-white/20 transition">Lihat Fitur</a>
                `;
            }
        } else {
            // User is guest
            if (authContainer) {
                authContainer.innerHTML = `
                    <a href="../backend/public/register" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs sm:text-sm font-semibold transition">
                        Daftar Warga
                    </a>
                    <a href="login.html" class="px-4 py-2 rounded-xl bg-white text-blue-700 text-xs sm:text-sm font-semibold hover:bg-blue-50 transition shadow-sm">
                        Login
                    </a>
                `;
            }
            if (mobileContainer) {
                mobileContainer.innerHTML = `
                    <a href="../backend/public/register" onclick="toggleMobileNavbar()" class="hover:text-blue-300 transition py-1 border-b border-white/5">Daftar Warga</a>
                    <a href="login.html" onclick="toggleMobileNavbar()" class="hover:text-blue-300 transition py-2 text-center bg-white text-blue-700 rounded-xl font-bold mt-2 shadow-lg">Login</a>
                `;
            }
            if (heroActions) {
                heroActions.innerHTML = `
                    <a href="login.html" class="btn-primary px-5 py-3 rounded-xl text-white text-xs sm:text-sm font-semibold transition-all duration-300">
                        <i class="fa-solid fa-right-to-bracket mr-1.5"></i>
                        Masuk Sekarang
                    </a>
                    <a href="../backend/public/register" class="px-5 py-3 rounded-xl glass border border-white/20 text-white text-xs sm:text-sm font-semibold hover:bg-white/20 transition">
                        Daftar Warga Baru
                    </a>
                    <a href="#fitur" class="px-5 py-3 rounded-xl glass text-white text-xs sm:text-sm font-semibold hover:bg-white/20 transition">
                        Lihat Fitur
                    </a>
                `;
            }
        }
    };

    // Stale-While-Revalidate (SWR): Load cached public data from localStorage first
    const cachedPublicData = localStorage.getItem('guyub_public_data');
    if (cachedPublicData) {
        try {
            const data = JSON.parse(cachedPublicData);
            publicData = data;
            document.getElementById('stat-warga').innerText = data.totalWarga;
            document.getElementById('stat-umkm').innerText = data.totalUmkm;

            if (data.rt_info) {
                const rt = data.rt_info;
                const contactRtText = document.getElementById('contact-rt-rw-text');
                const contactAlamatText = document.getElementById('contact-alamat-text');
                if (contactRtText) {
                    contactRtText.innerText = `RT ${rt.nomor_rt || '05'} / RW ${rt.nomor_rw || '12'} — ${rt.nama_wilayah || 'Perumahan Grand Guyub Residence'}`;
                }
                if (contactAlamatText) {
                    contactAlamatText.innerText = rt.alamat_lengkap || 'Jl. Harmony Boulevard No. 1, Blok A5, Perumahan Grand Guyub Residence';
                }
            }
            renderTabContent('tab-pengumuman');
        } catch (e) {
            console.error("Error parsing cached public data:", e);
        }
    }

    // Load cached session status if available
    const cachedUser = localStorage.getItem('guyub_user');
    if (cachedUser) {
        try {
            renderAuthButtons({ status: 'success', user: JSON.parse(cachedUser) });
        } catch (e) {}
    }

    // 2. Fetch Fresh Public Data in background
    const loadPublicData = (url) => {
        return fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => {
            if (!res.ok) throw new Error("HTTP Error " + res.status);
            return res.json();
        });
    };

    const mainApi = `${CONFIG.API_BASE_URL}/api/public/data`;
    const fallbackApi = `${CONFIG.API_FALLBACK_URL}/api/public/data`;

    loadPublicData(mainApi)
        .catch(() => loadPublicData(fallbackApi))
        .then(data => {
            publicData = data;
            localStorage.setItem('guyub_public_data', JSON.stringify(data)); // Save to cache

            document.getElementById('stat-warga').innerText = data.totalWarga;
            document.getElementById('stat-umkm').innerText = data.totalUmkm;

            // Populate RT details dynamically if available
            if (data.rt_info) {
                const rt = data.rt_info;
                const contactRtText = document.getElementById('contact-rt-rw-text');
                const contactAlamatText = document.getElementById('contact-alamat-text');
                const contactWaLink = document.getElementById('contact-wa-link');

                if (contactRtText) {
                    contactRtText.innerText = `RT ${rt.nomor_rt || '05'} / RW ${rt.nomor_rw || '12'} — ${rt.nama_wilayah || 'Perumahan Grand Guyub Residence'}`;
                }
                if (contactAlamatText) {
                    contactAlamatText.innerText = rt.alamat_lengkap || 'Jl. Harmony Boulevard No. 1, Blok A5, Perumahan Grand Guyub Residence';
                }
                if (contactWaLink) {
                    contactWaLink.href = `https://wa.me/6281234567890`;
                    contactWaLink.innerText = `+62 812-3456-7890`;
                }
            }

            renderTabContent('tab-pengumuman');
        })
        .catch(err => {
            console.error("Gagal memuat data publik:", err);
            if (!publicData) {
                document.getElementById('tab-container').innerHTML = `
                    <div class="text-center py-12 text-red-500 font-semibold">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i> Gagal menghubungkan ke server backend API.<br>
                        <span class="text-xs text-gray-500 font-normal mt-2 inline-block">Pastikan <b>php artisan serve</b> berjalan di folder backend.</span>
                    </div>`;
            }
        });

    // 3. Dynamic Authentication Check in background (delayed by 300ms to prevent single-threaded lockups)
    setTimeout(() => {
        const checkAuthStatus = (url) => {
            return fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => {
                if (res.status === 401) return null;
                if (!res.ok) throw new Error("HTTP Error " + res.status);
                return res.json();
            });
        };

        const authUrl = `${CONFIG.API_BASE_URL}/api/auth/user`;
        const authFallbackUrl = `${CONFIG.API_FALLBACK_URL}/api/auth/user`;

        checkAuthStatus(authUrl)
            .catch(() => checkAuthStatus(authFallbackUrl))
            .then(userData => {
                if (userData && userData.status === 'success' && userData.user) {
                    localStorage.setItem('guyub_user', JSON.stringify(userData.user));
                } else {
                    localStorage.removeItem('guyub_user');
                }
                renderAuthButtons(userData);
            })
            .catch(() => {
                const authContainer = document.getElementById('auth-buttons-container');
                if (authContainer && !localStorage.getItem('guyub_user')) {
                    authContainer.innerHTML = `
                        <a href="login.html" class="px-4 py-2 rounded-xl bg-white text-blue-700 text-xs sm:text-sm font-semibold hover:bg-blue-50 transition shadow-sm">
                            Login
                        </a>
                    `;
                }
            });
    }, 300);
});

/* =============================================
   TAB CONTENT RENDERING (Identical to Blade views)
============================================= */
function renderTabContent(tabId) {
    const container = document.getElementById('tab-container');
    if (!publicData) return;

    if (tabId === 'tab-pengumuman') {
        const list = publicData.announcements || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-10 text-gray-400 italic text-xs sm:text-sm">Belum ada pengumuman publik yang aktif.</div>';
            return;
        }
        container.innerHTML = `<div class="space-y-4 sm:space-y-6">${list.map(item => `
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm hover:shadow transition duration-200">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <span class="bg-blue-50 text-blue-600 font-bold px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] tracking-wide uppercase">${item.status}</span>
                    <span class="text-[10px] sm:text-xs text-gray-400 font-medium"><i class="fa-regular fa-clock mr-1"></i> ${new Date(item.created_at).toLocaleDateString('id-ID')}</span>
                </div>
                <h3 class="text-sm sm:text-base font-extrabold text-gray-800 mb-1.5 sm:mb-2">${item.judul}</h3>
                <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line">${item.isi}</p>
            </div>
        `).join('')}</div>`;
    } 
    
    else if (tabId === 'tab-umkm') {
        const list = publicData.umkms || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-10 text-gray-400 italic text-xs sm:text-sm">Belum ada usaha UMKM warga terdaftar.</div>';
            return;
        }
        container.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">${list.map(item => `
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg transition duration-300 overflow-hidden group">
                <div>
                    <div class="h-36 sm:h-44 w-full overflow-hidden relative bg-gray-100">
                        <img src="${item.gambar || 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=800&auto=format&fit=crop'}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="${item.nama_usaha}">
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-md text-emerald-600 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-circle text-[5px]"></i> ${item.status}
                        </span>
                    </div>
                    <div class="p-4 sm:p-6">
                        <span class="inline-block bg-indigo-50 text-indigo-600 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold tracking-wide uppercase mb-2 sm:mb-3">${item.kategori}</span>
                        <h3 class="text-sm sm:text-base font-extrabold text-gray-800 mb-1 leading-snug">${item.nama_usaha}</h3>
                        <p class="text-[10px] sm:text-[11px] font-bold text-gray-400 mb-2 sm:mb-3"><i class="fa-solid fa-user text-indigo-400 mr-1"></i> Pemilik: ${item.pemilik}</p>
                        <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">${item.deskripsi || 'Usaha lokal warga RT.'}</p>
                    </div>
                </div>
                <div class="px-4 sm:px-6 pb-4 sm:pb-6 pt-2">
                    <a href="https://wa.me/${item.kontak.replace(/[^0-9]/g, '')}" target="_blank" class="w-full py-2 sm:py-3 px-3 sm:px-4 rounded-xl sm:rounded-2xl bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white font-bold text-xs flex items-center justify-center gap-1.5 transition duration-200">
                        <i class="fa-brands fa-whatsapp text-sm sm:text-base"></i> Hubungi WhatsApp Usaha
                    </a>
                </div>
            </div>
        `).join('')}</div>`;
    }

    else if (tabId === 'tab-kegiatan') {
        const list = publicData.kegiatans || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-10 text-gray-400 italic text-xs sm:text-sm">Belum ada agenda kegiatan terdekat.</div>';
            return;
        }
        container.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">${list.map(item => `
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg transition duration-300 overflow-hidden group">
                <div>
                    <div class="h-32 sm:h-40 w-full overflow-hidden relative bg-gray-100">
                        <img src="${item.gambar || 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=800&auto=format&fit=crop'}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="${item.nama_kegiatan}">
                        <span class="absolute top-3 left-3 bg-teal-600 text-white px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold shadow-md">
                            <i class="fa-solid fa-clock mr-1"></i> ${item.waktu}
                        </span>
                    </div>
                    <div class="p-4 sm:p-6">
                        <span class="text-[10px] sm:text-xs text-gray-400 font-bold block mb-1.5"><i class="fa-solid fa-calendar text-teal-500 mr-1.5"></i> ${new Date(item.tanggal).toLocaleDateString('id-ID')}</span>
                        <h3 class="text-sm sm:text-base font-extrabold text-gray-800 mb-1.5 leading-snug">${item.nama_kegiatan}</h3>
                        <p class="text-xs font-bold text-gray-500 mb-2"><i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i> ${item.lokasi}</p>
                        <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">${item.deskripsi || 'Aktivitas kebersamaan warga.'}</p>
                    </div>
                </div>
            </div>
        `).join('')}</div>`;
    }

    else if (tabId === 'tab-posyandu') {
        const list = publicData.posyandus || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-10 text-gray-400 italic text-xs sm:text-sm">Belum ada agenda Posyandu terjadwal.</div>';
            return;
        }
        container.innerHTML = `<div class="space-y-3 sm:space-y-4">${list.map(item => `
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4 hover:shadow transition duration-200">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-base sm:text-lg shadow-sm">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-extrabold text-gray-800">${item.nama_kegiatan}</h4>
                        <p class="text-[10px] sm:text-xs text-gray-400 font-medium mt-0.5"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> ${item.lokasi}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between md:justify-end gap-3 border-t border-gray-50 md:border-none pt-2.5 md:pt-0">
                    <span class="bg-rose-50 text-rose-600 px-2.5 py-1 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-extrabold">${item.target_peserta}</span>
                    <span class="text-[10px] sm:text-xs text-gray-500 font-bold"><i class="fa-solid fa-calendar mr-1"></i> ${new Date(item.tanggal).toLocaleDateString('id-ID')}</span>
                </div>
            </div>
        `).join('')}</div>`;
    }

    else if (tabId === 'tab-ronda') {
        const list = publicData.rondas || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-10 text-gray-400 italic text-xs sm:text-sm">Belum ada jadwal ronda siskamling malam.</div>';
            return;
        }
        container.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">${list.map(item => `
            <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-sm hover:shadow transition duration-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="bg-slate-800 text-white px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">${item.hari}</span>
                    <span class="text-[10px] sm:text-xs text-gray-400 font-medium">${item.jam_shift}</span>
                </div>
                <h4 class="text-xs sm:text-sm font-extrabold text-gray-800 mt-2"><i class="fa-solid fa-user-shield text-slate-500 mr-1"></i> Petugas: ${item.petugas_ronda}</h4>
                <p class="text-[11px] text-gray-400 mt-0.5">Koordinator Shift: <span class="font-bold text-gray-600">${item.koordinator}</span></p>
            </div>
        `).join('')}</div>`;
    }
}

function switchPublicTab(tabId, btn) {
    renderTabContent(tabId);
    document.querySelectorAll('.public-tab-btn').forEach(b => {
        b.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200');
        b.classList.add('bg-gray-50', 'text-gray-600');
    });
    btn.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200');
    btn.classList.remove('bg-gray-50', 'text-gray-600');
}

/* =============================================
   MOBILE NAVBAR TOGGLE
============================================= */
window.toggleMobileNavbar = function() {
    const nav = document.getElementById('mobile-nav');
    const icon = document.getElementById('hamburger-icon');
    if (!nav || !icon) return;
    
    const isHidden = nav.classList.contains('hidden');
    if (isHidden) {
        nav.classList.remove('hidden');
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-xmark');
    } else {
        nav.classList.add('hidden');
        icon.classList.remove('fa-xmark');
        icon.classList.add('fa-bars');
    }
};

/* =============================================
   KIRIM KONTAK VIA WHATSAPP
============================================= */
window.kirimKontak = function(e) {
    e.preventDefault();
    const nama  = document.getElementById('kontak-nama').value.trim();
    const hp    = document.getElementById('kontak-hp').value.trim();
    const pesan = document.getElementById('kontak-pesan').value.trim();

    if (!nama || !pesan) return;

    const nomorWA = '6281234567890';
    const teks = encodeURIComponent(
        `Halo Pengurus RT,\n\nSaya *${nama}*${hp ? ' (HP: ' + hp + ')' : ''} ingin menyampaikan:\n\n_${pesan}_\n\nTerima kasih.`
    );

    window.open(`https://wa.me/${nomorWA}?text=${teks}`, '_blank');

    document.getElementById('kontak-form').reset();
    const sukses = document.getElementById('kontak-sukses');
    if (sukses) {
        sukses.classList.remove('hidden');
        setTimeout(() => sukses.classList.add('hidden'), 4000);
    }
};

/* =============================================
   NAVBAR: HIGHLIGHT AKTIF SAAT SCROLL & BACKGROUND
============================================= */
const sections  = ['beranda', 'fitur', 'tentang', 'kontak'];
const navLinks  = document.querySelectorAll('.nav-link');

function updateActiveNav() {
    let current = 'beranda';
    sections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 100) current = id;
    });
    navLinks.forEach(link => {
        const isActive = link.getAttribute('data-section') === current;
        link.classList.toggle('text-blue-300', isActive);
        link.classList.toggle('font-bold', isActive);
    });
}

window.addEventListener('scroll', updateActiveNav, { passive: true });
updateActiveNav();

const navbar = document.querySelector('nav');
window.addEventListener('scroll', () => {
    if (window.scrollY > 60) {
        if (navbar) navbar.classList.add('bg-[#0f2460]', 'shadow-lg');
    } else {
        if (navbar) navbar.classList.remove('bg-[#0f2460]', 'shadow-lg');
    }
}, { passive: true });

/* =============================================
   MODE GELAP & TERANG (DARK / LIGHT MODE)
============================================= */
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
        if (icon) icon.className = 'fa-solid fa-moon text-sm text-white/80';
        if (btn) btn.title = 'Ubah ke Mode Gelap';
    }
}
