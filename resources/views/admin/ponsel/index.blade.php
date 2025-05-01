<x-dashboard>
     <!-- Produk Baru -->
     <div class="flex justify-between items-center mb-4">
        <!-- Kiri: Judul atau bisa dikosongkan -->
        <h2 class="text-xl font-semibold mb-4">Produk Baru</h2>
      
        <!-- Kanan: Tombol Aksi -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.ponsel.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Tambah Produk
            </a>
            <a href="{{ route('admin.ponsel.softdelete') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                Riwayat Produk
            </a>
            <!-- Tombol utama Filter -->
            <button onclick="openFilter()" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
              Filter By
            </button>
        </div>
      </div>
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
       <!-- Card Product -->
       @forelse($produkBaru as $produk)
       <div class="bg-white p-4 rounded-xl shadow-md flex flex-col h-full">
         <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->model }}" class="rounded-xl mb-4 h-40 object-contain">
         <h3 class="font-semibold">{{ $produk->merk }} {{ $produk->model }}</h3>
         <p class="text-blue-500 font-semibold">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
         <div class="flex items-center text-yellow-400 text-sm mb-3">
           ★★★★☆ <span class="text-gray-400 ml-2">(131)</span>
         </div>
         <div class="mt-auto flex space-x-2">
          {{-- Tombol Edit --}}
          <a href="{{ route('admin.ponsel.edit', $produk->id_ponsel) }}" 
             class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm">
              Edit Product
          </a>
      
          {{-- Tombol Hapus --}}
          <form action="{{ route('admin.ponsel.destroy', $produk->id_ponsel) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm">
                  Hapus Product
              </button>
          </form>
      </div>
      
       </div>
       @empty
       <p class="text-gray-500">Tidak ada produk baru.</p>
       @endforelse
     </div>

     <!-- Produk Bekas -->
     <h2 class="text-xl font-semibold mb-4">Produk Bekas</h2>
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
       <!-- Card Product (sama seperti sebelumnya) -->
       @forelse($produkBekas as $produk)
       <div class="bg-white p-4 rounded-xl shadow-md flex flex-col h-full">
         <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->model }}" class="rounded-xl mb-4 h-40 object-contain">
         <h3 class="font-semibold">{{ $produk->merk }} {{ $produk->model }}</h3>
         <p class="text-blue-500 font-semibold">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
         <div class="flex items-center text-yellow-400 text-sm mb-3">
           ★★★★☆ <span class="text-gray-400 ml-2">(131)</span>
         </div>
         <div class="mt-auto flex space-x-2">
           <button class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm">Edit Product</button>
           <button class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm">Hapus Product</button>
         </div>
       </div>
       @empty
       <p class="text-gray-500">Tidak ada produk bekas.</p>
       @endforelse
       </div>
     </div>
     <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
      <div class="bg-white w-full max-w-md rounded-xl p-6 relative shadow-lg">
        
        <!-- Tombol Close -->
        <button onclick="closeFilter()" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-xl">&times;</button>
        
        <h2 class="text-xl font-semibold mb-4">Filter Produk</h2>

        <form method="GET" action="{{ route('admin.ponsel.index') }}" class="space-y-4">
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
                <a href="{{ route('admin.ponsel.index') }}" class="w-full bg-gray-300 text-center py-2 rounded">Reset</a>
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
  </x-dashboard>