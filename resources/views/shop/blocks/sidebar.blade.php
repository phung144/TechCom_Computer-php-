<div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
    <div class="mb-6 border border-width-2 border-color-3 borders-radius-6">
        <!-- List Categories -->
        <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar">
            <li class="mb-3">
                <div
                    class="font-weight-bold text-uppercase font-size-16 pb-2 border-bottom border-primary border-width-2 d-inline-block">
                   Danh mục sản phẩm
                </div>
            </li>

            @foreach ($categories as $category)
                <li class="mb-2">
                    <a href="{{ route('category.products', $category->id) }}"
                        class="d-block py-2 px-3 text-dark text-decoration-none rounded hover-category">
                        <span class="d-flex align-items-center">
                            <span class="flex-grow-1">{{ $category->name }}</span>
                            <i class="fas fa-angle-right ml-2 text-muted"></i>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>

        <style>
            .hover-category {
                transition: all 0.3s ease;
                border-left: 3px solid transparent;
            }

            .hover-category:hover {
                background-color: rgba(0, 123, 255, 0.08);
                color: #007bff !important;
                transform: translateX(5px);
                border-left-color: #007bff;
                text-decoration: none;
            }

            .sidebar-navbar {
                background: #fff;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .border-width-2 {
                border-width: 2px !important;
            }
        </style>
        <!-- End List Categories -->
    </div>

    <!-- On Sales Products -->
    <div class="mb-8">
        <div class="border-bottom border-color-1 mb-5">
            <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Sản phẩm đang giảm giá</h3>
        </div>
        <ul class="list-unstyled">
            @foreach ($onSaleProducts as $product)
                @php
                    $originalPrice = $product->display_price ?? 0;
                    $finalPrice = $product->final_price ?? $originalPrice;
                @endphp
                <li class="mb-4 product-item-hover">
                    <div class="row">
                        <div class="col-auto">
                            <a href="{{ route('product.detail', $product->id) }}" class="d-block width-75">
                                <img class="img-fluid product-image-hover"
                                    src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            </a>
                        </div>
                        <div class="col">
                            <h3 class="text-lh-1dot2 font-size-14 mb-0 product-title-hover"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                <a href="{{ route('product.detail', $product->id) }}"
                                    class="text-dark hover-primary">{{ $product->name }}</a>
                            </h3>
                            <div class="font-weight-bold">
                                @if ($finalPrice < $originalPrice)
                                    <del class="font-size-11 text-gray-9 d-block">{{ number_format($originalPrice, 0) }} VND</del>
                                @endif
                                <ins class="font-size-15 text-red text-decoration-none d-block hover-red">{{ number_format($finalPrice, 0) }} VND</ins>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- End On Sales Products -->


    <!-- Best Selling Products -->
    <div class="mb-8">
        <div class="border-bottom border-color-1 mb-5">
            <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Sản phẩm bán chạy</h3>
        </div>
        <ul class="list-unstyled">
            @foreach ($topSalesProducts as $product)
                @php
                    $originalPrice = $product->display_price ?? 0;
                    $finalPrice = $product->final_price ?? $originalPrice;
                @endphp
                <li class="mb-4 product-item-hover">
                    <div class="row">
                        <div class="col-auto">
                            <a href="{{ route('product.detail', $product->id) }}" class="d-block width-75">
                                <img class="img-fluid product-image-hover"
                                    src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            </a>
                        </div>
                        <div class="col">
                            <h3 class="text-lh-1dot2 font-size-14 mb-0 product-title-hover"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; height: 2.6em;">
                                <a href="{{ route('product.detail', $product->id) }}" class="text-dark hover-primary">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="font-weight-bold">
                                @if ($finalPrice < $originalPrice)
                                    <del class="font-size-11 text-gray-9 d-block">{{ number_format($originalPrice, 0) }} VND</del>
                                @endif
                                <ins class="font-size-15 text-red text-decoration-none d-block hover-red">{{ number_format($finalPrice, 0) }} VND</ins>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- End Best Selling Products -->


</div>

<!-- Styles -->
<style>
    /* Hiệu ứng hover cho toàn bộ item sản phẩm */
    .product-item-hover:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
    }

    /* Hiệu ứng hover cho ảnh sản phẩm */
    .product-image-hover {
        transition: all 0.3s ease;
    }

    .product-image-hover:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }

    /* Hiệu ứng hover cho tiêu đề sản phẩm */
    .hover-primary:hover {
        color: #007bff !important;
        /* Màu primary */
        text-decoration: none;
    }

    /* Hiệu ứng hover cho giá */
    .hover-red:hover {
        color: #dc3545 !important;
        /* Màu đỏ đậm hơn */
    }

    /* Hiệu ứng tổng thể khi hover vào item */
    .product-item-hover {
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 4px;
    }

    .product-item-hover:hover {
        background-color: #f8f9fa;
        /* Màu nền nhạt khi hover */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>
