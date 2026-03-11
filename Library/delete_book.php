<?php
session_start();
$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['librarian', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'] ?? '';
    if ($book_id) {
        $stmt = $pdo->prepare("DELETE FROM book WHERE BookID = ?");
        $stmt->execute([$book_id]);
        $message = "✅ 書籍已成功刪除！";
    } else {
        $message = "⚠️ 請輸入書籍 ID";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>🗑️ 刪除書籍</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            background: #fff;
            padding: 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        button {
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            color: #27ae60;
            font-weight: bold;
        }
        .back-btn {
            margin-top: 30px;
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

<h2>🗑️ 刪除書籍</h2>

<form method="post">
    <label for="book_id">書籍 ID：</label>
    <input type="text" name="book_id" id="book_id" required>
    <button type="submit">刪除書籍</button>
</form>

<?php if ($message): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php'">🔙 返回主控台</button>
</div>

</body>
</html>
