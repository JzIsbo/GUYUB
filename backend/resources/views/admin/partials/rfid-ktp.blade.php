{{--
    Halaman: Kartu KTP / RFID
    Rute   : /rfid-ktp
    Desain : Vanilla JavaScript murni (kompatibel penuh dengan SPA Kas RT)
--}}

<div class="p-4 md:p-6 max-w-5xl mx-auto">

    {{-- ===== HEADER ===== --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-id-card text-blue-600"></i>
            Kartu Identitas (RFID/NFC)
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftarkan kartu KTP Anda untuk membuka portal perumahan secara otomatis.</p>
    </div>

    {{-- ===== STATUS KONEKSI ESP32 SCANNER ===== --}}
    <div id="esp32-status-card" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 mb-6 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div id="esp32-status-dot" class="w-3.5 h-3.5 rounded-full bg-amber-400 animate-pulse"></div>
            <div>
                <p class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-microchip text-blue-500"></i>
                    Status Scanner ESP32: <span id="esp32-status-text" class="text-amber-500">Memeriksa...</span>
                </p>
                <p id="esp32-status-desc" class="text-[11px] text-gray-400 mt-0.5">Memeriksa apakah alat ESP32 menyala dan terhubung ke server...</p>
            </div>
        </div>
        <button onclick="checkEsp32DeviceStatus(true)" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline flex items-center gap-1 cursor-pointer">
            <i class="fa-solid fa-rotate-right"></i> Cek Koneksi
        </button>
    </div>

    {{-- ===== KARTU STATUS KTP ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status Kartu KTP</p>
                <div id="ktp-status-badge">
                    @if(Auth::user()->rfid_uid)
                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fa-solid fa-circle-check text-green-500"></i> Terdaftar
                        </span>
                        <p class="mt-2 font-mono text-lg font-bold text-gray-800 dark:text-white tracking-widest" id="ktp-uid-display">
                            {{ Auth::user()->rfid_uid }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">UID kartu e-KTP terdaftar di akun Anda</p>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fa-solid fa-circle-xmark text-amber-500"></i> Belum Terdaftar
                        </span>
                        <p class="text-xs text-gray-400 mt-2">Anda belum mendaftarkan kartu KTP ke akun ini.</p>
                    @endif
                </div>
            </div>

            <div class="flex gap-2 flex-wrap">
                {{-- Tombol Daftarkan KTP --}}
                <button id="btn-start-scan"
                        onclick="startRfidRegistration()"
                        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-700 transition cursor-pointer">
                    <i class="fa-solid fa-wifi"></i>
                    <span id="btn-start-scan-text">{{ Auth::user()->rfid_uid ? 'Ganti KTP' : 'Daftarkan KTP' }}</span>
                </button>

                {{-- Tombol Hapus (jika ada UID) --}}
                @if(Auth::user()->rfid_uid)
                <button id="btn-delete-ktp"
                        onclick="unregisterRfidKtp()"
                        class="flex items-center gap-2 bg-red-50 text-red-600 border border-red-200 px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-red-100 transition cursor-pointer">
                    <i class="fa-solid fa-trash"></i>
                    Hapus KTP
                </button>
                @endif
            </div>
        </div>

        {{-- ===== PANEL 1: SEDANG SCANNING (DEFAULT: HIDDEN) ===== --}}
        <div id="panel-scanning" class="hidden mt-6 border-t border-gray-100 dark:border-slate-700 pt-6">
            <div class="flex flex-col items-center text-center py-4">
                {{-- Animasi radar sinyal RFID --}}
                <div class="relative w-20 h-20 mb-4">
                    <div class="absolute inset-0 rounded-full bg-blue-100 dark:bg-blue-900/40 animate-ping opacity-60"></div>
                    <div class="relative flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 dark:bg-blue-950/60 border-2 border-blue-400">
                        <i class="fa-solid fa-id-card text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <p class="text-blue-600 dark:text-blue-400 font-bold text-lg mb-1">Menunggu Scan e-KTP...</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    Tempelkan fisik e-KTP Anda ke sensor RFID pada perangkat ESP32 sekarang.
                </p>

                {{-- Hitung mundur --}}
                <div class="mt-4 flex items-center gap-2 text-sm text-gray-500 bg-gray-50 dark:bg-slate-700/50 px-4 py-2 rounded-xl">
                    <i class="fa-regular fa-clock text-amber-500"></i>
                    <span>Sisa waktu: <strong id="rfid-countdown" class="font-mono text-gray-800 dark:text-white font-bold">05:00</strong></span>
                </div>

                <button onclick="cancelRfidScanning()" class="mt-4 text-xs text-gray-400 hover:text-red-500 underline transition cursor-pointer">
                    Batalkan Sesi
                </button>
            </div>
        </div>

        {{-- ===== PANEL 2: KARTU TERDETEKSI (DEFAULT: HIDDEN) ===== --}}
        <div id="panel-detected" class="hidden mt-6 border-t border-gray-100 dark:border-slate-700 pt-6">
            <div class="flex flex-col items-center text-center py-4">
                <div id="detected-icon-wrapper" class="w-16 h-16 rounded-full bg-green-50 dark:bg-green-950/60 border-2 border-green-500 flex items-center justify-center mb-3">
                    <i id="detected-icon" class="fa-solid fa-check text-green-600 text-2xl"></i>
                </div>
                <p id="detected-title" class="font-bold text-green-600 text-lg mb-1">e-KTP Terdeteksi!</p>
                <p class="text-xs text-gray-400 mb-2">UID Kartu:</p>
                <div class="font-mono bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-white px-5 py-2 rounded-xl text-lg font-black tracking-widest mb-4 border border-gray-200 dark:border-slate-600" id="detected-uid-text">
                    XXXXXXXX
                </div>
                <div id="detected-subtitle" class="text-xs text-gray-500 dark:text-gray-400 mb-5 max-w-sm">
                    Apakah Anda ingin mendaftarkan kartu ini untuk membuka portal perumahan?
                </div>
                <div class="flex items-center gap-3">
                    <button id="btn-confirm-assign"
                            onclick="confirmRfidAssign()"
                            class="flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-green-700 transition cursor-pointer shadow-md shadow-green-600/20">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Ya, Daftarkan Sekarang!</span>
                    </button>
                    <button id="btn-cancel-assign"
                            onclick="cancelRfidScanning()"
                            class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== ALERT BANNER PESAN ===== --}}
        <div id="rfid-alert" class="hidden mt-4">
            <div id="rfid-alert-box" class="flex items-center gap-2 border rounded-xl px-4 py-3 text-sm font-medium">
                <i id="rfid-alert-icon" class="fa-solid fa-circle-info"></i>
                <span id="rfid-alert-text"></span>
            </div>
        </div>
    </div>

    {{-- ===== PANDUAN CARA KERJA ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6">
        <h3 class="font-bold text-gray-700 dark:text-white mb-4 flex items-center gap-2 text-base">
            <i class="fa-solid fa-circle-info text-blue-500"></i>
            Cara Kerja Sistem e-KTP & Portal
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex flex-col items-center text-center p-4 bg-blue-50/70 dark:bg-slate-700/50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold mb-2">1</div>
                <p class="font-bold text-sm text-gray-800 dark:text-white mb-1">Klik "Daftarkan KTP"</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tekan tombol di atas, lalu tempelkan e-KTP ke alat scanner ESP32.</p>
            </div>
            <div class="flex flex-col items-center text-center p-4 bg-purple-50/70 dark:bg-slate-700/50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold mb-2">2</div>
                <p class="font-bold text-sm text-gray-800 dark:text-white mb-1">Konfirmasi UID</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">UID kartu akan muncul otomatis di layar. Klik tombol konfirmasi untuk simpan.</p>
            </div>
            <div class="flex flex-col items-center text-center p-4 bg-green-50/70 dark:bg-slate-700/50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold mb-2">3</div>
                <p class="font-bold text-sm text-gray-800 dark:text-white mb-1">Buka Portal Otomatis</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Setelah terdaftar, tap e-KTP Anda di gerbang perumahan untuk membuka portal.</p>
            </div>
        </div>
    </div>

    {{-- ===== LOG AKSES PORTAL (Khusus Pengurus) ===== --}}
    @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'RW', 'Sekretaris RT', 'Sekretaris RW']))
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-700 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-list-check text-indigo-500"></i>
                Log Riwayat Akses Portal
            </h3>
            <button onclick="loadPortalLogs()" class="text-xs text-blue-600 hover:underline flex items-center gap-1 cursor-pointer">
                <i class="fa-solid fa-rotate-right"></i> Refresh Log
            </button>
        </div>

        <div id="logs-loading" class="text-center py-6 text-gray-400">
            <i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
            <p class="text-xs">Memuat log portal...</p>
        </div>

        <div id="logs-table-container" class="hidden overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-700 text-xs font-semibold text-gray-400 uppercase">
                        <th class="pb-3 pr-4">Waktu</th>
                        <th class="pb-3 pr-4">UID Kartu</th>
                        <th class="pb-3 pr-4">Nama Pengguna</th>
                        <th class="pb-3 pr-4">Perangkat</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody id="logs-table-body" class="divide-y divide-gray-50 dark:divide-slate-700">
                    {{-- Dinamis --}}
                </tbody>
            </table>
        </div>

        <div id="logs-empty" class="hidden text-center py-8 text-gray-400">
            <i class="fa-solid fa-inbox text-3xl mb-2"></i>
            <p class="text-xs">Belum ada riwayat tap di portal.</p>
        </div>
    </div>
    @endif

</div>

{{-- ===== JAVASCRIPT LOGIC (VANILLA JS) ===== --}}
<script>
(function() {
    // Variabel state lokal
    let rfidSessionToken = null;
    let rfidPollInterval = null;
    let rfidCountdownTimer = null;
    let rfidCountdownSec = 300;
    let rfidDetectedUid = null;

    // Helper CSRF Token
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

    // Helper Alert
    function showRfidAlert(type, message) {
        const container = document.getElementById('rfid-alert');
        const box = document.getElementById('rfid-alert-box');
        const icon = document.getElementById('rfid-alert-icon');
        const text = document.getElementById('rfid-alert-text');

        if (!container || !box) return;

        text.textContent = message;

        if (type === 'success') {
            box.className = 'flex items-center gap-2 border rounded-xl px-4 py-3 text-sm font-medium bg-green-50 border-green-200 text-green-700';
            icon.className = 'fa-solid fa-circle-check text-green-500';
        } else if (type === 'error') {
            box.className = 'flex items-center gap-2 border rounded-xl px-4 py-3 text-sm font-medium bg-red-50 border-red-200 text-red-700';
            icon.className = 'fa-solid fa-circle-exclamation text-red-500';
        } else if (type === 'warning') {
            box.className = 'flex items-center gap-2 border rounded-xl px-4 py-3 text-sm font-medium bg-amber-50 border-amber-200 text-amber-700';
            icon.className = 'fa-solid fa-triangle-exclamation text-amber-500';
        } else {
            box.className = 'flex items-center gap-2 border rounded-xl px-4 py-3 text-sm font-medium bg-blue-50 border-blue-200 text-blue-700';
            icon.className = 'fa-solid fa-circle-info text-blue-500';
        }

        container.classList.remove('hidden');
        setTimeout(() => {
            if (container) container.classList.add('hidden');
        }, 7000);
    }

    // 0. CEK STATUS KONEKSI ESP32
    let isEsp32Online = false;
    window.checkEsp32DeviceStatus = async function(manual = false) {
        const dot = document.getElementById('esp32-status-dot');
        const text = document.getElementById('esp32-status-text');
        const desc = document.getElementById('esp32-status-desc');

        if (!dot || !text) return;

        try {
            const res = await fetch('/rfid/device-status');
            const data = await res.json();

            isEsp32Online = data.online === true;

            if (isEsp32Online) {
                dot.className = 'w-3.5 h-3.5 rounded-full bg-green-500 shadow-sm shadow-green-500/50';
                text.className = 'text-green-600 font-bold';
                text.textContent = 'ONLINE (Terhubung)';
                desc.textContent = `Alat ESP32 aktif & siap membaca kartu. (Terakhir merespons ${data.seconds_ago} detik lalu)`;
                if (manual) showRfidAlert('success', 'ESP32 Scanner terhubung dan aktif!');
            } else {
                dot.className = 'w-3.5 h-3.5 rounded-full bg-red-500 animate-pulse';
                text.className = 'text-red-500 font-bold';
                text.textContent = 'OFFLINE (Belum Terhubung)';
                desc.textContent = data.seconds_ago !== null
                    ? `ESP32 tidak merespons (terakhir terlihat ${data.seconds_ago} detik lalu). Pastikan Hotspot & ESP32 menyala.`
                    : 'ESP32 belum pernah terhubung ke server. Pastikan Hotspot menyala dan sketch ter-upload.';
                if (manual) showRfidAlert('warning', 'ESP32 belum terdeteksi online di server.');
            }
        } catch (e) {
            console.warn('Error checking device status:', e);
            if (dot) dot.className = 'w-3.5 h-3.5 rounded-full bg-red-500 animate-pulse';
            if (text) {
                text.className = 'text-red-500 font-bold';
                text.textContent = 'OFFLINE (Gagal Kontak)';
            }
            if (desc) desc.textContent = 'Tidak dapat menghubungi server status.';
        }
    };

    // 1. MULAI REGISTRASI
    window.startRfidRegistration = async function() {
        const btn = document.getElementById('btn-start-scan');
        const btnText = document.getElementById('btn-start-scan-text');
        const panelScanning = document.getElementById('panel-scanning');
        const panelDetected = document.getElementById('panel-detected');

        // Beri tahu status ESP32 ke user
        if (!isEsp32Online) {
            showRfidAlert('warning', '⚠️ Perhatian: Scanner ESP32 terdeteksi OFFLINE. Pastikan ESP32 sudah menyala dan tersambung ke Hotspot Anda!');
        }

        if (btn) btn.disabled = true;
        if (btnText) btnText.textContent = 'Menyiapkan sesi...';

        try {
            const res = await fetch('/rfid/start-registration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            });

            const data = await res.json();

            if (data.status === 'success') {
                rfidSessionToken = data.token;
                rfidCountdownSec = data.ttl || 300;

                // Tampilkan panel scanning, sembunyikan panel detected
                if (panelScanning) panelScanning.classList.remove('hidden');
                if (panelDetected) panelDetected.classList.add('hidden');

                if (btnText) btnText.textContent = 'Menunggu scan...';

                // Jalankan polling & countdown
                startRfidPolling();
                startRfidCountdown();
            } else {
                showRfidAlert('error', data.message || 'Gagal memulai sesi registrasi.');
                cancelRfidScanning();
            }
        } catch (e) {
            console.error(e);
            showRfidAlert('error', 'Gagal menghubungi server.');
            cancelRfidScanning();
        }
    };

    // 2. POLLING STATUS SESI SETIAP 1.5 DETIK
    function startRfidPolling() {
        stopRfidPolling();
        rfidPollInterval = setInterval(checkRfidSessionStatus, 1500);
    }

    function stopRfidPolling() {
        if (rfidPollInterval) {
            clearInterval(rfidPollInterval);
            rfidPollInterval = null;
        }
    }

    async function checkRfidSessionStatus() {
        if (!rfidSessionToken) return;

        try {
            const res = await fetch(`/rfid/session/${rfidSessionToken}`);
            const data = await res.json();

            const panelScanning = document.getElementById('panel-scanning');
            const panelDetected = document.getElementById('panel-detected');
            const detectedUidText = document.getElementById('detected-uid-text');
            const detectedIconWrapper = document.getElementById('detected-icon-wrapper');
            const detectedIcon = document.getElementById('detected-icon');
            const detectedTitle = document.getElementById('detected-title');
            const detectedSubtitle = document.getElementById('detected-subtitle');
            const btnConfirm = document.getElementById('btn-confirm-assign');
            const btnCancel = document.getElementById('btn-cancel-assign');

            if (data.status === 'completed') {
                // Kartu valid & belum terdaftar
                stopRfidPolling();
                stopRfidCountdown();

                rfidDetectedUid = data.uid;

                if (panelScanning) panelScanning.classList.add('hidden');
                if (panelDetected) panelDetected.classList.remove('hidden');
                if (detectedUidText) {
                    detectedUidText.textContent = data.uid;
                    detectedUidText.className = 'font-mono bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-white px-5 py-2 rounded-xl text-lg font-black tracking-widest mb-4 border border-gray-200 dark:border-slate-600';
                }

                if (detectedIconWrapper) detectedIconWrapper.className = 'w-16 h-16 rounded-full bg-green-50 dark:bg-green-950/60 border-2 border-green-500 flex items-center justify-center mb-3';
                if (detectedIcon) detectedIcon.className = 'fa-solid fa-check text-green-600 text-2xl';
                if (detectedTitle) {
                    detectedTitle.className = 'font-bold text-green-600 text-lg mb-1';
                    detectedTitle.textContent = 'e-KTP Terdeteksi!';
                }
                if (detectedSubtitle) {
                    detectedSubtitle.className = 'text-xs text-gray-500 dark:text-gray-400 mb-5 max-w-sm';
                    detectedSubtitle.innerHTML = 'Apakah Anda ingin mendaftarkan kartu ini untuk membuka portal perumahan?';
                }
                if (btnConfirm) btnConfirm.classList.remove('hidden');
                if (btnCancel) {
                    btnCancel.className = 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition cursor-pointer';
                    btnCancel.textContent = 'Batal';
                    btnCancel.onclick = cancelRfidScanning;
                }

            } else if (data.status === 'already_registered') {
                // Kartu sudah terdaftar di akun lain atau akun sendiri
                stopRfidPolling();
                stopRfidCountdown();

                rfidDetectedUid = data.uid;

                if (panelScanning) panelScanning.classList.add('hidden');
                if (panelDetected) panelDetected.classList.remove('hidden');
                if (detectedUidText) {
                    detectedUidText.textContent = data.uid;
                    detectedUidText.className = 'font-mono bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 px-5 py-2 rounded-xl text-lg font-black tracking-widest mb-4 border border-red-200 dark:border-red-800';
                }

                if (detectedIconWrapper) detectedIconWrapper.className = 'w-16 h-16 rounded-full bg-red-50 dark:bg-red-950/60 border-2 border-red-500 flex items-center justify-center mb-3';
                if (detectedIcon) detectedIcon.className = 'fa-solid fa-ban text-red-600 text-2xl';
                if (detectedTitle) {
                    detectedTitle.className = 'font-bold text-red-600 text-lg mb-1';
                    detectedTitle.textContent = 'Kartu Sudah Terdaftar!';
                }
                if (detectedSubtitle) {
                    detectedSubtitle.className = 'text-xs max-w-md mb-5';
                    if (data.is_self) {
                        detectedSubtitle.innerHTML = '<div class="p-3.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 rounded-xl text-amber-700 dark:text-amber-300 font-medium text-xs leading-relaxed text-left"><div class="flex items-start gap-2"><i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i><div>Kartu e-KTP ini <strong>sudah terdaftar</strong> pada akun Anda sendiri.</div></div></div>';
                    } else {
                        detectedSubtitle.innerHTML = `<div class="p-3.5 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 rounded-xl text-red-700 dark:text-red-300 font-medium text-xs leading-relaxed text-left">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5"></i>
                                <div>
                                    Kartu e-KTP ini <strong>sudah terdaftar</strong> atas nama warga lain: <strong>${data.registered_to}</strong>.<br>
                                    <span class="text-[11px] text-red-500/80 mt-0.5 block">Satu fisik kartu e-KTP tidak dapat didaftarkan ke lebih dari 1 akun warga. Silakan gunakan kartu e-KTP Anda sendiri.</span>
                                </div>
                            </div>
                        </div>`;
                    }
                }
                // Sembunyikan tombol "Ya, Daftarkan Sekarang!"
                if (btnConfirm) btnConfirm.classList.add('hidden');
                if (btnCancel) {
                    btnCancel.className = 'flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition cursor-pointer shadow-md shadow-blue-600/20';
                    btnCancel.innerHTML = '<i class="fa-solid fa-rotate-left"></i><span>Scan Kartu Lain</span>';
                    btnCancel.onclick = function() {
                        cancelRfidScanning();
                        startRfidRegistration();
                    };
                }

            } else if (data.status === 'expired') {
                cancelRfidScanning();
                showRfidAlert('error', 'Waktu pendaftaran habis. Silakan klik Daftarkan KTP kembali.');
            }
        } catch (e) {
            console.warn('Polling rfid error:', e);
        }
    }

    // 3. COUNTDOWN TIMER
    function startRfidCountdown() {
        stopRfidCountdown();
        updateCountdownDisplay();
        rfidCountdownTimer = setInterval(() => {
            rfidCountdownSec--;
            updateCountdownDisplay();
            if (rfidCountdownSec <= 0) {
                cancelRfidScanning();
                showRfidAlert('error', 'Waktu registrasi habis. Silakan coba lagi.');
            }
        }, 1000);
    }

    function stopRfidCountdown() {
        if (rfidCountdownTimer) {
            clearInterval(rfidCountdownTimer);
            rfidCountdownTimer = null;
        }
    }

    function updateCountdownDisplay() {
        const el = document.getElementById('rfid-countdown');
        if (!el) return;
        const m = Math.floor(rfidCountdownSec / 60);
        const s = rfidCountdownSec % 60;
        el.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }

    // 4. BATAL SCAN
    window.cancelRfidScanning = function() {
        stopRfidPolling();
        stopRfidCountdown();

        rfidSessionToken = null;
        rfidDetectedUid = null;

        const panelScanning = document.getElementById('panel-scanning');
        const panelDetected = document.getElementById('panel-detected');
        const btn = document.getElementById('btn-start-scan');
        const btnText = document.getElementById('btn-start-scan-text');
        const btnConfirm = document.getElementById('btn-confirm-assign');
        const btnCancel = document.getElementById('btn-cancel-assign');

        if (panelScanning) panelScanning.classList.add('hidden');
        if (panelDetected) panelDetected.classList.add('hidden');
        if (btn) btn.disabled = false;
        if (btnText) btnText.textContent = 'Daftarkan KTP';
        if (btnConfirm) btnConfirm.classList.remove('hidden');
        if (btnCancel) {
            btnCancel.className = 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition cursor-pointer';
            btnCancel.textContent = 'Batal';
            btnCancel.onclick = cancelRfidScanning;
        }
    };

    // 5. KONFIRMASI SIMPAN UID KE USER
    window.confirmRfidAssign = async function() {
        if (!rfidSessionToken) return;

        const btnConfirm = document.getElementById('btn-confirm-assign');
        if (btnConfirm) {
            btnConfirm.disabled = true;
            btnConfirm.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        }

        try {
            const res = await fetch('/rfid/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ token: rfidSessionToken })
            });

            const data = await res.json();

            if (data.status === 'success') {
                showRfidAlert('success', `Berhasil! KTP dengan UID ${data.uid} telah terdaftar.`);
                setTimeout(() => {
                    // Reload halaman atau switchPage
                    if (typeof switchPage === 'function') {
                        switchPage('rfid-ktp');
                    } else {
                        window.location.reload();
                    }
                }, 1500);
            } else {
                showRfidAlert('error', data.message || 'Gagal menyimpan kartu.');
                cancelRfidScanning();
            }
        } catch (e) {
            console.error(e);
            showRfidAlert('error', 'Terjadi kesalahan saat menyimpan data.');
            cancelRfidScanning();
        }
    };

    // 6. HAPUS KTP DARI AKUN
    window.unregisterRfidKtp = async function() {
        if (!confirm('Apakah Anda yakin ingin menghapus KTP ini? Setelah dihapus, Anda tidak bisa membuka portal.')) {
            return;
        }

        try {
            const res = await fetch('/rfid/unregister', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            });

            const data = await res.json();

            if (data.status === 'success') {
                showRfidAlert('success', 'KTP berhasil dihapus.');
                setTimeout(() => {
                    if (typeof switchPage === 'function') {
                        switchPage('rfid-ktp');
                    } else {
                        window.location.reload();
                    }
                }, 1200);
            } else {
                showRfidAlert('error', data.message || 'Gagal menghapus KTP.');
            }
        } catch (e) {
            console.error(e);
            showRfidAlert('error', 'Gagal menghubungi server.');
        }
    };

    // 7. LOAD LOGS (UNTUK PENGURUS)
    window.loadPortalLogs = async function() {
        const loading = document.getElementById('logs-loading');
        const container = document.getElementById('logs-table-container');
        const empty = document.getElementById('logs-empty');
        const tbody = document.getElementById('logs-table-body');

        if (!tbody) return;

        if (loading) loading.classList.remove('hidden');
        if (container) container.classList.add('hidden');
        if (empty) empty.classList.add('hidden');

        try {
            const res = await fetch('/rfid/logs');
            const data = await res.json();

            if (loading) loading.classList.add('hidden');

            const list = (data.logs && data.logs.data) ? data.logs.data : [];

            if (list.length === 0) {
                if (empty) empty.classList.remove('hidden');
                return;
            }

            if (container) container.classList.remove('hidden');

            tbody.innerHTML = list.map(log => {
                const date = new Date(log.created_at).toLocaleString('id-ID', {
                    day: '2-digit', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
                const isGranted = log.status === 'granted';
                const statusBadge = isGranted
                    ? `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700"><i class="fa-solid fa-circle-check"></i> Diberikan</span>`
                    : `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>`;
                const userName = log.user ? `${log.user.name} (${log.user.role})` : '<span class="text-gray-400 italic">Tidak Terdaftar</span>';

                return `
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/50 transition">
                        <td class="py-2.5 pr-4 text-gray-500 whitespace-nowrap text-xs">${date}</td>
                        <td class="py-2.5 pr-4 font-mono font-bold text-gray-800 dark:text-white">${log.rfid_uid}</td>
                        <td class="py-2.5 pr-4 text-gray-700 dark:text-gray-300">${userName}</td>
                        <td class="py-2.5 pr-4 text-gray-400 text-xs">${log.device_id || '-'}</td>
                        <td class="py-2.5">${statusBadge}</td>
                    </tr>
                `;
            }).join('');

        } catch (e) {
            console.warn('Failed loading logs:', e);
            if (loading) loading.classList.add('hidden');
        }
    };

    // Auto load log jika elemen ada di DOM
    if (document.getElementById('logs-table-body')) {
        loadPortalLogs();
    }

    // Auto cek status ESP32 saat halaman terbuka & polling tiap 3 detik
    setTimeout(checkEsp32DeviceStatus, 100);
    setTimeout(checkEsp32DeviceStatus, 1000);
    setInterval(checkEsp32DeviceStatus, 3000);
})();
</script>
