@extends('client.layout')

@section('main')
<main id="content" role="main" class="cart-page">
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
    <!-- Breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visible">
                        <li class="breadcrumb-item"><a href="{{ route('client-home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="container">
        <div class="mb-5 text-center">
            <h1 class="display-5">Your Shopping Cart</h1>
            <p class="text-muted">Review your items before checkout</p>
        </div>

        <!-- Cart Table -->
        <div class="cart-table mb-5">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">Remove</th>
                            <th class="text-center" style="width: 100px;">Image</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th class="text-center" style="width: 120px;">Price</th>
                            <th class="text-center" style="width: 150px;">Quantity</th>
                            <th class="text-center" style="width: 120px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carts as $cart)
                        <tr data-cart-id="{{ $cart->id }}">
                            <td class="text-center align-middle">
                                <form class="delete-cart-form" action="{{ route('cart.delete', $cart->id) }}" method="POST" data-cart-id="{{ $cart->id }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center align-middle">
                                <div class="product-thumbnail">
                                    <img src="{{ asset(Storage::url($cart->product->image)) }}"
                                         alt="{{ $cart->product->name }}"
                                         class="img-fluid rounded border">
                                </div>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{ $cart->product->name }}</h6>
                            </td>
                            <td class="align-middle">
                                @if($cart->variant)
                                    <div class="variant-options">
                                        @foreach($cart->variant->options as $option)
                                            <span class="variant-value">({{ $option->value }})</span>
                                            @if(!$loop->last) - @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No variant</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <span class="fw-bold">{{ number_format($cart->price, 0) }} VND</span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center">
                                    <div class="quantity-control" style="max-width: 120px;">
                                        <input type="text" class="form-control text-center js-quantity-input"
                                               value="{{ $cart->quantity }}"
                                               data-cart-id="{{ $cart->id }}"
                                               readonly>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle fw-bold item-total" data-item-total="{{ $cart->price * $cart->quantity }}">
                                {{ number_format($cart->price * $cart->quantity, 0) }} VND
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <th colspan="6" class="text-end">Total:</th>
                            @php
                                $total = $carts->sum(function($cart) {
                                    return $cart->price * $cart->quantity;
                                });
                            @endphp
                            <td class="text-center">
                                <strong id="cart-total" class="text-primary">{{ number_format($total, 0) }} VND</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- End Cart Table -->

        <!-- Billing Details -->
        <div class="billing-details bg-light p-4 rounded mb-5">
            <div class="mb-4">
                <h3 class="h4 border-bottom pb-2">Billing Details</h3>
            </div>
            <form id="order-form" action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Jack Wayley" required>
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="470 Lucy Forks, London" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="jackwayley@gmail.com" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="+1 (062) 109-9222">
                    </div>
                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-shopping-bag me-2"></i> Place Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Billing Details -->
    </div>
</main>

<style>
    /* Custom CSS for better visual */
    .cart-page {
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

    .quantity-control {
        max-width: 120px;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .table tfoot th {
        font-size: 1rem;
    }

    .billing-details {
        background-color: #f8f9fa;
        border: 1px solid #eee;
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
        document.querySelectorAll('.delete-cart-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const cartId = this.dataset.cartId;
                if (confirm('Are you sure you want to remove this product?')) {
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ cart_id: cartId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`tr[data-cart-id="${cartId}"]`).remove();
                            updateCartTotal();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        function updateCartTotal() {
            let total = 0;
            document.querySelectorAll('.item-total').forEach(el => {
                total += parseFloat(el.dataset.itemTotal);
            });
            document.getElementById('cart-total').textContent = total.toLocaleString() + ' VND';
        }
    });
</script>
@endsection
