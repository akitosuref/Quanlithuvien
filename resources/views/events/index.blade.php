@extends('layouts.app')

@section('title', 'Quản Lý Sự Kiện')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản Lý Sự Kiện</h1>
            <a href="{{ route('events.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tạo Sự Kiện Mới
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Danh Sách Sự Kiện</h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tiêu Đề</th>
                                <th>Loại Sự Kiện</th>
                                <th>Ngày Diễn Ra</th>
                                <th>Địa Điểm</th>
                                <th>Số Người Tham Gia</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $index => $event)
                                <tr>
                                    <td>{{ $events->firstItem() + $index }}</td>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->event_type }}</td>
                                    <td>{{ $event->event_date->format('d/m/Y H:i') }}</td>
                                    <td>{{ $event->location ?? 'N/A' }}</td>
                                    <td>
                                        {{ $event->attendees->count() }}
                                        @if($event->max_participants)
                                            / {{ $event->max_participants }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->status === 'published')
                                            <span class="badge bg-success">Đã Xuất Bản</span>
                                        @elseif($event->status === 'draft')
                                            <span class="badge bg-secondary">Nháp</span>
                                        @elseif($event->status === 'cancelled')
                                            <span class="badge bg-danger">Đã Hủy</span>
                                        @else
                                            <span class="badge bg-info">Hoàn Thành</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sự kiện này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có sự kiện nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
