<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderNowController extends Controller
{
    public function __construct()
{
    $this->middleware('auth')->except(['loginRedirect']);
}
    public function index(Request $request)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $productId = $request->input('product_id');
    $variantId = $request->input('variant_id');
    $quantity = $request->input('quantity', 1);

    // Lưu ID vào session thay vì cả object
    session([
        'order_now_product_id' => $productId,
        'order_now_variant_id' => $variantId,
        'order_now_quantity' => $quantity
    ]);

    // Lấy thông tin để hiển thị
    $product = Product::findOrFail($productId);
    $variant = $variantId ? ProductVariant::find($variantId) : null;

    return view('client.orderNows.main', [
        'product' => $product,
        'variant' => $variant,
        'quantity' => $quantity
    ]);
}

public function store(Request $request)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    // Validate thông tin khách hàng
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
    ]);

    // Lấy thông tin sản phẩm từ URL (GET parameters)
    $productId = $request->input('product_id');
    $variantId = $request->input('variant_id');
    $quantity = $request->input('quantity', 1); // Mặc định là 1 nếu không có

    // Kiểm tra sản phẩm
    $product = Product::find($productId);
    if (!$product) {
        return redirect()->route('client-home')->with('error', 'Sản phẩm không tồn tại');
    }

    // Tính toán giá
    $variant = $variantId ? ProductVariant::find($variantId) : null;
    $price = $variant ? $variant->price : $product->price;
    $total = $price * $quantity;

    // Tạo đơn hàng
    $order = Order::create([
        'user_id' => Auth::id(),
        'total' => $total,
        'full_name' => $validated['full_name'],
        'email' => $validated['email'],
        'address' => $validated['address'],
        'phone' => $validated['phone'],
        'status' => 'pending',
        'payment_method' => 'cod',
    ]);

    // Tạo chi tiết đơn hàng
    OrderDetail::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'variant_id' => $variant ? $variant->id : null,
        'quantity' => $quantity,
        'price' => $price,
    ]);

    return redirect()->route('orders.show', $order->id)
               ->with('success', 'Đặt hàng thành công!');
}

    public function success($orderId)
    {
        $order = Order::with(['orderDetails.product', 'orderDetails.variant.options'])
                    ->findOrFail($orderId);

        return view('client.order-success', compact('order'));
    }
}
