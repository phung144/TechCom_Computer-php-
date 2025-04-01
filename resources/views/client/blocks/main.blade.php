@extends('client.layout')

@section('main')
<!-- List Product -->
<div class="products-group-8-1 space-1 bg-gray-7 mb-6">
    <h2 class="sr-only">Products Grid</h2>
    <div class="container">
        <!-- List Product Title -->
        <div class="mb-4">
            <h3 class="text-center font-weight-bold">List Product</h3>
        </div>
        <!-- End List Product Title -->

        <div class="row no-gutters">
            @foreach($products as $product)
                <div class="col-md-6 col-lg-4 col-wd-3 product-item remove-divider">
                    <div class="product-item__outer h-100 w-100 prodcut-box-shadow">
                        <div class="product-item__inner bg-white p-3">
                            <div class="product-item__body pb-xl-2 position-relative">
                                {{-- Discount Badge --}}
                                <div class="discount-badge position-absolute top-0 left-0 bg-danger text-white px-2 py-1">
                                    @if($product->discount_type === 'percentage')
                                        Giảm {{ intval($product->discount_value ?? 0) }}%
                                    @else
                                        Giảm {{ number_format($product->discount_value ?? 0, 0) }}.000 VND
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <a href="#" class="d-block text-center">
                                        <img class="img-fluid" src="{{ asset(Storage::url($product->image)) }}" alt="{{ $product->name }}">
                                    </a>
                                </div>
                                <h5 class="mb-1 product-item__title">
                                    <a href="#" class="text-blue font-weight-bold">{{ $product->name }}</a>
                                </h5>
                                <p class="text-gray-6 font-size-13 description-clamp">{{ $product->description }}</p>
                                <div class="flex-center-between mb-1">
                                    <div class="prodcut-price">
                                        <span class="text-danger font-size-16 font-weight-bold">
                                            {{ number_format($product->final_price, 0) }} VND
                                        </span>
                                        <span class="text-gray-500 text-decoration-line-through font-size-14 ml-2">
                                            {{ number_format($product->price, 0) }} VND
                                        </span>
                                    </div>
                                    <div class="d-none d-xl-block prodcut-add-cart">
                                        <a href="#" class="btn-add-cart btn-primary transition-3d-hover">
                                            <i class="ec ec-add-to-cart"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="text-gray-6 font-size-13">Sold: {{ $product->sales ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="product-item__footer">
                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                    <a href="#" class="text-gray-6 font-size-13">
                                        <i class="ec ec-compare mr-1 font-size-15"></i> Compare
                                    </a>
                                    <a href="#" class="text-gray-6 font-size-13">
                                        <i class="ec ec-favorites mr-1 font-size-15"></i> Add to Wishlist
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Css xóa chữ "Showing 1 to 12 of 14 results" ở nút chuyển trang --}}
        <div class="mt-4 d-flex justify-content-center">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($products->links()->elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $products->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">&raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </div>
        {{-- End Css --}}


    </div>
</div>
<!-- End List Product -->

{{-- List Product Sale --}}
<div class="mb-6">
    <!-- Nav Classic -->
    <div class="position-relative bg-white text-center z-index-2">
        <ul class="nav nav-classic nav-tab justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link js-animation-link active" id="pills-sale-tab" data-toggle="pill" href="#pills-sale" role="tab" aria-controls="pills-sale" aria-selected="true">
                    <div class="d-md-flex justify-content-md-center align-items-md-center">
                        On Sale
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <!-- End Nav Classic -->

    <!-- Tab Content -->
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
                                            <div class="discount-badge position-absolute top-0 left-0 bg-danger text-white px-2 py-1">
                                                @if($product->discount_type === 'percentage')
                                                    Giảm {{ intval($product->discount_value ?? 0) }}%
                                                @else
                                                    Giảm {{ number_format($product->discount_value ?? 0, 0) }}.000 VND
                                                @endif
                                            </div>
                                            <div class="mb-2">
                                                <a href="#" class="d-block text-center">
                                                    <img class="img-fluid" src="{{ asset(Storage::url($product->image)) }}" alt="{{ $product->name }}">
                                                </a>
                                            </div>
                                            <h5 class="mb-1 product-item__title">
                                                <a href="#" class="text-blue font-weight-bold">{{ $product->name }}</a>
                                            </h5>
                                            <div class="prodcut-price-sale">
                                                <span class="final-price">
                                                    {{ number_format($product->final_price, 0) }} VND
                                                </span>
                                                <span class="original-price">
                                                    {{ number_format($product->price, 0) }} VND
                                                </span>
                                            </div>
                                            <!-- Add to Cart Button and Sales Count -->
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="text-gray-6 font-size-13">Sold: {{ $product->sales ?? 0 }}</span>
                                                <a href="#" class="btn-add-cart btn-primary transition-3d-hover">
                                                    <i class="ec ec-add-to-cart"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination và Navigation -->
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
    <!-- End Tab Content -->
</div>


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


<!-- Import Swiper.js -->
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

@endsection
