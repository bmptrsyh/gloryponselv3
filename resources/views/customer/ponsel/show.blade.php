@extends('layouts.layout_home')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <!-- Atas: Gambar + Info -->
    <div class="grid md:grid-cols-2 gap-10">
        <!-- Gambar Produk -->
        <div class="flex justify-center">
            <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->merk }} {{ $produk->model }}" class="rounded-xl max-h-96 object-contain">
        </div>

        <!-- Info Produk -->
        <div>
            <h2 class="text-xl font-semibold mb-2">{{ $produk->merk }} {{ $produk->model }}</h2>
            <div class="flex items-center space-x-2 mb-2">
                <div class="text-yellow-400 text-lg">
                    @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($produk->rating))
                        <i class="fas fa-star"></i>
                    @elseif ($i - $produk->rating <= 0.5)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
                </div>
                <div class="text-gray-600 text-sm">{{ number_format($produk->rating, 1) }} • {{ $produk->terjual }}+ terjual</div>
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-4">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>

            <!-- Jumlah -->
            <div class="flex items-center space-x-4 mb-6">
                <label class="text-sm font-medium">Jumlah</label>
                <div class="flex items-center border rounded">
                    <button type="button" id="minus-btn" class="px-3 py-1 text-lg font-bold">-</button>
                    <span id="jumlah-span" class="px-4">1</span>
                    <button type="button" id="plus-btn" class="px-3 py-1 text-lg font-bold">+</button>
                </div>
                <span class="text-xs text-gray-500">Tersisa {{ $produk->stok }} buah</span>
            </div>
            

            <!-- Tombol Aksi -->
            <div class="flex flex-wrap gap-4 mb-6">
                <form method="POST" action="{{ route('beli.ponsel', $produk->id_ponsel) }}">
                    @csrf
                    <input type="hidden" name="jumlah" id="jumlah_produk" value="1">
                    <input type="hidden" name="metode_pembayaran" value="cod">
                    <button type="submit" class="bg-purple-700 text-white px-5 py-2 rounded-lg hover:bg-purple-800">Beli Sekarang</button>
                </form>
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id_ponsel }}">
                    <input type="hidden" name="jumlah" id="jumlah_keranjang" value="1">
                    
                    <button type="button" id="checkoutBtn" class="border border-purple-700 text-purple-700 px-5 py-2 rounded-lg hover:bg-purple-50">
                        Masukkan Keranjang
                    </button>
                </form>
                
                
            </div>

            <div id="paymentModal" class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-white w-full max-w-4xl max-h-[80vh] overflow-y-auto p-6 rounded-lg shadow-lg z-50 hidden">
                <button id="closeModal" class="absolute top-2 right-2 text-gray-500 text-2xl">&times;</button>
                <h2 class="text-xl font-bold mb-4">Pilih Metode Pembayaran</h2>
            
                <!-- Kolom Metode Pembayaran -->
                <div id="paymentCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Kartu Metode Pembayaran akan diisi oleh JavaScript -->
                </div>
            </div>

            <!-- Tab Tombol -->
            <div class="flex gap-4 mb-4">
                <button onclick="setTab('spesifikasi')" id="tab-spesifikasi-btn" class="tab-btn bg-blue-700 text-white px-4 py-1 rounded-md text-sm">
                    Spesifikasi
                </button>
                <button onclick="setTab('testimoni')" id="tab-testimoni-btn" class="tab-btn bg-gray-200 text-gray-700 px-4 py-1 rounded-md text-sm">
                    Testimoni
                </button>
            </div>
        </div>
    </div>

    <!-- Spesifikasi -->
    <div id="tab-spesifikasi" class="tab-content">
    <div class="mt-12 space-y-2 text-sm text-gray-700">
        <h3 class="text-lg font-semibold text-black mb-2">Spesifikasi Produk</h3>
        <p><strong>Status:</strong> {{ $produk->status }}</p>
        <p><strong>Merk:</strong> {{ $produk->merk }}</p>
        <p><strong>Model:</strong> {{ $produk->model }}</p>
        <p><strong>Kapasitas Penyimpanan:</strong> {{ $produk->storage }} GB</p>
        <p><strong>Ram:</strong> {{ $produk->stok }} GB</p>
        <p><strong>Processor:</strong> {{ $produk->processor }}</p>
        <p><strong>Dimension:</strong> {{ $produk->dimension }}</p>
        <p><strong>Stok:</strong> {{ $produk->stok }}</p>
    </div>
    </div>

    <div id="tab-testimoni" class="tab-content hidden">
        <div class="mt-12 space-y-2 text-sm text-gray-700">
        <h3 class="text-lg font-semibold text-black mb-4">Penilaian Produk</h3>
    
        @forelse($produk->ulasan as $ulasan)
            <div class="bg-white p-6 rounded-2xl shadow-md border text-sm text-gray-700 mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">👤</span>
                    <span class="text-sm font-semibold text-gray-800">@Someone</span> {{-- Bisa ganti kalau ada nama user --}}
                </div>
    
                <div class="text-yellow-400 text-lg mb-1">
                    {!! str_repeat('★', $ulasan->rating) . str_repeat('☆', 5 - $ulasan->rating) !!}
                </div>
    
                <p class="mb-4 leading-relaxed">
                    {{ $ulasan->ulasan }}
                </p>
    
                @php
                    // Contoh: tampilkan ❤️ untuk rating >= 5
                    $like = $ulasan->rating >= 5 ? '❤️ 1' : '♡';
                @endphp
                <div class="text-sm {{ $ulasan->rating >= 5 ? 'text-red-500' : 'text-gray-500' }}">
                    {{ $like }}
                </div>
            </div>
        @empty
            <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
        @endforelse
        </div>
    </div>
    
    
