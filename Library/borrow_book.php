<?php
session_start();

$role = $_SESSION['role'] ?? '';
$userID = $_SESSION['id'] ?? '';
$userName = $_SESSION['user'] ?? '';

// Admin 借書時需輸入讀者 ID
$readerID = '';
if ($role === 'reader') {
    $readerID = $userID;
} elseif (in_array($role, ['librarian', 'superadmin'])) {
    $readerID = $_POST['reader_id'] ?? '';
} else {
    header("Location: input002.php");
    exit;
}

$adminID = ($role === 'reader') ? 'A0001' : $userID;

$host = 'localhost';
$dbname = 'library';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
        if (empty($readerID)) {
            echo "<script>alert('❌ 請輸入讀者ID'); history.back();</script>";
            exit;
        }

        $bookID = $_POST['book_id'];
        $borrowDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+7 days'));

        $stmt = $pdo->query("SELECT LoanID FROM loan ORDER BY LoanID DESC LIMIT 1");
        $lastID = $stmt->fetchColumn();
        $nextNum = $lastID ? intval(substr($lastID, 1)) + 1 : 1;
        $newLoanID = 'L' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $stmt = $pdo->prepare("INSERT INTO loan (LoanID, BookID, ReaderID, AdminID, BorrowDate, DueDate, ReturnDate, Fine)
                               VALUES (?, ?, ?, ?, ?, ?, NULL, 0)");
        $stmt->execute([$newLoanID, $bookID, $readerID, $adminID, $borrowDate, $dueDate]);

        $pdo->prepare("UPDATE book SET Status = '已借出' WHERE BookID = ?")->execute([$bookID]);

        echo "<script>alert('✅ 借閱成功！'); window.location='borrow_book.php';</script>";
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
    <title>📥 借書 Borrow Book</title>
    <style>
        body { font-family: "Microsoft JhengHei", sans-serif; background-color: #f9f9f9; padding: 20px; }
        h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: center; }
        th { background-color: #3498db; color: white; }
        form { margin: 0; }
        button { padding: 6px 12px; background-color: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #219150; }
        .back-btn { margin-top: 40px; text-align: center; }
        .back-btn button { background-color: #95a5a6; padding: 10px 20px; font-size: 16px; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .back-btn button:hover { background-color: #7f8c8d; }
    </style>
</head>
<body>
    <h2>📥 借書 Borrow Book - 歡迎 <?= htmlspecialchars($userName) ?>（<?= $role ?>）</h2>

    <?php if ($role !== 'reader'): ?>
    <form method="post">
        <label for="reader_id">請輸入讀者ID：</label>
        <input type="text" name="reader_id" id="reader_id" required value="<?= htmlspecialchars($readerID) ?>">
        <br><br>
    <?php endif; ?>

    <?php if (count($books) > 0): ?>
        <table>
            <tr>
                <th>書籍編號</th>
                <th>書名</th>
                <th>作者</th>
                <th>分類</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['BookID']) ?></td>
                    <td><?= htmlspecialchars($book['Title']) ?></td>
                    <td><?= htmlspecialchars($book['Author']) ?></td>
                    <td><?= htmlspecialchars($book['Category']) ?></td>
                    <td><?= htmlspecialchars($book['Status']) ?></td>
                    <td>
                        <form method="post" action="borrow_book.php">
                            <?php if ($role !== 'reader'): ?>
                                <input type="hidden" name="reader_id" value="<?= htmlspecialchars($readerID) ?>">
                            <?php endif; ?>
                            <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['BookID']) ?>">
                            <button type="submit">借閱</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>📚 目前沒有可借閱的書籍。</p>
    <?php endif; ?>

    <?php if ($role !== 'reader'): ?>
    </form>
    <?php endif; ?>

    <div class="back-btn">
        <button onclick="location.href='input002.php'">🔙 返回主控台</button>
    </div>
</body>
</html>
