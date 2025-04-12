@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header">
                <h2>Carts List</h2>
            </div>
            <div class="card-body">
                <table id="productsTable" class="table table-hover table-product" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                        <tr>
                            <td>{{ $cart->id }}</td>
                            <td>{{ $cart->user->name ?? 'Guest' }}</td>
                            <td>
                                @if($cart->product && $cart->product->image)
                                <img src="{{ Storage::url($cart->product->image) }}" width="50" alt="Product Image">
                                @else
                                <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>{{ $cart->product->name ?? 'N/A' }}</td>
                            <td>
                                @if($cart->variant)
                                    {{ $cart->variant->combination_code }}
                                    <small class="text-muted d-block">({{ $cart->variant->price }} VND)</small>
                                @else
                                    <span class="text-muted">No variant</span>
                                @endif
                            </td>
                            <td>{{ number_format($cart->price) }} VND</td>
                            <td>{{ $cart->quantity }}</td>
                            <td>{{ number_format($cart->price * $cart->quantity) }} VND</td>
                            <td>
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="min-width: 180px;">
                                        <!-- Nút View -->
                                        <a href="{{ route('admin.carts.show', $cart->id) }}"
                                           class="dropdown-item py-2">
                                           <i class="mdi mdi-eye-outline mr-2"></i> View Details
                                        </a>

                                        <!-- Nút Edit -->
                                        <a href="{{ route('admin.carts.edit', $cart->id) }}"
                                           class="dropdown-item py-2">
                                           <i class="mdi mdi-pencil-outline mr-2"></i> Edit
                                        </a>

                                        <!-- Phân cách -->
                                        <div class="dropdown-divider my-1"></div>

                                        <!-- Nút Delete - Đã tối ưu -->
                                        <form action="{{ route('admin.carts.destroy', $cart->id) }}" method="POST" class="dropdown-item p-0 m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger w-100 text-left px-3 py-2"
                                                    onclick="return confirm('Delete this item?')">
                                                <i class="mdi mdi-delete-outline mr-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
