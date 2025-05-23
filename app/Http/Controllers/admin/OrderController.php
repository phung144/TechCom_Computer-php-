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
        'status' => 'required|in:pending,processing,shipping,completed,rated,canceled',
        'cancel_reason' => 'nullable|string|max:255'
    ]);

    $currentStatus = $order->status;
    $newStatus = $request->status;

    $allowedTransitions = [
        'pending'     => ['processing', 'canceled'],
        'processing'  => ['shipping', 'canceled'],
        'shipping'    => ['completed', 'canceled'],
        'completed'   => [],
        'rated'      => [],
        'canceled'   => [],
    ];

    // Nếu chuyển sang canceled, lưu lý do hủy
    if (
        ($currentStatus === 'pending' && $newStatus === 'canceled') ||
        ($currentStatus === 'shipping' && $newStatus === 'canceled')
    ) {
        $order->status = $newStatus;
        $order->cancel_reason = $request->cancel_reason;
        $order->save();
        return redirect()->route('admin.orders.show', $id)->with('success', 'Chuyển trạng thái thành công.');
    }

    if (!in_array($newStatus, $allowedTransitions[$currentStatus])) {
        return back()->with('error', "Không thể chuyển trạng thái từ {$currentStatus} thành {$newStatus}.");
    }

    $order->status = $newStatus;
    // Nếu chuyển sang canceled từ các trạng thái khác (phòng trường hợp mở rộng)
    if ($newStatus === 'canceled') {
        $order->cancel_reason = $request->cancel_reason;
    }
    $order->save();

    if ($newStatus === 'completed') {
        foreach ($order->orderDetails as $detail) {
            $product = $detail->product;
            $product->sales += $detail->quantity;
            $product->save();
        }
    }

    return redirect()->route('admin.orders.show', $id)->with('success', 'Chuyển trạng thái thành công.');
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
