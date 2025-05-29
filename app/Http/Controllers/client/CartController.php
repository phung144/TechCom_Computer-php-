<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Voucher;

class CartController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}
    public function index()
    {
        $carts = Cart::where('user_id', auth()->id())->with(['product', 'variant.options'])->get();

    // Tính lại subtotal theo giá đã áp dụng discount của product (áp cho cả variant)
    $subtotal = $carts->sum(function($cart) {
        $basePrice = $cart->variant ? $cart->variant->price : $cart->product->price;
        $hasDiscount = $cart->product->discount_type && $cart->product->discount_value > 0;
        $discountedPrice = $basePrice;
        if ($hasDiscount) {
            if ($cart->product->discount_type === 'percent' || $cart->product->discount_type === 'percentage') {
                $discountedPrice = $basePrice * (1 - $cart->product->discount_value / 100);
            } else {
                $discountedPrice = $basePrice - $cart->product->discount_value;
            }
        }
        return $discountedPrice * $cart->quantity;
    });

    // Lấy tất cả voucher còn hiệu lực
    $vouchers = Voucher::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->where('is_active', 1)
        ->orderBy('discount_value', 'desc')
        ->get();

    return view('client.cars.main', compact('carts', 'subtotal', 'vouchers'));
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

    $product = Product::findOrFail($request->product_id);
    $productVariant = ProductVariant::findOrFail($request->variant_id);

    // Áp dụng discount của product cho cả variant
    $basePrice = $productVariant->price;
    $finalPrice = $basePrice;
    if ($product->discount_type && $product->discount_value > 0) {
        if ($product->discount_type === 'percent' || $product->discount_type === 'percentage') {
            $finalPrice = $basePrice * (1 - $product->discount_value / 100);
        } else {
            $finalPrice = $basePrice - $product->discount_value;
        }
    }

    $existingCartItem = Cart::where('user_id', auth()->id())
        ->where('product_id', $request->product_id)
        ->where('variant_id', $request->variant_id)
        ->first();

    if ($existingCartItem) {
        $existingCartItem->update([
            'quantity' => $existingCartItem->quantity + $request->quantity,
        ]);
    } else {
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'quantity' => $request->quantity,
            'price' => $finalPrice,
        ]);
    }
    if ($request->orderNow == 1) {
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng! 🛒');
    }else{
        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng! 🛒');
    }
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
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        }
        return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng.');
    }

    public function checkPrices(Request $request)
{
    $changed = [];
    foreach ($request->items as $item) {
        if (empty($item['variant_id'])) continue;
        $variant = ProductVariant::with('product')->find($item['variant_id']);
        $basePriceDb = $variant ? $variant->price : 0;
        $oldPrice = floatval($item['base_price']);
        // Kiểm tra thay đổi giá
        if ($basePriceDb != $oldPrice) {
            $changed[] = [
                'type' => 'price',
                'name' => $variant && $variant->product ? $variant->product->name : 'Sản phẩm',
                'old_price' => $oldPrice,
                'new_price' => $basePriceDb
            ];
        }
        // Kiểm tra tồn kho
        if ($variant && isset($item['quantity'])) {
            $quantityDb = $variant->quantity;
            $requestedQty = intval($item['quantity']);
            if ($quantityDb < $requestedQty) {
                $changed[] = [
                    'type' => 'quantity',
                    'name' => $variant->product ? $variant->product->name : 'Sản phẩm',
                    'variant' => $variant->variant ?? '',
                    'available' => $quantityDb,
                    'requested' => $requestedQty
                ];
            }
        }
    }
    return response()->json(['changed' => $changed]);
}


}
