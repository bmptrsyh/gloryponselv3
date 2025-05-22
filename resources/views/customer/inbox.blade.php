<!-- resources/views/customer/inbox.blade.php -->
@extends('layouts.layout_home')
@section('content')
<!-- Main Content -->
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg flex flex-col" style="height: calc(100vh - 150px);">
        <!-- Header Chat -->
        <div class="bg-blue-600 text-white p-5 rounded-t-lg flex justify-between items-center">
            <div>
                <h1 class="font-bold text-xl">Glory Ponsel Support</h1>
                <p class="text-sm text-blue-100 flex items-center mt-1">
                    Online - Membalas dalam beberapa menit
                </p>
            </div>
        </div>

        <!-- Chat Box -->
        <div id="chatBox" class="chat-box flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
            @foreach ($chats as $chat)
    <div class="{{ $chat->sender_type === 'customer' ? 'flex justify-end' : 'flex items-start space-x-3' }}">
        @if ($chat->sender_type !== 'customer')
            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('storage/gambar/admin/admin.png') }}" alt="Foto Profil" class="rounded-full w-full h-full object-cover">
                </div>
            </div>
        @endif
        <div class="max-w-xl">
            <div class="{{ $chat->sender_type === 'customer' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }} rounded-xl py-3 px-4">
                {{ $chat->message }}
            </div>
            <p class="text-xs text-gray-500 mt-1 {{ $chat->sender_type === 'customer' ? 'text-right mr-1' : 'ml-1' }}">
                {{ $chat->sender_type === 'customer' ? 'Anda' : 'Admin' }} - {{ \Carbon\Carbon::parse($chat->created_at)->format('H:i') }}
            </p>
        </div>
    </div>
@endforeach
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t bg-white">
            <form id="chatForm" class="flex items-center space-x-2">
                <input 
                    type="text" 
                    id="chatMessage" 
                    name="message" 
                    placeholder="Ketik pesan..." 
                    class="flex-1 border border-gray-300 rounded-full px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <button type="submit" class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
    // Inisialisasi Pusher
    var pusher = new Pusher('19b63228ecff31232668', {
    cluster: 'ap1',
    authEndpoint: '/broadcasting/auth', // agar channel privat bisa diakses
    auth: {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }
});

// Ganti {{ auth()->id() }} dengan ID customer saat ini
var currentCustomerId = {{ auth()->id() }};

var channel = pusher.subscribe('private-inbox.customer.' + currentCustomerId);
channel.bind('Inbox', function(data) {
    if (
        data.receiverId   == currentCustomerId
    ) {
        appendMessage(data.message, data.sender);
    }
});

    // Tangani submit form
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('chatMessage');
        const message = input.value.trim();
        if (message === '') return;

        // Kirim ke backend via fetch
        fetch("{{ route('send.inbox') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                appendMessage(message, 'customer');
                input.value = '';
            }
        });
    });

    // Fungsi untuk menambahkan pesan ke chat box
    function appendMessage(message, sender = 'admin') {
        const chatBox = document.getElementById('chatBox');

        const wrapper = document.createElement('div');
        wrapper.className = sender === 'customer' ? 'flex justify-end' : 'flex items-start space-x-3';

        const content = document.createElement('div');
        content.className = 'max-w-xl';

        const bubble = document.createElement('div');
        bubble.className = sender === 'customer' 
            ? 'bg-blue-600 text-white rounded-xl py-3 px-4' 
            : 'bg-gray-200 text-gray-800 rounded-xl py-3 px-4';
        bubble.innerText = message;

        const time = document.createElement('p');
        time.className = sender === 'customer' 
            ? 'text-xs text-gray-500 mt-1 mr-1 text-right' 
            : 'text-xs text-gray-500 mt-1 ml-1';
        const now = new Date();
        const hour = now.getHours().toString().padStart(2, '0');
        const min = now.getMinutes().toString().padStart(2, '0');
        time.innerText = (sender === 'customer' ? 'Anda' : 'Admin') + ' - ' + hour + ':' + min;

        content.appendChild(bubble);
        content.appendChild(time);

        if (sender === 'admin') {
            const icon = document.createElement('div');
            icon.className = "w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center";
            icon.innerHTML = `<img src="{{ asset('storage/gambar/admin/admin.png') }}" alt="Foto Profil" class="rounded-full w-full h-full object-cover">`;
            wrapper.appendChild(icon);
        }

        wrapper.appendChild(content);
        chatBox.appendChild(wrapper);

        // Scroll ke bawah otomatis
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
@endsection
