{{--
    Halaman: Portal Akses Perumahan (RFID / e-KTP Gate)
    Rute   : /portal-akses
    Desain : Vanilla JavaScript murni & Tailwind CSS (kompatibel penuh dengan SPA Kas RT)
--}}

<div class="p-4 md:p-6 max-w-7xl mx-auto space-y-6">

    {{-- ===== 1. HEADER HALAMAN & ACTIONS ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2.5">
                <span class="w-10 h-10 rounded-2xl bg-cyan-50 dark:bg-cyan-950/60 border border-cyan-200 dark:border-cyan-800 flex items-center justify-center text-cyan-600">
                    <i class="fa-solid fa-door-open text-lg"></i>
                </span>
                Portal Akses Perumahan
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Monitoring real-time aktivitas keluar-masuk warga melalui palang pintu otomatis menggunakan e-KTP / RFID.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            {{-- Tombol Buka Gerbang Manual --}}
            <button type="button"
                    onclick="triggerManualOpenGate()"
                    id="btn-manual-open"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold px-4 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all active:scale-95 cursor-pointer text-sm">
                <i class="fa-solid fa-key"></i>
                <span>Buka Portal Manual</span>
            </button>

            {{-- Tombol Refresh Data --}}
            <button type="button"
                    onclick="refreshPortalData(true)"
                    id="btn-refresh-portal"
                    class="inline-flex items-center gap-2 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-slate-700 font-semibold px-4 py-2.5 rounded-xl transition cursor-pointer text-sm">
                <i class="fa-solid fa-rotate-right" id="icon-refresh"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    {{-- ===== 2. STATUS SCANNER & PERANGKAT HARDWARE ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 md:p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="relative">
                    <div id="portal-scanner-dot" class="w-4 h-4 rounded-full {{ ($scanner_online ?? false) ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.8)]' : 'bg-rose-500 shadow-[0_0_12px_rgba(244,63,94,0.8)]' }}"></div>
                    <div id="portal-scanner-ping" class="absolute inset-0 rounded-full {{ ($scanner_online ?? false) ? 'bg-emerald-400 animate-ping opacity-75' : 'hidden' }}"></div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status Reader Gerbang:</span>
                        <span id="portal-scanner-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ ($scanner_online ?? false) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                            {{ ($scanner_online ?? false) ? '🟢 ESP32 Online & Terhubung' : '🔴 ESP32 Offline / Belum Terhubung' }}
                        </span>
                    </div>
                    <p id="portal-scanner-desc" class="text-xs text-gray-400 mt-1">
                        @if(($scanner_online ?? false) && isset($scanner_info['ip']))
                            Alat aktif di IP: <strong class="text-gray-700 dark:text-gray-200 font-mono">{{ $scanner_info['ip'] }}</strong> (Device ID: {{ $scanner_info['device_id'] ?? 'esp32-scanner-01' }}) &bull; Sinyal Heartbeat teratur.
                        @else
                            Perangkat ESP32 belum mengirimkan heartbeat ke server. Pastikan alat menyala dan terhubung ke WiFi/Hotspot.
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-slate-700/50 px-3 py-2 rounded-xl">
                <i class="fa-solid fa-microchip text-blue-500"></i>
                <span>Hardware: <strong>RC522 + LCD 16x2 + Servo SG90</strong></span>
                <span class="mx-1 text-gray-300">|</span>
                <i class="fa-solid fa-circle-dot text-cyan-500 animate-pulse"></i>
                <span id="portal-live-indicator">Live Polling Aktif (3s)</span>
            </div>
        </div>
    </div>

    {{-- ===== 3. STATISTIK AKTIVITAS HARI INI ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Tap Hari Ini --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-5 border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Tap Hari Ini</p>
                <h3 id="stat-today" class="text-2xl md:text-3xl font-extrabold text-gray-800 dark:text-white mt-1">{{ $stats_today ?? 0 }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Semua pembacaan kartu</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-credit-card"></i>
            </div>
        </div>

        {{-- Card 2: Akses Diterima (Palang Terbuka) --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-5 border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akses Diberikan</p>
                <h3 id="stat-granted" class="text-2xl md:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats_granted ?? 0 }}</h3>
                <p class="text-[11px] text-emerald-600/80 mt-0.5">Palang gerbang dibuka</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        {{-- Card 3: Akses Ditolak --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-5 border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akses Ditolak</p>
                <h3 id="stat-denied" class="text-2xl md:text-3xl font-extrabold text-rose-600 dark:text-rose-400 mt-1">{{ $stats_denied ?? 0 }}</h3>
                <p class="text-[11px] text-rose-600/80 mt-0.5">Kartu tidak terdaftar/nonaktif</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>

        {{-- Card 4: Warga Punya RFID --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-5 border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">KTP Terdaftar</p>
                <h3 id="stat-registered-rfid" class="text-2xl md:text-3xl font-extrabold text-purple-600 dark:text-purple-400 mt-1">{{ $stats_registered_rfid ?? 0 }}</h3>
                <p class="text-[11px] text-purple-600/80 mt-0.5">Warga memiliki akses</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-address-card"></i>
            </div>
        </div>
    </div>

    {{-- ===== 4. TABEL LOG AKTIVITAS WARGA DI PORTAL ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        {{-- Table Toolbar --}}
        <div class="p-4 md:p-5 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h4 class="font-bold text-gray-800 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-cyan-600"></i>
                    Log Aktivitas Akses Gerbang
                </h4>
                <p class="text-xs text-gray-400 mt-0.5">Riwayat realtime setiap kali kartu ditempelkan pada sensor gate.</p>
            </div>

            {{-- Input Pencarian Lokal & Filter Status --}}
            <div class="flex items-center gap-2 flex-wrap">
                <div class="relative min-w-[200px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text"
                           id="filter-search-input"
                           onkeyup="filterPortalTable()"
                           placeholder="Cari nama / UID / blok..."
                           class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>

                <select id="filter-status-select"
                        onchange="filterPortalTable()"
                        class="text-xs py-1.5 px-3 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="all">Semua Status</option>
                    <option value="granted">Akses Diterima (Palang Terbuka)</option>
                    <option value="denied">Akses Ditolak</option>
                </select>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="portal-table">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-700/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700">
                        <th class="py-3.5 px-4">Waktu Tap</th>
                        <th class="py-3.5 px-4">Warga / Pemilik Kartu</th>
                        <th class="py-3.5 px-4">Blok & NIK</th>
                        <th class="py-3.5 px-4">UID Kartu</th>
                        <th class="py-3.5 px-4 text-center">Status Gerbang</th>
                        <th class="py-3.5 px-4">Perangkat / Pintu</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="portal-logs-tbody" class="divide-y divide-gray-100 dark:divide-slate-700/60 text-xs">
                    @forelse($portal_logs ?? [] as $log)
                        @php
                            $isGranted = ($log->status === 'granted');
                            $timeObj = \Carbon\Carbon::parse($log->created_at);
                        @endphp
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-slate-700/40 transition portal-log-row"
                            data-search="{{ strtolower(($log->user_name ?? '') . ' ' . ($log->rfid_uid ?? '') . ' ' . ($log->blok_rumah ?? '')) }}"
                            data-status="{{ $log->status }}">
                            {{-- Waktu Tap --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-gray-800 dark:text-white font-mono">{{ $timeObj->format('H:i:s') }}</span>
                                <span class="text-[11px] text-gray-400 block">{{ $timeObj->translatedFormat('d M Y') }}</span>
                            </td>

                            {{-- Warga / Pemilik Kartu --}}
                            <td class="py-3.5 px-4">
                                @if($log->user_name)
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm overflow-hidden">
                                            @if($log->user_photo)
                                                <img src="{{ $log->user_photo }}" alt="{{ $log->user_name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-white leading-tight">{{ $log->user_name }}</p>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 font-semibold mt-0.5 inline-block">
                                                {{ $log->user_role ?? 'Warga' }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-xs">
                                            <i class="fa-solid fa-user-xmark"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-500 dark:text-gray-400">Tidak Terdaftar</p>
                                            <span class="text-[10px] text-amber-600">Perlu didaftarkan</span>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Blok & NIK --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-700 dark:text-gray-300 block">
                                    {{ $log->blok_rumah ?? '-' }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-mono">
                                    {{ $log->nik ?? '-' }}
                                </span>
                            </td>

                            {{-- UID Kartu --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-xs bg-slate-100 dark:bg-slate-700/80 text-slate-800 dark:text-slate-200 px-2 py-1 rounded-md border border-slate-200 dark:border-slate-600">
                                    {{ $log->rfid_uid ?? 'MANUAL' }}
                                </span>
                            </td>

                            {{-- Status Gerbang --}}
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($isGranted)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                                        <i class="fa-solid fa-door-open text-emerald-500"></i> Palang Terbuka
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-800 dark:text-rose-300">
                                        <i class="fa-solid fa-ban text-rose-500"></i> Akses Ditolak
                                    </span>
                                @endif
                            </td>

                            {{-- Perangkat / Pintu --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-300 text-xs">
                                    <i class="fa-solid fa-microchip text-gray-400 text-[10px]"></i>
                                    {{ $log->device_id ?? 'gate-01' }}
                                </span>
                            </td>

                            {{-- Keterangan --}}
                            <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">
                                <span class="line-clamp-1" title="{{ $log->message }}">{{ $log->message ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr id="portal-empty-row">
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <i class="fa-solid fa-door-closed text-3xl mb-2 text-gray-300 dark:text-gray-600 block"></i>
                                Belum ada riwayat aktivitas tap kartu di portal gerbang perumahan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== JAVASCRIPT REALTIME POLLING & KONTROL ===== --}}
<script>
(function() {
    let portalPollingTimer = null;
    let isFetchingPortal = false;

    // Bersihkan interval jika berpindah halaman di SPA Kas RT
    if (window._portalInterval) {
        clearInterval(window._portalInterval);
    }

    // Fungsi fetch live data
    window.refreshPortalData = function(manualClick = false) {
        if (isFetchingPortal) return;
        isFetchingPortal = true;

        if (manualClick) {
            const icon = document.getElementById('icon-refresh');
            if (icon) icon.classList.add('fa-spin');
        }

        fetch('/rfid/portal-live', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                updatePortalUI(data);
            }
        })
        .catch(err => {
            console.warn('[Portal Akses] Polling error:', err);
        })
        .finally(() => {
            isFetchingPortal = false;
            const icon = document.getElementById('icon-refresh');
            if (icon) icon.classList.remove('fa-spin');
        });
    };

    function updatePortalUI(data) {
        // 1. Update Hardware Status
        const dot = document.getElementById('portal-scanner-dot');
        const ping = document.getElementById('portal-scanner-ping');
        const badge = document.getElementById('portal-scanner-badge');
        const desc = document.getElementById('portal-scanner-desc');

        if (dot && badge) {
            if (data.online) {
                dot.className = "w-4 h-4 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.8)]";
                if (ping) ping.className = "absolute inset-0 rounded-full bg-emerald-400 animate-ping opacity-75";
                badge.className = "px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200";
                badge.innerText = "🟢 ESP32 Online & Terhubung";

                const devId = (data.scanner_info && data.scanner_info.device_id) ? data.scanner_info.device_id : 'esp32-scanner-01';
                const ip = (data.scanner_info && data.scanner_info.ip) ? data.scanner_info.ip : '-';
                if (desc) {
                    desc.innerHTML = `Alat aktif di IP: <strong class="text-gray-700 dark:text-gray-200 font-mono">${ip}</strong> (Device ID: ${devId}) &bull; Sinyal Heartbeat teratur.`;
                }
            } else {
                dot.className = "w-4 h-4 rounded-full bg-rose-500 shadow-[0_0_12px_rgba(244,63,94,0.8)]";
                if (ping) ping.className = "hidden";
                badge.className = "px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200";
                badge.innerText = "🔴 ESP32 Offline / Belum Terhubung";
                if (desc) {
                    desc.innerText = "Perangkat ESP32 belum mengirimkan heartbeat ke server. Pastikan alat menyala dan terhubung ke WiFi/Hotspot.";
                }
            }
        }

        // 2. Update Stats
        if (data.stats) {
            const stToday = document.getElementById('stat-today');
            const stGranted = document.getElementById('stat-granted');
            const stDenied = document.getElementById('stat-denied');
            const stReg = document.getElementById('stat-registered-rfid');

            if (stToday) stToday.innerText = data.stats.today ?? 0;
            if (stGranted) stGranted.innerText = data.stats.granted ?? 0;
            if (stDenied) stDenied.innerText = data.stats.denied ?? 0;
            if (stReg) stReg.innerText = data.stats.registered_rfid ?? 0;
        }

        // 3. Update Table Rows
        if (data.logs && data.logs.length > 0) {
            const tbody = document.getElementById('portal-logs-tbody');
            if (tbody) {
                let html = '';
                data.logs.forEach(log => {
                    const isGranted = (log.status === 'granted');
                    const d = new Date(log.created_at);
                    const timeStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    const dateStr = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

                    const searchStr = `${log.user_name || ''} ${log.rfid_uid || ''} ${log.blok_rumah || ''}`.toLowerCase();

                    const userBlock = log.user_name ? `
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm overflow-hidden">
                                ${log.user_photo ? `<img src="${log.user_photo}" alt="${log.user_name}" class="w-full h-full object-cover">` : log.user_name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-white leading-tight">${log.user_name}</p>
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 font-semibold mt-0.5 inline-block">
                                    ${log.user_role || 'Warga'}
                                </span>
                            </div>
                        </div>
                    ` : `
                        <div class="flex items-center gap-2 text-gray-400">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-user-xmark"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-500 dark:text-gray-400">Tidak Terdaftar</p>
                                <span class="text-[10px] text-amber-600">Perlu didaftarkan</span>
                            </div>
                        </div>
                    `;

                    const statusBadge = isGranted ? `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                            <i class="fa-solid fa-door-open text-emerald-500"></i> Palang Terbuka
                        </span>
                    ` : `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-800 dark:text-rose-300">
                            <i class="fa-solid fa-ban text-rose-500"></i> Akses Ditolak
                        </span>
                    `;

                    html += `
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-slate-700/40 transition portal-log-row"
                            data-search="${searchStr}"
                            data-status="${log.status}">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-gray-800 dark:text-white font-mono">${timeStr}</span>
                                <span class="text-[11px] text-gray-400 block">${dateStr}</span>
                            </td>
                            <td class="py-3.5 px-4">${userBlock}</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-700 dark:text-gray-300 block">${log.blok_rumah || '-'}</span>
                                <span class="text-[10px] text-gray-400 font-mono">${log.nik || '-'}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-xs bg-slate-100 dark:bg-slate-700/80 text-slate-800 dark:text-slate-200 px-2 py-1 rounded-md border border-slate-200 dark:border-slate-600">
                                    ${log.rfid_uid || 'MANUAL'}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">${statusBadge}</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-300 text-xs">
                                    <i class="fa-solid fa-microchip text-gray-400 text-[10px]"></i>
                                    ${log.device_id || 'gate-01'}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">
                                <span class="line-clamp-1" title="${log.message || '-'}">${log.message || '-'}</span>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
                filterPortalTable();
            }
        }
    }

    // Filter lokal pencarian & status
    window.filterPortalTable = function() {
        const query = (document.getElementById('filter-search-input')?.value || '').toLowerCase().trim();
        const status = document.getElementById('filter-status-select')?.value || 'all';
        const rows = document.querySelectorAll('.portal-log-row');

        rows.forEach(row => {
            const rowSearch = row.getAttribute('data-search') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchQuery = !query || rowSearch.includes(query);
            const matchStatus = (status === 'all') || (rowStatus === status);

            row.style.display = (matchQuery && matchStatus) ? '' : 'none';
        });
    };

    // Buka Portal Manual
    window.triggerManualOpenGate = function() {
        if (!confirm('Apakah Anda yakin ingin membuka palang pintu gerbang portal perumahan secara manual?')) {
            return;
        }

        const btn = document.getElementById('btn-manual-open');
        const origContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> <span>Membuka...</span>`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/rfid/manual-open', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                alert('✅ Gerbang portal berhasil dibuka secara manual!');
                refreshPortalData(true);
            } else {
                alert('❌ ' + (res.message || 'Gagal membuka gerbang portal.'));
            }
        })
        .catch(err => {
            alert('⚠️ Terjadi kendala jaringan saat mengirim perintah ke gerbang.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = origContent;
        });
    };

    // Mulai Polling Realtime setiap 3.5 detik
    window._portalInterval = setInterval(() => {
        refreshPortalData(false);
    }, 3500);

})();
</script>
