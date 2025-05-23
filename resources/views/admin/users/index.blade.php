@extends('admin.layout')

@section('main')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="card mb-3" id="customersTable">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-md-6 col-xl-5 d-flex align-items-center gap-3 flex-wrap">
                <h5 class="fs-5 mb-0 text-nowrap py-2 py-xl-0">Customers</h5>
                <form action="{{ route('admin.users.index') }}" method="GET" class="w-100 w-md-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Tìm</button>
                    </div>
                </form>
            </div>

            <div class="col-8 col-sm-auto text-end ps-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm Người Dùng
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-6 mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-900">#</th>
                        <th class="text-900" data-sort="name">Tên</th>
                        <th class="text-900" data-sort="email">Email</th>
                        <th class="text-900" data-sort="joined">Ngày tham gia</th>
                        <th class="text-900">Vai trò</th>
                        <th class="text-900">Hành động</th>
                    </tr>
                </thead>
                <tbody class="list" id="table-customers-body">
                    @foreach ($users as $user)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td class="align-middle">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <img class="rounded-circle" src="{{ asset('assets/img/team/2.jpg') }}" alt="avatar" />
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                </div>
                            </a>
                        </td>
                        <td class="align-middle">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </td>
                        <td class="align-middle">
                            {{ optional($user->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="align-middle">
                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }} text-white">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">Cập nhật</a>
                            @if(auth()->id() != $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-center">
        {!! $users->links('pagination::bootstrap-5') !!}
    </div>
</div>

@endsection
