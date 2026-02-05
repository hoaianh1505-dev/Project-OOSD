<?php
/**
 * HƯỚNG DẪN TÍCH HỢP HỆ THỐNG REVIEW VÀO DỰ ÁN
 * 
 * Các bước chi tiết để tích hợp review system vào Project-OOSD
 */
?>

# 📚 Hướng dẫn Tích hợp Hệ thống Review

## 📋 Các file đã tạo

```
✅ site/view/review/
   ├── form.php                      # Form viết review
   ├── list.php                      # Danh sách review
   ├── product-detail-example.php    # Ví dụ tích hợp
   └── README.md

✅ admin/view/review/
   └── index.php                     # Admin quản lý review

✅ site/public/css/
   └── review.css                    # CSS tổng hợp

✅ create_review_tables.sql          # Schema database
```

---

## 🔧 Bước 1: Tạo Database Tables

### 1.1 Chạy SQL Script

```bash
# Vào MySQL/PhpMyAdmin
# Import file: create_review_tables.sql
# Hoặc chạy lệnh:
```

```sql
-- Mở file create_review_tables.sql và chạy tất cả các lệnh
```

### 1.2 Kiểm tra tables đã tạo

```sql
SHOW TABLES LIKE 'review%';
-- Kết quả: reviews, review_ratings, review_images, review_responses
```

---

## 🎯 Bước 2: Tạo Model Classes

### 2.1 Tạo Review Model

Tạo file: `model/review/Review.php`

```php
<?php

class Review
{
    public $id;
    public $product_id;
    public $customer_id;
    public $fullname;
    public $email;
    public $rating;
    public $title;
    public $content;
    public $status; // pending, approved, rejected
    public $likes;
    public $dislikes;
    public $verified;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
    }
}
```

### 2.2 Tạo ReviewRepository

Tạo file: `model/review/ReviewRepository.php`

```php
<?php

class ReviewRepository extends BaseRepository
{
    function __construct()
    {
        parent::__construct('reviews');
    }

    // Lấy review theo product_id
    function getByProductId($product_id, $status = 'approved', $limit = 10, $offset = 0)
    {
        $conds = [
            'product_id' => ['type' => '=', 'val' => $product_id],
            'status' => ['type' => '=', 'val' => $status]
        ];
        return $this->findByConds($conds, [], $limit, $offset);
    }

    // Lấy đánh giá trung bình
    function getAverageRating($product_id)
    {
        $db = new ConnectDB();
        $sql = "SELECT AVG(rating) as avg_rating FROM reviews 
                WHERE product_id = $product_id AND status = 'approved'";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        return $row['avg_rating'] ?? 0;
    }

    // Đếm review theo rating
    function getCountByRating($product_id)
    {
        $db = new ConnectDB();
        $sql = "SELECT rating, COUNT(*) as count FROM reviews 
                WHERE product_id = $product_id AND status = 'approved'
                GROUP BY rating";
        $result = $db->query($sql);
        $counts = [];
        while ($row = $result->fetch_assoc()) {
            $counts[$row['rating']] = $row['count'];
        }
        return $counts;
    }

    // Cập nhật trạng thái review
    function updateStatus($id, $status)
    {
        return $this->update(['status' => $status], ['id' => $id]);
    }

    // Lấy review cho admin (có filter)
    function getForAdmin($conds = [], $sorts = [], $limit = 20, $offset = 0)
    {
        return $this->findByConds($conds, $sorts, $limit, $offset);
    }
}
```

---

## 🖥️ Bước 3: Tạo Controllers

### 3.1 Tạo ReviewController (Site)

Tạo file: `site/controller/ReviewController.php`

