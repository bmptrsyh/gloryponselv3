<x-dashboard>
   <div class="container px-6 mx-auto grid">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 sm:gap-0">
         <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Daftar Laporan Pembukuan
         </h2>
         <div class="flex flex-col sm:flex-row gap-2 sm:space-x-2">
            <!-- Setujui -->
            <form action="" method="POST">
               @csrf
               <input type="hidden" name="status" value="disetujui">
               <button type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full sm:w-auto">
                  Tambahkan
               </button>
            </form>
         </div>
      </div>
      <div class="w-full overflow-hidden rounded-lg shadow-xs">
         <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
               <thead>
                  <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                     <th class="px-4 py-3">Id Transaksi</th>
                     <th class="px-4 py-3">Tanggal</th>
                     <th class="px-4 py-3">Debit</th>
                     <th class="px-4 py-3">Kredit</th>
                     <th class="px-4 py-3">Saldo</th>
                     <th class="px-4 py-3">Metode Pembayaran</th>
                     <th class="px-4 py-3">Aksi</th>
                  </tr>
               </thead>
</x-dashboard>
