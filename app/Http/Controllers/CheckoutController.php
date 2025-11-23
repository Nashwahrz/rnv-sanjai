<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // ==============================
    // HALAMAN CHECKOUT
    // ==============================
    public function proses(Request $request)
    {
        // ============================
        // MODE BELI SEKARANG
        // ============================
        if ($request->has(['produk_id', 'variasi_id', 'qty'])) {

            $produk = Product::with('prices')->find($request->produk_id);

            if (!$produk) { abort(404); }

            $variasi = $produk->prices->where('id', $request->variasi_id)->first();

            if (!$variasi) { abort(404); }

            $qty = (int) $request->qty;

            $keranjang = [[
                'produk'     => $produk->nama_produk,
                'produk_id'  => $produk->id,
                'variasi_id' => $variasi->id,
                'gram'       => $variasi->berat . ' gram',
                'harga'      => $variasi->harga,     // ← harga pasti benar
                'qty'        => $qty,
                'total'      => $variasi->harga * $qty, // ← total dihitung disini
            ]];

            session(['checkout_now' => $keranjang]);

            return view('user.checkout', compact('keranjang'));
        }

        // ============================
        // MODE CHECKOUT DARI KERANJANG
        // ============================
        $keranjang = session('keranjang', []);

        // hitung ulang total (supaya harga pasti benar)
        foreach ($keranjang as &$item) {
            $item['total'] = $item['harga'] * $item['qty'];
        }

        session(['checkout_now' => $keranjang]);

        return view('user.checkout', compact('keranjang'));
    }

    // ==============================
    // PROSES CHECKOUT → SIMPAN DB
    // ==============================
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'alamat'  => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'metode'  => 'required|string'
        ]);

        // Ambil keranjang dari session
        $keranjang = session('checkout_now', []);
        if (empty($keranjang)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        // Hitung total fix
        $total = array_sum(array_column($keranjang, 'total'));

        // SIMPAN ORDER
        $order = Order::create([
            'user_id'      => Auth::id(),
            'total_amount' => $total,
            'status'       => 'pending',
            'alamat'       => $request->alamat,
            'no_hp'        => $request->telepon,
        ]);

        // SIMPAN ORDER ITEMS
        foreach ($keranjang as $item) {
            OrderItem::create([
                'order_id'         => $order->id,
                'product_id'       => $item['produk_id'],
                'product_price_id' => $item['variasi_id'],
                'quantity'         => $item['qty'],
            ]);
        }

        // SIMPAN PAYMENT
        Payment::create([
            'order_id' => $order->id,
            'metode'   => $request->metode,
            'status'   => 'pending',
            'bukti'    => null,
        ]);

        // WhatsApp Message
        $pesan = "Halo, saya ingin pesan keripik:\n\n";

        foreach ($keranjang as $item) {
            $pesan .= "- {$item['produk']} ({$item['gram']}) x {$item['qty']} : Rp "
                . number_format($item['total'], 0, ',', '.') . "\n";
        }

        $pesan .= "\nTotal: Rp " . number_format($total, 0, ',', '.') . "\n\n";
        $pesan .= "Nama: {$request->nama}\n";
        $pesan .= "Alamat: {$request->alamat}\n";
        $pesan .= "Telepon: {$request->telepon}\n";
        $pesan .= "Order ID: {$order->id}\n";

        // Bersihkan session
        session()->forget('checkout_now');
        session()->forget('keranjang');

        // Redirect ke WA
        return redirect("https://wa.me/6282384522629?text=" . urlencode($pesan));
    }
}
