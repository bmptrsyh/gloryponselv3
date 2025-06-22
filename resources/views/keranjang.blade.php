<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keranjang Belanja</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white font-sans">
  @if (session('error'))
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

  <!-- Tombol Kembali -->
  <div class="p-6">
    <a href="{{ route('home') }}" class="bg-indigo-600 text-white p-2 rounded-full inline-block">
      ⬅
    </a>
  </div>

  <!-- Judul -->
  <div class="text-center text-2xl font-bold text-blue-700 mb-4">
    Keranjang Anda
  </div>

  <!-- Tab Navigation -->
  <div class="max-w-7xl mx-auto px-6 mb-6">
    <div class="flex border-b">
      <button class="tab-button flex-1 py-3 text-center text-lg font-semibold text-blue-600 border-b-2 border-blue-600" data-tab="cart">
        Keranjang
      </button>
      <button class="tab-button flex-1 py-3 text-center text-lg font-semibold text-gray-600" data-tab="orders">
        Pemesanan
      </button>
      <button class="tab-button flex-1 py-3 text-center text-lg font-semibold text-gray-600" data-tab="reviews">
        Untuk Diulas
      </button>
    </div>
  </div>

  <!-- Tab Content: Keranjang -->
  <div id="cart-tab" class="tab-content">
    <!-- Pilih Semua + Garis -->
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex items-center mb-2">
        <input type="checkbox" class="mr-2 w-5 h-5" id="select-all">
        <span class="text-lg font-medium">Pilih semua</span>
      </div>
      <div class="border-t border-gray-300 mb-6"></div>
    </div>

    <!-- Konten Utama -->
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row gap-8">

      <!-- Kiri: Daftar Produk -->
      <div class="flex-1 space-y-6">
          @foreach ($keranjang as $item)
<div class="flex items-center gap-4 border-b pb-4">
  <input 
    type="checkbox" 
    class="w-5 h-5 product-checkbox" 
    data-harga="{{ $item['harga'] }}" 
    data-id="{{ $item['produk_id'] }}"
  >
  <div class="w-24 h-24 bg-gray-100 p-2 rounded">
    <img src="{{ $item['gambar'] }}" alt="iPhone" class="object-cover w-full h-full">
  </div>
  <div class="flex-1">
    <div class="font-bold">{{ $item['nama'] }}</div>
    <div class="text-sm text-gray-500">Warna : {{ $item['warna'] }} &nbsp; Varian :{{ $item['storage'] }} GB / {{ $item['ram'] }} GB</div>
    <div class="text-lg font-semibold mt-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
    <div class="mt-2 flex items-center gap-2 text-sm">
      <span>Jumlah</span>
      <div class="flex items-center border rounded overflow-hidden">
        <button class="px-3 py-1 decrease">-</button>
        <span class="jumlah" id="jumlah-{{ $item['produk_id'] }}">{{ $item['jumlah'] }}</span>
        <button class="px-3 py-1 increase">+</button>
      </div>
    </div>
  </div>
</div>
@endforeach

      </div>

      <!-- Kanan: Ringkasan Belanja -->
      <div class="w-full md:max-w-sm">
        <div class="border rounded-xl shadow-md p-4 space-y-4">
          <h2 class="font-bold text-lg">Ringkasan belanja</h2>
          <div class="flex justify-between text-sm">
            <span>Total</span>
            <span class="font-semibold" id="total-price">Rp 0</span>
          </div>
          <form id="beli-form" action="{{ route('beli.ponsel') }}" method="POST">
            @csrf
          <input type="hidden" name="produk" id="produk-input">
          <button class="w-full bg-blue-600 text-white py-2 rounded font-semibold" id="checkout-btn">Beli</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Content: Pemesanan -->
