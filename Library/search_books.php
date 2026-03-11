<?php
session_start();

// 身分與名稱處理（預設為 guest 訪客）
$role = $_SESSION['role'] ?? 'guest';
$name = $_SESSION['user'] ?? '訪客';

$host = 'localhost';
$dbname = 'library';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $column = $_GET['column'] ?? 'Title';
    $keyword = $_GET['keyword'] ?? '';
    $results = [];

    if ($keyword !== '') {
        $sql = "SELECT * FROM book WHERE $column LIKE :keyword";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['keyword' => "%$keyword%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📚 查詢書籍</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f5f7fa;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        form {
            text-align: center;
            margin: 30px 0;
        }
        select, input[type="text"] {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button[type="submit"], .btn-green {
            padding: 10px 18px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }
        button[type="submit"]:hover, .btn-green:hover {
            background-color: #2980b9;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 14px;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .btn-green {
            background-color: #2ecc71;
        }
        .btn-disabled {
            background-color: #ccc;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
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

<h2>📚 查詢書籍</h2>

<form method="get">
    查詢欄位：
    <select name="column">
        <option value="Title" <?= $column === 'Title' ? 'selected' : '' ?>>書名 (Title)</option>
        <option value="Author" <?= $column === 'Author' ? 'selected' : '' ?>>作者 (Author)</option>
        <option value="Category" <?= $column === 'Category' ? 'selected' : '' ?>>分類 (Category)</option>
    </select>
    <input type="text" name="keyword" placeholder="請輸入關鍵字" value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit">查詢</button>
</form>

<?php if ($keyword !== ''): ?>
    <table>
        <tr>
            <th>書名</th>
            <th>作者</th>
            <th>分類</th>
            <th>狀態</th>
            <th>操作</th>
        </tr>
        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Title']) ?></td>
                    <td><?= htmlspecialchars($row['Author']) ?></td>
                    <td><?= htmlspecialchars($row['Category']) ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                    <td>
                        <?php if ($role === 'reader' && $row['Status'] === '可借'): ?>
                            <form method="post" action="borrow_from_reader.php" style="margin:0;">
                                <input type="hidden" name="book_id" value="<?= htmlspecialchars($row['BookID']) ?>">
                                <button type="submit" class="btn-green">借書</button>
                            </form>
                        <?php else: ?>
                            <button class="btn-disabled" disabled>瀏覽</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">❌ 查無符合的書籍</td></tr>
        <?php endif; ?>
    </table>
<?php endif; ?>

<!-- ✅ 返回主畫面按鈕（導向 input002.php） -->
<div class="back-btn">
    <button onclick="location.href='input002.php'">🔙 返回主畫面</button>
</div>

</body>
</html>
