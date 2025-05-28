@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Danh sách biến thể</h2>
                    <a href="{{ route('admin.variants.create') }}" class="btn btn-primary">Thêm biến thể</a>
                </div>
                <div class="card-body">
                    <table id="productsTable" class="table table-hover table-product" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($variants as $variant)
                                <tr>
                                    <td>{{ $variant->id }}</td>
                                    <td>{{ $variant->name }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <!-- Nút Detail -->
                                                <a href="{{ route('admin.variants.show', $variant->id) }}"
                                                   class="dropdown-item">
                                                   <i class="mdi mdi-eye-outline mr-2"></i> Chi tiết
                                                </a>

                                                <!-- Nút Edit -->
                                                <a href="{{ route('admin.variants.edit', $variant->id) }}"
                                                   class="dropdown-item">
                                                   <i class="mdi mdi-pencil-outline mr-2"></i> Sửa
                                                </a>

                                                <!-- Phân cách -->
                                                <div class="dropdown-divider"></div>

                                                <!-- Nút Delete -->
                                                <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure to delete this variant?')">
                                                        <i class="mdi mdi-delete-outline mr-2"></i> Xóa
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
