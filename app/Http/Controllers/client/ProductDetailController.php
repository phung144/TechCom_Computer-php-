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
        // $product = Product::findOrFail($id); // Lấy sản phẩm theo ID

        // // Tính giá sau khi giảm
        // $discountedPrice = $product->price * (1 - $product->discount_value / 100);  //Giảm giá sản phẩm detaildetail
        // $originalPrice = $product->price; //Giảm giá sản phẩm Related product

        $product = Product::with(['variants.options', 'category'])->findOrFail($id);
        $variants = $product->variants;

        // Tính giá sau discount
        $originalPrice = $product->price;
        $discountedPrice = $product->discount > 0
        ? $originalPrice * (1 - $product->discount/100)
        : $originalPrice;
        $variants = $product->variants; // Lấy các biến thể từ bảng product_variants

        // Lấy 12 sản phẩm bán chạy cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->orderBy('sales', 'desc')
            ->take(12)
            ->get();

            $comments = Comment::with(['user', 'replies.user'])
            ->where('product_id', $id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(3); // Hoặc ->get() nếu không cần phân trang
        // Truyền dữ liệu qua view
        return view('client.products.main', compact('product', 'relatedProducts', 'discountedPrice', 'originalPrice', 'variants', 'comments'));
    }

    public function comment(Request $request) {
        $requestData = $request->all();
        if ($requestData['comment']) {
            $Auth = Auth::id();
            $data = [
                "comment" => $requestData["comment"],
                "product_id" => $requestData["product_id"],
                "user_id" => $Auth,
                "parent_id" => NULL
            ];
            Comment::create($data);
        }
        return back();
    }
    public function reply(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        $adminId = auth()->id();
        $parentComment = Comment::findOrFail($id);
        Comment::create([
            'user_id' => $adminId,
            'product_id' => $parentComment->product_id,
            'comment' => $request->comment,
            'parent_id' => $id,
        ]);
        return back();
    }
}
