<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Variant;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::all()->where('user_id', auth()->id());
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
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Lấy thông tin sản phẩm và biến thể
        $product = Product::findOrFail($request->product_id);
        $productVariant = ProductVariant::findOrFail($request->variant_id);

        // Tính giá sau giảm giá
        $finalPrice = $productVariant->price; // Giá gốc của biến thể

        if ($product->discount_value > 0) {
            if ($product->discount_type === 'percentage') {
                $finalPrice = $productVariant->price * (1 - $product->discount_value / 100);
            } else {
                $finalPrice = $productVariant->price - $product->discount_value;
            }
        }

        // Lưu vào giỏ hàng với giá đã giảm
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'quantity' => $request->quantity,
            'price' => $finalPrice, // Giá sau giảm giá
        ]);

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng! 🛒');
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
