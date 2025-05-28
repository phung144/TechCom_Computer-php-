@extends('client.auth-layout')

@section('main')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2>Đăng ký</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">Tên:</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="font-weight-bold">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Mật khẩu:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="font-weight-bold">Xác nhận mật khẩu:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group">
                        <label for="image" class="font-weight-bold">Hình ảnh:</label>
                        <input type="file" id="image" name="image" class="form-control-file">
                        <small class="text-muted">Tải lên ảnh đại diện của bạn. (tùy chọn)</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>Already have an account? <a href="{{ route('login') }}" class="text-primary"> Đăng nhập ở đây </a></small>
            </div>
        </div>
    </div>
</div>
@endsection
