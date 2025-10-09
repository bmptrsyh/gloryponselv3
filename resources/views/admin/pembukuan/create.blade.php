<x-dashboard>

   <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-2xl font-bold mb-6">Tambah Laporan Pembukuan</h2>
      <form action="{{ route('admin.pembukuan.store') }}" method="POST">
         @csrf
         <div class = " grid grid-cols-1 gap-4 mb-4">
            <div class="mb-5">
               <label for="tanggal" class="block font-semibold mb-2 text-gray-700">
                  Tanggal
               </label>
               <input id="tanggal" type="date" name="tanggal" max="{{ date('Y-m-d') }}"
                  class="w-full px-4 py-3 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  required>
            </div>

            <div class="block font-medium text-gray-700 mb-1">
               <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
               <x-input name="deskripsi" class="form-control" required />
            </div>
            <div class="block font-medium text-gray-700 mb-1">
               <label class="block font-medium text-gray-700 mb-1">Debit</label>
               <x-input type="number" name="debit" class="form-control" />
            </div>
            <div class="block font-medium text-gray-700 mb-1">
               <label class="block font-medium text-gray-700 mb-1">Kredit</label>
               <x-input type="number" name="kredit" class="form-control" />
            </div>
            <div class="p-4 bg-white rounded shadow">
               <label class="block font-medium text-gray-700 mb-1">Metode Pembayaran</label>
               <select name="metode_pembayaran"
                  class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200" required>
                  <option value="transfer">Transfer</option>
                  <option value="e-wallet">E-Wallet</option>
                  <option value="COD">COD</option>
                  <option value="lainnya">Lainnya</option>
               </select>
            </div>
         </div>
         <div class="flex justify-between mt-8">
            <a href="{{ route('admin.pembukuan') }}"
               class="px-6 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Kembali</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lanjut</button>
         </div>
      </form>
   </div>

</x-dashboard>
