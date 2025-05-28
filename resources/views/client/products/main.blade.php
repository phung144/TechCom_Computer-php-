@extends('client.layout')

@section('main')
    <div>
        {{-- Thông báo add to cart thành công --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if (session('success'))
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
                    @if (!empty($product->photos) && is_array($product->photos))
                        <div class="mt-3 d-flex flex-wrap">
                            <!-- Include the main image as the first thumbnail -->
                            <div class="mr-2 mb-2">
                                <img src="{{ Storage::url($product->image) }}" alt="Main Photo"
                                    class="img-thumbnail thumbnail-hover" style="cursor: pointer;"
                                    onclick="document.querySelector('.main-image').src='{{ Storage::url($product->image) }}'">
                            </div>
                            @foreach ($product->photos as $photo)
                                <div class="mr-2 mb-2">
                                    <img src="{{ Storage::url($photo) }}" alt="Additional Photo"
                                        class="img-thumbnail thumbnail-hover" style="cursor: pointer;"
                                        onclick="document.querySelector('.main-image').src='{{ Storage::url($photo) }}'">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-md-7 mb-md-6 mb-lg-0 mt-6">
                    <!-- Phần thông tin sản phẩm -->
                    <div class="border-bottom mb-3 pb-md-1 pb-3">
                        <a href="#" class="font-size-12 text-gray-5 mb-2 d-inline-block">Laptops
                            {{ $product->category->name }}</a>
                        <h2 class="font-size-25 text-lh-1dot2">{{ $product->name }}</h2>
                        <div class="mb-2">
                            <div class="text-warning mr-2">
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="far fa-star text-muted"></small>
                            </div>
                            <span class="text-secondary font-size-13">({{ $feedbacks->total() }} customer reviews)</span>
                        </div>
                        <div class="ml-md-3 text-gray-9 font-size-14">Availability:
                            <span class="text-green font-weight-bold"
                                id="variant-quantity">{{ $variants->first()->quantity }}</span> in stock
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
                            $discountType = $product->discount_type;
                            $discountValue = $product->discount_value;
                            $isDiscount = isset($isDiscountActive) && $isDiscountActive && $discountValue > 0;
                            if ($isDiscount) {
                                if ($discountType === 'percentage') {
                                    $discountedPrice = $originalPrice * (1 - $discountValue / 100);
                                } elseif ($discountType === 'fixed') {
                                    $discountedPrice = max(0, $originalPrice - $discountValue);
                                } else {
                                    $discountedPrice = $originalPrice;
                                }
                            } else {
                                $discountedPrice = $originalPrice;
                            }
                        @endphp

                        <div class="mb-4">
                            <div class="d-flex align-items-baseline">
                                <!-- Giá mới sau khi giảm -->
                                <span class="font-size-36 text-red mr-3">
                                    <span id="variant-price">{{ number_format($discountedPrice) }}</span> VND
                                </span>

                                <!-- Giá cũ và % giảm giá (chỉ hiện khi có discount và trong thời gian giảm giá) -->
                                <div id="original-price-container"
                                    @if (!$isDiscount) style="display:none;" @endif>
                                    <del class="font-size-16 text-gray-6"
                                        id="original-price">{{ number_format($originalPrice) }} VND</del>
                                    <span class="badge badge-danger ml-2"
                                        id="discount-percent">
                                        @if($discountType === 'percentage')
                                            -{{ $discountValue }}%
                                        @elseif($discountType === 'fixed')
                                            -{{ number_format($discountValue, 0) }} VND
                                        @endif
                                    </span>
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
                                $isVariantDiscount = isset($isDiscountActive) && $isDiscountActive && $discountValue > 0;
                                if ($isVariantDiscount) {
                                    if ($discountType === 'percentage') {
                                        $variantDiscountedPrice = $variantOriginalPrice * (1 - $discountValue / 100);
                                    } elseif ($discountType === 'fixed') {
                                        $variantDiscountedPrice = max(0, $variantOriginalPrice - $discountValue);
                                    } else {
                                        $variantDiscountedPrice = $variantOriginalPrice;
                                    }
                                } else {
                                    $variantDiscountedPrice = $variantOriginalPrice;
                                }
                                $variantShowDiscount = $isVariantDiscount;
                                $isOutOfStock = $variant->quantity == 0;
                            @endphp

                            <div class="variant-box {{ $loop->first ? 'selected' : '' }}{{ $isOutOfStock ? ' out-of-stock' : '' }}"
                                data-variant-id="{{ $variant->id }}" data-variant-price="{{ $variant->price }}"
                                data-variant-quantity="{{ $variant->quantity }}"
                                data-discount-percent="{{ (isset($isDiscountActive) && $isDiscountActive && $discountType === 'percentage') ? $discountValue : 0 }}"
                                data-discount-fixed="{{ (isset($isDiscountActive) && $isDiscountActive && $discountType === 'fixed') ? $discountValue : 0 }}"
                                @if($isOutOfStock) style="pointer-events: none; opacity: 0.5; position: relative;" @endif
                            >

                                <div class="variant-specs">
                                    @foreach ($variant->options as $option)
                                        <span class="spec-item">
                                            {{ strtoupper($option->variant->name) }}({{ $option->value }})
                                        </span>
                                        @if (!$loop->last)
                                            -
                                        @endif
                                    @endforeach
                                </div>

                                <div class="price-container">
                                    <span class="final-price">{{ number_format($variantDiscountedPrice) }} VND</span>
                                    @if ($variantShowDiscount)
                                        <del class="original-price">{{ number_format($variantOriginalPrice) }} VND</del>
                                    @endif
                                </div>
                                @if($isOutOfStock)
                                    <div class="out-of-stock-label"
                                        style="position: absolute; top: 8px; right: 8px; background: #dc3545; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 13px;">
                                        Hết hàng
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Phần số lượng và nút mua hàng -->
                    <div class="max-width-150 my-4">
                        <h6 class="font-size-14">Số lượng</h6>
                        <div class="border rounded-pill py-2 px-3 border-color-1">
                            <div class="js-quantity row align-items-center">
                                <div class="col">
                                    <input class="js-result form-control h-auto border-0 rounded p-0 shadow-none"
                                        type="text" value="1">
                                </div>
                                <div class="col-auto pr-1">
                                    <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0"
                                        href="javascript:;">
                                        <small class="fas fa-minus btn-icon__inner"></small>
                                    </a>
                                    <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0"
                                        href="javascript:;">
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
                                <input type="hidden" name="variant_id" id="variant-id-input"
                                    value="{{ $variants->first()->id }}">
                                <input type="hidden" name="quantity" id="quantity-input" value="1">

                                <button type="submit" class="btn btn-primary-dark transition-3d-hover">
                                    <i class="ec ec-add-to-cart mr-2 font-size-20"></i> Thêm vào giỏ hàng
                                </button>
                            </form>
                        @else
                            <!-- Nút xử lý bằng SweetAlert khi chưa đăng nhập -->
                            <button onclick="handleAddToCart()" class="btn btn-primary-dark transition-3d-hover mr-2 mb-2">
                                <i class="ec ec-add-to-cart mr-2 font-size-20"></i> Thêm vào giỏ hàng
                            </button>
                        @endauth

                        <script>
                            function handleAddToCart() {
                                Swal.fire({
                                    title: 'Ready to Checkout?',
                                    html: `
                                            <div class="text-center py-3">
                                                <i class="fas fa-shopping-bag fa-3x text-primary mb-3"></i>
                                                <p class="mb-2">Đăng nhập để hoàn tất giao dịch của bạn.</p>
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
                                <form action="{{ route('cart.add') }}" method="POST" id="order-now-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="variant_id" id="buy-now-variant-id"
                                        value="{{ $variants->first()->id }}">
                                    <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
                                    <input type="hidden" name="orderNow" value="1">
                                    <button type="submit" class="btn btn-success transition-3d-hover mb-2">
                                        <i class="ec ec-credit-card mr-2 font-size-20"></i> Đặt hàng ngay
                                    </button>
                                </form>
                            @else
                                <!-- Nút cho user chưa đăng nhập -->
                                <button onclick="handleOrderNow()" class="btn btn-success transition-3d-hover mb-2">
                                    <i class="ec ec-credit-card mr-2 font-size-20"></i> Đặt hàng ngay
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
                                        <p class="mb-2">Đăng nhập để hoàn tất giao dịch của bạn.</p>
                                        <small class="text-muted">Enjoy faster checkout and order tracking</small>
                                    </div>
                                `,
                                    showCancelButton: true,
                                    confirmButtonText: '<i class="fas fa-sign-in-alt mr-2"></i> Đăng nhập ngay',
                                    cancelButtonText: 'Tiếp tục mua sắm',
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

            <!-- Feedback Section -->
            <div class="feedback-section mt-5">
                <h2 class="mb-4 font-weight-bold text-dark">Đánh giá của khách hàng</h2>



                <!-- Feedback List -->
                <div class="feedback-list">
                    @foreach ($feedbacks as $feedback)
                        <div class="feedback-item card mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex">
                                    <!-- User Avatar -->
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="avatar avatar-lg">
                                            @if ($feedback->user && $feedback->user->image)
                                                <img src="{{ Storage::url($feedback->user->image) }}" alt="User"
                                                    class="rounded-circle">
                                            @else
                                                <div
                                                    class="avatar-initials bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                    {{ substr($feedback->user->name ?? 'U', 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Feedback Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <h5 class="mb-0 font-weight-bold">
                                                    {{ $feedback->user->name ?? 'Anonymous' }}</h5>
                                                <div class="rating-stars small">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="fas fa-star{{ $i <= $feedback->rating ? ' text-warning' : ' text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $feedback->created_at->format('M d, Y') }}
                                            </small>
                                        </div>

                                        <div class="feedback-content mb-3">
                                            <p class="mb-0">{{ $feedback->content }}</p>
                                        </div>

                                        @if (!empty($feedback->image))
                                            <div class="feedback-images mt-3">
                                                <a href="{{ Storage::url($feedback->image) }}"
                                                    data-fancybox="feedback-gallery">
                                                    <img src="{{ Storage::url($feedback->image) }}"
                                                        class="img-thumbnail mr-2 mb-2" style="max-height: 100px;">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $feedbacks->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>

            <!-- Comment Section -->
            <div class="comment-section mt-5">
                <h2 class="mb-4 font-weight-bold text-dark">Thảo luận về sản phẩm</h2>

                <!-- Comment Form -->
                @auth
                    <div class="comment-form card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="avatar avatar-lg">
                                        @if (Auth::user()->image)
                                            <img src="{{ Storage::url(Auth::user()->image) }}" alt="User"
                                                class="rounded-circle">
                                        @else
                                            <div
                                                class="avatar-initials bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <form action="{{ route('comment') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="form-group mb-3">
                                            <textarea name="comment" class="form-control" rows="3" placeholder="Share your thoughts about this product..."
                                                style="border-radius: 12px;"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notify-replies">
                                                <label class="form-check-label small text-muted" for="notify-replies">
                                                    Notify me about replies
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fas fa-paper-plane mr-2"></i> Đăng bình luận
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-light d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        <span>Please <a href="{{ route('login') }}" class="font-weight-bold">sign in</a> Đăng bình luận của bạn.</span>
                    </div>
                @endauth

                <!-- Comment List -->
                <div class="comment-list">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 font-weight-bold">{{ $comments->total() }} Bình luận</h5>

                    </div>

                    @forelse ($comments as $comment)
                        <div class="comment-item card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0 mr-3">
                                        @if ($comment->user && $comment->user->image)
                                            <img src="{{ asset('storage/' . $comment->user->image) }}"
                                                alt="Avatar" class="rounded-circle" width="50" height="50">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}"
                                                alt="Avatar" class="rounded-circle" width="50" height="50">
                                        @endif
                                    </div>
                                    <!-- Nội dung bình luận chính -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h5 class="mb-0 font-weight-bold">
                                                    {{ $comment->user->name }}</h5>
                                                <small class="text-muted">
                                                    <i class="far fa-clock mr-1"></i>{{ $comment->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            @if (Auth::check() && Auth::id() == $comment->user_id)
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-text dropdown-toggle"
                                                        type="button" data-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    {{-- <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editCommentModal-{{ $reply->id }}">Sửa</a>
                                                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('delete-comment-{{ $reply->id }}').submit();">Xóa</a>
                                                        <form id="delete-comment-{{ $reply->id }}" action="{{ route('comment.delete', $reply->id) }}" method="POST" style="display: none;">
                                                            @csrf @method('DELETE')
                                                        </form>
                                                    </div> --}}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="comment-content mb-2">
                                            {{ $comment->comment }}
                                        </div>
                                        <div class="comment-actions d-flex align-items-center">
                                            <button class="btn btn-text btn-sm mr-3 like-btn"
                                                data-comment-id="{{ $comment->id }}">
                                                <i class="far fa-thumbs-up mr-1"></i>
                                                <span>{{ $comment->likes_count ?? 0 }}</span>
                                            </button>
                                            <button class="btn btn-text btn-sm reply-btn"
                                                data-comment-id="{{ $comment->id }}">
                                                <i class="far fa-comment-dots mr-1"></i> Phản hồi
                                            </button>
                                        </div>
                                        <!-- Form rep comment (ẩn, hiện khi bấm Phản hồi) -->
                                        <div class="reply-form mt-2"
                                            id="reply-form-{{ $comment->id }}"
                                            style="display: none;">
                                            @if (Auth::check())
                                                <form
                                                    action="{{ route('comments.reply.client', $comment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $product->id }}">
                                                    <input type="hidden" name="parent_id"
                                                        value="{{ $comment->id }}">
                                                    <div class="form-group mb-2">
                                                        <textarea name="comment" class="form-control shadow-sm" rows="2" placeholder="Viết phản hồi..."
                                                            style="border-radius: 20px;"></textarea>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm px-3 py-1"
                                                            style="border-radius: 20px; background-color: #a49e20; border: none;">
                                                            <i class="fas fa-paper-plane mr-1"></i>Gửi
                                                            phản hồi
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                <div
                                                    class="alert alert-light border mb-2 d-flex align-items-center">
                                                    <i
                                                        class="fas fa-info-circle mr-2 text-warning"></i>
                                                    <span>Vui lòng <a href="{{ route('login') }}"
                                                            class="font-weight-bold text-primary">đăng
                                                            nhập</a> để phản hồi.</span>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Hiển thị rep comment -->
                                        @if ($comment->replies && $comment->replies->count())
                                            <div class="ml-5 mt-3">
                                                @foreach ($comment->replies as $reply)
                                                    <div
                                                        class="comment-reply mb-2 p-2 bg-light rounded">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <strong
                                                                class="mr-2 text-primary">{{ $reply->user->name ?? 'Admin' }}</strong>
                                                            <small
                                                                class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <div>{{ $reply->comment }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state text-center py-5">
                            <i class="far fa-comments fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có bình luận nào</h5>
                            <p class="text-muted">Hãy là người đầu tiên chia sẻ suy nghĩ của bạn!</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $comments->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>


            {{-- List Product Sale --}}
<div>
    <div>
        <div class="mb-6">
            <!-- Nav Classic -->
            <div class="position-relative bg-white text-center z-index-2">
                <ul class="nav nav-classic nav-tab justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link js-animation-link active" id="pills-sale-tab" data-toggle="pill" href="#pills-sale" role="tab" aria-controls="pills-sale" aria-selected="true">
                            <div class="d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                                <h3 class="section-title section-title__full mb-0 pb-2 font-size-22">Danh sách sản phẩm của danh mục</h3>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade pt-2 show active" id="pills-sale" role="tabpanel" aria-labelledby="pills-sale-tab">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($categoryProducts as $product)
                                @php
                                    $cheapestVariant = $product->getCheapestVariant();
                                    $originalPrice = $cheapestVariant ? $cheapestVariant->price : 0;
                                    $discountType = $product->discount_type;
                                    $discountValue = $product->discount_value;
                                    if ($discountType === 'percentage') {
                                        $finalPrice = $originalPrice * (1 - $discountValue / 100);
                                    } elseif ($discountType === 'fixed') {
                                        $finalPrice = max(0, $originalPrice - $discountValue);
                                    } else {
                                        $finalPrice = $originalPrice;
                                    }
                                @endphp
                                <div class="swiper-slide">
                                    <div class="product-item">
                                        <div class="product-item__outer h-100 w-100">
                                            <div class="product-item__inner px-xl-4 p-3">
                                                <div class="product-item__body pb-xl-2">
                                                    {{-- Discount Badge --}}
                                                    @if($discountValue > 0)
                                                        <div class="discount-badge position-absolute top-0 left-0 bg-danger text-white px-2 py-1">
                                                            @if($discountType === 'percentage')
                                                                Giảm {{ intval($discountValue) }}%
                                                            @else
                                                                Giảm {{ number_format($discountValue, 0) }} VND
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <div class="mb-2">
                                                        <a href="{{ route('product.detail', ['id' => $product->id]) }}" class="d-block text-center">
                                                            <img class="img-fluid" src="{{ asset(Storage::url($product->image)) }}" alt="{{ $product->name }}">
                                                        </a>
                                                    </div>
                                                    <h5 class="mb-1 product-item__title">
                                                        <a href="{{ route('product.detail', ['id' => $product->id]) }}" class="text-blue font-weight-bold">{{ $product->name }}</a>
                                                    </h5>
                                                    <div class="prodcut-price-sale">
                                                        @if($discountValue > 0)
                                                            <span class="original-price">
                                                                {{ number_format($originalPrice, 0) }} VND
                                                            </span>
                                                        @endif
                                                        <span class="final-price">
                                                            {{ number_format($finalPrice, 0) }} VND
                                                        </span>
                                                    </div>
                                                    <!-- Add to Cart Button and Sales Count -->
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <span class="text-gray-6 font-size-13">Sold: {{ $product->sales ?? 0 }}</span>
                                                        <div class="d-none d-xl-block prodcut-add-cart">
                                                            <form action="{{ route('wishlist.add') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <input type="hidden" name="quantity" value="1">
                                                                <button type="submit" class="btn-add-cart btn-danger transition-3d-hover"
                                                                        style="border: none; outline: none; background: transparent; padding: 0;">
                                                                    <i class="ec ec-favorites" style="font-size: 20px; color: #ff3a3a;"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Css List Product Sale --}}
        <style>
            .swiper-container {
                width: 100%;
                max-width: 1200px; /* Giới hạn chiều rộng */
                margin: 0 auto; /* Căn giữa */
                overflow: hidden; /* Ẩn phần tràn ra */
            }

            .swiper-wrapper {
                display: flex;
                align-items: center; /* Căn giữa các sản phẩm */
            }

            .swiper-slide {
                display: flex;
                justify-content: center;
                max-width: 200px; /* Giới hạn kích thước sản phẩm */
            }

            /* Style for price in List Product Sale */
            .prodcut-price-sale {
                display: flex;
                flex-direction: column; /* Stack prices vertically */
                align-items: flex-start; /* Align prices to the left */
            }

            .prodcut-price-sale .final-price {
                color: #dc3545; /* Red color for discounted price */
                font-size: 18px;
                font-weight: bold;
            }

            .prodcut-price-sale .original-price {
                color: #6c757d; /* Gray color for original price */
                font-size: 14px;
                text-decoration: line-through;
                margin-top: 4px; /* Add spacing between prices */
            }
            </style>

    {{-- End Css for Best Selling Products --}}
    </div>
    {{-- JS List Product Sale --}}
    <div>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: 6,  // Hiển thị 6 sản phẩm cùng lúc
                spaceBetween: 10,  // Khoảng cách giữa các sản phẩm
                loop: true,  // Cho phép chạy vô hạn
                autoplay: {
                    delay: 2000,  // Tự động chạy sau mỗi 2 giây
                    disableOnInteraction: false // Không dừng khi người dùng tương tác
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    1024: { slidesPerView: 6 },
                    768: { slidesPerView: 4 },
                    480: { slidesPerView: 2 }
                }
            });
        });
    </script>
    </div>

</div>
{{-- End List Product Sale --}}

            <!-- CSS Styles -->
            <style>
                /* General Styles */
                .avatar {
                    width: 48px;
                    height: 48px;
                    position: relative;
                }

                .avatar-lg {
                    width: 56px;
                    height: 56px;
                }

                .avatar-sm {
                    width: 36px;
                    height: 36px;
                }

                .avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .avatar-initials {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    font-weight: bold;
                }

                /* Feedback Section */
                .review-summary {
                    background-color: #f8f9fa;
                    border: 1px solid #e9ecef;
                }

                .rating-bars .progress {
                    border-radius: 4px;
                    background-color: #e9ecef;
                }

                .rating-stars {
                    color: #ffc107;
                    letter-spacing: 2px;
                }

                .rating-stars.small {
                    font-size: 0.8rem;
                }

                /* Comment Section */
                .comment-form textarea {
                    resize: none;
                    transition: all 0.3s;
                }

                .comment-form textarea:focus {
                    border-color: #80bdff;
                    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
                }

                .comment-item {
                    transition: transform 0.2s;
                }

                .comment-item:hover {
                    transform: translateY(-2px);
                }

                .like-btn,
                .reply-btn {
                    transition: all 0.2s;
                }

                .like-btn:hover,
                .reply-btn:hover {
                    color: #007bff !important;
                }

                .empty-state {
                    background-color: #f8f9fa;
                    border-radius: 8px;
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {
                    .review-summary .col-md-3 {
                        margin-bottom: 1.5rem;
                    }

                    .avatar,
                    .avatar-lg {
                        width: 40px;
                        height: 40px;
                    }
                }
            </style>

            <!-- JavaScript Enhancements -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize Fancybox for image gallery
                    if (typeof Fancybox !== 'undefined') {
                        Fancybox.bind("[data-fancybox]", {
                            // Options
                        });
                    }

                    // Like button functionality
                    document.querySelectorAll('.like-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const commentId = this.dataset.commentId;
                            // AJAX call to like the comment
                            console.log('Liked comment:', commentId);

                            // Temporary UI update
                            const likeCount = this.querySelector('span');
                            if (likeCount) {
                                const currentLikes = parseInt(likeCount.textContent);
                                likeCount.textContent = currentLikes + 1;
                            }

                            this.classList.add('text-primary');
                        });
                    });

                    // Reply button functionality
                    document.querySelectorAll('.reply-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const commentId = this.dataset.commentId;
                            // Logic to show reply form
                            console.log('Reply to comment:', commentId);
                        });
                    });
                });
            </script>


        </div>
    </div>

    <!-- CSS cmt -->
    <style>
        /* CSS cho phần variant */
        .variant-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            /* Khoảng cách giữa các ô */
            margin: 15px 0;
        }

        .variant-box {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: calc(50% - 8px);
            /* 2 ô mỗi hàng, trừ đi khoảng cách */
            box-sizing: border-box;
            min-height: 100px;
            /* Chiều cao tối thiểu */
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            margin-top: auto;
            /* Đẩy phần giá xuống dưới */
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
            width: 100px;
            /* Fixed width for thumbnails */
            height: auto;
            /* Maintain aspect ratio */
            object-fit: cover;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .thumbnail-hover:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Use the main image's dimensions as the standard */
        .main-image {
            width: 100%;
            /* Adjust to fit the container */
            height: auto;
            /* Maintain aspect ratio */
            max-width: 500px;
            /* Optional: Limit maximum size */
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
                        const variantId = this.dataset.variantId; // ID biến thể
                        const variantPrice = parseFloat(this.dataset.variantPrice); // Giá gốc
                        const discountPercent = parseFloat(this.dataset.discountPercent); // % giảm giá
                        const variantQuantity = parseInt(this.dataset
                            .variantQuantity); // Số lượng tồn kho

                        // 4. Tính toán giá sau khi giảm (nếu có)
                        const finalPrice = discountPercent > 0 ?
                            variantPrice * (1 - discountPercent / 100) :
                            variantPrice;

                        // 5. Cập nhật thông tin lên giao diện
                        // - Giá hiển thị
                        document.getElementById('variant-price').textContent = finalPrice
                            .toLocaleString('vi-VN');
                        // - Số lượng tồn kho
                        document.getElementById('variant-quantity').textContent = variantQuantity;
                        // - ID biến thể cho form thêm vào giỏ hàng
                        document.getElementById('variant-id-input').value = variantId;
                        // - ID biến thể cho form mua ngay
                        document.getElementById('buy-now-variant-id').value = variantId;

                        // 6. Xử lý hiển thị giá gốc và % giảm giá (nếu có)
                        const originalPriceContainer = document.getElementById(
                            'original-price-container');
                        const originalPriceElement = document.getElementById('original-price');
                        const discountPercentElement = document.getElementById('discount-percent');

                        if (discountPercent > 0) {
                            originalPriceElement.textContent = variantPrice.toLocaleString('vi-VN') +
                                ' VND';
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
                    const maxQuantity = parseInt(document.querySelector('.variant-box.selected')?.dataset
                        .variantQuantity || 999);

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
                    const maxQuantity = parseInt(document.querySelector('.variant-box.selected')?.dataset
                        .variantQuantity || 999);

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
