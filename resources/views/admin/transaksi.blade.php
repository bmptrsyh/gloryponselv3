<x-dashboard>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Transaksi Saya</h1>
    
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
    
        @if($transaksi->isEmpty())
            <p class="text-gray-600">Belum ada transaksi.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 rounded-lg text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">#</th>
                            <th class="p-3 text-left">Nama Ponsel</th>
                            <th class="p-3 text-left">Nama Customer</th>
                            <th class="p-3 text-left">Jumlah</th>
                            <th class="p-3 text-left">Harga</th>
                            <th class="p-3 text-left">Metode Pembayaran</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Status Pengiriman</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $index => $transaksi)
                        <tr class="border-t">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $transaksi->ponsel->merk }}{{ $transaksi->ponsel->model }}</td>
                            <td class="p-3">{{ $transaksi->customer->nama }}</td>
                            <td class="p-3">{{ $transaksi->jumlah }}</td>
                            <td class="p-3">Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</td>
                            <td class="p-3">{{ ucfirst($transaksi->metode_pembayaran) }}</td>
                            <td class="p-3">
                                @if($transaksi->status === 'tertunda')
                                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-sm">Tertunda</span>
                                @elseif($transaksi->status === 'selesai')
                                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-sm">Selesai</span>
                                @else
                                    <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm">{{ ucfirst($transaksi->status) }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($transaksi->status_pengiriman === 'belum_dikirim')
                                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-sm">Belum Dikirim</span>
                                @elseif($transaksi->status_pengiriman === 'terkirim')
                                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-sm">Terkirim</span>
                                @else
                                    <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm">{{ ucfirst($transaksi->status_pengiriman) ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y H:i') }}</td>
                            <td class="p-3">
                                <a href="{{ route('admin.edit.transaksi', $transaksi->id_beli_ponsel) }}" 
                                    class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm">
                                        Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-dashboard>
