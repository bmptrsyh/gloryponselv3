@extends('layouts.layout_home')
@section('content')
   <div class="min-h-screen py-10 px-2 bg-gray-50">
      <div class="max-w-4xl mx-auto">
         <h2 class="text-2xl font-bold mb-6 text-center text-indigo-700">Pengajuan Kredit Ponsel</h2>

         <div class="p-4 bg-white rounded-lg shadow mb-6">
            <h2 class="text-base font-semibold mb-3">Data Pribadi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8 text-sm">
               <div>
                  <p class="font-semibold text-gray-600">Nama Lengkap</p>
                  <p class="font-medium">{{ $data['step1']['nama_lengkap'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Tempat Lahir</p>
                  <p class="font-medium">{{ $data['step1']['tempat_lahir'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Tanggal Lahir</p>
                  <p class="font-medium">{{ $data['step1']['tanggal_lahir'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Jenis Kelamin</p>
                  <p class="font-medium">{{ $data['step1']['jenis_kelamin'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Status Pernikahan</p>
                  <p class="font-medium">{{ $data['step1']['status_pernikahan'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">NIK</p>
                  <p class="font-medium">{{ $data['step1']['nik'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">No. Telepon</p>
                  <p class="font-medium">{{ $data['step1']['telepon'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Email</p>
                  <p class="font-medium">{{ $data['step1']['email'] ?? '-' }}</p>
               </div>
            </div>
         </div>

         <div class="p-4 bg-white rounded-lg shadow mb-6">
            <h2 class="text-base font-semibold mb-3">Data Pekerjaan & Pengajuan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8 text-sm">
               <div>
                  <p class="font-semibold text-gray-600">Pekerjaan</p>
                  <p class="font-medium">{{ $data['step2']['pekerjaan'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Perusahaan</p>
                  <p class="font-medium">{{ $data['step2']['nama_perusahaan'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Alamat Perusahaan</p>
                  <p class="font-medium">{{ $data['step2']['alamat_perusahaan'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Lama Bekerja</p>
                  <p class="font-medium">{{ $data['step2']['lama_bekerja'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Penghasilan</p>
                  <p class="font-medium">{{ $data['step2']['penghasilan_bulanan'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Penghasilan Lain</p>
                  <p class="font-medium">{{ $data['step2']['penghasilan_lain'] ?? '-' }}</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">Jangka Waktu</p>
                  <p class="font-medium">{{ $data['step2']['jangka_waktu'] ?? '-' }} bulan</p>
               </div>
               <div>
                  <p class="font-semibold text-gray-600">DP</p>
                  <p class="font-medium">Rp {{ number_format($data['step2']['dp'] ?? 0) }}</p>
               </div>
            </div>
         </div>

         <div class="p-4 bg-white rounded-lg shadow mb-6">
            <h2 class="text-base font-semibold mb-3">Upload Dokumen</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
               <div class="flex flex-col items-center">
                  <p class="font-semibold text-gray-600 mb-2">Foto KTP</p>
                  <img src="data:image/jpeg;base64,{{ $data['step3']['foto_ktp'] }}"
                     class="h-40 w-40 object-contain rounded-lg border border-gray-200 shadow" alt="KTP">
               </div>
               <div class="flex flex-col items-center">
                  <p class="font-semibold text-gray-600 mb-2">Foto Selfie dengan KTP</p>
                  <img src="data:image/jpeg;base64,{{ $data['step3']['foto_selfie'] }}"
                     class="h-40 w-40 object-contain rounded-lg border border-gray-200 shadow" alt="Selfie">
               </div>
            </div>
         </div>

         <form action="{{ route('kredit.submit') }}" method="POST" class="bg-white rounded-lg shadow p-4">
            @csrf
            <label class="inline-flex items-center mb-4">
               <input type="checkbox" id="agreeCheckbox" class="form-checkbox accent-indigo-600">
               <span class="ml-2 text-gray-700">Saya yakin data yang saya kirim sudah benar</span>
            </label>
            <div class="flex flex-col sm:flex-row justify-between gap-3">
               <button type="button" onclick="history.back()"
                  style="padding: 12px 24px; background-color: #6c757d; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">
                  Kembali
               </button>
               <button type="submit" id="submitBtn" disabled
                  class="bg-gradient-to-r from-green-500 to-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow transition-all duration-200 opacity-50 cursor-not-allowed">
                  Kirim Pengajuan
               </button>
            </div>
         </form>



      </div>
   </div>
   <script>
      document.getElementById('agreeCheckbox').addEventListener('change', function() {
         const btn = document.getElementById('submitBtn');
         if (this.checked) {
            btn.removeAttribute('disabled');
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
         } else {
            btn.setAttribute('disabled', true);
            btn.classList.add('opacity-50', 'cursor-not-allowed');
         }
      });
   </script>
@endsection
