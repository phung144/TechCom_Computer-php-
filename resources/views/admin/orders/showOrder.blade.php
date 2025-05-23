@extends('admin.layout')

@section('main')
    {{-- Thông báo SweetAlert2 được thiết kế lại --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Hàm hiển thị thông báo thành công với icon checkmark
        function showSuccessAlert(message) {
            Swal.fire({
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#f0fff4', // Màu nền xanh nhạt
                iconColor: '#38a169', // Màu xanh lá đậm
                color: '#2f855a' // Màu chữ xanh đậm
            });
        }

        // Hàm hiển thị thông báo lỗi với icon chấm than
        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#fff5f5', // Màu nền đỏ nhạt
                iconColor: '#e53e3e', // Màu đỏ
                color: '#c53030' // Màu chữ đỏ đậm
            });
        }

        // Hàm hiển thị thông báo thông tin
        function showInfoAlert(message) {
            Swal.fire({
                icon: 'info',
                title: message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#ebf8ff', // Màu nền xanh dương nhạt
                iconColor: '#3182ce', // Màu xanh dương
                color: '#2c5282' // Màu chữ xanh đậm
            });
        }

        // Xử lý thông báo từ session
        @if (session('success'))
            showSuccessAlert('{{ session('success') }}');
        @endif

        @if (session('error'))
            showErrorAlert('{{ session('error') }}');
        @endif

        @if (session('info'))
            showInfoAlert('{{ session('info') }}');
        @endif
    </script>
    <div class="row">
        <div class="col-12">
            <div class="card card-default shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Order Details</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="order-info-box">
                                <h4 class="info-title"><i class="mdi mdi-information-outline"></i> Order Information
                                </h4>
                                <div class="info-content">
                                    <div class="info-item">
                                        <span class="info-label">ID:</span>
                                        <span class="info-value">{{ $order->id }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Full Name:</span>
                                        <span class="info-value">{{ $order->full_name }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Phone:</span>
                                        <span class="info-value">{{ $order->phone }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Address:</span>
                                        <span class="info-value">{{ $order->address }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="order-info-box">
                                <h4 class="info-title"><i class="mdi mdi-cash-multiple"></i> Payment Information
                                </h4>
                                <div class="info-content">
                                    <div class="info-item">
                                        <span class="info-label">Total:</span>
                                        <span class="info-value text-success">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Created At:</span>
                                        <span class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Updated At:</span>
                                        <span class="info-value">{{ $order->updated_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Status:</span>
                                        <span class="info-value">
                                            <span class="badge status-{{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            @if ($order->status === 'canceled' && $order->cancel_reason)
                                                <span class="cancel-reason">
                                                    (Lý do:
                                                    {{ htmlspecialchars($order->cancel_reason, ENT_QUOTES, 'UTF-8', false) }})
                                                </span>
                                            @endif
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin user -->
                    <div class="user-info-box mb-4">
                        <h4 class="info-title"><i class="mdi mdi-account-circle"></i> Customer Information</h4>
                        <div class="info-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <span class="info-label">User ID:</span>
                                        <span class="info-value">{{ $order->user->id }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <span class="info-label">Name:</span>
                                        <span class="info-value">{{ $order->user->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <span class="info-label">Email:</span>
                                        <span class="info-value">{{ $order->user->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="status-update-box mb-4">
                        <h4 class="info-title"><i class="mdi mdi-update"></i> Update Order Status</h4>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-row align-items-center">
                                <div class="col-md-8">
                                    <select name="status" id="status" class="form-control"
                                        @if(in_array($order->status, ['completed', 'rated', 'canceled'])) disabled @endif>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác
                                            nhận</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                            Đang xử lý</option>
                                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang
                                            vận chuyển</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã
                                            giao</option>
                                        <option value="rated" {{ $order->status == 'rated' ? 'selected' : '' }}>Đã hoàn
                                            thành</option>
                                        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Đã
                                            hủy</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-block"
                                        @if(in_array($order->status, ['completed', 'rated', 'canceled'])) disabled @endif>
                                        <i class="mdi mdi-check"></i> Update Status
                                    </button>
                                </div>
                            </div>
                            <div class="form-row mt-3" id="cancel-reason-row" style="display: none;">
                                <div class="col-12">
                                    <input type="text" name="cancel_reason" id="cancel_reason" class="form-control"
                                        placeholder="Nhập lý do hủy đơn..."
                                        value="{{ old('cancel_reason', $order->cancel_reason) }}"
                                        @if(in_array($order->status, ['completed', 'rated', 'canceled'])) disabled @endif>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="products-table-box">
                        <h4 class="info-title"><i class="mdi mdi-cart"></i> Ordered Products</h4>
                        <div class="table-responsive">
                            <table class="table product-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Image</th>
                                        <th>Product Details</th>
                                        <th class="text-center" style="width: 100px;">Qty</th>
                                        <th class="text-center" style="width: 120px;">Price</th>
                                        <th class="text-center" style="width: 120px;">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails as $detail)
                                        <tr>
                                            <td class="text-center">
                                                <div class="product-img">
                                                    @if ($detail->product && $detail->product->image)
                                                        <!-- Thêm kiểm tra $detail->product -->
                                                        <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                            alt="{{ $detail->product->name }}">
                                                    @else
                                                        <div class="no-image">
                                                            <i class="mdi mdi-image-off"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-details">
                                                    <div class="product-name">
                                                        {{ $detail->product->name ?? 'Product deleted' }}
                                                        <!-- Xử lý khi product bị xóa -->
                                                        <span class="product-id">#{{ $detail->product->id ?? '' }}</span>
                                                    </div>
                                                    @if ($detail->variant && $detail->variant->options->isNotEmpty())
                                                        <div class="variant-options">
                                                            @foreach ($detail->variant->options as $option)
                                                                <span class="variant-badge">
                                                                    @if ($option->variant)
                                                                        <!-- Kiểm tra relation variant -->
                                                                        <span
                                                                            class="option-name">{{ $option->variant->name }}:</span>
                                                                    @endif
                                                                    <span class="option-value">{{ $option->value }}</span>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="no-variant">Standard product</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-center">{{ number_format($detail->price) }} VND</td>
                                            <td class="text-center text-success">
                                                {{ number_format($detail->price * $detail->quantity) }} VND</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* General Styles */
        .info-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #495057;
            display: flex;
            align-items: center;
        }

        .info-title i {
            margin-right: 0.5rem;
            font-size: 1.25rem;
        }

        /* Info Box Styles */
        .order-info-box,
        .user-info-box,
        .status-update-box,
        .products-table-box {
            background: #fff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .info-content {
            padding: 0.5rem 0;
        }

        .info-item {
            display: flex;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f1f1;
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            min-width: 120px;
        }

        .info-value {
            color: #495057;
        }

        /* Status Badges */
        .badge.status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge.status-processing {
            background-color: #17a2b8;
            color: white;
        }

        .badge.status-completed {
            background-color: #28a745;
            color: white;
        }

        .badge.status-canceled {
            background-color: #dc3545;
            color: white;
        }

        .badge.status-shipping {
            background-color: #6f42c1;
            /* Màu tím đặc trưng */
            color: white;
        }

        .badge.status-rate {
            background-color: #fd7e14;
            /* Màu cam nổi bật */
            color: white;
        }


        /* Product Table Styles */
        .product-table {
            margin-top: 1rem;
        }

        .product-table thead th {
            background-color: #f8f9fa;
            border-bottom-width: 1px;
            font-weight: 600;
            color: #495057;
        }

        .product-img {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 0.25rem;
            overflow: hidden;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            color: #adb5bd;
            font-size: 1.5rem;
        }

        .product-details {
            padding: 0.5rem 0;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #343a40;
        }

        .product-id {
            font-size: 0.8rem;
            color: #6c757d;
            margin-left: 0.5rem;
        }

        .variant-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .variant-badge {
            display: inline-flex;
            align-items: center;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .option-name {
            font-weight: 500;
            margin-right: 0.25rem;
            color: #495057;
        }

        .option-value {
            color: #6c757d;
        }

        .no-variant {
            font-size: 0.85rem;
            color: #6c757d;
            font-style: italic;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .info-item {
                flex-direction: column;
            }

            .info-label {
                margin-bottom: 0.25rem;
                min-width: auto;
            }

            .product-table thead {
                display: none;
            }

            .product-table tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
            }

            .product-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                border: none;
                border-bottom: 1px solid #f1f1f1;
            }

            .product-table td:last-child {
                border-bottom: none;
            }

            .product-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6c757d;
                margin-right: 1rem;
            }

            .product-img {
                margin: 0;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const cancelReasonRow = document.getElementById('cancel-reason-row');
            const cancelReasonInput = document.getElementById('cancel_reason');
            function toggleCancelReason() {
                if (statusSelect.value === 'canceled') {
                    cancelReasonRow.style.display = '';
                } else {
                    cancelReasonRow.style.display = 'none';
                }
            }
            if (statusSelect && cancelReasonRow) {
                statusSelect.addEventListener('change', toggleCancelReason);
                toggleCancelReason();
            }
            // Disable all controls if completed, rated, or canceled (for JS fallback)
            @if(in_array($order->status, ['completed', 'rated', 'canceled']))
                if (statusSelect) statusSelect.disabled = true;
                if (cancelReasonInput) cancelReasonInput.disabled = true;
                const updateBtn = document.querySelector('.status-update-box button[type="submit"]');
                if (updateBtn) updateBtn.disabled = true;
            @endif

            // Add data-label attributes for responsive table
            const headers = document.querySelectorAll('.product-table thead th');
            const rows = document.querySelectorAll('.product-table tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].textContent.trim());
                    }
                });
            });
        });
    </script>
@endsection
