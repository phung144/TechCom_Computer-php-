@extends('client.layout')

@section('main')
<div class="container mt-5 mb-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div style="width: 100%; max-width: 500px;">
        <h2 class="mb-4 text-center">Thông tin cá nhân</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="card p-4">
            @csrf
            <div class="form-group text-center">
                <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle mb-3" width="100" height="100">
                <input type="file" name="image" class="form-control-file">
            </div>
            <div class="form-group">
                <label for="name">Họ tên</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Cập nhật</button>
        </form>
    </div>
</div>
@endsection 