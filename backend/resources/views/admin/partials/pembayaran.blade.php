<div class="space-y-6">

    <!-- HEADER -->

    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">

        <div class="flex justify-between items-center">

            <div>

                <h1 class="text-3xl font-black text-gray-800">
                    Payment Gateway
                </h1>

                <p class="text-gray-400 mt-2">
                    Pembayaran QRIS, VA, E-Wallet
                </p>

            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all">

                + Buat Tagihan

            </button>

        </div>

    </div>

    <!-- CARD -->

    <div class="grid grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">

            <p class="text-sm text-gray-400 font-bold">
                Total Tagihan
            </p>

            <h2 class="text-3xl font-black text-gray-800 mt-2">
                {{ $tagihan->count() }}
            </h2>

        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">

            <p class="text-sm text-gray-400 font-bold">
                Pending
            </p>

            <h2 class="text-3xl font-black text-yellow-500 mt-2">
                {{ $tagihan->where('status','pending')->count() }}
            </h2>

        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">

            <p class="text-sm text-gray-400 font-bold">
                Success
            </p>

            <h2 class="text-3xl font-black text-green-500 mt-2">
                {{ $tagihan->where('status','success')->count() }}
            </h2>

        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">

            <p class="text-sm text-gray-400 font-bold">
                Total Income
            </p>

            <h2 class="text-3xl font-black text-blue-600 mt-2">
                Rp{{ number_format($tagihan->sum('jumlah')) }}
            </h2>

        </div>

    </div>

    <!-- TABLE -->

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8">

        <div class="flex justify-between items-center mb-6">

            <h2 class="text-xl font-black text-gray-800">
                Daftar Pembayaran
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b border-gray-100">

                        <th class="text-left py-4 text-sm text-gray-400">
                            Invoice
                        </th>

                        <th class="text-left py-4 text-sm text-gray-400">
                            Nama
                        </th>

                        <th class="text-left py-4 text-sm text-gray-400">
                            Jenis
                        </th>

                        <th class="text-left py-4 text-sm text-gray-400">
                            Jumlah
                        </th>

                        <th class="text-left py-4 text-sm text-gray-400">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($tagihan as $item)

                    <tr class="border-b border-gray-50">

                        <td class="py-5 font-bold text-gray-700">
                            {{ $item->invoice_id }}
                        </td>

                        <td class="py-5">
                            {{ $item->nama }}
                        </td>

                        <td class="py-5">
                            {{ $item->jenis }}
                        </td>

                        <td class="py-5 font-bold text-blue-600">
                            Rp{{ number_format($item->jumlah) }}
                        </td>

                        <td class="py-5">

                            @if($item->status == 'success')

                                <span class="bg-green-100 text-green-600 px-4 py-2 rounded-full text-xs font-bold">
                                    SUCCESS
                                </span>

                            @elseif($item->status == 'failed')

                                <span class="bg-red-100 text-red-600 px-4 py-2 rounded-full text-xs font-bold">
                                    FAILED
                                </span>

                            @else

                                <span class="bg-yellow-100 text-yellow-600 px-4 py-2 rounded-full text-xs font-bold">
                                    PENDING
                                </span>

                            @endif

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
