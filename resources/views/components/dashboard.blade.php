<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard GloryPonsel</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-100">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white p-6 flex flex-col min-h-screen hidden md:flex">
        <h1 class="text-2xl font-bold text-blue-600 mb-8">Glory <span class="text-gray-800">Ponsel</span></h1>
      
        <nav class="flex flex-col gap-4">
          <x-sidebar-link route="admin.dashboard">Dashboard</x-sidebar-link>
          <x-sidebar-link route="admin.ponsel.index">Produk</x-sidebar-link>
          <x-sidebar-link>Inbox</x-sidebar-link>
          <x-sidebar-link >Daftar Pesanan</x-sidebar-link>
          <x-sidebar-link>Stok Produk</x-sidebar-link>
          <x-sidebar-link route="admin.ponsel.transaksi">Transaksi</x-sidebar-link>
        </nav>
      
        <!-- Menu bawah -->
        <div class="mt-auto flex flex-col gap-2 pt-4 border-t">
          <a href="#" 
            class="{{ request()->is('settings') ? 'text-white bg-blue-500 px-4 py-2 rounded-lg' : 'text-gray-400 hover:text-blue-600' }}">
            Settings
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-red-500 text-left">Logout</button>
          </form>
        </div>
      </aside>
      
    <!-- Main content -->
    <main class="flex-1 flex flex-col">
      <!-- Header (dibungkus agar menyatu dengan sidebar) -->
      <div class="bg-white px-6 py-4 flex justify-between items-center min-w-screen">
        <form action="" method="GET" class="w-1/3 flex space-x-4">
          <input type="text" name="keyword" placeholder="Search" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
          <input type="submit" value="submit" class="px-4 py-2 border border-gray-300 rounded-lg"/>

        </form>

        <div class="flex items-center gap-4">
          <span class="relative">
            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">9+</span>
            <img src="{{ asset('storage/gambar/admin/notifikasi.png') }}" alt="Profile" class="w-6 h-6" />
            {{-- <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg> --}}
          </span>
          <div class="flex items-center gap-2">
            <img src="{{ asset('storage/gambar/admin/admin.png') }}" alt="Profile" class="w-10 h-10 rounded-full" />
            <div>
              <p class="font-semibold">Admin</p>
              <p class="text-sm text-gray-500">Glory Ponsel</p>
            </div>
          </div>
        </div>
        
      </div>

      <!-- Content -->
      <div class="p-6">
        {{ $slot }}
      </div>
    </main>
  </div>
</body>
</html>
