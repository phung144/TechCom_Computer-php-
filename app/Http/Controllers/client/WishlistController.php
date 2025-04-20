<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    // Hiển thị danh sách sản phẩm yêu thích của người dùng
    // WishlistController.php
public function index()
{
    $wishlists = Wishlist::with(['product', 'variant.options.variant'])
        ->where('user_id', auth()->id())
        ->get();

    return view('client.wishlist.main', compact('wishlists'));
}

    // Hiển thị chi tiết một sản phẩm trong danh sách yêu thích
    public function show($id)
    {
        return view('client.wishlist.show', ['id' => $id]);
    }

    // Thêm sản phẩm vào danh sách yêu thích
    public function addToWishlist(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'sometimes|integer|min:1',
    ]);

    if (!auth()->check()) {
        return redirect()->route('login')->with('error', '🔒 Vui lòng đăng nhập để sử dụng tính năng này');
    }

    $existing = Wishlist::where('user_id', auth()->id())
                       ->where('product_id', $request->product_id)
                       ->first();

    if ($existing) {
        return back()->with('info', 'ℹ Sản phẩm đã có trong wishlist');
    }

    Wishlist::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'quantity' => $request->quantity ?? 1,
    ]);

    return back()->with('success', '✅ Đã thêm vào wishlist thành công!');
}

    // Cập nhật số lượng sản phẩm trong danh sách yêu thích
    public function updateQuantity(Request $request)
    {
        $wishlistItem = Wishlist::find($request->cart_id);
        if ($wishlistItem) {
            $wishlistItem->quantity = $request->quantity;
            $wishlistItem->save();

            return response()->json([
                'success' => true,
                'quantity' => $wishlistItem->quantity
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm.']);
    }

    // Xoá sản phẩm khỏi danh sách yêu thích
    public function delete($id)
    {
        $wishlistItem = Wishlist::find($id);
        if ($wishlistItem) {
            $wishlistItem->delete();

        }
        return redirect()->back()->with('success', '✅ Đã xóa sản phẩm khỏi wishlist thành công!');
    }
}
