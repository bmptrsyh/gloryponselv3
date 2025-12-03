@extends('layouts.layout_home')
@section('content')
   <div class="max-w-6xl mx-auto px-4 py-10 mb-4">
      <!-- Atas: Gambar + Info -->
      <div class="grid md:grid-cols-2 gap-10">
         <!-- Gambar Produk -->
         <div class="flex justify-center">
            <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->merk }} {{ $produk->model }}"
               class="rounded-xl max-h-96 object-contain">
         </div>

         <!-- Info Produk -->
         <div>
            <h2 class="text-xl font-semibold mb-2">{{ $produk->merk }} {{ $produk->model }}</h2>
            <div class="flex items-center space-x-2 mb-2">
               <div class="text-yellow-400 text-lg">
                  @for ($i = 1; $i <= 5; $i++)
                     @if ($i <= floor($avg))
                        <i class="fas fa-star"></i>
                     @elseif ($i - $avg <= 0.5)
                        <i class="fas fa-star-half-alt"></i>
                     @else
                        <i class="far fa-star"></i>
                     @endif
                  @endfor
               </div>
               <div class="text-gray-600 text-sm">{{ number_format($avg ?? 0, 1) }} • {{ $terjual }} terjual</div>
            </div>
            <div id="harga-display" class="text-2xl font-bold text-gray-900 mb-4">Rp
               {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>

            <!-- Jumlah -->
            <div class="flex items-center space-x-4 mb-6">
               <label class="text-sm font-medium">Jumlah</label>
               <div class="flex items-center border rounded">
                  <button type="button" id="minus-btn" class="px-3 py-1 text-lg font-bold">-</button>
                  <span id="jumlah-span" class="px-4">1</span>
                  <button type="button" id="plus-btn" class="px-3 py-1 text-lg font-bold">+</button>
               </div>
               <span class="text-xs text-gray-500">Tersisa {{ $produk->stok }} buah</span>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-wrap gap-4 mb-6">
               <form id="beliForm" method="POST" action="{{ route('beli.ponsel') }}">
                  @csrf
                  <input type="hidden" name="jumlah" id="jumlah_produk" value="1">
                  <input type="hidden" name="id_ponsel" value="{{ $produk->id_ponsel }}">
                  <button type="submit" class="bg-purple-700 text-white px-5 py-2 rounded-lg hover:bg-purple-800">Beli
                     Sekarang</button>
               </form>
               <form id="cartForm" method="POST" action="{{ route('keranjang.store') }}">
                  @csrf
                  <input type="hidden" name="produk_id" value="{{ $produk->id_ponsel }}">
                  <input type="hidden" name="jumlah" id="jumlah_keranjang" value="1">
                  <button type="submit" class="bg-purple-700 text-white px-5 py-2 rounded-lg hover:bg-purple-800">
                     Masukkan Keranjang
                  </button>
               </form>
               <a href="{{ route('tukar.tambah.create', $produk->id_ponsel) }}"
                  class="bg-blue-600 text-white border border-purple-700 px-5 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50 inline-block text-center {{ $produk->stok <= 0 ? 'pointer-events-none opacity-50' : '' }}">
                  Tukar Tambah
               </a>
               <a href="{{ route('ajukan.kredit', $produk->id_ponsel) }}"
                  class="bg-blue-600 text-white border border-purple-700 px-5 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50 inline-block text-center {{ $produk->stok <= 0 ? 'pointer-events-none opacity-50' : '' }}">
                  Ajukan Kredit
               </a>
            </div>

            <!-- Tab Tombol -->
            <div class="flex gap-4 mb-4">
               <button onclick="setTab('spesifikasi')" id="tab-spesifikasi-btn"
                  class="tab-btn bg-purple-700 text-white px-4 py-1 rounded-md text-sm">
                  Spesifikasi
               </button>
               <button onclick="setTab('testimoni')" id="tab-testimoni-btn"
                  class="tab-btn bg-gray-200 text-gray-700 px-4 py-1 rounded-md text-sm">
                  Testimoni
               </button>
            </div>
         </div>
      </div>

      <!-- Spesifikasi -->
      <div id="tab-spesifikasi" class="tab-content mb-10">
         <div class="mt-12 space-y-2 text-sm text-gray-700">
            <h3 class="text-lg font-semibold text-black mb-2">Spesifikasi Produk</h3>
            <p><strong>Status:</strong> {{ $produk->status }}</p>
            <p><strong>Merk:</strong> {{ $produk->merk }}</p>
            <p><strong>Model:</strong> {{ $produk->model }}</p>
            <p><strong>Kapasitas Penyimpanan:</strong> {{ $produk->storage }} GB</p>
            <p><strong>Ram:</strong> {{ $produk->ram }} GB</p>
            <p><strong>Processor:</strong> {{ $produk->processor }}</p>
            <p><strong>Dimension:</strong> {{ $produk->dimension }}</p>
            <p><strong>Stok:</strong> {{ $produk->stok }}</p>
         </div>
      </div>
      {{-- testimoni --}}
      <div id="tab-testimoni" class="tab-content hidden mb-10">
         <div class="mt-12 space-y-2 text-sm text-gray-700">
            <h3 class="text-lg font-semibold text-black mb-4">Penilaian Produk</h3>

            @forelse($produk->ulasan as $ulasan)
               <div class="bg-white p-6 rounded-2xl shadow-md border text-sm text-gray-700 mb-6">
                  <div class="flex items-center gap-2 mb-2">
                     <img src="{{ $ulasan->beliPonsel->customer->foto_profil }}"
                        alt="{{ $ulasan->beliPonsel->customer->nama }}" width="30" height="30"
                        class="rounded-full">
                     <span class="text-sm font-semibold text-gray-800">{{ $ulasan->beliPonsel->customer->nama }}</span>
                  </div>

                  <div class="text-yellow-400 text-lg mb-1">
                     {!! str_repeat('★', $ulasan->rating) . str_repeat('☆', 5 - $ulasan->rating) !!}
                  </div>

                  <p class="mb-4 leading-relaxed">
                     {{ $ulasan->ulasan }}
                  </p>
               </div>
            @empty
               <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
            @endforelse
         </div>
      </div>
   </div>
   <script>
      function setTab(tab) {
         const tabs = ['spesifikasi', 'testimoni'];

         tabs.forEach(t => {
            const content = document.getElementById(`tab-${t}`);
            const button = document.getElementById(`tab-${t}-btn`);
            if (content) content.classList.add('hidden');
            if (button) {
               button.classList.remove('bg-purple-700', 'text-white');
               button.classList.add('bg-gray-200', 'text-gray-700');
            }
         });

         const activeContent = document.getElementById(`tab-${tab}`);
         const activeButton = document.getElementById(`tab-${tab}-btn`);
         if (activeContent) activeContent.classList.remove('hidden');
         if (activeButton) {
            activeButton.classList.add('bg-purple-700', 'text-white');
            activeButton.classList.remove('bg-gray-200', 'text-gray-700');
         }
      }
   </script>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const minusBtn = document.getElementById('minus-btn');
         const plusBtn = document.getElementById('plus-btn');
         const jumlahSpan = document.getElementById('jumlah-span');
         const jumlahInput = document.getElementById('jumlah_produk');
         const jumlahKeranjang = document.getElementById('jumlah_keranjang');
         const jumlahProdukKredit = document.getElementById('jumlah_produk_kredit');
         const hargaDisplay = document.getElementById('harga-display');
         const maxStok = {{ $produk->stok }};
         const hargaSatuan = {{ $produk->harga_jual }};

         let jumlah = 1;

         function updateHarga() {
            const totalHarga = hargaSatuan * jumlah;
            hargaDisplay.innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
         }

         minusBtn.addEventListener('click', () => {
            if (jumlah > 1) {
               jumlah--;
               jumlahSpan.innerText = jumlah;
               jumlahInput.value = jumlah;
               jumlahKeranjang.value = jumlah;
               jumlahProdukKredit.value = jumlah;
               updateHarga();
            }
         });

         plusBtn.addEventListener('click', () => {
            if (jumlah < maxStok) {
               jumlah++;
               jumlahSpan.innerText = jumlah;
               jumlahInput.value = jumlah;
               jumlahKeranjang.value = jumlah;
               jumlahProdukKredit.value = jumlah;
               updateHarga();
            }
         });
      });
   </script>
@endsection
