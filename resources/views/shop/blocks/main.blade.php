@extends('shop.layout')

@section('main')

<div class="col-xl-9 col-wd-9gdot5">
    <!-- Shop-control-bar Title -->
    {{-- <div class="flex-center-between mb-3">
        <h3 class="font-size-25 mb-0">Shop</h3>
        <p class="font-size-14 text-gray-90 mb-0">Showing 1–25 of 56 results</p>
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
                <i class="fas fa-sliders-h"></i> <span class="ml-1">Filters</span>
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
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab" data-target-group="groups">
            <ul class="row list-unstyled products-group no-gutters">
                @foreach($products as $product)
                <li class="col-6 col-md-3 product-item position-relative"> <!-- Added position-relative -->
                    <div class="product-item__outer h-100 w-100">
                        <div class="product-item__inner px-xl-3 p-2">
                            <div class="product-item__body pb-xl-2">
                                <!-- Discount Badge - Moved below category -->
                                @if($product->discount_value > 0)
                                    <div class="discount-badge position-absolute mt-3" style="top: 25px; left: 10px; background-color: #ff4444; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px; z-index: 1;">
                                        @if($product->discount_type === 'percentage')
                                            Giảm {{ intval($product->discount_value) }}%
                                        @else
                                            Giảm {{ number_format($product->discount_value, 0) }}đ
                                        @endif
                                    </div>
                                @endif

                                @if($product->category)
                                <div class="mb-1">
                                    <a href="" class="font-size-11 text-gray-5">
                                        {{ $product->category->name }}
                                    </a>
                                </div>
                                @endif

                                <div class="mb-1 product-image-container position-relative"> <!-- Added container for image -->
                                    <a href="" class="d-block text-center">
                                        <img class="img-fluid" src="{{Storage::url($product->image)}}" alt="{{ $product->name }}">
                                    </a>
                                </div>

                                <h5 class="mb-1 product-item__title">
                                    <a href="" class="text-blue font-weight-bold font-size-14">
                                        {{ Str::limit($product->name, 50) }} <!-- Added limit to product name -->
                                    </a>
                                </h5>

                                <div class="flex-center-between mb-1">
                                    <div class="prodcut-price">
                                        @if($product->discount_value > 0)
                                            <div class="text-gray-100 font-size-13"><del>{{ number_format($product->price, 0) }}đ</del></div>
                                            <div class="text-danger font-size-14">{{ number_format($product->final_price, 0) }}đ</div>
                                        @else
                                            <div class="text-gray-100 font-size-14">{{ number_format($product->price, 0) }}đ</div>
                                        @endif
                                    </div>
                                    <div class="d-none d-xl-block prodcut-add-cart">
                                        <a href="" class="btn-add-cart btn-primary transition-3d-hover">
                                            <i class="ec ec-add-to-cart"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="product-item__footer">
                                <div class="border-top pt-1 flex-center-between flex-wrap">
                                    <a href="compare.html" class="text-gray-6 font-size-12">
                                        <i class="ec ec-compare mr-1 font-size-13"></i> Compare
                                    </a>
                                    <a href="wishlist.html" class="text-gray-6 font-size-12">
                                        <i class="ec ec-favorites mr-1 font-size-13"></i> Wishlist
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    {{ $products->onEachSide(1)->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    </div>

    <style>
        /* Add this to your CSS file */
        .product-item {
            position: relative;
            margin-bottom: 15px;
        }

        .discount-badge {
            position: absolute;
            top: 25px;
            left: 10px;
            background-color: #ff4444;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            z-index: 1;
        }

        .product-image-container {
            overflow: hidden;
        }

        .product-image-container img {
            transition: transform 0.3s ease;
        }

        .product-item:hover .product-image-container img {
            transform: scale(1.05);
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-link {
            color: #007bff;
            margin: 0 3px;
            border-radius: 4px !important;
        }
    </style>
</div>

@endsection
