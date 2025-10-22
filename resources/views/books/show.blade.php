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

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông Tin Sách</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @if($book->cover_image)
                                    <img src="{{ asset($book->cover_image) }}" 
                                         alt="{{ $book->title }}" 
                                         class="img-fluid rounded shadow-sm mb-3" 
                                         style="max-width: 100%; height: auto;">
                                @else
                                    <div class="alert alert-secondary">
                                        <i class="fas fa-book fa-5x mb-3"></i>
                                        <p>Chưa có ảnh bìa</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4 class="mb-3">{{ $book->title }}</h4>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>ISBN:</strong></td>
                                        <td>{{ $book->isbn }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Chủ Đề:</strong></td>
                                        <td>{{ $book->subject }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày Xuất Bản:</strong></td>
                                        <td>{{ $book->publication_date ? \Carbon\Carbon::parse($book->publication_date)->format('d/m/Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tổng số bản:</strong></td>
                                        <td>
                                            <span class="badge bg-primary">{{ $book->bookItems->count() }} cuốn</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Có sẵn:</strong></td>
                                        <td>
                                            @php
                                                $availableCount = $book->bookItems->where('status', 'AVAILABLE')->count();
                                            @endphp
                                            @if($availableCount > 0)
                                                <span class="badge bg-success">{{ $availableCount }} cuốn</span>
                                            @else
                                                <span class="badge bg-danger">Hết sách</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                
                                <div class="mt-4">
                                    <a href="{{ route('posts.create', ['book_id' => $book->id]) }}" class="btn btn-info">
                                        <i class="fas fa-pen"></i> Viết cảm nhận về sách này
                                    </a>
                                    @if(auth()->user()->role === 'librarian')
                                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Chỉnh sửa
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                @if(auth()->user()->role === 'member')
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-hand-holding-heart"></i> Mượn Sách
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $availableItems = $book->bookItems->where('status', 'AVAILABLE');
                            @endphp
                            
                            @if($availableItems->count() > 0)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Có <strong>{{ $availableItems->count() }}</strong> cuốn có sẵn
                                </div>
                                
                                <form action="{{ route('requests.borrow.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Chọn bản sao:</strong></label>
                                        <select name="book_item_id" class="form-select" required>
                                            <option value="">-- Chọn bản sao --</option>
                                            @foreach($availableItems as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->barcode }} - {{ $item->format }}
                                                    @if($item->rack)
                                                        (Kệ: {{ $item->rack->rack_number }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Ngày muốn mượn:</strong></label>
                                        <input type="date" name="expected_borrow_date" class="form-control" 
                                               value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Ghi chú (không bắt buộc):</strong></label>
                                        <textarea name="member_notes" class="form-control" rows="2" 
                                                  placeholder="Nhập ghi chú nếu có..."></textarea>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <small>
                                            <i class="fas fa-info-circle"></i> Yêu cầu sẽ được gửi đến thủ thư để phê duyệt<br>
                                            <i class="fas fa-calendar-check"></i> Thời hạn mượn sau khi được duyệt: <strong>15 ngày</strong>
                                        </small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-paper-plane"></i> Gửi Yêu Cầu Mượn Sách
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Hiện tại không có bản sao nào có sẵn
                                </div>
                                
                                @php
                                    $loanedItems = $book->bookItems->where('status', 'LOANED');
                                @endphp
                                
                                @if($loanedItems->count() > 0)
                                    <p class="mb-2"><strong>Trạng thái các bản:</strong></p>
                                    <ul class="list-unstyled">
                                        <li><span class="badge bg-danger">{{ $loanedItems->count() }} cuốn đang được mượn</span></li>
                                        @php
                                            $reservedItems = $book->bookItems->where('status', 'RESERVED');
                                        @endphp
                                        @if($reservedItems->count() > 0)
                                            <li><span class="badge bg-warning">{{ $reservedItems->count() }} cuốn đã được đặt trước</span></li>
                                        @endif
                                    </ul>
                                    
                                    <hr>
                                    <p class="text-muted"><small><i class="fas fa-lightbulb"></i> Bạn có thể đặt trước sách này để được ưu tiên khi có sách trả lại.</small></p>
                                    <button type="button" class="btn btn-warning w-100" onclick="alert('Chức năng đặt trước sách đang được phát triển!')">
                                        <i class="fas fa-bookmark"></i> Đặt Trước Sách
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Danh Sách Bản Sao</h6>
                    </div>
                    <div class="card-body">
                        @if($book->bookItems->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Barcode</th>
                                            <th>Định dạng</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($book->bookItems as $item)
                                            <tr>
                                                <td><small>{{ $item->barcode }}</small></td>
                                                <td><small>{{ $item->format }}</small></td>
                                                <td>
                                                    @if($item->status === 'AVAILABLE')
                                                        <span class="badge bg-success">Có sẵn</span>
                                                    @elseif($item->status === 'LOANED')
                                                        <span class="badge bg-danger">Đã mượn</span>
                                                    @elseif($item->status === 'RESERVED')
                                                        <span class="badge bg-warning">Đặt trước</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Chưa có bản sao nào của cuốn sách này.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