<div id="orders-tab" class="tab-content hidden">
  <div class="max-w-7xl mx-auto px-6">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">

      <!-- Filter Status -->
      <div class="flex border-b">
        <button class="order-filter flex-1 py-2 text-center text-sm font-semibold text-blue-600 border-b-2 border-blue-600" data-filter="all">
          Semua
        </button>
        <button class="order-filter flex-1 py-2 text-center text-sm font-semibold text-gray-600" data-filter="pending">
          Menunggu Pembayaran
        </button>
        <button class="order-filter flex-1 py-2 text-center text-sm font-semibold text-gray-600" data-filter="processing">
          Diproses
        </button>
        <button class="order-filter flex-1 py-2 text-center text-sm font-semibold text-gray-600" data-filter="shipped">
          Dikirim
        </button>
        <button class="order-filter flex-1 py-2 text-center text-sm font-semibold text-gray-600" data-filter="completed">
          Selesai
        </button>
      </div>

      <!-- Daftar Pesanan -->
      <div class="p-4 space-y-4" id="order-list">
        @forelse ($beliponsel as $ponsel)
          @php
            if ($ponsel->status == 'tertunda' && $ponsel->status_pengiriman == 'belum_dikirim') {
                $status = 'pending';
            } elseif ($ponsel->status == 'selesai' && $ponsel->status_pengiriman == 'belum_dikirim') {
                $status = 'processing';
            } elseif ($ponsel->status == 'selesai' && $ponsel->status_pengiriman == 'dikirim') {
                $status = 'shipped';
            } elseif ($ponsel->status == 'selesai' && $ponsel->status_pengiriman == 'selesai') {
                $status = 'completed';
            } else {
                $status = 'other';
            }
          @endphp

          <div class="border rounded-lg p-4 order-item" data-status="{{ $status }}">
            <div class="flex justify-between items-center mb-2">
              <div class="text-sm text-gray-500">{{ $ponsel->code }}</div>
              <div class="text-sm font-semibold 
                  {{ $status == 'pending' ? 'text-yellow-600' : ($status == 'shipped' ? 'text-blue-600' : ($status == 'completed' ? 'text-green-600' : 'text-gray-600')) }}">
                  {{ $ponsel->status_pengiriman ?? $ponsel->status }}
              </div>
            </div>
            <div class="flex items-start gap-4 mb-3">
              <img src="{{ $ponsel->ponsel->gambar }}" alt="{{ $ponsel->ponsel->merk }} {{ $ponsel->ponsel->model }}" class="w-16 h-16 object-cover rounded">
              <div>
                <div class="font-semibold">{{ $ponsel->ponsel->merk }} {{ $ponsel->ponsel->model }}</div>
                <div class="text-sm text-gray-500">Rp {{ number_format($ponsel->ponsel->harga_jual, 0, ',', '.') }}</div>
              </div>
            </div>
            <div class="flex justify-between items-center border-t pt-3">
              <div class="text-sm">Total: <span class="font-semibold">Rp {{ number_format($ponsel->ponsel->harga_jual, 0, ',', '.') }}</span></div>
              <div class="flex gap-2">
                @if ($status == 'pending')
                  <button class="bg-blue-600 text-white px-4 py-1 rounded text-sm">Bayar Sekarang</button>
                @elseif ($status == 'shipped')
                <form action="{{ route('ubah.status', $ponsel->id_beli_ponsel ) }}" method="POST">
                  @csrf
                  <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded text-sm">Tandai Sudah Selesai</button>
                </form>
                @elseif ($status == 'completed')
                  <button class="bg-gray-200 text-gray-800 px-4 py-1 rounded text-sm">Beli Lagi</button>
                  <button class="bg-blue-600 text-white px-4 py-1 rounded text-sm">Ulas</button>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-8 text-gray-500" id="no-orders">
            <i class="fas fa-shopping-bag text-4xl mb-2"></i>
            <p>Belum ada pesanan aktif</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>


  <!-- Tab Content: Untuk Diulas -->
            <div id="reviews-tab" class="tab-content hidden">
              <div class="max-w-7xl mx-auto px-6">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                  <!-- Daftar Produk untuk Diulas -->
                  <div class="p-4 space-y-4">
                    <!-- Produk 1 -->
                    @forelse ($beliponsel->where('status_pengiriman', 'selesai') as $ponsel)
            <div class="border rounded-lg p-4">
              <div class="flex items-start gap-4 mb-3">
                <img src="{{ $ponsel->ponsel->gambar }}" alt="{{ $ponsel->ponsel->merk }} {{ $ponsel->ponsel->model }}" class="w-16 h-16 object-cover rounded">
                <div class="flex-1">
                  <div class="font-semibold">{{ $ponsel->ponsel->merk }} {{ $ponsel->ponsel->model }}</div>
                  <div class="text-sm text-gray-500">Pesanan selesai pada {{ \Carbon\Carbon::parse($ponsel->updated_at)->translatedFormat('d F Y') }}</div>
                </div>
              
                @if (!$ponsel->ulasan)
                  <button 
                    class="bg-blue-600 text-white px-4 py-1 rounded text-sm review-btn"
                    data-id-beli="{{ $ponsel->id_beli_ponsel }}"
                    data-id-ponsel="{{ $ponsel->id_ponsel }}"
                    data-nama="{{ $ponsel->ponsel->merk }} {{ $ponsel->ponsel->model }}"
                    data-gambar="{{ $ponsel->ponsel->gambar }}"
                  >
                    Ulas
                  </button>
                @else
                  <button 
            class="bg-gray-200 text-green-700 px-4 py-1 rounded text-sm view-review-btn"
            data-id-beli="{{ $ponsel->id_beli_ponsel }}"
            data-id-ponsel="{{ $ponsel->id_ponsel }}"
            data-nama="{{ $ponsel->ponsel->merk }} {{ $ponsel->ponsel->model }}"
            data-gambar="{{ $ponsel->ponsel->gambar }}"
            data-ulasan="{{ $ponsel->ulasan->ulasan }}"
            data-rating="{{ $ponsel->ulasan->rating }}"
          >
            Sudah Diulas
          </button>

                @endif

              </div>
            </div>
          @empty
            <div class="text-center py-8 text-gray-500" id="no-reviews">
              <i class="fas fa-star text-4xl mb-2"></i>
              <p>Belum ada produk yang perlu diulas</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Ulasan -->
  <div id="review-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Berikan Ulasan</h3>
        <button class="text-gray-500 hover:text-gray-700" id="close-review-modal">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form method="POST" action="{{ route('ulasan.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_beli_ponsel" id="review_id_beli">
        <input type="hidden" name="id_ponsel" id="review_id_ponsel">
        <input type="hidden" name="rating" id="rating-value">
      <div class="mb-4">
        <div class="flex items-center gap-4 mb-3">
          <img src="https://via.placeholder.com/80" alt="Product" class="w-16 h-16 object-cover rounded" id="review-product-image">
          <div>
            <div class="font-semibold" id="review-product-name">Nama Produk</div>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Rating</label>
          <div class="flex text-2xl text-gray-300 rating-stars">
            <i class="fas fa-star cursor-pointer" data-rating="1"></i>
            <i class="fas fa-star cursor-pointer" data-rating="2"></i>
            <i class="fas fa-star cursor-pointer" data-rating="3"></i>
            <i class="fas fa-star cursor-pointer" data-rating="4"></i>
            <i class="fas fa-star cursor-pointer" data-rating="5"></i>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Ulasan</label>
          <textarea name="ulasan" id="ulasan-textarea" class="w-full border rounded-lg p-2 h-32" placeholder="Bagikan pengalaman Anda menggunakan produk ini..."></textarea>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Unggah Foto (opsional)</label>
          <div class="border-dashed border-2 border-gray-300 rounded-lg p-4 text-center">
            <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
            <p class="text-sm text-gray-500">Klik untuk mengunggah foto</p>
            <input type="file" class="hidden" accept="image/*" multiple>
          </div>
        </div>
        <button type="submit" id="submit-review-btn" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Kirim Ulasan</button>
        </form>
      </div>
    </div>
  </div>
  <script>
  document.querySelectorAll('.order-filter').forEach(button => {
    button.addEventListener('click', () => {
      const filter = button.getAttribute('data-filter');
      document.querySelectorAll('.order-filter').forEach(btn => {
        if (btn) {
          btn.classList.remove('text-blue-600', 'border-blue-600');
          btn.classList.add('text-gray-600');
        }
      });
      if (button) {
        button.classList.add('text-blue-600', 'border-blue-600');
        button.classList.remove('text-gray-600');
      }

      const items = document.querySelectorAll('.order-item');
      let anyVisible = false;

      items.forEach(item => {
        if (item && (filter === 'all' || item.getAttribute('data-status') === filter)) {
          item.classList.remove('hidden');
          anyVisible = true;
        } else if (item) {
          item.classList.add('hidden');
        }
      });

      const noOrdersElement = document.getElementById('no-orders');
      if (noOrdersElement) {
        noOrdersElement.classList.toggle('hidden', anyVisible);
      }
    });
  });
