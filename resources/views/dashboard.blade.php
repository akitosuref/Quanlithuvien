@extends('layouts.app')

@section('content')
    <div class="container-fluid fade-in">
        <div class="card mb-4">
            <div class="card-body"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px;">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-2" style="font-size: 2rem; font-weight: 700;">
                            <i class="fas fa-hand-sparkles"></i> Xin chào, {{ $user->name ?? 'Admin Librarian' }}!
                        </h2>
                        <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                            Vai trò: <span
                                style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 6px; font-weight: 600;">{{ $user->role ?? 'librarian' }}</span>
                        </p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger mt-3 mt-md-0"
                            style="box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-search"></i> Tìm kiếm Sách (Search Catalog)
            </div>
            <div class="card-body">
                <form action="{{ route('catalog.search') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="query" class="form-control"
                            placeholder="Nhập tiêu đề, tác giả, chủ đề, ISBN..." value="{{ $searchQuery ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <select name="search_type" class="form-control">
                            <option value="title" @if(isset($searchType) && $searchType == 'title') selected @endif>Theo Tiêu
                                đề</option>
                            <option value="author" @if(isset($searchType) && $searchType == 'author') selected @endif>Theo
                                Tên Tác giả</option>
                            <option value="subject" @if(isset($searchType) && $searchType == 'subject') selected @endif>Theo
                                Chủ đề</option>
                            <option value="publication_date" @if(isset($searchType) && $searchType == 'publication_date')
                            selected @endif>Theo Ngày XB</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($searchResults) && $searchResults->count() > 0)
            <div class="card mb-4" style="background: #f8f9ff;">
                <div class="card-body">
                    <h3 class="mb-4" style="font-weight: 700;">Kết quả tìm kiếm cho: "{{ $searchQuery }}"</h3>
                    <div class="row g-4">
                        @foreach ($searchResults as $book)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 style="color: var(--primary); font-weight: 700; margin-bottom: 8px;">{{ $book->title }}
                                        </h4>
                                        <p class="text-muted mb-3" style="font-size: 0.9rem;">
                                            <i class="fas fa-user"></i> Tác giả: {{ $book->author->name ?? 'N/A' }} |
                                            <i class="fas fa-barcode"></i> ISBN: {{ $book->isbn }}
                                        </p>

                                        @foreach ($book->items as $item)
                                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                                                <span style="font-weight: 600; font-size: 0.85rem;">Barcode: {{ $item->barcode }}</span>
                                                <span class="badge 
                                                                    @if($item->status == 'AVAILABLE') badge-success 
                                                                    @elseif($item->status == 'RESERVED') badge-warning 
                                                                    @else badge-danger 
                                                                    @endif">
                                                    {{ $item->status }}
                                                </span>

                                                @if ($user->isMember() && $item->status == 'LOANED' && !$item->currentReservation)
                                                    <form action="{{ route('member.reserve', $item) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-bookmark"></i> Đặt giữ
                                                        </button>
                                                    </form>
                                                @elseif ($user->isLibrarian())
                                                    <form action="{{ route('admin.books.delete', $book) }}" method="POST" class="d-inline"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả bản sao của sách này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i> Xóa
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $searchResults->links() }}
                    </div>
                </div>
            </div>
        @endif

        @if ($user->isLibrarian())
            <div class="mb-4">
                <h3 class="mb-3" style="font-weight: 700; color: #1f2937; border-left: 4px solid #6366f1; padding-left: 12px;">
                    <i class="fas fa-tasks"></i> Quản lý Nghiệp vụ & Dữ liệu (Thủ thư)
                </h3>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                            <i class="fas fa-book-open"></i> Cấp phát Sách (Issue Book)
                        </div>
                        <div class="card-body">
                            <form action="{{ route('librarian.issue') }}" method="POST">
                                @csrf
                                <p class="text-muted small mb-3"><i class="fas fa-info-circle"></i> Issue Book (R7, R8). ID
                                    Thành viên/Item</p>
                                <div class="mb-3">
                                    <label class="form-label">ID Thành viên</label>
                                    <input type="number" name="member_id" class="form-control" placeholder="Nhập ID thành viên"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ID Book Item</label>
                                    <input type="number" name="book_item_id" class="form-control"
                                        placeholder="Nhập ID book item" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check-circle"></i> Cấp phát
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;">
                            <i class="fas fa-undo"></i> Trả Sách (Return Book)
                        </div>
                        <div class="card-body">
                            <form action="{{ route('librarian.return') }}" method="POST">
                                @csrf
                                <p class="text-muted small mb-3"><i class="fas fa-info-circle"></i> Return book EXTEND Pay fine
                                    (R12)</p>
                                <div class="mb-3">
                                    <label class="form-label">ID Lần mượn (Book Lending ID)</label>
                                    <input type="number" name="lending_id" class="form-control" placeholder="Nhập ID lần mượn"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-check-double"></i> Hoàn tất Trả sách
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;">
                            <i class="fas fa-plus-circle"></i> Thêm Sách Mới
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.books.create') }}" method="POST">
                                @csrf
                                <p class="text-muted small mb-3"><i class="fas fa-info-circle"></i> Add book INCLUDE Add book
                                    item</p>
                                <div class="row g-2">
                                    <div class="col-12 mb-2">
                                        <input type="text" name="title" class="form-control" placeholder="Tiêu đề" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="isbn" class="form-control" placeholder="ISBN" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="number" name="author_id" class="form-control" placeholder="ID Tác giả"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="number" name="num_copies" class="form-control"
                                            placeholder="Số lượng bản sao" required min="1">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="number" name="rack_id" class="form-control" placeholder="ID Kệ sách"
                                            required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-save"></i> Thêm Sách
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;">
                            <i class="fas fa-user-plus"></i> Đăng ký Thành viên Mới
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.members.register') }}" method="POST">
                                @csrf
                                <p class="text-muted small mb-3"><i class="fas fa-info-circle"></i> Register new account INCLUDE
                                    Issue library card</p>
                                <div class="row g-2">
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="name" class="form-control" placeholder="Tên" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="street" class="form-control" placeholder="Đường" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" name="city" class="form-control" placeholder="Thành phố" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 text-white">
                                    <i class="fas fa-id-card"></i> Đăng ký
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($user->isMember())
            <div class="mb-4">
                <h3 class="mb-3" style="font-weight: 700; color: #1f2937; border-left: 4px solid #10b981; padding-left: 12px;">
                    <i class="fas fa-user-circle"></i> Chức năng dành cho Thành viên
                </h3>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-user" style="font-size: 3rem; color: #10b981; margin-bottom: 16px;"></i>
                            <h5 style="font-weight: 700; margin-bottom: 12px;">Thông tin Cá nhân</h5>
                            <p class="text-muted mb-4">Số sách đang mượn:
                                {{ $user->lendings()->whereNull('return_date')->count() }} / 10 (R7)
                            </p>
                            <a href="{{ route('member.profile') }}" class="btn btn-success w-100">
                                <i class="fas fa-info-circle"></i> Xem Chi tiết
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-history" style="font-size: 3rem; color: #6366f1; margin-bottom: 16px;"></i>
                            <h5 style="font-weight: 700; margin-bottom: 12px;">Lịch sử Mượn</h5>
                            <p class="text-muted mb-4">Xem lịch sử mượn và đặt giữ sách của bạn.</p>
                            <a href="{{ route('member.lending-history') }}" class="btn btn-primary w-100">
                                <i class="fas fa-list"></i> Xem lịch sử
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-clock" style="font-size: 3rem; color: #f59e0b; margin-bottom: 16px;"></i>
                            <h5 style="font-weight: 700; margin-bottom: 12px;">Sách Đang Mượn</h5>
                            @php
                                $currentLendings = $user->lendings()->whereNull('return_date')->with('bookItem.book')->get();
                            @endphp
                            <p class="text-muted mb-4">{{ $currentLendings->count() }} cuốn đang mượn</p>
                            <a href="{{ route('member.profile') }}" class="btn btn-warning w-100 text-white">
                                <i class="fas fa-book-open"></i> Xem & Gia hạn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection