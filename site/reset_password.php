<?php
session_start();
require '../config.php';
require '../connectDB.php';
require '../bootstrap.php';

$cid = (int)($_GET['cid'] ?? 0);
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid = (int)($_POST['cid'] ?? 0);
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    // Kiểm tra mật khẩu
    if ($password !== $confirm) {
        $_SESSION['error'] = "Mật khẩu không khớp.";
        header("Location: reset_password.php?cid={$cid}&token=" . urlencode($token));
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Mật khẩu phải có ít nhất 6 ký tự.";
        header("Location: reset_password.php?cid={$cid}&token=" . urlencode($token));
        exit;
    }

    // Lấy token reset mới nhất của customer
    $sql = "SELECT id, token_hash, expires_at, used_at 
            FROM password_resets 
            WHERE customer_id = {$cid} 
            ORDER BY id DESC 
            LIMIT 1";
    
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $valid = false;
    if ($row) {
        $now = new DateTime();
        $exp = new DateTime($row['expires_at']);
        
        // Kiểm tra: token chưa dùng, chưa hết hạn, và hash khớp
        if ($row['used_at'] === null && $now <= $exp) {
            if (password_verify($token, $row['token_hash'])) {
                $valid = true;
            }
        }
    }

    if (!$valid) {
        $_SESSION['error'] = "Liên kết không hợp lệ hoặc đã hết hạn.";
        header("Location: reset_password.php?cid={$cid}&token=" . urlencode($token));
        exit;
    }

    // Cập nhật mật khẩu
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $updateSql = "UPDATE customer SET password = '{$newHash}' WHERE id = {$cid}";
    
    // Đánh dấu token đã sử dụng
    $usedSql = "UPDATE password_resets SET used_at = NOW() WHERE id = {$row['id']}";

    if ($conn->query($updateSql) && $conn->query($usedSql)) {
        $_SESSION['success'] = "Đổi mật khẩu thành công. Bạn có thể đăng nhập lại.";
        header("Location: /");
        exit;
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
        header("Location: reset_password.php?cid={$cid}&token=" . urlencode($token));
        exit;
    }
}

// GET: Hiển thị form
$err = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="password"]:focus,
        input[type="hidden"]:focus {
            outline: none;
            border-color: #667eea;
        }
        input[type="password"]:focus {
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #764ba2;
        }
        .text-center {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        a {
            color: #667eea;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .note {
            margin-top: 15px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đặt Lại Mật Khẩu</h1>
        
        <?php if ($err): ?>
            <div class="alert error"><?= htmlspecialchars($err) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="cid" value="<?= (int)$cid ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <label for="password">Mật Khẩu Mới</label>
                <input type="password" id="password" name="password" required placeholder="Ít nhất 6 ký tự">
            </div>

            <div class="form-group">
                <label for="confirm">Nhập Lại Mật Khẩu</label>
                <input type="password" id="confirm" name="confirm" required placeholder="Nhập lại mật khẩu">
            </div>

            <button type="submit">Cập Nhật Mật Khẩu</button>
        </form>

        <div class="note">
            <strong>Lưu ý:</strong> Liên kết này sẽ hết hạn sau 15 phút. Nếu bạn không cập nhật mật khẩu kịp thời, vui lòng yêu cầu một liên kết mới.
        </div>

        <div class="text-center">
            <p><a href="/">Về trang chủ</a></p>
        </div>
    </div>
</body>
</html>
