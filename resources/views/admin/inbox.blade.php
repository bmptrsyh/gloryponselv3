<!-- resources/views/inbox.blade.php -->
<x-dashboard>
        
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-xl font-bold mb-6">Inbox - Chat dengan Admin</h1>

        <!-- Chat Box -->
        <div class="border rounded-lg p-4 mb-6 h-96 overflow-y-auto bg-gray-50">
            <!-- Admin Message -->
            <div class="flex mb-4">
                <div class="bg-gray-200 rounded-lg py-2 px-4 max-w-xs">
                    <p class="text-gray-800">Halo, ada yang bisa kami bantu?</p>
                    <p class="text-xs text-gray-500 mt-1">Admin - 10:30 AM</p>
                </div>
            </div>

            <!-- User Message -->
            <div class="flex mb-4 justify-end">
                <div class="bg-blue-500 text-white rounded-lg py-2 px-4 max-w-xs">
                    <p>Saya mau tanya soal stok iPhone 13</p>
                    <p class="text-xs text-blue-100 mt-1">Anda - 10:32 AM</p>
                </div>
            </div>

            <!-- Admin Message -->
            <div class="flex mb-4">
                <div class="bg-gray-200 rounded-lg py-2 px-4 max-w-xs">
                    <p class="text-gray-800">Masih ready kak! Mau warna apa?</p>
                    <p class="text-xs text-gray-500 mt-1">Admin - 10:33 AM</p>
                </div>
            </div>

            <!-- User Message -->
            <div class="flex mb-4 justify-end">
                <div class="bg-blue-500 text-white rounded-lg py-2 px-4 max-w-xs">
                    <p>Warna midnight, berapa harganya?</p>
                    <p class="text-xs text-blue-100 mt-1">Anda - 10:35 AM</p>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="flex gap-2">
            <input type="text" placeholder="Ketik pesan..." class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Kirim</button>
        </div>
    </div>
    @vite('resources/js/app.js')
</x-dashboard>