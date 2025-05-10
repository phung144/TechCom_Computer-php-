@extends('admin.layout')

@section('main')
<div class="container">
    <h1 class="mb-4">Banners</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary mb-3">Add New Banner</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Image</th>
                <th>Link</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $banner->title }}</td>
                    <td><img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image" width="100"></td>
                    <td>{{ $banner->link }}</td>
                    <td>{{ $banner->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <form action="{{ route('admin.banners.toggleStatus', $banner->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
