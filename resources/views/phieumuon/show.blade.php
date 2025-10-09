@extends('layouts.app')

@section('title', 'Chi Tiết Phiếu Mượn')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi Tiết Phiếu Mượn</h1>
            <a href="{{ route('phieumuon.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Phiếu Mượn #{{ $phieumuon->id }}</h6>
            </div>
            <div class="card-body">
                <p class="card-text"><strong>Sách:</strong> {{ $phieumuon->bookItem->book->title }}</p>
                <p class="card-text"><strong>Barcode:</strong> {{ $phieumuon->bookItem->barcode }}</p>
                <p class="card-text"><strong>Thành Viên:</strong> {{ $phieumuon->member->name }}</p>
                <p class="card-text"><strong>Ngày Mượn:</strong> {{ $phieumuon->borrowed_date }}</p>
                <p class="card-text"><strong>Ngày Hẹn Trả:</strong> {{ $phieumuon->due_date }}</p>
                <p class="card-text"><strong>Ngày Trả:</strong> {{ $phieumuon->return_date ?? 'Chưa trả' }}</p>
                <p class="card-text"><strong>Trạng Thái:</strong>
                    @if ($phieumuon->return_date)
                        <span class="badge bg-success">Đã Trả</span>
                    @elseif (now()->gt($phieumuon->due_date))
                        <span class="badge bg-danger">Quá Hạn</span>
                    @else
                        <span class="badge bg-warning">Đang Mượn</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection