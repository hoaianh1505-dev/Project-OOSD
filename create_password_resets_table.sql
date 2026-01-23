-- Bảng lưu trữ token reset mật khẩu
CREATE TABLE IF NOT EXISTS password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer(id) ON DELETE CASCADE
);

-- Tạo index để tìm kiếm nhanh
CREATE INDEX idx_customer_id ON password_resets(customer_id);
CREATE INDEX idx_expires_at ON password_resets(expires_at);