</div>
<script>
    function setTab(tab) {
        const tabs = ['spesifikasi', 'testimoni'];

        tabs.forEach(t => {
            document.getElementById(`tab-${t}`).classList.add('hidden');
            document.getElementById(`tab-${t}-btn`).classList.remove('bg-blue-700', 'text-white');
            document.getElementById(`tab-${t}-btn`).classList.add('bg-gray-200', 'text-gray-700');
        });

        document.getElementById(`tab-${tab}`).classList.remove('hidden');
        document.getElementById(`tab-${tab}-btn`).classList.add('bg-blue-700', 'text-white');
        document.getElementById(`tab-${tab}-btn`).classList.remove('bg-gray-200', 'text-gray-700');
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const minusBtn = document.getElementById('minus-btn');
    const plusBtn = document.getElementById('plus-btn');
    const jumlahSpan = document.getElementById('jumlah-span');
    const jumlahInput = document.getElementById('jumlah_produk');
    const jumlahKeranjang = document.querySelector('input[name="jumlah"][id="jumlah_keranjang"]');
    const maxStok = {{ $produk->stok }};

    let jumlah = 1;

    minusBtn.addEventListener('click', () => {
        if (jumlah > 1) {
            jumlah--;
            jumlahSpan.innerText = jumlah;
            jumlahInput.value = jumlah;
            jumlahKeranjang.value = jumlah;
        }
    });

    plusBtn.addEventListener('click', () => {
        if (jumlah < maxStok) {
            jumlah++;
            jumlahSpan.innerText = jumlah;
            jumlahInput.value = jumlah;
            jumlahKeranjang.value = jumlah;
        }
    });
});

</script>

<script>
    document.getElementById('checkoutBtn').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('paymentForm'));

    fetch('{{ route("payment.methods") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const modal = document.getElementById('paymentModal');
        const cardsContainer = document.getElementById('paymentCards');
        cardsContainer.innerHTML = ''; // Kosongkan dulu

        const methods = data.payment_methods?.paymentFee;
        const totalAmount = parseInt(data.total_amount); // total dari backend, misalnya 12500000

        if (methods && methods.length > 0) {
            methods.forEach(method => {
                const fee = parseInt(method.totalFee);
                const totalBiaya = fee + totalAmount;

                const card = document.createElement('div');
                card.className = "border rounded-lg p-4 flex items-center justify-between shadow hover:shadow-md transition";

                card.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <img src="${method.paymentImage}" alt="${method.paymentName}" class="h-10 w-10 object-contain">
                        <div>
                            <h3 class="font-semibold">${method.paymentName}</h3>
                            <p class="text-sm text-gray-500">Biaya: Rp${totalBiaya.toLocaleString()}</p>
                        </div>
                    </div>
                    <button class="chooseBtn bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700"
                            data-method="${method.paymentMethod}">
                        Pilih
                    </button>
                `;

                cardsContainer.appendChild(card);
            });

            // Event saat tombol "Pilih" diklik
            document.querySelectorAll('.chooseBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const selectedMethod = this.getAttribute('data-method');
                    alert('Metode pembayaran dipilih: ' + selectedMethod);
                    modal.classList.add('hidden'); // Tutup modal
                });
            });

            modal.classList.remove('hidden'); // Tampilkan modal di depan halaman utama
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Tombol untuk menutup modal
document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('paymentModal').classList.add('hidden');
});





    </script>


@endsection
