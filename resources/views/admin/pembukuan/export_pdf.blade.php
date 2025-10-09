<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="utf-8">
   <title>Laporan Pembukuan</title>
   <script src="https://cdn.tailwindcss.com"></script>
   <style>
      /* Fallback untuk DomPDF */
      table {
         border-collapse: collapse;
         width: 100%;
      }

      th,
      td {
         border: 1px solid #000;
         padding: 8px 6px;
      }

      th {
         background: #f3f4f6;
      }

      tr:nth-child(even) td {
         background: #f9fafb;
      }

      .wrap-text {
         word-break: break-word;
         white-space: pre-line;
      }
   </style>
</head>

<body class="font-sans text-sm m-8">

   <!-- Header -->
   <div class="mb-6" style="text-align: center;">
      <h2 class="font-bold text-xl" style="margin-bottom: 4px;">Laporan Pembukuan</h2>
      <h4 class="text-gray-600">Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F') }}
         {{ $tahun }}</h4>
   </div>


   <!-- Tabel -->
   <div class="flex justify-center">
      <table class="w-11/12">
         <thead>
            <tr>
               <th class="text-center w-12">ID</th>
               <th class="text-center w-24">Tanggal</th>
               <th class="text-center w-28">Deskripsi</th>
               <th class="text-center w-28">Debit</th>
               <th class="text-center w-28">Kredit</th>
               <th class="text-center w-28">Saldo</th>
               <th class="text-center w-32">Metode</th>
            </tr>
         </thead>
         <tbody>
            @php
               $totalDebit = 0;
               $totalKredit = 0;
               $totalSaldo = 0;
            @endphp

            @forelse($laporan as $row)
               @php
                  $totalDebit += $row->debit;
                  $totalKredit += $row->kredit;
               @endphp
               <tr>
                  <td class="text-center align-top">{{ $row->id_laporan }}</td>
                  <td class="text-center align-top">
                     {{ \Carbon\Carbon::parse($row->tanggal)->format('d F Y') }}
                  </td>
                  <td class="wrap-text align-top">{{ $row->deskripsi }}</td>
                  <td class="text-right align-top whitespace-nowrap">
                     Rp&nbsp;{{ number_format($row->debit, 0, ',', '.') }}
                  </td>
                  <td class="text-right align-top whitespace-nowrap">
                     Rp&nbsp;{{ number_format($row->kredit, 0, ',', '.') }}
                  </td>
                  <td class="text-right align-top whitespace-nowrap">
                     Rp&nbsp;{{ number_format($row->saldo, 0, ',', '.') }}
                  </td>

                  <td class="text-center align-top">{{ $row->metode_pembayaran ?? '-' }}</td>
               </tr>
            @empty
               <tr>
                  <td colspan="7" class="text-center text-gray-500 py-3">
                     Tidak ada data
                  </td>
               </tr>
            @endforelse

            {{-- Total --}}
            @if (count($laporan) > 0)
               <tr class="bg-gray-100 font-bold">
                  <td colspan="3" class="text-center">Total</td>
                  <td class="text-right whitespace-nowrap">
                     <strong>Rp&nbsp;{{ number_format($totalDebit, 0, ',', '.') }}</strong>
                  </td>
                  <td class="text-right whitespace-nowrap">
                     <strong>Rp&nbsp;{{ number_format($totalKredit, 0, ',', '.') }}</strong>
                  </td>
                  <td class="text-right whitespace-nowrap">
                     <strong>Rp&nbsp;{{ number_format($row->saldo, 0, ',', '.') }}</strong>
                  </td>
                    <td></td>
               </tr>
            @endif
         </tbody>
      </table>
   </div>

</body>

</html>
