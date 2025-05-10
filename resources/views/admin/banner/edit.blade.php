@extends('admin.layout')

@section('main')
<div class="container">
    <h1 class="mb-4">Edit Banner</h1>
    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $banner->title }}" required>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control-file">
            @if($banner->image)
                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image" width="100" class="mt-2">
            @endif
        </div>
        <div class="form-group">
            <label for="link">Link</label>
            <input type="url" name="link" id="link" class="form-control" value="{{ $banner->link }}">
        </div>
        <div class="form-group">
            <label for="is_active">Active</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="number" name="position" id="position" class="form-control" value="{{ $banner->position }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
