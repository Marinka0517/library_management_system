<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// ✅ 驗證登入身份
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'superadmin', 'librarian'])) {
    echo "<script>alert('⚠️ 尚未登入或權限不足'); window.location.href='login.php';</script>";
    exit;
}

// ✅ 管理員 ID（預設為 A0001）
$admin_id = $_SESSION['id'] ?? 'A0001';

// ✅ 優先讀 POST 的 reader_id，否則使用 session 中的
if (isset($_POST['reader_id'])) {
    $_SESSION['borrow_reader_id'] = $_POST['reader_id'];
}
$reader_id = $_SESSION['borrow_reader_id'] ?? '';

// ❗ 若讀者 ID 不存在，強制返回輸入頁
if (empty($reader_id)) {
    echo "<script>alert('⚠️ 尚未輸入讀者 ID'); window.location.href='borrow_input_reader.php';</script>";
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ 如果有送出借書請求
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_ids']) && is_array($_POST['book_ids'])) {
        $borrowDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+7 days'));

        foreach ($_POST['book_ids'] as $bookID) {
            $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(LoanID, 2) AS UNSIGNED)) AS max_id FROM loan");
            $max_id = $stmt->fetchColumn();
            $newLoanID = 'L' . str_pad(($max_id + 1), 4, '0', STR_PAD_LEFT);

            $stmt = $pdo->prepare("INSERT INTO loan (LoanID, BookID, ReaderID, AdminID, BorrowDate, DueDate, ReturnDate, Fine)
                                   VALUES (?, ?, ?, ?, ?, ?, NULL, 0)");
            $stmt->execute([$newLoanID, $bookID, $reader_id, $admin_id, $borrowDate, $dueDate]);

            $pdo->prepare("UPDATE book SET Status = '已借出' WHERE BookID = ?")->execute([$bookID]);
        }

        echo "<script>alert('✅ 借書成功！'); window.location.href='borrow_input_reader.php';</script>";
        exit;
    }

    $stmt = $pdo->query("SELECT * FROM book WHERE Status = '可借'");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ 資料庫連線失敗: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📥 管理員借書</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        h2 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        button, input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            min-width: 160px;
        }
        input[type="submit"] {
            background-color: #27ae60;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #219150;
        }
        .back-btn {
            background-color: #95a5a6;
            color: white;
        }
        .back-btn:hover {
            background-color: #7f8c8d;
        }
        .action-area {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>📥 管理員代借書 - 讀者 ID：<?= htmlspecialchars($reader_id) ?></h2>

    <?php if (count($books) > 0): ?>
    <form method="post">
        <input type="hidden" name="reader_id" value="<?= htmlspecialchars($reader_id) ?>">
        <table>
            <tr>
                <th>選取</th>
                <th>書籍編號</th>
                <th>書名</th>
                <th>作者</th>
                <th>分類</th>
            </tr>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><input type="checkbox" name="book_ids[]" value="<?= htmlspecialchars($book['BookID']) ?>"></td>
                <td><?= htmlspecialchars($book['BookID']) ?></td>
                <td><?= htmlspecialchars($book['Title']) ?></td>
                <td><?= htmlspecialchars($book['Author']) ?></td>
                <td><?= htmlspecialchars($book['Category']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="action-area">
            <input type="submit" value="📚 確認借閱">
            <button type="button" class="back-btn" onclick="window.location.href='clear_and_redirect.php'">🔙 返回</button>
        </div>
    </form>
    <?php else: ?>
        <p>📚 目前沒有可借閱的書籍。</p>
    <?php endif; ?>
</body>
</html>
