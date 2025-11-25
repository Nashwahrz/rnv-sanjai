<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\AdminTransactionController;

Route::get('/', [HomeController::class, 'index'])->name('beranda');
Route::get('/tentang', [HomeController::class, 'about'])->name('tentang');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
// Route::get('/keranjang', [HomeController::class, 'cart'])->name('keranjang');
// Route::get('/keranjang', [HomeController::class, 'keranjang'])->name('keranjang');
// Route::post('/keranjang/tambah', [HomeController::class, 'tambahKeranjang'])->name('keranjang.tambah');
// Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
// Route::post('/checkout/proses', [HomeController::class, 'prosesCheckout'])->name('checkout.proses');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('produk.show');
Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
Route::get('/checkout/sukses/{id}', function($id){
    return view('checkout_sukses', compact('id'));
})->name('pesanan.sukses');
Route::post('/checkout/simpan', [CheckoutController::class, 'prosesCheckout'])
    ->name('checkout.simpan');



// Keranjang
// Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
// Route::middleware(['auth'])->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.tambah');
    Route::delete('/keranjang/{index}', [KeranjangController::class, 'remove'])->name('keranjang.remove');
    Route::get('/keranjang/count', [KeranjangController::class, 'getCartCount'])->name('keranjang.count');
// });
Route::prefix('admin')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

});


// routes/web.php
Route::get('/preorder/{price_id?}', [PreorderController::class, 'create'])->name('preorder.create');
Route::post('/preorder/store', [PreorderController::class, 'store'])->name('preorder.store');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// REGISTER
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.post');
// Route::get('/profil', [AuthController::class, 'profil'])->name('profil.index');
// Profil user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});


Route::get('/pesanan-saya', [CheckoutController::class, 'pesananSaya'])
    ->name('pesanan.saya');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])
    // ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/admin/all-transactions', [AdminTransactionController::class, 'index'])
    ->name('admin.transactions');

// routes/web.php (Diubah)
Route::put('/admin/orders/updateStatus/{id}', [AdminTransactionController::class, 'updateOrderStatus'])
    ->name('admin.orders.updateStatus');

// Route Preorder juga disesuaikan agar konsisten (Opsional, tapi disarankan)
Route::put('/admin/preorders/updateStatus/{id}', [AdminTransactionController::class, 'updatePreorderStatus'])
    ->name('admin.preorders.updateStatus');
