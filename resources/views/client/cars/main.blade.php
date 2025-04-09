@extends('client.layout')

@section('main')
<main id="content" role="main" class="cart-page">
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
        <div class="mb-4 text-center">
            <h1>Shopping Cart</h1>
        </div>

        <!-- Cart Table -->
        <div class="cart-table mb-10">
            <table class="table table-bordered" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">Remove</th>
                        <th class="text-center">Image</th>
                        <th>Product</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carts as $cart)
                        <tr data-cart-id="{{ $cart->id }}">
                            <td class="text-center">
                                <form class="delete-cart-form" action="{{ route('cart.delete', $cart->id) }}" method="POST" data-cart-id="{{ $cart->id }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                            <td class="text-center">
                                <img class="img-fluid max-width-100 p-1 border"
                                     src="{{ asset(Storage::url($cart->product->image)) }}"
                                     alt="{{ $cart->product->name }}"
                                     style="width: 50px;">
                            </td>
                            <td>{{ $cart->product->name }}</td>
                            <td class="text-center">{{ number_format($cart->price, 0) }} VND</td>
                            <td class="text-center">
                                <div class="input-group justify-content-center align-items-center">
                                    <button class="btn btn-sm btn-outline-secondary js-quantity-update" data-cart-id="{{ $cart->id }}" data-action="decrease">-</button>
                                    <input type="text" class="form-control text-center mx-2 js-quantity-input" value="{{ $cart->quantity }}" data-cart-id="{{ $cart->id }}" style="width: 50px;" readonly>
                                    <button class="btn btn-sm btn-outline-secondary js-quantity-update" data-cart-id="{{ $cart->id }}" data-action="increase">+</button>
                                </div>
                            </td>
                            <td class="text-center item-total" data-item-total="{{ $cart->price * $cart->quantity }}">
                                {{ number_format($cart->price * $cart->quantity, 0) }} VND
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total:</th>
                        @php
                            $total = $carts->sum(function($cart) {
                                return $cart->price * $cart->quantity;
                            });
                        @endphp
                        <td class="text-center">
                            <strong id="cart-total">{{ number_format($total, 0) }} VND</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- End Cart Table -->

        <!-- Billing Details -->
        <div class="billing-details">
            <div class="border-bottom mb-4">
                <h3 class="section-title font-size-25">Billing Details</h3>
            </div>
            <form id="order-form" action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="full_name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Jack Wayley" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="address">Address <span class="text-danger">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="470 Lucy Forks, London" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="jackwayley@gmail.com" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="+1 (062) 109-9222">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Đặt hàng</button>
                </div>
            </form>
        </div>
        <!-- End Billing Details -->
    </div>
</main>

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

        document.querySelectorAll('.js-quantity-update').forEach(button => {
            button.addEventListener('click', function () {
                const cartId = this.dataset.cartId;
                const action = this.dataset.action;
                const input = document.querySelector(`.js-quantity-input[data-cart-id="${cartId}"]`);
                let quantity = parseInt(input.value);

                if (action === 'decrease' && quantity > 1) {
                    quantity--;
                } else if (action === 'increase') {
                    quantity++;
                }

                updateCartQuantity(cartId, quantity);
            });
        });

        function updateCartQuantity(cartId, quantity) {
            fetch('{{ route('cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ cart_id: cartId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                    const totalCell = row.querySelector('td.item-total');
                    const quantityInput = row.querySelector(`.js-quantity-input[data-cart-id="${cartId}"]`);
                    totalCell.textContent = new Intl.NumberFormat('vi-VN').format(data.item_total) + ' VND';
                    totalCell.setAttribute('data-item-total', data.item_total);
                    quantityInput.value = data.quantity;
                    updateCartTotal();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function updateCartTotal() {
            let total = 0;
            document.querySelectorAll('td.item-total').forEach(cell => {
                const itemTotal = parseInt(cell.dataset.itemTotal) || 0;
                total += itemTotal;
            });
            document.getElementById('cart-total').textContent = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
        }
    });
</script>
@endsection
