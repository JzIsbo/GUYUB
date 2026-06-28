<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pembayaran Online</h2>
            <p class="text-sm text-gray-500">Konfigurasi Payment Gateway & Manajemen Metode Pembayaran</p>
        </div>

        @if(isset($gateway) && $gateway->is_active)
        <div class="px-4 py-2 bg-green-50 border border-green-100 text-green-600 rounded-xl flex items-center font-bold text-sm shadow-sm">
            <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
            Gateway Aktif
        </div>
        @else
        <div class="px-4 py-2 bg-red-50 border border-red-100 text-red-600 rounded-xl flex items-center font-bold text-sm shadow-sm">
            <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
            Gateway Belum Disetting
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-50 p-3 rounded-2xl mr-4 text-blue-600">
                        <i class="fa-solid fa-gears text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-800">Konfigurasi API Gateway</h3>
                        <p class="text-xs text-gray-500">Pengaturan kredensial untuk menghubungkan KAS RT dengan Midtrans.</p>
                    </div>
                </div>

                <form id="form-konfigurasi-gateway" action="/pembayaran/gateway/store" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1 mb-2 block">Environment</label>
                            <div class="relative">
                                <select name="environment" class="w-full p-3.5 border rounded-xl bg-gray-50 text-gray-700 font-medium focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition-all appearance-none">
                                    <option value="sandbox" {{ (isset($gateway) && $gateway->environment == 'sandbox') ? 'selected' : '' }}>Sandbox (Testing / Development)</option>
                                    <option value="production" {{ (isset($gateway) && $gateway->environment == 'production') ? 'selected' : '' }}>Production (Live)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-gray-400 text-sm pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1 mb-2 block">Merchant ID</label>
                            <input type="text" name="merchant_id" placeholder="Contoh: G123456789" required
                                   value="{{ $gateway->merchant_id ?? '' }}"
                                   class="w-full p-3.5 border rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition-all font-mono text-sm">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1 mb-2 block">Client Key</label>
                            <input type="text" name="client_key" placeholder="Masukkan Client Key Midtrans" required
                                   value="{{ $gateway->client_key ?? '' }}"
                                   class="w-full p-3.5 border rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition-all font-mono text-sm">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1 mb-2 block">Server Key</label>
                            <input type="password" name="server_key" placeholder="Masukkan Server Key Midtrans" required
                                   value="{{ $gateway->server_key ?? '' }}"
                                   class="w-full p-3.5 border rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition-all font-mono text-sm">
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <button type="button" onclick="simpanDataUmum(event, 'form-konfigurasi-gateway', 'pembayaran-online')"
                                    class="bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 transition-all">
                                Simpan Konfigurasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Metode Tersedia</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center p-4 border rounded-2xl bg-gray-50 hover:bg-white transition-all cursor-default">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-building-columns"></i></div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">Bank Transfer</h4>
                                <p class="text-[10px] text-gray-500 font-medium uppercase">Virtual Account</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-toggle-{{ (isset($gateway) && $gateway->is_active) ? 'on text-green-500' : 'off text-gray-300' }} text-2xl"></i>
                    </div>

                    <div class="flex justify-between items-center p-4 border rounded-2xl bg-gray-50 hover:bg-white transition-all cursor-default">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center"><i class="fa-solid fa-qrcode"></i></div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">QRIS</h4>
                                <p class="text-[10px] text-gray-500 font-medium uppercase">All Payment App</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-toggle-{{ (isset($gateway) && $gateway->is_active) ? 'on text-green-500' : 'off text-gray-300' }} text-2xl"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-4 text-center border-t border-gray-100 pt-4">
                    Metode ini akan otomatis aktif jika Server Key yang dimasukkan valid.
                </p>
            </div>
        </div>
    </div>
</div>
