<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Variant;

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

    // Trong CartController.php
public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'variant_id' => 'required|exists:product_variants,id', // Sửa lại bảng
        'quantity' => 'required|integer|min:1',
    ]);

    // Sửa thành ProductVariant
    $productVariant = ProductVariant::findOrFail($request->variant_id);

    Cart::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'variant_id' => $request->variant_id,
        'quantity' => $request->quantity,
        'price' => $productVariant->price, // Lấy giá từ ProductVariant
    ]);

    return redirect()->back()->with('success', '🛒 Sản phẩm đã được thêm vào giỏ hàng!');
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
