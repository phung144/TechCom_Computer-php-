<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\Order; // Đảm bảo model Order được import
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
        ]);

        // Lấy giỏ hàng với lock để tránh xung đột
        $carts = Cart::with([
            'product' => function ($q) {
                $q->lockForUpdate();
            },
            'variant' => function ($q) {
                $q->lockForUpdate()->with('options');
            }
        ])
            ->where('user_id', $userId)
            ->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống');
        }

        // Kiểm tra tồn kho với transaction riêng
        DB::transaction(function () use ($carts) {
            foreach ($carts as $cart) {
                $available = $cart->variant ? $cart->variant->quantity : $cart->product->quantity;
                if ($available < $cart->quantity) {
                    throw new \Exception("{$cart->product->name} chỉ còn {$available} sản phẩm");
                }
            }
        });

        // Tạo đơn hàng với transaction chính
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $userId,
                'total' => $carts->sum(fn($cart) => $cart->price * $cart->quantity),
                ...$validated,
                'status' => 'pending',
                'payment_method' => 'cash_on_delivery'
            ]);

            foreach ($carts as $cart) {
                $variantOptions = $cart->variant ?
                    $cart->variant->options->pluck('value')->implode(', ') : null;

                $order->orderDetails()->create([
                    'product_id' => $cart->product_id,
                    'variant_id' => $cart->variant_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price,
                    'variant_options' => $variantOptions
                ]);

                // Cập nhật tồn kho trực tiếp qua query builder
                if ($cart->variant_id) {
                    ProductVariant::where('id', $cart->variant_id)
                        ->decrement('quantity', $cart->quantity);
                } else {
                    Product::where('id', $cart->product_id)
                        ->decrement('quantity', $cart->quantity);
                }
            }

            // Xóa giỏ hàng
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            // Thêm thông báo success
            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order failed', [
                'user' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'carts' => $carts->toArray()
            ]);

            // Thêm thông báo error
            return back()->with('error', 'Order placement failed: ' . $e->getMessage());
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

    public function destroy($id, Request $request)
    {
        $userId = auth()->id();
        $order = Order::where('id', $id)->where('user_id', $userId)->first();

        // Kiểm tra đơn hàng tồn tại
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        // Danh sách trạng thái KHÔNG cho phép hủy
        $protectedStatuses = ['completed', 'rated', 'canceled'];

        // Kiểm tra trạng thái hiện tại
        if (in_array($order->status, $protectedStatuses)) {
            $message = match ($order->status) {
                'completed' => 'Cannot cancel a completed order.',
                'rated'     => 'Cannot cancel an order that has been rated.',
                'canceled'  => 'Order is already canceled.',
                default     => 'Order cannot be canceled in its current state.'
            };
            return redirect()->route('orders.index')->with('error', $message);
        }

        // Lưu lý do hủy đơn hàng
        $cancelReason = $request->input('cancel_reason');
        $order->update([
            'status' => 'canceled',
            'cancel_reason' => $cancelReason
        ]);

        // Thêm thông báo success
        return redirect()->route('orders.index')->with('success', 'Order canceled successfully.');
    }

    public function complete(Request $request, $orderId)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'content' => 'nullable|string|max:500',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    $order = Order::with('orderDetails.product')->findOrFail($orderId);

    // Kiểm tra quyền truy cập
    if ($order->user_id !== auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized action.'
        ], 403);
    }

    // Kiểm tra sản phẩm trong đơn hàng
    $firstProduct = $order->orderDetails->first();
    if (!$firstProduct) {
        return response()->json([
            'success' => false,
            'message' => 'No products in this order.'
        ], 400);
    }

    // Chuẩn bị dữ liệu feedback
    $data = [
        'user_id' => auth()->id(),
        'product_id' => $firstProduct->product_id,
        'order_id' => $orderId,
        'rating' => $request->rating,
        'content' => $request->content,
    ];

    // Xử lý upload ảnh
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('feedback_images', 'public');
        $data['image'] = $imagePath;
    }

    // Tạo feedback
    Feedback::create($data);

    // Cập nhật trạng thái đơn hàng
    $order->update(['status' => 'rated']);

    return response()->json([
        'success' => true,
        'message' => 'Đánh giá đã được gửi thành công!'
    ]);
}

public function skipRating($orderId)
{
    $order = Order::findOrFail($orderId);

    // Kiểm tra quyền truy cập
    if ($order->user_id !== auth()->id()) {
        return back()->with('error', 'Bạn không có quyền thực hiện thao tác này');
    }

    $order->update(['status' => 'rated']);

    return back()->with('success', 'Đã bỏ qua đánh giá');
}


}
