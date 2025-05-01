<x-dashboard>
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Edit Produk</h1>

        <form action="{{ route('admin.ponsel.update', $ponsel->id_ponsel) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kolom Gambar --}}
                <div class="flex flex-col items-center">
                    {{-- Gambar Saat Ini --}}

                    {{-- Preview Gambar Baru --}}
                    <img id="gambar-preview" src="{{ asset($ponsel->gambar) }}" alt="Gambar Produk" class="mb-4 rounded-lg shadow h-64 w-64 object-contain" />

                    {{-- Input Gambar --}}
                    <label for="gambar" class="font-medium text-gray-700 mb-2">Gambar</label>
                    <input type="file" name="gambar" id="gambar"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700"
                        accept="image/*" />
                </div>

                {{-- Kolom Form --}}
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="merk" class="block font-medium text-gray-700 mb-1">Merk</label>
                        <x-input name="merk" :value="$ponsel->merk" />
                    </div>

                    <div>
                        <label for="model" class="block font-medium text-gray-700 mb-1">Model</label>
                        <x-input name="model" :value="$ponsel->model" />
                    </div>

                    <div>
                        <label for="harga_jual" class="block font-medium text-gray-700 mb-1">Harga Jual</label>
                        <x-input type="number" name="harga_jual" :value="$ponsel->harga_jual" />
                    </div>

                    <div>
                        <label for="harga_beli" class="block font-medium text-gray-700 mb-1">Harga Beli</label>
                        <x-input type="number" name="harga_beli" :value="$ponsel->harga_beli" />
                    </div>

                    <div>
                        <label for="stok" class="block font-medium text-gray-700 mb-1">Stok</label>
                        <x-input type="number" name="stok" :value="$ponsel->stok" />
                    </div>

                    <div>
                        <label for="status" class="block font-medium text-gray-700 mb-1">Status Produk</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="status" value="baru"
                                    {{ old('status', $ponsel->status) == 'baru' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600" />
                                <span class="ml-2">Baru</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="status" value="bekas"
                                    {{ old('status', $ponsel->status) == 'bekas' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600" />
                                <span class="ml-2">Bekas</span>
                            </label>
                        </div>
                    </div>
                               

                    <div>
                        <label for="processor" class="block font-medium text-gray-700 mb-1">Processor</label>
                        <x-input name="processor" :value="$ponsel->processor" />
                    </div>

                    <div>
                        <label for="dimension" class="block font-medium text-gray-700 mb-1">Dimension</label>
                        <x-input name="dimension" :value="$ponsel->dimension" />
                    </div>

                    <div>
                        <label for="ram" class="block font-medium text-gray-700 mb-1">RAM</label>
                        <x-input type="number" name="ram" :value="$ponsel->ram" />
                    </div>

                    <div>
                        <label for="storage" class="block font-medium text-gray-700 mb-1">Storage</label>
                        <x-input type="number" name="storage" :value="$ponsel->storage" />
                    </div>

                    <div>
                        <label for="warna" class="block font-medium text-gray-700 mb-1">Warna</label>
                        <x-input name="warna" :value="$ponsel->warna" />
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="mt-8">
                <button type="submit"
                        class="w-full h-12 bg-green-600 text-white text-lg font-semibold rounded-lg hover:bg-green-700 transition">
                    Simpan Perubahan
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
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-dashboard>
