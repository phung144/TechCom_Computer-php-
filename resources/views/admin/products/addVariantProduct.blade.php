@extends('admin.layout')

@section('main')
    <div class="card mt-4 mb-4">
        <div class="card-header bg-light">
            <h4>Add Product Variant</h4>
            <small class="text-muted">Thêm cấu hình sản phẩm (CPU, RAM, SSD...)</small>
        </div>

        <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    @foreach($variants as $variant)
                        <div class="col-md-3">
                            <label>{{ $variant->name }}</label>
                            <select name="variant[{{ strtolower($variant->name) }}]" class="form-control" required>
                                <option value="">Chọn {{ $variant->name }}</option>
                                @foreach($variant->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Price</label>
                        <input type="number" name="variant[price]" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-4">
                        <label>Quantity</label>
                        <input type="number" name="variant[quantity]" class="form-control" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Add Variant</button>
                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </form>
    </div>
@endsection
