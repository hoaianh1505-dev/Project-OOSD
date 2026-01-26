-- ===== REVIEW SYSTEM TABLES =====

-- Bảng Reviews (Đánh giá sản phẩm)
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    customer_id INT,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200) NOT NULL,
    content LONGTEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    
    INDEX idx_product_id (product_id),
    INDEX idx_customer_id (customer_id),
    INDEX idx_status (status),
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
);

-- Bảng Review Ratings (Hữu ích/Không hữu ích)
CREATE TABLE review_ratings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT NOT NULL,
    customer_id INT,
    rating ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    
    UNIQUE KEY unique_rating (review_id, customer_id),
    INDEX idx_review_id (review_id)
);

-- Bảng Review Images (Ảnh đi kèm review - tuỳ chọn)
CREATE TABLE review_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    
    INDEX idx_review_id (review_id)
);

-- Bảng Review Responses (Trả lời từ admin - tuỳ chọn)
CREATE TABLE review_responses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT NOT NULL,
    staff_id INT NOT NULL,
    response_text LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
    
    INDEX idx_review_id (review_id)
);

-- ===== VIEWS =====

-- View để hiển thị review với thông tin sản phẩm
CREATE VIEW view_reviews AS
SELECT 
    r.id,
    r.product_id,
    r.customer_id,
    r.fullname,
    r.email,
    r.rating,
    r.title,
    r.content,
    r.status,
    r.likes,
    r.dislikes,
    r.verified,
    r.created_at,
    r.updated_at,
    p.name as product_name,
    p.sale_price,
    p.image,
    (SELECT COUNT(*) FROM reviews WHERE product_id = r.product_id AND status = 'approved') as product_review_count,
    (SELECT AVG(rating) FROM reviews WHERE product_id = r.product_id AND status = 'approved') as product_avg_rating
FROM reviews r
LEFT JOIN products p ON r.product_id = p.id
WHERE r.deleted_at IS NULL;

-- ===== INSERT SAMPLE DATA (Optional) =====

-- Sample reviews (bỏ comment nếu không cần)
/*
INSERT INTO reviews (product_id, customer_id, fullname, email, rating, title, content, status, verified, created_at)
VALUES 
(1, 1, 'Nguyễn Văn A', 'a@example.com', 5, 'Sản phẩm tuyệt vời', 'Hoa tươi, giao hàng nhanh, đóng gói chắc chắn. Rất hài lòng với sản phẩm này!', 'approved', TRUE, NOW()),
(1, 2, 'Trần Thị B', 'b@example.com', 4, 'Đẹp nhưng giá hơi cao', 'Sản phẩm đẹp, nhưng giá hơi cao so với những nơi khác. Tuy nhiên chất lượng tốt.', 'approved', TRUE, NOW()),
(1, 3, 'Lê Văn C', 'c@example.com', 3, 'Bình thường', 'Sản phẩm ổn, tuy nhiên không xuất sắc như mong đợi.', 'pending', FALSE, NOW());
*/
