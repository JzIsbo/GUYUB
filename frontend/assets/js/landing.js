/* landing.js - Script for index.html (Landing & Public Portal) */

let publicData = null;

document.addEventListener('DOMContentLoaded', () => {
    const loadPublicData = (url) => {
        return fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => {
            if (!res.ok) throw new Error("HTTP Error " + res.status);
            return res.json();
        });
    };

    // Load public data with fallback from main or fallback API endpoints
    const mainApi = `${CONFIG.API_BASE_URL}/api/public/data`;
    const fallbackApi = `${CONFIG.API_FALLBACK_URL}/api/public/data`;

    loadPublicData(mainApi)
        .catch(() => loadPublicData(fallbackApi))
        .then(data => {
            publicData = data;
            document.getElementById('stat-warga').innerText = data.totalWarga;
            document.getElementById('stat-umkm').innerText = data.totalUmkm;
            renderTabContent('tab-pengumuman');
        })
        .catch(err => {
            console.error("Gagal memuat data publik:", err);
            document.getElementById('tab-container').innerHTML = `
                <div class="text-center py-12 text-red-500 font-semibold">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> Gagal menghubungkan ke server backend API.<br>
                    <span class="text-xs text-gray-500 font-normal mt-2 inline-block">Pastikan <b>php artisan serve</b> berjalan di folder backend.</span>
                </div>`;
        });
});

function renderTabContent(tabId) {
    const container = document.getElementById('tab-container');
    if (!publicData) return;

    if (tabId === 'tab-pengumuman') {
        const list = publicData.announcements || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-12 text-gray-400 italic">Belum ada pengumuman publik.</div>';
            return;
        }
        container.innerHTML = list.map(item => `
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow transition duration-200 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-blue-50 text-blue-600 font-bold px-3 py-1 rounded-full text-[10px] tracking-wide uppercase">${item.status}</span>
                    <span class="text-xs text-gray-400 font-medium"><i class="fa-regular fa-clock mr-1"></i> ${new Date(item.created_at).toLocaleDateString('id-ID')}</span>
                </div>
                <h3 class="text-base font-extrabold text-gray-800 mb-2">${item.judul}</h3>
                <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line">${item.isi}</p>
            </div>
        `).join('');
    } 
    
    else if (tabId === 'tab-umkm') {
        const list = publicData.umkms || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-12 text-gray-400 italic">Belum ada UMKM terdaftar.</div>';
            return;
        }
        container.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-3 gap-6">${list.map(item => `
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg transition duration-300 overflow-hidden group">
                <div>
                    <div class="h-48 w-full overflow-hidden relative bg-gray-100">
                        <img src="${item.gambar || 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=800&auto=format&fit=crop'}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="${item.nama_usaha}">
                        <span class="absolute top-4 right-4 bg-white/90 backdrop-blur-md text-emerald-600 px-3 py-1 rounded-full text-[10px] font-extrabold flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-circle text-[6px]"></i> ${item.status}
                        </span>
                    </div>
                    <div class="p-6">
                        <span class="inline-block bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-extrabold tracking-wide uppercase mb-3">${item.kategori}</span>
                        <h3 class="text-base font-extrabold text-gray-800 mb-1 leading-snug">${item.nama_usaha}</h3>
                        <p class="text-[11px] font-bold text-gray-400 mb-3"><i class="fa-solid fa-user text-indigo-400 mr-1"></i> Pemilik: ${item.pemilik}</p>
                        <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">${item.deskripsi || 'Usaha lokal warga RT.'}</p>
                    </div>
                </div>
                <div class="px-6 pb-6 pt-2">
                    <a href="https://wa.me/${item.kontak.replace(/[^0-9]/g, '')}" target="_blank" class="w-full py-3 px-4 rounded-2xl bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white font-bold text-xs flex items-center justify-center gap-2 transition duration-200">
                        <i class="fa-brands fa-whatsapp text-base"></i> Hubungi WhatsApp Usaha
                    </a>
                </div>
            </div>
        `).join('')}</div>`;
    }

    else if (tabId === 'tab-kegiatan') {
        const list = publicData.kegiatans || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-12 text-gray-400 italic">Belum ada kegiatan RT.</div>';
            return;
        }
        container.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-3 gap-6">${list.map(item => `
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg transition duration-300 overflow-hidden group">
                <div>
                    <div class="h-44 w-full overflow-hidden relative bg-gray-100">
                        <img src="${item.gambar || 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=800&auto=format&fit=crop'}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="${item.nama_kegiatan}">
                        <span class="absolute top-4 left-4 bg-teal-600 text-white px-3 py-1 rounded-full text-[10px] font-extrabold shadow-md">
                            <i class="fa-solid fa-clock mr-1"></i> ${item.waktu}
                        </span>
                    </div>
                    <div class="p-6">
                        <span class="text-xs text-gray-400 font-bold block mb-1"><i class="fa-solid fa-calendar text-teal-500 mr-1.5"></i> ${new Date(item.tanggal).toLocaleDateString('id-ID')}</span>
                        <h3 class="text-base font-extrabold text-gray-800 mb-2 leading-snug">${item.nama_kegiatan}</h3>
                        <p class="text-xs font-bold text-gray-500 mb-3"><i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i> ${item.lokasi}</p>
                        <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">${item.deskripsi || 'Aktivitas kebersamaan warga.'}</p>
                    </div>
                </div>
            </div>
        `).join('')}</div>`;
    }

    else if (tabId === 'tab-posyandu') {
        const list = publicData.posyandus || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-12 text-gray-400 italic">Belum ada jadwal Posyandu.</div>';
            return;
        }
        container.innerHTML = list.map(item => `
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:shadow transition duration-200 mb-3">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-gray-800">${item.nama_kegiatan}</h4>
                        <p class="text-xs text-gray-400 font-medium mt-0.5"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> ${item.lokasi}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-xl text-xs font-extrabold">${item.target_peserta}</span>
                    <span class="text-xs text-gray-500 font-bold"><i class="fa-solid fa-calendar mr-1"></i> ${new Date(item.tanggal).toLocaleDateString('id-ID')}</span>
                </div>
            </div>
        `).join('');
    }

    else if (tabId === 'tab-ronda') {
        const list = publicData.rondas || [];
        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-12 text-gray-400 italic">Belum ada jadwal ronda.</div>';
            return;
        }
        container.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 gap-6">${list.map(item => `
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-slate-800 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">${item.hari}</span>
                    <span class="text-xs text-gray-400 font-medium">${item.jam_shift}</span>
                </div>
                <h4 class="text-sm font-extrabold text-gray-800 mt-2"><i class="fa-solid fa-user-shield text-slate-500 mr-1"></i> Petugas: ${item.petugas_ronda}</h4>
                <p class="text-xs text-gray-400 mt-1">Koordinator Shift: <span class="font-bold text-gray-600">${item.koordinator}</span></p>
            </div>
        `).join('')}</div>`;
    }
}

function switchPublicTab(tabId, btn) {
    // Switch content
    renderTabContent(tabId);

    // Reset all buttons style
    document.querySelectorAll('.public-tab-btn').forEach(button => {
        button.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200');
        button.classList.add('bg-gray-50', 'text-gray-600');
    });

    // Set active style to clicked button
    btn.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200');
    btn.classList.remove('bg-gray-50', 'text-gray-600');
}
