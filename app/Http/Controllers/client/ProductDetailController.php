<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Product; // Import the Product model
use App\Models\Category; 
use Illuminate\Support\Facades\Auth;// Import the Category model

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

        $comment = Comment::where('product_id', $id)->get();
        // Truyền dữ liệu qua view
        return view('client.products.main', compact('product', 'relatedProducts', 'discountedPrice', 'originalPrice', 'variants', 'comment'));
    }

    public function comment(Request $request) {
        $requestData = $request->all();
        if ($requestData['comment']) {
            $Auth = Auth::id();
            $data = [
                "comment" => $requestData["comment"],
                "product_id" => $requestData["product_id"],
                "user_id" => $Auth
            ];
            Comment::create($data);
        }
        return back();
    }
}
