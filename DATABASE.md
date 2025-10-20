# Cấu Trúc Cơ Sở Dữ Liệu - Hệ Thống Quản Lý Thư Viện

## Tổng Quan

Hệ thống quản lý thư viện sử dụng **MySQL** với 13 bảng chính, được thiết kế để quản lý sách, người dùng, mượn trả, đặt chỗ, thông báo và các tính năng mạng xã hội.

---

## 1. Bảng `addresses`

**Mục đích**: Lưu trữ địa chỉ của người dùng (Custom Data Type)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID địa chỉ |
| `street` | VARCHAR(255) | NOT NULL | Tên đường |
| `city` | VARCHAR(255) | NOT NULL | Thành phố |
| `state` | VARCHAR(255) | NULLABLE | Tỉnh/Bang |
| `zip_code` | VARCHAR(10) | NULLABLE | Mã bưu điện |
| `country` | VARCHAR(255) | NULLABLE | Quốc gia |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

---

## 2. Bảng `users`

**Mục đích**: Quản lý thông tin người dùng (Thủ thư và Thành viên)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID người dùng |
| `address_id` | BIGINT | FOREIGN KEY → addresses.id, NULLABLE | Liên kết với địa chỉ |
| `name` | VARCHAR(255) | NOT NULL | Tên người dùng |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | Email |
| `phone` | VARCHAR(255) | NULLABLE | Số điện thoại |
| `password` | VARCHAR(255) | NOT NULL | Mật khẩu (plain text - chưa mã hóa) |
| `role` | ENUM | NOT NULL | Vai trò: `librarian`, `member` |
| `account_status` | ENUM | DEFAULT 'ACTIVE' | Trạng thái: `ACTIVE`, `CLOSED`, `CANCELED`, `BLACKLISTED` |
| `email_verified_at` | TIMESTAMP | NULLABLE | Thời gian xác thực email |
| `remember_token` | VARCHAR(100) | NULLABLE | Token ghi nhớ đăng nhập |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `address_id` → `addresses.id` (1-1, ON DELETE SET NULL)

---

## 3. Bảng `library_cards`

**Mục đích**: Quản lý thẻ thư viện của thành viên (R6)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID thẻ |
| `user_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Người dùng sở hữu thẻ |
| `card_number` | VARCHAR(255) | NOT NULL, UNIQUE | Số thẻ |
| `issued_at` | DATE | NOT NULL | Ngày cấp thẻ |
| `is_active` | BOOLEAN | DEFAULT TRUE | Trạng thái kích hoạt |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `user_id` → `users.id` (1-1, ON DELETE CASCADE)

---

## 4. Bảng `racks`

**Mục đích**: Quản lý kệ sách trong thư viện (R2)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID kệ |
| `rack_number` | VARCHAR(255) | NOT NULL, UNIQUE | Số hiệu kệ |
| `location_identifier` | VARCHAR(255) | NOT NULL | Vị trí kệ |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

---

## 5. Bảng `books`

**Mục đích**: Lưu trữ thông tin sách (R3)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID sách |
| `isbn` | VARCHAR(20) | NOT NULL, UNIQUE | Mã ISBN |
| `title` | VARCHAR(255) | NOT NULL | Tên sách |
| `subject` | VARCHAR(255) | NOT NULL | Chủ đề |
| `publication_date` | DATE | NOT NULL | Ngày xuất bản |
| `cover_image` | VARCHAR(255) | NULLABLE | Đường dẫn ảnh bìa |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

---

## 6. Bảng `book_items`

**Mục đích**: Quản lý từng bản sao vật lý của sách (R4, R2)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID bản sao sách |
| `book_id` | BIGINT | FOREIGN KEY → books.id, NOT NULL | Sách gốc |
| `rack_id` | BIGINT | FOREIGN KEY → racks.id, NULLABLE | Kệ chứa sách |
| `barcode` | VARCHAR(255) | NOT NULL, UNIQUE | Mã vạch |
| `format` | ENUM | DEFAULT 'HARDCOVER' | Định dạng: `HARDCOVER`, `PAPERBACK`, `AUDIOBOOK`, `EBOOK`, `NEWSPAPER`, `MAGAZINE`, `JOURNAL` |
| `status` | ENUM | DEFAULT 'AVAILABLE' | Trạng thái: `AVAILABLE`, `RESERVED`, `LOANED`, `LOST` |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `book_id` → `books.id` (N-1, ON DELETE CASCADE)
- `rack_id` → `racks.id` (N-1, ON DELETE SET NULL)

---

## 7. Bảng `book_lendings`

**Mục đích**: Quản lý việc mượn sách (R10, R8)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID giao dịch mượn |
| `member_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Thành viên mượn |
| `book_item_id` | BIGINT | FOREIGN KEY → book_items.id, NOT NULL | Bản sao sách được mượn |
| `borrowed_date` | DATE | NOT NULL | Ngày mượn |
| `due_date` | DATE | NOT NULL | Ngày hết hạn |
| `return_date` | DATE | NULLABLE | Ngày trả (NULL = chưa trả) |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `member_id` → `users.id` (N-1, ON DELETE CASCADE)
- `book_item_id` → `book_items.id` (N-1, ON DELETE CASCADE)

