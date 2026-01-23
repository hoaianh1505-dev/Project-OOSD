<?php
session_start();
require '../config.php';
require '../connectDB.php';
require '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    // Thông báo chung để tránh lộ email có tồn tại hay không
    $genericMsg = "Nếu email tồn tại trong hệ thống, bạn sẽ nhận được liên kết đặt lại mật khẩu trong 15 phút.";

    // Kiểm tra email hợp lệ
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = $genericMsg;
        header("Location: forgot_password.php");
        exit;
    }

    // Tìm customer theo email
    $customerRepository = new CustomerRepository();
    $customer = $customerRepository->findEmail($email);

    if ($customer) {
        // Tạo token random
        $rawToken = bin2hex(random_bytes(32)); // 64 hex chars
        $tokenHash = password_hash($rawToken, PASSWORD_DEFAULT);
        $expiresAt = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // Lưu token vào database
        $sql = "INSERT INTO password_resets(customer_id, token_hash, expires_at) 
                VALUES ('{$customer->getId()}', '{$tokenHash}', '{$expiresAt}')";
        
        if ($conn->query($sql) === TRUE) {
            // Tạo link reset
            $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            $resetLink = $baseUrl . "/reset_password.php?token=" . urlencode($rawToken) . "&cid=" . $customer->getId();

            // Gửi email
            $subject = "Đặt lại mật khẩu";
            $html = "
                <h2>Yêu cầu đặt lại mật khẩu</h2>
                <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>
                <p>Nhấn vào liên kết dưới đây để tiếp tục (hết hạn sau 15 phút):</p>
                <p><a href='{$resetLink}' style='color: #007bff; font-weight: bold;'>{$resetLink}</a></p>
                <p>Nếu không phải bạn yêu cầu này, vui lòng bỏ qua email này.</p>
                <hr>
                <p style='font-size: 12px; color: #999;'>Đây là email tự động, vui lòng không trả lời.</p>
            ";

            $emailService = new EmailService();
            $emailService->send($customer->getEmail(), $subject, $html);
        }
    }

    $_SESSION['msg'] = $genericMsg;
    header("Location: forgot_password.php");
    exit;
}

$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
        .msg {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        .msg.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="email"]:focus {
            outline: none;
            border-color: #667eea;
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
        }
        a {
            color: #667eea;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quên Mật Khẩu</h1>
        
        <?php if ($msg): ?>
            <div class="msg success"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Nhập Email Của Bạn</label>
                <input type="email" id="email" name="email" required placeholder="abc@example.com">
            </div>
            <button type="submit">Gửi Liên Kết Reset</button>
        </form>

        <div class="text-center">
            <p>Nhớ mật khẩu? <a href="/">Đăng nhập</a></p>
        </div>
    </div>
</body>
</html>
