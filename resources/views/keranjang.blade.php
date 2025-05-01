<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keranjang Belanja</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans">

  <!-- Tombol Kembali -->
  <div class="p-6">
    <button class="bg-indigo-600 text-white p-2 rounded-full">
      ⬅
    </button>
  </div>

  <!-- Judul -->
  <div class="text-center text-2xl font-bold text-blue-700 mb-4">
    Keranjang Anda
  </div>

  <!-- Pilih Semua + Garis -->
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex items-center mb-2">
      <input type="checkbox" class="mr-2 w-5 h-5">
      <span class="text-lg font-medium">Pilih semua</span>
    </div>
    <div class="border-t border-gray-300 mb-6"></div>
  </div>

  <!-- Konten Utama -->
  <div class="max-w-7xl mx-auto px-6 flex gap-8">

    <!-- Kiri: Daftar Produk -->
    <div class="flex-1 space-y-6">
        @foreach ($keranjang as $item)
      <!-- Satu item produk -->
      <div class="flex items-center gap-4 border-b pb-4">
        <input type="checkbox" class="w-5 h-5">
        <div class="w-24 h-24 bg-gray-100 p-2 rounded">
          <img src="{{$item['gambar'] }}" alt="iPhone" class="object-cover w-full h-full">
        </div>
        <div class="flex-1">
          <div class="font-bold">{{$item['nama']}}</div>
          <div class="text-sm text-gray-500">Color: black &nbsp; Varian: 256GB</div>
          <div class="text-lg font-semibold mt-1">RP {{ number_format($item['harga'], 0, ',', '.') }}</div>
          <div class="mt-2 flex items-center gap-2 text-sm">
            <span>Jumlah</span>
            <div class="flex items-center border rounded overflow-hidden">
              <button class="px-3 py-1 decrease">-</button>
              <span class="jumlah" id="jumlah">{{$item['jumlah']}}</span>
              {{-- <input type="text" value="1" class="w-10 text-center border-x jumlah" readonly> --}}
              <button class="px-3 py-1 increase">+</button>
            </div>
          </div>
        </div>
      </div>
      @endforeach
      <!-- Tambah item lainnya jika diperlukan -->
    </div>

    <!-- Kanan: Ringkasan Belanja -->
    <div class="w-full max-w-sm">
      <div class="border rounded-xl shadow-md p-4 space-y-4">
        <h2 class="font-bold text-lg">Ringkasan belanja</h2>
        <div class="flex justify-between text-sm">
          <span>Total</span>
          <span class="font-semibold">RP 14.999.999</span>
        </div>
        <input type="text" placeholder="Masukkan kode promo" class="w-full border rounded px-3 py-2 text-sm">
        <button class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Beli</button>
        <button class="w-full border py-2 rounded text-gray-500">Hapus</button>
      </div>
    </div>

  </div>

  <!-- Background Gelombang -->
  <div class="mt-20">
    <svg viewBox="0 0 1440 320" class="w-full">
      <path fill="#5D4ECB" fill-opacity="1" d="M0,288L40,272C80,256,160,224,240,197.3C320,171,400,149,480,138.7C560,128,640,128,720,160C800,192,880,256,960,261.3C1040,267,1120,213,1200,181.3C1280,149,1360,139,1400,133.3L1440,128L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
    </svg>
  </div>

  <!-- JavaScript -->
  <script>
    document.querySelectorAll('.increase').forEach(btn => {
      btn.addEventListener('click', function () {
        const jumlahSpan = this.parentElement.querySelector('.jumlah');
        let jumlah = parseInt(jumlahSpan.innerText);
        jumlah++;
        jumlahSpan.innerText = jumlah;
      });
    });
  
    document.querySelectorAll('.decrease').forEach(btn => {
      btn.addEventListener('click', function () {
        const jumlahSpan = this.parentElement.querySelector('.jumlah');
        let jumlah = parseInt(jumlahSpan.innerText);
        if (jumlah > 1) {
          jumlah--;
          jumlahSpan.innerText = jumlah;
        }
      });
    });
  </script>
  

</body>
</html>
