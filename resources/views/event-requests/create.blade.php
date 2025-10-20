@extends('layouts.app')

@section('title', 'Gửi Yêu Cầu Sự Kiện')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gửi Yêu Cầu Sự Kiện</h1>
        <a href="{{ route('event-requests.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Yêu Cầu Sự Kiện</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('event-requests.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu Đề Sự Kiện <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô Tả Chi Tiết <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                    <small class="form-text text-muted">Vui lòng mô tả chi tiết về sự kiện bạn mong muốn.</small>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="requested_event_date" class="form-label">Ngày Đề Xuất (Tùy Chọn)</label>
                    <input type="datetime-local" class="form-control @error('requested_event_date') is-invalid @enderror" 
                           id="requested_event_date" name="requested_event_date" value="{{ old('requested_event_date') }}">
                    <small class="form-text text-muted">Ngày bạn mong muốn sự kiện diễn ra.</small>
                    @error('requested_event_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Gửi Yêu Cầu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
