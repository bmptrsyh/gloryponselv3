
       
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Glory Ponsel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS Select2 -->
    
    <style>
                * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #fff;
            color: #333;
        }
        header {
            width: 100%;
            background-color: #fff;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }
        .logo {
            font-size: 22px;
            font-weight: 700;
        }
        nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #007bff;
        }
        .sign-in {
            background-color: #4F46E5;
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
        }
        .hero {
            padding: 80px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .hero-text {
            max-width: 50%;
        }
        .hero-text h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .hero-text p {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn-primary {
            background-color: #4F46E5;
            color: #fff;
            padding: 12px 28px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #3730a3;
        }
        .hero-image {
            width: 45%;
            height: 300px;
            background-color: #ccc;
            border-radius: 12px;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">Glory Ponsel</div>
        <nav>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('produk.index') }}">Produk</a>
            <a href="{{ route('pengajuan') }}">Pengajuan</a>
            <a href="{{ route('daftar.kredit') }}">Daftar Kredit</a>
            <a href="{{ route('customer.inbox') }}">Kontak</a>
            <a href=" {{ route('keranjang') }}">
                <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Cart" width="20">
            </a>
    
            @guest
                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Register
                    </a>
                @endif
                <a href="{{ route('login') }}" class="sign-in">Sign In</a>
            @endguest
    
            @auth
                <a href="{{ route('customer.profile') }}">
                    <img src="{{ $customer->foto_profil_url }}" width="30" height="30" class="rounded-full">
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="log-out">Log Out</button>
                </form>                
            @endauth
        </nav>
    </header>
@if (session('error'))
    <div id="alert" class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        {{ session('error') }}
    </div>
@elseif (session('success'))
    <div id="alert" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
        @yield('content')
    

<!-- Footer -->
<footer style="background-color: #111; color: #fff; padding: 60px 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 30px; max-width: 1200px; margin: auto;">
        
        <!-- Kolom 1: Deskripsi -->
        <div style="flex: 1; min-width: 250px;">
            <h3 style="font-weight: bold;">Glory Ponsel</h3>
            <p style="line-height: 1.6;">is the leading ReCommerce Platform in Indonesia. We believe ReCommerce can help Emerging Middle-Class to upgrade their lifestyle, at affordable prices.</p>
        </div>

        <!-- Kolom 2: Useful Links -->
        <div style="flex: 1; min-width: 200px;">
            <h4 style="margin-bottom: 10px;">USEFUL LINKS</h4>
            <ul style="list-style: none; padding: 0;">
                <li><a href="{{ route('home') }}" style="color: #fff; text-decoration: none;">• Home</a></li>
                <li><a href="{{ route('produk.index') }}" style="color: #fff; text-decoration: none;">• Produk</a></li>
                <li><a href="#" style="color: #fff; text-decoration: none;">• Tentang Kami</a></li>
                <li><a href="#" style="color: #fff; text-decoration: none;">• Kontak</a></li>
            </ul>
        </div>

        <!-- Kolom 3: Contact -->
        <div style="flex: 1.5; min-width: 250px;">
            <h4 style="margin-bottom: 10px;">CONTACT US</h4>
            Telp: 085882105531<br>
            Email: Gloryonsel@gmail.com</p>
        </div>

        <!-- Kolom 4: Pengaduan -->
        <div style="flex: 1.5; min-width: 250px;">
            <h4 style="margin-bottom: 10px;">LAYANAN PENGADUAN KONSUMEN</h4>
            <p>Direktorat Jenderal Perlindungan Konsumen dan Tertib Niaga<br>
            Kementerian Perdagangan RI<br>
            Whatsapp: 085311111010</p>
        </div>
    </div>

    <div style="text-align: center; padding-top: 40px; color: #aaa;">
        <small>Copyright &copy; 2025 Made by Team</small>
    </div>
</footer>
</body>
<script>
    // Tunggu sampai halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function () {
        const alert = document.getElementById('alert');
        if (alert) {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000); // 3000 milidetik = 3 detik
        }
    });
</script>


</html>
