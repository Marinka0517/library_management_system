<?php
session_start();
$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['admin', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';
if (!in_array($type, ['admin', 'reader']) || empty($id)) {
    die("❌ 無效的參數");
}

$host = 'localhost';
$dbname = 'library';
$user = 'root';
$pass = '';
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$account = '';
$password = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_account = $_POST['account'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if ($new_account && $new_password) {
        if ($type === 'admin') {
            $stmt = $pdo->prepare("UPDATE admin SET Account = ?, Password = ? WHERE AdminID = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE reader SET Account = ?, Password = ? WHERE ReaderID = ?");
        }
        $stmt->execute([$new_account, $new_password, $id]);

        // ✅ 修改成功後固定跳回 input002.php
        echo "<script>alert('✅ 修改成功'); window.location.href = 'input002.php';</script>";
        exit;
    } else {
        $message = "⚠️ 請填寫所有欄位";
    }
} else {
    if ($type === 'admin') {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE AdminID = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM reader WHERE ReaderID = ?");
    }
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $account = $data['Account'];
        $password = $data['Password'];
    } else {
        die("❌ 查無資料");
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>✏️ 修改 <?= htmlspecialchars($type) ?> 帳號</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f5f5f5;
            padding: 50px;
        }
        .container {
            width: 420px;
            background-color: white;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #3498db;
            border: none;
            padding: 12px;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .msg {
            text-align: center;
            margin-top: 15px;
            color: #e74c3c;
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
<div class="container">
    <h2>✏️ 修改 <?= htmlspecialchars($type) ?> 帳號</h2>
    <form method="post">
        <label>帳號：</label>
        <input type="text" name="account" value="<?= htmlspecialchars($account) ?>" required>

        <label>密碼：</label>
        <input type="text" name="password" value="<?= htmlspecialchars($password) ?>" required>

        <button type="submit">確認修改</button>
    </form>
    <?php if ($message): ?>
        <p class="msg"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</div>

<!-- 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='input002.php'">🔙 返回主控台</button>
</div>

</body>
</html>
