@extends('layouts.app')

@section('title', 'Chỉnh Sửa Sách')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh Sửa Sách</h1>
        <a href="{{ route('books.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Chỉnh Sửa Sách</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn"
                        value="{{ old('isbn', $book->isbn) }}" required>
                    @error('isbn')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu Đề</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                        value="{{ old('title', $book->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Chủ Đề</label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject"
                        value="{{ old('subject', $book->subject) }}">
                    @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="publication_date" class="form-label">Ngày Xuất Bản</label>
                    <input type="date" class="form-control @error('publication_date') is-invalid @enderror"
                        id="publication_date" name="publication_date"
                        value="{{ old('publication_date', $book->publication_date) }}">
                    @error('publication_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Ảnh Bìa</label>
                    @if($book->cover_image)
                        <div class="mb-2">
                            <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="img-thumbnail" style="max-width: 200px;">
                            <p class="text-muted small">Ảnh hiện tại: {{ $book->cover_image }}</p>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
                    @error('cover_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Chấp nhận file: JPG, PNG, GIF (Tối đa 2MB). Để trống nếu không muốn thay đổi.</small>
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật Sách</button>
            </form>
        </div>
    </div>
</div>
@endsection