<x-dashboard>
    <!-- Produk Baru -->
    <div class="flex justify-between items-center mb-4">
       <!-- Kiri: Judul atau bisa dikosongkan -->
       <h2 class="text-xl font-semibold mb-4">Riwayat Produk</h2>
     </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
      <!-- Card Product -->
      @forelse($ponselSoftDeleted as $produk)
      <div class="bg-white p-4 rounded-xl shadow-md flex flex-col h-full">
        <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->model }}" class="rounded-xl mb-4 h-40 object-contain">
        <h3 class="font-semibold">{{ $produk->merk }} {{ $produk->model }}</h3>
        <p class="text-blue-500 font-semibold">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
        <div class="flex items-center text-yellow-400 text-sm mb-3">
          ★★★★☆ <span class="text-gray-400 ml-2">(131)</span>
        </div>
        <div class="mt-auto flex space-x-2">
         {{-- Tombol Edit --}}
         <form action="{{ route('admin.ponsel.restore', $produk->id_ponsel) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm">
                Restore Product
            </button>
        </form>
     
         {{-- Tombol Hapus --}}
         <form action="{{ route('admin.ponsel.forceDelete', $produk->id_ponsel) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini? produk akan dihapus secara permanen');">
             @csrf
             @method('DELETE')
             <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm">
                 Hapus Product Permanen
             </button>
         </form>
     </div>
     
      </div>
      @empty
      <p class="text-gray-500">Tidak ada riwayat ponsel.</p>
      @endforelse
    </div>
    </div>
 </x-dashboard>