<x-dashboard>
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Daftar Pengajuan Tukar Tambah
    </h2>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Ponsel Customer</th>
                        <th class="px-4 py-3">Produk Tujuan</th>
                        <th class="px-4 py-3">Estimasi Harga</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($pengajuan as $item)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $item->customer->nama ?? 'Customer #'.$item->id_customer }}</p>
                                    <p class="text-xs text-gray-600">{{ $item->customer->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center">
                                <div class="h-10 w-10 mr-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset($item->gambar) }}" alt="{{ $item->merk }} {{ $item->model }}">
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $item->merk }} {{ $item->model }}</p>
                                    <p class="text-xs text-gray-600">{{ $item->ram }}GB/{{ $item->storage }}GB</p>
                                    <p class="text-xs text-gray-600">{{ $item->kondisi }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center">
                                <div class="h-10 w-10 mr-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset($item->produkTujuan->gambar) }}" alt="{{ $item->produkTujuan->merk }} {{ $item->produkTujuan->model }}">
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $item->produkTujuan->merk }} {{ $item->produkTujuan->model }}</p>
                                    <p class="text-xs text-gray-600">Rp {{ number_format($item->produkTujuan->harga_jual, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Rp {{ number_format($item->harga_estimasi, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($item->status == 'menunggu')
                                <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                    Menunggu
                                </span>
                            @elseif($item->status == 'di setujui')
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                    Disetujui
                                </span>
                            @elseif($item->status == 'di tolak')
                                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.tukar-tambah.show', $item->id_tukar_tambah) }}" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <button onclick="openStatusModal({{ $item->id_tukar_tambah }}, '{{ $item->status }}' )" class="text-purple-500 hover:text-purple-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.tukar-tambah.destroy', $item->id_tukar_tambah ) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                            Belum ada pengajuan tukar tambah.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <form id="statusForm" action="" method="POST">
            @csrf
            @method('PUT' )
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Update Status Tukar Tambah</h3>
                    <button type="button" onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="menunggu" class="form-radio h-5 w-5 text-purple-600">
                            <span class="ml-2 text-gray-700">Menunggu</span>
                        </label>
                        <br>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="di setujui" class="form-radio h-5 w-5 text-purple-600">
                            <span class="ml-2 text-gray-700">Disetujui</span>
                        </label>
                        <br>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="di tolak" class="form-radio h-5 w-5 text-purple-600">
                            <span class="ml-2 text-gray-700">Ditolak</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium mb-1">Catatan (Opsional)</label>
                    <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-purple-500 focus:border-purple-500"></textarea>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3 rounded-b-lg">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white rounded-lg text-sm hover:bg-purple-800">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const statusModal = document.getElementById('statusModal');
    const statusForm = document.getElementById('statusForm');
    
    function openStatusModal(id, currentStatus) {
        statusForm.action = `/admin/tukar-tambah/${id}/update-status`;
        
        // Set current status
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            if (radio.value === currentStatus) {
                radio.checked = true;
            }
        });
        
        statusModal.classList.remove('hidden');
    }
    
    function closeStatusModal() {
        statusModal.classList.add('hidden');
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === statusModal) {
            closeStatusModal();
        }
    });
</script>
</x-dashboard>

