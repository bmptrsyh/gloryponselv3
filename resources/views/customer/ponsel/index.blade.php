@extends('layouts.layout_home')
@section('content')

  <!-- Search -->
  <div class="container mx-auto mt-8 px-4 flex justify-between space-x-4">
    <form action="" method="GET" class="w-2/4 flex space-x-4">
      <input type="text" name="keyword" placeholder="Cari barang yang anda inginkan..." class="w-full p-3 border rounded-lg" />
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow">Search</button>
    </form>
    <div class="flex items-center space-x-4">
        <div class="bg-purple-600 text-white px-4 py-2 rounded-lg shadow">
            <a href="{{ route('jual.ponsel.create') }}">Jual Ponsel</a>
        </div>
        <button onclick="openFilter()" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
          Filter By
        </button>
    </div>
</div>



  <!-- Produk Unggulan -->
  <section class="container mx-auto px-4 mt-10 mb-10">
    <h2 class="text-lg font-semibold mb-4">Produk Kami</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
      <!-- Card Produk -->
        @forelse ($produk as $produk)
        <a href="{{ route('produk.show', $produk->id_ponsel) }}">
      <div class="bg-white shadow rounded-lg p-3 text-center">
        <img src="{{ asset($produk->gambar) }}" class="mx-auto mb-2 h-48 w-48 object-contain" alt="{{ $produk->model }}">
        <p class="font-semibold">{{ $produk->merk }} {{ $produk->model }}</p>
        <p class="text-sm text-gray-600">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
        <div class="text-yellow-400 text-sm mt-1">⭐ {{ number_format($produk->avg ?? 0, 1) }} ({{ $produk->count ?? '' }} ulasan)</div>
      </div>
      </a>
      @empty
      <p>Tidak ada produk.</p>
  @endforelse
      <!-- Ulangi sesuai kebutuhan -->
    </div>
  </section>

  <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full max-w-md rounded-xl p-6 relative shadow-lg">
      
      <!-- Tombol Close -->
      <button onclick="closeFilter()" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-xl">&times;</button>
      
      <h2 class="text-xl font-semibold mb-4">Filter Produk</h2>

      <form method="GET" action="{{ route('produk.index') }}" class="space-y-4">
          <!-- Filter: Merk -->
          <div>
              <label class="block text-sm">Merk</label>
              <select name="merk" class="w-full border rounded px-2 py-1">
                  <option value="">Semua Merk</option>
                  @foreach($filters['merk'] as $val)
                      <option value="{{ $val }}" {{ request('merk') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-sm">Model</label>
              <select name="model" class="w-full border rounded px-2 py-1">
                  <option value="">Semua Model</option>
                  @foreach($filters['model'] as $val)
                      <option value="{{ $val }}" {{ request('model') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-sm">Status</label>
              <select name="status" class="w-full border rounded px-2 py-1">
                  <option value="">Semua Status</option>
                  @foreach(['baru', 'bekas'] as $val)
                      <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-sm">processor</label>
              <select name="processor" class="w-full border rounded px-2 py-1">
                  <option value="">Semua processor</option>
                  @foreach($filters['processor'] as $val)
                      <option value="{{ $val }}" {{ request('processor') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-sm">dimension</label>
              <select name="dimension" class="w-full border rounded px-2 py-1">
                  <option value="">Semua dimension</option>
                  @foreach($filters['dimension'] as $val)
                      <option value="{{ $val }}" {{ request('dimension') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-sm">ram</label>
              <select name="ram" class="w-full border rounded px-2 py-1">
                  <option value="">Semua ram</option>
                  @foreach($filters['ram'] as $val)
                      <option value="{{ $val }}" {{ request('ram') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-sm">storage</label>
              <select name="storage" class="w-full border rounded px-2 py-1">
                  <option value="">Semua storage</option>
                  @foreach($filters['storage'] as $val)
                      <option value="{{ $val }}" {{ request('storage') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-sm">warna</label>
              <select name="warna" class="w-full border rounded px-2 py-1">
                  <option value="">Semua warna</option>
                  @foreach($filters['warna'] as $val)
                      <option value="{{ $val }}" {{ request('warna') == $val ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
              </select>
          </div>

          <!-- Tombol -->
          <div class="flex justify-between gap-2 pt-4">
              <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Filter</button>
              <a href="{{ route('produk.index') }}" class="w-full bg-gray-300 text-center py-2 rounded">Reset</a>
          </div>
      </form>
    </div>
  </div>
  <script>
    function openFilter() {
        document.getElementById('filterModal').classList.remove('hidden');
    }
    function closeFilter() {
        document.getElementById('filterModal').classList.add('hidden');
    }
  </script>

@endsection