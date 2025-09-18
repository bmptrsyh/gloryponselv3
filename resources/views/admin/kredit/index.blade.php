<x-dashboard>
   <div class="container px-6 mx-auto grid">
      <h2 class="my-6 text-2xl font-semibold text-gray-700">
         Daftar Pengajuan Jual Ponsel
      </h2>
      <div class="w-full overflow-hidden rounded-lg shadow-xs">
         <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
               <thead>
                  <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                     <th class="px-4 py-3">Customer</th>
                     <th class="px-4 py-3">Ponsel</th>
                     <th class="px-4 py-3">Harga</th>
                     <th class="px-4 py-3">Status</th>
                     <th class="px-4 py-3">Tanggal</th>
                     <th class="px-4 py-3">Aksi</th>
                  </tr>
               </thead>
               <tbody class="bg-white divide-y">
                  @forelse($kredit as $item)
                     <tr class="text-gray-700">
                        <td class="px-4 py-3">
                           <div class="flex items-center text-sm">
                              <div>
                                 <p class="font-semibold">
                                    {{ $item->customer->nama ?? 'Customer #' . $item->id_customer }}</p>
                                 <p class="text-xs text-gray-600">{{ $item->customer->email ?? '-' }}</p>
                              </div>
                           </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                           <div class="flex items-center">
                              <div class="h-10 w-10 mr-3">
                                 <img class="h-10 w-10 rounded-full object-cover"
                                    src="{{ asset($item->ponsel->gambar) }}"
                                    alt="{{ $item->ponsel->merk }} {{ $item->ponsel->model }}">
                              </div>
                              <div>
                                 <p class="font-semibold">{{ $item->ponsel->merk }} {{ $item->ponsel->model }}</p>
                                 <p class="text-xs text-gray-600">
                                    {{ $item->ponsel->ram }}GB/{{ $item->ponsel->storage }}GB</p>
                              </div>
                           </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                           Rp {{ number_format($item->ponsel->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                           @if ($item->status == 'menunggu')
                              <span
                                 class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                 Menunggu
                              </span>
                           @elseif($item->status == 'disetujui')
                              <span
                                 class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                 Disetujui
                              </span>
                           @elseif($item->status == 'ditolak')
                              <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
                                 Ditolak
                              </span>
                           @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                           {{ $item->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                           <div class="flex items-center space-x-2">
                              <a href="{{ route('admin.kredit.show', $item->id_kredit_ponsel) }}"
                                 class="text-blue-500 hover:text-blue-700">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                       d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                       d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                 </svg>
                              </a>
                              <button onclick="openStatusModal({{ $item->id_kredit_ponsel }}, '{{ $item->status }}' )"
                                 class="text-purple-500 hover:text-purple-700">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                       d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                 </svg>
                              </button>
                              <form action="{{ route('admin.kredit.destroy', $item->id_kredit_ponsel) }}"
                                 method="POST"
                                 onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                       xmlns="http://www.w3.org/2000/svg">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                       </path>
                                    </svg>
                                 </button>
                              </form>
                           </div>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                           Belum ada pengajuan kredit ponsel.
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <!-- Modal Update Status -->
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
                  <div class="flex flex-col sm:flex-row gap-2 mt-2">
                     <button type="button"
                        class="status-btn px-4 py-2 rounded-lg font-semibold border border-yellow-400 text-yellow-700 bg-yellow-50 hover:bg-yellow-100 w-full sm:w-auto"
                        data-value="menunggu">
                        Menunggu
                     </button>
                     <button type="button"
                        class="status-btn px-4 py-2 rounded-lg font-semibold border border-green-500 text-green-700 bg-green-50 hover:bg-green-100 w-full sm:w-auto"
                        data-value="disetujui">
                        Disetujui
                     </button>
                     <button type="button"
                        class="status-btn px-4 py-2 rounded-lg font-semibold border border-red-500 text-red-700 bg-red-50 hover:bg-red-100 w-full sm:w-auto"
                        data-value="ditolak">
                        Ditolak
                     </button>
                  </div>
                  <input type="hidden" name="status" id="statusInput" value="">
               </div>

               <!-- Alasan Ditolak (hidden by default) -->
               <div id="alasanField" class="mb-4 hidden">
                  <label for="alasan_ditolak" class="block text-sm font-medium mb-1">Alasan Ditolak</label>
                  <textarea id="alasan_ditolak" name="alasan_ditolak" rows="3"
                     class="w-full px-3 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500"></textarea>
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
      const statusInput = document.getElementById('statusInput');
      const alasanField = document.getElementById('alasanField');
      let selectedStatusBtn = null;

      function openStatusModal(idKredit, currentStatus) {
         statusForm.action = `/admin/kredit/${idKredit}/update-status`;
         statusInput.value = currentStatus;

         // Reset semua tombol
         document.querySelectorAll('.status-btn').forEach(btn => {
            btn.classList.remove('ring', 'ring-offset-2', 'bg-yellow-200', 'bg-green-200', 'bg-red-200');
            if (btn.dataset.value === currentStatus) {
               btn.classList.add('ring', 'ring-offset-2');
               if (currentStatus === 'menunggu') btn.classList.add('bg-yellow-200');
               if (currentStatus === 'disetujui') btn.classList.add('bg-green-200');
               if (currentStatus === 'ditolak') {
                  btn.classList.add('bg-red-200');
                  alasanField.classList.remove('hidden');
               } else {
                  alasanField.classList.add('hidden');
               }
               selectedStatusBtn = btn;
            }
         });

         statusModal.classList.remove('hidden');
      }

      function closeStatusModal() {
         statusModal.classList.add('hidden');
      }

      document.addEventListener('DOMContentLoaded', function() {
         document.querySelectorAll('.status-btn').forEach(btn => {
            btn.addEventListener('click', function() {
               document.querySelectorAll('.status-btn').forEach(b => {
                  b.classList.remove('ring', 'ring-offset-2', 'bg-yellow-200', 'bg-green-200',
                     'bg-red-200');
               });
               btn.classList.add('ring', 'ring-offset-2');
               if (btn.dataset.value === 'menunggu') btn.classList.add('bg-yellow-200');
               if (btn.dataset.value === 'disetujui') btn.classList.add('bg-green-200');
               if (btn.dataset.value === 'ditolak') btn.classList.add('bg-red-200');

               // Tampilkan input alasan hanya jika status ditolak
               if (btn.dataset.value === 'ditolak') {
                  alasanField.classList.remove('hidden');
               } else {
                  alasanField.classList.add('hidden');
               }

               statusInput.value = btn.dataset.value;
            });
         });
      });

      window.addEventListener('click', function(event) {
         if (event.target === statusModal) {
            closeStatusModal();
         }
      });
   </script>
</x-dashboard>
