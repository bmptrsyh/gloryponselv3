<x-dashboard>
   <div class="container px-6 mx-auto grid">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 sm:gap-0">
         <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Daftar Laporan Pembukuan
         </h2>
         <div class="flex flex-col sm:flex-row gap-2 sm:space-x-2">
            <a href=""
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full sm:w-auto inline-block text-center">
               Tambahkan
            </a>
         </div>
      </div>
      <div class="w-full overflow-hidden rounded-lg shadow-xs">
         <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
               <thead>
                  <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                     <th class="px-4 py-3">Id Transaksi</th>
                     <th class="px-4 py-3">Tanggal</th>
                     <th class="px-4 py-3">Deskripsi</th>
                     <th class="px-4 py-3">Debit</th>
                     <th class="px-4 py-3">Kredit</th>
                     <th class="px-4 py-3">Saldo</th>
                     <th class="px-4 py-3">Metode Pembayaran</th>
                     <th class="px-4 py-3">Aksi</th>
                  </tr>
               </thead>
               <tbody class="bg-white divide-y">
                  <tr class="text-gray-700">
                     <td class="px-4 py-3 text-sm">TR001</td>
                     <td class="px-4 py-3 text-sm">2025-09-23</td>
                     <td class="px-4 py-3 text-sm">Penjualan iPhone 14 Pro Max</td>
                     <td class="px-4 py-3 text-sm">12.000.000</td>
                     <td class="px-4 py-3 text-sm">0</td>
                     <td class="px-4 py-3 text-sm">12.000.000</td>
                     <td class="px-4 py-3 text-sm">Transfer bank</td>
                     <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                           <button class="text-blue-600 hover:text-blue-800">
                              <i class="fas fa-edit"></i>
                           </button>
                           <button class="text-red-600 hover:text-red-800">
                              <i class="fas fa-trash"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
                  <tr class="text-gray-700">
                     <td class="px-4 py-3 text-sm">TR002</td>
                     <td class="px-4 py-3 text-sm">2025-09-23</td>
                     <td class="px-4 py-3 text-sm">Pembelian bekas Galaxy S23 Ultra</td>
                     <td class="px-4 py-3 text-sm">0</td>
                     <td class="px-4 py-3 text-sm">8.000.000</td>
                     <td class="px-4 py-3 text-sm">12.000.000</td>
                     <td class="px-4 py-3 text-sm">Cash</td>
                     <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                           <button class="text-blue-600 hover:text-blue-800">
                              <i class="fas fa-edit"></i>
                           </button>
                           <button class="text-red-600 hover:text-red-800">
                              <i class="fas fa-trash"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
                  <tr class="text-gray-700">
                     <td class="px-4 py-3 text-sm">TR003</td>
                     <td class="px-4 py-3 text-sm">2025-09-23</td>
                     <td class="px-4 py-3 text-sm">Penjualan Xiaomi 13 Pro</td>
                     <td class="px-4 py-3 text-sm">5.400.000</td>
                     <td class="px-4 py-3 text-sm">0</td>
                     <td class="px-4 py-3 text-sm">12.000.000</td>
                     <td class="px-4 py-3 text-sm">Cash</td>
                     <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                           <button class="text-blue-600 hover:text-blue-800">
                              <i class="fas fa-edit"></i>
                           </button>
                           <button class="text-red-600 hover:text-red-800">
                              <i class="fas fa-trash"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
                  <tr class="text-gray-700">
                     <td class="px-4 py-3 text-sm">TR004</td>
                     <td class="px-4 py-3 text-sm">2025-09-22</td>
                     <td class="px-4 py-3 text-sm">Penjualan Oppo Find X6 Pro</td>
                     <td class="px-4 py-3 text-sm">7.800.000</td>
                     <td class="px-4 py-3 text-sm">0</td>
                     <td class="px-4 py-3 text-sm">12.000.000</td>
                     <td class="px-4 py-3 text-sm">Transfer</td>
                     <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                           <button class="text-blue-600 hover:text-blue-800">
                              <i class="fas fa-edit"></i>
                           </button>
                           <button class="text-red-600 hover:text-red-800">
                              <i class="fas fa-trash"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

</x-dashboard>
