@extends('admin.layout')

@section('main')
<div class="container mt-4">

    <!-- Nút quay lại -->
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Back
    </a>

    <!-- Thông báo thành công -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Thông tin người dùng -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $user->name }} (<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>)
            </h5>
            <div>
                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }} text-white">
                    {{ ucfirst($user->role) }}
                </span>
            </div>


        </div>
        <div class="card-body border-top d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-hourglass-half text-success me-2"></i>
                <span class="text-muted">Created at:</span>
                <strong>{{ optional($user->created_at)->format('d/m/Y H:i:s') }}</strong>
            </div>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Form chỉnh sửa -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Chỉnh sửa tài khoản</h5>
        </div>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="card-body">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Thông tin tài khoản -->
                <div class="col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3">Thông tin tài khoản</h6>
                    <div class="mb-2">
                        <strong>ID:</strong> {{ $user->id }}
                    </div>
                    <div class="mb-2">
                        <label for="name" class="form-label">Tên người dùng</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $user->name) }}" disabled>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" disabled>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">Mật khẩu (nếu muốn thay đổi)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label for="role" class="form-label">Vai trò</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                            </option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Thông tin thanh toán -->
                <div class="col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3">Thông tin thanh toán</h6>
                    <div class="mb-2">
                        <strong>Email:</strong>
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection