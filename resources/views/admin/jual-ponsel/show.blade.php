<x-dashboard>
   <div class="container px-6 mx-auto grid">
      <h2 class="my-6 text-2xl font-semibold text-gray-700">
         Detail Pengajuan Jual Ponsel
      </h2>

      @if (session('success'))
         <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
         </div>
      @endif

      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
               <img src="{{ asset($pengajuan->gambar) }}" alt="{{ $pengajuan->merk }} {{ $pengajuan->model }}"
                  class="w-full h-auto rounded-lg shadow-md">

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

                     <button onclick="openStatusModal({{ $pengajuan->id_jual_ponsel }}, '{{ $pengajuan->status }}')"
                        class="ml-4 px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Update Status
                     </button>
                  </div>
                  @if ($pengajuan->catatan_admin)
                     <div class="alert alert-info mt-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700" role="alert">
                        <strong>Catatan Admin:</strong> {{ $pengajuan->catatan_admin }}
                     </div>
                  @endif
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
               <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $pengajuan->merk }} {{ $pengajuan->model }}
               </h3>

               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Harga Pengajuan</h4>
                  <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($pengajuan->harga, 0, ',', '.') }}
                  </p>
               </div>

               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Spesifikasi</h4>
                  <div class="grid grid-cols-2 gap-4 text-sm">
                     <div>
                        <span class="font-medium text-gray-700">Merk:</span>
                        <p>{{ $pengajuan->merk }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Model:</span>
                        <p>{{ $pengajuan->model }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Warna:</span>
                        <p>{{ $pengajuan->warna }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">RAM:</span>
                        <p>{{ $pengajuan->ram }} GB</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Storage:</span>
                        <p>{{ $pengajuan->storage }} GB</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Processor:</span>
                        <p>{{ $pengajuan->processor }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Kondisi:</span>
                        <p>{{ $pengajuan->kondisi }}</p>
                     </div>
                     <div>
                        <span class="font-medium text-gray-700">Tanggal Pengajuan:</span>
                        <p>{{ $pengajuan->created_at->format('d M Y H:i') }}</p>
                     </div>
                  </div>
               </div>

               <div class="mb-6">
                  <h4 class="text-lg font-semibold text-gray-700 mb-2">Deskripsi</h4>
                  <div class="bg-gray-50 p-4 rounded-md">
                     <p class="text-gray-700 whitespace-pre-line">{{ $pengajuan->deskripsi }}</p>
                  </div>
               </div>
            </div>
         </div>

         <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.jual-ponsel.index') }}"
               class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-md active:bg-gray-50 hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray">
               Kembali ke Daftar
            </a>

            <form action="{{ route('admin.jual-ponsel.destroy', $pengajuan->id_jual_ponsel) }}" method="POST"
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
                  <h3 class="text-lg font-semibold">Update Status Pengajuan</h3>
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
         statusForm.action = `/admin/jual-ponsel/${id}/update-status`;

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
