<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'reader') {
    header("Location: input002.php");
    exit;
}

$readerID = $_SESSION['id'] ?? '';

$host = "localhost";
$dbname = "library";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 處理還書請求
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loan_id'], $_POST['book_id'])) {
        $loanID = $_POST['loan_id'];
        $bookID = $_POST['book_id'];
        $today = date('Y-m-d');

        // 1️⃣ 先取得 DueDate
        $stmt = $pdo->prepare("SELECT DueDate FROM loan WHERE LoanID = :loan_id");
        $stmt->execute(['loan_id' => $loanID]);
        $dueDate = $stmt->fetchColumn();

        // 2️⃣ 計算罰金（每逾期一天 10 元）
        $overdueDays = max(0, (strtotime($today) - strtotime($dueDate)) / (60 * 60 * 24));
        $fine = $overdueDays * 10;

        // 3️⃣ 更新 Loan 表的 ReturnDate 與 Fine
        $updateLoan = $pdo->prepare("UPDATE loan SET ReturnDate = :today, Fine = :fine WHERE LoanID = :loan_id");
        $updateLoan->execute(['today' => $today, 'fine' => $fine, 'loan_id' => $loanID]);

        // 4️⃣ 更新 Book 狀態為可借
        $updateBook = $pdo->prepare("UPDATE book SET Status = '可借' WHERE BookID = :book_id");
        $updateBook->execute(['book_id' => $bookID]);

        echo "<script>alert('還書成功！罰金為：{$fine} 元'); location.href='return_book.php';</script>";
        exit;
    }

    // 查詢尚未還書的資料
    $stmt = $pdo->prepare("SELECT l.LoanID, l.BookID, b.Title FROM loan l 
                           JOIN book b ON l.BookID = b.BookID 
                           WHERE l.ReaderID = :reader_id AND l.ReturnDate IS NULL");
    $stmt->execute(['reader_id' => $readerID]);
    $books = $stmt->fetchAll();

} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📤 還書功能</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        form {
            display: inline;
        }
        .btn {
            padding: 6px 12px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #219150;
        }

        /* ✅ 返回按鈕樣式 */
        .back-btn {
            margin-top: 40px;
            text-align: center;
        }
        .back-btn button {
            background-color: #95a5a6;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .back-btn button:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>

<h2>📤 還書列表</h2>

<?php if (count($books) === 0): ?>
    <p>目前無需歸還的書籍。</p>
<?php else: ?>
    <table>
        <tr>
            <th>借書編號</th>
            <th>書籍編號</th>
            <th>書名</th>
            <th>操作</th>
        </tr>
        <?php foreach ($books as $book): ?>
        <tr>
            <td><?= htmlspecialchars($book['LoanID']) ?></td>
            <td><?= htmlspecialchars($book['BookID']) ?></td>
            <td><?= htmlspecialchars($book['Title']) ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="loan_id" value="<?= $book['LoanID'] ?>">
                    <input type="hidden" name="book_id" value="<?= $book['BookID'] ?>">
                    <button type="submit" class="btn">還書</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<!-- ✅ 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='input002.php'">🔙 返回主控台</button>
</div>

</body>
</html>
