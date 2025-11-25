@extends('layouts.template')

@section('title', 'Kelola Orders & Preorders')

{{-- Tambahkan Style untuk mengatasi ikon pagination yang rusak (hanya untuk panah) --}}
@push('styles')
<style>
    /* Mengatasi ikon pagination yang gagal dimuat (tampil besar) */
    .pagination .page-link svg {
        display: none !important; /* Sembunyikan SVG/ikon bawaan yang rusak */
    }

    /* Tampilkan teks 'Previous' dan 'Next' sebagai fallback yang aman dan kecil */
    .pagination .page-link span[aria-hidden="true"] {
        font-size: 0.8rem; /* Jadikan ukuran font lebih kecil agar rapi */
        font-weight: bold;
    }

    /* Perbaikan kecil: mengatur jarak antara tabel dan pagination */
    .pagination {
        margin-top: 10px;
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Kelola Semua Transaksi</h3>

    {{-- Pesan Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <hr>

    {{-- ==========================
        TABEL ORDERS
    ========================== --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between">
            <h5 class="mb-0">Orders</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Update Status</th>
                        <th>Tanggal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>**Rp {{ number_format($order->total_amount, 0, ',', '.') }}**</td>

                            <td>{{ ucfirst($order->payments->first()->metode ?? 'N/A') }}</td>

                            <td><span class="badge bg-{{ $order->status == 'selesai' ? 'success' : ($order->status == 'ditolak' ? 'danger' : ($order->status == 'proses' ? 'primary' : 'warning')) }}">{{ ucfirst($order->status) }}</span></td>

                            {{-- Dropdown Status Order --}}
                            <td>
                                @php
                                    $paymentMethod = strtolower($order->payments->first()->metode ?? 'cash');
                                @endphp
                                <select class="form-select form-select-sm"
                                    data-current-status="{{ $order->status }}"
                                    onchange="handleOrderStatusChange(this, {{ $order->id }}, {{ $order->total_amount }}, '{{ $paymentMethod }}')">

                                    <option value="pending"  {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="proses"   {{ $order->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai"  {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak"  {{ $order->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </td>

                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada order</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination untuk Orders --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $orders->appends(['preorders_page' => request('preorders_page')])->links() }}
            </div>
        </div>
    </div>

    <hr>

    {{-- ==========================
        TABEL PREORDERS
    ========================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Preorders</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Pemesan</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Update Status</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($preorders as $item)
                        <tr>
                            <td>{{ ($preorders->currentPage() - 1) * $preorders->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->price->nama_harga ?? '-' }}</td>
                            <td>Rp {{ number_format($item->price->harga ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $item->qty }}</td>

                            <td><span class="badge bg-{{ $item->status == 'selesai' ? 'success' : ($item->status == 'ditolak' ? 'danger' : ($item->status == 'proses' ? 'primary' : 'warning')) }}">{{ ucfirst($item->status) }}</span></td>

                            {{-- Dropdown Status Preorder (Submit Form Biasa) --}}
                            <td>
                                <form action="{{ route('admin.preorders.updateStatus', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending"  {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="proses"   {{ $item->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="selesai"  {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak"  {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </form>
                            </td>

                            <td>**Rp {{ number_format(($item->price->harga ?? 0) * $item->qty, 0, ',', '.') }}**</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada preorder</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination untuk Preorders --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $preorders->appends(['orders_page' => request('orders_page')])->links() }}
            </div>
        </div>
    </div>

</div>

{{-- ==========================
MODAL PENGEMBALIAN DANA (REFUND)
========================== --}}
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="refundForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="type" value="order">
            <input type="hidden" name="total_amount_refunded" id="totalAmountRefunded">
            <input type="hidden" name="status" value="ditolak">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Proses Pengembalian Dana (Transfer)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="text-danger">Order ini menggunakan metode **Transfer**. Harap konfirmasi pengembalian dana.</p>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Refund</label>
                        <input type="text" id="refund_amount_display" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Bukti Refund</label>
                        <input type="file" name="refund_proof" class="form-control" accept="image/*,application/pdf" required>
                        <div class="form-text">Bukti ini akan disimpan sebagai arsip pengembalian dana.</div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        Konfirmasi Tolak & Refund
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


{{-- SCRIPT HANDLE STATUS (Tidak ada perubahan logika) --}}
<script>
    /**
     * Menangani perubahan status untuk Order
     */
    function handleOrderStatusChange(select, orderId, amount, paymentMethod) {

        const previousStatus = select.getAttribute('data-current-status');
        const selectedStatus = select.value;

        // Base URL untuk update status (asumsi: admin/orders/updateStatus/{id})
        const baseUrl = '{{ url("admin/orders/updateStatus") }}';

        // 1. Cek kondisi untuk menampilkan Modal Refund
        if (selectedStatus === 'ditolak' && paymentMethod.toLowerCase() === 'transfer') {

            // Set data ke Modal Form
            const form = document.getElementById('refundForm');
            form.action = `${baseUrl}/${orderId}`;

            document.getElementById('totalAmountRefunded').value = amount;
            document.getElementById('refund_amount_display').value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);

            const modal = new bootstrap.Modal(document.getElementById('refundModal'));
            modal.show();

            // Kembalikan status dropdown ke status sebelumnya
            select.value = previousStatus;

        } else {
            // 2. Logika Status Lain atau Ditolak (Non-Transfer) -> Langsung Submit

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${baseUrl}/${orderId}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PUT';
            form.appendChild(method);

            const status = document.createElement('input');
            status.type = 'hidden';
            status.name = 'status';
            status.value = selectedStatus;
            form.appendChild(status);

            const type = document.createElement('input');
            type.type = 'hidden';
            type.name = 'type';
            type.value = 'order';
            form.appendChild(type);

            document.body.appendChild(form);
            form.submit();

            // Update data-attribute status saat ini setelah submit non-modal
            select.setAttribute('data-current-status', selectedStatus);
        }
    }
</script>
{{-- Pastikan Anda juga memiliki section @push('scripts') di layout.template Anda --}}
@endsection
