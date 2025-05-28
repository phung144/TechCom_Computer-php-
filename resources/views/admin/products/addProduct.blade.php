@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Thêm sản phẩm</h2>
                </div>
                <div class="card-body">
                    <form id="add-product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Phần thông tin cơ bản -->
                        <div class="form-group">
                            <label for="name">Tên sản phẩm</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">Danh mục</label>
                            <select name="category_id" id="category" class="form-control" required>
                                <option value="">Chọn danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Phần biến thể sản phẩm -->
                        <div class="card mt-4 mb-4">
                            <div class="card-header bg-light">
                                <h4>Cấu hình cơ bản</h4>
                                <small class="text-muted">Thêm cấu hình sản phẩm (CPU, RAM, Ổ cứng...)</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($variants as $variant)
                                        <div class="col-md-3">
                                            <label>{{ $variant->name }}</label>
                                            <select name="variant[{{ strtolower($variant->name) }}]" class="form-control" required>
                                                <option value="">Select {{ $variant->name }}</option>
                                                @foreach($variant->options as $option)
                                                    <option value="{{ $option->id }}">{{ $option->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label>Giá</label>
                                        <input type="number" name="variant[price]" class="form-control" step="0.01" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Số lượng</label>
                                        <input type="number" name="variant[quantity]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <!-- Giá và số lượng mặc định -->
                        <div id="base-price-section" class="{{ old('variants') ? 'd-none' : '' }}">
                            <div class="form-group">
                                <label for="price">Base Price (VND)</label>
                                <input type="number" name="price" id="price" class="form-control"
                                       value="{{ old('price') }}" min="0">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="quantity">Base Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                       value="{{ old('quantity') }}" >
                                @error('quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div> --}}

                        <!-- Phần ảnh sản phẩm -->
                        <div class="form-group">
                            <label for="image">Hình ảnh sản phẩm</label>
                            <input type="file" name="image" id="image" class="form-control-file" required>
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="photos">Hình ảnh nhiều sản phẩm</label>
                            <input type="file" name="photos[]" id="photos" class="form-control-file" multiple required>
                            @error('photos')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @if ($errors->has('photos.*'))
                                @foreach ($errors->get('photos.*') as $error)
                                    <small class="text-danger">{{ $error[0] }}</small><br>
                                @endforeach
                            @endif
                        </div>

                        <!-- Phần giảm giá (Discount) -->
                        <div class="card mt-4 mb-4 border">
                            <div class="card-header bg-light">
                                <h4 class="mb-0">Cài đặt giảm giá</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="discount_type">Loại giảm giá</label>
                                            <select name="discount_type" id="discount_type" class="form-control">
                                                <option value="">Không</option>
                                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Tỷ lệ phần trăm</option>
                                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                                            </select>
                                            @error('discount_type')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="discount_value">Giá trị chiết khấu</label>
                                            <input type="number" name="discount_value" id="discount_value"
                                                   class="form-control" value="{{ old('discount_value') }}" step="0.01" min="0">
                                            @error('discount_value')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sales">Số lượng bán hàng</label>
                                            <input type="number" name="sales" id="sales"
                                                   class="form-control" value="{{ old('sales', 0) }}" min="0">
                                            @error('sales')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="discount_start">Ngày bắt đầu giảm giá</label>
                                            <input type="date" name="discount_start" id="discount_start"
                                                   class="form-control" value="{{ old('discount_start') }}">
                                            @error('discount_start')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="discount_end">Ngày kết thúc giảm giá</label>
                                            <input type="date" name="discount_end" id="discount_end"
                                                   class="form-control" value="{{ old('discount_end') }}">
                                            @error('discount_end')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
