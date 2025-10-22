# Hệ thống Quản lý Thư viện

Đây là một dự án quản lý thư viện được xây dựng bằng Laravel. Ứng dụng cho phép quản lý sách, thành viên, phiếu mượn sách, đặt giữ sách và mạng xã hội (posts, comments, likes, shares).

## Tính năng

-   **Quản lý Sách:** Thêm, xem, sửa và xóa sách trong thư viện.
-   **Quản lý Thành viên:** Thêm, xem, sửa và xóa thông tin thành viên.
-   **Quản lý Phiếu mượn:** Tạo phiếu mượn sách cho thành viên, cập nhật trạng thái trả sách và xem lịch sử mượn.
-   **Đặt giữ sách:** Thành viên có thể đặt giữ sách.
-   **Mạng xã hội:** Tạo bài viết về sách, comment, like và share.
-   **Xác thực & Phân quyền:** Hệ thống đăng ký, đăng nhập với phân quyền Librarian và Member.

## Cấu trúc Cơ sở dữ liệu

Dự án sử dụng các bảng chính sau:

-   `addresses`: Lưu trữ địa chỉ.
-   `users`: Lưu trữ thông tin người dùng.
    -   `role`: 'librarian' hoặc 'member'
    -   `account_status`: 'ACTIVE', 'CLOSED', 'CANCELED', 'BLACKLISTED'
-   `library_cards`: Thẻ thư viện cho thành viên.
-   `racks`: Kệ sách.
-   `books`: Lưu trữ thông tin về sách (ISBN, title, subject, publication_date).
-   `book_items`: Các bản sao vật lý của sách (barcode, format, status).
-   `book_lendings`: Lưu trữ thông tin về các lần mượn sách.
-   `book_reservations`: Lưu trữ thông tin đặt giữ sách.
-   `notifications`: Thông báo cho người dùng.
-   `posts`: Bài viết về sách.
-   `comments`: Bình luận cho bài viết.
-   `likes`: Like cho bài viết.
-   `shares`: Chia sẻ bài viết.

## Hướng dẫn Cài đặt và Chạy dự án

1.  **Clone repository:**

    ```bash
    git clone <URL_REPOSITORY>
    cd <TEN_THU_MUC>
    ```

2.  **Cài đặt dependencies:**

    ```bash
    composer install
    npm install
    ```

3.  **Cấu hình môi trường:**

    -   Sao chép tệp `.env.example` thành `.env`:
        ```bash
        copy .env.example .env
        ```
    -   Cấu hình thông tin kết nối cơ sở dữ liệu trong tệp `.env`.

4.  **Tạo khóa ứng dụng:**

    ```bash
    php artisan key:generate
    ```

5.  **Chạy migrations và seeders:**
    Lệnh này sẽ tạo cấu trúc cơ sở dữ liệu và thêm dữ liệu mẫu, bao gồm một tài khoản admin và một tài khoản user.

    ```bash
    php artisan migrate:fresh --seed
    ```

    **Tài khoản mẫu:**

    -   **Librarian (Thủ thư):**
        -   Email: `librarian@mail.com`
        -   Password: `password`
    -   **Member (Thành viên):**
        -   Email: `member@mail.com`
        -   Password: `password`

6.  **Khởi chạy dự án:**
    Bạn cần chạy cả hai lệnh sau trong hai cửa sổ terminal riêng biệt.

    -   **Khởi chạy máy chủ Laravel:**
        ```bash
        php artisan serve
        ```
    -   **Biên dịch tài sản front-end (Vite):**
        ```bash
        npm run dev
        ```

    Ứng dụng sẽ chạy tại địa chỉ `http://127.0.0.1:8000`.

## Phân Chia Công Việc

### akitosuref (Vương Quang Quý)
-   Khởi tạo và cấu hình dự án Laravel
-   Thiết kế và triển khai cơ sở dữ liệu
-   Xây dựng hệ thống xác thực và phân quyền (Librarian/Member)
-   Phát triển module quản lý thành viên với hệ thống địa chỉ chi tiết
-   Triển khai hệ thống mật khẩu plain text cho admin với backward compatibility
-   Phát triển module quản lý phiếu mượn sách
-   Xây dựng tính năng mạng xã hội (Posts, Comments, Likes, Shares)
-   Thiết kế lại giao diện quản lý thành viên (create/edit/show) với layout 2 cột
-   Tích hợp hệ thống địa chỉ (Address model) vào quản lý thành viên
-   Sửa lỗi critical: loại bỏ tham chiếu author không tồn tại
-   Thêm middleware bảo vệ routes cho Librarian
-   Quản lý repository và Git operations

### banhgatongonngon (Nguyễn Thanh Nam)
-   Phát triển module quản lý sách (CRUD)
-   Xây dựng tính năng tìm kiếm và phân trang sách
-   Phát triển hệ thống upload và hiển thị ảnh bìa sách
-   Cải thiện giao diện đăng ký và UX xác thực
-   Tối ưu hóa cấu trúc database và models
-   Loại bỏ module Authors khỏi hệ thống
-   Hỗ trợ code review và debug

## Các Tuyến đường (Routes) chính

### Routes Công khai (Public)
-   `GET /login`: Đăng nhập
-   `POST /login`: Xử lý đăng nhập
-   `GET /register`: Đăng ký
-   `POST /register`: Xử lý đăng ký
-   `POST /logout`: Đăng xuất