```php
<?php

class ReviewController
{
    // Trang viết review
    function form()
    {
        $product_id = $_GET['product_id'] ?? 0;
        require 'view/review/form.php';
    }

    // Gửi review
    function submit()
    {
        // Validate data
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rating = (int)($_POST['rating'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $product_id = (int)($_POST['product_id'] ?? 0);

        if (empty($fullname) || empty($email) || $rating < 1 || $rating > 5 
            || empty($title) || empty($content) || $product_id < 1) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            return;
        }

        // Save review
        $reviewRepo = new ReviewRepository();
        $review = new Review();
        $review->product_id = $product_id;
        $review->customer_id = $_SESSION['customer_id'] ?? null;
        $review->fullname = $fullname;
        $review->email = $email;
        $review->rating = $rating;
        $review->title = $title;
        $review->content = $content;
        $review->status = 'pending'; // Chờ duyệt
        $review->verified = false;

        if ($reviewRepo->insert((array)$review)) {
            echo json_encode(['success' => true, 'message' => 'Gửi đánh giá thành công, đang chờ duyệt']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi gửi đánh giá']);
        }
    }

    // Lấy danh sách review
    function getList()
    {
        $product_id = (int)($_GET['product_id'] ?? 0);
        $page = (int)($_GET['page'] ?? 1);
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $reviewRepo = new ReviewRepository();
        $reviews = $reviewRepo->getByProductId($product_id, 'approved', $per_page, $offset);
        $avg_rating = $reviewRepo->getAverageRating($product_id);
        $rating_counts = $reviewRepo->getCountByRating($product_id);

        echo json_encode([
            'reviews' => $reviews,
            'avg_rating' => $avg_rating,
            'rating_counts' => $rating_counts
        ]);
    }

    // Like review
    function like()
    {
        $review_id = (int)($_POST['review_id'] ?? 0);
        if ($review_id > 0) {
            $reviewRepo = new ReviewRepository();
            $reviewRepo->update(['likes' => 'likes + 1'], ['id' => $review_id]);
            echo json_encode(['success' => true]);
        }
    }
}
```

### 3.2 Tạo ReviewController (Admin)

Tạo file: `admin/controller/ReviewController.php`

```php
<?php

class ReviewController
{
    // Danh sách review
    function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $per_page = 20;
        $offset = ($page - 1) * $per_page;

        $reviewRepo = new ReviewRepository();
        
        // Build conditions
        $conds = [];
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $conds['status'] = ['type' => '=', 'val' => $_GET['status']];
        }
        if (isset($_GET['rating']) && !empty($_GET['rating'])) {
            $conds['rating'] = ['type' => '=', 'val' => (int)$_GET['rating']];
        }

        $reviews = $reviewRepo->getForAdmin($conds, [], $per_page, $offset);
        
        // Stats
        $total_reviews = $reviewRepo->countByConds(['status' => ['type' => '!=', 'val' => 'rejected']]);
        $pending_reviews = $reviewRepo->countByConds(['status' => ['type' => '=', 'val' => 'pending']]);
        $approved_reviews = $reviewRepo->countByConds(['status' => ['type' => '=', 'val' => 'approved']]);
        $rejected_reviews = $reviewRepo->countByConds(['status' => ['type' => '=', 'val' => 'rejected']]);

        require 'view/review/index.php';
    }

    // Duyệt review
    function approve()
    {
        $id = (int)($_POST['id'] ?? 0);
        $reviewRepo = new ReviewRepository();
        if ($reviewRepo->updateStatus($id, 'approved')) {
            echo json_encode(['success' => true]);
        }
    }

    // Từ chối review
    function reject()
    {
        $id = (int)($_POST['id'] ?? 0);
        $reviewRepo = new ReviewRepository();
        if ($reviewRepo->updateStatus($id, 'rejected')) {
            echo json_encode(['success' => true]);
        }
    }

    // Xóa review
    function delete()
    {
        $id = (int)($_POST['id'] ?? 0);
        $reviewRepo = new ReviewRepository();
        if ($reviewRepo->delete(['id' => $id])) {
            echo json_encode(['success' => true]);
        }
    }
}
```

---

## 🎨 Bước 4: Tích hợp vào View Hiện tại

### 4.1 Sửa file `site/view/product/detail.php`

Thêm vào cuối file:

