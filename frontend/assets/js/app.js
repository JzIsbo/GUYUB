/* app.js - Global JavaScript Configurations & Helpers */

// Global Configuration
const CONFIG = {
    API_BASE_URL: '/backend/public', // Use absolute path proxied by Vite
    API_FALLBACK_URL: 'http://127.0.0.1:8000'
};

// Global Fetch Interceptor to rewrite URLs and bypass CORS/port conflicts
const originalFetch = window.fetch;
window.fetch = function(input, init) {
    let url = typeof input === 'string' ? input : (input && input.url ? input.url : '');
    
    if (url) {
        // Rewrite backend absolute URLs to proxy prefix
        if (url.includes(':8000')) {
            url = url.replace(/^https?:\/\/[^\/]+/, CONFIG.API_BASE_URL);
        } else if (url.startsWith('/') && 
                   !url.startsWith('/backend/public') && 
                   !url.startsWith('/storage') && 
                   !url.startsWith('/uploads') && 
                   !url.startsWith('/assets') && 
                   !url.startsWith('/@') && 
                   !url.startsWith('/node_modules')) {
            url = `${CONFIG.API_BASE_URL}${url}`;
        }
        
        if (typeof input === 'string') {
            input = url;
        } else if (input && input.url) {
            // Re-create request with updated URL if needed
            try {
                input = new Request(url, input);
            } catch (e) {
                // Fallback for simple objects
                input.url = url;
            }
        }
    }
    
    return originalFetch(input, init);
};

// Global Link/Anchor Click Interceptor to rewrite URLs and preserve session context
document.addEventListener('click', function(e) {
    const anchor = e.target.closest('a');
    if (anchor) {
        const href = anchor.getAttribute('href');
        if (href) {
            // Rewrite backend absolute URLs to proxy prefix
            if (href.includes(':8000')) {
                const rewritten = href.replace(/^https?:\/\/[^\/]+/, CONFIG.API_BASE_URL);
                anchor.setAttribute('href', rewritten);
            } else if (href.startsWith('/') && 
                       !href.startsWith('/backend/public') && 
                       !href.startsWith('/storage') && 
                       !href.startsWith('/uploads') && 
                       !href.startsWith('/assets') && 
                       !href.startsWith('#') &&
                       !href.startsWith('javascript:')) {
                const rewritten = `${CONFIG.API_BASE_URL}${href}`;
                anchor.setAttribute('href', rewritten);
            }
        }
    }
}, true); // Use capture phase to intercept before default browser navigation triggers

// Override native alert with modern premium guyub custom modal matching backend
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
