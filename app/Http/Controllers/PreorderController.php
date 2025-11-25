<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Preorder;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PreorderController extends Controller
{
    /**
     * FORM PREORDER
     */
    public function create(Request $request)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pre-order.');
        }

        // Ambil variasi_id dari URL
        $variasiId = $request->query('variasi_id');

        // Validasi
        if (!$variasiId) {
            return redirect()->route('produk')
                ->with('error', 'Variasi produk tidak ditentukan. Silakan pilih produk terlebih dahulu.');
        }

        // Ambil data variasi
        $price = ProductPrice::with('product')->find($variasiId);

        if (!$price) {
            return redirect()->route('produk')
                ->with('error', 'Data variasi produk tidak ditemukan atau tidak valid.');
        }

        // Qty default = 1 jika tidak dikirim
        $qty = $request->query('qty', 1);

        return view('preorder.create', compact('price', 'qty'));
    }

    /**
     * SIMPAN PREORDER
     */
   public function store(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')
            ->with('error', 'Silakan login terlebih dahulu untuk melakukan pre-order.');
    }

    $validated = $request->validate([
        'price_id'  => 'required|exists:product_prices,id',
        'qty'       => 'required|integer|min:1',
        'deskripsi' => 'nullable|string|max:255',
    ]);

    $user = Auth::user();

    try {
        $price = ProductPrice::with('product')->findOrFail($validated['price_id']);
    } catch (\Exception $e) {
        return back()->with('error', 'Variasi produk tidak ditemukan. Pre-order dibatalkan.');
    }

    // $existing = Preorder::where('user_id', $user->id)
    //     ->where('price_id', $price->id)
    //     ->first();

    // if ($existing) {
    //     return back()->with('warning', 'Kamu sudah melakukan pre-order untuk variasi ini.');
    // }

    try {

        // Simpan ke database
        $preorder = Preorder::create([
            'user_id'          => $user->id,
            'price_id'         => $price->id,
            'qty'              => $validated['qty'],
            'tanggal_preorder' => Carbon::now()->toDateString(),
            'deskripsi'        => $validated['deskripsi'] ?? null,
        ]);

        // ======================================
        // 🔥 KIRIM KE WHATSAPP ADMIN
        // ======================================

        $adminNumber = "6285165755238"; // nomor admin (format internasional tanpa +)

        $message = "📌 *PRE-ORDER BARU MASUK* 📌\n\n"
            . "*Nama:* {$user->name}\n"
            . "*Email:* {$user->email}\n"
            . "*No Telp:* {$user->no_telp}\n"
            . "*Alamat:* {$user->alamat}\n\n"

            . "=====================\n"
            . "*Detail Pre-Order*\n"
            . "=====================\n"
            . "*Produk:* {$price->product->nama_produk}\n"
            . "*Variasi:* {$price->variasi}\n"
            . "*Qty:* {$preorder->qty}\n"
            . "*Tanggal:* {$preorder->tanggal_preorder}\n"
            . "*Catatan:* {$preorder->deskripsi}\n\n"

            . "Silakan diproses 🙏";

        // Encode pesan ke URL
        $waUrl = "https://wa.me/{$adminNumber}?text=" . urlencode($message);

        // Redirect user ke WA admin
        return redirect($waUrl);

        // ======================================

    } catch (\Exception $e) {

        Log::error('Preorder Store Error: ' . $e->getMessage(), [
            'user_id'  => $user->id,
            'price_id' => $validated['price_id'],
        ]);

        return back()->with('error', 'Terjadi kesalahan saat membuat pre-order. Silakan coba lagi.');
    }
}

}
