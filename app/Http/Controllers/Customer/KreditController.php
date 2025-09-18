<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\KreditPonsel;
use App\Models\Ponsel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KreditController extends Controller
{
    public function ajukanKredit($id_produk)
    {
        $customer = auth('web')->user();
        session(['kredit_produk_id' => $id_produk]);

        return view('customer.kredit.data_pribadi', compact('customer'));
    }

    public function step1Post(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'required|string',
            'no_telp' => 'required|string',
            'email' => 'required|email',
        ]);

        // simpan ke session
        session(['kredit_step1' => $validated]);

        // redirect ke step 2
        return view('customer.kredit.data_pekerjaan');

    }

    public function dataPribadi(Request $request)
    {
        $validated = $request->validate([
            'pekerjaan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'lama_bekerja' => 'required|string|max:100',
            'penghasilan_bulanan' => 'required|numeric|min:0',
            'penghasilan_lain' => 'nullable|numeric|min:0',
            'jangka_waktu' => 'required|integer|min:1',
            'jumlah_dp' => 'required|numeric|min:0',
        ]);

        // Simpan ke session (step2)
        session(['kredit_step2' => $validated]);

        return view('customer.kredit.upload_dokumen');
    }

    public function uploadDokumen(Request $request)
    {
        $validated = $request->validate([
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_selfie' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // simpan file ke storage (misalnya folder kredit)
        $fotoKtp = base64_encode(file_get_contents($request->file('foto_ktp')));
        $fotoSelfie = base64_encode(file_get_contents($request->file('foto_selfie')));

        // simpan ke session
        session(['kredit_step3' => [
            'foto_ktp' => $fotoKtp,
            'foto_selfie' => $fotoSelfie,
        ]]);

        $data = [
            'step1' => session('kredit_step1', []),
            'step2' => session('kredit_step2', []),
            'step3' => session('kredit_step3', []),
        ];

        return view('customer.kredit.data_kredit', compact('data'));
    }

    public function submitPengajuan(Request $request)
    {
        $id_produk = session('kredit_produk_id');
        $step1 = session('kredit_step1', []);
        $step2 = session('kredit_step2', []);
        $step3 = session('kredit_step3', []);
        $ponsel = Ponsel::find($id_produk);

        $hargaPonsel = $ponsel->harga_jual;
        $bunga = $hargaPonsel * 0.015;
        $totalHarga = $hargaPonsel + $bunga;
        $dp = $step2['jumlah_dp'];
        $jangkaWaktu = $step2['jangka_waktu'];

        $sisa = $totalHarga - $dp;
        $cicilanPerBulan = $jangkaWaktu > 0 ? $sisa / $jangkaWaktu : 0;
        // Validasi ulang sebelum menyimpan

        if (empty($step1) || empty($step2) || empty($step3) || empty($id_produk)) {
            return redirect()->route('kredit.step1')->with('error', 'Data pengajuan tidak lengkap. Silakan mulai dari awal.');
        }

        $ktpPath = null;
        $selfiePath = null;

        if (! empty($step3['foto_ktp'])) {
            $ktpPath = 'kredit/ktp/'.uniqid().'.jpg';
            Storage::disk('public')->put($ktpPath, base64_decode($step3['foto_ktp']));
        }

        if (! empty($step3['foto_selfie'])) {
            $selfiePath = 'kredit/foto_selfie/'.uniqid().'.jpg';
            Storage::disk('public')->put($selfiePath, base64_decode($step3['foto_selfie']));
        }
        $kredit = KreditPonsel::create([
            'id_customer' => auth('web')->id(),
            'id_ponsel' => $id_produk,
            'nama_lengkap' => $step1['nama_lengkap'],
            'NIK' => $step1['nik'],
            'tempat_lahir' => $step1['tempat_lahir'],
            'tanggal_lahir' => $step1['tanggal_lahir'],
            'jenis_kelamin' => $step1['jenis_kelamin'],
            'status_pernikahan' => $step1['status_pernikahan'],
            'alamat_ktp' => $step1['alamat_ktp'],
            'alamat_domisili' => $step1['alamat_domisili'],
            'no_telepon' => $step1['no_telp'],
            'email' => $step1['email'],

            'pekerjaan' => $step2['pekerjaan'],
            'nama_perusahaan' => $step2['nama_perusahaan'],
            'alamat_perusahaan' => $step2['alamat_perusahaan'],
            'lama_bekerja' => $step2['lama_bekerja'],
            'penghasilan_per_bulan' => $step2['penghasilan_bulanan'],
            'penghasilan_lainnya' => $step2['penghasilan_lain'] ?? 0,
            'tenor' => $step2['jangka_waktu'],
            'jumlah_DP' => $step2['jumlah_dp'],
            'angsuran_per_bulan' => $cicilanPerBulan,
            'jumlah_pinjaman' => $totalHarga,

            'gambar_ktp' => $ktpPath,
            'gambar_selfie' => $selfiePath,

            'status' => 'menunggu',
        ]);
        // Hapus data session setelah submit
        session()->forget(['kredit_step1', 'kredit_step2', 'kredit_step3', 'kredit_produk_id']);

        return redirect()->route('customer.kredit.success', $kredit->id_kredit_ponsel);
    }

    public function success($id)
    {
        $kredit = KreditPonsel::findOrFail($id);
        $hargaPonsel = $kredit->ponsel->harga_jual; // asumsi relasi sudah dibuat

        return view('customer.kredit.success', compact('kredit', 'hargaPonsel'));
    }
}
