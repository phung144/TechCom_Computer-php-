@extends('admin.layout')

@section('main')

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Thêm người dùng mới</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" class="row g-4">
            @csrf

            <div class="col-md-6">
                <label for="name" class="form-label">Tên</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name') }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>


            <div class="col-md-6">
                <label for="role" class="form-label">Vai trò</label>
                <select class="form-select @error('role') is-invalid @enderror"
                        id="role" name="role">
                    <option value="">-- Chọn vai trò --</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Thêm người dùng
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
