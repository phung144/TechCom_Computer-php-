@extends('client.layout')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endsection

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
</script>

@section('main')
    <div class="container my-5">
        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="mb-4 text-center fw-bold" style="color: #2c3e50; position: relative;">
            <span
                style="background: linear-gradient(135deg, #3498db, #8e44ad); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                🛍️ Lịch sử đặt hàng
            </span>
            <div
                style="height: 3px; background: linear-gradient(90deg, #3498db, #8e44ad); width: 100px; margin: 10px auto;">
            </div>
        </h2>

        @auth
            @if ($orders->isEmpty())
                <div class="empty-order text-center py-5">
                    <div class="empty-icon mb-3" style="font-size: 3rem; color: #95a5a6;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h4 class="mb-3" style="color: #7f8c8d;">Danh sách đơn hàng của bạn đang trống.</h4>
                    <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-shopping-bag me-2"></i> Bắt đầu mua sắm ngay bây giờ
                    </a>
                </div>
            @else
                <div class="order-list">
                    @foreach ($orders as $order)
                        <div class="order-card mb-4 rounded-3 overflow-hidden border-0 shadow-sm position-relative">
                            <div class="order-ribbon position-absolute"
                                style="background: linear-gradient(135deg, #3498db, #8e44ad);"></div>

                            <div class="order-header p-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="order-id fw-bold fs-5" style="color: #2c3e50;">ĐẶT HÀNG
                                            #{{ $order->id }}</span>
                                        <span class="ms-3 order-date" style="color: #7f8c8d;">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $order->created_at->format('d M, Y - H:i') }}
                                        </span>
                                    </div>
                                    <div>
                                        @php
                                            $statusColor = match ($order->status) {
                                                'pending' => ['bg' => 'bg-warning', 'icon' => 'fas fa-clock'],
                                                'processing' => [
                                                    'bg' => 'bg-info',
                                                    'icon' => 'fas fa-cog',
                                                ], // Thêm nếu cần
                                                'shipping' => [
                                                    'bg' => 'bg-primary',
                                                    'icon' => 'fas fa-truck',
                                                ], // Màu xanh dương + icon truck
                                                'completed' => ['bg' => 'bg-success', 'icon' => 'fas fa-check-circle'],
                                                'rated' => [
                                                    'bg' => 'bg-success',
                                                    'icon' => 'fas fa-star',
                                                ], // Màu xanh lá + icon ngôi sao
                                                'canceled' => ['bg' => 'bg-secondary', 'icon' => 'fas fa-times-circle'],
                                                default => ['bg' => 'bg-light', 'icon' => 'fas fa-info-circle'],
                                            };
                                        @endphp
                                        <span
                                            class="badge {{ $statusColor['bg'] }} text-white rounded-pill px-3 py-2 text-capitalize">
                                            <i class="{{ $statusColor['icon'] }} me-1"></i> {{ $order->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="order-body p-4">
                                @foreach ($order->orderDetails as $detail)
                                    <div class="order-item d-flex mb-4 pb-4" style="border-bottom: 1px dashed #e0e0e0;">
                                        <div class="product-image me-4 position-relative">
                                            <img src="{{ Storage::url($detail->product->image ?? 'default-product-image.jpg') }}"
                                                alt="{{ $detail->product->name }}" class="img-fluid rounded-3 shadow-sm p-4"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                            <span
                                                class="product-quantity position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $detail->quantity }}
                                            </span>
                                        </div>
                                        <div class="product-details flex-grow-1 ml-3">
                                            <h5 class="product-name mb-2 fw-bold" style="color: #2c3e50;">
                                                {{ $detail->product->name }}</h5>

                                            @if ($detail->variant && $detail->variant->options->isNotEmpty())
                                                <div class="variant-options mb-3 d-flex flex-wrap gap-2">
                                                    @foreach ($detail->variant->options as $option)
                                                        <span class="variant-badge px-3 py-1 rounded-pill shadow-sm"
                                                            style="background: linear-gradient(135deg, #f1f1f1, #ffffff); border: 1px solid #e0e0e0;">
                                                            <span class="option-name fw-semibold"
                                                                style="color: #3498db;">{{ $option->variant->name ?? 'Option' }}:</span>
                                                            <span class="option-value"
                                                                style="color: #e74c3c;">{{ $option->value }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="product-price" style="font-size: 1.1rem;">
                                                    <span class="text-muted me-2">Đơn giá:</span>
                                                    <span class="fw-bold"
                                                        style="color: #e74c3c;">${{ number_format($detail->price, 2) }}</span>
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
                                                <div class="shipping-icon me-3"
                                                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #3498db, #8e44ad); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold ml-3" style="color: #2c3e50;">Địa chỉ giao hàng</h6>
                                                    <p class="mb-0 ml-3" style="color: #7f8c8d;">{{ $order->address }}</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="contact-icon me-3"
                                                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #2ecc71, #3498db); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold ml-3" style="color: #2c3e50;">Số điện thoại</h6>
                                                    <p class="mb-0 ml-3" style="color: #7f8c8d;">{{ $order->phone }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex flex-column align-items-md-end">
                                        <div class="order-summary mb-3 text-end">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="me-3" style="color: #7f8c8d;">Tổng phụ:</span>
                                                <span>{{ number_format($order->total) }} VND</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="me-3" style="color: #7f8c8d;">Vận chuyển:</span>
                                                <span>Free</span>
                                            </div>
                                            <div class="d-flex justify-content-between fw-bold fs-4 mt-2">
                                                <span class="me-3" style="color: #2c3e50;">Tổng cộng:</span>
                                                <span style="color: #27ae60;">{{ number_format($order->total) }} VND</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <div class="order-actions d-flex gap-2 mt-3 mr-2">
                                                @if (in_array($order->status, ['pending', 'processing']))
                                                    <button type="button"
                                                        class="btn btn-outline-danger rounded-pill px-4 py-2 shadow-sm"
                                                        onclick="styledCancelOrder({{ $order->id }})">
                                                        <i class="fas fa-times-circle me-1"></i> Hủy đơn hàng
                                                    </button>
                                                @endif

                                                @if (in_array($order->status, ['completed']))
                                                    <button type="button"
                                                        class="btn btn-outline-success rounded-pill px-4 py-2 shadow-sm mr-3"
                                                        onclick="showRatingForm({{ $order->id }})">
                                                        <i class="fas fa-check-circle me-1"></i> Hoàn thành
                                                    </button>
                                                @endif
                                            </div>

                                            <div>
                                                <a href="{{ route('orders.show', $order->id) }}"
                                                    class="btn btn-primary rounded-pill px-4 shadow-sm"
                                                    style="background: linear-gradient(135deg, #3498db, #8e44ad); border: none;">
                                                    <i class="fas fa-eye me-1"></i>Chi tiết
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
                 
Đăng nhập để xem đơn hàng, theo dõi lô hàng và quản lý giao dịch mua của bạn.
                </p>
                <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm"
                    style="background: linear-gradient(135deg, #3498db, #8e44ad); border: none;">
                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay
                </a>
            </div>
        @endauth
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Sử dụng biến toàn cục để theo dõi trạng thái modal
    let currentSwalInstance = null;

    function styledCancelOrder(orderId) {
        // Đóng modal hiện tại nếu có
        if (currentSwalInstance) {
            currentSwalInstance.close();
        }

        currentSwalInstance = Swal.fire({
            title: 'Hủy đơn hàng',
            html: `
            <div style="text-align: center;">
                <p style="margin-bottom: 15px; font-size: 16px;">Vui lòng nhập lý do hủy:</p>
                <textarea
                    id="swal-reason"
                    style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd; min-height: 100px;"
                    placeholder="Nhập lý do hủy đơn hàng..."></textarea>
            </div>
        `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Xác nhận hủy',
            cancelButtonText: 'Quay lại',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                const reason = document.getElementById('swal-reason').value;
                if (!reason.trim()) {
                    Swal.showValidationMessage('Vui lòng nhập lý do hủy');
                    return false;
                }
                return reason;
            },
            didOpen: () => {
                // Tự động focus vào textarea khi modal mở
                document.getElementById('swal-reason').focus();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitCancelForm(orderId, result.value);
            }
            currentSwalInstance = null; // Reset instance sau khi đóng
        });
    }

    function submitCancelForm(orderId, reason) {
        // Tạo form động và submit
        const form = document.createElement('form');
        form.action = `/orders/${orderId}`;
        form.method = 'POST';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'cancel_reason';
        reasonInput.value = reason;

        form.appendChild(csrf);
        form.appendChild(method);
        form.appendChild(reasonInput);

        document.body.appendChild(form);
        form.submit();
    }
</script>

<script>
    function showRatingForm(orderId) {
    Swal.fire({
        title: 'Đánh giá đơn hàng',
        html: `
            <div class="rating-container">
                <p class="rating-title">Vui lòng đánh giá chất lượng đơn hàng</p>

                <div class="rating-stars mb-4">
                    <i class="far fa-star star-icon" data-rating="1"></i>
                    <i class="far fa-star star-icon" data-rating="2"></i>
                    <i class="far fa-star star-icon" data-rating="3"></i>
                    <i class="far fa-star star-icon" data-rating="4"></i>
                    <i class="far fa-star star-icon" data-rating="5"></i>
                    <span class="rating-text ml-2">Chưa đánh giá</span>
                </div>

                <textarea id="feedback-content" class="form-control rating-textarea" rows="4"
                    placeholder="Nhập nhận xét của bạn..."></textarea>

                <div class="file-upload-section">
                    <label class="file-upload-label">Thêm hình ảnh (tối đa 2MB)</label>

                    <div class="file-upload-wrapper">
                        <label for="feedback-image" class="file-upload-btn">
                            <i class="fas fa-camera mr-2"></i>
                            <span class="btn-text">Chọn ảnh</span>
                            <span id="file-name" class="file-name"></span>
                        </label>
                        <input type="file" id="feedback-image" accept="image/*">
                    </div>

                    <div id="image-preview-container" class="image-preview-wrapper">
                        <img id="image-preview" src="#" alt="Preview">
                        <button type="button" id="remove-image-btn" class="remove-image-btn">
                            <i class="fas fa-trash-alt mr-1"></i> Xóa
                        </button>
                    </div>
                </div>

                <input type="hidden" id="selected-rating" value="0">
            </div>
        `,
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Gửi đánh giá',
        cancelButtonText: 'Hủy bỏ',
        denyButtonText: 'Bỏ qua đánh giá',
        confirmButtonColor: '#4CAF50',
        denyButtonColor: '#757575',
        width: '600px',
        customClass: {
            popup: 'rating-popup',
            title: 'rating-popup-title',
            htmlContainer: 'rating-html-container'
        },
        preConfirm: () => {
            const rating = document.getElementById('selected-rating').value;
            const content = document.getElementById('feedback-content').value;
            const imageInput = document.getElementById('feedback-image');

            if (rating == 0) {
                Swal.showValidationMessage('Vui lòng chọn số sao đánh giá');
                return false;
            }

            if (imageInput.files.length > 0 && imageInput.files[0].size > 2 * 1024 * 1024) {
                Swal.showValidationMessage('Ảnh không được vượt quá 2MB');
                return false;
            }

            return {
                rating,
                content,
                image: imageInput.files.length > 0 ? imageInput.files[0] : null
            };
        },
        didOpen: () => {
            // Xử lý rating stars
            const stars = document.querySelectorAll('.star-icon');
            const ratingText = document.querySelector('.rating-text');

            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    highlightStars(rating);
                });

                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    document.getElementById('selected-rating').value = rating;
                    highlightStars(rating);
                    updateRatingText(rating);
                });
            });

            document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
                const currentRating = document.getElementById('selected-rating').value;
                if (currentRating > 0) {
                    highlightStars(currentRating);
                } else {
                    resetStars();
                }
            });

            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.remove('far');
                        star.classList.add('fas', 'star-active');
                    } else {
                        star.classList.remove('fas', 'star-active');
                        star.classList.add('far');
                    }
                });
            }

            function resetStars() {
                stars.forEach(star => {
                    star.classList.remove('fas', 'star-active');
                    star.classList.add('far');
                });
            }

            function updateRatingText(rating) {
                const texts = ['Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Rất tốt'];
                ratingText.textContent = texts[rating - 1] || 'Chưa đánh giá';
                ratingText.style.color = getRatingColor(rating);
            }

            function getRatingColor(rating) {
                const colors = ['#ff3d3d', '#ff6b6b', '#ffb74d', '#81c784', '#4caf50'];
                return colors[rating - 1] || '#757575';
            }

            // Xử lý file upload
            const imageInput = document.getElementById('feedback-image');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const removeImageBtn = document.getElementById('remove-image-btn');
            const fileNameDisplay = document.getElementById('file-name');

            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    fileNameDisplay.textContent = file.name.length > 20
                        ? file.name.substring(0, 17) + '...'
                        : file.name;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.style.display = 'flex';
                    }
                    reader.readAsDataURL(file);
                }
            });

            removeImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreviewContainer.style.display = 'none';
                fileNameDisplay.textContent = '';
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitOrderRating(orderId, result.value.rating, result.value.content, result.value.image);
        } else if (result.isDenied) {
            skipRating(orderId);
        }
    });
}


    function submitOrderRating(orderId, rating, content, imageFile) {
    // Tạo form động với FormData để hỗ trợ file upload
    const form = new FormData();
    form.append('_token', '{{ csrf_token() }}');
    form.append('rating', rating);
    form.append('content', content);
    if (imageFile) {
        form.append('image', imageFile);
    }

    // Gửi dữ liệu bằng AJAX
    fetch(`/orders/${orderId}/complete`, {
        method: 'POST',
        body: form,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Thành công!', 'Đã gửi đánh giá thành công', 'success')
                .then(() => {
                    window.location.reload();
                });
        } else {
            Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra khi gửi đánh giá', 'error');
        }
    })
    .catch(error => {
        Swal.fire('Lỗi!', 'Có lỗi xảy ra khi gửi đánh giá', 'error');
        console.error('Error:', error);
    });
}

