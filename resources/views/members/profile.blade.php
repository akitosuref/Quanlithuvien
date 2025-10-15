@extends('layouts.app')

@section('content')
<div class="container-fluid fade-in">
    <div class="card mb-4">
        <div class="card-body" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="mb-2" style="font-size: 2rem; font-weight: 700;">
                        <i class="fas fa-user-circle"></i> Thông tin Cá nhân
                    </h2>
                    <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                        Xem và quản lý thông tin tài khoản của bạn
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

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
                    <i class="fas fa-id-card"></i> Thông tin Tài khoản
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="font-weight: 600; width: 40%;"><i class="fas fa-user text-primary"></i> Họ và Tên:</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fas fa-envelope text-primary"></i> Email:</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fas fa-phone text-primary"></i> Số điện thoại:</td>
                                <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fas fa-map-marker-alt text-primary"></i> Địa chỉ:</td>
                                <td>
                                    @if($address)
                                        {{ $address->street }}, {{ $address->city }}
                                        @if($address->state), {{ $address->state }}@endif
                                        @if($address->zip_code), {{ $address->zip_code }}@endif
                                        @if($address->country), {{ $address->country }}@endif
                                    @else
                                        Chưa cập nhật
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fas fa-toggle-on text-primary"></i> Trạng thái:</td>
                                <td>
                                    <span class="badge 
                                        @if($user->account_status == 'ACTIVE') badge-success
                                        @elseif($user->account_status == 'CLOSED') badge-secondary
                                        @elseif($user->account_status == 'CANCELED') badge-warning
                                        @else badge-danger
                                        @endif">
                                        {{ $user->account_status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if($libraryCard)
            <div class="card mt-4">
                <div class="card-header" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;">
                    <i class="fas fa-credit-card"></i> Thẻ Thư viện
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="font-weight: 600; width: 40%;"><i class="fas fa-hashtag text-primary"></i> Số thẻ:</td>
                                <td><code style="font-size: 1.1rem;">{{ $libraryCard->card_number }}</code></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fas fa-calendar text-primary"></i> Ngày cấp:</td>
                                <td>{{ \Carbon\Carbon::parse($libraryCard->issued_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fas fa-check-circle text-primary"></i> Trạng thái thẻ:</td>
                                <td>
                                    @if($libraryCard->is_active)
                                        <span class="badge badge-success">Đang hoạt động</span>
                                    @else
                                        <span class="badge badge-danger">Không hoạt động</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;">
                    <i class="fas fa-book-open"></i> Sách Đang Mượn ({{ $currentLendings->count() }} / 10)
                </div>
                <div class="card-body">
                    @if($currentLendings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tên sách</th>
                                        <th>Ngày mượn</th>
                                        <th>Hạn trả</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentLendings as $lending)
                                    <tr>
                                        <td>
                                            <strong>{{ $lending->bookItem->book->title }}</strong><br>
                                            <small class="text-muted">ISBN: {{ $lending->bookItem->book->isbn }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($lending->borrowed_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if(\Carbon\Carbon::parse($lending->due_date)->isPast()) badge-danger
                                                @elseif(\Carbon\Carbon::parse($lending->due_date)->diffInDays(now()) <= 3) badge-warning
                                                @else badge-success
                                                @endif">
                                                {{ \Carbon\Carbon::parse($lending->due_date)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('member.renew', $lending) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" 
                                                    @if(\Carbon\Carbon::parse($lending->due_date)->isPast()) disabled title="Không thể gia hạn sách quá hạn" @endif>
                                                    <i class="fas fa-sync-alt"></i> Gia hạn
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Bạn chưa mượn sách nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%) !important;">
                    <i class="fas fa-bookmark"></i> Sách Đã Đặt Giữ ({{ $activeReservations->count() }})
                </div>
                <div class="card-body">
                    @if($activeReservations->count() > 0)
                        <div class="list-group">
                            @foreach($activeReservations as $reservation)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $reservation->bookItem->book->title }}</h6>
                                        <small class="text-muted">{{ $reservation->bookItem->book->author->name }}</small><br>
                                        <small class="text-muted">Ngày đặt: {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</small>
                                    </div>
                                    <span class="badge 
                                        @if($reservation->status == 'WAITING') badge-warning
                                        @elseif($reservation->status == 'PROCESSING') badge-info
                                        @else badge-secondary
                                        @endif">
                                        {{ $reservation->status }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bookmark text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Bạn chưa đặt giữ sách nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
