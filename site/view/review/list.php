<?php
/**
 * Danh sách đánh giá sản phẩm
 */
?>

<style>
    .reviews-container {
        margin: 30px 0;
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e91e63;
    }

    .reviews-title {
        font-size: 28px;
        font-weight: bold;
        color: #333;
    }

    .reviews-count {
        font-size: 14px;
        color: #666;
    }

    /* Average Rating Box */
    .rating-summary {
        background: #f9f9f9;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .rating-average {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .average-score {
        font-size: 48px;
        font-weight: bold;
        color: #e91e63;
    }

    .average-text {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }

    .rating-stars {
        font-size: 24px;
        color: #ffc107;
        margin-top: 8px;
    }

    .rating-bars {
        flex: 1;
    }

    .rating-bar-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .rating-bar-label {
        font-size: 14px;
        color: #666;
        width: 40px;
    }

    .rating-bar {
        flex: 1;
        height: 6px;
        background: #ddd;
        border-radius: 3px;
        overflow: hidden;
    }

    .rating-bar-fill {
        height: 100%;
        background: #ffc107;
    }

    .rating-bar-count {
        font-size: 12px;
        color: #999;
        width: 40px;
        text-align: right;
    }

    /* Review Item */
    .review-item {
        background: white;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .review-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e91e63;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }

    .reviewer-name {
        font-weight: 600;
        color: #333;
    }

    .reviewer-date {
        font-size: 12px;
        color: #999;
    }

    .review-rating {
        font-size: 18px;
        color: #ffc107;
    }

    .review-title {
        font-weight: 600;
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
    }

    .review-content {
        color: #555;
        line-height: 1.6;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .review-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #eee;
    }

    .review-stats {
        display: flex;
        gap: 15px;
        font-size: 12px;
        color: #666;
    }

    .review-stat-item {
        cursor: pointer;
        transition: all 0.2s;
    }

    .review-stat-item:hover {
        color: #e91e63;
    }

    .verified-badge {
        background: #d4edda;
        color: #155724;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Empty State */
    .no-reviews {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .no-reviews-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .no-reviews-text {
        font-size: 16px;
        margin-bottom: 15px;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 30px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: #e91e63;
        color: white;
        border-color: #e91e63;
    }

    .pagination .active {
        background: #e91e63;
        color: white;
        border-color: #e91e63;
    }

    .load-more-btn {
        display: block;
        margin: 30px auto;
        background: #e91e63;
        color: white;
        padding: 12px 40px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .load-more-btn:hover {
        background: #c2185b;
    }
</style>

<div class="reviews-container">
    <!-- Header -->
    <div class="reviews-header">
        <h2 class="reviews-title">Đánh giá sản phẩm</h2>
        <span class="reviews-count"><?php echo isset($total_reviews) ? $total_reviews : 0; ?> đánh giá</span>
    </div>

    <!-- Rating Summary -->
    <div class="rating-summary">
        <div class="rating-average">
            <div class="average-score"><?php echo isset($avg_rating) ? number_format($avg_rating, 1) : '0'; ?></div>
            <div class="rating-stars">
                <?php 
                $rating = isset($avg_rating) ? $avg_rating : 0;
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= floor($rating)) {
                        echo '★';
                    } else if ($i - $rating < 1) {
                        echo '☆';
                    } else {
                        echo '☆';
                    }
                }
                ?>
            </div>
            <div class="average-text">trên 5 sao</div>
        </div>

        <div class="rating-bars">
            <?php 
            $ratings = [5, 4, 3, 2, 1];
            foreach ($ratings as $star) {
                $count = isset($rating_counts[$star]) ? $rating_counts[$star] : 0;
                $total = isset($total_reviews) ? $total_reviews : 1;
                $percentage = $total > 0 ? ($count / $total * 100) : 0;
            ?>
            <div class="rating-bar-item">
                <div class="rating-bar-label"><?php echo $star; ?> ★</div>
                <div class="rating-bar">
                    <div class="rating-bar-fill" style="width: <?php echo $percentage; ?>%;"></div>
                </div>
                <div class="rating-bar-count"><?php echo $count; ?></div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="reviews-list">
        <?php 
        if (isset($reviews) && !empty($reviews)) {
            foreach ($reviews as $review) {
        ?>
        <div class="review-item">
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar"><?php echo strtoupper(substr($review['fullname'], 0, 1)); ?></div>
                    <div>
                        <div class="reviewer-name"><?php echo $review['fullname']; ?></div>
                        <div class="reviewer-date"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></div>
                    </div>
                    <?php if (isset($review['verified']) && $review['verified']) { ?>
                        <span class="verified-badge">✓ Đã xác nhận</span>
                    <?php } ?>
                </div>
                <div class="review-rating">
                    <?php 
                    for ($i = 0; $i < $review['rating']; $i++) {
                        echo '★';
                    }
                    for ($i = $review['rating']; $i < 5; $i++) {
                        echo '☆';
                    }
                    ?>
                </div>
            </div>

            <div class="review-title"><?php echo htmlspecialchars($review['title']); ?></div>
            <div class="review-content"><?php echo nl2br(htmlspecialchars($review['content'])); ?></div>

            <div class="review-footer">
                <div class="review-stats">
                    <span class="review-stat-item" onclick="likeReview(<?php echo $review['id']; ?>)">
                        👍 Hữu ích (<?php echo isset($review['likes']) ? $review['likes'] : 0; ?>)
                    </span>
                    <span class="review-stat-item" onclick="dislikeReview(<?php echo $review['id']; ?>)">
                        👎 Không hữu ích (<?php echo isset($review['dislikes']) ? $review['dislikes'] : 0; ?>)
                    </span>
                </div>
                <span style="font-size: 12px; color: #999;">ID: <?php echo $review['id']; ?></span>
            </div>
        </div>
        <?php 
            }
        } else {
        ?>
        <div class="no-reviews">
            <div class="no-reviews-icon">★</div>
            <div class="no-reviews-text">Chưa có đánh giá nào</div>
            <p style="font-size: 14px;">Hãy là người đầu tiên đánh giá sản phẩm này</p>
        </div>
        <?php } ?>
    </div>

    <!-- Load More Button (nếu có nhiều reviews) -->
    <?php if (isset($has_more) && $has_more) { ?>
    <button class="load-more-btn" onclick="loadMoreReviews()">Xem thêm đánh giá</button>
    <?php } ?>
</div>

<script>
    function likeReview(reviewId) {
        // Call API to like review
        console.log('Like review:', reviewId);
    }

    function dislikeReview(reviewId) {
        // Call API to dislike review
        console.log('Dislike review:', reviewId);
    }

    function loadMoreReviews() {
        // Load more reviews
        console.log('Load more reviews');
    }
</script>
