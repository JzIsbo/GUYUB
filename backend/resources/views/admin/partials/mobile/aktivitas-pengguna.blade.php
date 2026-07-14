<div class="p-3">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-sm font-bold text-gray-800">Aktivitas Pengguna</h2>
            <p class="text-[10px] text-gray-500">Rekam jejak aksi terbaru</p>
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-green-50 rounded-lg border border-green-100">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <span class="text-[8px] font-black text-green-600 uppercase tracking-wider">Live</span>
        </div>
    </div>

    <div id="container-aktivitas" class="space-y-2">
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm text-center">
            <i class="fa-solid fa-spinner fa-spin text-xl mb-2 text-blue-500"></i>
            <p class="text-xs font-medium text-gray-400">Memuat...</p>
        </div>
    </div>
</div>

<script>
    function muatAktivitas() {
        if (!document.getElementById('container-aktivitas')) return;
        let urlRealtime = "{{ route('aktivitas.data') }}?_t=" + new Date().getTime();
        fetch(urlRealtime, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Cache-Control': 'no-cache', 'Pragma': 'no-cache' } })
        .then(async response => { if (!response.ok) { let errText = await response.text(); throw new Error("ERROR: " + errText); } return response.json(); })
        .then(data => {
            if (data && data.error) throw new Error(data.message);
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
                html = `<div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm text-center text-gray-400 text-xs">Belum ada aktivitas.</div>`;
            } else {
                data.forEach(item => {
                    let photoUrl = item.photo || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name || 'User') + '&size=28');
                    let badgeClass = dapatkanBadgeClass(item.action);
                    html += `
                    <div class="bg-white rounded-xl border border-gray-100 p-2.5 shadow-sm">
                        <div class="flex items-center gap-2">
                            <img src="${photoUrl}" class="w-7 h-7 rounded-full border border-gray-200 object-cover flex-shrink-0">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1.5">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <p class="font-bold text-gray-800 text-[10px] truncate">${item.name || '-'}</p>
                                        <span class="text-[7px] text-gray-400 font-bold uppercase tracking-wider bg-gray-50 border border-gray-100 px-1 py-0.2 rounded">${item.hak_akses || 'Sistem'}</span>
                                    </div>
                                    <span class="text-[8px] text-gray-400 whitespace-nowrap shrink-0">${item.waktu_berlalu || '-'}</span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="px-1 py-0.2 rounded text-[7px] font-bold border ${badgeClass} shrink-0">${item.action || '-'}</span>
                                    <span class="text-[9px] text-gray-500 truncate">${item.description || ''}</span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            }
            document.getElementById('container-aktivitas').innerHTML = html;
        })
        .catch(err => {
            clearInterval(window.realtimeInterval);
            document.getElementById('container-aktivitas').innerHTML = `<div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm text-center text-red-500 text-xs">Error memuat data</div>`;
        });
    }
    muatAktivitas();
    if(window.realtimeInterval) clearInterval(window.realtimeInterval);
    window.realtimeInterval = setInterval(muatAktivitas, 5000);
</script>
