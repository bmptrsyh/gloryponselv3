@extends('layouts.layout_home')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-semibold mb-6">Riwayat Transaksi</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 mb-6 rounded">{{ session('success') }}</div>
    @endif

    @if (count($transaksis) > 0)
        <div class="bg-white shadow-md rounded-lg p-6">
            <ul class="space-y-6">
                @foreach ($transaksis as $transaksi)
                    <li class="border-b pb-4">
                        <h3 class="text-lg font-semibold">{{ $transaksi['product_name'] }}</h3>
                        <p class="text-sm text-gray-600">Tanggal: {{ $transaksi['created_at'] }}</p>
                        <p class="text-sm text-gray-600">Jumlah: {{ $transaksi['quantity'] }}</p>
                        <p class="text-sm text-gray-600">Total Harga: Rp {{ number_format($transaksi['total_price'], 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">Metode Pembayaran: {{ $transaksi['metode_pembayaran'] }}</p>
                        <p class="text-sm text-gray-600">Jasa Pengiriman: {{ $transaksi['jasa_pengiriman'] }}</p>
                        <p class="text-sm text-gray-600">Nama: {{ $transaksi['nama'] }}</p>
                        <p class="text-sm text-gray-600">Nomor Telepon: {{ $transaksi['telepon'] }}</p>
                        <p class="text-sm text-gray-600">Alamat: {{ $transaksi['alamat'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-gray-600">Belum ada transaksi.</p>
            <a href="{{ route('produk.index') }}" class="inline-block mt-4 bg-purple-700 text-white px-5 py-2 rounded-lg hover:bg-purple-800">Belanja Sekarang</a>
        </div>
    @endif
</div>
@endsection