function skipRating(orderId) {
    // Tạo form động
    const form = document.createElement('form');
    form.action = `/orders/${orderId}/skip-rating`;
    form.method = 'POST';
    form.style.display = 'none';

    // Thêm CSRF token
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    `;

    document.body.appendChild(form);
    form.submit();

    // Hiển thị thông báo ngay lập tức
    Swal.fire('Thành công!', 'Đã bỏ qua đánh giá', 'info');
}
</script>

<style>
    /* Animation cho popup */
    @keyframes bounceIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animated {
        animation-duration: 0.3s;
        animation-fill-mode: both;
    }
</style>

<style>
    .btn-outline-dark {
        border-color: #2c3e50;
        color: #2c3e50;
    }

    .btn-outline-dark:hover {
        background-color: #2c3e50;
        color: white;
    }

    .btn-outline-danger.cancel-order-btn {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .btn-outline-danger.cancel-order-btn:hover {
        background-color: #f5c6cb;
        color: #721c24;
    }

    .cancel-reason-form {
        display: none;
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
        background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
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
        border-top: 1px solid rgba(0, 0, 0, 0.05);
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


{{-- CSS form feedback --}}
<style>
    /* Popup style */
.rating-popup {
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.rating-popup-title {
    color: #333;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
}

.rating-container {
    padding: 0 10px;
}

/* Star rating style */
.rating-stars {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
}

.star-icon {
    font-size: 2.5rem;
    color: #ddd;
    cursor: pointer;
    transition: all 0.2s;
    margin: 0 5px;
}

.star-icon:hover {
    transform: scale(1.1);
}

.star-active {
    color: #FFC107 !important;
    text-shadow: 0 0 5px rgba(255, 193, 7, 0.3);
}

.rating-text {
    font-size: 1rem;
    color: #757575;
    font-weight: 500;
    transition: all 0.3s;
}

/* Textarea style */
.rating-textarea {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    padding: 12px;
    font-size: 0.95rem;
    transition: all 0.3s;
    margin-bottom: 20px;
}

.rating-textarea:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

/* File upload style */
.file-upload-section {
    margin-top: 20px;
}

.file-upload-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
    font-size: 0.95rem;
}

.file-upload-wrapper {
    position: relative;
    margin-bottom: 15px;
}

.file-upload-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    background-color: #f5f5f5;
    color: #555;
    border: 2px dashed #ccc;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.95rem;
    font-weight: 500;
}

.file-upload-btn:hover {
    background-color: #e9f5e9;
    border-color: #4CAF50;
    color: #4CAF50;
}

.file-upload-btn i {
    font-size: 1.1rem;
}

.file-name {
    margin-left: 10px;
    color: #666;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
    display: inline-block;
}

#feedback-image {
    position: absolute;
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    z-index: -1;
}

/* Image preview style */
.image-preview-wrapper {
    display: none;
    flex-direction: column;
    align-items: center;
    margin-top: 15px;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

#image-preview {
    max-width: 100%;
    max-height: 200px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
    object-fit: contain;
}

.remove-image-btn {
    background: none;
    border: 1px solid #f44336;
    color: #f44336;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
}

.remove-image-btn:hover {
    background-color: #f44336;
    color: white;
}

.remove-image-btn i {
    font-size: 0.8rem;
}
</style>
