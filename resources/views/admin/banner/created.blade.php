@extends('admin.layout')

@section('main')
<div class="container">
    <h1 class="mb-4">Add New Banner</h1>
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control-file" required>
        </div>
        <div class="form-group">
            <label for="link">Link</label>
            <input type="url" name="link" id="link" class="form-control">
        </div>
        <div class="form-group">
            <label for="is_active">Active</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="number" name="position" id="position" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
