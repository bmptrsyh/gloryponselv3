<?php
namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use App\Models\BeliPonsel;
use Illuminate\Http\Request;
use App\Services\OngkirService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Royryando\Duitku\Facades\Duitku;

class BeliPonselController extends Controller
{
    public function beliPonsel(Request $request)
    {
        $customer = Auth::user();

        if ($customer->alamat == null) {
            return redirect()->route('produk.index')->with('error', 'Alamat masih kosong, harap lengkapi data alamat anda');
        }

        if($request->has('produk')) {
        $produkJson = $request->input('produk');
        $produkArray = json_decode($produkJson, true);
                
        if (!$produkArray || count($produkArray) === 0) {
            return redirect()->back()->with('error', 'Tidak ada produk yang di pilih');
        }
        
        $idPonselArray = array_column($produkArray, 'id');
        
        $ponsel = Ponsel::whereIn('id_ponsel', $idPonselArray)->get();
        
        $harga = 0;
        
        foreach ($ponsel as $item) {
            foreach ($produkArray as $p) {
                if ($item->id_ponsel == $p['id']) {
                    if($p['jumlah'] > $item->stok) {
                        return redirect()->back()->with('error', 'Jumlah pembelian melebihi stok yang tersedia untuk produk ' . $item->nama_ponsel);
                    }
                    $item->jumlah = $p['jumlah'];
                    $item->harga = $item->harga_jual;
                    $item->subtotal = $item->jumlah * $item->harga;
                    $harga += $item->subtotal;
                    break;
                }
            }
        }

        $paymentMethod = Duitku::paymentMethods($harga);

    return view('checkout', compact('ponsel', 'paymentMethod', 'harga'));
} else {
        $id = $request->input('id_ponsel');
        $jumlah = $request->input('jumlah', 1);

        $ponsel = Ponsel::where('id_ponsel', $id)->get();
        foreach ($ponsel as $item) {
            if($jumlah > $item->stok) {
                return redirect()->back()->with('error', 'Jumlah pembelian melebihi stok yang tersedia');
            }
            $item->jumlah = $jumlah;
        }

        $harga = $item->harga_jual * $jumlah;
        $paymentMethod = Duitku::paymentMethods($harga);

        return view('checkout', compact('ponsel', 'paymentMethod', 'harga'));
}
    }

   public function submitCheckout(Request $request)
{
    $validated = $request->validate([
        'id_ponsel' => 'required|array',
        'id_ponsel.*' => 'exists:ponsel,id_ponsel',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
        'harga' => 'required|numeric', // total harga semua ponsel
        'payment_method' => 'required',
        'exp' => 'required|numeric',
        'payment_method_name' => 'required',
        'fee' => 'required',
        'destination' => 'required',
        'courier' => 'required',
    ]);


    $destination = $validated['destination'];
    $courier = $validated['courier'];


    $ongkir = OngkirService::getOngkir($destination, $courier);

    $hargaOngkir = $ongkir['0']['cost'];

    $customer = Auth::user();
    $orderId = 'ORDER-' . strtoupper(uniqid());
    $paymentMethod = $validated['payment_method'];
    $paymentMethodName = $validated['payment_method_name'];
    $expiry = (int) $validated['exp'];
    $totalHarga = (int) $validated['harga']; // total dari semua item

    $totalHarga = 0;

    foreach ($validated['id_ponsel'] as $index => $idPonsel) {
    // Ambil data ponsel berdasarkan ID
    $ponsel = Ponsel::findOrFail($idPonsel);

    // Ambil jumlah yang dikirim dari frontend
    $jumlah = $validated['jumlah'][$index];
    if ($jumlah > $ponsel->stok) {
        return redirect('/produk')->with(['error' => 'Stok tidak mencukupi' . ' ' . $ponsel->merk . ' ' . $ponsel->model]);
    }

    // Hitung total harga
    $subtotal = $ponsel->harga_jual * $jumlah;
    $totalHarga += $subtotal;
}

    $hargaAkhir = $totalHarga + $hargaOngkir + $validated['fee'];

    if ($validated['harga'] != $hargaAkhir) {
        return redirect('/produk')->with(['error' => 'Pembayaran Gagal, Silahkan ulangi !']);
    }

    try {
        $response = Duitku::createInvoice(
            $orderId,
            $totalHarga,
            $paymentMethod,
            'Checkout Ponsel',
            $customer->nama,
            $customer->email,
            $expiry
        );

        // Simpan semua pembelian ponsel dalam transaksi yang sama
        foreach ($validated['id_ponsel'] as $index => $idPonsel) {
            $ponsel = Ponsel::findOrFail($idPonsel);
            $jumlah = $validated['jumlah'][$index];

            if($jumlah > $ponsel->stok) {
                return back()->with('error', 'stok tidak mencukupi');
            }

            BeliPonsel::create([
                'id_customer' => $customer->id_customer,
                'id_ponsel' => $idPonsel,
                'metode_pembayaran' => $paymentMethodName,
                'status' => 'tertunda',
                'tanggal_transaksi' => now(),
                'jumlah' => $jumlah,
                'harga' => $ponsel->harga_jual * $jumlah,
                'code' => $orderId,
                'status_pengiriman' => 'belum_dikirim',
            ]);
        }


        return redirect($response['payment_url']);
    } catch (\Exception $e) {
        return redirect('/produk')->with(['error' => 'Pembayaran Gagal, Silahkan ulangi !']);
    }

}

}