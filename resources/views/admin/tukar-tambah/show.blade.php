<x-dashboard>
   <div class="container px-6 mx-auto grid">
      <h2 class="my-6 text-2xl font-semibold text-gray-700">
         Detail Pengajuan Tukar Tambah
      </h2>

      @if (session('success'))
         <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
         </div>
      @endif

      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
               <h4 class="text-lg font-semibold text-gray-700 mb-2">Ponsel Customer</h4> <img
                  src="{{ asset($pengajuan->gambar) }}"
                  alt="{{ $pengajuan->merk ?? 'N/A' }} {{ $pengajuan->model ?? 'N/A' }}"
                  class="w-full h-auto rounded-lg shadow-md mb-4">
               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Produk Tujuan</h4>
                  <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                     <div class="flex items-center">
                        @if ($pengajuan->produkTujuan)
                           <img src="{{ asset($pengajuan->produkTujuan->gambar) }}"
                              alt="{{ $pengajuan->produkTujuan->merk }} {{ $pengajuan->produkTujuan->model }}"
                              class="w-16 h-16 object-cover rounded-lg mr-4">
                           <div>
                              <p class="font-medium">{{ $pengajuan->produkTujuan->merk }}
                                 {{ $pengajuan->produkTujuan->model }}</p>
                              <p class="text-sm text-gray-600">Harga: Rp
                                 {{ number_format($pengajuan->produkTujuan->harga_jual, 0, ',', '.') }}</p>
                           </div>
                        @else
                           <p class="text-gray-600">Produk tujuan tidak ditemukan.</p>
                        @endif
                     </div>
                  </div>
               </div>

               <div class="mt-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Status Pengajuan</h4>
                  <div class="flex items-center">
                     @if ($pengajuan->status == 'menunggu')
                        <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                           Menunggu
                        </span>
                     @elseif($pengajuan->status == 'di setujui')
                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                           Disetujui
                        </span>
                     @elseif($pengajuan->status == 'di tolak')
                        <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
                           Ditolak
                        </span>
                     @endif

                     <button onclick="openStatusModal({{ $pengajuan->id_tukar_tambah }}, '{{ $pengajuan->status }}')"
                        class="ml-4 px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Update Status
                     </button>
                  </div>
               </div>

               <div class="mt-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Informasi Customer</h4>
                  <div class="flex items-center mb-4">
                     <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full object-cover"
                           src="{{ $pengajuan->customer->foto_profil_url ?? asset('storage/gambar/customer/default.png') }}"
                           alt="{{ $pengajuan->customer->nama ?? 'Customer' }}">
                     </div>
                     <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                           {{ $pengajuan->customer->nama ?? 'Customer #' . $pengajuan->id_customer }}</div>
                        <div class="text-sm text-gray-500">{{ $pengajuan->customer->email ?? '-' }}</div>
                     </div>
                  </div>
                  <div class="grid grid-cols-2 gap-4 text-sm">
                     <div>
                        <span class="font-medium text-gray-700">Telepon:</span>
                        <p>{{ $pengajuan->customer->nomor_telepon ?? '-' }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Alamat:</span>
                        <p>{{ $pengajuan->customer->alamat ?? '-' }}</p>
                     </div>
                  </div>
               </div>
            </div>

            <div>
               <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $pengajuan->merk ?? 'N/A' }}
                  {{ $pengajuan->model ?? 'N/A' }}</h3>

               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Estimasi Harga Ponsel Customer</h4>
                  <p class="text-2xl font-bold text-purple-600">Rp
                     {{ number_format($pengajuan->harga_estimasi ?? 0, 0, ',', '.') }}</p>
               </div>

               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Selisih Harga</h4>
                  @php
                     $selisih = ($pengajuan->produkTujuan->harga_jual ?? 0) - ($pengajuan->harga_estimasi ?? 0);
                  @endphp
                  <p class="text-lg font-semibold {{ $selisih > 0 ? 'text-red-600' : 'text-green-600' }}">
                     {{ $selisih > 0 ? 'Kurang bayar: ' : 'Kelebihan: ' }}
                     Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                  </p>
               </div>

               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Spesifikasi Ponsel Customer</h4>
                  <div class="grid grid-cols-2 gap-4 text-sm">
                     <div>
                        <span class="font-medium text-gray-700">Merk:</span>
                        <p>{{ $pengajuan->merk ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Model:</span>
                        <p>{{ $pengajuan->model ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Warna:</span>
                        <p>{{ $pengajuan->warna ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">RAM:</span>
                        <p>{{ $pengajuan->ram ?? 'N/A' }} GB</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Storage:</span>
                        <p>{{ $pengajuan->storage ?? 'N/A' }} GB</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Processor:</span>
                        <p>{{ $pengajuan->processor ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Kondisi:</span>
                        <p>{{ $pengajuan->kondisi ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Tanggal Pengajuan:</span>
                        <p>{{ $pengajuan->created_at->format('d M Y H:i') ?? 'N/A' }}</p>
                     </div>
                  </div>
               </div>

               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Deskripsi</h4>
                  <div class="bg-gray-50 p-4 rounded-md">
                     <p class="text-gray-700 whitespace-pre-line">{{ $pengajuan->deskripsi ?? 'Tidak ada deskripsi.' }}
                     </p>
                  </div>
               </div>
            </div>
         </div>

         <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.tukar-tambah.index') }}"
               class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-md active:bg-gray-50 hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray">
               Kembali ke Daftar
            </a>

            <form action="{{ route('admin.tukar-tambah.destroy', $pengajuan->id_tukar_tambah) }}" method="POST"
               onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
               @csrf
               @method('DELETE')
               <button type="submit"
                  class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                  Hapus Pengajuan
               </button>
            </form>
         </div>
      </div>
   </div>

   <!-- Modal Update Status -->
   <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
         <form id="statusForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
               <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-semibold">Update Status Tukar Tambah</h3>
                  <button type="button" onclick="closeStatusModal()"
                     class="text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
               </div>

               <div class="mb-4">
                  <label class="block text-sm font-medium mb-1">Status</label>
                  <div class="mt-2 space-y-2">
                     <label class="inline-flex items-center">
                        <input type="radio" name="status" value="menunggu"
                           class="form-radio h-5 w-5 text-purple-600">
                        <span class="ml-2 text-gray-700">Menunggu</span>
                     </label>
                     <br>
                     <label class="inline-flex items-center">
                        <input type="radio" name="status" value="di setujui"
                           class="form-radio h-5 w-5 text-purple-600">
                        <span class="ml-2 text-gray-700">Disetujui</span>
                     </label>
                     <br>
                     <label class="inline-flex items-center">
                        <input type="radio" name="status" value="di tolak"
                           class="form-radio h-5 w-5 text-purple-600">
                        <span class="ml-2 text-gray-700">Ditolak</span>
                     </label>
                  </div>
               </div>

               <div class="mb-4">
                  <label for="catatan_admin" class="block text-sm font-medium mb-1">Catatan Admin</label>
                  <textarea id="catatan_admin" name="catatan_admin" rows="3"
                     class="w-full px-3 py-2 border rounded-lg focus:ring-purple-500 focus:border-purple-500"></textarea>
               </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3 rounded-b-lg">
               <button type="button" onclick="closeStatusModal()"
                  class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
                  Batal
               </button>
               <button type="submit"
                  class="px-4 py-2 bg-purple-700 text-white rounded-lg text-sm hover:bg-purple-800">
                  Simpan Perubahan
               </button>
            </div>
         </form>
      </div>
   </div>


   <script>
      const statusModal = document.getElementById('statusModal');
      const statusForm = document.getElementById('statusForm');

      function openStatusModal(id, currentStatus) {
         statusForm.action = `/admin/tukar-tambah/${id}/update-status`;

         // Set current status
         document.querySelectorAll('input[name="status"]').forEach(radio => {
            if (radio.value === currentStatus) {
               radio.checked = true;
            }
         });

         statusModal.classList.remove('hidden');
      }

      function closeStatusModal() {
         statusModal.classList.add('hidden');
      }

      // Close modal when clicking outside
      window.addEventListener('click', function(event) {
         if (event.target === statusModal) {
            closeStatusModal();
         }
      });
   </script>
</x-dashboard>
