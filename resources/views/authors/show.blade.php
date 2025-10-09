@extends('layouts.app')

@section('title', 'Chi Tiết Tác Giả')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi Tiết Tác Giả</h1>
            <a href="{{ route('authors.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ $author->name }}</h6>
            </div>
            <div class="card-body">
                <p><strong>Tiểu sử:</strong></p>
                <p>{{ $author->bio ?: 'Không có thông tin tiểu sử.' }}</p>

                <hr>

                <h6 class="font-weight-bold">Các sách của tác giả:</h6>
                @if($author->books->count() > 0)
                    <ul>
                        @foreach($author->books as $book)
                            <li><a href="{{ route('books.show', $book->id) }}">{{ $book->title }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p>Tác giả này chưa có sách nào trong thư viện.</p>
                @endif
            </div>
        </div>
    </div>
@endsection