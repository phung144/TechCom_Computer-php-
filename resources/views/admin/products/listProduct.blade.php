@extends('admin.layout')

@section('main')
@php use Illuminate\Support\Str; @endphp
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Products List</h2>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
                </div>
                <div class="card-body">
                    <table id="productsTable" class="table table-hover table-product" style="width:100%">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Sales</th>
                                <th>Discount</th>
                                <th>Discount Start</th>
                                <th>Discount End</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="py-0">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                                            width="50">
                                    </td>
                                    <td>{{ Str::limit($product->name, 50, '...')}}</td>
                                    <td>{{ Str::limit($product->description, 50, '...') ?? 'No description available' }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($product->price) }} VND</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ $product->sales }}</td>
                                    <td>
                                        @if ($product->discount_type && $product->discount_value)
                                            {{ $product->discount_type === 'percentage' ? $product->discount_value . '%' : '$' . number_format($product->discount_value) }}
                                        @else
                                            No Discount
                                        @endif
                                    </td>
                                    <td>{{ $product->discount_start ? $product->discount_start->format('Y-m-d') : 'N/A' }}
                                    </td>
                                    <td>{{ $product->discount_end ? $product->discount_end->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                   class="dropdown-item">
                                                   <i class="mdi mdi-eye-outline mr-2"></i> Detail
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                   class="dropdown-item">
                                                   <i class="mdi mdi-pencil-outline mr-2"></i> Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this product?')">
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
