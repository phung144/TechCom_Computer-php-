@extends('client.layout')

@section('head')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endsection

@section('main')
<div class="container my-5">
    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h2 class="mb-4 text-center fw-bold" style="color: #2c3e50; position: relative;">
        <span style="background: linear-gradient(135deg, #3498db, #8e44ad); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            🛍️ Your Order History
        </span>
        <div style="height: 3px; background: linear-gradient(90deg, #3498db, #8e44ad); width: 100px; margin: 10px auto;"></div>
    </h2>

    @auth
        @if($orders->isEmpty())
            <div class="empty-order text-center py-5">
                <div class="empty-icon mb-3" style="font-size: 3rem; color: #95a5a6;">
                    <i class="fas fa-box-open"></i>
                </div>
                <h4 class="mb-3" style="color: #7f8c8d;">Your order list is empty</h4>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-shopping-bag me-2"></i> Start Shopping Now
                </a>
            </div>
        @else
            <div class="order-list">
                @foreach($orders as $order)
                <div class="order-card mb-4 rounded-3 overflow-hidden border-0 shadow-sm position-relative">
                    <div class="order-ribbon position-absolute" style="background: linear-gradient(135deg, #3498db, #8e44ad);"></div>

                    <div class="order-header p-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="order-id fw-bold fs-5" style="color: #2c3e50;">ORDER #{{ $order->id }}</span>
                                <span class="ms-3 order-date" style="color: #7f8c8d;">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $order->created_at->format('d M, Y - H:i') }}
                                </span>
                            </div>
                            <div>
                                @php
                                    $statusColor = match($order->status) {
                                        'pending' => ['bg' => 'bg-warning', 'icon' => 'fas fa-clock'],
                                        'completed' => ['bg' => 'bg-success', 'icon' => 'fas fa-check-circle'],
                                        'canceled' => ['bg' => 'bg-secondary', 'icon' => 'fas fa-times-circle'],
                                        default => ['bg' => 'bg-light', 'icon' => 'fas fa-info-circle']
                                    };
                                @endphp
                                <span class="badge {{ $statusColor['bg'] }} text-white rounded-pill px-3 py-2 text-capitalize">
                                    <i class="{{ $statusColor['icon'] }} me-1"></i> {{ $order->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="order-body p-4">
                        @foreach($order->orderDetails as $detail)
                        <div class="order-item d-flex mb-4 pb-4" style="border-bottom: 1px dashed #e0e0e0;">
                            <div class="product-image me-4 position-relative">
                                <img src="{{ Storage::url($detail->product->image ?? 'default-product-image.jpg') }}"
                                     alt="{{ $detail->product->name }}"
                                     class="img-fluid rounded-3 shadow-sm p-4" style="width: 100px; height: 100px; object-fit: cover;">
                                <span class="product-quantity position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $detail->quantity }}
                                </span>
                            </div>
                            <div class="product-details flex-grow-1 ml-3">
                                <h5 class="product-name mb-2 fw-bold" style="color: #2c3e50;">{{ $detail->product->name }}</h5>

                                @if($detail->variant && $detail->variant->options->isNotEmpty())
                                <div class="variant-options mb-3 d-flex flex-wrap gap-2">
                                    @foreach($detail->variant->options as $option)
                                    <span class="variant-badge px-3 py-1 rounded-pill shadow-sm"
                                          style="background: linear-gradient(135deg, #f1f1f1, #ffffff); border: 1px solid #e0e0e0;">
                                        <span class="option-name fw-semibold" style="color: #3498db;">{{ $option->variant->name ?? 'Option' }}:</span>
                                        <span class="option-value" style="color: #e74c3c;">{{ $option->value }}</span>
                                    </span>
                                    @endforeach
                                </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="product-price" style="font-size: 1.1rem;">
                                        <span class="text-muted me-2">Unit Price:</span>
                                        <span class="fw-bold" style="color: #e74c3c;">${{ number_format($detail->price, 2) }}</span>
                                    </div>
                                    <div class="item-total fw-bold fs-5" style="color: #27ae60;">
                                        ${{ number_format($detail->price * $detail->quantity, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="order-footer p-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="shipping-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="shipping-icon me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3498db, #8e44ad); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold ml-3" style="color: #2c3e50;">Shipping Address</h6>
                                            <p class="mb-0 ml-3" style="color: #7f8c8d;">{{ $order->address }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="contact-icon me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #2ecc71, #3498db); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold ml-3" style="color: #2c3e50;">Contact Number</h6>
                                            <p class="mb-0 ml-3" style="color: #7f8c8d;">{{ $order->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column align-items-md-end">
                                <div class="order-summary mb-3 text-end">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="me-3" style="color: #7f8c8d;">Subtotal:</span>
                                        <span>{{ number_format($order->total) }} VND</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="me-3" style="color: #7f8c8d;">Shipping:</span>
                                        <span>Free</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold fs-4 mt-2">
                                        <span class="me-3" style="color: #2c3e50;">Total:</span>
                                        <span style="color: #27ae60;">{{ number_format($order->total) }} VND</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="order-actions d-flex gap-2 mt-3 mr-2">
                                        @if($order->status !== 'canceled')
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2 shadow-sm"
                                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                                <i class="fas fa-times-circle me-1"></i> Cancel Order
                                            </button>
                                        </form>
                                        @endif

                                        @if(in_array($order->status, ['completed', 'canceled']))
                                        <form action="{{ route('orders.forceDelete', $order->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-dark rounded-pill px-4 py-2 shadow-sm mr-3"
                                                    onclick="return confirm('Are you sure you want to permanently delete this order?')">
                                                <i class="fas fa-trash-alt me-1"></i> Delete Order
                                            </button>
                                        </form>
                                        @endif


                                    </div>

                                    <div>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm"
                                            style="background: linear-gradient(135deg, #3498db, #8e44ad); border: none;">
                                             <i class="fas fa-eye me-1"></i> View Details
                                         </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="guest-message text-center py-5">
            <div class="guest-icon mb-4" style="font-size: 4rem; color: #95a5a6;">
                <i class="fas fa-user-lock"></i>
            </div>
            <h3 class="mb-4" style="color: #7f8c8d;">Access Your Order History</h3>
            <p class="mb-4" style="color: #95a5a6; max-width: 500px; margin: 0 auto;">
                Sign in to view your orders, track shipments, and manage your purchases.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm"
               style="background: linear-gradient(135deg, #3498db, #8e44ad); border: none;">
                <i class="fas fa-sign-in-alt me-2"></i> Login Now
            </a>
        </div>
    @endauth
</div>
@endsection

<style>

.btn-outline-dark {
    border-color: #2c3e50;
    color: #2c3e50;
}

.btn-outline-dark:hover {
    background-color: #2c3e50;
    color: white;
}
    .order-card {
    transition: all 0.3s ease;
    border: none !important;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.order-ribbon {
    width: 5px;
    height: 100%;
    left: 0;
    top: 0;
}

.order-header {
    position: relative;
    overflow: hidden;
}

.order-header::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,0,0,0.1), transparent);
}

.order-id {
    position: relative;
    padding-left: 15px;
}

.order-id::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background: #3498db;
    border-radius: 50%;
}

.variant-badge {
    transition: all 0.2s ease;
}

.variant-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product-quantity {
    font-size: 0.7rem;
}

.order-footer {
    border-top: 1px solid rgba(0,0,0,0.05);
}

.empty-order {
    background: #f8f9fa;
    border-radius: 10px;
    max-width: 600px;
    margin: 0 auto;
    padding: 40px 20px;
}

.guest-message {
    background: #f8f9fa;
    border-radius: 10px;
    max-width: 600px;
    margin: 0 auto;
    padding: 40px 20px;
}

@media (max-width: 768px) {
    .order-item {
        flex-direction: column;
    }

    .product-image {
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .variant-options {
        justify-content: center !important;
    }

    .order-actions {
        flex-direction: column;
        width: 100%;
    }

    .order-actions .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
