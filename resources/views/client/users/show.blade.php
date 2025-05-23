@extends('client.layout')

@section('main')
<div class="container mt-4 mb-4">

    {{-- Nút quay lại --}}
    <a href="{{ route('client-home') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    {{-- THÔNG BÁO TOÀN TRANG --}}
    <div class="container mt-3">

        {{-- Thông báo thành công --}}
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-3 p-3 rounded shadow-sm border-start border-5 border-success"
            role="alert">
            <i class="fas fa-check-circle fa-lg"></i>
            <div class="flex-grow-1">
                {{ session('success') }}
            </div>
        </div>
        @endif

        {{-- Thông báo lỗi --}}
        @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-3 p-3 rounded shadow-sm border-start border-5 border-danger"
            role="alert">
            <i class="fas fa-exclamation-circle fa-lg mt-1"></i>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-2">Có lỗi xảy ra:</h6>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

    </div>



    {{-- Thông tin người dùng --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $user->name }} (<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>)
            </h5>
        </div>
        <div class="card-body border-top d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-calendar-alt text-success me-2"></i>
                <span class="text-muted">Ngày tạo:</span>
                <strong>{{ optional($user->created_at)->format('d/m/Y H:i:s') }}</strong>
            </div>
        </div>
    </div>

    {{-- Form chỉnh sửa --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Chỉnh sửa tài khoản</h5>
        </div>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="card-body">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Thông tin tài khoản --}}
                <div class="col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3">Thông tin tài khoản</h6>
                    <div class="mb-3">
                        <label class="form-label">ID người dùng:</label>
                        <div class="form-control-plaintext">{{ $user->id }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên người dùng</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu (nếu muốn thay đổi)</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Thông tin thanh toán --}}
                <div class="col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3">Thông tin thanh toán</h6>
                    <div class="mb-3">
                        <label class="form-label">Email thanh toán:</label>
                        <div class="form-control-plaintext">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </div>
                    </div>
                    {{-- Bạn có thể bổ sung thêm các thông tin thanh toán khác tại đây nếu có --}}
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
