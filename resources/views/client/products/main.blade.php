@extends('client.layout')

@section('main')

{{-- Product Detail --}}
<div>
    <div class="mb-xl-14 mb-6">
        <div class="row">
            <div class="col-md-5 mb-4 mb-md-0">
                <div>
                    <img class="img-fluid" src="{{ Storage::url($product->image) }}" width="90%" alt="Image Description">
                </div>
            </div>
            <div class="col-md-7 mb-md-6 mb-lg-0 mt-6">
                <div class="mb-2">
                    <div class="border-bottom mb-3 pb-md-1 pb-3">
                        <a href="#" class="font-size-12 text-gray-5 mb-2 d-inline-block">Laptops {{ $product->category->name }}</a>
                        <h2 class="font-size-25 text-lh-1dot2">{{ $product->name }}</h2>
                        <div class="mb-2">
                            <a class="d-inline-flex align-items-center small font-size-15 text-lh-1" href="#">
                                <div class="text-warning mr-2">
                                    <small class="fas fa-star"></small>
                                    <small class="fas fa-star"></small>
                                    <small class="fas fa-star"></small>
                                    <small class="fas fa-star"></small>
                                    <small class="far fa-star text-muted"></small>
                                </div>
                                <span class="text-secondary font-size-13">(3 customer reviews)</span>
                            </a>
                        </div>
                        <div class="d-md-flex align-items-center">
                            <div class="ml-md-3 text-gray-9 font-size-14">Availability: <span class="text-green font-weigh-bold">{{ $product->quantity }} in stock</span></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <ul class="font-size-14 pl-3 ml-1 text-gray-110">
                            {{ $product->description }}
                        </ul>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex align-items-baseline">
                            <ins class="font-size-36 text-decoration-none text-red mr-3">
                                {{ number_format($discountedPrice, 3) }} VND
                            </ins>
                            @if($discountedPrice < $originalPrice)
                                <del class="font-size-16 text-gray-6">{{ number_format($originalPrice, 3) }} VND</del>
                            @endif
                        </div>
                    </div>
                    <div class="d-md-flex align-items-end mb-3">
                        <div class="max-width-150 mb-4 mb-md-0">
                            <h6 class="font-size-14">Quantity</h6>
                            <!-- Quantity -->
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
                            <!-- End Quantity -->
                        </div>
                    </div>
                    <div class="d-flex mt-3">
                        <a href="#" class="btn px-5 btn-primary-dark transition-3d-hover mr-2">
                            <i class="ec ec-add-to-cart mr-2 font-size-20"></i> Add to Cart
                        </a>
                        <a href="#" class="btn px-5 btn-success transition-3d-hover">
                            <i class="ec ec-credit-card mr-2 font-size-20"></i> Mua ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Product Detail --}}


<!-- Related products -->
<div class="mb-6">
    <div class="d-flex justify-content-between align-items-center border-bottom border-color-1 flex-lg-nowrap flex-wrap mb-4">
        <h3 class="section-title mb-0 pb-2 font-size-22">Related products</h3>
    </div>
    <ul class="row list-unstyled products-group no-gutters">
        @foreach($relatedProducts as $relatedProduct)
        <li class="col-6 col-md-3 col-xl-2gdot4-only col-wd-2 product-item">
            <div class="product-item__outer h-100">
                <div class="product-item__inner px-xl-4 p-3">
                    <div class="product-item__body pb-xl-2 position-relative">
                        <div class="mb-2">
                            <a href="#" class="font-size-12 text-gray-5">{{ $relatedProduct->category->name }}</a>
                        </div>
                        <div class="mb-2 position-relative">
                            @if($relatedProduct->discount_value > 0)
                            <span class="badge badge-danger position-absolute top-0 left-0 font-size-12 p-2">
                                Giảm {{ floor($relatedProduct->discount_value) }}%
                            </span>
                            @endif
                            <a href="{{ route('product.detail', $relatedProduct->id) }}" class="d-block text-center">
                                <img class="img-fluid" src="{{ Storage::url($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
                            </a>
                        </div>
                        <h5 class="mb-1 product-item__title">
                            <a href="{{ route('product.detail', $relatedProduct->id) }}" class="text-blue font-weight-bold">{{ $relatedProduct->name }}</a>
                        </h5>

                        <div class="flex-center-between mb-1">
                            <div class="prodcut-price d-flex flex-column align-items-start position-relative">
                                @php
                                    $discountedPrice = $relatedProduct->price - ($relatedProduct->price * $relatedProduct->discount / 100);
                                @endphp
                                <del class="font-size-12 text-gray-6">{{ number_format($relatedProduct->price, 3) }} VND</del>
                                <ins class="font-size-16 text-red text-decoration-none mt-1">{{ number_format($discountedPrice, 3) }} VND</ins>
                            </div>
                            <div class="d-none d-xl-block prodcut-add-cart">
                                <a href="#" class="btn-add-cart btn-warning transition-3d-hover">
                                    <i class="ec ec-add-to-cart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
<!-- End Related products -->
@endsection
