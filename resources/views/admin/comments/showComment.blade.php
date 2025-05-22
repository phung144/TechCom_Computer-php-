@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header">
                <h2>Chi tiết bình luận</h2>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $comment->user->name ?? 'Ẩn danh' }}</h5>
                            <p class="mb-1"><strong>Sản phẩm:</strong> {{ $comment->product->name ?? 'Không xác định' }}</p>
                            <p class="mb-1"><strong>Nội dung:</strong> {{ $comment->comment }}</p>
                            <p class="mb-0"><small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small></p>
                        </div>
                    </div>
                </div>
                <h5 class="mb-3">Phản hồi của admin</h5>
                @forelse($comment->replies as $reply)
                    <div class="card mb-2 ml-4 border-left border-primary">
                        <div class="card-body py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $reply->user->name ?? 'Admin' }}:</strong> {{ $reply->comment }}
                                <span class="text-muted ml-2">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <form action="{{ route('admin.comments.destroy', $reply->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa phản hồi này?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="ml-4 text-muted">Chưa có phản hồi nào.</div>
                @endforelse
                <div class="card mt-4">
                    <div class="card-body">
                        <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="comment">Phản hồi bình luận:</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-reply"></i> Gửi phản hồi</button>
                        </form>
                    </div>
                </div>
                <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary mt-3"><i class="mdi mdi-arrow-left"></i> Quay lại danh sách</a>
            </div>
        </div>
    </div>
</div>
@endsection 