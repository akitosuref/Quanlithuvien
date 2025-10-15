# Hệ thống Quản lý Thư viện

Đây là một dự án quản lý thư viện được xây dựng bằng Laravel. Ứng dụng cho phép quản lý sách, tác giả, thành viên, phiếu mượn sách, đặt giữ sách và mạng xã hội (posts, comments, likes, shares).

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
-   Phát triển module quản lý thành viên
-   Phát triển module quản lý phiếu mượn sách
-   Xây dựng tính năng mạng xã hội (Posts, Comments, Likes, Shares)
-   Quản lý repository và Git operations

### banhgatongonngon
-   Phát triển module quản lý sách (CRUD)
-   Xây dựng tính năng tìm kiếm và phân trang sách
-   Phát triển hệ thống upload và hiển thị ảnh bìa sách
-   Cải thiện giao diện đăng ký và UX xác thực
-   Tối ưu hóa cấu trúc database và models
-   Hỗ trợ code review và debug

## Các Tuyến đường (Routes) chính

-   `GET /`: Dashboard
-   **Books:**
    -   `GET /books`: Danh sách sách (có tìm kiếm và phân trang)
    -   `POST /books`: Tạo sách mới
    -   `GET /books/{book}`: Chi tiết sách
    -   `PUT /books/{book}`: Cập nhật sách
    -   `DELETE /books/{book}`: Xóa sách
    -   `POST /books/issue`: Mượn sách
    -   `POST /books/{bookItem}/reserve`: Đặt giữ sách
-   **Members:**
    -   `GET /members`: Danh sách thành viên
    -   Resource routes cho thành viên
    -   `GET /member/profile`: Trang cá nhân
    -   `GET /member/lending-history`: Lịch sử mượn sách
-   **Phiếu mượn:**
    -   `GET /phieumuon`: Danh sách phiếu mượn
    -   Resource routes cho phiếu mượn
    -   `POST /lendings/return`: Trả sách
    -   `POST /lendings/{lending}/renew`: Gia hạn
-   **Posts:**
    -   `GET /posts`: Danh sách bài viết
    -   Resource routes cho bài viết
    -   `POST /posts/{post}/comment`: Bình luận
    -   `POST /posts/{post}/like`: Like bài viết
    -   `POST /posts/{post}/share`: Chia sẻ bài viết
-   **Admin:**
    -   `POST /admin/books`: Tạo sách (admin)
    -   `DELETE /admin/books/{book}`: Xóa sách (admin)
    -   `POST /admin/members/register`: Đăng ký thành viên
    -   `POST /admin/members/{user}/cancel`: Hủy tài khoản
