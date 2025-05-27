@extends('client.layout')

@section('main')
<div class="container mt-5 mb-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div style="width: 100%; max-width: 500px;">
        <h2 class="mb-4 text-center">Thông tin cá nhân</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(isset($user) && $user->role === 'admin')
            <div class="mb-3 text-center">
                <a href="{{ route('admin.home') }}" class="btn btn-warning">Admin Dashboard</a>
            </div>
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
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
            </div>
            <button type="button" class="btn btn-secondary mb-3" id="toggle-change-password">Đổi mật khẩu</button>
            <div id="change-password-section" style="display: none;">
                <hr>
                <h5>Đổi mật khẩu</h5>
                <div class="form-group">
                    <label for="old_password">Mật khẩu cũ</label>
                    <input type="password" name="old_password" class="form-control" autocomplete="new-password">
                    @error('old_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" name="new_password" class="form-control" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" class="form-control" autocomplete="new-password">
                    @error('new_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Cập nhật</button>
        </form>
        <script>
            document.getElementById('toggle-change-password').addEventListener('click', function() {
                var section = document.getElementById('change-password-section');
                section.style.display = section.style.display === 'none' ? 'block' : 'none';
            });
        </script>
    </div>
</div>
@endsection
