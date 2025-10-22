@extends('layouts.app')

@section('content')
<div class="container fade-in" style="max-width: 800px;">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card" style="border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; border: none;">
            <h3 class="mb-0" style="color: white; font-weight: 800; font-size: 1.75rem;">
                <i class="fas fa-pen-fancy"></i> Viết cảm nhận về Sách
            </h3>
            <p class="mb-0 mt-2" style="color: rgba(255,255,255,0.9); font-size: 0.95rem;">Chia sẻ những suy nghĩ và cảm xúc của bạn về cuốn sách</p>
        </div>
        <div class="card-body" style="padding: 2.5rem;">
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="book_id" class="form-label" style="font-weight: 700; color: #1f2937; font-size: 1rem; margin-bottom: 10px;">
                        <i class="fas fa-book" style="color: #667eea;"></i> Chọn Sách <span class="text-danger">*</span>
                    </label>
                    <select name="book_id" id="book_id" 
                            class="form-control @error('book_id') is-invalid @enderror" 
                            style="border-radius: 12px; border: 2px solid #e5e7eb; padding: 12px 16px; font-size: 0.98rem; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                            required>
                        <option value="">-- Chọn sách để viết cảm nhận --</option>
                        @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} (ISBN: {{ $book->isbn }})
                        </option>
                        @endforeach
                    </select>
                    @error('book_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="form-label" style="font-weight: 700; color: #1f2937; font-size: 1rem; margin-bottom: 10px;">
                        <i class="fas fa-heading" style="color: #667eea;"></i> Tiêu đề <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           placeholder="Nhập tiêu đề thu hút cho bài viết của bạn..." 
                           value="{{ old('title') }}" 
                           style="border-radius: 12px; border: 2px solid #e5e7eb; padding: 12px 16px; font-size: 0.98rem; transition: all 0.3s;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label" style="font-weight: 700; color: #1f2937; font-size: 1rem; margin-bottom: 10px;">
                        <i class="fas fa-align-left" style="color: #667eea;"></i> Nội dung <span class="text-danger">*</span>
                    </label>
                    <textarea name="content" id="content" rows="10" 
                              class="form-control @error('content') is-invalid @enderror" 
                              placeholder="Chia sẻ cảm nhận sâu sắc của bạn về cuốn sách này... Bạn nghĩ gì về nội dung, nhân vật, thông điệp hay phong cách viết?"
                              style="border-radius: 12px; border: 2px solid #e5e7eb; padding: 14px 16px; font-size: 0.98rem; line-height: 1.7; resize: vertical; transition: all 0.3s;"
                              onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                              onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted" style="display: block; margin-top: 8px;">
                        <i class="fas fa-info-circle"></i> Hãy chia sẻ những suy nghĩ chân thật của bạn để truyền cảm hứng cho độc giả khác
                    </small>
                </div>

                <div class="d-flex gap-3 pt-3">
                    <button type="submit" class="btn btn-primary btn-lg" 
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px; padding: 12px 32px; font-weight: 700; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s;">
                        <i class="fas fa-paper-plane"></i> Đăng bài viết
                    </button>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-lg" 
                       style="border-radius: 25px; padding: 12px 32px; font-weight: 700; border: 2px solid #6b7280; background: transparent; color: #6b7280; transition: all 0.3s;">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4 p-4" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%); border-radius: 16px; border-left: 4px solid #667eea;">
        <h6 style="color: #1f2937; font-weight: 700; margin-bottom: 12px;">
            <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Gợi ý viết bài hay
        </h6>
        <ul style="color: #4b5563; margin-bottom: 0; padding-left: 20px; line-height: 1.8;">
            <li>Chia sẻ cảm xúc và suy nghĩ cá nhân của bạn về cuốn sách</li>
            <li>Đề cập đến những điểm đặc biệt hoặc bất ngờ trong nội dung</li>
            <li>Giải thích tại sao bạn giới thiệu (hoặc không giới thiệu) cuốn sách này</li>
            <li>Tránh tiết lộ chi tiết quan trọng để không làm mất đi trải nghiệm của người khác</li>
        </ul>
    </div>
</div>

<style>
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5) !important;
}

.btn-secondary:hover {
    background: #6b7280 !important;
    color: white !important;
}
</style>
@endsection
