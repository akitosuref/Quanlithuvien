@extends('layouts.app')

@section('title', 'Chỉnh Sửa Phiếu Mượn')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh Sửa Phiếu Mượn</h1>
        <a href="{{ route('phieumuon.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay Lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Chỉnh Sửa Phiếu Mượn</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('phieumuon.update', $phieumuon->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="book_item_id" class="form-label">Sách</label>
                    <select class="form-control @error('book_item_id') is-invalid @enderror" id="book_item_id" name="book_item_id"
                        required>
                        <option value="">Chọn sách</option>
                        @foreach($bookItems as $bookItem)
                        <option value="{{ $bookItem->id }}"
                            {{ old('book_item_id', $phieumuon->book_item_id) == $bookItem->id ? 'selected' : '' }}>
                            {{ $bookItem->book->title }} (Barcode: {{ $bookItem->barcode }})
                        </option>
                        @endforeach
                    </select>
                    @error('book_item_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="member_id" class="form-label">Độc Giả</label>
                    <select class="form-control @error('member_id') is-invalid @enderror" id="member_id"
                        name="member_id" required>
                        <option value="">Chọn độc giả</option>
                        @foreach($members as $member)
                        <option value="{{ $member->id }}"
                            {{ old('member_id', $phieumuon->member_id) == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('member_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="borrowed_date" class="form-label">Ngày Mượn</label>
                    <input type="date" class="form-control @error('borrowed_date') is-invalid @enderror"
                        id="borrowed_date" name="borrowed_date"
                        value="{{ old('borrowed_date', $phieumuon->borrowed_date) }}" required>
                    @error('borrowed_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label">Ngày Hẹn Trả</label>
                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date"
                        name="due_date" value="{{ old('due_date', $phieumuon->due_date) }}" required>
                    @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="return_date" class="form-label">Ngày Trả</label>
                    <input type="date" class="form-control @error('return_date') is-invalid @enderror"
                        id="return_date" name="return_date"
                        value="{{ old('return_date', $phieumuon->return_date) }}">
                    @error('return_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật Phiếu Mượn</button>
            </form>
        </div>
    </div>
</div>
@endsection