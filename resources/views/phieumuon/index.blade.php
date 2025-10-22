@extends('layouts.app')

@section('title', 'Danh Sách Mượn Sách')

@section('content')
    <div class="container-fluid fade-in">
        <div class="mb-4">
            <h2 style="font-weight: 700; color: #1f2937; margin-bottom: 4px;">
                <i class="fas fa-ticket-alt" style="color: #667eea;"></i> Quản Lý Mượn Trả
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Quản lý phiếu mượn và yêu cầu trả sách</p>
        </div>

        @if (session('success'))
        <div class="alert alert-success" style="border-left: 4px solid #10b981; background: #f0fdf4; border-radius: 12px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger" style="border-left: 4px solid #ef4444; background: #fef2f2; border-radius: 12px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('phieumuon.create') }}" class="btn" 
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-plus"></i> Tạo Phiếu Mượn Mới
            </a>
        </div>

        <ul class="nav nav-tabs mb-4" id="lendingTabs" role="tablist" style="border-bottom: 2px solid #e5e7eb;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="lendings-tab" data-bs-toggle="tab" data-bs-target="#lendings" type="button" 
                        style="font-weight: 600; color: #6b7280; border: none; padding: 12px 24px; position: relative;">
                    <i class="fas fa-book-open"></i> Phiếu Mượn
                    <span class="badge bg-primary ms-2">{{ $phieumuons->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button"
                        style="font-weight: 600; color: #6b7280; border: none; padding: 12px 24px; position: relative;">
                    <i class="fas fa-undo"></i> Yêu Cầu Trả Sách
                    <span class="badge bg-warning ms-2">{{ $returnRequests->where('status', 'pending')->count() }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="lendingTabsContent">
            <div class="tab-pane fade show active" id="lendings" role="tabpanel">
                <div class="card shadow-sm" style="border: none; border-radius: 12px;">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" style="border-collapse: separate; border-spacing: 0;">
                                <thead style="background: #f9fafb;">
                                    <tr>
                                        <th style="padding: 12px; border: none;">ID</th>
                                        <th style="padding: 12px; border: none;">Tên Sách</th>
                                        <th style="padding: 12px; border: none;">Độc Giả</th>
                                        <th style="padding: 12px; border: none;">Ngày Mượn</th>
                                        <th style="padding: 12px; border: none;">Hạn Trả</th>
                                        <th style="padding: 12px; border: none;">Ngày Trả</th>
                                        <th style="padding: 12px; border: none;">Trạng Thái</th>
                                        <th style="padding: 12px; border: none;">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($phieumuons as $phieumuon)
                                        <tr style="border-bottom: 1px solid #f3f4f6;">
                                            <td style="padding: 12px;">{{ $phieumuon->id }}</td>
                                            <td style="padding: 12px; font-weight: 600; color: #1f2937;">{{ $phieumuon->bookItem->book->title }}</td>
                                            <td style="padding: 12px;">{{ $phieumuon->member->name }}</td>
                                            <td style="padding: 12px;">{{ $phieumuon->borrowed_date->format('d/m/Y') }}</td>
                                            <td style="padding: 12px;">{{ $phieumuon->due_date->format('d/m/Y') }}</td>
                                            <td style="padding: 12px;">{{ $phieumuon->return_date ? $phieumuon->return_date->format('d/m/Y') : '-' }}</td>
                                            <td style="padding: 12px;">
                                                @if ($phieumuon->return_date)
                                                    <span class="badge bg-success" style="padding: 6px 12px;">Đã Trả</span>
                                                @elseif ($phieumuon->isOverdue())
                                                    <span class="badge bg-danger" style="padding: 6px 12px;">Quá Hạn</span>
                                                @else
                                                    <span class="badge bg-warning" style="padding: 6px 12px;">Đang Mượn</span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px;">
                                                <a href="{{ route('phieumuon.show', $phieumuon->id) }}" class="btn btn-sm btn-info me-1" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('phieumuon.edit', $phieumuon->id) }}" class="btn btn-sm btn-warning me-1" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('phieumuon.destroy', $phieumuon->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa" 
                                                            onclick="return confirm('Bạn có chắc muốn xóa phiếu mượn này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center" style="padding: 3rem;">
                                                <i class="fas fa-inbox" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                                                <p class="text-muted mb-0">Chưa có phiếu mượn nào</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="returns" role="tabpanel">
                @forelse ($returnRequests as $returnRequest)
                <div class="card mb-3" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden;">
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="d-flex gap-3">
                            @if($returnRequest->lending->bookItem->book->cover_image)
                            <div style="flex-shrink: 0;">
                                <img src="{{ asset($returnRequest->lending->bookItem->book->cover_image) }}" 
                                     alt="{{ $returnRequest->lending->bookItem->book->title }}"
                                     style="width: 80px; height: 107px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 style="color: #1f2937; font-weight: 700; margin-bottom: 8px;">
                                    {{ $returnRequest->lending->bookItem->book->title }}
                                </h5>
                                <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-user"></i> {{ $returnRequest->lending->member->name }} | 
                                    <i class="fas fa-barcode"></i> {{ $returnRequest->lending->bookItem->barcode }}
                                </p>
                                <div class="d-flex gap-3 flex-wrap" style="font-size: 0.85rem; color: #6b7280;">
                                    <span><i class="fas fa-calendar-day"></i> Ngày yêu cầu: {{ $returnRequest->requested_date->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-calendar-times"></i> Hạn trả: {{ $returnRequest->lending->due_date->format('d/m/Y') }}</span>
                                </div>
                                @if($returnRequest->member_notes)
                                <div class="mt-2 p-2" style="background: #f9fafb; border-radius: 6px; font-size: 0.85rem;">
                                    <small class="text-muted d-block" style="font-weight: 600;">Ghi chú từ độc giả:</small>
                                    <p class="mb-0" style="color: #4b5563;">{{ $returnRequest->member_notes }}</p>
                                </div>
                                @endif
                                @if($returnRequest->librarian_notes)
                                <div class="mt-2 p-2" style="background: #f0f9ff; border-radius: 6px; font-size: 0.85rem;">
                                    <small class="text-muted d-block" style="font-weight: 600;">Ghi chú của bạn:</small>
                                    <p class="mb-0" style="color: #4b5563;">{{ $returnRequest->librarian_notes }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-end">
                                @if($returnRequest->status === 'pending')
                                    <span class="badge bg-warning" style="padding: 8px 16px; font-size: 0.85rem;">
                                        <i class="fas fa-hourglass-half"></i> Chờ xử lý
                                    </span>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('phieumuon.return.complete', $returnRequest) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Xác nhận đã trả">
                                                <i class="fas fa-check"></i> Hoàn thành
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $returnRequest->id }}"
                                                title="Từ chối">
                                            <i class="fas fa-times"></i> Từ chối
                                        </button>
                                    </div>
                                @elseif($returnRequest->status === 'approved')
                                    <span class="badge bg-info" style="padding: 8px 16px; font-size: 0.85rem;">
                                        <i class="fas fa-check-circle"></i> Đã duyệt
                                    </span>
                                    <form action="{{ route('phieumuon.return.complete', $returnRequest) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Xác nhận đã trả">
                                            <i class="fas fa-check"></i> Hoàn thành
                                        </button>
                                    </form>
                                @elseif($returnRequest->status === 'completed')
                                    <span class="badge bg-success" style="padding: 8px 16px; font-size: 0.85rem;">
                                        <i class="fas fa-check-double"></i> Hoàn thành
                                    </span>
                                @else
                                    <span class="badge bg-danger" style="padding: 8px 16px; font-size: 0.85rem;">
                                        <i class="fas fa-times-circle"></i> Từ chối
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="rejectModal{{ $returnRequest->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content" style="border-radius: 16px; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 16px 16px 0 0;">
                                <h5 class="modal-title"><i class="fas fa-times-circle"></i> Từ Chối Yêu Cầu</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('phieumuon.return.reject', $returnRequest) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body" style="padding: 2rem;">
                                    <div class="mb-3">
                                        <label for="librarian_notes{{ $returnRequest->id }}" class="form-label" style="font-weight: 600; color: #1f2937;">
                                            Lý do từ chối <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" id="librarian_notes{{ $returnRequest->id }}" name="librarian_notes" rows="3" 
                                                  placeholder="Nhập lý do từ chối yêu cầu trả sách..." required
                                                  style="border: 2px solid #e5e7eb; border-radius: 8px;"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer" style="border-top: 1px solid #e5e7eb;">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                                            style="border-radius: 8px; padding: 8px 20px;">Hủy</button>
                                    <button type="submit" class="btn btn-danger" 
                                            style="border-radius: 8px; padding: 8px 20px; font-weight: 600;">
                                        <i class="fas fa-times"></i> Từ chối
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center" style="padding: 4rem 2rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 16px;">
                    <i class="fas fa-undo" style="font-size: 4rem; color: #f59e0b; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p class="text-muted mb-0">Chưa có yêu cầu trả sách nào</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
    .nav-tabs .nav-link.active {
        color: #667eea !important;
        border-bottom: 3px solid #667eea !important;
        background: transparent !important;
    }

    .nav-tabs .nav-link:hover {
        color: #667eea !important;
    }
    </style>
@endsection