<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class KeranjangController extends Controller
{
    // ===========================
    // TAMPILKAN ISI KERANJANG
    // ===========================
 public function index()
{
    // session()->forget('keranjang');

    $keranjang = session()->get('keranjang', []);

    $total = 0;
    foreach ($keranjang as $item) {
        $total += $item['total'];
    }

    return view('user.keranjang', compact('keranjang', 'total'));
}




    // ==========================================================
    // FUNGSI BARU: Mengambil jumlah item unik di keranjang (untuk AJAX)
    // ==========================================================
    public function getCartCount(Request $request)
    {
        $keranjang = session()->get('keranjang', []);
        $count = count($keranjang);

        return response()->json([
            'count' => $count
        ]);
    }

    // ==========================================================
    // TAMBAH PRODUK KE KERANJANG
    // ==========================================================
   public function store(Request $request)
{
    try {
        // 1. Cek Login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk menambahkan ke keranjang.');
        }

        // 2. Cek Role
        if (Auth::user()->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengguna dengan role "user" yang dapat menambahkan produk ke keranjang.'
            ], 403);
        }

        // 3. Validasi
        if (!$request->filled(['produk_id', 'variasi_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'ID Produk atau Variasi tidak ditemukan.'
            ], 400);
        }

        // 4. Ambil produk
        $produk = Product::with('prices')->findOrFail($request->produk_id);
        $variasi = $produk->prices()->findOrFail($request->variasi_id);

        // 5. Tambah ke session keranjang
        $keranjang = session()->get('keranjang', []);
      $keranjang[] = [
    'produk_id' => $produk->id,
    'variasi_id' => $variasi->id,
    'produk'    => $produk->nama_produk,
    'gram'      => $variasi->berat . ' gram',
    'harga'     => $variasi->harga, // harga satuan
    'qty'       => $request->qty ?? 1, // default 1
    'total'     => ($request->qty ?? 1) * $variasi->harga,
    'foto'      => $produk->foto ?? null,
];

        session()->put('keranjang', $keranjang);

        // =============================
        // 6. Jika request AJAX → JSON
        // =============================
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'count'   => count($keranjang)
            ]);
        }

        // ===========================================
        // 7. Jika bukan AJAX → redirect ke keranjang
        // ===========================================
        return redirect()->route('keranjang.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang!');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Item tidak tersedia atau ID tidak valid.'
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.'
        ], 500);
    }
}

    // ==========================================================
    // HAPUS ITEM KERANJANG
    // ==========================================================
    public function remove($index)
    {
        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$index])) {
            unset($keranjang[$index]);
            session()->put('keranjang', $keranjang);
        }

        return redirect()->route('keranjang.index')
            ->with('success', 'Item berhasil dihapus!');
    }
}
