@extends('layouts.layout_home')
@section('content')
   <div class="container px-6 mx-auto grid">
      <h2 class="my-6 text-2xl font-semibold text-gray-700">
         Detail Pengajuan Tukar Tambah
      </h2>

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
                  </div>
                  @if ($pengajuan->catatan_admin)
                     <div class="alert alert-info mt-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700"
                        role="alert">
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

         <div class="mt-8 flex gap-4">
            <a href="{{ route('pengajuan') }}"
               class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-md active:bg-gray-50 hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray">
               Kembali ke Daftar
            </a>
            <a href="{{ route('customer.inbox') }}"
               class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-md active:bg-gray-50 hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray">
               Hubungi Kami
            </a>
         </div>
      </div>
   </div>
@endsection
