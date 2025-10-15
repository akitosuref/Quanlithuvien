@extends('layouts.app')

@section('title', 'Thêm Độc Giả Mới')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Thêm Độc Giả Mới</h1>
            <a href="{{ route('members.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Thêm Độc Giả</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('members.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="text-primary mb-3">Thông Tin Cá Nhân</h5>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Điện Thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    
                    <h5 class="text-primary mb-3">Mật Khẩu</h5>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" placeholder="Tối thiểu 8 ký tự" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="password_confirmation" name="password_confirmation" 
                            placeholder="Nhập lại mật khẩu" required>
                    </div>

                    <hr class="my-4">
                    
                    <h5 class="text-primary mb-3">Địa Chỉ</h5>
                    
                    <div class="mb-3">
                        <label for="street" class="form-label">Đường</label>
                        <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street"
                            value="{{ old('street') }}">
                        @error('street')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">Thành phố</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city"
                                value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">Tỉnh/Bang</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state"
                                value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="zip_code" class="form-label">Mã bưu điện</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code"
                                value="{{ old('zip_code') }}">
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Quốc gia</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country"
                                value="{{ old('country') }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu Độc Giả</button>
                </form>
            </div>
        </div>
    </div>

@endsection
