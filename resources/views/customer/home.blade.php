@extends('layouts.layout_home')
@section('content')
<section class="hero">
    <div class="hero-text">
        <h1>Platform Ecommerce Terdepan di Indonesia</h1>
        <p>Kami percaya bahwa ReCommerce dapat meningkatkan standar hidup masyarakat Kelas Menengah dengan harga terjangkau.</p>
    </div>
    <div class="hero-image">
        <img src="{{ asset('storage/gambar/admin/media.png') }}" alt="Hero Image" width="100%">
    </div>
</section>

<section style="background-color: #f9f9f9; padding: 60px;">
  <!-- Bagian Statistik -->
    <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; padding: 40px 20px; background-color: #f9f9f9;">

        <div style="text-align: center; flex: 1 1 200px; max-width: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Icon" width="32" style="margin-bottom: 10px;">
            <p style="font-size: 16px; font-weight: 600;">{{ $countBeliPonsel }}</p>
            <p style="font-size: 14px; color: #555;">barang yang sudah dikirim</p>
        </div>

        <div style="text-align: center; flex: 1 1 200px; max-width: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Icon" width="32" style="margin-bottom: 10px;">
            <p style="font-size: 16px; font-weight: 600;">{{ $count }}</p>
            <p style="font-size: 14px; color: #555;">pelanggan aktif</p>
        </div>

        <div style="text-align: center; flex: 1 1 200px; max-width: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Icon" width="32" style="margin-bottom: 10px;">
            <p style="font-size: 16px; font-weight: 600;">{{ $countUlasan }}</p>
            <p style="font-size: 14px; color: #555;">ulasan positif</p>
        </div>

        <div style="text-align: center; flex: 1 1 200px; max-width: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Icon" width="32" style="margin-bottom: 10px;">
            <p style="font-size: 16px; font-weight: 600;">{{ $jumlahPengunjung }}</p>
            <p style="font-size: 14px; color: #555;">pengunjung bulanan</p>
        </div>

</div>



    <!-- Konten Utama -->
    <div style="display: flex; gap: 60px; align-items: center;">
        <!-- Ilustrasi Placeholder -->
        <div>
            <img src="{{ asset('storage/gambar/admin/media.png') }}" alt="a" style="max-width: 100%; height: 300px; border-radius: 12px;">
        </div>
        
        <!-- Teks -->
        <div style="flex: 1;">
            <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 16px;">
                Marketplace yang menghubungkan pemilik gadget preloved dengan pelaku bisnis.
            </h2>
            <p style="margin-bottom: 20px; line-height: 1.6;">
                Glory Ponsel mengembangkan dan menjalankan berbagai macam Platform dan Teknologi, yang memfasilitasi ekosistem pasar C2B (Consumer to Business), dimana individu dapat menjual gadgetnya kepada ribuan pembeli dengan Cepat, Aman, dan Mudah.
            </p>
            <ul style="line-height: 1.8;">
                <li><strong>Pasti Terjual</strong>, ribuan pembeli memiliki banyak daftar barang yang mereka inginkan</li>
                <li><strong>Kecepatan</strong>, ribuan pembeli di platform kami siap membeli tanpa negosiasi</li>
                <li><strong>Aman dan Mudah</strong>, kami menyediakan layanan seperti penjemputan dari penjual, pengecekan kondisi, dan pengantaran ke pembeli</li>
            </ul>
        </div>
    </div>
</section>

<!-- Produk Terbaru -->
<section style="padding: 60px 20px; background-color: #fff; text-align: center;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 30px;">Produk Terbaru</h2>

    <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
        @forelse ($produkTerbaru as $produk)
        <div style="width: 180px; background-color: #f5f5f5; padding: 15px; border-radius: 12px;">
            <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->model }}" style="width: 200px; border-radius: 8px; height: 200px; object-fit: contain;">
                <p style="margin-top: 10px; font-weight: bold;">{{ $produk->merk }} {{ $produk->model }}</p>
        </div>
        @empty
            <p>Tidak ada produk terbaru.</p>
        @endforelse
    </div>
</section>


<!-- Ulasan Pelanggan -->
<section style="padding: 60px 20px; background-color: #f9f9f9; text-align: center;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 30px;">Ulasan Pelanggan</h2>
<div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
    @foreach ($ulasans as $ulasan)
        <div style="width: 280px; background-color: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 10px;">
                <img src="{{ $ulasan->ponsel->gambar }}" alt="User Icon" width="40">
            </div>
                <p style="font-style: italic;">{{ $ulasan->ulasan }}</p>
        </div>
    @endforeach
</div>

</section>
@endsection