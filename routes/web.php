<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\client\ProductDetailController;
use App\Http\Controllers\client\SearchController;
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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});
