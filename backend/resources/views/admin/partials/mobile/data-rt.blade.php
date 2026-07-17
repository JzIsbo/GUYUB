<div class="p-3 space-y-3">
    <!-- Profile Card -->
    <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-4 rounded-2xl shadow-xl text-white relative overflow-hidden">
        <div class="relative z-10">
            <span class="bg-blue-500/10 text-blue-400 text-[8px] px-2 py-1 rounded-lg font-black border border-blue-500/20 uppercase tracking-wider">Profil RT & RW</span>
            <h2 class="text-lg font-black mt-2 tracking-tight">RT {{ $rt_info->nomor_rt ?? '00' }} / RW {{ $rt_info->nomor_rw ?? '00' }}</h2>
            <p class="text-gray-400 text-[10px] mt-0.5 font-bold uppercase tracking-wider">{{ $rt_info->nama_wilayah ?? 'Belum Diatur' }}</p>
            <div class="mt-3 pt-3 border-t border-white/5">
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Alamat:</p>
                <p class="text-[10px] text-gray-300 font-medium mt-0.5">{{ $rt_info->alamat_lengkap ?? 'Belum dikonfigurasi.' }}</p>
            </div>
        </div>
        <i class="fa-solid fa-map-location-dot absolute -bottom-6 -right-6 text-[80px] opacity-5 rotate-12"></i>
    </div>

    <!-- Form -->
    <div class="bg-white p-4 rounded-xl border border-gray-50 shadow-sm">
        <h3 class="text-sm font-black text-gray-800 mb-1">Konfigurasi Identitas RT & RW</h3>
        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-3">Perbarui data pimpinan wilayah</p>

        <form id="form-rt-lokal" onsubmit="simpanProfilRt(event)">
            @csrf
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nomor RT</label>
                        <input type="text" name="nomor_rt" value="{{ $rt_info->nomor_rt ?? '' }}" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl disabled:opacity-60">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nomor RW</label>
                        <input type="text" name="nomor_rw" value="{{ $rt_info->nomor_rw ?? '' }}" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl disabled:opacity-60">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Nama Wilayah</label>
                    <input type="text" name="nama_wilayah" value="{{ $rt_info->nama_wilayah ?? '' }}" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-2 px-3 rounded-xl disabled:opacity-60">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Alamat Sekretariat</label>
                    <textarea name="alamat_lengkap" rows="3" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-2 px-3 rounded-xl resize-none disabled:opacity-60">{{ $rt_info->alamat_lengkap ?? '' }}</textarea>
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="button" onclick="aktifkanModeUbah('{{ $rt_info->nomor_rt ?? '' }}', '{{ $rt_info->nomor_rw ?? '' }}', '{{ addslashes($rt_info->nama_wilayah ?? '') }}', '{{ addslashes($rt_info->alamat_lengkap ?? '') }}')" class="flex-1 bg-yellow-100 text-yellow-600 py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-1">
                    <i class="fa-solid fa-pen text-[10px]"></i> Ubah
                </button>
                <button type="submit" id="btn-submit" disabled class="flex-1 bg-gray-300 text-white py-2.5 rounded-xl font-bold text-xs cursor-not-allowed flex items-center justify-center gap-1">
                    <i class="fa-solid fa-save text-[10px]"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function aktifkanModeUbah(rt, rw, wilayah, alamat) {
    document.querySelectorAll('.input-rt').forEach(input => input.disabled = false);
    const btn = document.getElementById('btn-submit');
    btn.disabled = false;
    btn.classList.replace('bg-gray-300', 'bg-[#2563EB]');
    btn.classList.remove('cursor-not-allowed');
    btn.classList.add('hover:bg-blue-700', 'shadow-lg');
    btn.innerHTML = '<i class="fa-solid fa-save mr-1 text-[10px]"></i> Simpan';
}

function simpanProfilRt(event) {
    event.preventDefault();
    const form = document.getElementById('form-rt-lokal');
    const formData = new FormData(form);
    fetch("{{ route('rt.store') }}", { method: "POST", body: formData, headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') { alert("✅ " + data.message); if (typeof loadPage === 'function') loadPage('data-rt'); else window.location.reload(); }
        else { alert("❌ Gagal: " + data.message); }
    });
}
</script>
