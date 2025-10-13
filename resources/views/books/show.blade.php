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
                    <div class="col-md-12">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                        <p class="card-text"><strong>Chủ Đề:</strong> {{ $book->subject }}</p>
                        <p class="card-text"><strong>Ngày Xuất Bản:</strong> {{ $book->publication_date ? \Carbon\Carbon::parse($book->publication_date)->format('d/m/Y') : 'N/A' }}</p>
                        
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