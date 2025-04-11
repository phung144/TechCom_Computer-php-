<?php

use App\Http\Controllers\admin\CartController as AdminCartController;
use App\Http\Controllers\admin\OrderController as AdminOrderController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\client\CartController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\client\ProductDetailController;
use App\Http\Controllers\client\SearchController;
use App\Http\Controllers\client\OrderController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin/home', function () { // /admin/home là đường dẫn chính xác đến view
    return view('admin.index');
})->name('admin-home'); //Tên route gọi đến view

Route::get('/', [HomeController::class, 'index'])->name('client-home');

Route::get('searchCategory', [HomeController::class, 'searchCategory'])->name('search-category');

Route::get('search', [HomeController::class, 'search'])->name('search');

Route::get('/product-detail/{id}', [ProductDetailController::class, 'productDetail'])->name('product.detail');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');

Route::post('/carts-delete/{id}', [CartController::class, 'delete'])->name('cart.delete');

Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('carts', AdminCartController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::put('orders/{id}/status', [App\Http\Controllers\admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('variants', App\Http\Controllers\admin\VariantController::class);
    Route::resource('variant-options', App\Http\Controllers\admin\VariantOptionController::class);
});
