@extends('layouts.app')

@section('title', 'Thêm Sách Mới')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm Sách Mới</h1>
        <a href="{{ route('books.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Thêm Sách</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn"
                        value="{{ old('isbn') }}" required>
                    @error('isbn')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu Đề</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                        value="{{ old('title') }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Chủ Đề</label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject"
                        value="{{ old('subject') }}">
                    @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="publication_date" class="form-label">Ngày Xuất Bản</label>
                    <input type="date" class="form-control @error('publication_date') is-invalid @enderror"
                        id="publication_date" name="publication_date" value="{{ old('publication_date') }}">
                    @error('publication_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Ảnh Bìa</label>
                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                           id="cover_image" name="cover_image" accept="image/*" onchange="previewImage(event)">
                    @error('cover_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Chấp nhận file: JPG, PNG, GIF (Tối đa 2MB)</small>
                    
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <p class="text-muted mb-2">Xem trước:</p>
                        <img id="preview" src="" alt="Preview" style="max-width: 200px; max-height: 300px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                    </div>
                </div>

                <hr class="my-4">
                <h6 class="text-primary mb-3"><i class="fas fa-copy"></i> Thông Tin Bản Sao</h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Số Lượng Sách <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                               id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                               min="1" max="100" required>
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Nhập số lượng bản sao muốn thêm vào thư viện (1-100)</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="rack_id" class="form-label">Kệ Sách <span class="text-danger">*</span></label>
                        <select class="form-control @error('rack_id') is-invalid @enderror" 
                                id="rack_id" name="rack_id" required>
                            <option value="">-- Chọn Kệ Sách --</option>
                            @foreach($racks as $rack)
                                <option value="{{ $rack->id }}" {{ old('rack_id') == $rack->id ? 'selected' : '' }}>
                                    Kệ {{ $rack->rack_number }} - {{ $rack->location_identifier }}
                                </option>
                            @endforeach
                        </select>
                        @error('rack_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Chọn vị trí kệ để đặt các bản sao</small>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Lưu ý:</strong> Hệ thống sẽ tự động tạo mã barcode cho mỗi bản sao theo định dạng: 
                    <code>ISBN-001, ISBN-002, ...</code>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu Sách
                </button>
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