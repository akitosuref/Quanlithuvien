@extends('layouts.app')

@section('title', 'Danh Sách Sách')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Danh Sách Sách</h1>
            <a href="{{ route('books.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm Sách Mới
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quản Lý Sách</h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-8">
                        <form action="{{ route('books.index') }}" method="GET" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Tìm kiếm theo tên sách, ISBN, hoặc chủ đề..."
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('books.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Xóa
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="text-muted">
                            Tìm thấy <strong>{{ $books->total() }}</strong> kết quả
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Ảnh Bìa</th>
                                <th>ISBN</th>
                                <th>Tên Sách</th>
                                <th>Chủ Đề</th>
                                <th>Ngày Xuất Bản</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($books as $book)
                                <tr>
                                    <td>
                                        @if($book->cover_image)
                                            <img src="{{ asset($book->cover_image) }}" 
                                                 alt="{{ $book->title }}" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 80px; height: auto;">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $book->isbn }}</td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->subject }}</td>
                                    <td>{{ $book->publication_date ? \Carbon\Carbon::parse($book->publication_date)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-info btn-sm me-1"
                                            title="Xem"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm me-1"
                                            title="Sửa"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa"
                                                onclick="return confirm('Bạn có chắc muốn xóa sách này?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        @if(request('search'))
                                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                            <p>Không tìm thấy sách nào với từ khóa "<strong>{{ request('search') }}</strong>"</p>
                                            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">Xem tất cả sách</a>
                                        @else
                                            Không có sách nào trong thư viện.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($books->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Hiển thị <strong>{{ $books->firstItem() }}</strong> đến <strong>{{ $books->lastItem() }}</strong> 
                            trong tổng số <strong>{{ $books->total() }}</strong> kết quả
                        </div>
                        <div>
                            {{ $books->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection