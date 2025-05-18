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
                                        class="text-decoration-none text-primary"><i class="fas fa-home me-1"></i> Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
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
                <h1 class="display-5 fw-bold text-gradient-primary">Your Shopping Cart</h1>
                <p class="lead text-muted">Review your items before checkout</p>
                <div class="divider mx-auto"></div>
            </div>

            <!-- Enhanced Cart Table -->
            <div class="card shadow-sm mb-5 border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light-primary">
                                <tr>
                                    <th class="text-center" style="width: 80px;">Remove</th>
                                    <th class="text-center" style="width: 100px;">Image</th>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th class="text-center" style="width: 120px;">Price</th>
                                    <th class="text-center" style="width: 150px;">Quantity</th>
                                    <th class="text-center" style="width: 120px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carts as $cart)
                                    <tr data-cart-id="{{ $cart->id }}" class="align-middle">
                                        <td class="text-center">
                                            <form class="delete-cart-form" action="{{ route('cart.delete', $cart->id) }}"
                                                method="POST" data-cart-id="{{ $cart->id }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle">
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
                                            <small class="text-muted">SKU: {{ $cart->product->sku }}</small>
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
                                                <span class="text-muted">No variant</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-primary">{{ number_format($cart->price, 0) }}
                                                VND</span>
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
                                            data-item-total="{{ $cart->price * $cart->quantity }}">
                                            {{ number_format($cart->price * $cart->quantity, 0) }} VND
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <th colspan="6" class="text-end">Subtotal:</th>
                                    @php
                                        $total = $carts->sum(function ($cart) {
                                            return $cart->price * $cart->quantity;
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
                            <i class="fas fa-tag me-2"></i>Available Vouchers
                        </h3>
                        <small class="text-muted">Select a voucher to apply discount</small>
                    </div>

                    <div class="row g-3">
                        @foreach ($vouchers as $voucher)
                            <div class="col-md-6">
                                <div class="voucher-card card mb-3 border-0 {{ $voucher->min_order_value > $subtotal ? 'bg-light text-muted' : 'shadow-sm' }}"
                                    data-voucher-id="{{ $voucher->id }}"
                                    data-discount-type="{{ $voucher->discount_type }}"
                                    data-discount-value="{{ $voucher->discount_value }}"
                                    data-min-order="{{ $voucher->min_order_value }}" onclick="applyVoucher(this)">
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
                                <strong id="selected-voucher-code"></strong> applied:
                                <span id="selected-voucher-discount"></span>
                            </div>
                            <button onclick="removeVoucher()" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i> Remove
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
                                    <i class="fas fa-address-card me-2"></i>Billing Details
                                </h3>
                                <small class="text-muted">All fields marked with * are required</small>
                            </div>
                            <form id="order-form" action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" id="full_name" name="full_name" class="form-control"
                                                placeholder="Jack Wayley" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Address <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" id="address" name="address" class="form-control"
                                                placeholder="470 Lucy Forks, London" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="jackwayley@gmail.com" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                placeholder="+1 (062) 109-9222">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="notes" class="form-label">Order Notes (Optional)</label>
                                        <textarea id="notes" name="notes" class="form-control" rows="3"
                                            placeholder="Special instructions for your order..."></textarea>
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
                                <i class="fas fa-receipt me-2"></i>Order Summary
                            </h3>

                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <span>Subtotal</span>
                                    <strong id="subtotal">{{ number_format($subtotal, 0) }} VND</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center d-none"
                                    id="discount-row">
                                    <span id="discount-label">Discount</span>
                                    <strong class="text-danger" id="discount-value"></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <span>Shipping</span>
                                    <span class="badge bg-success">FREE</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-5">
                                    <span>Total</span>
                                    {{-- Giá trị này sẽ được cập nhật bằng JS khi chọn voucher --}}
                                    <span class="text-primary" id="cart-total">
                                        {{ number_format($subtotal, 0) }} VND
                                    </span>
                                </li>
                            </ul>

                            <button type="submit" form="order-form" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="fas fa-shopping-bag me-2"></i> Place Order
                            </button>

                            <div class="mt-3 text-center">
                                <small class="text-muted">By placing your order, you agree to our <a href="#">Terms
                                        of Service</a></small>
                            </div>

                            <form action="{{ route('momo.payment') }}" method="post">
                                @csrf
                                <input type="hidden" name="subtotal" value="{{ $subtotal }}" >
                                <button type="submit" class="btn btn-default check_out" name="payUrl">Thanh
                                    toán MOMO</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Custom CSS for better visual */
        .cart-page {
            padding-bottom: 3rem;
            background-color: #f8fafc;
        }

        .text-gradient-primary {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .divider {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
            margin: 15px auto;
        }

        .bg-light-primary {
            background-color: rgba(75, 108, 183, 0.1);
        }

        .product-thumbnail {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 8px;
            padding: 5px;
        }

        .quantity-control {
            max-width: 120px;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #4b6cb7;
        }

        .table tfoot th {
            font-size: 1rem;
        }

        .variant-options {
            line-height: 1.3;
        }

        .voucher-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            border: none;
        }

        .voucher-card:hover:not(.bg-light) {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .voucher-card.bg-light {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .card {
            border-radius: 12px;
            border: none;
        }

        .input-group-text {
            background-color: #f8f9fa;
        }

        @media (max-width: 767.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .sticky-top {
                position: relative !important;
            }
        }

        /* Quantity control buttons */
        .js-quantity-decrement,
        .js-quantity-increment {
            width: 36px;
        }

        .js-quantity-input {
            width: 50px;
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
                // Sửa lại: phân biệt percent và fixed
                type: element.dataset.discountType, // 'percent' hoặc 'fixed'
                value: parseFloat(element.dataset.discountValue),
                minOrder: minOrder
            };

            // Tính toán giảm giá
            if (currentVoucher.type === 'percent') {
                discountAmount = subtotal * (currentVoucher.value / 100);
            } else {
                discountAmount = Math.min(currentVoucher.value, subtotal);
            }

            // Hiển thị thông tin voucher đã chọn
            document.getElementById('selected-voucher-code').textContent = currentVoucher.code;

            let discountText = '';
            if (currentVoucher.type === 'percent') {
                discountText = `${currentVoucher.value}% off (${discountAmount.toLocaleString()} VND)`;
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

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const cartId = this.dataset.cartId;
                    if (confirm('Are you sure you want to remove this product?')) {
                        fetch(this.action, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    cart_id: cartId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.querySelector(`tr[data-cart-id="${cartId}"]`)
                                        .remove();
                                    updateCartTotal();
                                } else {
                                    alert(data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            });

            function updateCartTotal() {
                let total = 0;
                document.querySelectorAll('.item-total').forEach(el => {
                    total += parseFloat(el.dataset.itemTotal);
                });
                document.getElementById('cart-total').textContent = total.toLocaleString() + ' VND';
            }
        });
    </script>
@endsection
