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
                <form id="beliForm" method="POST" action="{{ route('beli.ponsel', $produk->id_ponsel) }}">
                    @csrf
                    <input type="hidden" name="jumlah" id="jumlah_produk" value="1">
                    <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" value="">
                    <input type="hidden" name="jasa_pengiriman" id="jasa_pengiriman" value="">
                    <input type="hidden" name="nama" id="nama">
                    <input type="hidden" name="telepon" id="telepon">
                    <input type="hidden" name="alamat" id="alamat">
                    <button type="button" id="beliSekarangBtn" class="bg-purple-700 text-white px-5 py-2 rounded-lg hover:bg-purple-800">Beli Sekarang</button>
                </form>
                <form id="cartForm" method="POST" action="{{ route('keranjang.store') }}">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id_ponsel }}">
                    <input type="hidden" name="jumlah" id="jumlah_keranjang" value="1">
                    <button type="submit" class="bg-purple-700 text-white px-5 py-2 rounded-lg hover:bg-purple-800">
                        Masukkan Keranjang
                    </button>
                </form>
            </div>

            <!-- Tab Tombol -->
            <div class="flex gap-4 mb-4">
                <button onclick="setTab('spesifikasi')" id="tab-spesifikasi-btn" class="tab-btn bg-purple-700 text-white px-4 py-1 rounded-md text-sm">
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
            <p><strong>Ram:</strong> {{ $produk->ram }} GB</p>
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
                        <span class="text-sm font-semibold text-gray-800">@Someone</span>
                    </div>
        
                    <div class="text-yellow-400 text-lg mb-1">
                        {!! str_repeat('★', $ulasan->rating) . str_repeat('☆', 5 - $ulasan->rating) !!}
                    </div>
        
                    <p class="mb-4 leading-relaxed">
                        {{ $ulasan->ulasan }}
                    </p>
        
                    @php
                        $like = $ulasan->rating >= 5 ? '❤ 1' : '♡';
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

    <!-- Modal untuk Proses Beli Sekarang -->
    <div id="beliModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <!-- Step Indicator -->
            <div class="flex justify-between mb-6">
                <div class="flex-1 text-center">
                    <span class="step-number inline-block w-8 h-8 rounded-full bg-purple-700 text-white flex items-center justify-center">1</span>
                    <p class="text-sm mt-2">Data Diri</p>
                </div>
                <div class="flex-1 text-center">
                    <span class="step-number inline-block w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center">2</span>
                    <p class="text-sm mt-2">Metode Pembayaran</p>
                </div>
                <div class="flex-1 text-center">
                    <span class="step-number inline-block w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center">3</span>
                    <p class="text-sm mt-2">Jasa Pengiriman</p>
                </div>
                <div class="flex-1 text-center">
                    <span class="step-number inline-block w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center">4</span>
                    <p class="text-sm mt-2">Selesai</p>
                </div>
            </div>

            <!-- Step 1: Data Diri dan Alamat -->
            <div id="step-1" class="step-content">
                <h3 class="text-lg font-semibold mb-4">Data Diri dan Alamat</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" id="input-nama" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
                        <input type="text" id="input-telepon" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Alamat Pengiriman</label>
                        <textarea id="input-alamat" class="w-full px-3 py-2 border rounded-lg" rows="3" required></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button id="cancelBeliBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                    <button id="nextToStep2Btn" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">Lanjut</button>
                </div>
            </div>

            <!-- Step 2: Metode Pembayaran -->
            <div id="step-2" class="step-content hidden">
                <h3 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h3>
                <div id="paymentOptions" class="space-y-3">
                    <!-- Opsi metode pembayaran akan diisi oleh JavaScript -->
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button id="backToStep1Btn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Kembali</button>
                    <button id="nextToStep3Btn" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">Lanjut</button>
                </div>
            </div>

            <!-- Step 3: Jasa Pengiriman -->
            <div id="step-3" class="step-content hidden">
                <h3 class="text-lg font-semibold mb-4">Pilih Jasa Pengiriman</h3>
                <div id="shippingOptions" class="space-y-3">
                    <!-- Opsi jasa pengiriman akan diisi oleh JavaScript -->
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button id="backToStep2Btn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Kembali</button>
                    <button id="nextToStep4Btn" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">Lanjut</button>
                </div>
            </div>

            <!-- Step 4: Pembayaran Berhasil -->
            <div id="step-4" class="step-content hidden">
                <h3 class="text-lg font-semibold mb-4">Pembayaran Berhasil</h3>
                <div class="text-center">
                    <p class="text-green-600 font-medium mb-4">Pembayaran Anda telah berhasil diproses!</p>
                    <p class="text-sm text-gray-600 mb-2">Produk: {{ $produk->merk }} {{ $produk->model }}</p>
                    <p class="text-sm text-gray-600 mb-2">Jumlah: <span id="jumlah-success"></span></p>
                    <p class="text-sm text-gray-600 mb-2">Total Harga: Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600 mb-2">Metode Pembayaran: <span id="metode-success"></span></p>
                    <p class="text-sm text-gray-600 mb-2">Jasa Pengiriman: <span id="jasa-success"></span></p>
                    <p class="text-sm text-gray-600 mb-4">Pesanan akan segera diproses dan dikirim ke alamat Anda.</p>
                    <a href="{{ route('transaksi.index') }}" class="inline-block bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">Lihat Riwayat Transaksi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setTab(tab) {
        const tabs = ['spesifikasi', 'testimoni'];

        tabs.forEach(t => {
            document.getElementById(tab-${t}).classList.add('hidden');
            document.getElementById(tab-${t}-btn).classList.remove('bg-purple-700', 'text-white');
            document.getElementById(tab-${t}-btn).classList.add('bg-gray-200', 'text-gray-700');
        });

        document.getElementById(tab-${tab}).classList.remove('hidden');
        document.getElementById(tab-${tab}-btn).classList.add('bg-purple-700', 'text-white');
        document.getElementById(tab-${tab}-btn).classList.remove('bg-gray-200', 'text-gray-700');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const minusBtn = document.getElementById('minus-btn');
        const plusBtn = document.getElementById('plus-btn');
        const jumlahSpan = document.getElementById('jumlah-span');
        const jumlahInput = document.getElementById('jumlah_produk');
        const jumlahKeranjang = document.getElementById('jumlah_keranjang');
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

        // Logika untuk Modal Beli Sekarang
        const beliSekarangBtn = document.getElementById('beliSekarangBtn');
        const beliModal = document.getElementById('beliModal');
        const cancelBeliBtn = document.getElementById('cancelBeliBtn');
        const beliForm = document.getElementById('beliForm');

        // Step 1: Data Diri
        const nextToStep2Btn = document.getElementById('nextToStep2Btn');
        const inputNama = document.getElementById('input-nama');
        const inputTelepon = document.getElementById('input-telepon');
        const inputAlamat = document.getElementById('input-alamat');
        const namaInput = document.getElementById('nama');
        const teleponInput = document.getElementById('telepon');
        const alamatInput = document.getElementById('alamat');

        // Step 2: Metode Pembayaran
        const paymentOptions = document.getElementById('paymentOptions');
        const backToStep1Btn = document.getElementById('backToStep1Btn');
        const nextToStep3Btn = document.getElementById('nextToStep3Btn');
        const metodePembayaranInput = document.getElementById('metode_pembayaran');

        // Step 3: Jasa Pengiriman
        const shippingOptions = document.getElementById('shippingOptions');
        const backToStep2Btn = document.getElementById('backToStep2Btn');
        const nextToStep4Btn = document.getElementById('nextToStep4Btn');
        const jasaPengirimanInput = document.getElementById('jasa_pengiriman');

        // Step 4: Konfirmasi
        const jumlahSuccess = document.getElementById('jumlah-success');
        const metodeSuccess = document.getElementById('metode-success');
        const jasaSuccess = document.getElementById('jasa-success');

        // Step Navigation
        const steps = ['step-1', 'step-2', 'step-3', 'step-4'];
        let currentStep = 0;

        function showStep(stepIndex) {
            steps.forEach((step, index) => {
                document.getElementById(step).classList.add('hidden');
                document.querySelectorAll('.step-number')[index].classList.remove('bg-purple-700', 'text-white');
                document.querySelectorAll('.step-number')[index].classList.add('bg-gray-300', 'text-gray-700');
            });
            document.getElementById(steps[stepIndex]).classList.remove('hidden');
            document.querySelectorAll('.step-number')[stepIndex].classList.add('bg-purple-700', 'text-white');
            document.querySelectorAll('.step-number')[stepIndex].classList.remove('bg-gray-300', 'text-gray-700');
            currentStep = stepIndex;
        }

        // Buka Modal pada Step 1
        beliSekarangBtn.addEventListener('click', () => {
            showStep(0);
            beliModal.classList.remove('hidden');
        });

        // Tutup Modal
        cancelBeliBtn.addEventListener('click', () => {
            beliModal.classList.add('hidden');
            showStep(0);
            resetForm();
        });

        function resetForm() {
            inputNama.value = '';
            inputTelepon.value = '';
            inputAlamat.value = '';
            paymentOptions.innerHTML = '';
            shippingOptions.innerHTML = '';
            metodePembayaranInput.value = '';
            jasaPengirimanInput.value = '';
            namaInput.value = '';
            teleponInput.value = '';
            alamatInput.value = '';
        }

        // Step 1 ke Step 2
        nextToStep2Btn.addEventListener('click', () => {
            if (inputNama.value && inputTelepon.value && inputAlamat.value) {
                namaInput.value = inputNama.value;
                teleponInput.value = inputTelepon.value;
                alamatInput.value = inputAlamat.value;

                // Ambil daftar metode pembayaran dari backend
                fetch('{{ route("payment.methods") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    paymentOptions.innerHTML = '';
                    data.forEach(method => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'w-full text-left px-4 py-2 border rounded-lg hover:bg-gray-100';
                        button.textContent = method.name;
                        button.dataset.value = method.value;
                        button.addEventListener('click', () => {
                            document.querySelectorAll('#paymentOptions button').forEach(btn => btn.classList.remove('bg-gray-200'));
                            button.classList.add('bg-gray-200');
                            metodePembayaranInput.value = method.value;
                        });
                        paymentOptions.appendChild(button);
                    });
                    showStep(1);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat metode pembayaran.');
                });
            } else {
                alert('Harap isi semua data diri dan alamat.');
            }
        });

        // Step 2 ke Step 1
        backToStep1Btn.addEventListener('click', () => {
            showStep(0);
        });

        // Step 2 ke Step 3
        nextToStep3Btn.addEventListener('click', () => {
            if (metodePembayaranInput.value) {
                // Daftar jasa pengiriman statis (bisa diganti dengan fetch ke backend)
                const shippingMethods = [
                    { name: 'JNE', value: 'jne' },
                    { name: 'J&T Express', value: 'jnt' },
                    { name: 'GoSend', value: 'gosend' },
                    { name: 'SiCepat', value: 'sicepat' }
                ];

                shippingOptions.innerHTML = '';
                shippingMethods.forEach(method => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'w-full text-left px-4 py-2 border rounded-lg hover:bg-gray-100';
                    button.textContent = method.name;
                    button.dataset.value = method.value;
                    button.addEventListener('click', () => {
                        document.querySelectorAll('#shippingOptions button').forEach(btn => btn.classList.remove('bg-gray-200'));
                        button.classList.add('bg-gray-200');
                        jasaPengirimanInput.value = method.value;
                    });
                    shippingOptions.appendChild(button);
                });
                showStep(2);
            } else {
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
            }
        });

        // Step 3 ke Step 2
        backToStep2Btn.addEventListener('click', () => {
            showStep(1);
        });

        // Step 3 ke Step 4
        nextToStep4Btn.addEventListener('click', () => {
            if (jasaPengirimanInput.value) {
                // Tampilkan detail di Step 4
                jumlahSuccess.textContent = jumlahInput.value;
                metodeSuccess.textContent = metodePembayaranInput.value;
                jasaSuccess.textContent = jasaPengirimanInput.value;

                // Submit form ke backend
                beliForm.submit();
                showStep(3);
            } else {
                alert('Silakan pilih jasa pengiriman terlebih dahulu.');
            }
        });
    });
</script>
@endsection