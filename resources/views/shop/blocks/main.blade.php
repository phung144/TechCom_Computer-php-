@extends('shop.layout')

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

<div class="col-xl-9 col-wd-9gdot5">
    <!-- Shop-control-bar Title -->
    {{-- <div class="flex-center-between mb-3">
        <h3 class="font-size-25 mb-0">Shop</h3>
        <p class="font-size-14 text-gray-90 mb-0">Hiển thị 1–25 trong số 56 kết quả</p>
    </div>
    <!-- End shop-control-bar Title -->
    <!-- Shop-control-bar -->
    <div class="bg-gray-1 flex-center-between borders-radius-9 py-1">
        <div class="d-xl-none">
            <!-- Account Sidebar Toggle Button -->
            <a id="sidebarNavToggler1" class="btn btn-sm py-1 font-weight-normal" href="javascript:;" role="button"
                aria-controls="sidebarContent1"
                aria-haspopup="true"
                aria-expanded="false"
                data-unfold-event="click"
                data-unfold-hide-on-scroll="false"
                data-unfold-target="#sidebarContent1"
                data-unfold-type="css-animation"
                data-unfold-animation-in="fadeInLeft"
                data-unfold-animation-out="fadeOutLeft"
                data-unfold-duration="500">
                <i class="fas fa-sliders-h"></i> <span class="ml-1">Bộ lọc</span>
            </a>
            <!-- End Account Sidebar Toggle Button -->
        </div>
        <div class="px-3 d-none d-xl-block">
            <ul class="nav nav-tab-shop" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-one-example1-tab" data-toggle="pill" href="#pills-one-example1" role="tab" aria-controls="pills-one-example1" aria-selected="false">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-th"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-two-example1-tab" data-toggle="pill" href="#pills-two-example1" role="tab" aria-controls="pills-two-example1" aria-selected="false">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-align-justify"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-three-example1-tab" data-toggle="pill" href="#pills-three-example1" role="tab" aria-controls="pills-three-example1" aria-selected="true">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-list"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-four-example1-tab" data-toggle="pill" href="#pills-four-example1" role="tab" aria-controls="pills-four-example1" aria-selected="true">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-th-list"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <nav class="px-3 flex-horizontal-center text-gray-20 d-none d-xl-flex">
            <form method="post" class="min-width-50 mr-1">
                <input size="2" min="1" max="3" step="1" type="number" class="form-control text-center px-2 height-35" value="1">
            </form> of 3
            <a class="text-gray-30 font-size-20 ml-2" href="#">→</a>
        </nav>
    </div> --}}
    <!-- End Shop-control-bar -->
    <!-- Shop Body -->
    <!-- Tab Content -->
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
                                    @if($product->final_price < $product->display_price)
                                        <div class="discount-badge position-absolute top-0 start-0 bg-danger text-white px-2 py-1 z-10">
                                            @if($product->discount_type === 'percentage')
                                                Giảm {{ intval($product->discount_value) }}%
                                            @else
                                                Giảm {{ number_format($product->discount_value, 0) }} VND
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
                                        @if($product->final_price < $product->display_price)
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
</div>

@endsection
