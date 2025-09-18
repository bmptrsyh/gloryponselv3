@extends('layouts.layout_home')
@section('content')
   <div class="p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 sm:gap-0">
         <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Detail Pengajuan Kredit Ponsel</h1>

      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
         <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-start justify-between mb-2">
               <h2 class="font-semibold text-lg">Status Pengajuan</h2>
               <div class="text-right">
                  <p class="text-xs text-gray-400">Tanggal Pengajuan</p>
                  <p class="text-sm text-gray-600">{{ $kredit->created_at->format('d M Y') }}</p>
               </div>
            </div>
            @if ($kredit->status == 'menunggu')
               <span class="inline-block px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 mb-2">
                  Menunggu
               </span>
            @elseif($kredit->status == 'disetujui')
               <span class="inline-block px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 mb-2">
                  Disetujui
               </span>
            @elseif($kredit->status == 'ditolak')
               <span class="inline-block px-3 py-1 text-sm rounded-full bg-red-100 text-red-700 mb-2">
                  Ditolak
               </span>
            @endif
            @if (!empty($kredit->alasan_ditolak))
               <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                  <p class="text-sm font-semibold text-red-700">Alasan Ditolak:</p>
                  <p class="text-sm text-red-600">{{ $kredit->alasan_ditolak }}</p>
               </div>
            @endif
            <p class="text-gray-600">Harga Pengajuan</p>
            <p class="text-2xl font-bold text-purple-700">Rp {{ number_format($kredit->jumlah_pinjaman, 0, ',', '.') }}
            </p>
         </div>
         <div class="p-4 bg-white rounded-lg shadow">
            <h2 class="text-base font-semibold text-bold mb-3">Informasi Cicilan</h2>
            <div class="grid grid-cols-2 gap-y-3 text-sm font-semibold">
               <div>
                  <p class="text-gray-600">Harga Ponsel</p>
                  <p class="font-semibold">Rp {{ number_format($kredit->ponsel->harga_jual, 0, ',', '.') }}</p>
               </div>
               <div>
                  <p class="text-gray-600">Jangka Waktu</p>
                  <p class="font-semibold">{{ $kredit->tenor }} bulan</p>
               </div>
               <div>
                  <p class="text-gray-600">Cicilan Per Bulan</p>
                  <p class="text-blue-600">Rp {{ number_format($kredit->angsuran_per_bulan, 0, ',', '.') }}</p>
               </div>
               <div>
                  <p class="text-gray-600">Uang Muka</p>
                  <p class="text-red-600">Rp {{ number_format($kredit->jumlah_DP, 0, ',', '.') }}</p>
               </div>
            </div>
         </div>
      </div>
      <!-- Spesifikasi Ponsel -->
      <div class="p-4 bg-white rounded-lg shadow mb-6">
         <h2 class="text-base font-semibold text-bold mb-3">Spesifikasi Ponsel</h2>
         <div class="grid grid-cols-3 gap-y-4 gap-x-8 text-sm">
            <div>
               <p class="font-semibold text-gray-600">Merk</p>
               <p class="font-medium">{{ $kredit->ponsel->merk }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Model</p>
               <p class="font-medium"> {{ $kredit->ponsel->model }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">RAM</p>
               <p class="font-medium">{{ $kredit->ponsel->ram }} GB</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Storage</p>
               <p class="font-medium">{{ $kredit->ponsel->storage }} GB</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Kondisi</p>
               <p class="font-medium">{{ $kredit->ponsel->kondisi }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Processor</p>
               <p class="font-medium">{{ $kredit->ponsel->processor }}</p>
            </div>
         </div>
      </div>

   </div>
@endsection
