@extends('layouts.layout_home')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Jual Ponsel Anda</h2>
        <p class="text-gray-600 mt-2">Isi formulir di bawah ini untuk mengajukan penjualan ponsel Anda ke kami.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('jual.ponsel.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Merk Ponsel -->
            <div>
                <label for="merk" class="block text-sm font-medium text-gray-700 mb-1">Merk Ponsel <span class="text-red-500">*</span></label>
                <input type="text" name="merk" id="merk" value="{{ old('merk') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
            </div>

            <!-- Model Ponsel -->
            <div>
                <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model Ponsel <span class="text-red-500">*</span></label>
                <input type="text" name="model" id="model" value="{{ old('model') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
            </div>

            <!-- Warna -->
            <div>
                <label for="warna" class="block text-sm font-medium text-gray-700 mb-1">Warna <span class="text-red-500">*</span></label>
                <input type="text" name="warna" id="warna" value="{{ old('warna') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
            </div>

            <!-- RAM -->
            <div>
                <label for="ram" class="block text-sm font-medium text-gray-700 mb-1">RAM (GB) <span class="text-red-500">*</span></label>
                <input type="number" name="ram" id="ram" value="{{ old('ram') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
            </div>

            <!-- Storage -->
            <div>
                <label for="storage" class="block text-sm font-medium text-gray-700 mb-1">Penyimpanan (GB) <span class="text-red-500">*</span></label>
                <input type="number" name="storage" id="storage" value="{{ old('storage') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
            </div>

            <!-- Processor -->
            <div>
                <label for="processor" class="block text-sm font-medium text-gray-700 mb-1">Processor <span class="text-red-500">*</span></label>
                <input type="text" name="processor" id="processor" value="{{ old('processor') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
            </div>

            <!-- Kondisi -->
            <div class="md:col-span-2">
                <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Ponsel <span class="text-red-500">*</span></label>
                <select name="kondisi" id="kondisi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                    <option value="">Pilih Kondisi</option>
                    <option value="Seperti Baru" {{ old('kondisi') == 'Seperti Baru' ? 'selected' : '' }}>Seperti Baru (Mulus 95-100%)</option>
                    <option value="Sangat Baik" {{ old('kondisi') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik (Mulus 85-95%)</option>
                    <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik (Mulus 70-85%)</option>
                    <option value="Normal" {{ old('kondisi') == 'Normal' ? 'selected' : '' }}>Normal (Mulus 50-70%)</option>
                    <option value="Kurang Baik" {{ old('kondisi') == 'Kurang Baik' ? 'selected' : '' }}>Kurang Baik (Mulus <50%)</option>
                </select>
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Detail <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>{{ old('deskripsi') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Jelaskan detail kondisi ponsel, termasuk cacat/kerusakan jika ada, kelengkapan, dan informasi penting lainnya.</p>
            </div>

            <!-- Harga -->
            <div class="md:col-span-2">
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga yang Diharapkan (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" min="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                <p class="text-xs text-gray-500 mt-1">Masukkan harga yang Anda harapkan. Tim kami akan meninjau dan mungkin menawarkan harga yang berbeda.</p>
            </div>

            <!-- Upload Gambar -->
            <div class="md:col-span-2" >
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Foto Ponsel <span class="text-red-500">*</span></label>
                <div id="drop-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                <span>Upload foto</span>
                                <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*" required>
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                    </div>
                </div>
                <div id="image-preview" class="mt-3 hidden">
                    <img id="gambar-preview" src="#" alt="Preview" class="mt-2 max-h-40 rounded-md">
                    <button type="button" onclick="resetUpload()" class="mt-2 text-sm text-purple-600 hover:underline">
                        Ganti Foto
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('produk.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 mr-3">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Ajukan Penjualan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('gambar').addEventListener('change', function (event) {
        const previewContainer = document.getElementById('image-preview');
        const previewImage = document.getElementById('gambar-preview');
        const uploadArea = event.target.closest('.border-dashed'); // container input upload
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadArea.classList.add('hidden'); // Sembunyikan upload area
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.src = '';
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden'); // Tampilkan lagi jika dibatalkan
        }
    });
</script>

<script>
    function resetUpload() {
        const fileInput = document.getElementById('gambar');
        const previewContainer = document.getElementById('image-preview');
        const previewImage = document.getElementById('gambar-preview');
        const uploadArea = fileInput.closest('.border-dashed');

        fileInput.value = '';
        previewImage.src = '';
        previewContainer.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    }
</script>

<script>
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('gambar');

    // Mencegah default browser behavior
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => e.preventDefault());
        dropArea.addEventListener(eventName, e => e.stopPropagation());
    });

    // Highlight saat drag masuk
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => {
            dropArea.classList.add('ring-2', 'ring-purple-500');
        });
    });

    // Hilangkan highlight saat drag keluar
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => {
            dropArea.classList.remove('ring-2', 'ring-purple-500');
        });
    });

    // Tangani file saat di-drop
    dropArea.addEventListener('drop', e => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            fileInput.files = files;

            // Trigger event change agar preview muncul
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    });
</script>


@endsection
