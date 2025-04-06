<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // Đảm bảo model Order được import
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
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
}
