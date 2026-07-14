<div>
    <div class="flex flex-wrap justify-between items-end mb-4 gap-3">
        <div>
            <h2 class="text-xl font-black text-gray-800 tracking-tight">Aktivitas Pengguna</h2>
            <p class="text-gray-500 text-xs mt-0.5">Rekam jejak aksi sistem terbaru secara langsung.</p>
        </div>

        <div class="flex items-center gap-1.5 px-3 py-1 bg-green-50 rounded-full border border-green-100 shadow-sm">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <span class="text-[9px] font-black text-green-600 uppercase tracking-wider">Realtime Aktif</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-[9px] text-gray-400 uppercase tracking-widest">
                        <th class="px-5 py-3.5 font-bold">Pengguna</th>
                        <th class="px-5 py-3.5 font-bold">Tindakan</th>
                        <th class="px-5 py-3.5 font-bold">Deskripsi</th>
                        <th class="px-5 py-3.5 font-bold text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody id="tabel-aktivitas" class="divide-y divide-gray-50">
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-spinner fa-spin text-xl mb-2 text-blue-500"></i>
                            <p class="text-xs font-medium">Memuat data aktivitas...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function muatAktivitas() {
        if (!document.getElementById('tabel-aktivitas')) return;

        // JURUS ANTI-CACHE: Tambahkan timestamp waktu agar URL selalu unik tiap detik!
        let urlRealtime = "{{ route('aktivitas.data') }}?_t=" + new Date().getTime();

        fetch(urlRealtime, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache', // Paksa browser jangan pakai memori lama
                'Pragma': 'no-cache'
            }
        })
        .then(async response => {
            if (!response.ok) {
                let errText = await response.text();
                throw new Error("SERVER ERROR: " + errText);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.error) {
                throw new Error("CONTROLLER ERROR: " + data.message);
            }

            let html = '';

            // Action tag coloring helper
            function dapatkanBadgeClass(action) {
                let act = (action || '').toUpperCase();
                if (act.includes('BUAT') || act.includes('TAMBAH')) {
                    return 'bg-emerald-50 text-emerald-600 border-emerald-100';
                }
                if (act.includes('UPDATE') || act.includes('RESPON') || act.includes('SETTING') || act.includes('SINKRONISASI')) {
                    return 'bg-amber-50 text-amber-600 border-amber-100';
                }
                if (act.includes('HAPUS') || act.includes('BERSIH') || act.includes('DELETE')) {
                    return 'bg-rose-50 text-rose-600 border-rose-100';
                }
                if (act.includes('LOGIN')) {
                    return 'bg-indigo-50 text-indigo-600 border-indigo-100';
                }
                return 'bg-blue-50 text-blue-600 border-blue-100';
            }

            if(!data || data.length === 0) {
                html = `<tr><td colspan="4" class="px-5 py-6 text-center text-gray-400 text-xs font-medium">Belum ada aktivitas tercatat.</td></tr>`;
            } else {
                data.forEach(item => {
                    let photoUrl = item.photo || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name || 'User'));
                    let badgeClass = dapatkanBadgeClass(item.action);
                    html += `
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-5 py-2.5">
                            <div class="flex items-center gap-2">
                                <img src="${photoUrl}" class="w-8 h-8 rounded-full border border-gray-200 object-cover shrink-0">
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-800 text-xs truncate">${item.name || '-'}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">${item.hak_akses || 'Sistem'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-2.5">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold border ${badgeClass} inline-block whitespace-nowrap">
                                ${item.action || '-'}
                            </span>
                        </td>
                        <td class="px-5 py-2.5 text-xs text-gray-600 font-medium max-w-xs truncate">
                            ${item.description || '-'}
                        </td>
                        <td class="px-5 py-2.5 text-right text-[10px] text-gray-400 font-medium whitespace-nowrap">
                            <i class="fa-regular fa-clock mr-0.5"></i> ${item.waktu_berlalu || '-'}
                        </td>
                    </tr>`;
                });
            }
            document.getElementById('tabel-aktivitas').innerHTML = html;
        })
        .catch(err => {
            clearInterval(window.realtimeInterval);
            let rawError = err.message.replace(/</g, "&lt;").replace(/>/g, "&gt;");

            document.getElementById('tabel-aktivitas').innerHTML = `
                <tr>
                    <td colspan="4" class="px-8 py-8">
                        <div class="bg-red-50 p-6 rounded-[1.5rem] border border-red-200 text-left">
                            <h3 class="text-red-700 font-black text-lg mb-2"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Sistem Mendeteksi Error!</h3>
                            <p class="text-sm text-red-600 font-medium mb-4">Mohon screenshot kotak ini dan kirimkan ke saya:</p>
                            <div class="bg-white p-4 rounded-xl border border-red-100 text-xs text-red-500 font-mono h-40 overflow-y-auto w-full break-words">
                                ${rawError}
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    muatAktivitas();
    if(window.realtimeInterval) clearInterval(window.realtimeInterval);
    window.realtimeInterval = setInterval(muatAktivitas, 5000);
</script>
