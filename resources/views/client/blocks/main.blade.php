@extends('client.layout')

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
            background: '#f0fff4',  // Màu nền xanh nhạt
            iconColor: '#38a169',   // Màu xanh lá đậm
            color: '#2f855a'       // Màu chữ xanh đậm
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
            background: '#fff5f5',  // Màu nền đỏ nhạt
            iconColor: '#e53e3e',    // Màu đỏ
            color: '#c53030'        // Màu chữ đỏ đậm
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
            background: '#ebf8ff',  // Màu nền xanh dương nhạt
            iconColor: '#3182ce',   // Màu xanh dương
            color: '#2c5282'       // Màu chữ xanh đậm
        });
    }

    // Xử lý thông báo từ session
    @if(session('success'))
        showSuccessAlert('{{ session('success') }}');
    @endif

    @if(session('error'))
        showErrorAlert('{{ session('error') }}');
    @endif

    @if(session('info'))
        showInfoAlert('{{ session('info') }}');
    @endif
</script>

{{-- List Category button --}}
<div>
    <div class="brand-wrapper">
        <div class="brand-container">
            @foreach($categories as $category)
                <form method="GET" action="{{ route('search-category') }}" style="display: inline;">
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <button type="submit" class="brand-box">
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                    </button>
                </form>
            @endforeach
        </div>
    </div>

    <style>
        .brand-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 15vh; /* Căn giữa theo chiều dọc */
        }

        .brand-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); /* Hiển thị tối đa 10 ô trên mỗi hàng */
            grid-auto-rows: 50px; /* Định chiều cao cho mỗi hàng */
            gap: 10px;
            justify-content: center; /* Căn giữa theo chiều ngang khi chưa đủ 10 ô */
            max-width: 1100px; /* Đảm bảo phù hợp với 10 ô */
        }

        .brand-box {
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 50px;
            padding: 5px;
        }

        .brand-box img {
            max-width: 80px;
            max-height: 40px;
            object-fit: contain;
        }

        .brand-box:hover {
            background-color: #f0f0f0;
        }
    </style>

</div>
{{-- End List Category button --}}

