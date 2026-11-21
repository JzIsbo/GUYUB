{{--
    Halaman: Kartu KTP / RFID
    Rute  : /rfid-ktp (ditangani AdminController::loadPage → partial ini)
    Fitur :
      - Tampil UID KTP yang sudah terdaftar
      - Tombol "Daftarkan KTP" → polling + konfirmasi
      - Tombol "Hapus KTP"
      - Log akses portal (untuk role pengurus)
--}}

<div class="p-4 md:p-6 max-w-5xl mx-auto" x-data="rfidPage()" x-init="init()">

    {{-- ===== HEADER ===== --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-id-card text-blue-600"></i>
            Kartu Identitas (RFID/NFC)
        </h2>
        <p class="text-sm text-gray-500 mt-1">Daftarkan kartu KTP Anda untuk membuka portal perumahan secara otomatis.</p>
    </div>

    {{-- ===== KARTU STATUS KTP ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Status Kartu KTP</p>
                @if(Auth::user()->rfid_uid)
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fa-solid fa-circle-check text-green-500"></i> Terdaftar
                        </span>
                    </div>
                    <p class="mt-2 font-mono text-lg font-bold text-gray-800 tracking-widest">
                        {{ Auth::user()->rfid_uid }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">UID kartu terdaftar di akun Anda</p>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-sm font-semibold">
                        <i class="fa-solid fa-circle-xmark text-amber-500"></i> Belum Terdaftar
                    </span>
                    <p class="text-xs text-gray-400 mt-2">Anda belum mendaftarkan kartu KTP ke akun ini.</p>
                @endif
            </div>

            <div class="flex gap-2 flex-wrap">
                {{-- Tombol Daftarkan KTP --}}
                <button @click="startRegistration()"
                        :disabled="scanning"
                        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                    <i class="fa-solid fa-wifi"></i>
                    <span x-text="scanning ? 'Menunggu scan...' : '{{ Auth::user()->rfid_uid ? 'Ganti KTP' : 'Daftarkan KTP' }}'"></span>
                </button>

                {{-- Tombol Hapus (hanya jika sudah ada UID) --}}
                @if(Auth::user()->rfid_uid)
                <button @click="unregisterKtp()"
                        class="flex items-center gap-2 bg-red-50 text-red-600 border border-red-200 px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-red-100 transition cursor-pointer">
                    <i class="fa-solid fa-trash"></i>
                    Hapus KTP
                </button>
                @endif
            </div>
        </div>

        {{-- ===== PANEL SCANNING (muncul saat mode registrasi aktif) ===== --}}
        <div x-show="scanning || scanned" x-transition class="mt-6 border-t border-gray-100 pt-5">

            {{-- Waiting untuk scan --}}
            <div x-show="scanning && !scanned">
                <div class="flex flex-col items-center text-center py-6">
                    {{-- Animasi sinyal RFID --}}
                    <div class="relative w-20 h-20 mb-4">
                        <div class="absolute inset-0 rounded-full bg-blue-100 animate-ping opacity-60"></div>
                        <div class="relative flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 border-2 border-blue-300">
                            <i class="fa-solid fa-id-card text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-blue-700 font-semibold text-base mb-1">Menunggu Scan KTP...</p>
                    <p class="text-sm text-gray-500">Tempelkan kartu KTP ke perangkat RFID yang tersedia.</p>

                    {{-- Countdown --}}
                    <div class="mt-3 flex items-center gap-1.5 text-sm text-gray-400">
                        <i class="fa-regular fa-clock"></i>
                        <span>Sisa waktu: <strong x-text="countdownDisplay" class="text-gray-600"></strong></span>
                    </div>

                    <button @click="cancelScanning()" class="mt-4 text-sm text-gray-400 underline hover:text-gray-600 cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>

            {{-- Kartu terdeteksi — konfirmasi --}}
            <div x-show="scanned && !confirmed">
                <div class="flex flex-col items-center text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-green-50 border-2 border-green-300 flex items-center justify-center mb-3">
                        <i class="fa-solid fa-check text-green-600 text-xl"></i>
                    </div>
                    <p class="font-bold text-green-700 text-base mb-1">Kartu Terdeteksi!</p>
                    <p class="text-sm text-gray-500 mb-1">UID Kartu:</p>
                    <code class="font-mono bg-gray-100 px-4 py-1.5 rounded-lg text-gray-800 text-base font-bold tracking-widest mb-4" x-text="detectedUid"></code>
                    <p class="text-sm text-gray-500 mb-4">Konfirmasi untuk mendaftarkan kartu ini ke akun Anda.</p>
                    <div class="flex gap-3">
                        <button @click="confirmAssign()"
                                :disabled="confirming"
                                class="flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-green-700 transition disabled:opacity-50 cursor-pointer">
                            <i class="fa-solid fa-circle-check"></i>
                            <span x-text="confirming ? 'Menyimpan...' : 'Ya, Daftarkan!'"></span>
                        </button>
                        <button @click="cancelScanning()" class="bg-gray-100 text-gray-600 px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition cursor-pointer">
                            Batal
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== ALERT PESAN ===== --}}
        <div x-show="alertMsg" x-transition class="mt-4">
            <div :class="alertType === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'"
                 class="flex items-center gap-2 border rounded-xl px-4 py-3 text-sm font-medium">
                <i :class="alertType === 'success' ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-circle-exclamation text-red-500'"></i>
                <span x-text="alertMsg"></span>
            </div>
        </div>
    </div>

    {{-- ===== CARA KERJA ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-blue-500"></i>
            Cara Kerja Sistem RFID Portal
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex flex-col items-center text-center p-4 bg-blue-50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold mb-2">1</div>
                <p class="font-semibold text-sm text-gray-700 mb-1">Daftarkan KTP</p>
                <p class="text-xs text-gray-500">Klik "Daftarkan KTP" lalu tempelkan kartu ke terminal RFID yang tersedia.</p>
            </div>
            <div class="flex flex-col items-center text-center p-4 bg-purple-50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold mb-2">2</div>
                <p class="font-semibold text-sm text-gray-700 mb-1">Konfirmasi</p>
                <p class="text-xs text-gray-500">Konfirmasi UID kartu yang terdeteksi di aplikasi untuk menyimpannya ke akun Anda.</p>
            </div>
            <div class="flex flex-col items-center text-center p-4 bg-green-50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold mb-2">3</div>
                <p class="font-semibold text-sm text-gray-700 mb-1">Akses Portal</p>
                <p class="text-xs text-gray-500">Tempelkan KTP di portal perumahan. Jika terdaftar & aktif, portal otomatis terbuka.</p>
            </div>
        </div>
    </div>

    {{-- ===== LOG AKSES PORTAL (hanya untuk pengurus) ===== --}}
    @if(in_array(Auth::user()->role, ['Super Admin', 'RT', 'RW', 'Sekretaris RT', 'Sekretaris RW']))
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-indigo-500"></i>
                Log Akses Portal
            </h3>
            <button @click="loadLogs()" class="text-xs text-blue-600 hover:underline flex items-center gap-1 cursor-pointer">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
        </div>

        {{-- Loading --}}
        <div x-show="logsLoading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        {{-- Tabel log --}}
        <div x-show="!logsLoading && logs.length > 0" class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <th class="pb-3 pr-4">Waktu</th>
                        <th class="pb-3 pr-4">UID Kartu</th>
                        <th class="pb-3 pr-4">Nama / Status Akun</th>
                        <th class="pb-3 pr-4">Perangkat</th>
                        <th class="pb-3">Status Akses</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="log in logs" :key="log.id">
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-2.5 pr-4 text-gray-500 whitespace-nowrap" x-text="formatDate(log.created_at)"></td>
                            <td class="py-2.5 pr-4 font-mono font-semibold text-gray-800" x-text="log.rfid_uid"></td>
                            <td class="py-2.5 pr-4 text-gray-700">
                                <span x-text="log.user ? log.user.name + ' (' + log.user.role + ')' : '— Tidak terdaftar'"></span>
                            </td>
                            <td class="py-2.5 pr-4 text-gray-500 text-xs" x-text="log.device_id || '-'"></td>
                            <td class="py-2.5">
                                <span :class="log.status === 'granted' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                      class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold">
                                    <i :class="log.status === 'granted' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-xmark'"></i>
                                    <span x-text="log.status === 'granted' ? 'Diberikan' : 'Ditolak'"></span>
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Kosong --}}
        <div x-show="!logsLoading && logs.length === 0" class="text-center py-10 text-gray-400">
            <i class="fa-solid fa-inbox text-3xl mb-2"></i>
            <p class="text-sm">Belum ada log akses portal.</p>
        </div>
    </div>
    @endif

</div>

{{-- ===== ALPINE.JS LOGIC ===== --}}
<script>
function rfidPage() {
    return {
        // Registrasi KTP
        scanning:    false,
        scanned:     false,
        confirmed:   false,
        confirming:  false,
        sessionToken: null,
        detectedUid:  null,
        pollInterval: null,
        countdown:    0,
        countdownTimer: null,

        // Alert
        alertMsg:  '',
        alertType: 'success',

        // Log
        logs:        [],
        logsLoading: false,

        get countdownDisplay() {
            const m = Math.floor(this.countdown / 60);
            const s = this.countdown % 60;
            return `${m}:${s.toString().padStart(2, '0')}`;
        },

        init() {
            @if(in_array(Auth::user()->role ?? '', ['Super Admin', 'RT', 'RW', 'Sekretaris RT', 'Sekretaris RW']))
            this.loadLogs();
            @endif
        },

        // --- Mulai proses registrasi ---
        async startRegistration() {
            this.clearAlert();
            this.scanned   = false;
            this.confirmed = false;
            this.detectedUid = null;

            try {
                const res = await fetch('/rfid/start-registration', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await res.json();

                if (data.status === 'success') {
                    this.sessionToken = data.token;
                    this.countdown    = data.ttl || 300;
                    this.scanning     = true;
                    this.startPolling();
                    this.startCountdown();
                } else {
                    this.showAlert('error', 'Gagal memulai sesi. Coba lagi.');
                }
            } catch (e) {
                this.showAlert('error', 'Koneksi gagal. Periksa internet Anda.');
            }
        },

        // --- Polling setiap 1.5 detik ---
        startPolling() {
            this.stopPolling();
            this.pollInterval = setInterval(() => this.checkSession(), 1500);
        },

        stopPolling() {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
            }
        },

        async checkSession() {
            if (!this.sessionToken) return;
            try {
                const res  = await fetch(`/rfid/session/${this.sessionToken}`);
                const data = await res.json();

                if (data.status === 'completed') {
                    this.stopPolling();
                    this.stopCountdown();
                    this.detectedUid = data.uid;
                    this.scanned     = true;
                } else if (data.status === 'expired') {
                    this.cancelScanning();
                    this.showAlert('error', 'Waktu pendaftaran habis. Silakan coba lagi.');
                }
            } catch (e) {
                // Abaikan error jaringan sementara
            }
        },

        // --- Konfirmasi assign UID ke akun ---
        async confirmAssign() {
            this.confirming = true;
            try {
                const res = await fetch('/rfid/assign', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ token: this.sessionToken }),
                });
                const data = await res.json();

                if (data.status === 'success') {
                    this.confirmed = true;
                    this.scanning  = false;
                    this.scanned   = false;
                    this.showAlert('success', `KTP berhasil didaftarkan! UID: ${data.uid}`);
                    // Reload halaman setelah 2 detik agar status kartu terupdate
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    this.showAlert('error', data.message || 'Gagal mendaftarkan KTP.');
                    this.cancelScanning();
                }
            } catch (e) {
                this.showAlert('error', 'Koneksi gagal.');
            } finally {
                this.confirming = false;
            }
        },

        // --- Hapus KTP dari akun ---
        async unregisterKtp() {
            if (!confirm('Yakin ingin menghapus KTP yang terdaftar? Anda tidak akan bisa membuka portal sampai mendaftarkan KTP baru.')) return;

            try {
                const res = await fetch('/rfid/unregister', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await res.json();

                if (data.status === 'success') {
                    this.showAlert('success', 'KTP berhasil dihapus dari akun Anda.');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    this.showAlert('error', data.message || 'Gagal menghapus KTP.');
                }
            } catch (e) {
                this.showAlert('error', 'Koneksi gagal.');
            }
        },

        // --- Batal scan ---
        cancelScanning() {
            this.stopPolling();
            this.stopCountdown();
            this.scanning    = false;
            this.scanned     = false;
            this.confirmed   = false;
            this.sessionToken = null;
            this.detectedUid = null;
        },

        // --- Countdown timer ---
        startCountdown() {
            this.stopCountdown();
            this.countdownTimer = setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                } else {
                    this.cancelScanning();
                    this.showAlert('error', 'Waktu pendaftaran habis. Silakan coba lagi.');
                }
            }, 1000);
        },

        stopCountdown() {
            if (this.countdownTimer) {
                clearInterval(this.countdownTimer);
                this.countdownTimer = null;
            }
        },

        // --- Load log akses portal ---
        async loadLogs() {
            this.logsLoading = true;
            try {
                const res  = await fetch('/rfid/logs');
                const data = await res.json();
                if (data.status === 'success') {
                    this.logs = data.logs.data || [];
                }
            } catch (e) {
                // ignore
            } finally {
                this.logsLoading = false;
            }
        },

        // --- Alert helper ---
        showAlert(type, msg) {
            this.alertType = type;
            this.alertMsg  = msg;
            setTimeout(() => { this.alertMsg = ''; }, 5000);
        },

        clearAlert() {
            this.alertMsg = '';
        },

        // --- Format tanggal ---
        formatDate(dateStr) {
            return new Date(dateStr).toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        },
    };
}
</script>
