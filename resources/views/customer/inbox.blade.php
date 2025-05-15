<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - Glory Ponsel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom scrollbar untuk chat box */
        .chat-box::-webkit-scrollbar {
            width: 8px;
        }
        .chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .chat-box::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .chat-box::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">Glory Ponsel</a>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('produk.index') }}" class="text-gray-700 hover:text-blue-600">Produk</a>
                <a href="{{ route('keranjang') }}" class="text-gray-700 hover:text-blue-600">Keranjang</a>
                <a href="{{ route('transaksi.index') }}" class="text-gray-700 hover:text-blue-600">Transaksi</a>
                
                <!-- User Dropdown -->
                <div class="relative">
                    <button class="flex items-center space-x-2 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-gray-700">User</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-lg flex flex-col" style="height: calc(100vh - 150px);">
            <!-- Header Chat -->
            <div class="bg-blue-600 text-white p-5 rounded-t-lg flex justify-between items-center">
                <div>
                    <h1 class="font-bold text-xl">Glory Ponsel Support</h1>
                    <p class="text-sm text-blue-100 flex items-center mt-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        Online - Membalas dalam beberapa menit
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button class="text-blue-100 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                        </svg>
                    </button>
                    <button class="text-blue-100 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Chat Box -->
            <div class="chat-box flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                <!-- Tanggal Divider -->
                <div class="flex justify-center">
                    <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">Hari Ini</span>
                </div>

                <!-- Admin Messages -->
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="max-w-xl">
                        <div class="bg-gray-200 rounded-xl py-3 px-4">
                            <p class="text-gray-800">Halo, ada yang bisa kami bantu?</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">Admin - 10:00 AM</p>
                    </div>
                </div>

                <!-- Customer Messages -->
                <div class="flex justify-end">
                    <div class="max-w-xl">
                        <div class="bg-blue-600 text-white rounded-xl py-3 px-4">
                            <p>Saya mau tanya soal stok iPhone 13 Pro Max 256GB</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 mr-1 text-right">Anda - 10:02 AM</p>
                    </div>
                </div>

                <!-- Admin Messages -->
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="max-w-xl">
                        <div class="bg-gray-200 rounded-xl py-3 px-4">
                            <p class="text-gray-800">Masih ready kak! Untuk warna kami punya Silver, Graphite, dan Gold. Mau warna apa?</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">Admin - 10:03 AM</p>
                    </div>
                </div>

                <!-- Customer Messages -->
                <div class="flex justify-end">
                    <div class="max-w-xl">
                        <div class="bg-blue-600 text-white rounded-xl py-3 px-4">
                            <p>Warna Graphite, berapa harganya? Apakah ada promo khusus hari ini?</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 mr-1 text-right">Anda - 10:05 AM</p>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 border-t bg-white">
                <div class="flex items-center space-x-2">
                    <button class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>
                    <input 
                        type="text" 
                        placeholder="Ketik pesan..." 
                        class="flex-1 border border-gray-300 rounded-full px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <button class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>