@extends('layouts.app')

@section('content')
<div class="container-fluid fade-in">
    <div class="card mb-4">
        <div class="card-body" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="mb-2" style="font-size: 2rem; font-weight: 700;">
                        <i class="fas fa-history"></i> Lịch sử Mượn Sách
                    </h2>
                    <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                        Xem lại tất cả các lần mượn và đặt giữ sách của bạn
                    </p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-light mt-3 mt-md-0">
                    <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    @if (session('warning'))
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ session('warning') }}</span>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;">
                    <i class="fas fa-book"></i> Lịch sử Mượn Sách ({{ $lendingHistory->count() }} lần mượn)
                </div>
                <div class="card-body">
                    @if($lendingHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sách</th>
                                        <th>Ngày mượn</th>
                                        <th>Hạn trả</th>
                                        <th>Ngày trả</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lendingHistory as $index => $lending)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $lending->bookItem->book->title }}</strong><br>
                                            <small class="text-muted">ISBN: {{ $lending->bookItem->book->isbn }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($lending->borrowed_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lending->due_date)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($lending->return_date)
                                                {{ \Carbon\Carbon::parse($lending->return_date)->format('d/m/Y') }}
                                            @else
                                                <span class="badge badge-info">Đang mượn</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($lending->return_date)
                                                @php
                                                    $returnDate = \Carbon\Carbon::parse($lending->return_date);
                                                    $dueDate = \Carbon\Carbon::parse($lending->due_date);
                                                    $isLate = $returnDate->gt($dueDate);
                                                @endphp
                                                @if($isLate)
                                                    <span class="badge badge-danger" title="Trả muộn {{ $returnDate->diffInDays($dueDate) }} ngày">
                                                        <i class="fas fa-exclamation-triangle"></i> Trả muộn
                                                    </span>
                                                @else
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Đúng hạn
                                                    </span>
                                                @endif
                                            @else
                                                @php
                                                    $dueDate = \Carbon\Carbon::parse($lending->due_date);
                                                    $isOverdue = $dueDate->isPast();
                                                    $daysUntilDue = now()->diffInDays($dueDate, false);
                                                @endphp
                                                @if($isOverdue)
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-exclamation-circle"></i> Quá hạn
                                                    </span>
                                                @elseif($daysUntilDue <= 3)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Sắp đến hạn
                                                    </span>
                                                @else
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Đang mượn
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-3" style="background: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 8px;">
                            <h6 style="color: #1e40af; font-weight: 600;">
                                <i class="fas fa-info-circle"></i> Thống kê
                            </h6>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h3 style="color: #3b82f6; font-weight: 700;">{{ $lendingHistory->count() }}</h3>
                                        <small class="text-muted">Tổng số lần mượn</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h3 style="color: #10b981; font-weight: 700;">{{ $lendingHistory->whereNotNull('return_date')->count() }}</h3>
                                        <small class="text-muted">Đã trả</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h3 style="color: #f59e0b; font-weight: 700;">{{ $lendingHistory->whereNull('return_date')->count() }}</h3>
                                        <small class="text-muted">Đang mượn</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book-open text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">Bạn chưa có lịch sử mượn sách</h5>
                            <p class="text-muted">Hãy tìm kiếm và mượn sách để bắt đầu!</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-search"></i> Tìm kiếm sách
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%) !important;">
                    <i class="fas fa-bookmark"></i> Lịch sử Đặt Giữ Sách ({{ $reservations->count() }})
                </div>
                <div class="card-body">
                    @if($reservations->count() > 0)
                        <div class="list-group">
                            @foreach($reservations as $reservation)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div style="flex: 1;">
                                        <h6 class="mb-1" style="font-weight: 600;">{{ $reservation->bookItem->book->title }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-barcode"></i> ISBN: {{ $reservation->bookItem->book->isbn }}
                                        </small>
                                    </div>
                                    <span class="badge 
                                        @if($reservation->status == 'WAITING') badge-warning
                                        @elseif($reservation->status == 'PROCESSING') badge-info
                                        @else badge-secondary
                                        @endif">
                                        {{ $reservation->status }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
                                    <span>
                                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-barcode"></i> {{ $reservation->bookItem->barcode }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-3 p-2" style="background: #fef3c7; border-radius: 8px;">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> <strong>Ghi chú:</strong>
                                <ul class="mb-0 mt-2" style="font-size: 0.85rem;">
                                    <li><span class="badge badge-warning">WAITING</span> - Đang chờ sách có sẵn</li>
                                    <li><span class="badge badge-info">PROCESSING</span> - Sách đã sẵn sàng để mượn</li>
                                    <li><span class="badge badge-secondary">CANCELED</span> - Đã hủy đặt giữ</li>
                                </ul>
                            </small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bookmark text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Bạn chưa đặt giữ sách nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
                    <i class="fas fa-lightbulb"></i> Lời khuyên
                </div>
                <div class="card-body">
                    <ul class="mb-0" style="line-height: 2;">
                        <li><i class="fas fa-check text-success"></i> Trả sách đúng hạn để tránh phí phạt (5,000 VND/ngày)</li>
                        <li><i class="fas fa-sync-alt text-warning"></i> Bạn có thể gia hạn sách trước khi đến hạn trả</li>
                        <li><i class="fas fa-bookmark text-info"></i> Đặt giữ sách khi sách đang được mượn</li>
                        <li><i class="fas fa-book text-primary"></i> Tối đa 10 cuốn sách cùng lúc (R7)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