---

## 8. Bảng `book_reservations`

**Mục đích**: Quản lý đặt chỗ sách (R10, R9, R13)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID đặt chỗ |
| `member_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Thành viên đặt |
| `book_item_id` | BIGINT | FOREIGN KEY → book_items.id, NOT NULL | Bản sao sách được đặt |
| `reservation_date` | DATE | NOT NULL | Ngày đặt chỗ |
| `status` | ENUM | DEFAULT 'WAITING' | Trạng thái: `WAITING`, `PROCESSING`, `CANCELED` |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `member_id` → `users.id` (N-1, ON DELETE CASCADE)
- `book_item_id` → `book_items.id` (N-1, ON DELETE CASCADE)

---

## 9. Bảng `notifications`

**Mục đích**: Quản lý thông báo gửi cho người dùng (R12)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID thông báo |
| `user_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Người nhận |
| `lending_id` | BIGINT | FOREIGN KEY → book_lendings.id, NULLABLE | Giao dịch mượn liên quan |
| `subject` | VARCHAR(255) | NOT NULL | Tiêu đề |
| `content` | TEXT | NOT NULL | Nội dung |
| `type` | ENUM | NOT NULL | Loại: `EMAIL`, `POSTAL` |
| `sent_at` | TIMESTAMP | NULLABLE | Thời gian gửi |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `user_id` → `users.id` (N-1, ON DELETE CASCADE)
- `lending_id` → `book_lendings.id` (N-1, ON DELETE CASCADE)

---

## 10. Bảng `posts`

**Mục đích**: Quản lý bài viết về sách (Tính năng mạng xã hội)

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID bài viết |
| `user_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Người đăng |
| `book_id` | BIGINT | FOREIGN KEY → books.id, NOT NULL | Sách được viết về |
| `title` | VARCHAR(255) | NOT NULL | Tiêu đề |
| `content` | TEXT | NOT NULL | Nội dung |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `user_id` → `users.id` (N-1, ON DELETE CASCADE)
- `book_id` → `books.id` (N-1, ON DELETE CASCADE)

---

## 11. Bảng `comments`

**Mục đích**: Quản lý bình luận trên bài viết

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID bình luận |
| `post_id` | BIGINT | FOREIGN KEY → posts.id, NOT NULL | Bài viết được bình luận |
| `user_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Người bình luận |
| `content` | TEXT | NOT NULL | Nội dung |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `post_id` → `posts.id` (N-1, ON DELETE CASCADE)
- `user_id` → `users.id` (N-1, ON DELETE CASCADE)

---

## 12. Bảng `likes`

**Mục đích**: Quản lý lượt thích bài viết

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID like |
| `post_id` | BIGINT | FOREIGN KEY → posts.id, NOT NULL | Bài viết được thích |
| `user_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Người thích |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Ràng Buộc Đặc Biệt**:
- UNIQUE(`post_id`, `user_id`) - Một người chỉ thích một bài viết một lần

**Quan hệ**:
- `post_id` → `posts.id` (N-1, ON DELETE CASCADE)
- `user_id` → `users.id` (N-1, ON DELETE CASCADE)

---

## 13. Bảng `shares`

**Mục đích**: Quản lý lượt chia sẻ bài viết

| Cột | Kiểu Dữ Liệu | Ràng Buộc | Mô Tả |
|-----|-------------|-----------|-------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID chia sẻ |
| `post_id` | BIGINT | FOREIGN KEY → posts.id, NOT NULL | Bài viết được chia sẻ |
| `user_id` | BIGINT | FOREIGN KEY → users.id, NOT NULL | Người chia sẻ |
| `created_at` | TIMESTAMP | | Ngày tạo |
| `updated_at` | TIMESTAMP | | Ngày cập nhật |

**Quan hệ**:
- `post_id` → `posts.id` (N-1, ON DELETE CASCADE)
- `user_id` → `users.id` (N-1, ON DELETE CASCADE)

---

## Sơ Đồ Quan Hệ Tổng Quan

```
addresses (1) ←→ (0..1) users
users (1) ←→ (0..1) library_cards
users (1) ←→ (N) book_lendings
users (1) ←→ (N) book_reservations
users (1) ←→ (N) notifications
users (1) ←→ (N) posts
users (1) ←→ (N) comments
users (1) ←→ (N) likes
users (1) ←→ (N) shares

