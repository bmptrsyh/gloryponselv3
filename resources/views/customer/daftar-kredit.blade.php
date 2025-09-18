@extends('layouts.layout_home')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 mb-4">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Daftar Pengajuan Kredit Ponsel
    </h2>
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Ponsel</th>
                        <th class="px-4 py-3">Harga Ponsel</th>
                        <th class="px-4 py-3">Cicilan Per Bulan</th>
                        <th class="px-4 py-3">Tenor</th>
                        <th class="px-4 py-3">Total Bayar</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($kreditPonsel as $item)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center">
                                <div class="h-10 w-10 mr-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset($item->ponsel->gambar) }}" alt="{{ $item->merk }} {{ $item->model }}">
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $item->ponsel->merk }} {{ $item->ponsel->model }}</p>
                                    <p class="text-xs text-gray-600">{{ $item->ponsel->ram }}GB/{{ $item->ponsel->storage }}GB</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Rp {{ number_format($item->ponsel->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Rp {{ number_format($item->angsuran_per_bulan, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $item->tenor }} bulan
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Rp {{ number_format($item->jumlah_pinjaman, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($item->status == 'menunggu')
                                <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                    Menunggu
                                </span>
                            @elseif($item->status == 'disetujui')
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                    Disetujui
                                </span>
                            @elseif($item->status == 'ditolak')
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
                                <a href="{{ route('daftar.kredit.show', $item->id_kredit_ponsel) }}" class="text-blue-500 hover:text-blue-700">
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
@endsection
