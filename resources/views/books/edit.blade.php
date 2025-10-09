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
                    <label for="title" class="form-label">Tiêu Đề</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                        value="{{ old('title', $book->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="author_id" class="form-label">Tác Giả</label>
                    <select class="form-control @error('author_id') is-invalid @enderror" id="author_id"
                        name="author_id" required>
                        <option value="">-- Chọn Tác Giả --</option>
                        @foreach($authors as $author)
                        <option value="{{ $author->id }}"
                            {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('author_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn"
                        value="{{ old('isbn', $book->isbn) }}" required>
                    @error('isbn')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="published_date" class="form-label">Ngày Xuất Bản</label>
                    <input type="date" class="form-control @error('published_date') is-invalid @enderror"
                        id="published_date" name="published_date"
                        value="{{ old('published_date', $book->published_date) }}" required>
                    @error('published_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Số Lượng</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                        name="quantity" value="{{ old('quantity', $book->quantity) }}" required min="0">
                    @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cover" class="form-label">Bìa Sách</label>
                    @if($book->cover)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="Bìa sách hiện tại" width="100">
                    </div>
                    @endif
                    <input type="file" class="form-control @error('cover') is-invalid @enderror" id="cover"
                        name="cover">
                    @error('cover')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật Sách</button>
            </form>
        </div>
    </div>
</div>
@endsection