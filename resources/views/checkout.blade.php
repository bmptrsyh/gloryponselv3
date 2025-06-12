@extends('layouts.layout_home')
@section('content')
  <div class="max-w-6xl mx-auto p-6 bg-white mt-6 rounded-lg shadow mb-6">

    <!-- Title -->
    <h2 class="text-2xl font-bold mb-6">Checkout</h2>

    <div class="flex flex-col lg:flex-row gap-6">

      <!-- Kiri: Detail Produk & Pengiriman -->
      <div class="flex-1 space-y-6">

        <!-- Alamat -->
        <div class="border rounded-lg p-4 bg-gray-50">
          <h3 class="font-semibold text-gray-800 mb-2">ALAMAT PENGIRIMAN</h3>
          <select id="provinsi" class="form-select border mb-2" style="width: 100%; height: 2em">
            <option value="" class="p-10" hidden>Pilih Provinsi</option>
          </select>
          <select id="kabupaten" class="form-select border mb-2" style="width: 100%; height: 2em">
            <option value="" class="p-10">Pilih Kabupaten/Kota</option>
          </select>
          <select id="kecamatan" class="form-select border mb-2" style="width: 100%; height: 2em">
            <option value="" class="p-10">Pilih Kecamatan</option>
          </select>
        </div>

        <!-- Produk -->
        @foreach ($ponsel as $index => $produk)
          <div class="border rounded-lg p-4">
            <div class="flex items-start space-x-4 border rounded-lg p-4 m-4">
              <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->merk }}" class="w-20 h-20 object-cover border rounded" />
              <div class="flex-1">
                <h4 class="font-semibold text-gray-800">{{ $produk->merk }} </h4>
                <p class="text-sm text-gray-600">{{ $produk->model }}</p>
                <div class="text-sm text-gray-500 jumlah-harga" data-harga="{{ $produk->harga_jual }}">
                  Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                </div>
                <div class="total-harga hidden"></div>
              </div>
              <div class="produk-item" data-stok="{{ $produk->stok }}">
                <div class="flex items-center">
                  <button type="button" class="minus-btn px-3 py-1 text-lg font-bold">-</button>
                  <span class="jumlah-span px-4">{{ $produk->jumlah }}</span>
                  <button type="button" class="plus-btn px-3 py-1 text-lg font-bold">+</button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Kanan: Metode Pembayaran & Ringkasan -->
      <div class="w-full lg:w-1/3 space-y-6">

        <!-- Metode Pembayaran -->
        <div class="border rounded-lg p-4 bg-white">
          <h3 class="font-semibold text-gray-800 mb-2">Metode Pembayaran</h3>
          <div class="space-y-2 text-sm">
            <select name="payment" id="payment" style="width: 100%">
              <option value="" hidden>Pilih Metode Pembayaran</option>
              @foreach ($paymentMethod as $method)
                <option value="{{ $method['code'] }}" data-image="{{ $method['image'] }}" data-fee="{{ $method['fee'] }}">
                  {{ $method['name'] }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="border rounded-lg p-4">
          <h3 class="font-semibold text-gray-800 mb-2">Jasa Pengiriman</h3>
          <!-- Pengiriman -->
          <div class="mt-4 space-y-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4" id="courier-group">
              <label class="cursor-pointer">
                <input type="radio" name="courier" value="jne" class="peer hidden">
                <div class="p-4 border rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center">
                  <span class="font-semibold">JNE</span>
                </div>
              </label>

              <label class="cursor-pointer">
                <input type="radio" name="courier" value="sicepat" class="peer hidden">
                <div class="p-4 border rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center">
                  <span class="font-semibold">Si Cepat</span>
                </div>
              </label>
            
              <label class="cursor-pointer">
                <input type="radio" name="courier" value="jnt" class="peer hidden">
                <div class="p-4 border rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center">
                  <span class="font-semibold">JNT</span>
                </div>
              </label>
            </div>

            <div id="ongkir-list" class="mt-4"></div>

          </div>
        </div>
        <!-- Ringkasan -->
        <div class="border rounded-lg p-4 bg-white space-y-2 text-sm text-gray-800">
          <hr />
          <div id="harga" class="flex justify-between font-semibold text-base">
            <span>Harga Produk</span>
            <span id="jumlah-harga">Rp {{ number_format($harga, 0, ',', '.') }}</span>
          </div>
          <div id="payment-fee" class="flex justify-between text-md">
            <span>Biaya tambahan:</span>
            <span id="fee-amount" data-fee="0">Rp 0</span>
          </div>
          <div id="biaya-ongkir" class="flex justify-between text-md">
            <span>Biaya Ongkir:</span>
            <span id="harga-ongkir" data-ongkir="0">Rp 0</span>
          </div>
          <div id="harga-total" class="flex justify-between font-bold text-lg">
            <span>Harga Total</span>
            <strong id="total-harga-akhir">Rp {{ number_format($harga, 0, ',', '.') }}</strong>
          </div>
         <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
            @csrf
            @foreach ($ponsel as $index => $produk)
                <input type="hidden" name="id_ponsel[]" value="{{ $produk->id_ponsel }}">
                <input type="hidden" name="jumlah[]" class="jumlah-input" value="{{ $produk->jumlah ?? 1 }}">
            @endforeach
            <input type="hidden" name="harga" id="harga-total-input">
            <input type="hidden" name="fee" id="fee-input">
            <input type="hidden" name="destination" id="destination-input">
            <input type="hidden" name="courier" id="courier-input">
            <input type="hidden" name="payment_method" id="input-payment-method">
            <input type="hidden" name="payment_method_name" id="payment-method-name">
            <input type="hidden" name="exp" value="120">

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold mt-2">
              Bayar Sekarang
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
  <script>
document.getElementById('checkout-form').addEventListener('submit', function (e) {
  // const selectedPayment = $('#payment').val();
  // const selectedText = $('#payment option:selected').text();
  const jumlahSpan = document.querySelector('.jumlah-span');
  const jumlah = parseInt(jumlahSpan?.innerText || 0);

  const jumlahInput = document.getElementById('jumlah-input');
  if (jumlahInput) {
    jumlahInput.value = jumlah;
  }

  // document.getElementById('input-payment-method').value = selectedPayment;
  // document.getElementById('payment-method-name').value = selectedText;
});
</script>
  <script>
function updateHargaJumlah(container) {
  const jumlahSpan = container.querySelector('.jumlah-span');
  const hargaElement = container.closest('.border').querySelector('.jumlah-harga');
  const totalElement = container.closest('.border').querySelector('.total-harga');
  
  const jumlah = parseInt(jumlahSpan.innerText);
  const hargaSatuan = parseInt(hargaElement.dataset.harga);
  const total = jumlah * hargaSatuan;
  
  totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
  updateTotalAkhir();
}

function updateTotalAkhir() {
  let totalHarga = 0;
  // Hitung total dari semua produk
  document.querySelectorAll('.total-harga').forEach(el => {
    const harga = parseInt(el.textContent.replace(/[^\d]/g, ''));
    totalHarga += harga;
  });

  // Tambahkan biaya tambahan
  const fee = parseInt(document.getElementById('fee-amount').dataset.fee || 0);
  const ongkir = parseInt(document.getElementById('harga-ongkir').dataset.ongkir || 0);
  
  const totalAkhir = totalHarga + fee + ongkir;
  
  // Update tampilan harga produk dan total akhir
  document.getElementById('jumlah-harga').textContent = `Rp ${totalHarga.toLocaleString('id-ID')}`;
  document.getElementById('total-harga-akhir').textContent = `Rp ${totalAkhir.toLocaleString('id-ID')}`;

  const inputHarga = document.getElementById('harga-total-input');
  if (inputHarga) {
    inputHarga.value = totalAkhir;
  }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const containers = document.querySelectorAll('.produk-item');

  containers.forEach((container, index) => {
    const minusBtn = container.querySelector('.minus-btn');
    const plusBtn = container.querySelector('.plus-btn');
    const jumlahSpan = container.querySelector('.jumlah-span');
    const jumlahInput = document.querySelectorAll('.jumlah-input')[index];
    const maxStok = parseInt(container.dataset.stok);
    let jumlah = parseInt(jumlahSpan.innerText);

    function updateJumlah() {
      jumlahSpan.innerText = jumlah;
      jumlahInput.value = jumlah; // ini penting!
      updateHargaJumlah(container);
    }

    minusBtn.addEventListener('click', () => {
      if (jumlah > 1) {
        jumlah--;
        updateJumlah();
      }
    });

    plusBtn.addEventListener('click', () => {
      if (jumlah < maxStok) {
        jumlah++;
        updateJumlah();
      }
    });

    // Initial set
    updateJumlah();
  });
});
</script>

