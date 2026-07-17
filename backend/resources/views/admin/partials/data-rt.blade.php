<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">

    <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-8 rounded-[2.5rem] shadow-xl text-white h-fit relative overflow-hidden">
        <div class="relative z-10">
            <span class="bg-blue-500/10 text-blue-400 text-[10px] px-3 py-1.5 rounded-xl font-black border border-blue-500/20 uppercase tracking-widest">Profil Lingkungan RT & RW</span>
            <h2 class="text-3xl font-black mt-4 tracking-tight">RT {{ $rt_info->nomor_rt ?? '00' }} / RW {{ $rt_info->nomor_rw ?? '00' }}</h2>
            <p class="text-gray-400 text-sm mt-1 font-bold uppercase tracking-wider">{{ $rt_info->nama_wilayah ?? 'Belum Diatur' }}</p>

            <div class="mt-8 pt-6 border-t border-white/5 space-y-3">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-none">Alamat Sekretariat:</p>
                <p class="text-sm text-gray-300 font-medium leading-relaxed">{{ $rt_info->alamat_lengkap ?? 'Alamat sekretariat belum dikonfigurasi.' }}</p>
            </div>
        </div>
        <i class="fa-solid fa-map-location-dot absolute -bottom-10 -right-10 text-[150px] opacity-5 rotate-12"></i>
    </div>

    <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)]">
        <h3 class="text-xl font-black text-gray-800 mb-2 tracking-tight">Konfigurasi Identitas RT & RW</h3>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-6">Perbarui data identitas pimpinan wilayah</p>

        <form id="form-rt-lokal" onsubmit="simpanProfilRt(event)">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor RT</label>
                    <input type="text" name="nomor_rt" value="{{ $rt_info->nomor_rt ?? '' }}" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all disabled:opacity-60">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor RW</label>
                    <input type="text" name="nomor_rw" value="{{ $rt_info->nomor_rw ?? '' }}" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all disabled:opacity-60">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama Wilayah / Dusun / Perumahan</label>
                <input type="text" name="nama_wilayah" value="{{ $rt_info->nama_wilayah ?? '' }}" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-bold text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all disabled:opacity-60">
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Alamat Lengkap Sekretariat</label>
                <textarea name="alamat_lengkap" rows="4" disabled required class="input-rt w-full bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 py-3 px-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none transition-all disabled:opacity-60">{{ $rt_info->alamat_lengkap ?? '' }}</textarea>
            </div>

            <div class="flex flex-row-reverse gap-4">
                <button type="submit" id="btn-submit" disabled class="flex-1 bg-gray-300 text-white px-6 py-4 rounded-2xl font-bold transition-all flex items-center justify-center cursor-not-allowed">
                    <i class="fa-solid fa-square-check mr-2"></i> Simpan Perubahan Profil
                </button>

                <button type="button"
                        onclick="aktifkanModeUbah('{{ $rt_info->nomor_rt ?? '' }}', '{{ $rt_info->nomor_rw ?? '' }}', '{{ addslashes($rt_info->nama_wilayah ?? '') }}', '{{ addslashes($rt_info->alamat_lengkap ?? '') }}')"
                        class="flex-1 bg-yellow-100 text-yellow-600 px-6 py-4 rounded-2xl font-bold hover:bg-yellow-200 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-pen mr-2"></i> Mode Ubah Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function aktifkanModeUbah(rt, rw, wilayah, alamat) {
    // 1. Aktifkan semua input
    document.querySelectorAll('.input-rt').forEach(input => input.disabled = false);

    // 2. Aktifkan tombol simpan
    const btn = document.getElementById('btn-submit');
    btn.disabled = false;
    btn.classList.replace('bg-gray-300', 'bg-[#2563EB]');
    btn.classList.remove('cursor-not-allowed');
    btn.classList.add('hover:bg-blue-700', 'shadow-lg', 'shadow-blue-900/20');
    btn.innerHTML = '<i class="fa-solid fa-save mr-2"></i> Simpan Perubahan Profil';
}

function simpanProfilRt(event) {
    event.preventDefault();
    const form = document.getElementById('form-rt-lokal');
    const formData = new FormData(form);

    fetch("{{ route('rt.store') }}", {
        method: "POST",
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert("✅ " + data.message);
            if (typeof loadPage === 'function') loadPage('data-rt');
            else window.location.reload();
        } else {
            alert("❌ Gagal: " + data.message);
        }
    });
}
</script>
