<?php
session_start();

// ✅ 如果網址中帶有 ?clear=1，就清空之前輸入的 ReaderID
if (isset($_GET['clear']) && $_GET['clear'] == 1) {
    unset($_SESSION['borrow_reader_id']);
}

// ✅ 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $readerID = $_POST['reader_id'] ?? '';
    if (!empty($readerID)) {
        $_SESSION['borrow_reader_id'] = $readerID;
        header("Location: borrow_book_admin.php");
        exit;
    } else {
        $message = "⚠️ 請輸入讀者 ID";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📥 輸入讀者 ID</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background: #f9f9f9;
            padding: 40px;
        }
        form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            width: 320px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #2980b9;
        }
        .msg {
            text-align: center;
            color: red;
            margin-top: 15px;
        }
        .back-btn {
            text-align: center;
            margin-top: 30px;
        }
        .back-btn button {
            background-color: #95a5a6;
            padding: 10px 24px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: auto;
            min-width: 160px;
        }
        .back-btn button:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>

<h2>📥 請輸入讀者 ID</h2>

<form method="post">
    <input type="text" name="reader_id" placeholder="例如：R0001" required>
    <button type="submit">下一步</button>
</form>

<?php if (!empty($message)): ?>
    <p class="msg"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- ✅ 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php?clear=1'">🔙 返回</button>
</div>

</body>
</html>
