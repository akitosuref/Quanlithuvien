@extends('layouts.app')

@section('title', 'Xét Duyệt Yêu Cầu')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Xét Duyệt Yêu Cầu Sự Kiện</h1>
        <a href="{{ route('event-requests.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Yêu Cầu</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Tiêu Đề:</strong>
                        <p>{{ $eventRequest->title }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Mô Tả:</strong>
                        <p>{{ $eventRequest->description }}</p>
                    </div>

                    @if($eventRequest->requested_event_date)
                        <div class="mb-3">
                            <strong>Ngày Đề Xuất:</strong>
                            <p>{{ $eventRequest->requested_event_date->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Người Yêu Cầu:</strong>
                        <p>{{ $eventRequest->member->name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Ngày Gửi:</strong>
                        <p>{{ $eventRequest->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quyết Định Xét Duyệt</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('event-requests.update-review', $eventRequest) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label">Quyết Định <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">-- Chọn Quyết Định --</option>
                                <option value="approved">Chấp Nhận</option>
                                <option value="rejected">Từ Chối</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="review_note" class="form-label">Ghi Chú</label>
                            <textarea class="form-control @error('review_note') is-invalid @enderror" 
                                      id="review_note" name="review_note" rows="4">{{ old('review_note') }}</textarea>
                            <small class="form-text text-muted">Ghi chú lý do quyết định (tùy chọn).</small>
                            @error('review_note')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> Hoàn Tất Xét Duyệt
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
