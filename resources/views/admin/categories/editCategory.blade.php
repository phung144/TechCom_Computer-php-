@extends('admin.layout')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Sửa danh mục</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $category->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Miêu tả</label>
                            <textarea name="description" id="description" class="form-control">{{ $category->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            <input type="file" name="image" id="image" class="form-control">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="" width="100px">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