{{-- List Product --}}
{{-- List Product --}}
<div class="products-group-8-1 space-1 bg-gray-7 mb-6">
    <h2 class="sr-only">Products Grid</h2>
    <div class="container">
        <!-- List Product Title -->
        <div class="d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
            <h3 class="section-title section-title__full mb-0 pb-2 font-size-22">List Products</h3>
        </div>
        <!-- End List Product Title -->

        <div class="row no-gutters">
            @foreach($products as $product)
                <div class="col-md-6 col-lg-4 col-wd-3 product-item remove-divider">
                    <div class="product-item__outer h-100 w-100 prodcut-box-shadow">
                        <div class="product-item__inner bg-white p-3 position-relative">
                            @if($product->category)
                                <div class="mb-1">
                                    <a href="" class="font-size-11 text-gray-5">
                                        {{ $product->category->name }}
                                    </a>
                                </div>
                            @endif
                            <div class="product-item__body pb-xl-2">
                                <div class="product-thumbnail position-relative">
                                    <div class="mb-2">
                                        <a href="{{ route('product.detail', ['id' => $product->id]) }}" class="d-block text-center">
                                            <img class="img-fluid product-image" src="{{ asset(Storage::url($product->image)) }}" alt="{{ $product->name }}" style="transition: transform 0.3s;">
                                        </a>
                                    </div>
                                    @if($product->discount_value > 0)
                                        <div class="discount-badge position-absolute top-0 start-0 bg-danger text-white px-2 py-1 z-10">
                                            @if($product->discount_type === 'percentage')
                                                Giảm {{ intval($product->discount_value) }}%
                                            @else
                                                Giảm {{ number_format($product->discount_value, 0) }}.000 VND
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <br>
                                <h5 class="mb-1 product-item__title">
                                    <a href="{{ route('product.detail', ['id' => $product->id]) }}" class="text-blue font-weight-bold">{{ $product->name }}</a>
                                </h5>

                                <p class="text-gray-6 font-size-13 description-clamp" title="{{ $product->description }}">{{ $product->description }}</p>

                                <div class="flex-center-between mb-1">
                                    <div class="prodcut-price">
                                        <span class="text-danger font-size-16 font-weight-bold">
                                            {{ number_format($product->final_price, 0) }} VND
                                        </span>
                                        @if($product->discount_value > 0)
                                            <span class="text-gray-500 text-decoration-line-through font-size-14 ml-2">
                                                {{ number_format($product->display_price, 0) }} VND
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <div class="mb-2">
                                        @if(isset($product->cheapest_variant) && $product->cheapest_variant->quantity > 0)
                                            <span class="font-size-13">
                                                Còn hàng ({{ $product->cheapest_variant->quantity }})
                                            </span>
                                        @else
                                            <span class="font-size-13">Tạm hết hàng</span>
                                        @endif
                                    </div>


                                    <form action="{{ route('wishlist.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-cart btn-danger transition-3d-hover"
                                                style="border: none; outline: none; background: transparent; padding: 0;">
                                            <i class="ec ec-favorites" style="font-size: 25px; color: #ff3a3a;"></i>
                                        </button>
                                    </form>
                                </div>


                            </div>

                            <div class="product-item__footer">
                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                    <div class="mt-2">
                                        <span class="text-gray-6 font-size-13">Đã bán: {{ $product->sales ?? 0 }}</span>
                                    </div>
                                    @if($product->compare_price)
                                        <div class="mt-2">
                                            <span class="text-gray-6 font-size-13">So sánh giá: {{ number_format($product->compare_price, 0) }} VND</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3 d-flex justify-content-center">
            <nav aria-label="Page navigation">
                {{ $products->onEachSide(1)->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

<style>
    .product-item {
        transition: all 0.3s ease;
    }
    .product-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .product-image:hover {
        transform: scale(1.05);
    }
    .description-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .star-rating .active {
        color: #FFC107;
    }
</style>
<!-- End List Product -->

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
                                <h3 class="section-title section-title__full mb-0 pb-2 font-size-22">On Sale</h3>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade pt-2 show active" id="pills-sale" role="tabpanel" aria-labelledby="pills-sale-tab">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($discountedProducts as $product)
                                <div class="swiper-slide">
                                    <div class="product-item">
                                        <div class="product-item__outer h-100 w-100">
                                            <div class="product-item__inner px-xl-4 p-3">
                                                <div class="product-item__body pb-xl-2">
                                                    {{-- Discount Badge --}}
                                                    @if($product->discount_value > 0)
                                                        <div class="discount-badge position-absolute top-0 left-0 bg-danger text-white px-2 py-1">
                                                            @if($product->discount_type === 'percentage')
                                                                Giảm {{ intval($product->discount_value) }}%
                                                            @else
                                                                Giảm {{ number_format($product->discount_value, 0) }}.000 VND
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
                                                        @if($product->discount_value > 0)
                                                            <span class="original-price">
                                                                {{ number_format($product->display_price, 0) }} VND
                                                            </span>
                                                        @endif
                                                        <span class="final-price">
                                                            {{ number_format($product->final_price, 0) }} VND
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

{{-- List Best selling products --}}
<div>
    <div class="container">
        <div class="mb-6 position-relative">
            <div class="d-flex justify-content-between border-bottom border-color-1 flex-md-nowrap flex-wrap border-sm-bottom-0">
                <h3 class="section-title section-title__full mb-0 pb-2 font-size-22">Best Selling Products</h3>
            </div>
            <div class="row">
                @foreach($topSalesProducts as $product)
                    <div class="col-md-4 product-item product-item__card pb-2 mb-2 pb-md-0 mb-md-0">
                        <div class="product-item__outer h-100 w-100">
                            <div class="product-item__inner p-md-3 row no-gutters">
                                <div class="position-relative">
                                    {{-- Badge giảm giá --}}
                                    @if($product->discount_value > 0)
                                        <div class="discount-badge position-absolute top-0 left-0 bg-danger text-white px-2 py-1" style="font-size: 12px; z-index: 2;">
                                            @if($product->discount_type === 'percentage')
                                                Giảm {{ intval($product->discount_value) }}%
                                            @else
                                                Giảm {{ number_format($product->discount_value, 0) }}.000 VND
                                            @endif
                                        </div>
                                    @endif

                                    <a href="{{ route('product.detail', ['id' => $product->id]) }}" class="max-width-150 d-block">
                                        <img class="img-fluid" src="{{ asset(Storage::url($product->image)) }}" alt="{{ $product->name }}">
                                    </a>
                                </div>

                                <div class="col product-item__body pl-2 pl-lg-3">
                                    <div class="mb-4">
                                        <h5 class="product-item__title">
                                            <a href="{{ route('product.detail', ['id' => $product->id]) }}" class="text-blue font-weight-bold">{{ $product->name }}</a>
                                        </h5>
                                    </div>
                                    <div class="flex-center-between mb-3">
                                        <div class="prodcut-price best-selling-price">
                                            @if($product->discount_value > 0)
                                                <div class="original-price">{{ number_format($product->display_price, 0) }} VND</div>
                                            @endif
                                            <div class="final-price">{{ number_format($product->final_price, 0) }} VND</div>
                                        </div>
                                        <div class="d-none d-xl-block prodcut-add-cart">
                                            <div class="d-none d-xl-block prodcut-add-cart">
                                                <form action="{{ route('wishlist.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn-add-cart btn-danger transition-3d-hover"
                                                            style="border: none; outline: none; background: transparent; padding: 0;">
                                                        <i class="ec ec-favorites" style="font-size: 25px; color: #ff3a3a;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-item__footer">
                                        <div class="border-top pt-2 d-flex justify-content-between">
                                            {{-- Trái --}}
                                            @if(isset($product->cheapest_variant) && $product->cheapest_variant->quantity > 0)
                                                <span class="text-gray-6 font-size-13">
                                                    Còn hàng ({{ $product->cheapest_variant->quantity }})
                                                </span>
                                            @else
                                                <span class="text-gray-6 font-size-13">Tạm hết hàng</span>
                                            @endif

                                            {{-- Phải --}}
                                            <span class="text-gray-6 font-size-13">Sold: {{ $product->sales }}</span>
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
    {{-- Css for Best Selling Products --}}
    <style>
        .best-selling-price {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-top: 8px;
        }

        .best-selling-price .final-price {
            color: #dc3545; /* Màu đỏ cho giá sau khi giảm */
            font-size: 20px; /* Tăng kích thước để nổi bật */
            font-weight: bold;
        }

        .best-selling-price .original-price {
            color: #6c757d; /* Màu xám cho giá cũ */
            font-size: 14px; /* Nhỏ hơn giá sau khi giảm */
            text-decoration: line-through;
            margin-top: 4px; /* Khoảng cách giữa giá cũ và giá mới */
        }
        </style>
</div>
{{-- End List Best selling products --}}

<script>
    function addToWishlist(name, image, price, productId) {
        fetch('{{ route('wishlist.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                name_product: name,
                image_product: image,

            })
        })
        .then(response => {
            if (response.status === 401) {
                alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

@endsection