books (1) ←→ (N) book_items
books (1) ←→ (N) posts

racks (1) ←→ (N) book_items

book_items (1) ←→ (N) book_lendings
book_items (1) ←→ (N) book_reservations

book_lendings (1) ←→ (N) notifications

posts (1) ←→ (N) comments
posts (1) ←→ (N) likes
posts (1) ←→ (N) shares
```

---

## Chú Ý Về Thiết Kế

### 1. Bảo Mật
- **Mật khẩu**: Hiện tại lưu dạng plain text (cần mã hóa bằng bcrypt trong production)

### 2. Quy Tắc Nghiệp Vụ
- Một thành viên có thể mượn tối đa **10 cuốn sách** cùng lúc (kiểm tra trong code logic)
- Một sách có thể có nhiều bản sao vật lý (book_items)
- Trạng thái sách tự động cập nhật khi mượn/trả/đặt chỗ

### 3. Xóa Dữ Liệu
- **CASCADE**: Xóa user → xóa luôn library_cards, lendings, reservations, posts, comments, likes, shares
- **SET NULL**: Xóa address → giữ user nhưng set address_id = NULL
- **SET NULL**: Xóa rack → giữ book_items nhưng set rack_id = NULL

### 4. Tính Năng Mạng Xã Hội
- Hệ thống hỗ trợ đăng bài, bình luận, thích, chia sẻ về sách
- Mỗi bài viết phải liên kết với một cuốn sách cụ thể

---

## Migration Files

1. `2025_10_09_021600_create_lms_schema.php` - Tạo 9 bảng chính
2. `2025_10_09_160144_create_posts_comments_likes_shares_tables.php` - Tạo 4 bảng social
3. `2025_10_14_140701_add_cover_image_to_books_table.php` - Thêm cột cover_image

---

## Cách Chạy Migration

```bash
# Chạy tất cả migrations
php artisan migrate

# Reset và chạy lại từ đầu
php artisan migrate:fresh

# Chạy với dữ liệu mẫu
php artisan migrate:fresh --seed
```

---

## Sơ Đồ Phân Rã Chức Năng (Function Decomposition)

### 1. Authentication & Authorization
```
auth/
├── login (LoginController)
├── logout (LoginController)
├── register (RegisterController)
├── verify email (VerificationController)
├── forgot password (ForgotPasswordController)
├── reset password (ResetPasswordController)
└── confirm password (ConfirmPasswordController)

middleware/
├── AdminMiddleware - Xác thực quyền quản trị viên
└── LibrarianMiddleware - Xác thực quyền thủ thư
```

### 2. Library Core Functions (LibraryController)

#### 2.1 Dashboard
```
dashboard()
└── Hiển thị trang chủ với thông tin người dùng
```

#### 2.2 Search Catalog (R14)
```
searchCatalog(Request)
├── Tìm kiếm theo title (mặc định)
├── Tìm kiếm theo ISBN
└── Tìm kiếm theo subject
    └── Hiển thị kết quả với book_items và rack
