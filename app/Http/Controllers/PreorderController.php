<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Preorder;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // ✅ Perbaikan di sini

class PreorderController extends Controller
{
    /**
     * Tampilkan form pre-order
     */
    public function create(Request $request, $priceId = null)
    {
        // 🔒 Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pre-order.');
        }

        // 1️⃣ Ambil ID dari URL atau query string
        $idToUse = $priceId ?? $request->query('price_id');

        // 2️⃣ Jika ID kosong
        if (!$idToUse) {
            return redirect()->route('produk')
                ->with('error', 'Variasi produk tidak ditentukan. Silakan pilih produk terlebih dahulu.');
        }

        // 3️⃣ Ambil data variasi produk
        $price = ProductPrice::with('product')->find($idToUse);

        if (!$price) {
            return redirect()->route('produk')
                ->with('error', 'Data variasi produk tidak ditemukan atau tidak valid.');
        }

        // ✅ Kirim data ke view
        return view('preorder.create', compact('price'));
    }

    /**
     * Simpan data pre-order
     */
    public function store(Request $request)
    {
        // 🔒 Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pre-order.');
        }

        // 🧾 Validasi input
        $validated = $request->validate([
            'price_id' => 'required|exists:product_prices,id',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        try {
            $price = ProductPrice::findOrFail($validated['price_id']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Variasi produk tidak ditemukan. Pre-order dibatalkan.');
        }

        // 🔍 Cek duplikasi
        $existing = Preorder::where('user_id', $user->id)
            ->where('price_id', $price->id)
            ->first();

        if ($existing) {
            return back()->with('warning', 'Kamu sudah melakukan pre-order untuk variasi ini.');
        }

        try {
            // 💾 Simpan data preorder
            Preorder::create([
                'user_id'           => $user->id,
                'price_id'          => $price->id,
                'tanggal_preorder'  => Carbon::now()->toDateString(),
                'deskripsi'         => $validated['deskripsi'] ?? null,
            ]);

            return redirect()->route('produk')
                ->with('success', 'Pre-order berhasil dibuat! Kami akan menghubungi kamu saat produk tersedia.');
        } catch (\Exception $e) {
            // 🔴 Log error untuk debugging
            Log::error('Preorder Store Error: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'price_id' => $validated['price_id'] ?? null,
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat pre-order. Silakan coba lagi.');
        }
    }
}
