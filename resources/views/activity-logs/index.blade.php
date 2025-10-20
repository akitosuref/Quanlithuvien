@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lịch sử Hoạt động</h5>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('activity-logs.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="subject_type" class="form-control">
                                    <option value="">Tất cả loại</option>
                                    <option value="App\Models\Book" {{ request('subject_type') == 'App\Models\Book' ? 'selected' : '' }}>Sách</option>
                                    <option value="App\Models\User" {{ request('subject_type') == 'App\Models\User' ? 'selected' : '' }}>Người dùng</option>
                                    <option value="App\Models\BookLending" {{ request('subject_type') == 'App\Models\BookLending' ? 'selected' : '' }}>Phiếu mượn</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="event" class="form-control">
                                    <option value="">Tất cả sự kiện</option>
                                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Tạo mới</option>
                                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Cập nhật</option>
                                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Xóa</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Người thực hiện</th>
                                    <th>Sự kiện</th>
                                    <th>Đối tượng</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $activity->causer?->name ?? 'Hệ thống' }}</td>
                                    <td>
                                        @if($activity->event == 'created')
                                            <span class="badge bg-success">Tạo mới</span>
                                        @elseif($activity->event == 'updated')
                                            <span class="badge bg-warning">Cập nhật</span>
                                        @elseif($activity->event == 'deleted')
                                            <span class="badge bg-danger">Xóa</span>
                                        @else
                                            <span class="badge bg-info">{{ $activity->event }}</span>
                                        @endif
                                    </td>
                                    <td>{{ class_basename($activity->subject_type) }}</td>
                                    <td>
                                        @if($activity->description)
                                            {{ $activity->description }}
                                        @else
                                            ID: {{ $activity->subject_id }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có hoạt động nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
