<x-dashboard>
   <!-- Produk Baru -->
   <div class="flex justify-between items-center mb-4">
      <!-- Kiri: Judul atau bisa dikosongkan -->
      <h2 class="text-xl font-semibold mb-4">Produk Baru</h2>

      <!-- Kanan: Tombol Aksi -->
      <div class="flex items-center space-x-4">
         <a href="{{ route('admin.ponsel.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            Tambah Produk
         </a>
         <a href="{{ route('admin.ponsel.softdelete') }}"
            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
            Riwayat Produk
         </a>
         <!-- Tombol utama Filter -->
         <button onclick="openFilter()" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
            Filter By
         </button>
      </div>
   </div>
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
      @forelse($produkBaru as $produk)
         <div class="bg-white p-4 rounded-xl shadow-md flex flex-col h-full">

            {{-- Gambar --}}
            <div class="h-40 flex items-center justify-center mb-4">
               <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->model }}" class="max-h-full object-contain">
            </div>

            {{-- Nama produk (dibatasi 2 baris, tinggi tetap) --}}
            <h3 class="font-semibold line-clamp-2 min-h-[48px]">
               {{ $produk->merk }} {{ $produk->model }}
            </h3>

            {{-- Harga (selalu sejajar) --}}
            <p class="text-blue-500 font-semibold min-h-[28px] flex items-center">
               Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
            </p>

            {{-- Rating (selalu sejajar) --}}
            <div class="flex items-center text-yellow-400 text-sm mb-3 min-h-[24px]">
               @php
                  $avgRating = $produk->ulasan->avg('rating') ?? 0;
                  $countUlasan = $produk->ulasan->count();
               @endphp
               @for ($i = 1; $i <= 5; $i++)
                  @if ($i <= floor($avgRating))
                     <i class="fas fa-star"></i>
                  @elseif ($i - $avgRating <= 0.5)
                     <i class="fas fa-star-half-alt"></i>
                  @else
                     <i class="far fa-star"></i>
                  @endif
               @endfor <span class="text-gray-400 ml-2">({{ $countUlasan }})</span>
            </div>

            {{-- Tombol aksi di bawah --}}
            <div class="mt-auto flex space-x-2">
               <a href="{{ route('admin.ponsel.edit', $produk->id_ponsel) }}"
                  class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm">
                  Edit Product
               </a>
               <form action="{{ route('admin.ponsel.destroy', $produk->id_ponsel) }}" method="POST" class="form-delete">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm">
                     Hapus Product
                  </button>
               </form>
            </div>
         </div>
      @empty
         <p class="text-gray-500">Tidak ada produk bekas.</p>
      @endforelse
   </div>

   <!-- Produk Bekas -->
   <h2 class="text-xl font-semibold mb-4">Produk Bekas</h2>
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
      @forelse($produkBekas as $produk)
         <div class="bg-white p-4 rounded-xl shadow-md flex flex-col h-full">

            {{-- Gambar --}}
            <div class="h-40 flex items-center justify-center mb-4">
               <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->model }}" class="max-h-full object-contain">
            </div>

            {{-- Nama produk (dibatasi 2 baris, tinggi tetap) --}}
            <h3 class="font-semibold line-clamp-2 min-h-[48px]">
               {{ $produk->merk }} {{ $produk->model }}
            </h3>

            {{-- Harga (selalu sejajar) --}}
            <p class="text-blue-500 font-semibold min-h-[28px] flex items-center">
               Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
            </p>

            {{-- Rating (selalu sejajar) --}}
            <div class="flex items-center text-yellow-400 text-sm mb-3 min-h-[24px]">
               @php
                  $avgRating = $produk->ulasan->avg('rating') ?? 0;
                  $countUlasan = $produk->ulasan->count();
               @endphp
               @for ($i = 1; $i <= 5; $i++)
                  @if ($i <= floor($avgRating))
                     <i class="fas fa-star"></i>
                  @elseif ($i - $avgRating <= 0.5)
                     <i class="fas fa-star-half-alt"></i>
                  @else
                     <i class="far fa-star"></i>
                  @endif
               @endfor <span class="text-gray-400 ml-2">({{ $countUlasan }})</span>
            </div>

            {{-- Tombol aksi di bawah --}}
            <div class="mt-auto flex space-x-2">
               <a href="{{ route('admin.ponsel.edit', $produk->id_ponsel) }}"
                  class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm">
                  Edit Product
               </a>
               <form action="{{ route('admin.ponsel.destroy', $produk->id_ponsel) }}" method="POST" class="form-delete">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm">
                     Hapus Product
                  </button>
               </form>
            </div>
         </div>
      @empty
         <p class="text-gray-500">Tidak ada produk bekas.</p>
      @endforelse
   </div>


   <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
      <div class="bg-white w-full max-w-md rounded-xl p-6 relative shadow-lg">

         <!-- Tombol Close -->
         <button onclick="closeFilter()"
            class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-xl">&times;</button>

         <h2 class="text-xl font-semibold mb-4">Filter Produk</h2>

         <form method="GET" action="{{ route('admin.ponsel.index') }}" class="space-y-4">
            <!-- Filter: Merk -->
            <div>
               <label class="block text-sm">Merk</label>
               <select name="merk" class="w-full border rounded px-2 py-1">
                  <option value="">Semua Merk</option>
                  @foreach ($filters['merk'] as $val)
                     <option value="{{ $val }}" {{ request('merk') == $val ? 'selected' : '' }}>
                        {{ $val }}</option>
                  @endforeach
               </select>
            </div>
            <div>
               <label class="block text-sm">Model</label>
               <select name="model" class="w-full border rounded px-2 py-1">
                  <option value="">Semua Model</option>
                  @foreach ($filters['model'] as $val)
                     <option value="{{ $val }}" {{ request('model') == $val ? 'selected' : '' }}>
                        {{ $val }}</option>
                  @endforeach
               </select>
            </div>
            <div>
               <label class="block text-sm">processor</label>
               <select name="processor" class="w-full border rounded px-2 py-1">
                  <option value="">Semua processor</option>
                  @foreach ($filters['processor'] as $val)
                     <option value="{{ $val }}" {{ request('processor') == $val ? 'selected' : '' }}>
                        {{ $val }}</option>
                  @endforeach
               </select>
            </div>
            <div>
               <label class="block text-sm">dimension</label>
               <select name="dimension" class="w-full border rounded px-2 py-1">
                  <option value="">Semua dimension</option>
                  @foreach ($filters['dimension'] as $val)
                     <option value="{{ $val }}" {{ request('dimension') == $val ? 'selected' : '' }}>
                        {{ $val }}</option>
                  @endforeach
               </select>
            </div>
            <div>
               <label class="block text-sm">ram</label>
               <select name="ram" class="w-full border rounded px-2 py-1">
                  <option value="">Semua ram</option>
                  @foreach ($filters['ram'] as $val)
                     <option value="{{ $val }}" {{ request('ram') == $val ? 'selected' : '' }}>
                        {{ $val }}</option>
                  @endforeach
               </select>
            </div>
            <div>
               <label class="block text-sm">storage</label>
               <select name="storage" class="w-full border rounded px-2 py-1">
                  <option value="">Semua storage</option>
                  @foreach ($filters['storage'] as $val)
                     <option value="{{ $val }}" {{ request('storage') == $val ? 'selected' : '' }}>
                        {{ $val }}</option>
                  @endforeach
               </select>
            </div>
            <div>
               <label class="block text-sm">warna</label>
               <select name="warna" class="w-full border rounded px-2 py-1">
                  <option value="">Semua warna</option>
                  @foreach ($filters['warna'] as $val)
                     <option value="{{ $val }}" {{ request('warna') == $val ? 'selected' : '' }}>
                        {{ $val }}</option>
                  @endforeach
               </select>
            </div>

            <!-- Tombol -->
            <div class="flex justify-between gap-2 pt-4">
               <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Filter</button>
               <a href="{{ route('admin.ponsel.index') }}"
                  class="w-full bg-gray-300 text-center py-2 rounded">Reset</a>
            </div>
         </form>
      </div>
   </div>
   <!-- Modal Konfirmasi Hapus -->
   <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
      <div class="bg-white rounded-xl p-6 shadow-lg w-full max-w-sm relative">
         <h3 class="text-lg font-semibold mb-4 text-center text-red-600">Konfirmasi Hapus Produk</h3>
         <p class="mb-6 text-center text-gray-700">Yakin ingin menghapus produk ini?</p>
         <div class="flex justify-center gap-3">
            <button id="cancelDeleteBtn" type="button" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Batal</button>
            <button id="confirmDeleteBtn" type="button" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
         </div>
      </div>
   </div>

   <script>
      function openFilter() {
         document.getElementById('filterModal').classList.remove('hidden');
      }

      function closeFilter() {
         document.getElementById('filterModal').classList.add('hidden');
      }

      // Modal konfirmasi hapus
      let deleteFormToSubmit = null;
      document.addEventListener('DOMContentLoaded', function () {
         document.querySelectorAll('.form-delete').forEach(function(form) {
            form.addEventListener('submit', function(e) {
               e.preventDefault();
               deleteFormToSubmit = form;
               document.getElementById('deleteModal').classList.remove('hidden');
            });
         });

         document.getElementById('cancelDeleteBtn').onclick = function() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteFormToSubmit = null;
         };
         document.getElementById('confirmDeleteBtn').onclick = function() {
            if (deleteFormToSubmit) {
               deleteFormToSubmit.submit();
            }
         };

         // Optional: close modal when clicking outside
         document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
               this.classList.add('hidden');
               deleteFormToSubmit = null;
            }
         });
      });
   </script>
</x-dashboard>
