@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $event->title }}</h1>
        <a href="{{ route('member-events.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
        </a>
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
                        <strong><i class="fas fa-user"></i> Tổ Chức Bởi:</strong>
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
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đăng Ký Tham Gia</h6>
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

                    @if($userResponse)
                        <div class="alert alert-info">
                            <strong>Trạng thái của bạn:</strong>
                            @if($userResponse->response_type === 'attending')
                                <span class="badge bg-success">Sẽ Tham Gia</span>
                            @elseif($userResponse->response_type === 'interested')
                                <span class="badge bg-warning">Quan Tâm</span>
                            @else
                                <span class="badge bg-secondary">Không Tham Gia</span>
                            @endif
                        </div>

                        @if($userResponse->comment)
                            <p><strong>Nhận xét của bạn:</strong><br>{{ $userResponse->comment }}</p>
                        @endif
                    @endif

                    <form action="{{ route('event-responses.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="mb-3">
                            <label for="response_type" class="form-label">Phản Hồi</label>
                            <select class="form-control" id="response_type" name="response_type" required>
                                <option value="interested" {{ $userResponse && $userResponse->response_type === 'interested' ? 'selected' : '' }}>Quan Tâm</option>
                                <option value="attending" {{ $userResponse && $userResponse->response_type === 'attending' ? 'selected' : '' }}>Sẽ Tham Gia</option>
                                <option value="not_attending" {{ $userResponse && $userResponse->response_type === 'not_attending' ? 'selected' : '' }}>Không Tham Gia</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Nhận Xét (Tùy Chọn)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3">{{ $userResponse->comment ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> {{ $userResponse ? 'Cập Nhật Phản Hồi' : 'Gửi Phản Hồi' }}
                        </button>
                    </form>

                    @if($userResponse)
                        <form action="{{ route('event-responses.destroy', $userResponse) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký?')">
                                <i class="fas fa-times"></i> Hủy Đăng Ký
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh Sách Tham Gia ({{ $event->responses->count() }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Thành Viên</th>
                            <th>Trạng Thái</th>
                            <th>Nhận Xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->responses as $response)
                            <tr>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Chưa có người đăng ký.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
