@extends('layouts.app')

@section('content')
<div class="container-fluid fade-in">
    <div class="mb-4">
        <h2 style="font-weight: 700; color: #1f2937; margin-bottom: 4px;">
            <i class="fas fa-tasks" style="color: #667eea;"></i> Quản Lý Yêu Cầu
        </h2>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Theo dõi tất cả yêu cầu của bạn tại một nơi</p>
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

    <ul class="nav nav-tabs mb-4" id="requestTabs" role="tablist" style="border-bottom: 2px solid #e5e7eb;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="borrow-tab" data-bs-toggle="tab" data-bs-target="#borrow" type="button" 
                    style="font-weight: 600; color: #6b7280; border: none; padding: 12px 24px; position: relative;">
                <i class="fas fa-book-reader"></i> Yêu Cầu Mượn Sách
                <span class="badge bg-primary ms-2">{{ $borrowRequests->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" 
                    style="font-weight: 600; color: #6b7280; border: none; padding: 12px 24px; position: relative;">
                <i class="fas fa-calendar-alt"></i> Yêu Cầu Sự Kiện
                <span class="badge bg-info ms-2">{{ $eventRequests->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservations" type="button"
                    style="font-weight: 600; color: #6b7280; border: none; padding: 12px 24px; position: relative;">
                <i class="fas fa-bookmark"></i> Đặt Trước Sách
                <span class="badge bg-warning ms-2">{{ $bookReservations->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="lendings-tab" data-bs-toggle="tab" data-bs-target="#lendings" type="button"
                    style="font-weight: 600; color: #6b7280; border: none; padding: 12px 24px; position: relative;">
                <i class="fas fa-book-open"></i> Sách Đang Mượn
                <span class="badge bg-success ms-2">{{ $bookLendings->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button"
                    style="font-weight: 600; color: #6b7280; border: none; padding: 12px 24px; position: relative;">
                <i class="fas fa-undo"></i> Yêu Cầu Trả Sách
                <span class="badge bg-secondary ms-2">{{ $returnRequests->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="requestTabsContent">
        <div class="tab-pane fade show active" id="borrow" role="tabpanel">
            @forelse ($borrowRequests as $borrowRequest)
            <div class="card mb-3" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden;">
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="d-flex gap-3">
                        @if($borrowRequest->bookItem->book->cover_image)
                        <div style="flex-shrink: 0;">
                            <img src="{{ asset($borrowRequest->bookItem->book->cover_image) }}" 
                                 alt="{{ $borrowRequest->bookItem->book->title }}"
                                 style="width: 80px; height: 107px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                        </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 style="color: #1f2937; font-weight: 700; margin-bottom: 8px;">
                                {{ $borrowRequest->bookItem->book->title }}
                            </h5>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                <i class="fas fa-barcode"></i> Barcode: {{ $borrowRequest->bookItem->barcode }}
                            </p>
                            <div class="d-flex gap-3 flex-wrap" style="font-size: 0.85rem; color: #6b7280;">
                                <span><i class="fas fa-calendar-day"></i> Ngày yêu cầu: {{ $borrowRequest->requested_date->format('d/m/Y') }}</span>
                                <span><i class="fas fa-calendar-check"></i> Ngày muốn mượn: {{ $borrowRequest->expected_borrow_date->format('d/m/Y') }}</span>
                            </div>
                            @if($borrowRequest->member_notes)
                            <div class="mt-2 p-2" style="background: #f9fafb; border-radius: 6px; font-size: 0.85rem;">
                                <small class="text-muted d-block" style="font-weight: 600;">Ghi chú của bạn:</small>
                                <p class="mb-0" style="color: #4b5563;">{{ $borrowRequest->member_notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div>
                            @if($borrowRequest->status === 'pending')
                                <span class="badge bg-warning" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-hourglass-half"></i> Chờ duyệt
                                </span>
                            @elseif($borrowRequest->status === 'approved')
                                <span class="badge bg-success" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-check-circle"></i> Đã duyệt
                                </span>
                            @elseif($borrowRequest->status === 'rejected')
                                <span class="badge bg-danger" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-times-circle"></i> Từ chối
                                </span>
                            @else
                                <span class="badge bg-secondary" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-ban"></i> Đã hủy
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($borrowRequest->librarian_notes)
                    <div class="mt-3 p-3" style="background: #f0f9ff; border-radius: 8px; border-left: 3px solid #667eea;">
                        <small class="text-muted d-block mb-1" style="font-weight: 600;">Phản hồi từ thủ thư:</small>
                        <p class="mb-0" style="color: #4b5563;">{{ $borrowRequest->librarian_notes }}</p>
                        @if($borrowRequest->processedBy)
                        <small class="text-muted d-block mt-1">
                            Xử lý bởi: {{ $borrowRequest->processedBy->name }} - {{ $borrowRequest->processed_at->format('d/m/Y H:i') }}
                        </small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 4rem 2rem; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 16px;">
                <i class="fas fa-book-reader" style="font-size: 4rem; color: #3b82f6; opacity: 0.3; margin-bottom: 1rem;"></i>
                <p class="text-muted mb-0">Bạn chưa có yêu cầu mượn sách nào</p>
            </div>
            @endforelse
        </div>

        <div class="tab-pane fade" id="events" role="tabpanel">
            @forelse ($eventRequests as $request)
            <div class="card mb-3" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden;">
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 style="color: #1f2937; font-weight: 700; margin-bottom: 8px;">
                                <i class="fas fa-calendar-alt" style="color: #667eea;"></i> {{ $request->event->title }}
                            </h5>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                <i class="fas fa-align-left"></i> {{ Str::limit($request->message, 100) }}
                            </p>
                            <div class="d-flex gap-3 flex-wrap" style="font-size: 0.85rem; color: #6b7280;">
                                <span><i class="fas fa-clock"></i> {{ $request->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div>
                            @if($request->status === 'pending')
                                <span class="badge bg-warning" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-hourglass-half"></i> Chờ duyệt
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="badge bg-success" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-check-circle"></i> Đã duyệt
                                </span>
                            @else
                                <span class="badge bg-danger" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-times-circle"></i> Từ chối
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($request->response_message)
                    <div class="mt-3 p-3" style="background: #f9fafb; border-radius: 8px; border-left: 3px solid #667eea;">
                        <small class="text-muted d-block mb-1" style="font-weight: 600;">Phản hồi từ thủ thư:</small>
                        <p class="mb-0" style="color: #4b5563;">{{ $request->response_message }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 4rem 2rem; background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%); border-radius: 16px;">
                <i class="fas fa-calendar-alt" style="font-size: 4rem; color: #667eea; opacity: 0.3; margin-bottom: 1rem;"></i>
                <p class="text-muted mb-0">Bạn chưa có yêu cầu sự kiện nào</p>
            </div>
            @endforelse
        </div>

        <div class="tab-pane fade" id="reservations" role="tabpanel">
            @forelse ($bookReservations as $reservation)
            <div class="card mb-3" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden;">
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="d-flex gap-3">
                        @if($reservation->bookItem->book->cover_image)
                        <div style="flex-shrink: 0;">
                            <img src="{{ asset($reservation->bookItem->book->cover_image) }}" 
                                 alt="{{ $reservation->bookItem->book->title }}"
                                 style="width: 80px; height: 107px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                        </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 style="color: #1f2937; font-weight: 700; margin-bottom: 8px;">
                                {{ $reservation->bookItem->book->title }}
                            </h5>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                <i class="fas fa-barcode"></i> Barcode: {{ $reservation->bookItem->barcode }}
                            </p>
                            <div class="d-flex gap-3 flex-wrap" style="font-size: 0.85rem; color: #6b7280;">
                                <span><i class="fas fa-clock"></i> {{ $reservation->reservation_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div>
                            @if($reservation->status === 'WAITING')
                                <span class="badge bg-warning" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-hourglass-half"></i> Chờ xử lý
                                </span>
                            @elseif($reservation->status === 'PROCESSING')
                                <span class="badge bg-info" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-spinner"></i> Đang xử lý
                                </span>
                            @else
                                <span class="badge bg-secondary" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-ban"></i> Đã hủy
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 4rem 2rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 16px;">
                <i class="fas fa-bookmark" style="font-size: 4rem; color: #f59e0b; opacity: 0.3; margin-bottom: 1rem;"></i>
                <p class="text-muted mb-0">Bạn chưa có yêu cầu mượn sách nào</p>
            </div>
            @endforelse
        </div>

        <div class="tab-pane fade" id="lendings" role="tabpanel">
            @forelse ($bookLendings as $lending)
            <div class="card mb-3" style="border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden;">
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="d-flex gap-3">
                        @if($lending->bookItem->book->cover_image)
                        <div style="flex-shrink: 0;">
                            <img src="{{ asset($lending->bookItem->book->cover_image) }}" 
                                 alt="{{ $lending->bookItem->book->title }}"
                                 style="width: 80px; height: 107px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                        </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 style="color: #1f2937; font-weight: 700; margin-bottom: 8px;">
                                {{ $lending->bookItem->book->title }}
                            </h5>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                <i class="fas fa-barcode"></i> Barcode: {{ $lending->bookItem->barcode }}
                            </p>
                            <div class="d-flex gap-3 flex-wrap" style="font-size: 0.85rem; color: #6b7280;">
                                <span><i class="fas fa-calendar-day"></i> Ngày mượn: {{ $lending->borrowed_date->format('d/m/Y') }}</span>
                                <span class="{{ $lending->due_date->isPast() ? 'text-danger' : '' }}">
                                    <i class="fas fa-calendar-times"></i> Hạn trả: {{ $lending->due_date->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2 align-items-end">
                            @if($lending->due_date->isPast())
                                <span class="badge bg-danger" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-exclamation-triangle"></i> Quá hạn
                                </span>
                            @else
                                <span class="badge bg-success" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-check"></i> Đang mượn
                                </span>
                            @endif
                            <button type="button" class="btn btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#returnModal{{ $lending->id }}"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 6px 16px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;">
                                <i class="fas fa-undo"></i> Yêu cầu trả
                            </button>
                        </div>
                    </div>
                    @if($lending->due_date->isPast())
                    <div class="mt-3 p-3" style="background: #fef2f2; border-radius: 8px; border-left: 3px solid #ef4444;">
                        <small class="text-danger d-block mb-1" style="font-weight: 600;">
                            <i class="fas fa-exclamation-circle"></i> Sách đã quá hạn {{ $lending->due_date->diffForHumans() }}
                        </small>
                        <p class="mb-0" style="color: #7f1d1d; font-size: 0.85rem;">Vui lòng trả sách sớm để tránh phí phạt</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="modal fade" id="returnModal{{ $lending->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px; border: none;">
                        <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px 16px 0 0;">
                            <h5 class="modal-title"><i class="fas fa-undo"></i> Yêu Cầu Trả Sách</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('requests.return.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="lending_id" value="{{ $lending->id }}">
                            <div class="modal-body" style="padding: 2rem;">
                                <div class="mb-3">
                                    <p class="mb-2"><strong>Sách:</strong> {{ $lending->bookItem->book->title }}</p>
                                    <p class="mb-2"><strong>Barcode:</strong> {{ $lending->bookItem->barcode }}</p>
                                    <p class="mb-3"><strong>Hạn trả:</strong> {{ $lending->due_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label for="member_notes{{ $lending->id }}" class="form-label" style="font-weight: 600; color: #1f2937;">
                                        Ghi chú (tùy chọn)
                                    </label>
                                    <textarea class="form-control" id="member_notes{{ $lending->id }}" name="member_notes" rows="3" 
                                              placeholder="Ví dụ: Tôi muốn trả sách vào ngày mai..."
                                              style="border: 2px solid #e5e7eb; border-radius: 8px;"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer" style="border-top: 1px solid #e5e7eb;">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                                        style="border-radius: 8px; padding: 8px 20px;">Hủy</button>
                                <button type="submit" class="btn" 
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 8px 20px; font-weight: 600;">
                                    <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 4rem 2rem; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 16px;">
                <i class="fas fa-book-open" style="font-size: 4rem; color: #10b981; opacity: 0.3; margin-bottom: 1rem;"></i>
                <p class="text-muted mb-0">Bạn hiện không mượn sách nào</p>
            </div>
            @endforelse
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
                                <i class="fas fa-barcode"></i> Barcode: {{ $returnRequest->lending->bookItem->barcode }}
                            </p>
                            <div class="d-flex gap-3 flex-wrap" style="font-size: 0.85rem; color: #6b7280;">
                                <span><i class="fas fa-calendar-day"></i> Ngày yêu cầu: {{ $returnRequest->requested_date->format('d/m/Y') }}</span>
                                <span><i class="fas fa-calendar-times"></i> Hạn trả: {{ $returnRequest->lending->due_date->format('d/m/Y') }}</span>
                            </div>
                            @if($returnRequest->member_notes)
                            <div class="mt-2 p-2" style="background: #f9fafb; border-radius: 6px; font-size: 0.85rem;">
                                <small class="text-muted d-block" style="font-weight: 600;">Ghi chú của bạn:</small>
                                <p class="mb-0" style="color: #4b5563;">{{ $returnRequest->member_notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div>
                            @if($returnRequest->status === 'pending')
                                <span class="badge bg-warning" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-hourglass-half"></i> Chờ xử lý
                                </span>
                            @elseif($returnRequest->status === 'approved')
                                <span class="badge bg-info" style="padding: 8px 16px; font-size: 0.85rem;">
                                    <i class="fas fa-check-circle"></i> Đã duyệt
                                </span>
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
                    @if($returnRequest->librarian_notes)
                    <div class="mt-3 p-3" style="background: #f0f9ff; border-radius: 8px; border-left: 3px solid #667eea;">
                        <small class="text-muted d-block mb-1" style="font-weight: 600;">Phản hồi từ thủ thư:</small>
                        <p class="mb-0" style="color: #4b5563;">{{ $returnRequest->librarian_notes }}</p>
                        @if($returnRequest->processed_by)
                        <small class="text-muted d-block mt-1">
                            Xử lý bởi: {{ $returnRequest->processedBy->name }} - {{ $returnRequest->processed_at->format('d/m/Y H:i') }}
                        </small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 4rem 2rem; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 16px;">
                <i class="fas fa-undo" style="font-size: 4rem; color: #3b82f6; opacity: 0.3; margin-bottom: 1rem;"></i>
                <p class="text-muted mb-0">Bạn chưa có yêu cầu trả sách nào</p>
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
