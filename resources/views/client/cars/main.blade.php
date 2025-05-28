@extends('client.layout')

@section('main')
    <main id="content" role="main" class="cart-page">
        {{-- Thông báo add to cart thành công --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    position: 'top-end',
                    toast: true,
                    background: '#f8f9fa',
                    iconColor: '#28a745'
                });
            </script>
        @endif

        <!-- Enhanced Breadcrumb -->
        <div class="bg-light py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('client-home') }}"
                                        class="text-decoration-none text-primary"><i class="fas fa-home me-1"></i> Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-primary rounded-pill">{{ $carts->count() }} items</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-gradient-primary">Giỏ hàng của bạn</h1>
                <p class="lead text-muted">Xem lại các mặt hàng của bạn trước khi thanh toán.</p>
                <div class="divider mx-auto"></div>
            </div>

            <!-- Enhanced Cart Table -->
            <div class="card shadow-sm mb-5 border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light-primary">
                                <tr>
                                    <th class="text-center" style="width: 80px;">Xoá</th>
                                    <th class="text-center" style="width: 100px;">Ảnh</th>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th class="text-center" style="width: 120px;">Giá</th>
                                    <th class="text-center" style="width: 150px;">Số lượng</th>
                                    <th class="text-center" style="width: 120px;">Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carts as $cart)
                                    @php
                                        $isVariant = $cart->variant ? true : false;
                                        $basePrice = $isVariant ? $cart->variant->price : $cart->product->price;
                                        $hasDiscount = $cart->product->discount_type && $cart->product->discount_value > 0;
                                        $discountedPrice = $basePrice;
                                        if ($hasDiscount) {
                                            if ($cart->product->discount_type === 'percent' || $cart->product->discount_type === 'percentage') {
                                                $discountedPrice = $basePrice * (1 - $cart->product->discount_value / 100);
                                            } else {
                                                $discountedPrice = $basePrice - $cart->product->discount_value;
                                            }
                                        }
                                    @endphp
                                    <tr data-cart-id="{{ $cart->id }}" class="align-middle">
                                        <td class="text-center">
                                            <form class="delete-cart-form" action="{{ route('cart.delete', $cart->id) }}"
                                                method="POST" data-cart-id="{{ $cart->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle delete-cart-btn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <div class="product-thumbnail mx-auto">
                                                <img src="{{ asset(Storage::url($cart->product->image)) }}"
                                                    alt="{{ $cart->product->name }}" class="img-fluid rounded border"
                                                    style="max-height: 80px;">
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-1 fw-semibold">{{ $cart->product->name }}</h6>
                                        </td>
                                        <td>
                                            @if ($cart->variant)
                                                <div class="variant-options">
                                                    @foreach ($cart->variant->options as $option)
                                                        <span
                                                            class="badge bg-light text-dark border me-1">{{ $option->value }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Không có biến thể.</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-primary">
                                                @if ($hasDiscount)
                                                    <span class="text-decoration-line-through text-muted me-1">
                                                        {{ number_format($basePrice, 0) }} VND
                                                    </span>
                                                    <span class="fw-bold text-danger">
                                                        {{ number_format($discountedPrice, 0) }} VND
                                                    </span>
                                                @else
                                                    {{ number_format($basePrice, 0) }} VND
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="input-group quantity-control" style="max-width: 120px;">
                                                    <input type="text" class="form-control text-center js-quantity-input"
                                                        value="{{ $cart->quantity }}" data-cart-id="{{ $cart->id }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold item-total text-primary"
                                            data-item-total="{{ $discountedPrice * $cart->quantity }}">
                                            @if ($hasDiscount)
                                                <span class="text-decoration-line-through text-muted me-1">
                                                    {{ number_format($basePrice * $cart->quantity, 0) }} VND
                                                </span>
                                                <span class="fw-bold text-danger">
                                                    {{ number_format($discountedPrice * $cart->quantity, 0) }} VND
                                                </span>
                                            @else
                                                {{ number_format($basePrice * $cart->quantity, 0) }} VND
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <th colspan="6" class="text-end">Tổng phụ:</th>
                                    @php
                                        $total = $carts->sum(function ($cart) {
                                            $isVariant = $cart->variant ? true : false;
                                            $basePrice = $isVariant ? $cart->variant->price : $cart->product->price;
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
                                    @endphp
                                    <td class="text-center">
                                        <strong id="cart-subtotal" class="text-primary">{{ number_format($total, 0) }}
                                            VND</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Enhanced Voucher Selection Section -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h4 mb-0 text-gradient-primary">
                            <i class="fas fa-tag me-2"></i>Phiếu giảm giá có sẵn
                        </h3>
                        <small class="text-muted">Chọn một phiếu giảm giá để áp dụng giảm giá.</small>
                    </div>

                    <div class="row g-3">
                        @foreach ($vouchers as $voucher)
                            <div class="col-md-6">
                                <div class="voucher-card card mb-3 border-0 {{ $voucher->min_order_value > $subtotal ? 'bg-light text-muted' : 'shadow-sm' }}"
                                    data-voucher-id="{{ $voucher->id }}"
                                    data-discount-type="{{ $voucher->discount_type }}"
                                    data-discount-value="{{ $voucher->discount_value }}"
                                    data-min-order="{{ $voucher->min_order_value }}"
                                    data-max-discount="{{ $voucher->max_discount ?? 0 }}" {{-- thêm max_discount --}}
                                    onclick="applyVoucher(this)">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    <span class="badge bg-success me-2">{{ $voucher->code }}</span>
                                                </h5>
                                                <p class="card-text small mb-1 text-truncate">{{ $voucher->description }}
                                                </p>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-warning text-dark me-2">
                                                        @if ($voucher->discount_type == 'percent')
                                                            {{ $voucher->discount_value }}% OFF
                                                        @else
                                                            {{ number_format($voucher->discount_value) }} VND OFF
                                                        @endif
                                                    </span>
                                                    <small class="text-muted">
                                                        Min order: {{ number_format($voucher->min_order_value) }} VND
                                                        @if (!empty($voucher->max_discount) && $voucher->max_discount > 0)
                                                            <br>
                                                            Max discount: {{ number_format($voucher->max_discount) }} VND
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ \Carbon\Carbon::parse($voucher->end_date)->diffForHumans() }}
                                                </span>
                                                @if ($voucher->min_order_value > $subtotal)
                                                    <div class="mt-2 text-danger small">
                                                        <i class="fas fa-exclamation-circle"></i> Need
                                                        {{ number_format($voucher->min_order_value - $subtotal) }} VND more
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Selected Voucher Display -->
                    <div id="selected-voucher" class="mt-3 d-none">
                        <div class="alert alert-success d-flex justify-content-between align-items-center mb-0">
                            <div>
                                <i class="fas fa-check-circle me-2"></i>
                                <strong id="selected-voucher-code"></strong> đã áp dụng:
                                <span id="selected-voucher-discount"></span>
                            </div>
                            <button onclick="removeVoucher()" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i> Xoá
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Order Summary -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Enhanced Billing Details -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="h4 mb-0 text-gradient-primary">
                                    <i class="fas fa-address-card me-2"></i>Chi tiết thanh toán
                                </h3>
                                <small class="text-muted">Tất cả các trường được đánh dấu bằng * là bắt buộc.</small>
                            </div>
                            <form id="order-form" action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Họ và tên <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" id="full_name" name="full_name" class="form-control"
                                                placeholder="Jack Wayley" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Địa chỉ <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" id="address" name="address" class="form-control"
                                                placeholder="470 Lucy Forks, London" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Địa chỉ email <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="jackwayley@gmail.com" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                placeholder="+1 (062) 109-9222">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="discount_amount" id="discount-amount" value="0">
                                <input type="hidden" name="applied_voucher" id="applied-voucher" value="">
                                <input type="hidden" name="voucher_id" id="applied-voucher-id" value="">
                                <input type="hidden" name="total" id="order-total" value="{{ $subtotal }}">
                                <input type="hidden" name="total_after_discount" id="order-total-after-discount"
                                    value="{{ $subtotal }}">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h3 class="h4 mb-4 text-gradient-primary">
                                <i class="fas fa-receipt me-2"></i>Tóm tắt đơn hàng
                            </h3>

                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <span>Subtotal</span>
                                    <strong id="subtotal">{{ number_format($subtotal, 0) }} VND</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center d-none"
                                    id="discount-row">
                                    <span id="discount-label">Giảm giá</span>
                                    <strong class="text-danger" id="discount-value"></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <span>Shipping</span>
                                    <span class="badge bg-success">MIỄN PHÍ</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-5">
                                    <span>Tổng cộng</span>
                                    {{-- Giá trị này sẽ được cập nhật bằng JS khi chọn voucher --}}
                                    <span class="text-primary" id="cart-total">
                                        {{ number_format($subtotal, 0) }} VND
                                    </span>
                                </li>
                            </ul>

                            <button type="submit" form="order-form" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="fas fa-shopping-bag me-2"></i> Đặt hàng
                            </button>

                            <div class="mt-3 text-center">
                                <small class="text-muted">By placing your order, you agree to our <a href="#">Terms
                                        of Service</a></small>
                            </div>

                            <form action="{{ route('momo.payment') }}" method="post" id="momo-form">
                                @csrf
                                <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                                <input type="hidden" name="total_after_discount" id="momo_total_after_discount" value="{{ $subtotal }}">
                                <input type="hidden" name="full_name" id="momo_full_name">
                                <input type="hidden" name="email" id="momo_email">
                                <input type="hidden" name="address" id="momo_address">
                                <input type="hidden" name="phone" id="momo_phone">
                                <button type="submit" class="btn btn-default check_out" name="payUrl">Thanh toán MOMO</button>
                            </form>
                            <script>
                                // Khi submit form MOMO, lấy dữ liệu từ form order và gán vào input hidden
                                document.getElementById('momo-form').addEventListener('submit', function(e) {
                                    // Cảnh báo: Đơn hàng sẽ không được tạo nếu chỉ submit form này!
                                    // Nên submit form order trước hoặc tạo đơn hàng ở backend khi callback MOMO thành công.
                                    document.getElementById('momo_full_name').value = document.getElementById('full_name').value;
                                    document.getElementById('momo_email').value = document.getElementById('email').value;
                                    document.getElementById('momo_address').value = document.getElementById('address').value;
                                    document.getElementById('momo_phone').value = document.getElementById('phone').value;
                                    // Lấy giá trị mới nhất của total_after_discount từ form chính
                                    document.getElementById('momo_total_after_discount').value = document.getElementById('order-total-after-discount').value;
                                });
                            </script>
                            {{--
                                Lưu ý: Đơn hàng sẽ không được tạo nếu chỉ submit form MOMO!
                                Bạn cần xử lý tạo đơn hàng ở backend khi callback MOMO thành công, hoặc submit form order trước khi thanh toán MOMO.
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
    /* Modern Cart Page Styling */
    .cart-page {
        background-color: #f9fafc;
        padding-bottom: 4rem;
    }

    /* Enhanced Header Section */
    .bg-light.py-3 {
        background-color: #ffffff !important;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .breadcrumb {
        padding: 0.5rem 1rem;
        background-color: transparent;
    }

    .breadcrumb-item a {
        color: #5a6a85;
        transition: color 0.3s;
    }

    .breadcrumb-item a:hover {
        color: #3b82f6;
        text-decoration: none;
    }

    /* Main Title Section */
    .text-center.mb-5 h1 {
        font-size: 2.5rem;
        letter-spacing: -0.5px;
        margin-bottom: 0.5rem;
    }

    .text-center.mb-5 .lead {
        color: #64748b;
        font-weight: 400;
    }

    .divider {
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
        border-radius: 2px;
        margin: 1rem auto;
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Table Styling */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #4b5563;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom-width: 1px;
        padding: 1rem 1.5rem;
    }

    .table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }

    .table tfoot td, .table tfoot th {
        border-top: 2px solid #f1f5f9;
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }

    /* Product Image */
    .product-thumbnail {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        background-color: white;
        padding: 5px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-thumbnail img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    /* Remove Button */
    .btn-outline-danger {
        border-color: #fecaca;
        color: #ef4444;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .btn-outline-danger:hover {
        background-color: #ef4444;
        border-color: #ef4444;
    }

    /* Quantity Input */
    .quantity-control {
        max-width: 120px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .js-quantity-input {
        border-left: none;
        border-right: none;
        text-align: center;
        font-weight: 500;
    }

    /* Price Styling */
    .text-primary {
        color: #3b82f6 !important;
    }

    .fw-bold.text-primary {
        font-weight: 600;
    }

    /* Voucher Cards */
    .voucher-card:not(.bg-light) {
        background-color: white;
        border-left: 4px solid #3b82f6;
    }

    .voucher-card .card-body {
        padding: 1.25rem;
    }

    .voucher-card .badge.bg-success {
        background-color: #10b981 !important;
    }

    .voucher-card .badge.bg-warning {
        background-color: #f59e0b !important;
    }

    .voucher-card .badge.bg-primary {
        background-color: #3b82f6 !important;
    }

    /* Selected Voucher */
    #selected-voucher .alert {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    /* Form Styling */
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .input-group-text {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    /* Order Summary */
    .list-group-item {
        padding: 1rem 0;
        border-color: #f1f5f9;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .bg-light {
        background-color: #f8fafc !important;
    }

    /* Checkout Button */
    .btn-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
        padding: 1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
        transform: translateY(-2px);
    }

    /* MOMO Payment Button */
    .btn-default.check_out {
        background-color: #a50064;
        color: white;
        border: none;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-default.check_out:hover {
        background-color: #7a0048;
        transform: translateY(-2px);
    }

    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .table-responsive {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .table thead {
            display: none;
        }

        .table tbody tr {
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody td {
            padding: 0.5rem 0;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #4b5563;
            margin-right: 1rem;
        }

        .product-thumbnail {
            margin: 0 auto 1rem;
        }
    }

    /* Animation Enhancements */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card, .table, .text-center.mb-5 {
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Hover Effects */
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Text Enhancements */
    .fw-semibold {
        font-weight: 600;
    }

    .text-muted {
        color: #64748b !important;
    }
</style>

    <script>
        // Tính toán tổng giỏ hàng
        const subtotal = {{ $subtotal }};
        let currentVoucher = null;
        let discountAmount = 0;

        function applyVoucher(element) {
            // Kiểm tra voucher có đủ điều kiện không
            const minOrder = parseFloat(element.dataset.minOrder);
            if (minOrder > subtotal) {
                alert(`This voucher requires minimum order of ${minOrder.toLocaleString()} VND`);
                return;
            }

            // Lấy thông tin voucher
            currentVoucher = {
                id: element.dataset.voucherId,
                code: element.querySelector('.card-title').textContent,
                type: element.dataset.discountType, // 'percent' hoặc 'fixed'
                value: parseFloat(element.dataset.discountValue),
                minOrder: minOrder,
                maxDiscount: parseFloat(element.dataset.maxDiscount) || 0 // lấy max_discount
            };

            // Tính toán giảm giá
            if (currentVoucher.type === 'percent') {
                discountAmount = subtotal * (currentVoucher.value / 100);
                if (currentVoucher.maxDiscount > 0) {
                    discountAmount = Math.min(discountAmount, currentVoucher.maxDiscount);
                }
            } else {
                discountAmount = Math.min(currentVoucher.value, subtotal);
            }

            // Hiển thị thông tin voucher đã chọn
            document.getElementById('selected-voucher-code').textContent = currentVoucher.code;

            let discountText = '';
            if (currentVoucher.type === 'percent') {
                discountText = `${currentVoucher.value}% off (${discountAmount.toLocaleString()} VND`;
                if (currentVoucher.maxDiscount > 0) {
                    discountText += `, max ${currentVoucher.maxDiscount.toLocaleString()} VND`;
                }
                discountText += ')';
            } else {
                discountText = `${discountAmount.toLocaleString()} VND off`;
            }
            document.getElementById('selected-voucher-discount').textContent = discountText;

            document.getElementById('selected-voucher').classList.remove('d-none');

            // Cập nhật tổng tiền
            updateOrderSummary();
        }

        function removeVoucher() {
            currentVoucher = null;
            discountAmount = 0;
            document.getElementById('selected-voucher').classList.add('d-none');
            updateOrderSummary();
        }

        function updateOrderSummary() {
            const total = subtotal - discountAmount;

            // Cập nhật UI
            // Sửa lại: phân biệt hiển thị discount là % hay VND
            let discountLabel = '';
            if (currentVoucher && currentVoucher.type === 'percent') {
                discountLabel = `Discount (${currentVoucher.value}%)`;
            } else {
                discountLabel = 'Discount';
            }
            document.getElementById('discount-label').textContent = discountLabel;
            document.getElementById('discount-value').textContent = `-${discountAmount.toLocaleString()} VND`;
            document.getElementById('cart-total').textContent = `${total.toLocaleString()} VND`;

            if (discountAmount > 0) {
                document.getElementById('discount-row').classList.remove('d-none');
            } else {
                document.getElementById('discount-row').classList.add('d-none');
            }

            // Cập nhật hidden inputs cho backend
            if (document.getElementById('applied-voucher')) {
                document.getElementById('applied-voucher').value = currentVoucher ? currentVoucher.code : '';
            }
            if (document.getElementById('applied-voucher-id')) {
                document.getElementById('applied-voucher-id').value = currentVoucher ? currentVoucher.id : '';
            }
            if (document.getElementById('discount-amount')) {
                document.getElementById('discount-amount').value = discountAmount;
            }
            if (document.getElementById('order-total')) {
                document.getElementById('order-total').value = subtotal;
            }
            if (document.getElementById('order-total-after-discount')) {
                document.getElementById('order-total-after-discount').value = total;
            }
        }

        // Lấy danh sách sản phẩm trong cart để kiểm tra giá (chỉ dùng variant_id và base_price)
        function getCartItemsForPriceCheck() {
            const items = [];
            @foreach ($carts as $cart)
                items.push({
                    variant_id: {{ $cart->variant_id }},
                    base_price: {{ $cart->variant ? $cart->variant->price : 0 }},
                    quantity: {{ $cart->quantity }}
                });
            @endforeach
            return items;
        }

        // Kiểm tra giá trước khi submit order/momo
        async function checkPricesBeforeSubmit(e, submitCallback) {
            e.preventDefault();
            try {
                const response = await fetch('{{ route('cart.check-prices') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: getCartItemsForPriceCheck()
                    })
                });
                const data = await response.json();
                if (data.changed && data.changed.length > 0) {
                    let html = '<ul style="text-align:left">';
                    data.changed.forEach(item => {
                        if (item.type === 'price') {
                            html += `<li><b>${item.name}</b>: Giá cũ <span style="color:#888">${item.old_price.toLocaleString()} VND</span> &rarr; Giá mới <span style="color:#e74c3c">${item.new_price.toLocaleString()} VND</span></li>`;
                        } else if (item.type === 'quantity') {
                            html += `<li><b>${item.name}${item.variant ? ' (' + item.variant + ')' : ''}</b>: Chỉ còn <span style="color:#e74c3c">${item.available}</span> sản phẩm, bạn đã chọn <span style="color:#888">${item.requested}</span></li>`;
                        }
                    });
                    html += '</ul>';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Có thay đổi trong giỏ hàng!',
                        html: html + '<br>Vui lòng kiểm tra lại giỏ hàng.',
                        confirmButtonText: 'OK',
                    }).then(() => {
                        window.location.reload();
                    });
                    return false;
                } else {
                    // Không có thay đổi giá hoặc thiếu hàng, tiếp tục submit
                    submitCallback();
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kiểm tra giá sản phẩm. Vui lòng thử lại!', 'error');
            }
        }

        // Hook vào nút Place Order và MOMO
        document.addEventListener('DOMContentLoaded', function() {
            // Sửa lại logic xóa sản phẩm trong cart
            document.querySelectorAll('.delete-cart-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = btn.closest('form');
                    Swal.fire({
                        title: 'Bạn có chắc muốn xóa sản phẩm này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            function updateCartTotal() {
                let total = 0;
                document.querySelectorAll('.item-total').forEach(el => {
                    total += parseFloat(el.dataset.itemTotal);
                });
                document.getElementById('cart-total').textContent = total.toLocaleString() + ' VND';
            }

            // --- Các logic kiểm tra giá trước khi đặt hàng ---
            // Place Order
            const orderForm = document.getElementById('order-form');
            if (orderForm) {
                orderForm.addEventListener('submit', function(e) {
                    checkPricesBeforeSubmit(e, () => orderForm.submit());
                });
            }

            // Thanh toán MOMO
            const momoForm = document.getElementById('momo-form');
            if (momoForm) {
                momoForm.addEventListener('submit', function(e) {
                    checkPricesBeforeSubmit(e, () => momoForm.submit());
                });
            }
        });
    </script>
@endsection
