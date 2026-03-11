<?php
session_start();

$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['librarian', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$book = null;
$message = "";

// 若為載入資料
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $stmt = $pdo->prepare("SELECT * FROM book WHERE BookID = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        $message = "❌ 查無此書籍 ID";
    }
}

// 若為更新資料
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? '';

    if ($book_id && $title && $author && $category && $status !== '') {
        $stmt = $pdo->prepare("UPDATE book SET Title = ?, Author = ?, Category = ?, Status = ? WHERE BookID = ?");
        $stmt->execute([$title, $author, $category, $status, $book_id]);

        echo "<script>alert('✅ 書籍資料更新成功！'); window.location.href = 'admin_dashboard.php';</script>";
        exit;
    } else {
        $message = "⚠️ 請填寫所有欄位";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>🛠️ 修改書籍資料</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f2f2f2;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 {
            color: #2c3e50;
        }
        form {
            background: #fff;
            padding: 25px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .message {
            text-align: center;
            color: red;
            font-weight: bold;
        }
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

<h2>🛠️ 修改書籍資料</h2>

<!-- 查詢書籍ID -->
<form method="get">
    <label>請輸入書籍 ID</label>
    <input type="text" name="book_id" required>
    <button type="submit">載入書籍資料</button>
</form>

<?php if ($message): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- 編輯表單 -->
<?php if ($book): ?>
<form method="post">
    <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['BookID']) ?>">

    <label>書名</label>
    <input type="text" name="title" value="<?= htmlspecialchars($book['Title']) ?>" required>

    <label>作者</label>
    <input type="text" name="author" value="<?= htmlspecialchars($book['Author']) ?>" required>

    <label>分類</label>
    <input type="text" name="category" value="<?= htmlspecialchars($book['Category']) ?>" required>

    <label>狀態</label>
    <select name="status" required>
        <option value="可借" <?= $book['Status'] === '可借' ? 'selected' : '' ?>>可借</option>
        <option value="已借出" <?= $book['Status'] === '已借出' ? 'selected' : '' ?>>已借出</option>
        <option value="遺失" <?= $book['Status'] === '遺失' ? 'selected' : '' ?>>遺失</option>
    </select>

    <button type="submit">儲存修改</button>
</form>
<?php endif; ?>

<!-- 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php'">🔙 返回主控台</button>
</div>

</body>
</html>
