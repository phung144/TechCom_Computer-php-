@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-12">
        <div class="card card-default shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Edit Product</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $products->id) }}" method="POST" enctype="multipart/form-data" class="product-form">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $products->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="category">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category" class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $products->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4">{{ old('description', $products->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="price">Price ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('price', $products->price) }}" >
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="quantity">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                   min="0" value="{{ old('quantity', $products->quantity) }}">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="sales">Sales</label>
                            <input type="number" name="sales" id="sales" class="form-control @error('sales') is-invalid @enderror"
                                   min="0" value="{{ old('sales', $products->sales) }}">
                            @error('sales')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <div class="custom-file">
                            <input type="file" name="image" id="image" class="custom-file-input @error('image') is-invalid @enderror">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if($products->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $products->image) }}" alt="Current Product Image" class="img-thumbnail" width="150">
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="remove_image" id="remove_image" class="form-check-input">
                                    <label for="remove_image" class="form-check-label text-danger">Remove current image</label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="photos">Product Images</label>
                        <input type="file" name="photos[]" id="photos" class="form-control-file" multiple>
                        @error('photos')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @if ($errors->has('photos.*'))
                            @foreach ($errors->get('photos.*') as $error)
                                <small class="text-danger">{{ $error[0] }}</small><br>
                            @endforeach
                        @endif
                        @if($products->photos)
                            <div class="mt-2">
                                @foreach($products->photos as $photo)
                                    <div class="d-inline-block position-relative mr-2">
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Product Image" class="img-thumbnail" width="150">
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="remove_photos[]" value="{{ $photo }}" class="form-check-input">
                                            <label class="form-check-label text-danger">Remove</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Discount Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="discount_type">Discount Type</label>
                                    <select name="discount_type" id="discount_type" class="form-control @error('discount_type') is-invalid @enderror">
                                        <option value="">None</option>
                                        <option value="percentage" {{ old('discount_type', $products->discount_type) == 'percentage' ? 'selected' : '' }}>
                                            Percentage
                                        </option>
                                        <option value="fixed" {{ old('discount_type', $products->discount_type) == 'fixed' ? 'selected' : '' }}>
                                            Fixed Amount
                                        </option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="discount_value">Discount Value</label>
                                    <input type="number" name="discount_value" id="discount_value" class="form-control @error('discount_value') is-invalid @enderror"
                                           step="0.01" min="0" value="{{ old('discount_value', $products->discount_value) }}">
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="discount_start">Discount Start Date</label>
                                    <input type="date" name="discount_start" id="discount_start" class="form-control @error('discount_start') is-invalid @enderror"
                                           value="{{ old('discount_start', $products->discount_start ? $products->discount_start->format('Y-m-d') : '') }}">
                                    @error('discount_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="discount_end">Discount End Date</label>
                                    <input type="date" name="discount_end" id="discount_end" class="form-control @error('discount_end') is-invalid @enderror"
                                           value="{{ old('discount_end', $products->discount_end ? $products->discount_end->format('Y-m-d') : '') }}">
                                    @error('discount_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="mdi mdi-content-save mr-1"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary ml-2">
                            <i class="mdi mdi-arrow-left mr-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-form {
        max-width: 1200px;
        margin: 0 auto;
    }

    .card-header.bg-primary {
        padding: 1.25rem 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control, .custom-select {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus, .custom-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }

    .custom-file-label::after {
        content: "Browse";
    }

    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        max-width: 100%;
        height: auto;
    }

    .card.bg-light {
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .text-danger {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Update custom file input label with selected file name
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        let fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
        document.querySelector('.custom-file-label').textContent = fileName;
    });

    // Enable/disable discount value based on discount type selection
    document.getElementById('discount_type').addEventListener('change', function() {
        const discountValue = document.getElementById('discount_value');
        discountValue.disabled = !this.value;
        if (!this.value) discountValue.value = '';
    });

    // Initialize discount value field state
    document.addEventListener('DOMContentLoaded', function() {
        const discountType = document.getElementById('discount_type');
        const discountValue = document.getElementById('discount_value');
        discountValue.disabled = !discountType.value;
    });
</script>
@endpush
