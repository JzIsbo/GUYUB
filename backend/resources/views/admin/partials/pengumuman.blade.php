<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengumuman Warga</h2>
            <p class="text-sm text-gray-500">Buat dan sebarkan informasi penting ke warga</p>
        </div>
        @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
        <button onclick="document.getElementById('modal-tambah-pengumuman').classList.remove('hidden')" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">
            + Buat Pengumuman
        </button>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($list_pengumuman as $info)
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative">
            @if(in_array(Auth::user()->role, ['Super Admin', 'RT']))
            <div class="absolute top-4 right-4">
                <button onclick="hapusPengumuman({{ $info->id }})" class="text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button>
            </div>
            @endif
            <span class="text-[10px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($info->created_at)->format('d M Y') }}</span>
            <h3 class="text-lg font-bold text-gray-800 mt-1 mb-3">{{ $info->judul }}</h3>
            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $info->isi }}</p>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[2rem] shadow-sm border border-gray-100 p-12 text-center">
            <p class="text-gray-400 font-medium">Belum ada pengumuman yang dibuat.</p>
        </div>
        @endforelse
    </div>
</div>

<div id="modal-tambah-pengumuman" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-3xl w-[400px]">
        <h2 class="text-xl font-bold mb-4">Buat Pengumuman Baru</h2>
        <form id="form-tambah-pengumuman" action="/pengumuman/store" method="POST">
            @csrf
            <div class="space-y-4">
                <input type="text" name="judul" placeholder="Judul Pengumuman" class="w-full p-3 border rounded-xl" required>
                <textarea name="isi" placeholder="Isi Pengumuman lengkap..." class="w-full p-3 border rounded-xl h-32" required></textarea>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-tambah-pengumuman').classList.add('hidden')" class="w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200">Batal</button>
                    <button type="button" onclick="simpanDataUmum(event, 'form-tambah-pengumuman', 'pengumuman')" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700">Siarkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function hapusPengumuman(id) {
    if(!confirm('Hapus pengumuman ini?')) return;

    let formData = new FormData();
    formData.append('id', id);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/pengumuman/delete', {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(res => res.json()).then(data => {
        alert(data.message);
        switchPage('pengumuman', document.querySelector('.menu-active'));
    });
}
</script>
