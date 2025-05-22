@extends('client.layout')

@section('main')
<div>
    {{-- Thông báo add to cart thành công --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000, // Tự động đóng sau 2 giây
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#f8f9fa',
                iconColor: '#28a745'
            });
        </script>
    @endif

    <div class="mb-xl-14 mb-6">
        <div class="row">
            <div class="col-md-5 mb-4 mb-md-0">
                <img class="img-fluid main-image" src="{{ Storage::url($product->image) }}" alt="Image Description">
                <!-- Additional product photos -->
                @if(!empty($product->photos) && is_array($product->photos))
                    <div class="mt-3 d-flex flex-wrap">
                        <!-- Include the main image as the first thumbnail -->
                        <div class="mr-2 mb-2">
                            <img src="{{ Storage::url($product->image) }}" alt="Main Photo" class="img-thumbnail thumbnail-hover" style="cursor: pointer;" onclick="document.querySelector('.main-image').src='{{ Storage::url($product->image) }}'">
                        </div>
                        @foreach($product->photos as $photo)
                            <div class="mr-2 mb-2">
                                <img src="{{ Storage::url($photo) }}" alt="Additional Photo" class="img-thumbnail thumbnail-hover" style="cursor: pointer;" onclick="document.querySelector('.main-image').src='{{ Storage::url($photo) }}'">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-md-7 mb-md-6 mb-lg-0 mt-6">
                <!-- Phần thông tin sản phẩm -->
                <div class="border-bottom mb-3 pb-md-1 pb-3">
                    <a href="#" class="font-size-12 text-gray-5 mb-2 d-inline-block">Laptops {{ $product->category->name }}</a>
                    <h2 class="font-size-25 text-lh-1dot2">{{ $product->name }}</h2>
                    <div class="mb-2">
                        <div class="text-warning mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="far fa-star text-muted"></small>
                        </div>
                        <span class="text-secondary font-size-13">(3 customer reviews)</span>
                    </div>
                    <div class="ml-md-3 text-gray-9 font-size-14">Availability:
                        <span class="text-green font-weight-bold" id="variant-quantity">{{ $variants->first()->quantity }}</span> in stock
                    </div>
                </div>

                <!-- Phần mô tả sản phẩm -->
                <div class="mb-2">
                    <ul class="font-size-14 pl-3 ml-1 text-gray-110">
                        {{ $product->description }}
                    </ul>
                </div>

                <!-- Phần giá sản phẩm -->
                <div class="mb-4">
                    @php
                        $firstVariant = $variants->first();
                        $originalPrice = $firstVariant->price;
                        $discountPercent = $product->discount_value;
                        $discountedPrice = $originalPrice * (1 - $discountPercent/100);
                        $showDiscount = $discountPercent > 0;
                    @endphp

                    <div class="mb-4">
                        <div class="d-flex align-items-baseline">
                            <!-- Giá mới sau khi giảm -->
                            <span class="font-size-36 text-red mr-3">
                                <span id="variant-price">{{ number_format($discountedPrice) }}</span> VND
                            </span>

                            <!-- Giá cũ và % giảm giá (chỉ hiện khi có discount) -->
                            <div id="original-price-container" @if(!$showDiscount) style="display:none;" @endif>
                                <del class="font-size-16 text-gray-6" id="original-price">{{ number_format($originalPrice) }} VND</del>
                                <span class="badge badge-danger ml-2" id="discount-percent">-{{ $discountPercent }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phần chọn cấu hình -->
                <h3>Chọn cấu hình:</h3>
                <div class="variant-grid">
                    @foreach ($variants as $variant)
                        @php
                            $variantOriginalPrice = $variant->price;
                            $variantDiscountedPrice = $variantOriginalPrice * (1 - $product->discount_value/100);
                            $variantShowDiscount = $product->discount_value > 0;
                        @endphp

                        <div class="variant-box {{ $loop->first ? 'selected' : '' }}"
                            data-variant-id="{{ $variant->id }}"
                            data-variant-price="{{ $variant->price }}"
                            data-variant-quantity="{{ $variant->quantity }}"
                            data-discount-percent="{{ $product->discount_value }}">

                            <div class="variant-specs">
                                @foreach($variant->options as $option)
                                    <span class="spec-item">
                                        {{ strtoupper($option->variant->name) }}({{ $option->value }})
                                    </span>
                                    @if(!$loop->last)-@endif
                                @endforeach
                            </div>

                            <div class="price-container">
                                <span class="final-price">{{ number_format($variantDiscountedPrice) }} VND</span>
                                @if($variantShowDiscount)
                                    <del class="original-price">{{ number_format($variantOriginalPrice) }} VND</del>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Phần số lượng và nút mua hàng -->
                <div class="max-width-150 my-4">
                    <h6 class="font-size-14">Số lượng</h6>
                    <div class="border rounded-pill py-2 px-3 border-color-1">
                        <div class="js-quantity row align-items-center">
                            <div class="col">
                                <input class="js-result form-control h-auto border-0 rounded p-0 shadow-none" type="text" value="1">
                            </div>
                            <div class="col-auto pr-1">
                                <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                    <small class="fas fa-minus btn-icon__inner"></small>
                                </a>
                                <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                    <small class="fas fa-plus btn-icon__inner"></small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center mt-3">
                    @auth
                        <!-- Form thêm vào giỏ hàng (chỉ hiển thị khi đã đăng nhập) -->
                        <form id="cart-form" action="{{ route('cart.add') }}" method="POST" class="mr-2 mb-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" id="variant-id-input" value="{{ $variants->first()->id }}">
                            <input type="hidden" name="quantity" id="quantity-input" value="1">

                            <button type="submit" class="btn btn-primary-dark transition-3d-hover">
                                <i class="ec ec-add-to-cart mr-2 font-size-20"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <!-- Nút xử lý bằng SweetAlert khi chưa đăng nhập -->
                        <button onclick="handleAddToCart()" class="btn btn-primary-dark transition-3d-hover mr-2 mb-2">
                            <i class="ec ec-add-to-cart mr-2 font-size-20"></i> Add to Cart
                        </button>
                    @endauth

                    <script>
                        function handleAddToCart() {
                            Swal.fire({
                                        title: 'Ready to Checkout?',
                                        html: `
                                            <div class="text-center py-3">
                                                <i class="fas fa-shopping-bag fa-3x text-primary mb-3"></i>
                                                <p class="mb-2">Sign in to complete your purchase</p>
                                                <small class="text-muted">Enjoy faster checkout and order tracking</small>
                                            </div>
                                        `,
                                        showCancelButton: true,
                                        confirmButtonText: '<i class="fas fa-sign-in-alt mr-2"></i> Login Now',
                                        cancelButtonText: 'Continue Shopping',
                                        buttonsStyling: false,
                                        customClass: {
                                            confirmButton: 'btn btn-primary px-4 py-2 mx-2',
                                            cancelButton: 'btn btn-light px-4 py-2 mx-2',
                                            popup: 'rounded-lg'
                                        },
                                        showClass: {
                                            popup: 'animate__animated animate__zoomIn'
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "{{ route('login', ['redirect_to' => url()->current()]) }}";
                                        }
                                    });
                        }
                    </script>

                   <!-- Nút mua ngay -->
                    <div class="d-inline">
                        @auth
                            <!-- Form mua ngay cho user đã đăng nhập -->
                            <form action="{{ route('orderNow.index') }}" method="GET" id="order-now-form">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" id="buy-now-variant-id" value="{{ $variants->first()->id }}">
                                <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
                                <button type="submit" class="btn btn-success transition-3d-hover mb-2">
                                    <i class="ec ec-credit-card mr-2 font-size-20"></i> Order Now
                                </button>
                            </form>
                        @else
                            <!-- Nút cho user chưa đăng nhập -->
                            <button onclick="handleOrderNow()" class="btn btn-success transition-3d-hover mb-2">
                                <i class="ec ec-credit-card mr-2 font-size-20"></i> Order Now
                            </button>
                        @endauth
                    </div>

                    <script>
                    function handleOrderNow() {
                        @auth
                            document.getElementById('order-now-form').submit();
                        @else
                            Swal.fire({
                                title: 'Ready to Checkout?',
                                html: `
                                    <div class="text-center py-3">
                                        <i class="fas fa-shopping-bag fa-3x text-primary mb-3"></i>
                                        <p class="mb-2">Sign in to complete your purchase</p>
                                        <small class="text-muted">Enjoy faster checkout and order tracking</small>
                                    </div>
                                `,
                                showCancelButton: true,
                                confirmButtonText: '<i class="fas fa-sign-in-alt mr-2"></i> Login Now',
                                cancelButtonText: 'Continue Shopping',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary px-4 py-2 mx-2',
                                    cancelButton: 'btn btn-light px-4 py-2 mx-2',
                                    popup: 'rounded-lg'
                                },
                                showClass: {
                                    popup: 'animate__animated animate__zoomIn'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('login', ['redirect_to' => url()->current()]) }}";
                                }
                            });
                        @endauth
                    }
                    </script>
                </div>
            </div>
        </div>

        <!-- Phần bình luận sản phẩm -->
        <div class="comment-section mt-5">
            <h2 class="mb-4 font-weight-bold text-dark">Bình luận sản phẩm</h2>

            @if (Auth::check())
                <div class="comment-form mb-4">
                    <div class="d-flex">
                        <!-- Avatar người dùng -->
                        <div class="flex-shrink-0 mr-3">
                            @if(Auth::user()->image)
                                <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Avatar" class="rounded-circle" width="50" height="50">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle" width="50" height="50">
                            @endif
                        </div>

                        <!-- Form bình luận -->
                        <div class="flex-grow-1">
                            <form action="{{ route('comment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="form-group mb-2">
                                    <textarea name="comment" class="form-control shadow-sm" rows="3" placeholder="Viết bình luận của bạn..." style="border-radius: 20px;"></textarea>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 20px; background-color: #a49e20; border: none;">
                                        <i class="fas fa-paper-plane mr-2"></i>Gửi bình luận
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-light border mb-4 d-flex align-items-center">
                    <i class="fas fa-info-circle mr-2 text-warning"></i>
                    <span>Vui lòng <a href="{{ route('login') }}" class="font-weight-bold text-primary">đăng nhập</a> để bình luận về sản phẩm này.</span>
                </div>
            @endif

            <!-- Danh sách bình luận -->
            <div class="comment-list">
                @if (isset($comments) && count($comments) > 0)
                    <div class="comment-count mb-3">
                        <span class="font-weight-bold">{{ $comments->total() }} bình luận</span>
                    </div>

                    @foreach ($comments as $item)
                        <div class="comment-item mb-4 pb-4 border-bottom">
                            <div class="d-flex align-items-start">
                                <!-- Avatar -->
                                <div class="flex-shrink-0 mr-3">
                                    @if($item->user->image)
                                        <img src="{{ asset('storage/' . $item->user->image) }}" alt="Avatar" class="rounded-circle" width="50" height="50">
                                    @else
                                        <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle" width="50" height="50">
                                    @endif
                                </div>
                                <!-- Nội dung bình luận -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h5 class="mb-0 font-weight-bold">{{ $item->user->name }}</h5>
                                            <small class="text-muted">
                                                <i class="far fa-clock mr-1"></i>{{ $item->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if(Auth::check() && Auth::id() == $item->user_id)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-text dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                {{-- <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editCommentModal-{{ $item->id }}">Sửa</a>
                                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('delete-comment-{{ $item->id }}').submit();">Xóa</a>
                                                    <form id="delete-comment-{{ $item->id }}" action="{{ route('comment.delete', $item->id) }}" method="POST" style="display: none;">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                </div> --}}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="comment-content mb-2">
                                        {{ $item->comment }}
                                    </div>
                                    <div class="comment-actions d-flex align-items-center">
                                        <button class="btn btn-text btn-sm mr-3 like-btn" data-comment-id="{{ $item->id }}">
                                            <i class="far fa-thumbs-up mr-1"></i> <span>{{ $item->likes_count ?? 0 }}</span>
                                        </button>
                                        <button class="btn btn-text btn-sm reply-btn" data-comment-id="{{ $item->id }}">
                                            <i class="far fa-comment-dots mr-1"></i> Phản hồi
                                        </button>
                                    </div>
                                    <!-- Form rep comment (ẩn, hiện khi bấm Phản hồi) -->
                                    <div class="reply-form mt-2" id="reply-form-{{ $item->id }}" style="display: none;">
                                        @if(Auth::check())
                                            <form action="{{ route('comments.reply.client',$item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="parent_id" value="{{ $item->id }}">
                                                <div class="form-group mb-2">
                                                    <textarea name="comment" class="form-control shadow-sm" rows="2" placeholder="Viết phản hồi..." style="border-radius: 20px;"></textarea>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary btn-sm px-3 py-1" style="border-radius: 20px; background-color: #a49e20; border: none;">
                                                        <i class="fas fa-paper-plane mr-1"></i>Gửi phản hồi
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="alert alert-light border mb-2 d-flex align-items-center">
                                                <i class="fas fa-info-circle mr-2 text-warning"></i>
                                                <span>Vui lòng <a href="{{ route('login') }}" class="font-weight-bold text-primary">đăng nhập</a> để phản hồi.</span>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Hiển thị rep comment -->
                                    @if($item->replies && $item->replies->count())
                                        <div class="ml-5 mt-3">
                                            @foreach($item->replies as $reply)
                                                <div class="comment-reply mb-2 p-2 bg-light rounded">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <strong class="mr-2 text-primary">{{ $reply->user->name ?? 'Admin' }}</strong>
                                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <div>{{ $reply->comment }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Phân trang -->
                    <div class="mt-3 d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            {{ $comments->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                @else
                    <div class="empty-comments text-center py-4">
                        <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có bình luận nào</h5>
                        <p class="text-muted">Hãy là người đầu tiên bình luận về sản phẩm này</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- CSS cmt -->
    <style>


        /* CSS cho phần variant */
        .variant-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px; /* Khoảng cách giữa các ô */
            margin: 15px 0;
        }

        .variant-box {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: calc(50% - 8px); /* 2 ô mỗi hàng, trừ đi khoảng cách */
            box-sizing: border-box;
            min-height: 100px; /* Chiều cao tối thiểu */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .variant-box.selected {
            border-color: #2a41e8;
            background-color: #f5f7ff;
            box-shadow: 0 0 0 2px rgba(42, 65, 232, 0.1);
        }

        .variant-box:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .variant-values {
            font-weight: 500;
            margin-bottom: 10px;
            font-size: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .price-container {
            margin-top: auto; /* Đẩy phần giá xuống dưới */
        }

        .final-price {
            color: #d32f2f;

            font-size: 20px;
        }

        .original-price {
            color: #999;
            font-size: 14px;
            text-decoration: line-through;
            margin-left: 8px;
        }

        /* CSS cho phần giá chính */
        .font-size-36 {
            font-size: 36px;
        }

        .text-red {
            color: #d32f2f;
        }

        .badge-danger {
            background-color: #ff4444;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Add hover effect for thumbnails */
        .thumbnail-hover {
            width: 100px; /* Fixed width for thumbnails */
            height: auto; /* Maintain aspect ratio */
            object-fit: cover;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .thumbnail-hover:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Use the main image's dimensions as the standard */
        .main-image {
            width: 100%; /* Adjust to fit the container */
            height: auto; /* Maintain aspect ratio */
            max-width: 500px; /* Optional: Limit maximum size */
        }
    </style>

    {{-- JS xử lý biến thể và số lượng sản phẩm --}}
    <div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // =============================================
                // PHẦN 1: XỬ LÝ CHỌN BIẾN THỂ SẢN PHẨM
                // =============================================

                // Lấy tất cả các ô biến thể
                const variantBoxes = document.querySelectorAll('.variant-box');

                // Xử lý khi click chọn một biến thể
                variantBoxes.forEach(box => {
                    box.addEventListener('click', function() {
                        // 1. Xóa lớp 'selected' khỏi tất cả các biến thể
                        variantBoxes.forEach(b => b.classList.remove('selected'));

                        // 2. Thêm lớp 'selected' vào biến thể được chọn
                        this.classList.add('selected');

                        // 3. Lấy thông tin từ data attributes của biến thể được chọn
                        const variantId = this.dataset.variantId;          // ID biến thể
                        const variantPrice = parseFloat(this.dataset.variantPrice); // Giá gốc
                        const discountPercent = parseFloat(this.dataset.discountPercent); // % giảm giá
                        const variantQuantity = parseInt(this.dataset.variantQuantity); // Số lượng tồn kho

                        // 4. Tính toán giá sau khi giảm (nếu có)
                        const finalPrice = discountPercent > 0
                            ? variantPrice * (1 - discountPercent/100)
                            : variantPrice;

                        // 5. Cập nhật thông tin lên giao diện
                        // - Giá hiển thị
                        document.getElementById('variant-price').textContent = finalPrice.toLocaleString('vi-VN');
                        // - Số lượng tồn kho
                        document.getElementById('variant-quantity').textContent = variantQuantity;
                        // - ID biến thể cho form thêm vào giỏ hàng
                        document.getElementById('variant-id-input').value = variantId;
                        // - ID biến thể cho form mua ngay
                        document.getElementById('buy-now-variant-id').value = variantId;

                        // 6. Xử lý hiển thị giá gốc và % giảm giá (nếu có)
                        const originalPriceContainer = document.getElementById('original-price-container');
                        const originalPriceElement = document.getElementById('original-price');
                        const discountPercentElement = document.getElementById('discount-percent');

                        if (discountPercent > 0) {
                            originalPriceElement.textContent = variantPrice.toLocaleString('vi-VN') + ' VND';
                            discountPercentElement.textContent = '-' + discountPercent + '%';
                            originalPriceContainer.style.display = 'block';
                        } else {
                            originalPriceContainer.style.display = 'none';
                        }

                        // 7. Cập nhật số lượng tối đa có thể mua (bằng số lượng tồn kho)
                        const quantityInput = document.querySelector('.js-result');
                        if (quantityInput) {
                            quantityInput.max = variantQuantity;

                            // Nếu số lượng hiện tại > tồn kho, điều chỉnh lại
                            if (parseInt(quantityInput.value) > variantQuantity) {
                                quantityInput.value = variantQuantity;
                                document.getElementById('quantity-input').value = variantQuantity;
                                document.getElementById('buy-now-quantity').value = variantQuantity;
                            }
                        }
                    });
                });

                // =============================================
                // PHẦN 2: XỬ LÝ THAY ĐỔI SỐ LƯỢNG SẢN PHẨM
                // =============================================

                // Các phần tử liên quan đến số lượng
                const quantityInput = document.querySelector('.js-result'); // Ô input hiển thị
                const quantityHiddenInput = document.getElementById('quantity-input'); // Input ẩn cho giỏ hàng
                const buyNowQuantityInput = document.getElementById('buy-now-quantity'); // Input ẩn cho mua ngay

                // Nút TĂNG số lượng
                document.querySelector('.js-plus').addEventListener('click', function(e) {
                    e.preventDefault();
                    let currentValue = parseInt(quantityInput.value);
                    // Lấy số lượng tối đa từ biến thể đang chọn
                    const maxQuantity = parseInt(document.querySelector('.variant-box.selected')?.dataset.variantQuantity || 999);

                    if (currentValue < maxQuantity) {
                        currentValue++;
                        // Cập nhật giá trị cho tất cả các input liên quan
                        quantityInput.value = currentValue;
                        quantityHiddenInput.value = currentValue;
                        buyNowQuantityInput.value = currentValue;
                    } else {
                        alert('Số lượng không được vượt quá tồn kho');
                    }
                });

                // Nút GIẢM số lượng
                document.querySelector('.js-minus').addEventListener('click', function(e) {
                    e.preventDefault();
                    let currentValue = parseInt(quantityInput.value);

                    if (currentValue > 1) {
                        currentValue--;
                        // Cập nhật giá trị cho tất cả các input liên quan
                        quantityInput.value = currentValue;
                        quantityHiddenInput.value = currentValue;
                        buyNowQuantityInput.value = currentValue;
                    }
                });

                // Xử lý khi nhập số lượng trực tiếp
                quantityInput.addEventListener('change', function() {
                    let value = parseInt(this.value) || 1;
                    // Lấy số lượng tối đa từ biến thể đang chọn
                    const maxQuantity = parseInt(document.querySelector('.variant-box.selected')?.dataset.variantQuantity || 999);

                    // Kiểm tra giá trị hợp lệ
                    if (value > maxQuantity) {
                        value = maxQuantity;
                        alert('Số lượng không được vượt quá tồn kho');
                    } else if (value < 1) {
                        value = 1;
                    }

                    // Cập nhật giá trị cho tất cả các input liên quan
                    this.value = value;
                    quantityHiddenInput.value = value;
                    buyNowQuantityInput.value = value;
                });

                // =============================================
                // KHỞI TẠO BAN ĐẦU
                // =============================================

                // Tự động chọn biến thể đầu tiên khi trang được tải
                if (variantBoxes.length > 0) {
                    variantBoxes[0].click();
                }
            });
            </script>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.reply-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var commentId = this.getAttribute('data-comment-id');
                var form = document.getElementById('reply-form-' + commentId);
                if (form) {
                    if (form.style.display === 'none') {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                }
            });
        });
    });
</script>
@endsection
