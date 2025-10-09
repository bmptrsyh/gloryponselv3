<x-dashboard>
   <div class="container px-6 mx-auto grid">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 sm:gap-0">
         <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Daftar Laporan Pembukuan
         </h2>
         <div class="flex flex-col sm:flex-row gap-2 sm:space-x-2">
            <a href="{{ route('admin.pembukuan.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition shadow">
               <i class="fas fa-plus mr-1"></i> Tambah Laporan</a>
         </div>
      </div>

      {{-- 🔹 Filter --}}
      <form method="GET" action="{{ route('admin.pembukuan') }}" class="mb-6 flex gap-3" id="filterForm">
         <select name="bulan" class="border rounded px-3 py-2"
            onchange="document.getElementById('filterForm').submit()">
            @for ($i = 1; $i <= 12; $i++)
               <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                  {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
               </option>
            @endfor
         </select>

         <select name="tahun" class="border rounded px-3 py-2"
            onchange="document.getElementById('filterForm').submit()">
            @for ($i = date('Y'); $i >= 2020; $i--)
               <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
         </select>

         <a href="{{ route('admin.pembukuan.export') }}?bulan={{ request('bulan', $bulan) }}&tahun={{ request('tahun', $tahun) }}"
            class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">
            Download
         </a>
      </form>

      {{-- 🔹 Tabel --}}
      <div class="w-full overflow-hidden rounded-lg shadow-lg">
         <div class="w-full overflow-x-auto rounded-lg shadow-lg">
            <table class="w-full min-w-max whitespace-no-wrap">
               <thead>
                  <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">

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
                  @forelse($laporan as $row)
                     <tr class="text-gray-700 hover:bg-blue-50 transition">
                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm whitespace-normal break-words w-48">
                           {{ $row->deskripsi }}
                        </td>
                        <td class="px-4 py-3 text-sm text-green-700 font-semibold">
                           Rp {{ number_format($row->debit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-red-700 font-semibold">
                           Rp {{ number_format($row->kredit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-blue-700">
                           Rp {{ number_format($row->saldo, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                           @if ($row->metode_pembayaran)
                              <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-700">
                                 {{ $row->metode_pembayaran }}
                              </span>
                           @else
                              <span class="text-gray-400">-</span>
                           @endif
                        </td>
                        <td class="px-4 py-3">
                           <div class="flex items-center space-x-2">
                              <a href="{{ route('admin.pembukuan.edit', $row->id_laporan) }}"
                                 class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded hover:bg-yellow-200 transition"
                                 title="Edit">
                                 <i class="fas fa-edit"></i>
                              </a>
                              <form action="{{ route('admin.pembukuan.delete', $row->id_laporan) }}" method="POST"
                                 onsubmit="return confirm('Yakin ingin menghapus?');">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit"
                                    class="bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 transition"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                 </button>
                              </form>
                           </div>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500">Belum ada data</td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>
</x-dashboard>
