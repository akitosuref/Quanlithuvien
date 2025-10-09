@extends('layouts.app')

@section('content')
<div class="container fade-in" style="max-width: 900px;">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 style="color: var(--primary); font-weight: 700; margin-bottom: 12px;">
                        {{ $post->title }}
                    </h2>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">
                        <i class="fas fa-book"></i> <strong>{{ $post->book->title }}</strong> | 
                        <i class="fas fa-user"></i> {{ $post->user->name }} | 
                        <i class="fas fa-clock"></i> {{ $post->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                @if(Auth::id() === $post->user_id || Auth::user()->isLibrarian())
                <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </form>
                @endif
            </div>

            <div style="color: #374151; line-height: 1.8; font-size: 1rem;">
                {!! nl2br(e($post->content)) !!}
            </div>

            <div class="d-flex gap-3 mt-4 pt-3 border-top">
                <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn {{ $post->isLikedBy(Auth::id()) ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fas fa-thumbs-up"></i> Thích ({{ $post->likes->count() }})
                    </button>
                </form>

                <form action="{{ route('posts.share', $post) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-share"></i> Chia sẻ ({{ $post->shares->count() }})
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="background: #f9fafb;">
            <h5 class="mb-0"><i class="fas fa-comments"></i> Bình luận ({{ $post->comments->count() }})</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('posts.comment', $post) }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <textarea name="content" rows="3" 
                              class="form-control @error('content') is-invalid @enderror" 
                              placeholder="Viết bình luận..." 
                              required></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Gửi bình luận
                </button>
            </form>

            @forelse ($post->comments as $comment)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <strong style="color: #1f2937;">{{ $comment->user->name }}</strong>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p style="color: #4b5563; margin-bottom: 0;">{{ $comment->content }}</p>
            </div>
            @empty
            <p class="text-muted text-center mb-0">
                <i class="fas fa-info-circle"></i> Chưa có bình luận nào. Hãy là người đầu tiên!
            </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
