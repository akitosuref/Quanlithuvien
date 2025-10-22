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
                        id="publication_date" name="publication_date" value="{{ old('publication_date', $book->publication_date) }}">
                    @error('publication_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Ảnh Bìa</label>
                    @if($book->cover_image)
                        <div class="mb-2">
                            <p class="text-muted mb-2">Ảnh hiện tại:</p>
                            <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" 
                                 style="max-width: 200px; max-height: 300px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                           id="cover_image" name="cover_image" accept="image/*" onchange="previewImage(event)">
                    @error('cover_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Chấp nhận file: JPG, PNG, GIF (Tối đa 2MB). Để trống nếu không muốn thay đổi.</small>
                    
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <p class="text-muted mb-2">Xem trước ảnh mới:</p>
                        <img id="preview" src="" alt="Preview" style="max-width: 200px; max-height: 300px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập Nhật Thông Tin Sách
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Quản Lý Bản Sao</h6>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addBookItemModal">
                <i class="fas fa-plus"></i> Thêm Bản Sao
            </button>
        </div>
        <div class="card-body">
            @if($book->bookItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Kệ</th>
                                <th>Định Dạng</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($book->bookItems as $item)
                                <tr>
                                    <td><code>{{ $item->barcode }}</code></td>
                                    <td>
                                        @if($item->rack)
                                            Kệ {{ $item->rack->rack_number }} - {{ $item->rack->location_identifier }}
                                        @else
                                            <span class="text-muted">Chưa xác định</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->format }}</td>
                                    <td>
                                        @if($item->status === 'AVAILABLE')
                                            <span class="badge bg-success">Có sẵn</span>
                                        @elseif($item->status === 'LOANED')
                                            <span class="badge bg-warning">Đang mượn</span>
                                        @elseif($item->status === 'RESERVED')
                                            <span class="badge bg-info">Đã đặt trước</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBookItemModal{{ $item->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($item->status === 'AVAILABLE')
                                            <form action="{{ route('book-items.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Bạn có chắc muốn xóa bản sao này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm" disabled title="Không thể xóa bản sao đang được mượn/đặt">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <div class="modal fade" id="editBookItemModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('book-items.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Chỉnh Sửa Bản Sao: {{ $item->barcode }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Barcode</label>
                                                        <input type="text" class="form-control" name="barcode" value="{{ $item->barcode }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kệ</label>
                                                        <select class="form-control" name="rack_id" required>
                                                            @foreach($racks as $rack)
                                                                <option value="{{ $rack->id }}" {{ $item->rack_id == $rack->id ? 'selected' : '' }}>
                                                                    Kệ {{ $rack->rack_number }} - {{ $rack->location_identifier }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Định Dạng</label>
                                                        <select class="form-control" name="format" required>
                                                            <option value="HARDCOVER" {{ $item->format == 'HARDCOVER' ? 'selected' : '' }}>Bìa Cứng</option>
                                                            <option value="PAPERBACK" {{ $item->format == 'PAPERBACK' ? 'selected' : '' }}>Bìa Mềm</option>
                                                            <option value="EBOOK" {{ $item->format == 'EBOOK' ? 'selected' : '' }}>E-Book</option>
                                                            <option value="AUDIOBOOK" {{ $item->format == 'AUDIOBOOK' ? 'selected' : '' }}>Audiobook</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Sách này chưa có bản sao nào. Vui lòng thêm bản sao để có thể cho mượn.
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="addBookItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('book-items.store') }}" method="POST">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Bản Sao Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Số Lượng <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="quantity" value="1" min="1" max="50" required>
                        <small class="form-text text-muted">Nhập số lượng bản sao muốn thêm</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kệ <span class="text-danger">*</span></label>
                        <select class="form-control" name="rack_id" required>
                            <option value="">-- Chọn Kệ --</option>
                            @foreach($racks as $rack)
                                <option value="{{ $rack->id }}">
                                    Kệ {{ $rack->rack_number }} - {{ $rack->location_identifier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Định Dạng <span class="text-danger">*</span></label>
                        <select class="form-control" name="format" required>
                            <option value="HARDCOVER">Bìa Cứng</option>
                            <option value="PAPERBACK">Bìa Mềm</option>
                            <option value="EBOOK">E-Book</option>
                            <option value="AUDIOBOOK">Audiobook</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Barcode sẽ được tự động tạo theo định dạng: <code>{{ $book->isbn }}-XXX</code>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>
@endsection
