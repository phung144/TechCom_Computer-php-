@extends('admin.layout')

@section('main')
@php use Illuminate\Support\Str; @endphp
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Danh sách bình luận</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm bình luận..." style="max-width: 300px;">
                    </div>
                    <table id="commentsTable" class="table table-hover table-category" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người bình luận</th>
                                <th>Sản phẩm</th>
                                <th>Nội dung</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td>{{ $comment->id }}</td>
                                    <td>{{ $comment->user->name ?? 'Ẩn danh' }}</td>
                                    <td>{{ $comment->product->name ?? 'Không xác định' }}</td>
                                    <td>{{ Str::limit($comment->comment, 120, '...') }}</td>
                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('admin.comments.show', $comment->id) }}" class="dropdown-item">
                                                    <i class="mdi mdi-eye mr-2"></i> Chi tiết
                                                </a>
                                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                        <i class="mdi mdi-delete mr-2"></i> Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#commentsTable tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchValue));
                row.style.display = match ? '' : 'none';
            });
        });
    </script>
@endsection 