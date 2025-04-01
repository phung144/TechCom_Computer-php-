@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Edit Product</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $products->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $products->name) }}" >
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $products->description) }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category_id" id="category" class="form-control" >
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $products->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01"
                                value="{{ old('price', $products->price) }}" >
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                value="{{ old('quantity', $products->quantity) }}" >
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" name="image" id="image" class="form-control-file">
                            <img src="{{ asset('storage/' . $products->image) }}" alt="Product Image" width="100"
                                class="mt-2">
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_type">Discount Type</label>
                            <select name="discount_type" id="discount_type" class="form-control">
                                <option value="">None</option>
                                <option value="percentage" {{ old('discount_type', $products->discount_type) == 'percentage' ? 'selected' : '' }}>
                                    Percentage</option>
                                <option value="fixed" {{ old('discount_type', $products->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed
                                    Amount</option>
                            </select>
                            @error('discount_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_value">Discount Value</label>
                            <input type="number" name="discount_value" id="discount_value" class="form-control"
                                step="0.01" value="{{ old('discount_value', $products->discount_value) }}">
                            @error('discount_value')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_start">Discount Start Date</label>
                            <input type="date" name="discount_start" id="discount_start" class="form-control"
                                value="{{ old('discount_start', $products->discount_start ? $products->discount_start->format('Y-m-d') : '') }}">
                            @error('discount_start')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_end">Discount End Date</label>
                            <input type="date" name="discount_end" id="discount_end" class="form-control"
                                value="{{ old('discount_end', $products->discount_end ? $products->discount_end->format('Y-m-d') : '') }}">
                            @error('discount_end')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sales">Sales</label>
                            <input type="number" name="sales" id="sales" class="form-control"
                                value="{{ old('sales', $products->sales) }}" min="0">
                            @error('sales')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
