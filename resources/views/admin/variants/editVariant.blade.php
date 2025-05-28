@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Sửa biến thể</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Tên biến thể</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $variant->name }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
