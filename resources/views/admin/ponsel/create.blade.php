<x-dashboard>
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>

        <form action="{{ route('admin.ponsel.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col">
                    {{-- Preview Gambar Baru --}}
                    <img id="gambar-preview" class="mb-4 w-64 h-auto rounded-lg shadow hidden" />

                    {{-- Input Gambar --}}
                    <label for="gambar" class="font-medium text-gray-700 mb-2">Gambar</label>
                    <input type="file" name="gambar" id="gambar"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700"
                        accept="image/*" />
                </div>
                <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="merk" class="block font-medium text-gray-700 mb-1">Merk</label>
                    <x-input name="merk" />
                </div>

                <div>
                    <label for="model" class="block font-medium text-gray-700 mb-1">Model</label>
                    <x-input name="model" />
                </div>

                <div>
                    <label for="harga_jual" class="block font-medium text-gray-700 mb-1">Harga Jual</label>
                    <x-input type="number" name="harga_jual" />
                </div>

                <div>
                    <label for="harga_beli" class="block font-medium text-gray-700 mb-1">Harga Beli</label>
                    <x-input type="number" name="harga_beli" />
                </div>

                <div>
                    <label for="stok" class="block font-medium text-gray-700 mb-1">Stok</label>
                    <x-input type="number" name="stok" />
                </div>

                <div>
                    <label for="status" class="block font-medium text-gray-700 mb-1">Status Produk</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <x-input type="radio" name="status" value="baru" :checked="old('status') == 'baru'" />
                            <span class="ml-2">Baru</span>
                        </label>
                        <label class="flex items-center">
                            <x-input type="radio" name="status" value="bekas" :checked="old('status') == 'bekas'" />
                            <span class="ml-2">Bekas</span>
                        </label>
                    </div>
                </div>
                

                <div>
                    <label for="processor" class="block font-medium text-gray-700 mb-1">Processor</label>
                    <x-input name="processor" />
                </div>

                <div>
                    <label for="dimension" class="block font-medium text-gray-700 mb-1">Dimension</label>
                    <x-input name="dimension" />
                </div>

                <div>
                    <label for="ram" class="block font-medium text-gray-700 mb-1">RAM</label>
                    <x-input type="number" name="ram" />
                </div>

                <div>
                    <label for="storage" class="block font-medium text-gray-700 mb-1">Storage</label>
                    <x-input type="number" name="storage" />
                </div>

                <div>
                    <label for="warna" class="block font-medium text-gray-700 mb-1">Warna</label>
                    <x-input name="warna" />
                </div>
                </div>

                
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                    Tambah Produk
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('gambar').addEventListener('change', function (event) {
            const preview = document.getElementById('gambar-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
            }
        });
    </script>
</x-dashboard>
