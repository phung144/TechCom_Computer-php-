@extends('client.layout')

@section('main')
<main id="content" role="main" class="wishlist-page">
    <!-- Breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                        <li class="breadcrumb-item"><a href="{{ route('client-home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách yêu thích</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="container">
        <div class="mb-5 text-center">
            <h1 class="display-5">Danh sách mong muốn của bạn</h1>
            <p class="text-muted">{{ $wishlists->count() }}Sản phẩm yêu thích</p>
        </div>

        @if($wishlists->isEmpty())
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="far fa-heart fa-4x text-muted"></i>
                </div>
                <h4 class="mb-3">Danh sách mong muốn của bạn đang trống</h4>
                <a href="{{ route('client-home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
        @else
            <!-- Wishlist Table -->
            <div class="wishlist-table mb-5">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 80px;">Xoá</th>
                                <th class="text-center" style="width: 100px;">Ảnh</th>
                                <th>Sản Phẩm</th>
                                <th class="text-right">Giá</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wishlists as $wishlist)
                            <tr data-wishlist-id="{{ $wishlist->id }}">
                                <td class="text-center align-middle">
                                    <form onclick="return confirm('Bạn có chắc muốn xóa không ?')" action="{{ route('wishlist.delete', $wishlist->id) }}" method="POST" data-cart-id="{{ $wishlist->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('product.detail', $wishlist->product->id) }}">
                                        <div class="product-thumbnail">
                                            <img src="{{ asset(Storage::url($wishlist->product->image)) }}"
                                                 alt="{{ $wishlist->product->name }}"
                                                 class="img-fluid rounded border">
                                        </div>
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <h6 class="mb-1">
                                        <a href="{{ route('product.detail', $wishlist->product->id) }}" class="text-dark">
                                            {{ $wishlist->product->name }}
                                        </a>
                                    </h6>
                                    @if($wishlist->variant)
                                        <div class="text-muted small variant-options">
                                            @foreach($wishlist->variant->options as $option)
                                                <span class="d-block">{{ $option->variant->name }}: {{ $option->value }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right align-middle">
                                    <div class="product-price">
                                        @if($wishlist->variant)
                                            <span class="text-danger font-weight-bold">
                                                {{ number_format($wishlist->variant->price) }} VND
                                            </span>
                                        @else
                                            <span class="text-danger font-weight-bold">
                                                {{ number_format($wishlist->product->price) }} VND
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('product.detail', $wishlist->product->id) }}" class="btn btn-sm btn-primary">
                                       Xem sản phẩm
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Wishlist Table -->
        @endif
    </div>
</main>

<style>
    .wishlist-page {
        padding-bottom: 3rem;
    }

    .product-thumbnail {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-thumbnail img {
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-thumbnail:hover img {
        transform: scale(1.05);
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .variant-options {
        line-height: 1.3;
    }

    .empty-wishlist {
        padding: 4rem 0;
    }

    @media (max-width: 767.98px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            display: none;
        }
    }
</style>

@endsection
