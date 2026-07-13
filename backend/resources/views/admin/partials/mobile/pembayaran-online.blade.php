<div class="p-3 space-y-3">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-gray-800">Pembayaran Online</h2>
            <p class="text-[10px] text-gray-500">Konfigurasi Payment Gateway</p>
        </div>
        @if(isset($gateway) && $gateway->is_active)
        <div class="px-2 py-1 bg-green-50 border border-green-100 text-green-600 rounded-lg flex items-center font-bold text-[9px]">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1 animate-pulse"></span> Aktif
        </div>
        @else
        <div class="px-2 py-1 bg-red-50 border border-red-100 text-red-600 rounded-lg flex items-center font-bold text-[9px]">
            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1"></span> Belum Set
        </div>
        @endif
    </div>

    <!-- Config Form -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-2 mb-3">
            <div class="bg-blue-50 p-2 rounded-lg text-blue-600"><i class="fa-solid fa-gears text-sm"></i></div>
            <div>
                <h3 class="text-xs font-bold text-gray-800">API Gateway</h3>
                <p class="text-[9px] text-gray-500">Kredensial Midtrans</p>
            </div>
        </div>

        <form id="form-konfigurasi-gateway" action="/pembayaran/gateway/store" method="POST">
            @csrf
            <div class="space-y-2">
                <div>
                    <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Environment</label>
                    <select name="environment" class="w-full py-2 px-3 border rounded-xl bg-gray-50 text-sm font-medium">
                        <option value="sandbox" {{ (isset($gateway) && $gateway->environment == 'sandbox') ? 'selected' : '' }}>Sandbox</option>
                        <option value="production" {{ (isset($gateway) && $gateway->environment == 'production') ? 'selected' : '' }}>Production</option>
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Merchant ID</label>
                    <input type="text" name="merchant_id" placeholder="G123456789" required value="{{ $gateway->merchant_id ?? '' }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 font-mono text-sm">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Client Key</label>
                    <input type="text" name="client_key" placeholder="Client Key" required value="{{ $gateway->client_key ?? '' }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 font-mono text-sm">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Server Key</label>
                    <input type="password" name="server_key" placeholder="Server Key" required value="{{ $gateway->server_key ?? '' }}" class="w-full py-2 px-3 border rounded-xl bg-gray-50 font-mono text-sm">
                </div>
                <button type="button" onclick="simpanDataUmum(event, 'form-konfigurasi-gateway', 'pembayaran-online')" class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-bold text-xs mt-2">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Methods -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-xs font-bold text-gray-800 mb-2">Metode Tersedia</h3>
        <div class="space-y-2">
            <div class="flex justify-between items-center p-3 border rounded-xl bg-gray-50">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-[10px]"><i class="fa-solid fa-building-columns"></i></div>
                    <div>
                        <h4 class="font-bold text-[10px] text-gray-800">Bank Transfer</h4>
                        <p class="text-[8px] text-gray-500 uppercase">Virtual Account</p>
                    </div>
                </div>
                <i class="fa-solid fa-toggle-{{ (isset($gateway) && $gateway->is_active) ? 'on text-green-500' : 'off text-gray-300' }} text-lg"></i>
            </div>
            <div class="flex justify-between items-center p-3 border rounded-xl bg-gray-50">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center text-[10px]"><i class="fa-solid fa-qrcode"></i></div>
                    <div>
                        <h4 class="font-bold text-[10px] text-gray-800">QRIS</h4>
                        <p class="text-[8px] text-gray-500 uppercase">All App</p>
                    </div>
                </div>
                <i class="fa-solid fa-toggle-{{ (isset($gateway) && $gateway->is_active) ? 'on text-green-500' : 'off text-gray-300' }} text-lg"></i>
            </div>
        </div>
        <p class="text-[9px] text-gray-400 mt-2 text-center">Aktif otomatis jika Server Key valid.</p>
    </div>
</div>
