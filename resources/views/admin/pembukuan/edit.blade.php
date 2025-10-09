<x-dashboard>
    <div class="container px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Laporan Pembukuan</h2>

        <form action="{{ route('admin.pembukuan.update', $laporan->id_laporan) }}" method="POST" class="bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $laporan->tanggal) }}" class="border rounded px-3 py-2 w-full" max="{{ date('Y-m-d') }}">
                @error('tanggal') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" class="border rounded px-3 py-2 w-full">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Debit</label>
                    <input type="number" name="debit" value="{{ old('debit', $laporan->debit) }}" class="border rounded px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-gray-700">Kredit</label>
                    <input type="number" name="kredit" value="{{ old('kredit', $laporan->kredit) }}" class="border rounded px-3 py-2 w-full">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700">Metode Pembayaran</label>
                <input type="text" name="metode_pembayaran" value="{{ old('metode_pembayaran', $laporan->metode_pembayaran) ?? '-' }}" class="border rounded px-3 py-2 w-full">
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.pembukuan') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Batal</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</x-dashboard>
