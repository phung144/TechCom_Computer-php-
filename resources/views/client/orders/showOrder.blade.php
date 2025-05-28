@extends('client.layout')

@section('head')
<link rel="stylesheet" href="{{ asset('css/order-detail.css') }}">
@endsection

@section('main')
<div class="container py-5">
    <div class="order-detail-container">
        <!-- Header -->
        <div class="order-header text-center mb-5">
            <h1 class="order-title">Order Details</h1>
            <div class="order-meta">
                <span class="order-number">Order #{{ $order->id }}</span>
                <span class="order-date">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
            </div>
        </div>

        <!-- Order Status -->
        <div class="order-status-card mb-5">
            @php
                $statusColor = match($order->status) {
                    'pending' => 'warning',
                    'processing' => 'info',
                    'completed' => 'success',
                    'canceled' => 'danger',
                    default => 'secondary'
                };
            @endphp
            <div class="status-badge bg-{{ $statusColor }}">
                <i class="fas fa-{{ $order->status === 'completed' ? 'check-circle' : ($order->status === 'canceled' ? 'times-circle' : 'clock') }} me-2"></i>
                {{ ucfirst($order->status) }}
            </div>

            @if($order->status === 'completed')
            <div class="delivery-info mt-3">
                <i class="fas fa-truck me-2"></i>
                Estimated delivery: {{ $order->created_at->addDays(3)->format('M d, Y') }}
            </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="order-summary-card mb-5">
            <h3 class="section-title">Order Summary</h3>

            <div class="products-list">
                @foreach($order->orderDetails as $detail)
                <div class="product-item">
                    <div class="product-image">
                        <img src="{{ Storage::url($detail->product->image ?? 'default-product-image.jpg') }}"
                             alt="{{ $detail->product->name }}">
                        <span class="quantity-badge">{{ $detail->quantity }}</span>
                    </div>

                    <div class="product-info">
                        <h4 class="product-name">{{ $detail->product->name }}</h4>

                        @if($detail->variant && $detail->variant->options->isNotEmpty())
                        <div class="variant-options">
                            @foreach($detail->variant->options as $option)
                            <div class="variant-option">
                                <span class="option-name">{{ $option->variant->name }}:</span>
                                <span class="option-value">{{ $option->value }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="product-price">
                            ${{ number_format($detail->price, 2) }} × {{ $detail->quantity }} =
                            <span class="total-price">${{ number_format($detail->price * $detail->quantity, 2) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="shipping-info-card mb-5">
            <h3 class="section-title">Shipping Information</h3>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $order->full_name }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $order->email }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $order->phone }}</div>
                </div>

                <div class="info-item full-width">
                    <div class="info-label">Address</div>
                    <div class="info-value">{{ $order->address }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary-card">
            <h3 class="section-title">Payment Summary</h3>

            <div class="payment-details">
                <div class="payment-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
                <div class="payment-row">
                    <span>Discount</span>
                    <span>
                        -${{ number_format(($order->total - ($order->total_after_discount ?? $order->total)), 2) }}
                    </span>
                </div>
                <div class="payment-row total-row">
                    <span>Total after discount</span>
                    <span>
                        ${{ number_format($order->total_after_discount ?? $order->total, 2) }}
                    </span>
                </div>
                <div class="payment-row method-row">
                    <span>Payment Method</span>
                    <span class="payment-method">
                        <i class="fas fa-{{ $order->payment_method === 'credit_card' ? 'credit-card' : 'money-bill-wave' }} me-2"></i>
                        {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                    </span>
                </div>
                <div class="payment-row">
                    <span>Payment Status</span>
                    <span>
                        @if($order->payment_status === 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="order-actions mt-5">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Orders
            </a>

            @if(in_array($order->status, ['completed', 'canceled']))
            <form action="{{ route('orders.forceDelete', $order->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger ms-2"
                        onclick="return confirm('Are you sure you want to permanently delete this order?')">
                    <i class="fas fa-trash-alt me-2"></i> Delete Order
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

<style>


    .order-detail-container {
    max-width: 800px;
    margin: 0 auto;9704 0000 0000 0018
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.order-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #3498db, #8e44ad);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.order-meta {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.order-meta span:not(:last-child)::after {
    content: "•";
    margin: 0 10px;
}

.order-status-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
}

.status-badge {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    color: white;
    font-size: 1rem;
}

.bg-warning {
    background: #f39c12;
}
.bg-info {
    background: #3498db;
}
.bg-success {
    background: #2ecc71;
}
.bg-danger {
    background: #e74c3c;
}
.bg-secondary {
    background: #95a5a6;
}

.delivery-info {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f2f6;
}

.products-list {
    border: 1px solid #f1f2f6;
    border-radius: 8px;
    overflow: hidden;
}

.product-item {
    display: flex;
    padding: 20px;
    border-bottom: 1px solid #f1f2f6;
}

.product-item:last-child {
    border-bottom: none;
}

.product-image {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    margin-right: 20px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    max-width: 80%;
    max-height: 80%;
    object-fit: contain;
}

.quantity-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #e74c3c;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}

.product-info {
    flex: 1;
}

.product-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.variant-options {
    margin-bottom: 10px;
}

.variant-option {
    display: inline-block;
    margin-right: 10px;
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.option-name {
    color: #7f8c8d;
}

.option-value {
    color: #2c3e50;
    font-weight: 500;
}

.product-price {
    font-size: 0.95rem;
    color: #7f8c8d;
}

.total-price {
    font-weight: 600;
    color: #2c3e50;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.full-width {
    grid-column: 1 / -1;
}

.info-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.info-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    color: #7f8c8d;
    margin-bottom: 5px;
    letter-spacing: 0.5px;
}

.info-value {
    font-weight: 500;
    color: #2c3e50;
}

.payment-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #f1f2f6;
}

.total-row {
    font-weight: 600;
    font-size: 1.1rem;
    padding-top: 15px;
    border-bottom: none;
}

.method-row {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f1f2f6;
    border-bottom: none;
}

.payment-method {
    color: #2ecc71;
    font-weight: 500;
}

.order-actions {
    display: flex;
    justify-content: space-between;
}

@media (max-width: 768px) {
    .order-detail-container {
        padding: 20px;
    }

    .product-item {
        flex-direction: column;
    }

    .product-image {
        margin-bottom: 15px;
        margin-right: 0;
        width: 100%;
        height: auto;
        aspect-ratio: 1/1;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .order-actions {
        flex-direction: column;
        gap: 10px;
    }

    .order-actions .btn {
        width: 100%;
    }
}
</style>
