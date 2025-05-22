<!-- resources/views/admin/inbox.blade.php -->
<x-dashboard>
        
<!-- Admin Inbox -->
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg flex flex-col" style="height: calc(100vh - 150px);">
        <!-- Header -->
        <div class="bg-gray-800 text-white p-5 rounded-t-lg">
            <h1 class="font-bold text-xl">Customer Chat</h1>
            <p class="text-sm text-gray-300">Balas pesan dari pelanggan secara real-time</p>
        </div>

        <!-- Chat Box -->
        <div id="chatBox" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
            @foreach ($chats as $chat)
    <div class="{{ $chat->sender_type === 'admin' ? 'flex justify-end' : 'flex items-start space-x-3' }}">
        @if ($chat->sender_type === 'customer')
            <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center overflow-hidden">
                <img src="{{ $customer->foto_profil }}" alt="Foto Profil" class="rounded-full w-full h-full object-cover">
            </div>
        @endif
        <div class="max-w-xl">
            <div class="{{ $chat->sender_type === 'admin' ? 'bg-gray-800 text-white' : 'bg-blue-100 text-blue-900' }} rounded-xl py-3 px-4">
                {{ $chat->message }}
            </div>
            <p class="text-xs text-gray-500 mt-1">
                {{ $chat->sender_type === 'admin' ? 'Admin' : 'Customer' }} - {{ \Carbon\Carbon::parse($chat->created_at)->format('H:i') }}
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
                    placeholder="Ketik balasan..." 
                    class="flex-1 border border-gray-300 rounded-full px-5 py-3 focus:outline-none focus:ring-2 focus:ring-gray-800"
                >
                <button type="submit" class="bg-gray-800 text-white p-3 rounded-full hover:bg-gray-900">
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
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }
});


var currentCustomerId = {{ $customer->id_customer }};

var channel = pusher.subscribe('private-inbox.admin.{{ auth("admin")->id() }}');
channel.bind('Inbox', function(data) {
    if (
        data.senderId     == currentCustomerId
    ) {
        appendMessage(data.message, data.sender);
    }
});

    // Tangani submit balasan admin
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('chatMessage');
        const message = input.value.trim();
        const customerId = {{ $customer->id_customer }};
        if (message === '') return;

        fetch("{{ route('admin.send.inbox') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message, customer_id: customerId })
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                appendMessage(message, 'admin');
                input.value = '';
            }
        });
    });

    function appendMessage(message, sender = 'customer') {
        const chatBox = document.getElementById('chatBox');
        const wrapper = document.createElement('div');
        wrapper.className = sender === 'admin' ? 'flex justify-end' : 'flex items-start space-x-3';

        const content = document.createElement('div');
        content.className = 'max-w-xl';

        const bubble = document.createElement('div');
        bubble.className = sender === 'admin' 
            ? 'bg-gray-800 text-white rounded-xl py-3 px-4' 
            : 'bg-blue-100 text-blue-900 rounded-xl py-3 px-4';
        bubble.innerText = message;

        const time = document.createElement('p');
        time.className = 'text-xs text-gray-500 mt-1';
        const now = new Date();
        const hour = now.getHours().toString().padStart(2, '0');
        const min = now.getMinutes().toString().padStart(2, '0');
        time.innerText = (sender === 'admin' ? 'Admin' : 'Customer') + ' - ' + hour + ':' + min;

        content.appendChild(bubble);
        content.appendChild(time);

        if (sender === 'customer') {
            const icon = document.createElement('div');
            icon.className = "w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center";
            icon.innerHTML = `<img src="{{ $customer->foto_profil }}" alt="Foto Profil" class="rounded-full w-full h-full object-cover">`;
            wrapper.appendChild(icon);
        }

        wrapper.appendChild(content);
        chatBox.appendChild(wrapper);

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    document.addEventListener('DOMContentLoaded', () => {
        resetNotifCount();
    });
</script>
</x-dashboard>