<?php
session_start();

$role = $_SESSION['role'] ?? '';
$name = $_SESSION['user'] ?? '';

// ✅ Admin 與 SuperAdmin 可進入
if (!in_array($role, ['admin', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

// 資料庫連線
$host = 'localhost';
$dbname = 'library';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $admins = $pdo->query("SELECT * FROM admin")->fetchAll(PDO::FETCH_ASSOC);
    $readers = $pdo->query("SELECT * FROM reader")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>🧾 帳號密碼管理</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }
        h2 {
            border-left: 8px solid #3498db;
            padding-left: 10px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        td {
            background-color: #fff;
        }
        .actions a {
            margin-right: 10px;
            color: #e74c3c;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .back-btn {
            text-align: center;
            margin-top: 30px;
        }
        .back-btn button {
            background-color: #95a5a6;
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .back-btn button:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>

<h2>📑 管理者帳號</h2>
<table>
    <tr>
        <th>帳號</th>
        <th>角色</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['Account']) ?></td>
            <td><?= htmlspecialchars($a['Role']) ?></td>
            <td class="actions">
                <a href="edit_account.php?type=admin&id=<?= $a['AdminID'] ?>">修改</a>
                <a href="delete_account.php?type=admin&id=<?= $a['AdminID'] ?>" onclick="return confirm('確定要刪除？')">刪除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>🟩 讀者帳號</h2>
<table>
    <tr>
        <th>帳號</th>
        <th>密碼</th>
        <th>操作</th>
    </tr>
    <?php foreach ($readers as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['Account']) ?></td>
            <td><?= htmlspecialchars($r['Password']) ?></td>
            <td class="actions">
                <a href="edit_account.php?type=reader&id=<?= $r['ReaderID'] ?>">修改</a>
                <a href="delete_account.php?type=reader&id=<?= $r['ReaderID'] ?>" onclick="return confirm('確定要刪除？')">刪除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- ✅ 返回主畫面 -->
<div class="back-btn">
    <button onclick="location.href='input002.php'">🔙 返回主畫面</button>
</div>

</body>
</html>
