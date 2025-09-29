@extends('layouts.layout_home')

@section('content')
   <div class="p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 sm:gap-0">
         <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">
            Detail Cicilan - {{ $kredit->ponsel->merk }} {{ $kredit->ponsel->model }}
         </h1>
         <button type="button" onclick="history.back()"
            style="padding: 12px 24px; background-color: #6c757d; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">
            Kembali
         </button>

      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
         <!-- Informasi Cicilan -->
         <div class="p-4 bg-white rounded-lg shadow">
            <h2 class="text-base font-semibold mb-3">Informasi Cicilan</h2>
            <div class="grid grid-cols-2 gap-y-3 text-sm font-semibold">
               <div>
                  <p class="text-gray-600">Total Harga + Bunga</p>
                  <p class="text-purple-700 font-bold">
                     Rp {{ number_format($kredit->jumlah_pinjaman, 0, ',', '.') }}
                  </p>
               </div>
               <div>
                  <p class="text-gray-600">Tenor</p>
                  <p class="font-semibold">{{ $kredit->tenor }} bulan</p>
               </div>
               <div>
                  <p class="text-gray-600">Cicilan Per Bulan</p>
                  <p class="text-blue-600">
                     Rp {{ number_format($kredit->angsuran_per_bulan, 0, ',', '.') }}
                  </p>
               </div>
               @if ($jatuhTempo)
                  <div>
                     <p class="text-gray-600">Jatuh Tempo Pertama</p>
                     <p class="text-orange-600">{{ $jatuhTempo->format('d M Y') }}</p>
                  </div>
               @endif
            </div>
         </div>

         <!-- Detail Ponsel -->
         <div class="p-4 bg-white rounded-lg shadow">
            <h2 class="text-base font-semibold mb-3">Detail Ponsel</h2>
            <div class="flex gap-4">
               <img src="{{ asset($kredit->ponsel->gambar) }}"
                  alt="{{ $kredit->ponsel->merk }} {{ $kredit->ponsel->model }}"
                  class="w-28 h-28 object-contain rounded-lg shadow">
               <div class="grid grid-cols-2 gap-2 text-sm">
                  <div>
                     <p class="text-gray-600">Merk</p>
                     <p class="font-medium">{{ $kredit->ponsel->merk }}</p>
                  </div>
                  <div>
                     <p class="text-gray-600">Model</p>
                     <p class="font-medium">{{ $kredit->ponsel->model }}</p>
                  </div>
                  <div>
                     <p class="text-gray-600">RAM</p>
                     <p class="font-medium">{{ $kredit->ponsel->ram }} GB</p>
                  </div>
                  <div>
                     <p class="text-gray-600">Storage</p>
                     <p class="font-medium">{{ $kredit->ponsel->storage }} GB</p>
                  </div>
                  <div>
                     <p class="text-gray-600">Kondisi</p>
                     <p class="font-medium">{{ $kredit->ponsel->status }}</p>
                  </div>
                  <div>
                     <p class="text-gray-600">Processor</p>
                     <p class="font-medium">{{ $kredit->ponsel->processor }}</p>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Laporan Cicilan -->
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
                              <span class="text-red-600 font-semibold">Belum</span>
                           @endif
                        </td>
                        <td class="px-4 py-4 text-center border">
                           {{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') : '-' }}
                        </td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
@endsection
