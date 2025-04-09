<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để tạo đơn hàng.');
        }

        $orders = Order::where('user_id', auth()->id())->get();
        return view('client.orders.main', compact('orders'));
    }
}
