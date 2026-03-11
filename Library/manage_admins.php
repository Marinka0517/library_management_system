<?php
session_start();
$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

$host = 'localhost';
$dbname = 'library';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $admins = $pdo->query("SELECT * FROM admin")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>👥 管理員帳號維護</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
        }
        h2 {
            border-left: 6px solid #3498db;
            padding-left: 10px;
            color: #2c3e50;
        }
        .form-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 18px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #2980b9;
        }
        .success-msg {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            width: 400px;
            margin: 0 auto 20px auto;
            text-align: center;
        }
        table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            background-color: white;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .action-link {
            color: #3498db;
            text-decoration: none;
            margin: 0 5px;
        }
        .action-link:hover {
            text-decoration: underline;
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

<h2>👤 新增管理員帳號</h2>

<?php if ($success === '1'): ?>
    <div class="success-msg">✅ 管理員新增成功！</div>
<?php endif; ?>

<div class="form-container">
    <form method="post" action="add_admin_process.php">
        <label>姓名：</label>
        <input type="text" name="name" required>

        <label>帳號：</label>
        <input type="text" name="account" required>

        <label>密碼：</label>
        <input type="password" name="password" required>

        <label>角色：</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="superadmin">SuperAdmin</option>
            <option value="librarian">Librarian</option>
        </select>

        <button type="submit">➕ 新增管理員</button>
    </form>
</div>

<h2>📑 管理者帳號</h2>
<table>
    <tr>
        <th>姓名</th>
        <th>帳號</th>
        <th>角色</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin): ?>
        <tr>
            <td><?= htmlspecialchars($admin['Name']) ?></td>
            <td><?= htmlspecialchars($admin['Account']) ?></td>
            <td><?= htmlspecialchars($admin['Role']) ?></td>
            <td>
                <a class="action-link" href="edit_account.php?type=admin&id=<?= $admin['AdminID'] ?>">修改</a> |
                <a class="action-link" href="delete_account.php?type=admin&id=<?= $admin['AdminID'] ?>" onclick="return confirm('確定要刪除？')">刪除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php'">🔙 返回主控台</button>
</div>

</body>
</html>
