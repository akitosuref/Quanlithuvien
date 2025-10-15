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

### akitosuref
-   Cập nhật README với phân chia công việc (commit: a33d913)
-   Tìm kiếm và phân trang sách (commit: 990724b)
-   Hệ thống ảnh bìa sách, sửa Model Member, cải thiện đăng ký và UX xác thực (commit: 6afff40)
-   Xóa module Authors và cập nhật module Books theo schema cơ sở dữ liệu (commit: 9eb9d9e)
-   Xóa tham chiếu Author từ migrations và seeders (commit: b00c586)
-   Tạo thêm posts (commit: 2ca54e4)
-   Merge remote và local README (commit: a613a93)
-   Initial commit (commit: 0a8e5db)
-   Cập nhật UserSeeder roles, xóa thông báo từ dashboard, làm sạch README (commit: 8f3b84b)
-   Cấu hình khởi tạo Laravel (commit: 5911024)
-   First commit (commit: 4a2b2ee)

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
