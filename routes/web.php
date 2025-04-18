<?php

use App\Http\Controllers\admin\CartController as AdminCartController;
use App\Http\Controllers\admin\OrderController as AdminOrderController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\VariantOptionProductController;
use App\Http\Controllers\client\CartController;
use App\Http\Controllers\client\WishlistController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\client\ProductDetailController;
use App\Http\Controllers\client\SearchController;
use App\Http\Controllers\client\OrderController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\shop\ShopController as ShopShopController;
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
Route::get('/shop', [ShopShopController::class, 'index'])->name('shop-home');
Route::get('category/{id}', [ShopShopController::class, 'getProductsByCategory'])
     ->name('category.products');

Route::get('searchCategory', [HomeController::class, 'searchCategory'])->name('search-category');

// Tìm kiếm trang chủ (ví dụ: sản phẩm nổi bật)
Route::get('search', [HomeController::class, 'search'])
    ->name('home.search');

// Tìm kiếm trong shop (toàn bộ sản phẩm)
Route::get('shop/search', [ShopShopController::class, 'search'])
    ->name('shop.search');

Route::get('/product-detail/{id}', [ProductDetailController::class, 'productDetail'])->name('product.detail');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');

Route::post('/carts-delete/{id}', [CartController::class, 'delete'])->name('cart.delete');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
Route::post('/wishlist/update', [WishlistController::class, 'update'])->name('wishlist.update');
Route::delete('/wishlist/delete/{id}', [WishlistController::class, 'delete'])->name('wishlist.delete');

Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

Route::post('comment', [ProductDetailController::class,'comment'])->name('comment');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::delete('/orders/{order}/force-delete', [OrderController::class, 'forceDeleteOrder'])
    ->name('orders.forceDelete');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('carts', AdminCartController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::put('orders/{id}/status', [App\Http\Controllers\admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('variants', App\Http\Controllers\admin\VariantController::class);
    Route::resource('variant-options', App\Http\Controllers\admin\VariantOptionController::class);
    Route::prefix('products/{product}')->group(function () {
        Route::get('variants/create', [VariantOptionProductController::class, 'create'])->name('products.variants.create');
        Route::post('variants', [VariantOptionProductController::class, 'store'])->name('products.variants.store');
    });


});