<script>
$(document).ready(function () {
  // === SELECT2 Custom Payment ===
  function formatState(state) {
    if (!state.id) return state.text;
    const imageUrl = $(state.element).data('image');
    return imageUrl ? $(`<span style="display:flex;align-items:center;">
      <img src="${imageUrl}" style="width:25px;height:25px;object-fit:contain;margin-right:8px;" />
      ${state.text}</span>`) : state.text;
  }

  $('#payment').select2({
    templateResult: formatState,
    templateSelection: formatState,
    minimumResultsForSearch: -1
  });

$('#payment').on('change', function () {
    const selectedOption = $(this).find('option:selected');
    const fee = selectedOption.data('fee') || 0;
    const methodCode = selectedOption.val();
    const methodName = selectedOption.text();

    $('#fee-amount').text(`Rp ${parseInt(fee).toLocaleString('id-ID')}`).attr('data-fee', fee);

    $('#fee-input').val(fee);
    $('#input-payment-method').val(methodCode);
    $('#payment-method-name').val(methodName);

    updateTotalAkhir();
  });

  // === Provinsi Fetch ===
  function fetchProvinsi() {
    $.getJSON("https://api.binderbyte.com/wilayah/provinsi?api_key=7e003ef0524bc76e5b321cb421b242368bb8b4fe9e8172cc220eedb7b6b9e88f", res => {
      if (res.code === "200") {
        res.value.forEach(p => $('#provinsi').append(`<option value="${p.id}">${p.name}</option>`));
      } else {
        alert("Gagal mengambil data provinsi: " + res.messages);
      }
    }).fail(() => alert("Terjadi kesalahan saat menghubungi API provinsi."));
  }

  $('#provinsi').on('change', function () {
    const idProvinsi = $(this).val();
    $('#kabupaten').html('<option value="">Pilih Kabupaten/Kota</option>');
    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');

    $.getJSON(`https://api.binderbyte.com/wilayah/kabupaten?api_key=7e003ef0524bc76e5b321cb421b242368bb8b4fe9e8172cc220eedb7b6b9e88f&id_provinsi=${idProvinsi}`, res => {
      if (res.code === "200") {
        res.value.forEach(k => $('#kabupaten').append(`<option value="${k.id}">${k.name}</option>`));
      } else {
        alert("Gagal mengambil data kabupaten: " + res.messages);
      }
    }).fail(() => alert("Terjadi kesalahan saat menghubungi API kabupaten."));
  });

  $('#kabupaten').on('change', function () {
    const cleanName = $('#kabupaten option:selected').text().replace(/^(KAB\.|KOTA)\s+/i, '');
    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');

    $.getJSON(`/get-kecamatan/${cleanName}`, res => {
      if (res.meta?.status === "success") {
        res.data.forEach(kec => $('#kecamatan').append(`<option value="${kec.id}">${kec.subdistrict_name}</option>`));
      } else {
        alert("Data kecamatan tidak ditemukan.");
      }
    }).fail(() => alert("Terjadi kesalahan saat mengambil data kecamatan."));
  });

  // === Ongkir Fetch ===
  $('#kecamatan, input[name="courier"]').on('change', function () {
    const destination = $('#kecamatan').val();
    const courier = $('input[name="courier"]:checked').val() || $(this).val();
    if (!destination || !courier) return;

      $('#destination-input').val(destination);
      $('#courier-input').val(courier);

    $.ajax({
      url: `/get-ongkir`,
      method: "POST",
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      data: { destination, courier },
      dataType: "json",
      success: function (res) {
        if (res.meta?.status === "success") {
          const options = res.data.map(item => `
            <label class="cursor-pointer block">
              <input type="radio" name="courier_option" value="${item.service}" data-cost="${item.cost}" class="peer hidden">
              <div class="p-4 border rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50">
                <strong>${item.name} - ${item.service}</strong><br>
                <span>Biaya: Rp${item.cost.toLocaleString()}</span><br>
                <span>Estimasi: ${item.etd || '-'} hari</span>
              </div>
            </label>
          `).join('');
          $('#ongkir-list').html(options);

          $('input[name="courier_option"]').on('change', function () {
            const cost = $(this).data('cost');
            $('#harga-ongkir').text('Rp ' + cost.toLocaleString('id-ID')).attr('data-ongkir', cost);
            updateTotalAkhir();
          });
        } else {
          // $('#ongkir-list').html('<div class="text-red-500">Tidak Ada Kurir yang ditemukan</div>');
        }
      },
      error: function (xhr) {
        alert("Gagal mengambil ongkir: " + (xhr.responseJSON?.meta?.message || "Terjadi kesalahan."));
      }
    });
  });

  fetchProvinsi(); // Load provinsi saat halaman pertama kali dimuat
});
</script> 
@endsection
