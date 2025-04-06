@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header">
                <h2>Carts List</h2>
                {{-- <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a> --}}
            </div>
            <div class="card-body">
                <table id="productsTable" class="table table-hover table-product" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User name</th>
                            <th>Image Product</th>
                            <th>Name Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($products as $product)
                            <tr>
                                <td class="py-0">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                                        width="50">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description ?? 'No description available' }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->sales }}</td>
                                <td>
                                    @if ($product->discount_type && $product->discount_value)
                                        {{ $product->discount_type === 'percentage' ? $product->discount_value . '%' : '$' . number_format($product->discount_value, 2) }}
                                    @else
                                        No Discount
                                    @endif
                                </td>
                                <td>{{ $product->discount_start ? $product->discount_start->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td>{{ $product->discount_end ? $product->discount_end->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
