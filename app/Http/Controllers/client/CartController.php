<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::all();
        return view('client.cars.main', compact('carts'));
    }

    public function show($id)
    {
        return view('client.car.show', ['id' => $id]);
    }

    public function addToCart(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.'], 401);
        }

        $cart = new Cart();
        $cart->user_id = auth()->user()->id;
        $cart->product_id = $request->product_id;
        $cart->price = $request->price;
        $cart->quantity = 1; // Default quantity
        $cart->save();

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công!']);
    }

    public function updateQuantity(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if ($cart) {
            $cart->quantity = $request->quantity;
            $cart->save();

            return response()->json([
                'success' => true,
                'quantity' => $cart->quantity
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Cart item not found.']);
    }

    public function delete($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->delete();
            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.']);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.']);
    }
}
