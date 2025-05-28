@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Chi tiết sản phẩm</h2>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Quay lại</a>
                </div>
                <div class="card-body">
                    <!-- Thông tin sản phẩm -->
                    <h4>Thông tin sản phẩm</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>Hình ảnh</th>
                            <td><img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100"></td>
                        </tr>
                        <tr>
                            <th>Tên</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td>{{ $product->description ?? 'No description available' }}</td>
                        </tr>
                        <tr>
                            <th>Danh mục</th>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Price</th>
                            <td>{{ number_format($product->price) }} VND</td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td>{{ $product->quantity }}</td>
                        </tr> --}}
                    </table>

                    <!-- Biến thể sản phẩm -->

                    <div class="card-header">
                        <h2>Biến thể sản phẩm</h2>
                        <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary">Thêm tùy chọn biến thể</a>
                    </div>

                    <div class="card card-default mt-3">
                        <div class="card-body">
                            @if ($product->variants->isNotEmpty())
                                <table class="table table-hover table-product" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Cấu hình</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->variants as $variant)
                                            <tr id="variant-row-{{ $variant->id }}">
                                                <td>{{ $variant->formatted_combination_code }}</td>
                                                <td class="price-cell">{{ number_format($variant->price) }} VND</td>
                                                <td class="quantity-cell">{{ $variant->quantity }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-variant"
                                                        data-id="{{ $variant->id }}">Sửa</button>
                                                    <button class="btn btn-sm btn-danger delete-variant"
                                                        data-id="{{ $variant->id }}">Xóa</button>
                                                </td>
                                            </tr>
                                            <!-- Edit Form (hidden by default) -->
                                            <tr id="edit-form-{{ $variant->id }}" style="display: none;">
                                                <td colspan="4">
                                                    <form class="variant-edit-form" data-id="{{ $variant->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Giá (VND)</label>
                                                                    <input type="number" name="price"
                                                                        class="form-control" value="{{ $variant->price }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Số lượng</label>
                                                                    <input type="number" name="quantity"
                                                                        class="form-control"
                                                                        value="{{ $variant->quantity }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 d-flex align-items-end">
                                                                <div class="form-group">
                                                                    <button type="submit"
                                                                        class="btn btn-success">Lưu</button>
                                                                    <button type="button"
                                                                        class="btn btn-secondary cancel-edit"
                                                                        data-id="{{ $variant->id }}">Hủy</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Không có biến thể nào có sẵn cho sản phẩm này.</p>
                            @endif
                        </div>
                    </div>

                    <!-- JavaScript for handling edit/delete functionality -->
                    <script>
                        $(document).ready(function() {
                            // Show edit form when edit button is clicked
                            $('.edit-variant').click(function() {
                                const variantId = $(this).data('id');
                                $('#variant-row-' + variantId).hide();
                                $('#edit-form-' + variantId).show();
                            });

                            // Hide edit form when cancel button is clicked
                            $('.cancel-edit').click(function() {
                                const variantId = $(this).data('id');
                                $('#edit-form-' + variantId).hide();
                                $('#variant-row-' + variantId).show();
                            });

                            // Handle form submission
                            $('.variant-edit-form').submit(function(e) {
                                e.preventDefault();
                                const variantId = $(this).data('id');
                                let formData = $(this).serializeArray();
                                formData.push({ name: '_method', value: 'PUT' }); // Thêm dòng này

                                $.ajax({
                                    url: '/variants/' + variantId,
                                    type: 'POST', // Giữ nguyên POST, Laravel sẽ hiểu là PUT nhờ _method
                                    data: $.param(formData),
                                    success: function(response) {
                                        // Update the displayed values
                                        $('#variant-row-' + variantId + ' .price-cell').text(response
                                            .formatted_price + ' VND');
                                        $('#variant-row-' + variantId + ' .quantity-cell').text(response
                                            .quantity);

                                        // Hide the form and show the row
                                        $('#edit-form-' + variantId).hide();
                                        $('#variant-row-' + variantId).show();

                                        // Show success message
                                        toastr.success('Variant updated successfully');
                                    },
                                    error: function(xhr) {
                                        toastr.error('Error updating variant: ' + xhr.responseJSON.message);
                                    }
                                });
                            });

                            // Handle delete button click
                            $('.delete-variant').click(function() {
                                const variantId = $(this).data('id');

                                if (confirm('Are you sure you want to delete this variant?')) {
                                    $.ajax({
                                        url: '/variants/' + variantId,
                                        type: 'DELETE',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            $('#variant-row-' + variantId).remove();
                                            $('#edit-form-' + variantId).remove();
                                            toastr.success('Variant deleted successfully');
                                        },
                                        error: function(xhr) {
                                            toastr.error('Error deleting variant: ' + xhr.responseJSON.message);
                                        }
                                    });
                                }
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection
