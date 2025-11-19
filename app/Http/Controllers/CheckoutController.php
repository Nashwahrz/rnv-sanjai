<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // ==============================
    // HALAMAN CHECKOUT
    // ==============================
    public function checkout(Request $request)
    {
        // Jika ada parameter Beli Sekarang
        if ($request->has(['produk_id', 'variasi_id', 'qty'])) {

            $produk = Product::with('prices')->find($request->produk_id);

            if (!$produk) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            $variasi = $produk->prices->find($request->variasi_id);

            if (!$variasi) {
                return redirect()->back()->with('error', 'Variasi produk tidak ditemukan.');
            }

            // Keranjang hanya berisi 1 item (checkout langsung)
            $keranjang = [[
                'produk' => $produk->nama_produk,
                'gram'   => $variasi->berat . ' gram',
                'harga'  => $variasi->harga,
                'qty'    => (int) $request->qty,
                'total'  => $variasi->harga * (int)$request->qty,
            ]];

            return view('user.checkout', compact('keranjang'));
        }

        // Checkout dari keranjang
        $keranjang = session('keranjang', []);

        return view('user.checkout', compact('keranjang'));
    }

    // ==============================
    // PROSES CHECKOUT → KIRIM WHATSAPP
    // ==============================
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'alamat'  => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
        ]);

        $keranjang = session()->get('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('produk')->with('error', 'Keranjang kosong, silakan pilih produk terlebih dahulu.');
        }

        // Format WA
        $pesan = "Halo, saya ingin pesan keripik:\n\n";

        foreach ($keranjang as $item) {
            $pesan .= "- {$item['produk']} ({$item['gram']}) x {$item['qty']} : Rp " . number_format($item['total'], 0, ',', '.') . "\n";
        }

        $subtotal = array_sum(array_column($keranjang, 'total'));
        $pesan .= "\nTotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
        $pesan .= "Data Pemesan:\n";
        $pesan .= "Nama: {$request->nama}\n";
        $pesan .= "Alamat: {$request->alamat}\n";
        $pesan .= "Telepon: {$request->telepon}\n";

        $pesan = urlencode($pesan);
        $nomorAdmin = "6282384522629";

        // Hapus keranjang
        session()->forget('keranjang');

        return redirect("https://wa.me/{$nomorAdmin}?text={$pesan}");
    }
}
