@extends('layouts.app')

@section('title', 'Danh Sách Mượn Sách')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Danh Sách Mượn Sách</h1>
            <a href="{{ route('phieumuon.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tạo Phiếu Mượn Mới
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quản Lý Mượn Trả</h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Sách</th>
                                <th>Tên Độc Giả</th>
                                <th>Ngày Mượn</th>
                                <th>Ngày Hẹn Trả</th>
                                <th>Ngày Trả</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($phieumuons as $phieumuon)
                                <tr>
                                    <td>{{ $phieumuon->id }}</td>
                                    <td>{{ $phieumuon->bookItem->book->title }}</td>
                                    <td>{{ $phieumuon->member->name }}</td>
                                    <td>{{ $phieumuon->borrowed_date }}</td>
                                    <td>{{ $phieumuon->due_date }}</td>
                                    <td>{{ $phieumuon->return_date ?? 'Chưa trả' }}</td>
                                    <td>
                                        @if ($phieumuon->return_date)
                                            <span class="badge bg-success">Đã Trả</span>
                                        @elseif (now()->gt($phieumuon->due_date))
                                            <span class="badge bg-danger">Quá Hạn</span>
                                        @else
                                            <span class="badge bg-warning">Đang Mượn</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('phieumuon.show', $phieumuon->id) }}" class="btn btn-info btn-sm me-1"
                                            title="Xem"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('phieumuon.edit', $phieumuon->id) }}"
                                            class="btn btn-warning btn-sm me-1" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('phieumuon.destroy', $phieumuon->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa"
                                                onclick="return confirm('Bạn có chắc muốn xóa phiếu mượn này?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Chưa có phiếu mượn nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection