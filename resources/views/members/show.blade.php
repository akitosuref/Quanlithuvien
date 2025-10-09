@extends('layouts.app')

@section('title', 'Chi Tiết Thành Viên')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi Tiết Độc Giả</h1>
            <a href="{{ route('members.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông Tin Độc Giả</h6>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $member->name }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ $member->email }}</p>
                <p class="card-text"><strong>Điện Thoại:</strong> {{ $member->phone }}</p>
                <p class="card-text"><strong>Địa Chỉ:</strong> {{ $member->address }}</p>
            </div>
        </div>
    </div>
@endsection