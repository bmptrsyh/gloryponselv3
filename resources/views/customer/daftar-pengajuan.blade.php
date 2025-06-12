@extends('layouts.layout_home')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 mb-4">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Daftar Pengajuan Jual Ponsel
    </h2>
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Ponsel</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($jualPonsel as $item)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center">
                                <div class="h-10 w-10 mr-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset($item->gambar) }}" alt="{{ $item->merk }} {{ $item->model }}">
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $item->merk }} {{ $item->model }}</p>
                                    <p class="text-xs text-gray-600">{{ $item->ram }}GB/{{ $item->storage }}GB</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
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
                                <a href="{{ route('pengajuan.jual.show', $item->id_jual_ponsel) }}" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                            Belum ada pengajuan jual ponsel.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-10 mb-4">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Daftar Pengajuan Tukar Tambah
    </h2>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Ponsel Kamu</th>
                        <th class="px-4 py-3">Ponsel Tujuan</th>
                        <th class="px-4 py-3">Estimasi Harga</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($tukarTambah as $item)
                    <tr class="text-gray-700">
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
                                <a href="{{ route('pengajuan.tukar.show', $item->id_tukar_tambah) }}" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
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




@endsection
