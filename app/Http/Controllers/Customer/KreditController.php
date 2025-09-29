<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use App\Models\Ponsel;
use App\Models\KreditPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Kredit\DataPribadiRequest;
use App\Http\Requests\Kredit\DataPekerjaanRequest;

class KreditController extends Controller
{

    public function ajukanKredit($id_produk)
    {
        $customer = auth('web')->user();
        $jumlah = 1;
        session(['kredit_produk_id' => $id_produk]);
        $maxDate = Carbon::now()->subYears(17)->format('m/d/Y');

        return view('customer.kredit.data_pribadi', compact('customer', 'maxDate'));
    }

    public function dataPekerjaan()
    {
        return view('customer.kredit.data_pekerjaan');
    }

    public function uploadDokumenForm()
    {
        return view('customer.kredit.upload_dokumen');
    }

    public function dataKredit()
    {
        $data = [
            'step1' => session('data_pribadi', []),
            'step2' => session('data_pekerjaan', []),
            'step3' => session('upload_dokumen', []),
        ];
        $tanggal_lahir = Carbon::parse($data['step1']['tanggal_lahir'])->translatedFormat('d F Y');

        return view('customer.kredit.data_kredit', compact('data', 'tanggal_lahir'));
    }

    public function dataPribadiStore(DataPribadiRequest $request)
    {
        $validated = $request->validated();
        $validated['tanggal_lahir'] = Carbon::createFromFormat('m/d/Y', $validated['tanggal_lahir'])->format('Y-m-d');
        session(['data_pribadi' => $validated]);

        return redirect()->route('data.pekerjaan');
    }

    public function dataPekerjaanStore(DataPekerjaanRequest $request)
    {
        $validated = $request->validated();

        // Simpan ke session (step2)
        session(['data_pekerjaan' => $validated]);

        return redirect()->route('kredit.upload.dokumen');
    }

    public function uploadDokumenStore(Request $request)
    {
        $validated = $request->validate([
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_selfie' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // simpan file ke storage (misalnya folder kredit)
        $fotoKtp = base64_encode(file_get_contents($request->file('foto_ktp')));
        $fotoSelfie = base64_encode(file_get_contents($request->file('foto_selfie')));

        // simpan ke session
        session(['upload_dokumen' => [
            'foto_ktp' => $fotoKtp,
            'foto_selfie' => $fotoSelfie,
        ]]);

        return redirect()->route('data.kredit');
    }

    public function submitKredit(Request $request)
    {
        $id_produk = session('kredit_produk_id');
        $data_pribadi = session('data_pribadi', []);
        $data_pekerjaan = session('data_pekerjaan', []);
        $upload_dokumen = session('upload_dokumen', []);
        $ponsel = Ponsel::find($id_produk);

        $hargaPonsel = $ponsel->harga_jual;
        $bunga = $hargaPonsel * 0.015;
        $totalHarga = $hargaPonsel + $bunga;
        $dp = $data_pekerjaan['jumlah_dp'];
        $jangkaWaktu = $data_pekerjaan['jangka_waktu'];

        $sisa = $totalHarga - $dp;
        $cicilanPerBulan = $jangkaWaktu > 0 ? $sisa / $jangkaWaktu : 0;
        // Validasi ulang sebelum menyimpan

        if (empty($data_pribadi) || empty($data_pekerjaan) || empty($upload_dokumen) || empty($id_produk)) {
            return redirect()->route('produk.show', $id_produk)->with('error', 'Data pengajuan tidak lengkap. Silakan mulai dari awal.');
        }

        $ktpPath = null;
        $selfiePath = null;

        if (! empty($upload_dokumen['foto_ktp'])) {
            $ktpPath = 'kredit/ktp/' . uniqid() . '.jpg';
            Storage::disk('public')->put($ktpPath, base64_decode($upload_dokumen['foto_ktp']));
        }

        if (! empty($upload_dokumen['foto_selfie'])) {
            $selfiePath = 'kredit/foto_selfie/' . uniqid() . '.jpg';
            Storage::disk('public')->put($selfiePath, base64_decode($upload_dokumen['foto_selfie']));
        }
        $kredit = KreditPonsel::create([
            'id_customer' => auth('web')->id(),
            'id_ponsel' => $id_produk,
            'nama_lengkap' => $data_pribadi['nama_lengkap'],
            'NIK' => $data_pribadi['nik'],
            'tempat_lahir' => $data_pribadi['tempat_lahir'],
            'tanggal_lahir' => $data_pribadi['tanggal_lahir'],
            'jenis_kelamin' => $data_pribadi['jenis_kelamin'],
            'status_pernikahan' => $data_pribadi['status_pernikahan'],
            'alamat_ktp' => $data_pribadi['alamat_ktp'],
            'alamat_domisili' => $data_pribadi['alamat_domisili'],
            'no_telepon' => $data_pribadi['no_telp'],
            'email' => $data_pribadi['email'],

            'pekerjaan' => $data_pekerjaan['pekerjaan'],
            'nama_perusahaan' => $data_pekerjaan['nama_perusahaan'],
            'alamat_perusahaan' => $data_pekerjaan['alamat_perusahaan'],
            'lama_bekerja' => $data_pekerjaan['lama_bekerja'],
            'penghasilan_per_bulan' => $data_pekerjaan['penghasilan_bulanan'],
            'penghasilan_lainnya' => $data_pekerjaan['penghasilan_lain'] ?? 0,
            'tenor' => $data_pekerjaan['jangka_waktu'],
            'jumlah_DP' => $data_pekerjaan['jumlah_dp'],
            'angsuran_per_bulan' => $cicilanPerBulan,
            'jumlah_pinjaman' => $totalHarga,

            'gambar_ktp' => $ktpPath,
            'gambar_selfie' => $selfiePath,

            'status' => 'menunggu',
        ]);

        // Hapus data session setelah submit
        session()->forget(['data_pribadi', 'data_pekerjaan', 'upload_dokumen', 'kredit_produk_id']);

        return redirect()->route('kredit.success', $kredit->id_kredit_ponsel);
    }

    public function success($id)
    {
        $kredit = KreditPonsel::findOrFail($id);
        $hargaPonsel = $kredit->ponsel->harga_jual; // asumsi relasi sudah dibuat

        return view('customer.kredit.success', compact('kredit', 'hargaPonsel'));
    }
}
