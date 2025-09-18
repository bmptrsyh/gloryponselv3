@extends('layouts.layout_home')
@section('content')
<div style="max-width: 800px; margin: 40px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    
    <!-- Progress Bar -->
    <div style="margin-bottom: 30px;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <div style="flex: 1; height: 4px; background-color: #007bff; border-radius: 2px;"></div>
            <div style="flex: 1; height: 4px; background-color: #007bff; border-radius: 2px; margin-left: 4px;"></div>
            <div style="flex: 1; height: 4px; background-color: #e0e0e0; border-radius: 2px; margin-left: 4px;"></div>
            <div style="flex: 1; height: 4px; background-color: #e0e0e0; border-radius: 2px; margin-left: 4px;"></div>
        </div>
        <p style="font-size: 14px; color: #666; margin: 0;">2/4</p>
    </div>

    <!-- Form Title -->
    <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 30px; color: #333;">Pengajuan Kredit Ponsel</h2>
    
    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #333;">Data Pekerjaan & Pengajuan</h3>

    <form action="{{ route('kredit.step2.post') }}" method="POST">
        @csrf
        
        <!-- Pekerjaan -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Pekerjaan</label>
            <input type="text" name="pekerjaan" placeholder="Masukkan pekerjaan Anda" 
                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
        </div>

        <!-- Nama Perusahaan -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" placeholder="Masukkan nama perusahaan Anda" 
                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
        </div>

        <!-- Alamat Perusahaan -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Alamat Perusahaan</label>
            <textarea name="alamat_perusahaan" placeholder="Masukkan Alamat Lengkap Perusahaan" rows="3"
                      style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;" required></textarea>
        </div>

        <!-- Lama Bekerja -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Lama Bekerja</label>
            <input type="number" name="lama_bekerja" placeholder="Masukkan Berapa Lama Anda Bekerja" 
                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
        </div>

        <!-- Penghasilan Bulanan -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Penghasilan Bulanan</label>
            <input type="number" name="penghasilan_bulanan" placeholder="Masukkan penghasilan bulanan" 
                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
        </div>

        <!-- Penghasilan Lain (opsional) -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Penghasilan Lain (opsional)</label>
            <input type="number" name="penghasilan_lain" placeholder="Masukkan penghasilan lain" 
                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
        </div>

        <!-- Jangka Waktu Pinjaman (Bulan) -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Jangka Waktu Pinjaman (Bulan)</label>
            <input type="number" name="jangka_waktu" placeholder="Contoh: 12" 
                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" required>
        </div>

        <!-- Jumlah DP -->
        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Jumlah DP</label>
            <input type="number" name="jumlah_dp" placeholder="Masukkan Jumlah DP anda" 
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
@endsection