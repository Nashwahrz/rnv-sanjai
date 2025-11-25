<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Preorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminTransactionController extends Controller
{
    // ===============================
    //  HALAMAN LIST SEMUA TRANSAKSI
    // ===============================
    public function index()
    {
        // Menggunakan relasi 'payments'
        $orders = Order::with(['user', 'payments'])
            ->latest()
            ->paginate(10, ['*'], 'orders_page');

        // Semua preorders
        $preorders = Preorder::with(['user', 'price'])
            ->latest()
            ->paginate(10, ['*'], 'preorders_page');

        return view('admin.transactions.index', compact(
            'orders',
            'preorders'
        ));
    }


    // ==================================
    //  UPDATE STATUS ORDER
    // ==================================
    public function updateOrderStatus(Request $request, $id)
    {
        // 1. VALIDASI DASAR
        $request->validate([
            'status' => 'required|string|in:pending,proses,selesai,ditolak',
            'type' => 'required|in:order',
        ]);

        $order = Order::findOrFail($id);
        $statusBaru = $request->status;

        // 2. LOGIKA REFUND (JIKA DITOLAK DAN ADA BUKTI REFUND DARI MODAL)
        if ($statusBaru == 'ditolak') {

            if ($request->hasFile('refund_proof')) {

                // VALIDASI KHUSUS REFUND (JIKA MELALUI MODAL)
                $request->validate([
                    'refund_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
                    'total_amount_refunded' => 'required|numeric|min:0',
                ]);

                DB::beginTransaction();

                try {
                    // Menyimpan file bukti refund ke folder 'refund_proofs'
                    $proofPath = $request->file('refund_proof')->store('refund_proofs', 'public');

                    // PERBAIKAN: Mengganti 'refund_proof_path' dengan 'bukti_admin'
                    $order->update([
                        'status' => 'ditolak',
                        'total_amount' => 0,
                        'bukti_admin' => $proofPath, // <-- Kolom database yang benar
                        'refund_amount' => $request->total_amount_refunded,
                    ]);

                    DB::commit();
                    return back()->with('success', 'Order Ditolak & Proses Refund Berhasil Dikonfirmasi!');

                } catch (\Exception $e) {
                    DB::rollBack();
                    // Hapus file jika transaksi database gagal
                    if (isset($proofPath)) {
                        Storage::disk('public')->delete($proofPath);
                    }
                    return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage());
                }

            } else {
                // Logika Ditolak TANPA Refund
                $order->update([
                    'status' => 'ditolak',
                    'total_amount' => 0,
                ]);
                return back()->with('success', 'Status order berhasil diubah menjadi Ditolak (Non-Transfer atau Tanpa Bukti Refund).');
            }

        } else {
            // 3. LOGIKA STATUS BIASA
            $order->update([
                'status' => $statusBaru,
            ]);

            return back()->with('success', 'Status order berhasil diperbarui menjadi ' . ucfirst($statusBaru) . '!');
        }
    }

    // ==================================
    //  UPDATE STATUS PREORDER
    // ==================================
    public function updatePreorderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,proses,selesai,ditolak'
        ]);

        $preorder = Preorder::findOrFail($id);

        $preorder->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status preorder berhasil diperbarui menjadi ' . ucfirst($request->status) . '!');
    }
}
