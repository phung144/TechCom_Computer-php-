@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Orders List</h2>
                </div>
                <div class="card-body">
                    <table id="productsTable" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer Info</th>
                                <th>Product</th> <!-- Thêm cột mới -->
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td class="customer-info-cell">
                                        <div class="customer-info-wrapper">
                                            <div class="customer-avatar">
                                                <div class="avatar-placeholder">
                                                    {{ strtoupper(substr($item->full_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="customer-details">
                                                <div class="customer-name">
                                                    <span>{{ $item->full_name }}</span>
                                                    <span class="customer-id">#{{ $item->id }}</span>
                                                </div>
                                                <div class="customer-meta">
                                                    <div class="meta-item">
                                                        <i class="mdi mdi-phone"></i>
                                                        <a href="tel:{{ $item->phone }}" class="text-muted">{{ $item->phone }}</a>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="mdi mdi-map-marker"></i>
                                                        <span class="text-muted text-truncate" style="max-width: 150px; display: inline-block;" title="{{ $item->address }}">
                                                            {{ $item->address }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="product-info-cell">
                                        @if($item->orderDetails && $item->orderDetails->count() > 0)
                                            @foreach($item->orderDetails as $orderDetail)
                                                <div class="order-product-item">
                                                    <div class="order-product-image">
                                                        @if($orderDetail->product && $orderDetail->product->image)
                                                            <img src="{{ asset(Storage::url($orderDetail->product->image)) }}"
                                                                 alt="{{ $orderDetail->product->name }}">
                                                        @else
                                                            <div class="no-image">
                                                                <i class="mdi mdi-image-off"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="order-product-info">
                                                        <div class="order-product-name" title="{{ $orderDetail->product->name ?? 'N/A' }}">
                                                            {{ $orderDetail->product->name ?? 'N/A' }}
                                                        </div>
                                                        @if($orderDetail->variant) <!-- Kiểm tra nếu có variant -->
                                                            <div class="order-product-variant">
                                                                @foreach($orderDetail->variant->options as $option)
                                                                    <span class="variant-option">{{ $option->value }}</span>
                                                                    @if(!$loop->last) - @endif <!-- Thêm dấu gạch ngang nếu không phải option cuối cùng -->
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="order-product-variant">No variant</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="no-products-message">No products</div>
                                        @endif
                                    </td>
                                    <td>${{ number_format($item->total, 2) }}</td>
                                    <td>
                                        <span class="badge
                                            @if($item->status == 'completed') badge-success
                                            @elseif($item->status == 'cancelled') badge-danger
                                            @else badge-warning
                                            @endif">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $item->payment_method == 'cash_on_delivery' ? 'COD' : 'Online' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('admin.orders.show', $item->id) }}"
                                                   class="dropdown-item">
                                                   <i class="mdi mdi-eye-outline mr-2"></i> View
                                                </a>
                                                <form action="{{ route('admin.orders.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Delete this order?')">
                                                        <i class="mdi mdi-delete-outline mr-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS cho khối hiển thị sản phẩm trong đơn hàng */
        .product-info-cell {
    max-width: 300px; /* Hoặc kích thước phù hợp với layout của bạn */
    width: 50%; /* Điều chỉnh theo nhu cầu */
}

.order-product-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 4px;
    overflow: hidden;
}

.order-product-image {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 4px;
    overflow: hidden;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
}

.order-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-product-image .no-image {
    color: #adb5bd;
    font-size: 18px;
}

.order-product-info {
    flex-grow: 1;
    margin-left: 10px;
    min-width: 0; /* Quan trọng để text-overflow hoạt động */
    overflow: hidden;
}

.order-product-name {
    font-weight: 500;
    margin-bottom: 2px;
    color: #212529;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 13px;
}

.order-product-variant {
    font-size: 11px;
    color: #6c757d;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.order-product-variant .variant-option {
    background-color: #e9ecef;
    padding: 2px 6px;
    border-radius: 10px;
    display: inline-block;
    white-space: nowrap;
}

.no-products-message {
    color: #6c757d;
    font-style: italic;
    font-size: 12px;
    padding: 4px;
}

/* CSS cho ô thông tin khách hàng */
.customer-info-cell {
    padding: 12px;
    vertical-align: middle;
}

.customer-info-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    flex-shrink: 0;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f0f2f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #4a5568;
    font-size: 16px;
}

.customer-details {
    flex-grow: 1;
    min-width: 0;
}

.customer-name {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
}

.customer-id {
    font-size: 12px;
    color: #718096;
    background-color: #edf2f7;
    padding: 2px 6px;
    border-radius: 4px;
}

.customer-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #718096;
}

.meta-item i {
    font-size: 14px;
    color: #a0aec0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .customer-info-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }

    .customer-meta {
        margin-top: 4px;
    }
}
    </style>
@endsection
