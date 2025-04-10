<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // Đảm bảo model Order được import
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $orders = Order::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('client.orders.main', compact('orders'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        $carts = DB::table('carts')->where('user_id', $userId)->get();

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
                'payment_method' => 'cash_on_delivery', // Default payment method

            ]);

            foreach ($carts as $cart) {
                $order->orderDetails()->create([
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price,
                ]);
            }

            DB::table('carts')->where('user_id', $userId)->delete();

            DB::commit();
            return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
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
}
