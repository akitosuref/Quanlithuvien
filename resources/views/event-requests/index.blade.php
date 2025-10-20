@extends('layouts.app')

@section('title', 'Danh Sách Yêu Cầu Sự Kiện')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                @if(Auth::user()->hasRole('librarian'))
                    Quản Lý Yêu Cầu Sự Kiện
                @else
                    Yêu Cầu Sự Kiện Của Tôi
                @endif
            </h1>
            @if(!Auth::user()->hasRole('librarian'))
                <a href="{{ route('event-requests.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Gửi Yêu Cầu Mới
                </a>
            @endif
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Danh Sách Yêu Cầu</h6>
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
                                @if(Auth::user()->hasRole('librarian'))
                                    <th>Thành Viên</th>
                                @endif
                                <th>Tiêu Đề</th>
                                <th>Ngày Đề Xuất</th>
                                <th>Ngày Gửi</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $index => $request)
                                <tr>
                                    <td>{{ $requests->firstItem() + $index }}</td>
                                    @if(Auth::user()->hasRole('librarian'))
                                        <td>{{ $request->member->name }}</td>
                                    @endif
                                    <td>{{ $request->title }}</td>
                                    <td>{{ $request->requested_event_date ? $request->requested_event_date->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning">Chờ Xét Duyệt</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">Đã Duyệt</span>
                                        @else
                                            <span class="badge bg-danger">Đã Từ Chối</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('event-requests.show', $request) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->hasRole('librarian') && $request->status === 'pending')
                                            <a href="{{ route('event-requests.review', $request) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-check"></i> Xét Duyệt
                                            </a>
                                        @endif
                                        @if(!Auth::user()->hasRole('librarian') && $request->status === 'pending')
                                            <form action="{{ route('event-requests.destroy', $request) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->hasRole('librarian') ? '7' : '6' }}" class="text-center">Không có yêu cầu nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
