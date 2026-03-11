<?php
session_start();

// ✅ 檢查登入與角色
$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['librarian', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title    = trim($_POST['title']    ?? '');
    $author   = trim($_POST['author']   ?? '');
    $category = trim($_POST['category'] ?? '');

    if ($title && $author && $category) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 1. 取得目前最大的 BookID 編號 (去掉前綴 B 後轉數字)
            $stmt = $pdo->query(
                "SELECT MAX(CAST(SUBSTRING(BookID, 2) AS UNSIGNED)) AS max_id 
                 FROM book"
            );
            $max_id = (int)$stmt->fetchColumn();

            // 2. 下一筆的 BookID = 'B' + 四位數字
            $next_id = 'B' . str_pad($max_id + 1, 4, '0', STR_PAD_LEFT);

            // 3. 插入圖書 (含自動產生的 BookID)
            $stmt = $pdo->prepare(
                "INSERT INTO book (BookID, Title, Author, Category, Status)
                 VALUES (?, ?, ?, ?, '可借')"
            );
            $stmt->execute([$next_id, $title, $author, $category]);

            $message = "✅ 新增圖書成功：{$title}（ID：{$next_id}）";
        } catch (PDOException $e) {
            $message = "❌ 資料庫錯誤：" . $e->getMessage();
        }
    } else {
        $message = "⚠️ 請填寫所有欄位";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>➕ 新增圖書</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
        }
        h2 { color: #2c3e50; }
        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            width: 350px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover { background-color: #2980b9; }
        .message {
            text-align: center;
            color: #e74c3c;
            margin-top: 20px;
        }
        .back-btn {
            margin-top: 30px;
            display: flex;
            justify-content: center;
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

<h2 align="center">➕ 新增圖書</h2>

<form method="post">
    <label for="title">書名</label>
    <input type="text" id="title" name="title" required
           value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

    <label for="author">作者</label>
    <input type="text" id="author" name="author" required
           value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">

    <label for="category">分類</label>
    <input type="text" id="category" name="category" required
           value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">

    <button type="submit">新增圖書</button>
</form>

<?php if (!empty($message)): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php'">🔙 返回主控台</button>
</div>

</body>
</html>
