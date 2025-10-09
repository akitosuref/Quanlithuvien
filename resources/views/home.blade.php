@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Trang Chủ</h1>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Chào mừng đến với Hệ Thống Quản Lý Thư Viện</h6>
                    </div>
                    <div class="card-body">
                        <p>Đây là trang quản trị của hệ thống. Bạn có thể sử dụng thanh điều hướng bên trái để truy cập các
                            chức năng chính:</p>
                        <ul>
                            <li><strong>Quản Lý Sách:</strong> Thêm, sửa, xóa và xem danh sách các đầu sách trong thư viện.
                            </li>
                            <li><strong>Quản Lý Độc Giả:</strong> Quản lý thông tin của các thành viên và độc giả.</li>
                            <li><strong>Quản Lý Mượn Trả:</strong> Theo dõi quá trình mượn và trả sách của độc giả.</li>
                        </ul>
                        <p>Chúc bạn có một ngày làm việc hiệu quả!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Quản Lý Sách Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Quản Lý Sách</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookCount }} Sách</div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('books.index') }}" class="text-primary"><i
                                        class="fas fa-book fa-2x"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quản Lý Độc Giả Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Quản Lý Độc Giả</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Truy Cập</div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('members.index') }}" class="text-success"><i
                                        class="fas fa-users fa-2x"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quản Lý Mượn Trả Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Quản Lý Mượn Trả
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Truy Cập</div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('phieumuon.index') }}" class="text-info"><i
                                        class="fas fa-ticket-alt fa-2x"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection