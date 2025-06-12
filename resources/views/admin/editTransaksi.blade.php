<x-dashboard>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Status Transaksi</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('transaksi.update', $transaksi->id_beli_ponsel) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Ponsel</label>
                        <input type="text" 
                               value="{{ $transaksi->ponsel->merk }} {{ $transaksi->ponsel->model }}" 
                               class="w-full p-2 border border-gray-300 rounded bg-gray-100" 
                               readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Customer</label>
                        <input type="text" 
                               value="{{ $transaksi->customer->nama }}" 
                               class="w-full p-2 border border-gray-300 rounded bg-gray-100" 
                               readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Jumlah</label>
                        <input type="number" 
                               value="{{ $transaksi->jumlah }}" 
                               class="w-full p-2 border border-gray-300 rounded bg-gray-100" 
                               readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Harga</label>
                        <input type="text" 
                               value="Rp {{ number_format($transaksi->harga, 0, ',', '.') }}" 
                               class="w-full p-2 border border-gray-300 rounded bg-gray-100" 
                               readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Metode Pembayaran</label>
                        <input type="text" 
                               value="{{ ucfirst($transaksi->metode_pembayaran) }}" 
                               class="w-full p-2 border border-gray-300 rounded bg-gray-100" 
                               readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Tanggal Transaksi</label>
                        <input type="text" 
                               value="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y H:i') }}" 
                               class="w-full p-2 border border-gray-300 rounded bg-gray-100" 
                               readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Status</label>
                    <select name="status" 
                            class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="tertunda" {{ $transaksi->status === 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                        <option value="selesai" {{ $transaksi->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Status Pengiriman</label>
                    <select name="status_pengiriman" 
                            class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="belum_dikirim" {{ $transaksi->status_pengiriman === 'belum_dikirim' ? 'selected' : '' }}>Belum Dikirim</option>
                        <option value="dikirim" {{ $transaksi->status_pengiriman === 'dikirim' ? 'selected' : '' }}>dikirim</option>
                    </select>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.ponsel.transaksi') }}" 
                       class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard>