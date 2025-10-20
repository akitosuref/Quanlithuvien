@extends('layouts.app')

@section('title', 'Chi Tiết Yêu Cầu')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi Tiết Yêu Cầu Sự Kiện</h1>
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

                    <div class="mb-3">
                        <strong>Trạng Thái:</strong>
                        <p>
                            @if($eventRequest->status === 'pending')
                                <span class="badge bg-warning">Chờ Xét Duyệt</span>
                            @elseif($eventRequest->status === 'approved')
                                <span class="badge bg-success">Đã Duyệt</span>
                            @else
                                <span class="badge bg-danger">Đã Từ Chối</span>
                            @endif
                        </p>
                    </div>

                    @if($eventRequest->reviewed_by)
                        <div class="mb-3">
                            <strong>Người Xét Duyệt:</strong>
                            <p>{{ $eventRequest->reviewer->name }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Ngày Xét Duyệt:</strong>
                            <p>{{ $eventRequest->reviewed_at->format('d/m/Y H:i') }}</p>
                        </div>

                        @if($eventRequest->review_note)
                            <div class="mb-3">
                                <strong>Ghi Chú Xét Duyệt:</strong>
                                <p>{{ $eventRequest->review_note }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('librarian') && $eventRequest->status === 'pending')
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Xét Duyệt</h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('event-requests.review', $eventRequest) }}" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> Xét Duyệt Yêu Cầu
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
