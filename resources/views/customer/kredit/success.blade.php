@extends('layouts.layout_home')
@section('content')
   <div
      style="max-width: 800px; margin: 40px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

      <!-- Progress Bar -->
      <div style="margin-bottom: 30px;">
         <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <div style="flex: 1; height: 4px; background-color: #007bff; border-radius: 2px;"></div>
            <div style="flex: 1; height: 4px; background-color: #007bff; border-radius: 2px; margin-left: 4px;"></div>
            <div style="flex: 1; height: 4px; background-color: #007bff; border-radius: 2px; margin-left: 4px;"></div>
            <div style="flex: 1; height: 4px; background-color: #007bff; border-radius: 2px; margin-left: 4px;"></div>
         </div>
         <p style="font-size: 14px; color: #666; margin: 0;">4/4</p>
      </div>

      <!-- Form Title -->
      <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 30px; color: #333;">Pengajuan Kredit Ponsel</h2>

      <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #333;">Data Berhasil Dikirim</h3>
      <div class="grid grid-cols-2 gap-4">
         <div>
            <label class="block text-sm font-medium">Harga Ponsel</label>
            <p>Rp {{ number_format($hargaPonsel, 0, ',', '.') }}</p>

         </div>
         <div>
            <label class="block text-sm font-medium">Jangka Waktu (Bulan)</label>
            <p>{{ $kredit->tenor }}</p>

         </div>
         <div>
            <label class="block text-sm font-medium">Jumlah DP</label>
            <p>Rp {{ number_format($kredit->jumlah_DP, 0, ',', '.') }}</p>

         </div>
         <div>
            <label class="block text-sm font-medium">Total Cicilan Per Bulan</label>
            <p>Rp {{ number_format($kredit->angsuran_per_bulan, 0, ',', '.') }}</p>

         </div>
         <div>
            <label class="block text-sm font-medium">Total Bayar</label>
            <p>Rp {{ number_format($kredit->jumlah_pinjaman, 0, ',', '.') }}</p>

         </div>
      </div>

      <div class="flex justify-between mt-6">
         <a href="{{ route('home') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Selesai</a>
      </div>
   </div>
@endsection
