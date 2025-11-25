<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Preorder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Halaman Beranda
    public function index()
    {
        $bestSeller = Product::with('prices')->take(3)->get();
        return view('user.beranda', compact('bestSeller'));
    }

    public function bestSeller()
    {
        $bestSeller = Product::with('prices')->take(3)->get();
        return view('welcome', compact('bestSeller'));
    }

    public function about()
    {
        return view('user.tentang');
    }

    // Halaman Produk
    public function produk()
    {
        $produk = Product::with('prices')->get();
        return view('user.produk', compact('produk'));
    }

    // Halaman Checkout
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

    // Jika tidak ada parameter, cek session keranjang
    $keranjang = session('keranjang', []);

    return view('user.checkout', compact('keranjang'));
}


    // Proses Checkout
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
        ]);

        $keranjang = session()->get('keranjang', []);

        // Jika keranjang kosong, redirect kembali
        if (empty($keranjang)) {
            return redirect()->route('produk')->with('error', 'Keranjang kosong, silakan pilih produk terlebih dahulu.');
        }

        // Format pesan WhatsApp
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

        // Hapus keranjang setelah checkout
        session()->forget('keranjang');

        // Redirect ke WhatsApp
        return redirect("https://wa.me/{$nomorAdmin}?text={$pesan}");
    }
  public function adminDashboard()
{
    // Total pesanan masuk
    $totalOrders = Order::count();

    // Total pendapatan dari tabel orders
    $totalOrderRevenue = Order::sum('total_amount');

    // Total pendapatan dari tabel preorders
    $totalPreOrderRevenue = Preorder::with('price')
        ->get()
        ->sum(function ($item) {
            return $item->price->harga * $item->qty;
        });

    // Gabungkan pendapatan orders + preorders
    $totalRevenue = $totalOrderRevenue + $totalPreOrderRevenue;

    // Pesanan pending
    $pendingOrders = Order::where('status', 'pending')->count();

    // Produk terlaris
    $bestProduct = OrderItem::select('product_id')
        ->selectRaw('SUM(quantity) as total_sold')
        ->with('product')
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->first();

    // Pesanan terbaru
    $latestOrders = Order::with('user')
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', compact(
        'totalOrders',
        'totalRevenue',
        'pendingOrders',
        'bestProduct',
        'latestOrders'
    ));
}


}
