@extends('layouts.app')

@section('title', auth()->user()->role === 'librarian' ? 'Quản Lý Sách' : 'Thư Viện Sách')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                @if(auth()->user()->role === 'librarian')
                    Quản Lý Sách
                @else
                    Thư Viện Sách
                @endif
            </h1>
            @if(auth()->user()->role === 'librarian')
                <a href="{{ route('books.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Thêm Sách Mới
                </a>
            @endif
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    @if(auth()->user()->role === 'librarian')
                        Quản Lý Sách
                    @else
                        Danh Sách Sách
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
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

                @if(auth()->user()->role === 'member')
                    <div class="row">
                        @forelse ($books as $book)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    @if($book->cover_image)
                                        <img src="{{ asset($book->cover_image) }}" 
                                             class="card-img-top" 
                                             alt="{{ $book->title }}"
                                             style="height: 300px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 300px;">
                                            <i class="fas fa-book fa-5x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $book->title }}</h5>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-tag"></i> {{ $book->subject }}<br>
                                                <i class="fas fa-barcode"></i> {{ $book->isbn }}<br>
                                                <i class="fas fa-calendar"></i> 
                                                {{ $book->publication_date ? \Carbon\Carbon::parse($book->publication_date)->format('d/m/Y') : 'N/A' }}
                                            </small>
                                        </p>
                                        @php
                                            $availableCount = $book->bookItems->where('status', 'AVAILABLE')->count();
                                            $totalCount = $book->bookItems->count();
                                        @endphp
                                        <div class="mb-2">
                                            @if($availableCount > 0)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> {{ $availableCount }} cuốn có sẵn
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle"></i> Hết sách
                                                </span>
                                            @endif
                                            <span class="badge bg-secondary">
                                                Tổng: {{ $totalCount }} cuốn
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-eye"></i> Xem Chi Tiết & Mượn Sách
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                @if(request('search'))
                                    <i class="fas fa-search fa-5x text-muted mb-3"></i>
                                    <h4>Không tìm thấy sách nào</h4>
                                    <p class="text-muted">Không tìm thấy sách nào với từ khóa "<strong>{{ request('search') }}</strong>"</p>
                                    <a href="{{ route('books.index') }}" class="btn btn-primary">Xem tất cả sách</a>
                                @else
                                    <i class="fas fa-book fa-5x text-muted mb-3"></i>
                                    <h4>Không có sách nào trong thư viện</h4>
                                @endif
                            </div>
                        @endforelse
                    </div>
                @else
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
                @endif
                
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
