<x-dashboard>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="text-gray-500">Total Transaksi</h4>
        <p class="text-2xl font-bold">10293</p>
        <p class="text-green-500 text-sm mt-1">⬆️ 1.3% Naik dari minggu lalu</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="text-gray-500">Total Pendapatan</h4>
        <p class="text-2xl font-bold">Rp 89,000,000</p>
        <p class="text-red-500 text-sm mt-1">⬇️ 4.3% Turun dari kemarin</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="text-gray-500">Transaksi Tertunda</h4>
        <p class="text-2xl font-bold">2040</p>
        <p class="text-green-500 text-sm mt-1">⬆️ 1.8% Naik dari kemarin</p>
      </div>
    </div>
  
    <!-- Grafik Penjualan -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
      <div class="flex justify-between items-center mb-4">
        <h4 class="text-lg font-semibold">Grafik Penjualan</h4>
        <select class="border border-gray-300 rounded px-2 py-1">
          @foreach($bulan as $item)
              <option {{ $item == $selectedMonth ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
      <div class="relative w-full overflow-x-auto">
        <canvas id="salesChart" height="100"></canvas>
      </div>
    </div>
  
    <!-- Daftar Transaksi Terkini -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-4">
        <h4 class="text-lg font-semibold">Daftar Transaksi Terkini</h4>
        <select class="border border-gray-300 rounded px-2 py-1">
          @foreach($bulan as $item)
              <option {{ $item == $selectedMonth ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b">
            <th class="pb-2">Nama Produk</th>
            <th>Lokasi</th>
            <th>Tanggal/Waktu</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b">
            <td class="py-2 flex items-center gap-2">
              <img src="https://cdn.eraspace.com/media/catalog/product/s/a/samsung-galaxy-s23-phantom-black_front_back_1_3_1_2.jpg" class="w-10 h-10 rounded" alt="Samsung Galaxy S23">
              Samsung Galaxy S23
            </td>
            <td>Glory Ponsel</td>
            <td>12.04.2025 - 12.53 PM</td>
            <td>1</td>
            <td>Rp 12,500,000</td>
            <td><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Selesai</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
    
        const rawData = [
            12000000, 19000000, 3000000, 5000000, 2000000, 3000000, 10000000, 15000000,
            8000000, 12000000, 20000000, 2000000, 14000000, 16000000, 13000000,
            11000000, 18000000, 2000000, 17000000, 500000000, 19000000, 2000000,
            5000000, 15000000, 13000000, 12000000, 10000000, 11000000, 14000000, 20000000
        ];
    
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({ length: rawData.length }, (_, i) => `Hari ${i + 1}`),
                datasets: [{
                    label: 'Penjualan Harian',
                    data: rawData,
                    fill: true,
                    tension: 0.4,
                    backgroundColor: gradient,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false 
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,     
                            drawBorder: false, 
                            color: '#e5e7eb'   
                        },
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                let val = context.raw;
                                return 'Rp ' + val.toLocaleString('id-ID');
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    
  </x-dashboard>
  