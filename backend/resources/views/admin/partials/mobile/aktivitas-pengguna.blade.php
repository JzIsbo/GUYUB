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

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[350px]">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-[9px] text-gray-400 uppercase tracking-wider">
                        <th class="px-3 py-2 font-bold">Pengguna</th>
                        <th class="px-3 py-2 font-bold">Aksi</th>
                        <th class="px-3 py-2 font-bold text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody id="tabel-aktivitas" class="divide-y divide-gray-50">
                    <tr>
                        <td colspan="3" class="px-3 py-6 text-center text-gray-400">
                            <i class="fa-solid fa-spinner fa-spin text-xl mb-2 text-blue-500"></i>
                            <p class="text-xs font-medium">Memuat...</p>
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
        let urlRealtime = "{{ route('aktivitas.data') }}?_t=" + new Date().getTime();
        fetch(urlRealtime, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Cache-Control': 'no-cache', 'Pragma': 'no-cache' } })
        .then(async response => { if (!response.ok) { let errText = await response.text(); throw new Error("ERROR: " + errText); } return response.json(); })
        .then(data => {
            if (data && data.error) throw new Error(data.message);
            let html = '';
            if(!data || data.length === 0) {
                html = `<tr><td colspan="3" class="px-3 py-6 text-center text-gray-400 text-xs">Belum ada aktivitas.</td></tr>`;
            } else {
                data.forEach(item => {
                    let photoUrl = item.photo || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name || 'User') + '&size=32');
                    html += `
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-3 py-2">
                            <div class="flex items-center gap-2">
                                <img src="${photoUrl}" class="w-6 h-6 rounded-full border border-gray-200 object-cover">
                                <div>
                                    <p class="font-bold text-gray-800 text-[10px] truncate max-w-[80px]">${item.name || '-'}</p>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase">${item.hak_akses || 'Sistem'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-2">
                            <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded text-[8px] font-bold">${item.action || '-'}</span>
                            <p class="text-[9px] text-gray-500 mt-0.5 truncate max-w-[100px]">${item.description || ''}</p>
                        </td>
                        <td class="px-3 py-2 text-right text-[9px] text-gray-400 whitespace-nowrap">${item.waktu_berlalu || '-'}</td>
                    </tr>`;
                });
            }
            document.getElementById('tabel-aktivitas').innerHTML = html;
        })
        .catch(err => {
            clearInterval(window.realtimeInterval);
            document.getElementById('tabel-aktivitas').innerHTML = `<tr><td colspan="3" class="px-3 py-4 text-center text-red-500 text-xs">Error memuat data</td></tr>`;
        });
    }
    muatAktivitas();
    if(window.realtimeInterval) clearInterval(window.realtimeInterval);
    window.realtimeInterval = setInterval(muatAktivitas, 5000);
</script>
