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

<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h2 class="mb-0">Danh sách người dùng</h2>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm Người Dùng
                </a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm người dùng"
                        style="max-width: 300px;">
                </div>
                <div class="table-responsive scrollbar">
                    <table class="table table-hover table-striped table-user" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Ngày tham gia</th>
                                <th>Vai trò</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="align-middle">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <img class="rounded-circle" src="{{ Storage::url($user->image) }}" alt="avatar" style="width: 40px; height: 40px; object-fit: cover;" />
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
                                    <div class="dropdown">
                                        <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                               class="dropdown-item">
                                               <i class="mdi mdi-pencil mr-2"></i> Cập nhật
                                            </a>
                                        </div>
                                    </div>
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
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table-user tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchValue));
            row.style.display = match ? '' : 'none';
        });
    });
</script>
@endsection
