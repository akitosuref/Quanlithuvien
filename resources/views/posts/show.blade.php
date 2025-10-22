@extends('layouts.app')

@section('content')
<div class="container fade-in" style="max-width: 900px;">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success" style="border-left: 4px solid #10b981; background: #f0fdf4; border-radius: 12px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card mb-4" style="border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 16px; overflow: hidden;">
        <div class="card-body" style="padding: 2.5rem;">
            <div class="d-flex gap-4 mb-4">
                @if($post->book->cover_image)
                <div style="flex-shrink: 0;">
                    <img src="{{ asset($post->book->cover_image) }}" 
                         alt="{{ $post->book->title }}"
                         style="width: 180px; height: 240px; object-fit: cover; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                </div>
                @endif
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="flex-grow-1">
                            <h2 style="color: #1f2937; font-weight: 800; margin-bottom: 16px; line-height: 1.3; font-size: 2rem;">
                                {{ $post->title }}
                            </h2>
                            <div class="d-flex align-items-center gap-3 flex-wrap" style="padding: 12px 0; border-bottom: 2px solid #e5e7eb;">
                                <span style="color: #667eea; font-weight: 700; font-size: 1rem;">
                                    <i class="fas fa-book"></i> {{ $post->book->title }}
                                </span>
                                <span style="color: #6b7280; font-size: 0.95rem;">
                                    <i class="fas fa-user-circle"></i> {{ $post->user->name }}
                                </span>
                                <span style="color: #9ca3af; font-size: 0.95rem;">
                                    <i class="fas fa-calendar-alt"></i> {{ $post->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::id() === $post->user_id || Auth::user()->isLibrarian())
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 10px; font-weight: 600;">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                        @endif
                    </div>

                    <div style="color: #374151; line-height: 1.9; font-size: 1.05rem; padding: 1.5rem 0;">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-4" style="border-top: 2px solid #e5e7eb;">
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $post->isLikedBy(Auth::id()) ? 'btn-primary' : 'btn-outline-primary' }}" 
                                    style="border-radius: 25px; padding: 10px 24px; font-weight: 700; font-size: 1rem; transition: all 0.3s; {{ $post->isLikedBy(Auth::id()) ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);' : '' }}">
                                <i class="fas fa-heart"></i> Thích ({{ $post->likes->count() }})
                            </button>
                        </form>

                        <form action="{{ route('posts.share', $post) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success" 
                                    style="border-radius: 25px; padding: 10px 24px; font-weight: 700; font-size: 1rem; transition: all 0.3s;">
                                <i class="fas fa-share-alt"></i> Chia sẻ ({{ $post->shares->count() }})
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%); border-bottom: 2px solid #e5e7eb; padding: 1.5rem;">
            <h5 class="mb-0" style="color: #1f2937; font-weight: 700; font-size: 1.2rem;">
                <i class="fas fa-comments" style="color: #667eea;"></i> Bình luận ({{ $post->comments->count() }})
            </h5>
        </div>
        <div class="card-body" style="padding: 2rem;">
            <form action="{{ route('posts.comment', $post) }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <textarea name="content" rows="3" 
                              class="form-control @error('content') is-invalid @enderror" 
                              placeholder="Viết bình luận của bạn..." 
                              style="border-radius: 12px; border: 2px solid #e5e7eb; padding: 14px; font-size: 0.98rem; resize: none; transition: all 0.3s;"
                              onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                              onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                              required></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary" 
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px; padding: 10px 28px; font-weight: 700; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                    <i class="fas fa-paper-plane"></i> Gửi bình luận
                </button>
            </form>

            <div class="comments-list">
                @forelse ($post->comments as $comment)
                <div class="comment-item" style="background: #f9fafb; border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; border-left: 4px solid #667eea;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <strong style="color: #1f2937; font-weight: 700;">{{ $comment->user->name }}</strong>
                        </div>
                        <small class="text-muted" style="font-size: 0.85rem;">
                            <i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <p style="color: #4b5563; margin-bottom: 0; margin-left: 44px; line-height: 1.6;">{{ $comment->content }}</p>
                </div>
                @empty
                <div class="text-center" style="padding: 3rem 1rem; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 12px;">
                    <i class="fas fa-comments" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                    <p class="text-muted mb-0" style="font-size: 1rem;">
                        Chưa có bình luận nào. Hãy là người đầu tiên bình luận!
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-outline-success:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #10b981;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

.comment-item {
    transition: all 0.3s ease;
}

.comment-item:hover {
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
</style>
@endsection
