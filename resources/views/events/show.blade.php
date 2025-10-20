@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $event->title }}</h1>
        <div>
            <a href="{{ route('events.edit', $event) }}" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh Sửa
            </a>
            <a href="{{ route('events.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Sự Kiện</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Loại Sự Kiện:</strong>
                        <span class="badge bg-primary">{{ $event->event_type }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Trạng Thái:</strong>
                        @if($event->status === 'published')
                            <span class="badge bg-success">Đã Xuất Bản</span>
                        @elseif($event->status === 'draft')
                            <span class="badge bg-secondary">Nháp</span>
                        @elseif($event->status === 'cancelled')
                            <span class="badge bg-danger">Đã Hủy</span>
                        @else
                            <span class="badge bg-info">Hoàn Thành</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-calendar"></i> Thời Gian:</strong>
                        <p class="mb-0">{{ $event->event_date->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($event->location)
                        <div class="mb-3">
                            <strong><i class="fas fa-map-marker-alt"></i> Địa Điểm:</strong>
                            <p class="mb-0">{{ $event->location }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong><i class="fas fa-info-circle"></i> Mô Tả:</strong>
                        <p>{{ $event->description }}</p>
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-user"></i> Người Tạo:</strong>
                        <p class="mb-0">{{ $event->creator->name }}</p>
                    </div>

                    @if($event->max_participants)
                        <div class="mb-3">
                            <strong><i class="fas fa-users"></i> Số Người Tham Gia:</strong>
                            <p class="mb-0">{{ $event->attendees->count() }} / {{ $event->max_participants }}</p>
                            @if($event->attendees->count() >= $event->max_participants)
                                <span class="badge bg-danger">Đã Đầy</span>
                            @endif
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Ngày Tạo:</strong>
                        <p class="mb-0">{{ $event->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống Kê</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Tổng Phản Hồi:</strong> {{ $event->responses->count() }}</p>
                        <p class="mb-1"><strong>Sẽ Tham Gia:</strong> {{ $event->responses->where('response_type', 'attending')->count() }}</p>
                        <p class="mb-1"><strong>Quan Tâm:</strong> {{ $event->responses->where('response_type', 'interested')->count() }}</p>
                        <p class="mb-0"><strong>Không Tham Gia:</strong> {{ $event->responses->where('response_type', 'not_attending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh Sách Phản Hồi ({{ $event->responses->count() }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Thành Viên</th>
                            <th>Trạng Thái</th>
                            <th>Nhận Xét</th>
                            <th>Đánh Giá</th>
                            <th>Ngày Đăng Ký</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->responses as $index => $response)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $response->member->name }}</td>
                                <td>
                                    @if($response->response_type === 'attending')
                                        <span class="badge bg-success">Sẽ Tham Gia</span>
                                    @elseif($response->response_type === 'interested')
                                        <span class="badge bg-warning">Quan Tâm</span>
                                    @else
                                        <span class="badge bg-secondary">Không Tham Gia</span>
                                    @endif
                                </td>
                                <td>{{ $response->comment ?? 'N/A' }}</td>
                                <td>
                                    @if($response->rating)
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $response->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $response->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Chưa có phản hồi nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
