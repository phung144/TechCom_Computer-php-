<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order; // Đảm bảo model Order được import
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
{
    $userId = auth()->id();
    $orders = Order::with([
        'orderDetails.product',
        'orderDetails.variant.options.variant' // Eager load variant và options
    ])
    ->where('user_id', $userId)
    ->orderBy('created_at', 'desc')
    ->get();

    return view('client.orders.main', compact('orders'));
}

    public function store(Request $request)
{
    $userId = auth()->id();
    // Sử dụng model Cart thay vì DB::table để có thể sử dụng relationships
    $carts = Cart::with(['product', 'variant'])->where('user_id', $userId)->get();

    if ($carts->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    $total = $carts->sum(function($cart) {
        return $cart->price * $cart->quantity;
    });

    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id' => $userId,
            'total' => $total,
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'status' => 'pending',
            'payment_method' => 'cash_on_delivery',
        ]);

        foreach ($carts as $cart) {
            $order->orderDetails()->create([
                'product_id' => $cart->product_id,
                'variant_id' => $cart->variant_id, // Thêm variant_id vào đây
                'quantity' => $cart->quantity,
                'price' => $cart->price,
            ]);
        }

        // Sử dụng model Cart để xóa
        Cart::where('user_id', $userId)->delete();

        DB::commit();
        return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to place order. Please try again.');
    }
}

public function show($id)
{
    $userId = auth()->id();
    $order = Order::with([
        'orderDetails.product',
        'orderDetails.variant.options.variant',
        'user'
    ])->where('id', $id)
      ->where('user_id', $userId)
      ->firstOrFail();

    return view('client.orders.showOrder', compact('order'));
}

    public function destroy($id)
    {
        $userId = auth()->id();
        $order = Order::where('id', $id)->where('user_id', $userId)->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        if ($order->status === 'canceled') {
            return redirect()->route('orders.index')->with('error', 'Order is already canceled.');
        }

        $order->update(['status' => 'canceled']);

        return redirect()->route('orders.index')->with('success', 'Order has been canceled.');
    }

    public function forceDeleteOrder($id)
{
    $userId = auth()->id();
    $order = Order::where('id', $id)
                ->where('user_id', $userId)
                ->whereIn('status', ['completed', 'canceled'])
                ->first();

    if (!$order) {
        return redirect()->route('orders.index')->with('error', 'Order cannot be deleted or not found.');
    }

    DB::beginTransaction();
    try {
        // Xóa các orderDetails trước
        $order->orderDetails()->delete();
        // Xóa order
        $order->delete();

        DB::commit();
        return redirect()->route('orders.index')->with('success', 'Order has been permanently deleted.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('orders.index')->with('error', 'Failed to delete order. Please try again.');
    }
}
}
