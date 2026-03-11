<?php
session_start();
$role = $_SESSION['role'] ?? '';
$name = $_SESSION['user'] ?? '';

// ✅ 管理者（admin / superadmin / librarian）登入時應該跳轉到 admin_dashboard
if (in_array($role, ['admin', 'superadmin', 'librarian'])) {
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📚 圖書資訊系統 Library System</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .button-container {
            text-align: center;
            margin-top: 80px;
        }
        button {
            padding: 12px 24px;
            margin: 15px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #3498db;
            color: white;
        }
        button:hover {
            background-color: #2980b9;
        }
        .login-popup {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -20%);
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        .popup-header {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 12px;
            text-align: left;
        }
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .popup-buttons {
            text-align: right;
        }
    </style>
</head>
<body>
<header>
    <div><strong>📚 圖書資訊系統 Library System</strong></div>
    <div>
        <?php if (!$role): ?>
            <button onclick="document.getElementById('loginPopup').style.display='block'">登入</button>
        <?php else: ?>
            <a href="logout.php"><button>登出</button></a>
        <?php endif; ?>
    </div>
</header>

<div class="button-container">
    <form action="search_books.php" method="get" style="display:inline;">
        <button type="submit">🔍 查詢書籍</button>
    </form>

    <?php if ($role === 'reader'): ?>
        <form action="borrow_book.php" method="get" style="display:inline;">
            <button type="submit">📥 借書</button>
        </form>
        <form action="return_book.php" method="get" style="display:inline;">
            <button type="submit">📤 還書</button>
        </form>
        <form action="my_fines.php" method="get" style="display:inline;">
            <button type="submit">📋 查詢我的罰金</button>
        </form>
        <form action="edit_profile.php" method="get" style="display:inline;">
            <button type="submit">✏️ 修改個人資料</button>
        </form>
    <?php endif; ?>
</div>

<!-- 登入彈窗 -->
<div id="loginPopup" class="login-popup">
    <form action="login.php" method="post" autocomplete="off">
        <div class="popup-header">🔐 登入系統</div>
        <div class="form-group">
            <label>身分</label>
            <select name="role" required>
                <option value="">請選擇</option>
                <option value="reader">讀者 Reader</option>
                <option value="admin">📋 一般管理者 (Admin)</option>
                <option value="librarian">📗 館員 (Librarian)</option>
                <option value="superadmin">👑 系統管理者 (SuperAdmin)</option>
            </select>
        </div>
        <div class="form-group">
            <label>帳號</label>
            <input type="text" name="account" required>
        </div>
        <div class="form-group">
            <label>密碼</label>
            <input type="password" name="password" required>
        </div>
        <div class="popup-buttons">
            <button type="submit">登入</button>
            <button type="button" onclick="document.getElementById('loginPopup').style.display='none'">取消</button>
        </div>
    </form>
</div>

<script>
    // 快捷鍵 ESC 關閉登入彈窗
    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('loginPopup').style.display = 'none';
        }
    });
</script>
</body>
</html>