```php
<?php
// ... code hiện tại ...

// Phần này thêm vào cuối trang chi tiết sản phẩm
?>

<!-- Review Section -->
<div style="margin-top: 50px;">
    <?php 
    // Hiển thị form review nếu đã đăng nhập
    if (isset($_SESSION['customer_id'])) {
        $product_id = $_GET['id']; // Giả sử product id lấy từ query string
        include 'view/review/form.php'; 
    } else {
        echo '<p style="text-align: center; color: #999;">
                <a href="index.php?c=auth&a=login">Đăng nhập</a> để viết đánh giá sản phẩm
              </p>';
    }
    ?>
</div>

<!-- Reviews List -->
<div style="margin-top: 30px;">
    <?php 
    $product_id = $_GET['id']; // ID sản phẩm
    $reviewRepo = new ReviewRepository();
    $reviews = $reviewRepo->getByProductId($product_id, 'approved', 10, 0);
    $avg_rating = $reviewRepo->getAverageRating($product_id);
    $rating_counts = $reviewRepo->getCountByRating($product_id);
    $total_reviews = count($reviews);
    
    include 'view/review/list.php'; 
    ?>
</div>

<!-- CSS -->
<link rel="stylesheet" href="public/css/review.css">
```

### 4.2 Sửa file `site/layout/header.php`

Thêm CSS link (nếu chưa có):

```html
<link rel="stylesheet" href="public/css/review.css">
```

---

## 🚀 Bước 5: Test Hệ thống

### 5.1 Test Frontend

1. Truy cập trang chi tiết sản phẩm
2. Nhấn form "Viết đánh giá"
3. Điền thông tin, chọn sao, gửi
4. Kiểm tra review hiển thị (chờ duyệt)

### 5.2 Test Admin

1. Vào `/admin/?c=review`
2. Xem danh sách review
3. Duyệt/Từ chối/Xóa review
4. Kiểm tra thống kê

---

## ⚙️ Cấu hình Thêm

### Thiết lập lọc tự động spam

```php
// Trong ReviewController::submit()
// Thêm kiểm tra từ khóa spam
$spam_keywords = ['spam', 'lottery', 'viagra', ...];
foreach ($spam_keywords as $keyword) {
    if (stripos($content, $keyword) !== false) {
        $review->status = 'rejected';
        break;
    }
}
```

### Email thông báo

```php
// Khi có review mới, gửi email cho admin
$emailService = new EmailService();
$emailService->send(
    SHOP_OWNER,
    'Đánh giá sản phẩm mới',
    "Có đánh giá mới đang chờ duyệt"
);
```

---

## 📊 API Endpoints

```
POST   /site/?c=review&a=submit      → Gửi review
GET    /site/?c=review&a=getList     → Lấy danh sách review
POST   /site/?c=review&a=like        → Like review

GET    /admin/?c=review              → Danh sách admin
POST   /admin/?c=review&a=approve    → Duyệt review
POST   /admin/?c=review&a=reject     → Từ chối review
POST   /admin/?c=review&a=delete     → Xóa review
```

---

## 🎯 Thêm tính năng (tuỳ chọn)

### 1. Upload ảnh kèm review

Thêm input file vào form.php:

```php
<div class="form-group">
    <label for="images">Hình ảnh (tuỳ chọn)</label>
    <input type="file" id="images" name="images[]" multiple accept="image/*">
</div>
```

### 2. Review responses (trả lời khách hàng)

Thêm field trong admin view để admin trả lời review

### 3. Email notification cho reviewer

Gửi email khi review được duyệt/từ chối

---

## 🐛 Xử lý Lỗi Thường Gặp

| Lỗi | Giải pháp |
|-----|----------|
| Review không hiển thị | Kiểm tra status = 'approved' |
| Form không gửi được | Validate JavaScript, check browser console |
| CSS không apply | Kiểm tra đường dẫn file CSS |
| Database error | Chạy lại SQL script, check table structure |

---

## 📞 Liên hệ Support

Nếu gặp vấn đề, kiểm tra:
1. Browser console (F12) → Console tab
2. Server logs
3. Database tables
4. PHP error logs

---

**Happy coding! 🚀**
