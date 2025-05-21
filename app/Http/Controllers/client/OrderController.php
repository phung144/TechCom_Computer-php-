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

            $discountAmount = floatval($request->input('discount_amount', 0));
            $voucherId = $request->input('voucher_id');
            if (!$voucherId) {
                // fallback nếu chỉ có code
                $voucherCode = $request->input('applied_voucher');
                if ($voucherCode) {
                    $voucher = \App\Models\Voucher::where('code', $voucherCode)->first();
                    if ($voucher) {
                        $voucherId = $voucher->id;
                    }
                }
            }

            $subtotal = floatval($request->input('total', 0));
            $totalAfterDiscount = floatval($request->input('total_after_discount', 0));

            $order = Order::create([
                'user_id' => $userId,
                'total' => $subtotal, // tổng chưa giảm giá
                'total_after_discount' => $totalAfterDiscount, // tổng sau giảm giá
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
                    'variant_options' => $variantOptions,
                    'voucher_id' => $voucherId // Truyền voucher_id vào order_details
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

            // Chuyển hướng tới trang chi tiết đơn hàng vừa đặt
            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');
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
            'cancel_reason' => $cancelReason // Đảm bảo đúng tên trường là cancel_reason
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

    public function momoPayment(Request $request)
    {
        $userId = auth()->id();

        // Lấy giỏ hàng
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

        // Kiểm tra tồn kho
        DB::transaction(function () use ($carts) {
            foreach ($carts as $cart) {
                $available = $cart->variant ? $cart->variant->quantity : $cart->product->quantity;
                if ($available < $cart->quantity) {
                    throw new \Exception("{$cart->product->name} chỉ còn {$available} sản phẩm");
                }
            }
        });

        // Lưu thông tin đơn hàng vào session (chưa tạo đơn hàng)
        $discountAmount = floatval($request->input('discount_amount', 0));
        $voucherId = $request->input('voucher_id');
        if (!$voucherId) {
            $voucherCode = $request->input('applied_voucher');
            if ($voucherCode) {
                $voucher = \App\Models\Voucher::where('code', $voucherCode)->first();
                if ($voucher) {
                    $voucherId = $voucher->id;
                }
            }
        }

        $subtotal = floatval($request->input('subtotal', 0));
        $totalAfterDiscount = floatval($request->input('total_after_discount', $subtotal));

        // Lưu thông tin đơn hàng và giỏ hàng vào session
        session([
            'momo_order_data_' . $userId => [
                'user_id' => $userId,
                'total' => $subtotal,
                'total_after_discount' => $totalAfterDiscount,
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'status' => 'pending',
                'payment_method' => 'momo',
                'payment_status' => 'unpaid',
                'voucher_id' => $voucherId,
                'carts' => $carts->map(function ($cart) {
                    return [
                        'product_id' => $cart->product_id,
                        'variant_id' => $cart->variant_id,
                        'quantity' => $cart->quantity,
                        'price' => $cart->price,
                        'variant_options' => $cart->variant ? $cart->variant->options->pluck('value')->implode(', ') : null,
                    ];
                })->toArray()
            ]
        ]);

        // Tạo request MoMo
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = $totalAfterDiscount;
        $orderId = uniqid('momo_') . '_' . time(); // orderId chỉ để gửi sang MoMo, không phải id đơn hàng thật
        $redirectUrl = route('momo.callback');
        $ipnUrl = route('momo.ipn');
        $extraData = json_encode(['user_id' => $userId]);

        $requestId = time() . "";
        $requestType = "payWithATM";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            // Lưu orderId MoMo vào session để đối chiếu callback nếu cần
            session(['momo_orderId' => $orderId]);
            return redirect()->to($jsonResult['payUrl']);
        } else {
            Log::error('MoMo payment error', [
                'response' => $jsonResult,
                'request_data' => $data
            ]);
            return back()->with('error', 'Không thể tạo liên kết thanh toán MoMo. Vui lòng thử lại hoặc liên hệ hỗ trợ.');
        }
    }

    // Xử lý khi MoMo redirect về (redirectUrl)
    public function momoPaymentCallback(Request $request)
    {
        // Lấy user_id từ extraData
        $extraData = $request->input('extraData');
        $userId = null;
        if ($extraData) {
            $extra = json_decode($extraData, true);
            $userId = $extra['user_id'] ?? null;
        }
        if (!$userId) {
            return redirect()->route('orders.index')->with('error', 'Không xác định được người dùng.');
        }

        // Lấy thông tin đơn hàng tạm từ session theo user_id
        $orderData = session('momo_order_data_' . $userId);
        if (!$orderData) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy thông tin đơn hàng.');
        }

        // Kiểm tra kết quả thanh toán
        $resultCode = $request->input('resultCode');
        if ($resultCode == 0) {
            // Thanh toán thành công, tạo đơn hàng thật sự
            try {
                DB::beginTransaction();
                $order = Order::create([
                    'user_id' => $orderData['user_id'],
                    'total' => $orderData['total'],
                    'total_after_discount' => $orderData['total_after_discount'],
                    'full_name' => $orderData['full_name'],
                    'email' => $orderData['email'],
                    'address' => $orderData['address'],
                    'phone' => $orderData['phone'],
                    'status' => 'pending',
                    'payment_method' => 'momo',
                    'payment_status' => 'paid',
                ]);
                foreach ($orderData['carts'] as $cart) {
                    $order->orderDetails()->create([
                        'product_id' => $cart['product_id'],
                        'variant_id' => $cart['variant_id'],
                        'quantity' => $cart['quantity'],
                        'price' => $cart['price'],
                        'variant_options' => $cart['variant_options'],
                        'voucher_id' => $orderData['voucher_id']
                    ]);
                    // Cập nhật tồn kho
                    if ($cart['variant_id']) {
                        ProductVariant::where('id', $cart['variant_id'])
                            ->decrement('quantity', $cart['quantity']);
                    } else {
                        Product::where('id', $cart['product_id'])
                            ->decrement('quantity', $cart['quantity']);
                    }
                }
                // Xóa giỏ hàng
                Cart::where('user_id', $orderData['user_id'])->delete();
                DB::commit();
                // Xóa session
                session()->forget(['momo_order_data_' . $userId, 'momo_orderId']);
                return redirect()->route('orders.show', $order->id)->with('success', 'Thanh toán thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Order creation after MoMo payment failed', [
                    'user' => $orderData['user_id'],
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'orderData' => $orderData
                ]);
                return redirect()->route('orders.index')->with('error', 'Tạo đơn hàng thất bại sau khi thanh toán MoMo: ' . $e->getMessage());
            }
        } else {
            // Thanh toán thất bại
            session()->forget(['momo_order_data_' . $userId, 'momo_orderId']);
            return redirect()->route('orders.index')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
        }
    }

    // Xử lý ipnUrl (nếu cần, có thể dùng cho xác nhận server-to-server)
    public function momoPaymentIpn(Request $request)
    {
        $orderId = session('momo_order_id');
        if (!$orderId) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $resultCode = $request->input('resultCode');
        if ($resultCode == 0) {
            $order->update(['payment_status' => 'paid']);
            Cart::where('user_id', $order->user_id)->delete();
            session()->forget('momo_order_id');
            return response()->json(['message' => 'Payment success']);
        } else {
            $order->update(['payment_status' => 'unpaid']);
            session()->forget('momo_order_id');
            return response()->json(['message' => 'Payment failed']);
        }
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
}