</script>


  <!-- JavaScript -->
 <script>
document.addEventListener('DOMContentLoaded', () => {
  // ==== Helper Functions ====
  function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount);
  }

  function updateTotal() {
    let total = 0;
    document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
      const harga = parseInt(checkbox.dataset.harga);
      const id = checkbox.dataset.id;
      const jumlahElement = document.getElementById('jumlah-' + id);
      if (jumlahElement) {
        const jumlah = parseInt(jumlahElement.innerText);
        total += harga * jumlah;
      }
    });
    const totalPriceElement = document.getElementById('total-price');
    if (totalPriceElement) {
      totalPriceElement.textContent = formatRupiah(total);
    }
  }

  function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('select-all');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    if (selectAllCheckbox && productCheckboxes.length > 0) {
      const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
      selectAllCheckbox.checked = allChecked;
    }
  }

  // ==== Checkout Logic ====
  const form = document.getElementById('beli-form');
  const checkoutBtn = document.getElementById('checkout-btn');
  const produkInput = document.getElementById('produk-input');
  const selectAllCheckbox = document.getElementById('select-all');
  const productCheckboxes = document.querySelectorAll('.product-checkbox');

  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', () => {
      productCheckboxes.forEach(cb => {
        if (cb) {
          cb.checked = selectAllCheckbox.checked;
        }
      });
      updateTotal();
    });
  }

  productCheckboxes.forEach(cb => {
    if (cb) {
      cb.addEventListener('change', () => {
        updateSelectAllState();
        updateTotal();
      });
    }
  });

  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', () => {
      const selectedItems = Array.from(productCheckboxes)
        .filter(cb => cb && cb.checked)
        .map(cb => {
          const jumlahElement = document.getElementById('jumlah-' + cb.dataset.id);
          return {
            id: cb.dataset.id,
            jumlah: jumlahElement ? jumlahElement.innerText : '1'
          };
        });

      if (selectedItems.length === 0) {
        alert('Pilih minimal 1 produk');
        return;
      }

      if (produkInput && form) {
        produkInput.value = JSON.stringify(selectedItems);
        form.submit();
      }
    });
  }

  // ==== Quantity Buttons (+/-) ====
  document.querySelectorAll('.increase').forEach(btn => {
    if (btn) {
      btn.addEventListener('click', () => {
        const jumlahSpan = btn.parentElement.querySelector('.jumlah');
        if (jumlahSpan) {
          jumlahSpan.innerText = parseInt(jumlahSpan.innerText) + 1;
          updateTotal();
        }
      });
    }
  });

  document.querySelectorAll('.decrease').forEach(btn => {
    if (btn) {
      btn.addEventListener('click', () => {
        const jumlahSpan = btn.parentElement.querySelector('.jumlah');
        if (jumlahSpan) {
          let jumlah = parseInt(jumlahSpan.innerText);
          if (jumlah > 1) {
            jumlahSpan.innerText = jumlah - 1;
            updateTotal();
          }
        }
      });
    }
  });

  // ==== Tab Switching ====
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');

  tabButtons.forEach(button => {
    if (button) {
      button.addEventListener('click', () => {
        tabButtons.forEach(btn => {
          if (btn) {
            btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            btn.classList.add('text-gray-600');
          }
        });
        
        if (button) {
          button.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
          button.classList.remove('text-gray-600');
        }

        tabContents.forEach(content => {
          if (content) {
            content.classList.add('hidden');
          }
        });
        
        const targetTab = document.getElementById(button.dataset.tab + '-tab');
        if (targetTab) {
          targetTab.classList.remove('hidden');
        }
      });
    }
  });

  // ==== Order Filter ====
  const orderFilters = document.querySelectorAll('.order-filter');
  const orderItems = document.querySelectorAll('.order-item');
  const noOrders = document.getElementById('no-orders');

  orderFilters.forEach(filter => {
    if (filter) {
      filter.addEventListener('click', () => {
        orderFilters.forEach(f => {
          if (f) {
            f.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            f.classList.add('text-gray-600');
          }
        });
        
        if (filter) {
          filter.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
          filter.classList.remove('text-gray-600');
        }

        const filterValue = filter.dataset.filter;
        let visibleCount = 0;

        orderItems.forEach(item => {
          if (item) {
            const match = filterValue === 'all' || item.dataset.status === filterValue;
            item.classList.toggle('hidden', !match);
            if (match) visibleCount++;
          }
        });

        if (noOrders) {
          noOrders.classList.toggle('hidden', visibleCount > 0);
        }
      });
    }
  });

  // ==== Review Modal Logic ====
  const reviewModal = document.getElementById('review-modal');
  const closeReviewModal = document.getElementById('close-review-modal');

  // Review button click handlers
  document.querySelectorAll('.review-btn').forEach(button => {
    if (button) {
      button.addEventListener('click', function () {
        const idBeli = this.dataset.idBeli;
        const idPonsel = this.dataset.idPonsel;
        const nama = this.dataset.nama;
        const gambar = this.dataset.gambar;

        const reviewIdBeli = document.getElementById('review_id_beli');
        const reviewIdPonsel = document.getElementById('review_id_ponsel');
        const reviewProductName = document.getElementById('review-product-name');
        const reviewProductImage = document.getElementById('review-product-image');

        if (reviewIdBeli) reviewIdBeli.value = idBeli;
        if (reviewIdPonsel) reviewIdPonsel.value = idPonsel;
        if (reviewProductName) reviewProductName.textContent = nama;
        if (reviewProductImage) reviewProductImage.src = gambar;

        if (reviewModal) {
          reviewModal.classList.remove('hidden');
        }
      });
    }
  });

  // View review button click handlers
  document.querySelectorAll('.view-review-btn').forEach(button => {
    if (button) {
      button.addEventListener('click', function () {
        const nama = this.dataset.nama;
        const gambar = this.dataset.gambar;
        const ulasan = this.dataset.ulasan;
        const rating = this.dataset.rating;

        const reviewProductName = document.getElementById('review-product-name');
        const reviewProductImage = document.getElementById('review-product-image');
        
        if (reviewProductName) reviewProductName.textContent = nama;
        if (reviewProductImage) reviewProductImage.src = gambar;

        // Isi dan kunci textarea ulasan
        const ulasanTextarea = document.getElementById('ulasan-textarea');
        if (ulasanTextarea) {
          ulasanTextarea.value = ulasan;
          ulasanTextarea.setAttribute('readonly', true);
        }

        // Tampilkan rating bintang tapi tidak bisa diubah
        const stars = document.querySelectorAll('.rating-stars i');
        stars.forEach(star => {
          if (star) { // Tambahkan null check
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= rating) {
              star.classList.add('text-yellow-400');
              star.classList.remove('text-gray-300');
            } else {
              star.classList.remove('text-yellow-400');
              star.classList.add('text-gray-300');
            }
            star.classList.remove('cursor-pointer');
          }
        });

        // Nonaktifkan tombol kirim
        const submitBtn = document.getElementById('submit-review-btn');
        if (submitBtn) {
          submitBtn.classList.add('hidden');
        }

        if (reviewModal) {
          reviewModal.classList.remove('hidden');
        }
      });
    }
  });

  // Close modal handler
  if (closeReviewModal) {
    closeReviewModal.addEventListener('click', function () {
      if (reviewModal) {
        reviewModal.classList.add('hidden');
      }

      // Reset semua input
      const ulasanTextarea = document.getElementById('ulasan-textarea');
      if (ulasanTextarea) {
        ulasanTextarea.value = '';
        ulasanTextarea.removeAttribute('readonly');
      }

      // Reset bintang rating
      const stars = document.querySelectorAll('.rating-stars i');
      stars.forEach(star => {
        if (star) {
          star.classList.remove('text-yellow-400');
          star.classList.add('text-gray-300', 'cursor-pointer');
        }
      });

      // Tampilkan kembali tombol kirim
      const submitBtn = document.getElementById('submit-review-btn');
      if (submitBtn) {
        submitBtn.classList.remove('hidden');
      }
    });
  }

  // Rating stars click handlers
  const ratingStars = document.querySelectorAll('.rating-stars i');
  const ratingValue = document.getElementById('rating-value');

  ratingStars.forEach(star => {
    if (star) {
      star.addEventListener('click', function () {
        const rating = parseInt(this.dataset.rating);
        if (ratingValue) {
          ratingValue.value = rating;
        }

        ratingStars.forEach((s, index) => {
          if (s) {
            if (index < rating) {
              s.classList.add('text-yellow-400');
              s.classList.remove('text-gray-300');
            } else {
              s.classList.remove('text-yellow-400');
              s.classList.add('text-gray-300');
            }
          }
        });
      });
    }
  });
});
</script>

</body>
</html>

