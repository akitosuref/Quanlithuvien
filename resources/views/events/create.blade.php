@extends('layouts.app')

@section('title', 'Tạo Sự Kiện Mới')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tạo Sự Kiện Mới</h1>
        <a href="{{ route('events.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Tạo Sự Kiện</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('events.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô Tả <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="event_date" class="form-label">Ngày & Giờ Diễn Ra <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('event_date') is-invalid @enderror" 
                               id="event_date" name="event_date" value="{{ old('event_date') }}" required>
                        @error('event_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="event_type" class="form-label">Loại Sự Kiện <span class="text-danger">*</span></label>
                        <select class="form-control @error('event_type') is-invalid @enderror" 
                                id="event_type" name="event_type" required>
                            <option value="">-- Chọn Loại Sự Kiện --</option>
                            <option value="Workshop" {{ old('event_type') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="Hội Thảo" {{ old('event_type') == 'Hội Thảo' ? 'selected' : '' }}>Hội Thảo</option>
                            <option value="Gặp Gỡ Tác Giả" {{ old('event_type') == 'Gặp Gỡ Tác Giả' ? 'selected' : '' }}>Gặp Gỡ Tác Giả</option>
                            <option value="Câu Lạc Bộ Đọc Sách" {{ old('event_type') == 'Câu Lạc Bộ Đọc Sách' ? 'selected' : '' }}>Câu Lạc Bộ Đọc Sách</option>
                            <option value="Triển Lãm" {{ old('event_type') == 'Triển Lãm' ? 'selected' : '' }}>Triển Lãm</option>
                            <option value="Khác" {{ old('event_type') == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('event_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Địa Điểm</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location') }}">
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="max_participants" class="form-label">Số Người Tham Gia Tối Đa</label>
                        <input type="number" class="form-control @error('max_participants') is-invalid @enderror" 
                               id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1">
                        <small class="form-text text-muted">Để trống nếu không giới hạn</small>
                        @error('max_participants')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                        <option value="published" {{ old('status', 'draft') == 'published' ? 'selected' : '' }}>Đã Xuất Bản</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã Hủy</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Hoàn Thành</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Tạo Sự Kiện
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
