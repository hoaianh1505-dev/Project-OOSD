<?php
/**
 * Admin - Quản lý đánh giá sản phẩm
 */
?>

<style>
    .reviews-management-container {
        padding: 20px;
    }

    .page-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    /* Filters */
    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .filters-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-group label {
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }

    .filter-group select,
    .filter-group input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        min-width: 150px;
    }

    .filter-btn {
        background: #007bff;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        align-self: flex-end;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        background: #0056b3;
    }

    .reset-btn {
        background: #6c757d;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        align-self: flex-end;
        transition: all 0.3s ease;
    }

    .reset-btn:hover {
        background: #545b62;
    }

    /* Table */
    .reviews-table {
        width: 100%;
        background: white;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .reviews-table thead {
        background: #f8f9fa;
    }

    .reviews-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #ddd;
    }

    .reviews-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .reviews-table tr:hover {
        background: #f9f9f9;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    /* Rating Stars */
    .rating-display {
        color: #ffc107;
        font-size: 16px;
    }

    /* Actions */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-view {
        background: #17a2b8;
        color: white;
    }

    .btn-view:hover {
        background: #138496;
    }

    .btn-approve {
        background: #28a745;
        color: white;
    }

    .btn-approve:hover {
        background: #218838;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
    }

    .btn-reject:hover {
        background: #c82333;
    }

    .btn-delete {
        background: #6c757d;
        color: white;
    }

    .btn-delete:hover {
        background: #5a6268;
    }

    /* Summary Cards */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }

    .summary-card h3 {
        color: #999;
        font-size: 14px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .summary-card .number {
        font-size: 32px;
        font-weight: bold;
        color: #333;
    }

    .summary-card.pending .number {
        color: #ffc107;
    }

    .summary-card.approved .number {
        color: #28a745;
    }

    .summary-card.rejected .number {
        color: #dc3545;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
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
        background: #007bff;
        color: white;
    }

    .pagination .active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    /* Checkbox */
    .checkbox-column {
        width: 40px;
    }

    .select-all {
        cursor: pointer;
    }

    /* Bulk Actions */
    .bulk-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .bulk-actions button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        display: none;
    }

    .bulk-actions button.show {
        display: block;
    }

    .bulk-approve {
        background: #28a745;
        color: white;
    }

    .bulk-reject {
        background: #dc3545;
        color: white;
    }

    .bulk-delete {
        background: #6c757d;
        color: white;
    }
</style>

<div class="reviews-management-container">
    <!-- Page Title -->
    <h1 class="page-title">Quản lý Đánh giá sản phẩm</h1>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Tổng số đánh giá</h3>
            <div class="number"><?php echo isset($total_reviews) ? $total_reviews : 0; ?></div>
        </div>
        <div class="summary-card pending">
            <h3>Chờ duyệt</h3>
            <div class="number"><?php echo isset($pending_reviews) ? $pending_reviews : 0; ?></div>
        </div>
        <div class="summary-card approved">
            <h3>Đã duyệt</h3>
            <div class="number"><?php echo isset($approved_reviews) ? $approved_reviews : 0; ?></div>
        </div>
        <div class="summary-card rejected">
            <h3>Bị từ chối</h3>
            <div class="number"><?php echo isset($rejected_reviews) ? $rejected_reviews : 0; ?></div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" id="filterForm">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="filterStatus">Trạng thái</label>
                    <select name="status" id="filterStatus">
                        <option value="">Tất cả</option>
                        <option value="pending">Chờ duyệt</option>
                        <option value="approved">Đã duyệt</option>
                        <option value="rejected">Bị từ chối</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterRating">Đánh giá</label>
                    <select name="rating" id="filterRating">
                        <option value="">Tất cả</option>
                        <option value="5">5 sao</option>
                        <option value="4">4 sao</option>
                        <option value="3">3 sao</option>
                        <option value="2">2 sao</option>
                        <option value="1">1 sao</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterProduct">Sản phẩm</label>
                    <input type="text" name="product" id="filterProduct" placeholder="Tìm theo tên sản phẩm">
                </div>

                <div class="filter-group">
                    <label for="filterCustomer">Khách hàng</label>
                    <input type="text" name="customer" id="filterCustomer" placeholder="Tìm theo tên khách hàng">
                </div>

                <button type="submit" class="filter-btn">Tìm kiếm</button>
                <button type="reset" class="reset-btn">Đặt lại</button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <button class="bulk-approve" onclick="bulkApprove()">Duyệt được chọn</button>
        <button class="bulk-reject" onclick="bulkReject()">Từ chối được chọn</button>
        <button class="bulk-delete" onclick="bulkDelete()">Xóa được chọn</button>
    </div>

    <!-- Table -->
    <table class="reviews-table">
        <thead>
            <tr>
                <th class="checkbox-column">
                    <input type="checkbox" id="selectAll" class="select-all" onchange="toggleSelectAll()">
                </th>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Khách hàng</th>
                <th>Đánh giá</th>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (isset($reviews) && !empty($reviews)) {
                foreach ($reviews as $review) {
            ?>
            <tr>
                <td>
                    <input type="checkbox" class="review-checkbox" value="<?php echo $review['id']; ?>">
                </td>
                <td>#<?php echo $review['id']; ?></td>
                <td>
                    <a href="index.php?c=product&a=detail&id=<?php echo $review['product_id']; ?>" title="<?php echo $review['product_name']; ?>">
                        <?php echo strlen($review['product_name']) > 30 ? substr($review['product_name'], 0, 30) . '...' : $review['product_name']; ?>
                    </a>
                </td>
                <td>
                    <strong><?php echo $review['fullname']; ?></strong><br>
                    <small><?php echo $review['email']; ?></small>
                </td>
                <td class="rating-display">
                    <?php 
                    for ($i = 0; $i < $review['rating']; $i++) {
                        echo '★';
                    }
                    for ($i = $review['rating']; $i < 5; $i++) {
                        echo '☆';
                    }
                    ?>
                </td>
                <td title="<?php echo $review['title']; ?>">
                    <?php echo strlen($review['title']) > 25 ? substr($review['title'], 0, 25) . '...' : $review['title']; ?>
                </td>
                <td>
                    <?php 
                    $status_class = 'status-' . $review['status'];
                    $status_text = $review['status'] == 'pending' ? 'Chờ duyệt' : ($review['status'] == 'approved' ? 'Đã duyệt' : 'Bị từ chối');
                    ?>
                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                </td>
                <td><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" onclick="viewReview(<?php echo $review['id']; ?>)">Xem</button>
                        <button class="btn-action btn-approve" onclick="approveReview(<?php echo $review['id']; ?>)">Duyệt</button>
                        <button class="btn-action btn-reject" onclick="rejectReview(<?php echo $review['id']; ?>)">Từ chối</button>
                        <button class="btn-action btn-delete" onclick="deleteReview(<?php echo $review['id']; ?>)">Xóa</button>
                    </div>
                </td>
            </tr>
            <?php 
                }
            } else {
            ?>
            <tr>
                <td colspan="9" style="text-align: center; padding: 40px;">Không có đánh giá nào</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if (isset($total_pages) && $total_pages > 1) { ?>
    <div class="pagination">
        <?php 
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        
        if ($current_page > 1) {
            echo '<a href="index.php?c=review&page=1">« Đầu tiên</a>';
            echo '<a href="index.php?c=review&page=' . ($current_page - 1) . '">‹ Trước</a>';
        }
        
        for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++) {
            if ($i == $current_page) {
                echo '<span class="active">' . $i . '</span>';
            } else {
                echo '<a href="index.php?c=review&page=' . $i . '">' . $i . '</a>';
            }
        }
        
        if ($current_page < $total_pages) {
            echo '<a href="index.php?c=review&page=' . ($current_page + 1) . '">Tiếp › </a>';
            echo '<a href="index.php?c=review&page=' . $total_pages . '">Cuối cùng »</a>';
        }
        ?>
    </div>
    <?php } ?>
