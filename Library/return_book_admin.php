<?php
session_start();

$role = $_SESSION['role'] ?? '';
$admin_id = $_SESSION['user'] ?? '';

if (!in_array($role, ['librarian', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reader_id = $_POST['reader_id'] ?? '';
    $book_id = $_POST['book_id'] ?? '';
    $return_date = date("Y-m-d");

    if ($reader_id && $book_id) {
        // 查找未還的紀錄
        $stmt = $pdo->prepare("SELECT LoanID FROM loan 
                               WHERE ReaderID = ? AND BookID = ? AND ReturnDate IS NULL 
                               ORDER BY BorrowDate DESC LIMIT 1");
        $stmt->execute([$reader_id, $book_id]);
        $loan_id = $stmt->fetchColumn();

        if ($loan_id) {
            // 更新還書日期
            $stmt = $pdo->prepare("UPDATE loan SET ReturnDate = ? WHERE LoanID = ?");
            $stmt->execute([$return_date, $loan_id]);

            // ✅ 正確更新書籍狀態為「可借」
            $stmt = $pdo->prepare("UPDATE book SET Status = '可借' WHERE BookID = ?");
            $stmt->execute([$book_id]);

            $message = "✅ 還書完成！";
        } else {
            $message = "❌ 查無該讀者尚未歸還此書的紀錄";
        }
    } else {
        $message = "⚠️ 請輸入完整資料";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📤 館員還書</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f7f7f7;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        form {
            background: white;
            padding: 30px;
            max-width: 400px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        /* 主要按鈕：藍色 */
        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;   /* 藍色 */
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;   /* 深一點的藍色 */
        }

        /* 系統訊息也改成藍色 */
        .message {
            text-align: center;
            color: #3498db;
            margin-top: 20px;
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

<h2>📤 館員還書</h2>

<form method="post">
    <label>ReaderID:</label>
    <input type="text" name="reader_id" required>

    <label>書籍 ID:</label>
    <input type="text" name="book_id" required>

    <button type="submit">還書</button>
</form>

<?php if ($message): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php'">🔙 返回主控台</button>
</div>

</body>
</html>
