<?php
/**
 * Trang chi tiết sản phẩm - Tích hợp phần Review
 * (Mẫu tích hợp review vào trang chi tiết sản phẩm hiện tại)
 */
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Product Section */
        .product-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .product-image {
            width: 100%;
            height: 400px;
            background: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #999;
        }

        .product-info h1 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #333;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .product-rating .stars {
            color: #ffc107;
            font-size: 20px;
        }

        .product-rating .count {
            color: #666;
            font-size: 14px;
        }

        .product-price {
            font-size: 32px;
            font-weight: bold;
            color: #e91e63;
            margin-bottom: 20px;
        }

        .product-description {
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #e91e63;
            color: white;
        }

        .btn-primary:hover {
            background: #c2185b;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #e91e63;
        }

        .btn-secondary:hover {
            background: #e91e63;
            color: white;
        }

        @media (max-width: 768px) {
            .product-section {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .product-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Product Details Section -->
        <div class="product-section">
            <div class="product-image">
                [Hình ảnh sản phẩm]
            </div>

            <div class="product-info">
                <h1>Bó hoa hồng đỏ sang trọng</h1>

                <div class="product-rating">
                    <span class="stars">★★★★★</span>
                    <span class="count">4.8/5 (125 đánh giá)</span>
                </div>

                <div class="product-price">599.000đ</div>

                <p class="product-description">
                    Một bó hoa hồng đỏ tuyệt đẹp, thích hợp cho những dịp đặc biệt. 
                    Hoa tươi, được cắm chuyên nghiệp với lá trang trí tinh tế. Giao hàng miễn phí trong thành phố.
                </p>

                <div class="product-actions">
                    <button class="btn btn-primary">🛒 Thêm vào giỏ hàng</button>
                    <button class="btn btn-secondary">❤️ Thêm vào yêu thích</button>
                </div>
            </div>
        </div>

        <!-- Review Section -->
        <div style="margin-bottom: 30px;">
            <!-- Include form review -->
            <?php 
            // Nếu đã đăng nhập mới cho phép viết review
            // include 'view/review/form.php'; 
            ?>

            <!-- Include review list -->
            <?php 
            // include 'view/review/list.php';
            ?>
        </div>
    </div>
</body>
</html>
