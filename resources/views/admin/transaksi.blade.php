<x-dashboard>
    <div class="container px-4 sm:px-6 mx-auto grid">
        <h1 class="text-2xl sm:text-3xl font-extrabold mb-4 sm:mb-6 text-gray-800">Daftar Transaksi Saya</h1>
    
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 shadow text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif
    
        @if($transaksi->isEmpty())
            <div class="flex justify-center items-center h-32">
                <p class="text-gray-500 text-base sm:text-lg">Belum ada transaksi.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-lg shadow -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200 rounded-lg text-sm sm:text-base">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                            <tr>
                                <th scope="col" class="hidden sm:table-cell p-3 text-left font-semibold text-gray-700">#</th>
                                <th scope="col" class="p-3 text-left font-semibold text-gray-700">Nama Ponsel</th>
                                <th scope="col" class="hidden md:table-cell p-3 text-left font-semibold text-gray-700">Nama Customer</th>
                                <th scope="col" class="hidden sm:table-cell p-3 text-left font-semibold text-gray-700">Jumlah</th>
                                <th scope="col" class="p-3 text-left font-semibold text-gray-700">Harga</th>
                                <th scope="col" class="hidden lg:table-cell p-3 text-left font-semibold text-gray-700">Metode</th>
                                <th scope="col" class="p-3 text-left font-semibold text-gray-700">Status</th>
                                <th scope="col" class="hidden md:table-cell p-3 text-left font-semibold text-gray-700">Pengiriman</th>
                                <th scope="col" class="hidden lg:table-cell p-3 text-left font-semibold text-gray-700">Tanggal</th>
                                <th scope="col" class="p-3 text-left font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaksi as $index => $transaksi)
                            <tr class="transition-colors hover:bg-blue-50">
                                <td class="hidden sm:table-cell p-3 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="p-3 whitespace-nowrap font-medium text-gray-800">
                                    <div class="flex flex-col sm:flex-row sm:items-center">
                                        <span>{{ $transaksi->ponsel->merk }}</span>
                                        <span class="sm:ml-1">{{ $transaksi->ponsel->model }}</span>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell p-3 whitespace-nowrap">{{ $transaksi->customer->nama }}</td>
                                <td class="hidden sm:table-cell p-3 whitespace-nowrap">{{ $transaksi->jumlah }}</td>
                                <td class="p-3 whitespace-nowrap font-semibold text-blue-700">
                                    <span class="text-xs sm:text-sm">Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="hidden lg:table-cell p-3 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        {{ ucfirst($transaksi->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    @if($transaksi->status === 'tertunda')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Tertunda</span>
                                    @elseif($transaksi->status === 'selesai')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Selesai</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">{{ ucfirst($transaksi->status) }}</span>
                                    @endif
                                </td>
                                <td class="hidden md:table-cell p-3 whitespace-nowrap">
                                    @if($transaksi->status_pengiriman === 'belum_dikirim')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Belum</span>
                                    @elseif($transaksi->status_pengiriman === 'terkirim')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Terkirim</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">{{ ucfirst($transaksi->status_pengiriman) ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell p-3 whitespace-nowrap text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    <a href="{{ route('admin.edit.transaksi', $transaksi->id_beli_ponsel) }}" 
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-colors duration-200 shadow-sm">
                                        <span class="hidden sm:inline">Edit</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-dashboard>