```

#### 2.3 Member Functions

**Reserve Book (R13)**
```
reserveBook(Request, BookItem)
├── Kiểm tra quyền member
├── Kiểm tra sách có thể đặt giữ
├── Tạo reservation (status: WAITING)
├── Cập nhật book_item.status = RESERVED
└── Thông báo thành công/thất bại
```

**Renew Book (R11)**
```
renewBook(BookLending)
├── Kiểm tra quyền sở hữu
├── Kiểm tra sách chưa trả
├── Gia hạn due_date thêm 15 ngày
└── Thông báo ngày trả mới
```

#### 2.4 Librarian Functions

**Issue Book (Cấp phát sách)**
```
issueBook(Request)
├── Kiểm tra quyền librarian
├── Validate member (role, account_status = ACTIVE)
├── Validate book_item (status = AVAILABLE)
├── Kiểm tra hạn mức mượn (max 10 cuốn) - R7
├── BEGIN TRANSACTION
│   ├── Tạo book_lending (due_date = +15 ngày) - R8
│   ├── Cập nhật book_item.status = LOANED
│   └── Cập nhật reservation.status = PROCESSING (nếu có)
└── COMMIT/ROLLBACK
```

**Return Book (Trả sách)**
```
returnBook(Request)
├── Kiểm tra quyền librarian
├── Kiểm tra lending hợp lệ
├── BEGIN TRANSACTION
│   ├── Cập nhật lending.return_date = now()
│   ├── Cập nhật book_item.status = AVAILABLE
│   ├── Kiểm tra overdue - R12
│   │   ├── Tính số ngày quá hạn
│   │   ├── Tính phí phạt (5000 VND/ngày)
│   │   └── Tạo notification phí phạt
│   └── Xử lý reservation tiếp theo
│       ├── Tìm reservation WAITING cũ nhất
│       ├── Cập nhật book_item.status = RESERVED
│       └── Tạo notification cho member
└── COMMIT/ROLLBACK
```

### 3. Book Management (BookController) - CRUD

```
index(Request)
├── Search (title, isbn, subject)
└── Paginate (10/page)

create()
└── Show form tạo sách mới

store(Request)
├── Validate (isbn unique, title, subject, publication_date)
├── Upload cover_image → public/images/books/
├── Create book
└── Redirect books.index

show(Book)
└── Hiển thị chi tiết sách

edit(Book)
└── Show form sửa sách

update(Request, Book)
├── Validate (isbn unique except current)
├── Handle cover_image upload/delete old
├── Update book
└── Redirect books.index

destroy(Book)
├── Delete cover_image file
├── Delete book (cascade delete book_items)
└── Redirect books.index
```

### 4. Member Management (MemberController) - CRUD

```
index()
└── Hiển thị danh sách members

create()
└── Show form tạo member

store(Request)
├── Validate (name, email unique, phone, password confirmed)
├── Create address (nếu có)
├── Create member với address_id
└── Redirect members.index

show(Member)
└── Hiển thị chi tiết member với address

edit(Member)
└── Show form sửa member với address

update(Request, Member)
├── Validate (email unique except current, password confirmed)
├── Update/Create address
├── Update member (name, email, phone, new_password)
└── Redirect members.index

destroy(Member)
└── Delete member (cascade delete lendings, reservations...)

profile()
├── Kiểm tra quyền member
├── Load currentLendings
├── Load activeReservations
├── Load libraryCard
├── Load address
└── Show members.profile

lendingHistory()
├── Kiểm tra quyền member
├── Load lendingHistory
├── Load reservations với book, author
└── Show members.lending-history
```

### 5. Lending Slip Management (PhieuMuonController) - CRUD

```
index()
└── Hiển thị tất cả phiếu mượn với bookItem, book, member

create()
├── Load available book_items
├── Load members
└── Show form tạo phiếu mượn

store(Request)
├── Validate (book_item_id, member_id, borrowed_date, due_date)
├── Kiểm tra book_item.status = AVAILABLE
├── Create phieumuon
├── Cập nhật book_item.status = LOANED
└── Redirect phieumuon.index

show(PhieuMuon)
└── Hiển thị chi tiết phiếu mượn

edit(PhieuMuon)
└── Show form sửa phiếu mượn

update(Request, PhieuMuon)
├── Validate dates
├── Update phieumuon
├── Update book_item.status based on return_date
│   ├── Nếu có return_date → AVAILABLE
│   └── Nếu không có return_date → LOANED
└── Redirect phieumuon.index

destroy(PhieuMuon)
├── Cập nhật book_item.status = AVAILABLE (nếu chưa trả)
├── Delete phieumuon
└── Redirect phieumuon.index
```

### 6. Social Features (PostController)

```
index()
├── Load posts với user, book, comments, likes, shares
└── Paginate (10/page)

create()
├── Load books
└── Show form tạo bài viết

store(Request)
├── Validate (book_id, title, content)
├── Create post (user_id = Auth::id())
└── Redirect posts.index

show(Post)
└── Load post với user, book, comments.user, likes, shares

