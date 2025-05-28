@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Chi tiết biến thể</h2>
                    <a href="{{ route('admin.variants.index') }}" class="btn btn-primary">Quay lại</a>
                </div>
                <div class="card-body">
                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Thông tin cơ bản</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ID</th>
                                    <td>{{ $variant->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên</th>
                                    <td>{{ $variant->name }}</td>
                                </tr>
                                <tr>
                                    <th>Thời gian tạo</th>
                                    <td>{{ $variant->created_at ? $variant->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Thời gian cập nhật</th>
                                    <td>{{ $variant->updated_at ? $variant->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Các nút hành động -->
                    <div class="mt-4">
                        <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-primary">Sửa biến thể</a>
                        <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this variant?')">Xóa biến thể</button>
                        </form>
                    </div>

                    <!-- Gộp Variant Values và Variant Options vào 1 table -->
                    <div class="card mt-5">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Tùy chọn và giá trị biến thể</h4>
                            <a href="{{route('admin.variant-options.create')}}" class="btn btn-success btn-sm">Thêm tùy chọn</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID biến thể</th>
                                            <th>Giá trị</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- Hiển thị Variant Options -->
                                            @foreach($variantOptions as $index => $option)
                                                <tr>
                                                    <td>{{ $option->variant_id }}</td>
                                                    <td>{{ $option->value }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-xs btn-primary">Sửa</a>
                                                        <form action="{{route('admin.variant-options.destroy', $option->id)}}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">Xóa</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach


                                        <!-- Hiển thị khi không có dữ liệu -->
                                        @if(empty($variant->values) && empty($variantOptions))
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Không có dữ liệu có sẵn</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
