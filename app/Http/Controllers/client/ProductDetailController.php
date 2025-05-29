<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Product; // Import the Product model
use App\Models\Category;
use Illuminate\Support\Facades\Auth;// Import the Category model
use Carbon\Carbon;

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

        // Kiểm tra thời gian giảm giá (sử dụng đúng tên trường discount_start, discount_end)
        $now = Carbon::now();
        $isDiscountActive = false;
        if (
            $product->discount_value > 0 &&
            $product->discount_start &&
            $product->discount_end
        ) {
            $start = $product->discount_start instanceof Carbon ? $product->discount_start : Carbon::parse($product->discount_start);
            $end = $product->discount_end instanceof Carbon ? $product->discount_end : Carbon::parse($product->discount_end)->endOfDay();
            $isDiscountActive = $now->between($start, $end);
        }

        // Tính giá sau discount (chỉ khi đang trong thời gian giảm giá)
        $originalPrice = $product->price ?? ($variants->first()->price ?? 0);
        if ($isDiscountActive) {
            if ($product->discount_type === 'percentage') {
                $discountedPrice = $originalPrice * (1 - $product->discount_value / 100);
            } elseif ($product->discount_type === 'fixed') {
                $discountedPrice = max(0, $originalPrice - $product->discount_value);
            } else {
                $discountedPrice = $originalPrice;
            }
        } else {
            $discountedPrice = $originalPrice;
        }
        $variants = $product->variants; // Lấy các biến thể từ bảng product_variants

        // Lấy 12 sản phẩm bán chạy cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->orderBy('sales', 'desc')
            ->take(12)
            ->get();

        // Lấy các sản phẩm cùng danh mục (trừ sản phẩm hiện tại)
        $categoryProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(12)
            ->get();

            $comments = Comment::with(['user', 'replies.user'])
            ->where('product_id', $id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(3); // Hoặc ->get() nếu không cần phân trang
        // Lấy feedbacks của sản phẩm, phân trang 5 feedback/trang
        $feedbacks = \App\Models\Feedback::with('user')
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Truyền thêm $categoryProducts sang view
        return view('client.products.main', compact(
            'product',
            'relatedProducts',
            'discountedPrice',
            'originalPrice',
            'variants',
            'comments',
            'feedbacks',
            'categoryProducts',
            'isDiscountActive' // truyền biến này sang view
        ));
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
    public function deleteComment($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);
        // Chỉ cho phép xóa nếu là chủ comment hoặc admin
        if (auth()->id() === $comment->user_id) {
            $comment->delete();
            return back()->with('success', 'Xóa bình luận thành công!');
        }
        return back()->with('error', 'Bạn không có quyền xóa bình luận này!');
    }
}
