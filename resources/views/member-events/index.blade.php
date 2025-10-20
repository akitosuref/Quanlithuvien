@extends('layouts.app')

@section('title', 'Sự Kiện Thư Viện')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Sự Kiện Thư Viện</h1>
        </div>

        <div class="row">
            @forelse ($events as $event)
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">{{ $event->title }}</h5>
                                <span class="badge bg-primary">{{ $event->event_type }}</span>
                            </div>
                            
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-calendar"></i> {{ $event->event_date->format('d/m/Y H:i') }}
                                @if($event->location)
                                    <br><i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                                @endif
                            </p>

                            <p class="card-text">{{ Str::limit($event->description, 150) }}</p>

                            @if($event->max_participants)
                                <p class="text-muted small">
                                    <i class="fas fa-users"></i> 
                                    Đã đăng ký: {{ $event->attendees->count() }} / {{ $event->max_participants }}
                                </p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Tổ chức bởi: {{ $event->creator->name }}
                                </small>
                                <a href="{{ route('member-events.show', $event) }}" class="btn btn-sm btn-primary">
                                    Chi Tiết <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Hiện tại không có sự kiện nào sắp diễn ra.
                    </div>
                </div>
            @endforelse
        </div>

        @if($events->hasPages())
            <div class="mt-3">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection
