<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Dashboard GloryPonsel</title>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script src="https://cdn.tailwindcss.com"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
   <style>
      body {
         font-family: 'Inter', sans-serif;
      }
   </style>
</head>

<body class="bg-gray-100">
   @if (session('success'))
      <script>
         document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
               icon: 'success',
               title: 'Berhasil!',
               text: "{{ session('success') }}",
               showConfirmButton: false,
               timer: 2000
            });
         });
      </script>
   @endif
   <div class="flex min-h-screen">
      <!-- Sidebar -->
      <aside class="w-64 bg-white p-6 flex flex-col min-h-screen hidden md:flex">
         <h1 class="text-2xl font-bold text-blue-600 mb-8">Glory <span class="text-gray-800">Ponsel</span></h1>

         <nav class="flex flex-col gap-4">
            <x-sidebar-link route="admin.dashboard">Dashboard</x-sidebar-link>
            <x-sidebar-link route="admin.ponsel.index">Produk</x-sidebar-link>
            <x-sidebar-link route="admin.listInbox">Inbox</x-sidebar-link>
            <x-sidebar-link route="admin.ponsel.transaksi">Daftar Pesanan</x-sidebar-link>
            <x-sidebar-link route="admin.jual-ponsel.index">Jual Ponsel</x-sidebar-link>
            <x-sidebar-link route="admin.tukar-tambah.index">Tukar Tambah</x-sidebar-link>
            <x-sidebar-link route="admin.kredit.index">Kredit Ponsel</x-sidebar-link>
            <x-sidebar-link>Ulasan</x-sidebar-link>
            <x-sidebar-link>Stok Produk</x-sidebar-link>
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
               <input type="text" name="keyword" placeholder="Search"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
               <input type="submit" value="submit" class="px-4 py-2 border border-gray-300 rounded-lg" />

            </form>

            <div class="flex items-center gap-4">
               <span class="relative">
                  <span id="notifCount"
                     class="{{ $unreadCount == 0 ? 'hidden' : '' }} absolute -top-1 -right-2 bg-red-500 text-white text-xs 
                px-2 py-1 rounded-full flex items-center justify-center min-w-[18px] h-5">
                     {{ $unreadCount }}
                  </span>

                  <img src="{{ asset('storage/gambar/admin/notifikasi.png') }}" alt="Profile" class="w-6 h-6" />

               </span>
               <a href="{{ route('admin.profile') }}">
                  <div class="flex items-center gap-2">
                     <img src="{{ asset('storage/gambar/admin/admin.png') }}" alt="Profile"
                        class="w-10 h-10 rounded-full" />
                     <div>
               </a>
               <p class="font-semibold">Admin</p>
               <p class="text-sm text-gray-500">Glory Ponsel</p>
            </div>
         </div>
   </div>

   </div>

   <!-- Content -->
   <div class="p-6">
      <div id="notification" class="hidden fixed top-5 right-5 bg-blue-600 text-white px-4 py-3 rounded shadow-lg z-50">
         <p id="notificationText">Pesan baru diterima</p>
      </div>
      {{ $slot }}
   </div>
   </main>
   </div>
   <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
   <script>
      // Inisialisasi Pusher
      var pusher = new Pusher('19b63228ecff31232668', {
         cluster: 'ap1',
         authEndpoint: '/broadcasting/auth',
         auth: {
            headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
         }
      });

      var channel = pusher.subscribe('private-inbox.admin.{{ auth('admin')->id() }}');

      channel.bind('Inbox', function(data) {
         // Contoh: hanya tampilkan jika pesan datang dari customer yang sedang tidak dibuka
         if (data.receiverType === 'admin' && data.receiverId == {{ auth('admin')->id() }}) {
            // Tampilkan notifikasi
            showNotification(`Pesan baru dari Customer`);
            incrementNotifCount();
            // {
            //   appendMessage(data.message, data.sender);
            // }
         }
      });

      let unreadCount = {{ $unreadCount }};

      function incrementNotifCount() {
         unreadCount++;
         const badge = document.getElementById('notifCount');
         if (badge) {
            if (unreadCount > 0) {
               badge.textContent = unreadCount;
               badge.classList.remove('hidden');
            } else {
               badge.classList.add('hidden');
            }
         }
      }

      // function resetNotifCount() {
      //     unreadCount = 0;
      //     const badge = document.getElementById('notifCount');
      //     if (badge) {
      //         badge.classList.add('hidden');
      //         badge.textContent = '0';
      //     }
      // }

      function showNotification(message = 'Pesan baru diterima') {
         const notif = document.getElementById('notification');
         const notifText = document.getElementById('notificationText');
         notifText.textContent = message;
         notif.classList.remove('hidden');

         setTimeout(() => {
            notif.classList.add('hidden');
         }, 3000);
      }
   </script>
</body>

</html>
