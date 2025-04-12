<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Import the Product model
use App\Models\Category; // Import the Category model

class ProductDetailController extends Controller
{
    public function productDetail(Request $request, $id)
    {
        $product = Product::findOrFail($id); // Lấy sản phẩm theo ID

        // Tính giá sau khi giảm
        $discountedPrice = $product->price * (1 - $product->discount_value / 100);  //Giảm giá sản phẩm detaildetail
        $originalPrice = $product->price; //Giảm giá sản phẩm Related product
        $variants = $product->variants; // Lấy các biến thể từ bảng product_variants

        // Lấy 12 sản phẩm bán chạy cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->orderBy('sales', 'desc')
            ->take(12)
            ->get();

        // Truyền dữ liệu qua view
        return view('client.products.main', compact('product', 'relatedProducts', 'discountedPrice', 'originalPrice', 'variants'));
    }
}
