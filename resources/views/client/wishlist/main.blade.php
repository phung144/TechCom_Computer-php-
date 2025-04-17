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
                        <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="container">
        <div class="mb-5 text-center">
            <h1 class="display-5">Your Wishlist</h1>
            <p class="text-muted">Products you love</p>
        </div>

        <!-- Wishlist Table -->
        <div class="wishlist-table mb-5">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">Remove</th>
                            <th class="text-center" style="width: 100px;">Image</th>
                            <th>Product</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wishlists as $wishlist)
                        <tr data-wishlist-id="{{ $wishlist->id }}">
                            <td class="text-center align-middle">
                                <form class="delete-wishlist-form" action="{{ route('wishlist.delete', $wishlist->id) }}" method="POST" data-wishlist-id="{{ $wishlist->id }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center align-middle">
                                <div class="product-thumbnail">
                                    <img src="{{ asset(Storage::url($wishlist->product->image)) }}"
                                         alt="{{ $wishlist->product->name }}"
                                         class="img-fluid rounded border">
                                </div>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{ $wishlist->product->name }}</h6>
                            </td>
                           
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Wishlist Table -->
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

    @media (max-width: 767.98px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-wishlist-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const wishlistId = this.dataset.wishlistId;
                if (confirm('Are you sure you want to remove this product from your wishlist?')) {
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ wishlist_id: wishlistId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`tr[data-wishlist-id="${wishlistId}"]`).remove();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
</script>
@endsection
