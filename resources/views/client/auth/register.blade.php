@extends('client.auth-layout')

@section('main')
{{-- Thông báo SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showSuccessAlert(message) {
        Swal.fire({
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            position: 'top-end',
            toast: true,
            background: '#f0fff4',
            iconColor: '#38a169',
            color: '#2f855a'
        });
    }
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            position: 'top-end',
            toast: true,
            background: '#fff5f5',
            iconColor: '#e53e3e',
            color: '#c53030'
        });
    }
    function showInfoAlert(message) {
        Swal.fire({
            icon: 'info',
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            position: 'top-end',
            toast: true,
            background: '#ebf8ff',
            iconColor: '#3182ce',
            color: '#2c5282'
        });
    }

    // Hiển thị lỗi validate đầu tiên nếu có
    @if ($errors->any())
        showErrorAlert("{{ $errors->first() }}");
    @endif

    // Hiển thị thông báo thành công nếu có
    @if(session('success'))
        showSuccessAlert('{{ session('success') }}');
    @endif

    @if(session('error'))
        showErrorAlert('{{ session('error') }}');
    @endif

    @if(session('info'))
        showInfoAlert('{{ session('info') }}');
    @endif
</script>
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
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="email" class="font-weight-bold">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required autocomplete="new-email">
                    </div>
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Mật khẩu:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="font-weight-bold">Xác nhận mật khẩu:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required autocomplete="new-password">
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
                <small><a href="{{ route('login') }}" class="text-primary"> Đăng nhập ở đây </a></small>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
    font-size: 0.95rem;
}

.form-control-file {
    display: block;
    width: 100%;
    padding: 0.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control-file:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.text-muted {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #6c757d;
}

/* Tùy chỉnh thêm để làm đẹp nút file input */
input[type="file"] {
    padding: 8px;
    background: #f8f9fa;
    border: 1px dashed #adb5bd;
    border-radius: 4px;
}

input[type="file"]:hover {
    background: #e9ecef;
    border-color: #6c757d;
}
</style>
