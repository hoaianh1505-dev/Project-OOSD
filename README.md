# The Bloom Studio

Sử dụng website thương mại điện tử này để đặt hoa. Dự án này được xây dựng bằng PHP và MySQL.

## Bắt đầu

### Yêu cầu tiên quyết

*   Một máy chủ web như XAMPP, WAMP, hoặc MAMP.
*   PHP 7.4 trở lên.
*   Composer (để quản lý các thư viện phụ thuộc).
*   MySQL.

### Cài đặt

1. **Clone repository:**
    ```bash
    git clone https://github.com/your-username/thebloomstudio.git
    cd thebloomstudio
    ```

2.  **Cài đặt các thư viện PHP:**
    ```bash
    composer install
    ```

3.  **Cấu hình Cơ sở dữ liệu:**
    *   Tạo một cơ sở dữ liệu MySQL có tên `the_bloom_studio`.
    *   Import schema cơ sở dữ liệu (nếu có trong thư mục `model/` hoặc file SQL riêng) vào database của bạn.

4.  **Cấu hình Môi trường:**
    *   Tìm file `config.example.php` trong thư mục gốc.
    *   Đổi tên nó thành `config.php` (hoặc copy):
        ```bash
        cp config.example.php config.php
        ```
    *   Mở `config.php` và cập nhật các giá trị với cài đặt local của bạn:
        *   Cấu hình Database (`SERVERNAME`, `USERNAME`, `PASSWORD`, `DBNAME`).
        *   Cài đặt SMTP để gửi email.
        *   Google reCAPTCHA keys.
        *   Google Client ID và Secret để đăng nhập.

### Sử dụng

*   Khởi động Apache và MySQL server.
*   Truy cập dự án trên trình duyệt (ví dụ: `http://localhost/thebloomstudio`).

## Cấu trúc Thư mục

*   `admin/` - Mã nguồn trang quản trị (Admin panel).
*   `site/` - Mã nguồn giao diện người dùng (Front-end).
*   `model/` - Các model cơ sở dữ liệu và logic.
*   `service/` - Các dịch vụ hỗ trợ (ví dụ: gửi mail).
*   `upload/` - Hình ảnh và tài nguyên do người dùng tải lên.
*   `vendor/` - Các thư viện Composer.

## Đóng góp

Hoan nghênh các Pull request. Đối với những thay đổi lớn, vui lòng mở issue trước để thảo luận về những gì bạn muốn thay đổi.
