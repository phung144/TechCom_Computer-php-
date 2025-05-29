@extends('admin.layout')

@section('main')
<div class="container mt-4">

    <!-- Nút quay lại -->
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>

    <!-- Thông báo thành công -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Thông báo lỗi -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Thông tin người dùng + Form cập nhật vai trò -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h6 class="text-uppercase fw-bold mb-3">Thông tin tài khoản</h6>
                    <div class="mb-2">
                        <strong>ID:</strong> {{ $user->id }}
                    </div>
                    <div class="mb-2">
                        <strong>Tên người dùng:</strong> {{ $user->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </div>
                    <hr>
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="row align-items-end">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-semibold">
                                <i class="fas fa-user-tag me-1"></i>Vai trò
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                </option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3 text-end">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-1"></i> Cập nhật vai trò
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