</div>

<!-- Modal View Review -->
<div id="viewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto;">
        <button onclick="closeModal()" style="float: right; background: #999; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">×</button>
        <h2 id="modalTitle" style="margin-top: 0;">Chi tiết đánh giá</h2>
        <div id="modalContent"></div>
    </div>
</div>

<script>
    function toggleSelectAll() {
        const checkboxes = document.querySelectorAll('.review-checkbox');
        const selectAll = document.getElementById('selectAll');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        
        updateBulkActionsVisibility();
    }

    function updateBulkActionsVisibility() {
        const checkboxes = document.querySelectorAll('.review-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        
        if (checkboxes.length > 0) {
            bulkActions.querySelectorAll('button').forEach(btn => {
                btn.classList.add('show');
            });
        } else {
            bulkActions.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('show');
            });
        }
    }

    document.querySelectorAll('.review-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsVisibility);
    });

    function getSelectedReviews() {
        const checkboxes = document.querySelectorAll('.review-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    function viewReview(reviewId) {
        // Simulate fetching review details
        const modal = document.getElementById('viewModal');
        const content = document.getElementById('modalContent');
        
        content.innerHTML = `
            <p><strong>ID:</strong> ${reviewId}</p>
            <p><strong>Nội dung:</strong> Sản phẩm rất tốt, giao hàng nhanh, đóng gói chắc chắn...</p>
            <p><strong>Email:</strong> customer@example.com</p>
            <p><strong>Ngày tạo:</strong> 24/01/2025</p>
        `;
        
        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('viewModal').style.display = 'none';
    }

    function approveReview(reviewId) {
        if (confirm('Bạn chắc chắn muốn duyệt đánh giá này?')) {
            // Call API to approve
            console.log('Approve review:', reviewId);
            location.reload();
        }
    }

    function rejectReview(reviewId) {
        if (confirm('Bạn chắc chắn muốn từ chối đánh giá này?')) {
            // Call API to reject
            console.log('Reject review:', reviewId);
            location.reload();
        }
    }

    function deleteReview(reviewId) {
        if (confirm('Bạn chắc chắn muốn xóa đánh giá này? Hành động này không thể hoàn tác.')) {
            // Call API to delete
            console.log('Delete review:', reviewId);
            location.reload();
        }
    }

    function bulkApprove() {
        const ids = getSelectedReviews();
        if (ids.length === 0) {
            alert('Vui lòng chọn ít nhất một đánh giá');
            return;
        }
        
        if (confirm('Duyệt ' + ids.length + ' đánh giá được chọn?')) {
            console.log('Bulk approve:', ids);
            location.reload();
        }
    }

    function bulkReject() {
        const ids = getSelectedReviews();
        if (ids.length === 0) {
            alert('Vui lòng chọn ít nhất một đánh giá');
            return;
        }
        
        if (confirm('Từ chối ' + ids.length + ' đánh giá được chọn?')) {
            console.log('Bulk reject:', ids);
            location.reload();
        }
    }

    function bulkDelete() {
        const ids = getSelectedReviews();
        if (ids.length === 0) {
            alert('Vui lòng chọn ít nhất một đánh giá');
            return;
        }
        
        if (confirm('Xóa ' + ids.length + ' đánh giá được chọn? Hành động này không thể hoàn tác.')) {
            console.log('Bulk delete:', ids);
            location.reload();
        }
    }
</script>
