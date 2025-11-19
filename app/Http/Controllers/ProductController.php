<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    // Form tambah produk
    public function create()
    {
        return view('admin.products.create');
    }

    // Simpan produk baru
  public function store(Request $request)
{
    $request->validate([
        'nama_produk' => 'required',
        'jenis_produk' => 'required',
        'deskripsi' => 'nullable',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'variasi.*.berat' => 'required|integer',
        'variasi.*.harga' => 'required|integer',
        'variasi.*.stok'  => 'required|integer|min:0',
    ]);

    // simpan produk
    $product = Product::create([
        'nama_produk' => $request->nama_produk,
        'jenis_produk' => $request->jenis_produk,
        'deskripsi' => $request->deskripsi,
        'foto' => $request->hasFile('foto')
                    ? $request->file('foto')->store('produk', 'public')
                    : null,
    ]);

    // simpan variasi harga + stok
    foreach ($request->variasi as $variasi) {
        $product->prices()->create([
            'berat' => $variasi['berat'],
            'harga' => $variasi['harga'],
            'stok'  => $variasi['stok'],
        ]);
    }

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan dengan variasi harga & stok!');
}



    // Form edit produk
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    // Update produk
   public function update(Request $request, $id)
{
    $request->validate([
        'nama_produk' => 'required',
        'jenis_produk' => 'required|in:manis,pedas',
        'deskripsi' => 'nullable',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'variasi.*.berat' => 'required|integer',
        'variasi.*.harga' => 'required|integer',
        'variasi.*.stok'  => 'required|integer|min:0',
    ]);

    $product = Product::findOrFail($id);

    // update data produk
    $product->update([
        'nama_produk' => $request->nama_produk,
        'jenis_produk' => $request->jenis_produk,
        'deskripsi' => $request->deskripsi,
        'foto' => $request->hasFile('foto')
                    ? $request->file('foto')->store('produk', 'public')
                    : $product->foto,
    ]);

    // update variasi harga & stok
    // hapus dulu variasi lama, lalu tambah ulang (paling gampang)
    $product->prices()->delete();

    foreach ($request->variasi as $variasi) {
        $product->prices()->create([
            'berat' => $variasi['berat'],
            'harga' => $variasi['harga'],
            'stok'  => $variasi['stok'],
        ]);
    }

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui dengan variasi harga & stok!');
}

    // Hapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function show($id)
    {
        // Ambil produk dengan harga/varian terkait
        $product = Product::with('prices')->findOrFail($id);

        return view('user.show', compact('product'));
    }
}
