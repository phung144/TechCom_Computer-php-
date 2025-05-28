@extends('client.layout')

@section('main')
<main id="content" role="main" class="cart-page bg-light">
    {{-- Success Notification --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
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

    <!-- Modern Breadcrumb -->
    <div class="bg-white shadow-sm">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h4 mb-0">
                        <i class="fas fa-shopping-cart text-primary me-2"></i>
                        Thanh toán nhanh chóng
                    </h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb" class="justify-content-end">
                        <ol class="breadcrumb mb-0 bg-transparent justify-content-end">
                            <li class="breadcrumb-item"><a href="{{ route('client-home') }}" class="text-decoration-none">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Đặt hàng ngay bây giờ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <!-- Order Summary Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <span class="badge bg-primary rounded-pill me-2">1</span>
                                Item in your order
                            </h5>
                            <a href="{{ route('client-home') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i>Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 100px;">Ảnh</th>
                                        <th style="min-width: 200px;">Sản phẩm</th>
                                        <th>Variant</th>
                                        <th class="text-center" style="width: 120px;">Giá</th>
                                        <th class="text-center" style="width: 120px;">Số lượng</th>
                                        <th class="text-center" style="width: 120px;">Tổng cộng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($product))
                                    <tr>
                                        <td class="text-center">
                                            <div class="product-thumbnail mx-auto">
                                                <img src="{{ asset(Storage::url($product->image)) }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-fluid rounded border"
                                                     style="max-height: 80px;">
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-1 fw-semibold">{{ $product->name }}</h6>
                                            <small class="text-muted d-block">SKU: {{ $product->sku }}</small>
                                        </td>
                                        <td>
                                            @if(isset($variant))
                                                <div class="variant-options">
                                                    <span class="badge bg-light text-dark border">{{ $variant->combination_code }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Chưa chọn biến thể nào.</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold">
                                                @if(isset($variant))
                                                    {{ number_format($variant->price, 0) }}
                                                @else
                                                    {{ number_format($product->price, 0) }}
                                                @endif
                                                VND
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="input-group quantity-control" style="max-width: 120px;">
                                                    <input type="text" class="form-control text-center"
                                                           value="{{ $quantity }}" readonly>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold text-primary">
                                            @php
                                                $price = isset($variant) ? $variant->price : $product->price;
                                                $total = $price * $quantity;
                                            @endphp
                                            {{ number_format($total, 0) }} VND
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot class="table-group-divider">
                                    <tr>
                                        <th colspan="5" class="text-end">Tổng phụ:</th>
                                        <td class="text-center">
                                            @if(isset($product))
                                                @php
                                                    $price = isset($variant) ? $variant->price : $product->price;
                                                    $total = $price * $quantity;
                                                @endphp
                                                <strong id="cart-total" class="text-primary">{{ number_format($total, 0) }} VND</strong>
                                            @else
                                                <strong id="cart-total" class="text-primary">0 VND</strong>
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Billing Details Card -->
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle text-primary me-2"></i>
                          Thông tin thanh toán
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="order-form" action="{{ route('orderNow.store', [
                            'product_id' => $product->id,
                            'variant_id' => $variant->id ?? null,
                            'quantity' => $quantity
                        ]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Your name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" id="address" name="address" class="form-control" placeholder="Your address" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Your phone number">
                            </div>
                            <div class="mb-4">
                                <label for="notes" class="form-label">Ghi chú đơn hàngs</label>
                                <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Special instructions..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mb-3">
                                <i class="fas fa-shopping-bag me-2"></i>Đặt hàng ngay bây giờ
                            </button>

                            <div class="text-center">
                                <small class="text-muted">Bằng cách đặt hàng, bạn đồng ý với Điều khoản & Điều kiện của chúng tôi. <a href="#" class="text-decoration-none">Terms of Service</a></small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Modern Cart Page Styling */
    .cart-page {
        min-height: calc(100vh - 120px);
    }

    .card {
        border-radius: 10px;
        overflow: hidden;
        border: none;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .quantity-control .btn {
        padding: 0.375rem 0.75rem;
    }

    .quantity-control .form-control {
        padding: 0.375rem;
        text-align: center;
    }

    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #495057;
    }

    .table td {
        vertical-align: middle;
    }

    .sticky-top {
        z-index: 10;
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

    @media (max-width: 991.98px) {
        .sticky-top {
            position: relative !important;
        }
    }

    /* Smooth transitions */
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        document.getElementById('order-form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields',
                    timer: 2000,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            }
        });
    });
</script>
@endsection
