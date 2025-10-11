# Hệ thống Quản lý Thư viện

Đây là một dự án quản lý thư viện đơn giản được xây dựng bằng Laravel. Ứng dụng cho phép quản lý sách, thành viên và các phiếu mượn sách, với hệ thống phân quyền cho quản trị viên.

## Tính năng

-   **Quản lý Sách:** Thêm, xem, sửa và xóa sách trong thư viện (chỉ dành cho admin).
-   **Quản lý Thành viên:** Thêm, xem, sửa và xóa thông tin thành viên (chỉ dành cho admin).
-   **Quản lý Phiếu mượn:** Tạo phiếu mượn sách cho thành viên, cập nhật trạng thái trả sách và xem lịch sử mượn (chỉ dành cho admin).
-   **Xác thực & Phân quyền:** Hệ thống đăng ký, đăng nhập và phân quyền. Chỉ những người dùng có vai trò `admin` mới có thể truy cập các chức năng quản lý.

## Cấu trúc Cơ sở dữ liệu

Dự án sử dụng các bảng chính sau:

-   `users`: Lưu trữ thông tin người dùng.
    -   `id`, `name`, `email`, `password`
    -   `role`: Vai trò người dùng ('admin' hoặc 'user')
-   `books`: Lưu trữ thông tin về sách.
    -   `id`, `title`, `author`, `isbn`, `published_date`, `quantity`
-   `members`: Lưu trữ thông tin về thành viên thư viện.
    -   `id`, `name`, `email`, `phone`, `address`
-   `phieumuon`: Lưu trữ thông tin về các lần mượn sách.
    -   `id`, `book_id`, `member_id`, `loan_date`, `due_date`, `return_date`, `status`

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

    -   **Admin:**
        -   Email: `admin@example.com`
        -   Password: `password`
    -   **User:**
        -   Email: `user@example.com`
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

## Các Tuyến đường (Routes)

Ứng dụng sử dụng resource controllers để xử lý các yêu cầu CRUD. Các tuyến đường quản lý được bảo vệ bởi middleware `auth` và `admin`.

-   `GET /`: Chuyển hướng đến trang đăng nhập.
-   **Nhóm Route được bảo vệ (Admin):**
    ```php
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('books', BookController::class);
        Route::resource('members', MemberController::class);
        Route::resource('phieumuon', PhieuMuonController::class);
    });
    ```