### Routes cho Thành viên (Member & Librarian)
-   `GET /`: Dashboard
-   `GET /search`: Tìm kiếm sách (theo tiêu đề, ISBN, chủ đề)
-   **Books (Xem):**
    -   `GET /books`: Danh sách sách (có tìm kiếm và phân trang)
    -   `GET /books/{book}`: Chi tiết sách
-   **Members (Xem):**
    -   `GET /members`: Danh sách thành viên
    -   `GET /members/{member}`: Chi tiết thành viên
-   **Thành viên:**
    -   `GET /member/profile`: Trang cá nhân
    -   `GET /member/lending-history`: Lịch sử mượn sách
    -   `POST /books/{bookItem}/reserve`: Đặt giữ sách
    -   `POST /lendings/{lending}/renew`: Gia hạn sách
-   **Posts (Mạng xã hội):**
    -   `GET /posts`: Danh sách bài viết
    -   `GET /posts/create`: Tạo bài viết mới
    -   `POST /posts`: Lưu bài viết
    -   `GET /posts/{post}`: Chi tiết bài viết
    -   `POST /posts/{post}/comment`: Bình luận
    -   `POST /posts/{post}/like`: Like bài viết
    -   `POST /posts/{post}/share`: Chia sẻ bài viết

### Routes cho Thủ thư (Librarian Only - Protected by LibrarianMiddleware)
-   **Nghiệp vụ:**
    -   `POST /books/issue`: Cấp phát sách (mượn sách)
    -   `POST /lendings/return`: Trả sách
-   **CRUD Sách:**
    -   `GET /books/create`: Form tạo sách
    -   `POST /books`: Tạo sách mới
    -   `GET /books/{book}/edit`: Form sửa sách
    -   `PUT /books/{book}`: Cập nhật sách
    -   `DELETE /books/{book}`: Xóa sách
-   **CRUD Thành viên:**
    -   `GET /members/create`: Form tạo thành viên
    -   `POST /members`: Tạo thành viên mới
    -   `GET /members/{member}/edit`: Form sửa thành viên
    -   `PUT /members/{member}`: Cập nhật thành viên
    -   `DELETE /members/{member}`: Xóa thành viên
-   **Phiếu mượn:**
    -   `GET /phieumuon`: Danh sách phiếu mượn
    -   Full CRUD resource routes cho phiếu mượn
-   **Admin Operations:**
    -   `POST /admin/books`: Tạo sách với nhiều bản sao
    -   `DELETE /admin/books/{book}`: Xóa sách và tất cả bản sao
    -   `POST /admin/members/register`: Đăng ký thành viên mới
    -   `POST /admin/members/{user}/cancel`: Hủy tư cách thành viên

## Bảo mật và Phân quyền

### Middleware
-   **auth**: Yêu cầu đăng nhập cho tất cả routes bên trong `Route::middleware('auth')`
-   **librarian**: Middleware tùy chỉnh bảo vệ routes chỉ dành cho Thủ thư
    -   Kiểm tra `auth()->user()->isLibrarian()`
    -   Redirect về dashboard nếu không có quyền

### Phân quyền
-   **Librarian (role: 'librarian'):**
    -   Toàn quyền CRUD sách, thành viên, phiếu mượn
    -   Cấp phát sách, trả sách
    -   Xem tất cả thông tin thành viên (bao gồm mật khẩu)
    -   Tạo và chỉnh sửa mật khẩu thành viên
-   **Member (role: 'member'):**
    -   Xem danh sách và chi tiết sách
    -   Đặt giữ sách, gia hạn sách
    -   Xem profile và lịch sử mượn của chính mình
    -   Tạo và tương tác với posts (comment, like, share)

### ⚠️ Lưu ý về Bảo mật
-   **Mật khẩu Plain Text:** Hệ thống hiện lưu mật khẩu dưới dạng plain text (không mã hóa) để admin có thể xem và quản lý mật khẩu thành viên. 
-   **Chỉ dùng cho môi trường học tập/demo:** Cách lưu trữ này **KHÔNG AN TOÀN** cho môi trường production thực tế.
-   **Backward Compatible:** Hệ thống vẫn hỗ trợ đăng nhập với mật khẩu đã hash từ trước đó.

## Quản lý Thành viên

### Tạo Thành viên Mới (Admin)
-   Thông tin cá nhân: Tên, Email, Điện thoại
-   Mật khẩu: Admin đặt mật khẩu ban đầu cho thành viên (hiển thị dạng text)
-   Địa chỉ: Đường, Thành phố, Tỉnh/Bang, Mã bưu điện, Quốc gia (tất cả các trường riêng biệt)

### Xem Thông tin Thành viên (Admin)
-   Hiển thị đầy đủ thông tin cá nhân
-   Hiển thị mật khẩu dạng plain text
-   Hiển thị địa chỉ chi tiết (street, city, state, zip_code, country)
-   Trạng thái tài khoản với badge màu sắc

### Chỉnh sửa Thành viên (Admin)
-   Cập nhật thông tin cá nhân
-   Cập nhật địa chỉ (các trường riêng biệt)
-   Thay đổi mật khẩu (tùy chọn - hiển thị dạng text)
