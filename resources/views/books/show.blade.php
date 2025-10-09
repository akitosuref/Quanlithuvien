@extends('layouts.app')

@section('title', 'Chi Tiết Sách')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi Tiết Sách</h1>
            <a href="{{ route('books.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông Tin Sách</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Bìa sách" class="img-fluid rounded mb-3"
                                style="max-height: 300px;">
                        @else
                            <div class="text-muted mb-3">Không có ảnh bìa</div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text"><strong>Tác Giả:</strong> {{ $book->author->name ?? 'N/A' }}</p>
                        <p class="card-text"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                        <p class="card-text"><strong>Ngày Xuất Bản:</strong> {{ $book->published_date }}</p>
                        <p class="card-text"><strong>Tổng Số Lượng:</strong> {{ $book->quantity }}</p>
                        <p class="card-text"><strong>Số Lượng Sẵn Có:</strong> {{ $book->available ?? $book->quantity }}</p>
                        
                        <div class="mt-4">
                            <a href="{{ route('posts.create', ['book_id' => $book->id]) }}" class="btn btn-primary">
                                <i class="fas fa-pen"></i> Viết cảm nhận về sách này
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection