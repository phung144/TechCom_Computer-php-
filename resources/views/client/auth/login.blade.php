@extends('client.auth-layout')

@section('main')
{{-- Thông báo SweetAlert2 được thiết kế lại --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Hàm hiển thị thông báo thành công với icon checkmark
    function showSuccessAlert(message) {
        Swal.fire({
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            position: 'top-end',
            toast: true,
            background: '#f0fff4',  // Màu nền xanh nhạt
            iconColor: '#38a169',   // Màu xanh lá đậm
            color: '#2f855a'       // Màu chữ xanh đậm
        });
    }

    // Hàm hiển thị thông báo lỗi với icon chấm than
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            position: 'top-end',
            toast: true,
            background: '#fff5f5',  // Màu nền đỏ nhạt
            iconColor: '#e53e3e',    // Màu đỏ
            color: '#c53030'        // Màu chữ đỏ đậm
        });
    }

    // Hàm hiển thị thông báo thông tin
    function showInfoAlert(message) {
        Swal.fire({
            icon: 'info',
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            position: 'top-end',
            toast: true,
            background: '#ebf8ff',  // Màu nền xanh dương nhạt
            iconColor: '#3182ce',   // Màu xanh dương
            color: '#2c5282'       // Màu chữ xanh đậm
        });
    }

    // Xử lý thông báo từ session
    @if(session('success'))
        showSuccessAlert('{{ session('success') }}');
    @endif

    @if(session('error'))
        showErrorAlert('{{ session('error') }}');
    @endif

    @if(session('info'))
        showInfoAlert('{{ session('info') }}');
    @endif

    // Hiển thị lỗi validate đầu tiên nếu có
    @if ($errors->any())
        showErrorAlert("{{ $errors->first() }}");
    @endif
</script>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2>Login</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="font-weight-bold">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Mật khẩu:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <small><a href="{{ route('register') }}" class="text-primary">Đăng ký tại đây</a></small>
            </div>
        </div>
    </div>
</div>
@endsection
