<?php
/**
 * README - Hệ thống Đánh giá & Review sản phẩm
 * 
 * Hướng dẫn tích hợp Review vào dự án
 */
?>

# Hệ thống Đánh giá & Review sản phẩm

## 📋 Tổng quan

Hệ thống review cho phép khách hàng:
- ⭐ Đánh giá sản phẩm từ 1-5 sao
- 💬 Viết nhận xét chi tiết
- 👍 Đánh giá độ hữu ích của review khác
- 📊 Xem đánh giá trung bình

Admin có thể:
- 🔍 Quản lý tất cả review
- ✅ Duyệt/Từ chối review
- 🗑️ Xóa review không phù hợp
- 📈 Xem thống kê đánh giá

---

## 📁 Cấu trúc File

```
site/
├── view/review/
│   ├── form.php              # Form viết đánh giá
│   ├── list.php              # Danh sách review sản phẩm
│   └── product-detail-example.php
├── public/css/
│   └── review.css            # CSS cho hệ thống review
│
admin/
└── view/review/
    └── index.php             # Admin quản lý review
```

---

## 🚀 Cách sử dụng

### 1. Hiển thị Form Đánh giá (Trang chi tiết sản phẩm)

```php
<?php
// site/view/product/detail.php hoặc tương tự

// Kiểm tra xem khách hàng đã đăng nhập không
if (isset($_SESSION['customer_id'])) {
    include 'view/review/form.php';
}

// Hiển thị danh sách review
include 'view/review/list.php';
?>
```

### 2. Thêm CSS vào header

```html
<link rel="stylesheet" href="public/css/review.css">
```

### 3. Tạo Database tables (SQL)

```sql
-- Bảng Review
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    customer_id INT NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT NOT NULL (1-5),
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX (product_id),
    INDEX (status)
);
```

---

## 📝 Tính năng

### Frontend Khách hàng
- ✅ Form nhập đánh giá với star rating tương tác
- ✅ Danh sách review với thông tin chi tiết
- ✅ Hiển thị đánh giá trung bình + biểu đồ thống kê
- ✅ Đánh giá độ hữu ích (likes/dislikes)
- ✅ Xác thực khách hàng đã mua sản phẩm (verified)

### Backend Admin
- ✅ Danh sách tất cả review (có lọc & tìm kiếm)
- ✅ Lọc theo trạng thái (pending, approved, rejected)
- ✅ Lọc theo số sao
- ✅ Duyệt/Từ chối review riêng lẻ
- ✅ Thao tác hàng loạt (bulk actions)
- ✅ Xóa review
- ✅ Thống kê tổng hợp

---

## 🎨 CSS Classes

### Form Elements
- `.review-form-container` - Container form review
- `.review-input-group` - Nhóm input
- `.star-rating` - Star rating widget
- `.btn-submit` - Nút gửi

### Review Display
- `.review-item` - Một review
- `.review-header` - Header review (author, rating)
- `.review-content` - Nội dung review
- `.review-footer` - Footer (actions, date)
- `.reviewer-avatar` - Avatar khách hàng
- `.verified-badge` - Badge xác nhận mua hàng

### Admin
- `.reviews-management-container` - Container admin
- `.summary-cards` - Các thẻ thống kê
- `.reviews-table` - Bảng review
- `.status-badge` - Badge trạng thái
- `.bulk-actions` - Hành động hàng loạt

---

## 🔧 Tuỳ chỉnh

### Thay đổi màu chủ đề

Thay đổi `#e91e63` thành màu của bạn trong `review.css`

### Số sao tối đa

Mặc định là 5 sao. Để thay đổi, sửa trong `form.php`:

```php
<span class="star" data-value="1">★</span>
<!-- ... lặp từ 1 đến số sao mong muốn ... -->
```

### Số lượng review mỗi trang

Chỉnh sửa trong controller:
```php
$reviews_per_page = 10; // Thay đổi số này
```

---

## 📱 Responsive

Tất cả component đều tối ưu cho mobile:
- Form responsive
- Table cuộn ngang trên mobile
- Star rating phù hợp kích thước màn hình

---

## ⚙️ Cấu hình

### Thời gian tính toán đánh giá trung bình

```php
// Tính từ tất cả review đã duyệt
SELECT AVG(rating) FROM reviews 
WHERE product_id = ? AND status = 'approved'
```

### Xác thực khách hàng

```php
// Xác minh khách hàng đã mua sản phẩm trước khi cho viết review
// Sử dụng kiểm tra trong OrderItem table
```

---

## 🐛 Troubleshooting

**Q: Review không hiển thị?**
- A: Kiểm tra trạng thái review có phải 'approved' không

**Q: Form không gửi được?**
- A: Kiểm tra validation JavaScript, đảm bảo chọn số sao

**Q: Không thấy avatar?**
- A: Avatar được tạo tự động từ ký tự đầu của tên

---

## 📞 Support

Liên hệ admin để được hỗ trợ thêm.
