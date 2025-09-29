<x-dashboard>
   <div class="p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 sm:gap-0">
         <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Detail Pengajuan Kredit Ponsel</h1>
         <div class="flex flex-col sm:flex-row gap-2 sm:space-x-2">
            <form action="{{ route('admin.kredit.updateStatus', $kredit->id_kredit_ponsel) }}" method="POST">
               @csrf
               @method('PUT')
               <input type="hidden" name="status" value="menunggu">
               <button type="submit"
                  class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-red-700 w-full sm:w-auto">
                  Menunggu
               </button>
            </form>
            <!-- Tombol Tolak -->
            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 w-full sm:w-auto"
               onclick="tolakPengajuan({{ $kredit->id_kredit_ponsel }})">
               Tolak Pengajuan
            </button>

            <!-- Form Hidden -->
            <form id="form-tolak-{{ $kredit->id_kredit_ponsel }}"
               action="{{ route('admin.kredit.updateStatus', $kredit->id_kredit_ponsel) }}" method="POST"
               class="hidden">
               @csrf
               @method('PUT')
               <input type="hidden" name="status" value="ditolak">
               <input type="hidden" name="alasan_ditolak" id="alasan-{{ $kredit->id_kredit_ponsel }}">
            </form>


            <!-- Setujui -->
            <form action="{{ route('admin.kredit.updateStatus', $kredit->id_kredit_ponsel) }}" method="POST">
               @csrf
               @method('PUT')
               <input type="hidden" name="status" value="disetujui">
               <button type="submit"
                  class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 w-full sm:w-auto">
                  Setujui Pengajuan
               </button>
            </form>
         </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
         <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-start justify-between mb-2">
               <h2 class="font-semibold text-lg">Status Pengajuan</h2>
               <div class="text-right">
                  <p class="text-xs text-gray-400">Tanggal Pengajuan</p>
                  <p class="text-sm text-gray-600">{{ $kredit->created_at->format('d M Y') }}</p>
               </div>
            </div>
            <div class="flex items-start justify-between mb-2">
               @if ($kredit->status == 'menunggu')
                  <span class="inline-block px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 mb-2">
                     Menunggu
                  </span>
               @elseif($kredit->status == 'disetujui')
                  <span class="inline-block px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 mb-2">
                     Disetujui
                  </span>
               @elseif($kredit->status == 'ditolak')
                  <span class="inline-block px-3 py-1 text-sm rounded-full bg-red-100 text-red-700 mb-2">
                     Ditolak
                  </span>
               @endif
               @if ($jatuhTempo)
                  <div class="text-right">
                     <p class="text-xs text-gray-400">Jatuh Tempo Pertama</p>
                     <p class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($jatuhTempo)->format('d M Y') }}
                     </p>
                  </div>
               @endif
            </div>
            <p class="text-gray-600">Harga Pengajuan</p>
            <p class="text-2xl font-bold text-purple-700">Rp {{ number_format($kredit->jumlah_pinjaman, 0, ',', '.') }}
            </p>
         </div>
         <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-start justify-between mb-2">
               <h2 class="text-base font-semibold text-bold mb-3">Informasi Cicilan</h2>
               <div class="text-right ">
                  <a href="{{ route('admin.cicilan.show', $kredit->id_kredit_ponsel) }}"
                     class="bg-blue-600 text-white border border-purple-700 px-5 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50 inline-block text-center {{ $produk->stok <= 0 ? 'pointer-events-none opacity-50' : '' }}">
                     Detail Cicilan
                  </a>
               </div>
            </div>

            <div class="grid grid-cols-2 gap-y-3 text-sm font-semibold">
               <div>
                  <p class="text-gray-600">Harga Ponsel</p>
                  <p class="font-semibold">Rp {{ number_format($kredit->ponsel->harga_jual, 0, ',', '.') }}</p>
               </div>
               <div>
                  <p class="text-gray-600">Jangka Waktu</p>
                  <p class="font-semibold">{{ $kredit->tenor }} bulan</p>
               </div>
               <div>
                  <p class="text-gray-600">Cicilan Per Bulan</p>
                  <p class="text-blue-600">Rp {{ number_format($kredit->angsuran_per_bulan, 0, ',', '.') }}</p>
               </div>
               <div>
                  <p class="text-gray-600">Uang Muka</p>
                  <p class="text-red-600">Rp {{ number_format($kredit->jumlah_DP, 0, ',', '.') }}</p>
               </div>
            </div>
         </div>
      </div>
      <!-- Data Pribadi -->
      <div class="p-4 bg-white rounded-lg shadow mb-6">
         <h2 class="text-base font-semibold text-bold mb-3">Data Pribadi</h2>
         <div class="grid grid-cols-3 gap-y-4 gap-x-8 text-sm">
            <div>
               <p class="font-semibold text-gray-600">Nama Lengkap</p>
               <p class="font-medium">{{ $kredit->nama_lengkap }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Tempat Lahir</p>
               <p class="font-medium">{{ $kredit->tempat_lahir }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Tanggal Lahir</p>
               <p class="font-medium">{{ \Carbon\Carbon::parse($kredit->tanggal_lahir)->format('d M Y') }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Jenis Kelamin</p>
               <p class="font-medium">{{ $kredit->jenis_kelamin }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Status Pernikahan</p>
               <p class="font-medium">{{ $kredit->status_pernikahan }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">NIK</p>
               <p class="font-medium">{{ $kredit->NIK }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Alamat KTP</p>
               <p class="font-medium">{{ $kredit->alamat_ktp }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Alamat Domisili</p>
               <p class="font-medium">{{ $kredit->alamat_domisili }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">No. Telepon</p>
               <p class="font-medium">{{ $kredit->no_telepon }}</p>
            </div>
         </div>
      </div>
      <!-- Data Pekerjaan -->
      <div class="p-4 bg-white rounded-lg shadow mb-6">
         <h2 class="text-base font-semibold text-bold mb-3">Data Pekerjaan dan Penghasilan</h2>
         <div class="grid grid-cols-3 gap-y-4 gap-x-8 text-sm">
            <div>
               <p class="font-semibold text-gray-600">Pekerjaan</p>
               <p class="font-medium">{{ $kredit->pekerjaan }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Nama Perusahaan</p>
               <p class="font-medium">{{ $kredit->nama_perusahaan }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Alamat Perusahaan</p>
               <p class="font-medium">{{ $kredit->alamat_perusahaan }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Lama Bekerja</p>
               <p class="font-medium">{{ $kredit->lama_bekerja }} Tahun</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Penghasilan Bulanan</p>
               <p class="font-medium">Rp {{ number_format($kredit->penghasilan_per_bulan, 0, ',', '.') }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Penghasilan Lainnya</p>
               <p class="font-medium">Rp {{ number_format($kredit->penghasilan_lainnya, 0, ',', '.') }}</p>
            </div>
         </div>
      </div>
      <!-- Spesifikasi Ponsel -->
      <div class="p-4 bg-white rounded-lg shadow mb-6">
         <h2 class="text-base font-semibold text-bold mb-3">Spesifikasi Ponsel</h2>
         <div class="grid grid-cols-3 gap-y-4 gap-x-8 text-sm">
            <div>
               <p class="font-semibold text-gray-600">Merk</p>
               <p class="font-medium">{{ $kredit->ponsel->merk }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Model</p>
               <p class="font-medium"> {{ $kredit->ponsel->model }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">RAM</p>
               <p class="font-medium">{{ $kredit->ponsel->ram }} GB</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Storage</p>
               <p class="font-medium">{{ $kredit->ponsel->storage }} GB</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Kondisi</p>
               <p class="font-medium">{{ $kredit->ponsel->status }}</p>
            </div>
            <div>
               <p class="font-semibold text-gray-600">Processor</p>
               <p class="font-medium">{{ $kredit->ponsel->processor }}</p>
            </div>
         </div>
      </div>
      <!-- Dokumen -->
      <div class="p-4 bg-white rounded-lg shadow">
         <h2 class="font-semibold text-lg mb-4">Dokumen</h2>
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Foto KTP Asli -->
            <div class="border rounded-lg p-4 flex flex-col items-center justify-center text-gray-700">
               <p class="font-medium mb-2">Foto KTP Asli</p>
               <img src="{{ asset('storage/' . $kredit->gambar_ktp) }}" alt="Foto KTP Asli"
                  class="rounded-lg shadow max-w-full h-auto">
            </div>

            <!-- Foto Selfie dengan KTP -->
            <div class="border rounded-lg p-4 flex flex-col items-center justify-center text-gray-700">
               <p class="font-medium mb-2">Foto Selfie dengan KTP</p>
               <img src="{{ asset('storage/' . $kredit->gambar_selfie) }}" alt="Foto Selfie dengan KTP"
                  class="rounded-lg shadow max-w-full h-auto">
            </div>

         </div>
      </div>

   </div>
   <script>
      function tolakPengajuan(id) {
         Swal.fire({
            title: 'Tolak Pengajuan',
            input: 'textarea',
            inputLabel: 'Alasan Penolakan',
            inputPlaceholder: 'Tulis alasan kenapa pengajuan ini ditolak...',
            inputAttributes: {
               'aria-label': 'Tulis alasan penolakan di sini'
            },
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            preConfirm: (alasan) => {
               if (!alasan) {
                  Swal.showValidationMessage('Alasan penolakan wajib diisi!')
               }
               return alasan;
            }
         }).then((result) => {
            if (result.isConfirmed) {
               document.getElementById('alasan-' + id).value = result.value;
               document.getElementById('form-tolak-' + id).submit();
            }
         });
      }
   </script>

</x-dashboard>
