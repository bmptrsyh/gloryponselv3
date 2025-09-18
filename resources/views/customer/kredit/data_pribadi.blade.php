@extends('layouts.layout_home')
@section('content')
   <div
      style="max-width: 800px; margin: 40px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

      <!-- Progress Bar -->
      <div style="margin-bottom: 30px;">
         <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <div style="flex: 1; height: 4px; background-color: #007bff; border-radius: 2px;"></div>
            <div style="flex: 1; height: 4px; background-color: #e0e0e0; border-radius: 2px; margin-left: 4px;"></div>
            <div style="flex: 1; height: 4px; background-color: #e0e0e0; border-radius: 2px; margin-left: 4px;"></div>
            <div style="flex: 1; height: 4px; background-color: #e0e0e0; border-radius: 2px; margin-left: 4px;"></div>
         </div>
         <p style="font-size: 14px; color: #666; margin: 0;">1/4</p>
      </div>

      <!-- Form Title -->
      <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 30px; color: #333;">Pengajuan Kredit Ponsel</h2>

      <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #333;">Data Pribadi</h3>

      <form action="{{ route('kredit.step1.post') }}" method="POST">
         @csrf

         <!-- Nama Lengkap -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="{{ $customer->nama }}"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
         </div>

         <!-- NIK -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Nomor Induk Kependudukan
               (NIK)</label>
            <input type="text" name="nik" placeholder="Masukkan NIK (16 digit)" maxlength="16"
               value="{{ $customer->nik ?? '' }}"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
         </div>

         <!-- Tempat Lahir -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" placeholder="Masukkan tempat lahir"
               value="{{ $customer->tempat_lahir ?? '' }}"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
         </div>

         <!-- Tanggal Lahir -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ $customer->tanggal_lahir ?? '' }}"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
         </div>

         <!-- Jenis Kelamin -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
               Jenis Kelamin
            </label>
            <div style="display: flex; gap: 20px; align-items: center;">
               <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                  <input type="radio" name="jenis_kelamin" value="Laki-laki" required>
                  <span>Laki-laki</span>
               </label>
               <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                  <input type="radio" name="jenis_kelamin" value="Perempuan" required>
                  <span>Perempuan</span>
               </label>
            </div>
         </div>

         <!-- Status Pernikahan -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Status Pernikahan</label>
            <div style="display: flex; gap: 20px; margin-top: 8px;">
               <label style="display: flex; align-items: center; font-weight: normal;">
                  <input type="radio" name="status_pernikahan" value="Belum Menikah" style="margin-right: 8px;" required>
                  Belum Menikah
               </label>
               <label style="display: flex; align-items: center; font-weight: normal;">
                  <input type="radio" name="status_pernikahan" value="Menikah" style="margin-right: 8px;" required>
                  Menikah
               </label>
            </div>
         </div>

         <!-- Alamat KTP -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Alamat KTP</label>
            <textarea name="alamat_ktp" placeholder="Masukkan alamat sesuai dengan KTP" rows="3" value = "{{ $customer->alamat ?? '' }}"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"
               required></textarea>
         </div>

         <!-- Alamat Domisili -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Alamat Domisili</label>
            <textarea name="alamat_domisili" placeholder="Masukkan alamat sesuai dengan domisili" rows="3"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"
               required></textarea>
         </div>

         <!-- Nomor Telepon -->
         <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Nomor Telepon</label>
            <input type="tel" name="no_telp" placeholder="Masukkan nomor telepon aktif" value="{{ $customer->nomor_telepon ?? '' }}"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
         </div>

         <!-- Alamat Email -->
         <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Alamat Email</label>
            <input type="email" name="email" placeholder="Masukkan Alamat Email" value="{{ $customer->email ?? '' }}"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
         </div>

         <!-- Navigation Buttons -->
         <div style="display: flex; justify-content: space-between; align-items: center;">
            <button type="button" onclick="history.back()"
               style="padding: 12px 24px; background-color: #6c757d; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">
               Kembali
            </button>
            <button type="submit"
               style="padding: 12px 24px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">
               Lanjut
            </button>
         </div>
      </form>
   </div>

   <script>
      // NIK validation - only numbers
      document.querySelector('input[name="nik"]').addEventListener('input', function(e) {
         this.value = this.value.replace(/[^0-9]/g, '');
      });

      // Phone number validation - only numbers and + symbol
      document.querySelector('input[name="no_telp"]').addEventListener('input', function(e) {
         this.value = this.value.replace(/[^0-9+]/g, '');
      });
   </script>
@endsection
