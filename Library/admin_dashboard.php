<?php
session_start();

$role = strtolower($_SESSION['role'] ?? '');
$name = $_SESSION['user'] ?? '';

if (!in_array($role, ['admin', 'superadmin', 'librarian'])) {
    header("Location: input002.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>🔧 管理者後台</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        h2 {
            margin-bottom: 30px;
        }
        button {
            margin: 10px;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #3498db;
            color: white;
        }
        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<header>
    <div><strong>🔧 管理者後台 | (<?= htmlspecialchars($role) ?>)</strong></div>
    <div>
        <a href="logout.php"><button>登出</button></a>
    </div>
</header>

<div class="content">
    <h2>📋 功能選單</h2>

    <button onclick="location.href='search_books.php'">🔍 查詢書籍</button>

    <?php if (in_array($role, ['librarian', 'superadmin'])): ?>
        <button onclick="location.href='add_reader.php'">➕ 新增讀者</button>
        <button onclick="location.href='add_book.php'">➕ 新增圖書</button>
        <button onclick="location.href='edit_book.php'">🛠️ 修改書籍資料</button>
        <button onclick="location.href='delete_book.php'">🗑️ 刪除書籍</button>
        <br>
        <a href="borrow_input_reader.php"><button>📥 借書（輸入 ReaderID）</button></a>
        <button onclick="location.href='return_book_admin.php'">📤 還書（輸入 ReaderID）</button>
    <?php endif; ?>

    <?php if (in_array($role, ['superadmin', 'admin'])): ?>
        <button onclick="location.href='manage_accounts.php'">🧾 帳號密碼管理</button>
        <button onclick="location.href='statistics.php'">📊 系統統計報表</button>
        <button onclick="location.href='manage_admins.php'">👥 管理員維護</button>
    <?php endif; ?>
</div>

</body>
</html>
