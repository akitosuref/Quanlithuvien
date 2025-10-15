@extends('layouts.app')

@section('title', 'Chi Tiết Thành Viên')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi Tiết Độc Giả</h1>
            <div>
                <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary shadow-sm mr-2">
                    <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh Sửa
                </a>
                <a href="{{ route('members.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông Tin Cá Nhân</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Tên:</strong> {{ $member->name }}</p>
                        <p class="mb-2"><strong>Email:</strong> {{ $member->email }}</p>
                        <p class="mb-2"><strong>Điện Thoại:</strong> {{ $member->phone ?? 'Chưa cập nhật' }}</p>
                        <p class="mb-2"><strong>Trạng Thái:</strong> 
                            @if($member->account_status === 'ACTIVE')
                                <span class="badge badge-success">Hoạt động</span>
                            @elseif($member->account_status === 'CLOSED')
                                <span class="badge badge-secondary">Đã đóng</span>
                            @elseif($member->account_status === 'CANCELED')
                                <span class="badge badge-warning">Đã hủy</span>
                            @elseif($member->account_status === 'BLACKLISTED')
                                <span class="badge badge-danger">Danh sách đen</span>
                            @endif
                        </p>
                        <p class="mb-2"><strong>Mật khẩu:</strong> {{ $member->password }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Địa Chỉ</h6>
                    </div>
                    <div class="card-body">
                        @if($member->address)
                            <p class="mb-2"><strong>Đường:</strong> {{ $member->address->street ?? 'Chưa cập nhật' }}</p>
                            <p class="mb-2"><strong>Thành phố:</strong> {{ $member->address->city ?? 'Chưa cập nhật' }}</p>
                            <p class="mb-2"><strong>Tỉnh/Bang:</strong> {{ $member->address->state ?? 'Chưa cập nhật' }}</p>
                            <p class="mb-2"><strong>Mã bưu điện:</strong> {{ $member->address->zip_code ?? 'Chưa cập nhật' }}</p>
                            <p class="mb-2"><strong>Quốc gia:</strong> {{ $member->address->country ?? 'Chưa cập nhật' }}</p>
                        @else
                            <p class="text-muted">Chưa cập nhật địa chỉ</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