destroy(Post)
├── Kiểm tra quyền (owner hoặc librarian)
├── Delete post (cascade delete comments, likes, shares)
└── Redirect posts.index

like(Post)
├── Kiểm tra existing like
├── Nếu đã like → Delete like (unlike)
└── Nếu chưa like → Create like

share(Post)
├── Create share record
└── Redirect back

comment(Request, Post)
├── Validate content
├── Create comment (user_id = Auth::id())
└── Redirect back
```

### 7. Admin Data Operations (DataController)

#### 7.1 Book Operations
```
createBook(Request) - Add book INCLUDE Add book item
├── Create book (isbn, title, subject, publication_date)
└── Loop num_copies
    └── Create book_item (barcode, format, rack_id, status=AVAILABLE)

deleteBook(Book) - Remove book INCLUDE Remove book item
└── Delete book (cascade delete book_items)
```

#### 7.2 Member Operations
```
registerMember(Request) - Register new account INCLUDE Issue library card
├── Create address
├── Create user (role=member, account_status=ACTIVE)
│   └── Hash password
└── Create library_card (card_number, issued_at, is_active=true)

cancelMembership(User)
├── Kiểm tra role = member
├── Kiểm tra không còn sách chưa trả
├── Update user.account_status = CANCELED
└── Update library_card.is_active = false
```

### 8. Routes Structure

```
Public Routes:
├── GET /login
├── POST /login
├── POST /logout
├── GET /register
└── POST /register

Authenticated Routes (middleware: auth):
├── GET / → dashboard
├── GET /search → searchCatalog
│
├── Posts/Social (All authenticated users)
│   ├── Resource: /posts (index, create, store, show, destroy)
│   ├── POST /posts/{post}/like
│   ├── POST /posts/{post}/share
│   └── POST /posts/{post}/comment
│
├── Member Functions (All authenticated members)
│   ├── POST /books/{bookItem}/reserve
│   ├── POST /lendings/{lending}/renew
│   ├── GET /member/profile
│   └── GET /member/lending-history
│
└── Librarian Functions (middleware: librarian)
    ├── Business Operations
    │   ├── POST /books/issue
    │   └── POST /lendings/return
    │
    ├── Admin CRUD (prefix: /admin)
    │   ├── POST /admin/books
    │   ├── DELETE /admin/books/{book}
    │   ├── POST /admin/members/register
    │   └── POST /admin/members/{user}/cancel
    │
    ├── Resource CRUD (except index, show)
    │   ├── /books → create, store, edit, update, destroy
    │   ├── /members → create, store, edit, update, destroy
    │   └── /phieumuon → full resource routes
    │
    └── View Routes (All authenticated users)
        ├── GET /books → index
        ├── GET /books/{book} → show
        ├── GET /members → index
        └── GET /members/{member} → show
```

---

## Business Rules Implementation

### R2: Quản Lý Kệ Sách
- Bảng `racks` lưu vị trí kệ
- `book_items.rack_id` liên kết sách với kệ

### R3-R4: Quản Lý Sách và Bản Sao
- `books`: Thông tin metadata (ISBN, title, subject)
- `book_items`: Bản sao vật lý (barcode, format, status, rack)

### R6: Cấp Thẻ Thư Viện
- Tự động tạo `library_card` khi đăng ký member mới
- Card number format: `LMS00001`, `LMS00002`, etc.

### R7: Hạn Mức Mượn
- Max 10 cuốn/member (kiểm tra trong `LibraryController::issueBook`)

### R8: Thời Hạn Mượn
- Default: 15 ngày (`due_date = borrowed_date + 15 days`)

### R9: Giới Hạn Đặt Giữ
- Max 5 đặt giữ/member (chưa implement trong code)

### R10: Hàng Đợi Đặt Giữ
- Sắp xếp theo `reservation_date` (FIFO)
- Status: WAITING → PROCESSING → Complete

### R11: Gia Hạn Sách
- Thêm 15 ngày vào `due_date` hiện tại
- Chỉ gia hạn được nếu chưa trả

### R12: Phạt Quá Hạn
- 5000 VND/ngày
- Tự động tạo notification khi trả sách quá hạn

### R13: Đặt Giữ Sách
- Chỉ đặt giữ được nếu sách đang LOANED
- Tự động thông báo khi sách available

### R14: Tìm Kiếm
- Tìm theo: title, ISBN, subject
- Hỗ trợ LIKE query với wildcard
