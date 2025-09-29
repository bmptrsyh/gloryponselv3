<x-dashboard>
   <div class="p-4 bg-white rounded-lg shadow mt-6">
      <h2 class="text-base font-semibold mb-4">Laporan Cicilan & Jatuh Tempo</h2>
      <div class="overflow-x-auto">
         <table class="w-full text-sm text-left border-collapse">
            <thead>
               <tr class="bg-gray-100 text-gray-700">
                  <th class="px-4 py-3 font-semibold border">Bulan</th>
                  <th class="px-4 py-3 font-semibold border text-right">Angsuran</th>
                  <th class="px-4 py-3 font-semibold border text-right">Jatuh Tempo</th>
                  <th class="px-4 py-3 font-semibold border text-center">Status</th>
                  <th class="px-4 py-3 font-semibold border text-center">Tanggal Bayar</th>
                  <th class="px-4 py-3">Aksi</th>
               </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
               @foreach ($kredit->angsuran as $item)
                  <tr class="hover:bg-gray-50">
                     <td class="px-4 py-3 text-center font-medium text-gray-700 border">
                        Bulan {{ $item->bulan_ke }}
                     </td>
                     <td class="px-4 py-3 text-right text-blue-600 border">
                        Rp {{ number_format($item->jumlah_cicilan, 0, ',', '.') }}
                     </td>
                     <td class="px-4 py-3 text-right text-orange-600 border">
                        {{ \Carbon\Carbon::parse($item['jatuh_tempo'])->format('d M Y') }}
                     </td>
                     <td class="px-4 py-3 text-center border">
                        @if ($item->status === 'lunas')
                           <span class="text-green-600 font-semibold">Lunas</span>
                        @else
                           <span class="text-red-600 font-semibold">Belum Bayar</span>
                        @endif
                     </td>
                     <td class="px-4 py-4 text-center border">
                        {{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') : '-' }}
                     </td>
                     <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                           <button onclick="openStatusModal({{ $item->id_angsuran }}, '{{ $item->status }}' )"
                              class="text-purple-500 hover:text-purple-700">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                 </path>
                              </svg>
                           </button>
                        </div>
                     </td>
                  </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>

   {{-- Modal Update Status Angsuran --}}
   <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
         <form id="statusForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
               <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-semibold">Update Status Angsuran</h3>
                  <button type="button" onclick="closeStatusModal()"
                     class="text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
               </div>

               <div class="mb-4">
                  <label class="block text-sm font-medium mb-1">Status Pembayaran</label>
                  <div class="flex flex-col sm:flex-row gap-2 mt-2">
                     <button type="button"
                        class="status-btn px-4 py-2 rounded-lg font-semibold border border-red-400 text-red-700 bg-red-50 hover:bg-red-100 w-full sm:w-auto"
                        data-value="belum">
                        Belum Bayar
                     </button>
                     <button type="button"
                        class="status-btn px-4 py-2 rounded-lg font-semibold border border-green-500 text-green-700 bg-green-50 hover:bg-green-100 w-full sm:w-auto"
                        data-value="lunas">
                        Lunas
                     </button>
                  </div>
                  <input type="hidden" name="status" id="statusInput" value="">
               </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3 rounded-b-lg">
               <button type="button" onclick="closeStatusModal()"
                  class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
                  Batal
               </button>
               <button type="submit" class="px-4 py-2 bg-purple-700 text-white rounded-lg text-sm hover:bg-purple-800">
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
      const statusButtons = document.querySelectorAll('.status-btn');

      function openStatusModal(id, currentStatus) {
         statusForm.action = `/admin/angsuran/${id}/update-status`;

         // Reset style
         statusButtons.forEach(btn => btn.classList.remove('ring-2', 'ring-purple-500'));

         // Set current status
         if (currentStatus) {
            statusInput.value = currentStatus;
            const activeBtn = document.querySelector(`.status-btn[data-value="${currentStatus}"]`);
            if (activeBtn) activeBtn.classList.add('ring-2', 'ring-purple-500');
         }

         statusModal.classList.remove('hidden');
      }

      function closeStatusModal() {
         statusModal.classList.add('hidden');
      }

      // Pilih status
      statusButtons.forEach(btn => {
         btn.addEventListener('click', () => {
            statusButtons.forEach(b => b.classList.remove('ring-2', 'ring-purple-500'));
            btn.classList.add('ring-2', 'ring-purple-500');
            statusInput.value = btn.dataset.value;
         });
      });

      // Tutup modal klik luar area
      window.addEventListener('click', function(event) {
         if (event.target === statusModal) {
            closeStatusModal();
         }
      });
   </script>

</x-dashboard>
