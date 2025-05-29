@extends('client.layout')

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
    @if(session('success'))
        showSuccessAlert('{{ session('success') }}');
    @endif
    @if(session('error'))
        showErrorAlert('{{ session('error') }}');
    @endif
    @if(session('info'))
        showInfoAlert('{{ session('info') }}');
    @endif
    @if ($errors->any())
        showErrorAlert("{{ $errors->first() }}");
    @endif
</script>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4">
                    <h2 class="text-center mb-0" style="color: #2c3e50;">Thông tin cá nhân</h2>
                </div>

                <div class="card-body px-4 py-3">
                    {{-- Xóa alert cũ, chỉ dùng SweetAlert2 --}}

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center mb-3">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/default-avatar.png') }}"
                                     alt="Avatar"
                                     class="rounded-circle shadow"
                                     width="90"
                                     height="90"
                                     style="object-fit: cover;">
                                <label for="image-upload" class="btn btn-sm btn-primary rounded-circle position-absolute" style="bottom: 5px; right: 5px; width: 28px; height: 28px; line-height: 28px; padding: 0;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="image-upload" type="file" name="image" class="d-none">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-sm"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   style="border-radius: 6px;">
                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control form-control-sm bg-light"
                                   value="{{ old('email', $user->email) }}"
                                   readonly
                                   style="border-radius: 6px;">
                        </div>

                        <div class="d-grid mb-3">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    id="toggle-change-password"
                                    style="border-radius: 6px;">
                                <i class="fas fa-key me-2"></i>Đổi mật khẩu
                            </button>
                        </div>

                        <div id="change-password-section" style="display: none;">
                            <hr class="my-3">
                            <h5 class="mb-3" style="color: #2c3e50; font-size: 1rem;">Đổi mật khẩu</h5>
                            <div class="mb-2">
                                <label for="old_password" class="form-label">Mật khẩu cũ</label>
                                <div class="input-group input-group-sm">
                                    <input type="password"
                                           name="old_password"
                                           class="form-control"
                                           autocomplete="new-password"
                                           style="border-radius: 6px;">
                                    <span class="input-group-text bg-white" style="border-radius: 0 6px 6px 0;">
                                        <i class="fas fa-eye-slash toggle-password"></i>
                                    </span>
                                </div>
                                @error('old_password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <div class="input-group input-group-sm">
                                    <input type="password"
                                           name="new_password"
                                           class="form-control"
                                           autocomplete="new-password"
                                           style="border-radius: 6px;">
                                    <span class="input-group-text bg-white" style="border-radius: 0 6px 6px 0;">
                                        <i class="fas fa-eye-slash toggle-password"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                <div class="input-group input-group-sm">
                                    <input type="password"
                                           name="new_password_confirmation"
                                           class="form-control"
                                           autocomplete="new-password"
                                           style="border-radius: 6px;">
                                    <span class="input-group-text bg-white" style="border-radius: 0 6px 6px 0;">
                                        <i class="fas fa-eye-slash toggle-password"></i>
                                    </span>
                                </div>
                                @error('new_password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-primary btn-sm"
                                    style="border-radius: 6px; background-color: #3490dc;">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>

                    {{-- Nút đăng xuất và admin dashboard thành 1 dòng, căn phải dưới --}}
                    <div class="d-flex justify-content-end align-items-center mt-4" style="gap: 8px;">
                        @if(isset($user) && $user->role === 'admin')
                            <a href="{{ route('admin.home') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggle-change-password').addEventListener('click', function() {
        var section = document.getElementById('change-password-section');
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
        this.querySelector('i').classList.toggle('fa-key');
        this.querySelector('i').classList.toggle('fa-times');
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    });

    // Preview image before upload
    document.getElementById('image-upload').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.querySelector('.rounded-circle').src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    });
</script>
@endsection
