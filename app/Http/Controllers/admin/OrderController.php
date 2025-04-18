<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $orders = Order::with([
        'orderDetails.product',
        'orderDetails.variant.options'
    ])->get();

    return view('admin.orders.listOrder', compact('orders'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $order = Order::with([
        'orderDetails.product',
        'orderDetails.variant.options.variant'
    ])->findOrFail($id);

    return view('admin.orders.showOrder', compact('order'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        $order->update(['status' => $request->status]);

        // Nếu trạng thái là "Completed", cập nhật sales cho từng sản phẩm
        if ($order->status === 'completed') {
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                $product->sales += $detail->quantity;
                $product->save();
            }
        }

        return redirect()->route('admin.orders.show', $id)->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
