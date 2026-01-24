<?php
/**
 * Form viết đánh giá sản phẩm
 */
?>

<style>
    .review-form-container {
        background: #f9f9f9;
        padding: 25px;
        border-radius: 8px;
        margin: 30px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .review-form-title {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        font-family: Arial, sans-serif;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #e91e63;
        box-shadow: 0 0 5px rgba(233, 30, 99, 0.3);
    }

    /* Star Rating */
    .star-rating {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .star-rating .star {
        font-size: 32px;
        cursor: pointer;
        color: #ddd;
        transition: all 0.2s ease;
    }

    .star-rating .star:hover,
    .star-rating .star.active {
        color: #ffc107;
        transform: scale(1.1);
    }

    .star-count {
        display: inline-block;
        margin-left: 10px;
        color: #666;
        font-weight: 600;
    }

    /* Buttons */
    .btn-submit {
        background: #e91e63;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #c2185b;
        box-shadow: 0 4px 8px rgba(233, 30, 99, 0.3);
    }

    .btn-cancel {
        background: #999;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        margin-left: 10px;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #777;
    }

    .button-group {
        display: flex;
        gap: 10px;
    }

    .alert-message {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        display: none;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="review-form-container">
    <h3 class="review-form-title">Viết đánh giá sản phẩm</h3>

    <div class="alert-message" id="alertMessage"></div>

    <form id="reviewForm" method="POST" action="index.php?c=review&a=submit">
        <!-- Product ID -->
        <input type="hidden" name="product_id" id="productId" value="<?php echo isset($product_id) ? $product_id : ''; ?>">

        <!-- Full Name -->
        <div class="form-group">
            <label for="fullname">Tên của bạn <span style="color: red;">*</span></label>
            <input type="text" id="fullname" name="fullname" required placeholder="Nhập tên của bạn">
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email <span style="color: red;">*</span></label>
            <input type="email" id="email" name="email" required placeholder="Nhập email của bạn">
        </div>

        <!-- Star Rating -->
        <div class="form-group">
            <label>Đánh giá <span style="color: red;">*</span></label>
            <div class="star-rating" id="starRating">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>
            <span class="star-count" id="starCount">0/5</span>
            <input type="hidden" name="rating" id="rating" value="0" required>
        </div>

        <!-- Title -->
        <div class="form-group">
            <label for="title">Tiêu đề đánh giá <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" required placeholder="vd: Sản phẩm tuyệt vời" maxlength="100">
        </div>

        <!-- Content -->
        <div class="form-group">
            <label for="content">Nội dung đánh giá <span style="color: red;">*</span></label>
            <textarea id="content" name="content" required placeholder="Chia sẻ chi tiết nhận xét của bạn về sản phẩm..."></textarea>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn-submit">Gửi đánh giá</button>
            <button type="reset" class="btn-cancel">Xóa</button>
        </div>
    </form>
</div>

<script>
    // Star Rating Logic
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const starCount = document.getElementById('starCount');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            ratingInput.value = value;
            starCount.textContent = value + '/5';
            
            // Update star colors
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });

        // Hover effect
        star.addEventListener('mouseenter', function() {
            const value = this.getAttribute('data-value');
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });

    document.getElementById('starRating').addEventListener('mouseleave', function() {
        const selectedValue = ratingInput.value;
        stars.forEach(s => {
            if (s.getAttribute('data-value') <= selectedValue) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#ddd';
            }
        });
    });

    // Form Validation
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        const rating = document.getElementById('rating').value;
        
        if (rating == 0) {
            e.preventDefault();
            showAlert('Vui lòng chọn số sao đánh giá', 'error');
        }
    });

    function showAlert(message, type) {
        const alert = document.getElementById('alertMessage');
        alert.textContent = message;
        alert.className = 'alert-message alert-' + type;
        alert.style.display = 'block';
        
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
</script>
