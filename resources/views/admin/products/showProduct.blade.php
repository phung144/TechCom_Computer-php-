@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Product Details</h2>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back to List</a>
                </div>
                <div class="card-body">
                    <!-- Thông tin sản phẩm -->
                    <h4>Product Information</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>Image</th>
                            <td><img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100"></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $product->description ?? 'No description available' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>${{ number_format($product->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td>{{ $product->quantity }}</td>
                        </tr>
                    </table>

                    <!-- Biến thể sản phẩm -->
                    <h4>Product Variants</h4>
                        @if ($product->variants->isNotEmpty())
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Combination Code</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $variant)
                                        <tr>
                                            <td>{{ $variant->formatted_combination_code }}</td>
                                            <td>{{ number_format($variant->price) }} VND</td>
                                            <td>{{ $variant->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No variants available for this product.</p>
                        @endif
                </div>
            </div>
        </div>
    </div>
@endsection
