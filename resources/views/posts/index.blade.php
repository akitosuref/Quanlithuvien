@extends('layouts.app')

@section('content')
<div class="container-fluid fade-in">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 style="font-weight: 700; color: #1f2937; margin-bottom: 4px;">
                        <i class="fas fa-book-reader" style="color: #667eea;"></i> Cảm nhận về Sách
                    </h2>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">Chia sẻ và khám phá những cảm nhận về sách từ cộng đồng</p>
                </div>
                <a href="{{ route('posts.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); font-weight: 600;">
                    <i class="fas fa-pen-fancy"></i> Viết cảm nhận mới
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success" style="border-left: 4px solid #10b981; background: #f0fdf4;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row g-4">
        @forelse ($posts as $post)
        <div class="col-12">
            <div class="card" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s ease; overflow: hidden;">
                <div class="card-body" style="padding: 1.75rem;">
                    <div class="d-flex gap-4 mb-3">
                        @if($post->book->cover_image)
                        <div style="flex-shrink: 0;">
                            <img src="{{ asset($post->book->cover_image) }}" 
                                 alt="{{ $post->book->title }}"
                                 style="width: 120px; height: 160px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h4 style="color: #1f2937; font-weight: 700; margin-bottom: 12px; line-height: 1.3;">
                                        <a href="{{ route('posts.show', $post) }}" style="text-decoration: none; color: inherit; transition: color 0.2s;">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                    <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size: 0.9rem;">
                                        <span style="color: #667eea; font-weight: 600;">
                                            <i class="fas fa-book"></i> {{ $post->book->title }}
                                        </span>
                                        <span style="color: #6b7280;">
                                            <i class="fas fa-user-circle"></i> {{ $post->user->name }}
                                        </span>
                                        <span style="color: #9ca3af;">
                                            <i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                @if(Auth::id() === $post->user_id || Auth::user()->isLibrarian())
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>

                            <p style="color: #4b5563; line-height: 1.7; font-size: 0.98rem; margin-bottom: 1.25rem;">
                                {{ Str::limit($post->content, 250) }}
                            </p>

                            <div class="d-flex gap-2 pt-2">
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $post->isLikedBy(Auth::id()) ? 'btn-primary' : 'btn-outline-primary' }}" 
                                            style="border-radius: 20px; padding: 6px 16px; font-weight: 600; transition: all 0.2s; {{ $post->isLikedBy(Auth::id()) ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;' : '' }}">
                                        <i class="fas fa-heart"></i> {{ $post->likes->count() }}
                                    </button>
                                </form>

                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-secondary" 
                                   style="border-radius: 20px; padding: 6px 16px; font-weight: 600; transition: all 0.2s;">
                                    <i class="fas fa-comment-dots"></i> {{ $post->comments->count() }}
                                </a>

                                <form action="{{ route('posts.share', $post) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                            style="border-radius: 20px; padding: 6px 16px; font-weight: 600; transition: all 0.2s;">
                                        <i class="fas fa-share-alt"></i> {{ $post->shares->count() }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center" style="padding: 4rem 2rem; background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%); border-radius: 16px;">
                <i class="fas fa-book-open" style="font-size: 4rem; color: #667eea; opacity: 0.5; margin-bottom: 1.5rem;"></i>
                <h4 style="color: #1f2937; font-weight: 700; margin-bottom: 0.5rem;">Chưa có cảm nhận nào</h4>
                <p class="text-muted mb-4">Hãy là người đầu tiên chia sẻ cảm nhận về sách yêu thích!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: 600;">
                    <i class="fas fa-pen-fancy"></i> Viết cảm nhận đầu tiên
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>

<style>
.card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    transform: translateY(-2px);
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

.card h4 a:hover {
    color: #667eea !important;
}
</style>
@endsection
