@extends('layouts.app')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight: 700; color: #1f2937;">
            <i class="fas fa-newspaper"></i> Cảm nhận về Sách
        </h2>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Viết cảm nhận mới
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row g-4">
        @forelse ($posts as $post)
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 style="color: var(--primary); font-weight: 700; margin-bottom: 8px;">
                                <a href="{{ route('posts.show', $post) }}" style="text-decoration: none; color: inherit;">
                                    {{ $post->title }}
                                </a>
                            </h4>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                <i class="fas fa-book"></i> Sách: <strong>{{ $post->book->title }}</strong> | 
                                <i class="fas fa-user"></i> {{ $post->user->name }} | 
                                <i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(Auth::id() === $post->user_id || Auth::user()->isLibrarian())
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>

                    <p style="color: #4b5563; line-height: 1.6;">
                        {{ Str::limit($post->content, 200) }}
                    </p>

                    <div class="d-flex gap-3 mt-3 pt-3 border-top">
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $post->isLikedBy(Auth::id()) ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-thumbs-up"></i> {{ $post->likes->count() }}
                            </button>
                        </form>

                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-comment"></i> {{ $post->comments->count() }}
                        </a>

                        <form action="{{ route('posts.share', $post) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-share"></i> {{ $post->shares->count() }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Chưa có bài viết nào. Hãy là người đầu tiên chia sẻ cảm nhận!
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection
