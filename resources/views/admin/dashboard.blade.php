<x-dashboard>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="text-gray-500">Total Transaksi</h4>
        <p class="text-2xl font-bold">{{ $totalTransaksi }}</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="text-gray-500">Total Pendapatan</h4>
        <p class="text-2xl font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="text-gray-500">Transaksi Tertunda</h4>
        <p class="text-2xl font-bold">{{ $transaksiTertunda }}</p>
      </div>
    </div>

    <!-- Grafik Penjualan -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
      <div class="flex justify-between items-center mb-4">
        <h4 class="text-lg font-semibold">Grafik Penjualan</h4>
        <form method="GET" action="{{ route('admin.dashboard') }}">
          <select name="bulan" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1">
            @foreach($bulan as $item)
                <option {{ $item == $selectedMonth ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
          </select>
        </form>
      </div>

      <div class="relative w-full overflow-x-auto">
        <canvas id="salesChart" height="100"></canvas>
      </div>
    </div>

    <!-- Daftar Transaksi Terkini -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-4">
        <h4 class="text-lg font-semibold">Daftar Transaksi Terkini</h4>
      </div>
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b">
            <th class="pb-2">Deskripsi</th>
            <th>Tanggal</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($transaksiTerkini as $t)
          <tr class="border-b">
            <td class="py-2">{{ $t->deskripsi }}</td>
            <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y H:i') }}</td>
            <td>Rp {{ number_format($t->debit, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($t->kredit, 0, ',', '.') }}</td>
            <td>
              <span class="px-2 py-1 text-xs rounded-full
                  {{ $t->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600' }}">
                  {{ ucfirst($t->status ?? 'selesai') }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center py-4 text-gray-400">Tidak ada transaksi</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const labels = @json($labels);
        const data = @json($values);

        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan Harian',
                    data: data,
                    fill: true,
                    tension: 0.4,
                    backgroundColor: gradient,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-dashboard>
