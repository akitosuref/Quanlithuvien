@extends('layouts.app')

@section('content')
<div class="container fade-in" style="max-width: 800px;">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3 class="mb-0"><i class="fas fa-pen"></i> Viết cảm nhận về Sách</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="book_id" class="form-label">Chọn Sách <span class="text-danger">*</span></label>
                    <select name="book_id" id="book_id" class="form-control @error('book_id') is-invalid @enderror" required>
                        <option value="">-- Chọn sách --</option>
                        @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} ({{ $book->author->name ?? 'N/A' }})
                        </option>
                        @endforeach
                    </select>
                    @error('book_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           placeholder="Nhập tiêu đề bài viết..." 
                           value="{{ old('title') }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <textarea name="content" id="content" rows="8" 
                              class="form-control @error('content') is-invalid @enderror" 
                              placeholder="Chia sẻ cảm nhận của bạn về cuốn sách này..." 
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Đăng bài
                    </button>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
