<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'reader') {
    header("Location: input002.php");
    exit;
}

$readerID = $_SESSION['id'] ?? '';

$pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 取得原始資料
$stmt = $pdo->prepare("SELECT * FROM reader WHERE ReaderID = ?");
$stmt->execute([$readerID]);
$data = $stmt->fetch();

// 更新資料
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $account = $_POST['account'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if ($account && $password) {
        $update = $pdo->prepare("UPDATE reader SET Account = ?, Password = ?, Email = ?, Phone = ? WHERE ReaderID = ?");
        $update->execute([$account, $password, $email, $phone, $readerID]);
        $message = "✅ 資料更新成功！";
        $_SESSION['user'] = $account;
        $stmt->execute([$readerID]);
        $data = $stmt->fetch();
    } else {
        $message = "⚠️ 帳號與密碼不可為空";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>✏️ 修改個人資料</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
        }
        form {
            width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .message {
            text-align: center;
            color: green;
            margin-top: 20px;
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

<h2>✏️ 修改個人資料</h2>

<form method="post">
    <label>讀者 ID（不可修改）</label>
    <input type="text" value="<?= htmlspecialchars($data['ReaderID']) ?>" readonly>

    <label>姓名（不可修改）</label>
    <input type="text" value="<?= htmlspecialchars($data['Name']) ?>" readonly>

    <label>帳號</label>
    <input type="text" name="account" value="<?= htmlspecialchars($data['Account']) ?>" required>

    <label>密碼</label>
    <input type="password" name="password" value="<?= htmlspecialchars($data['Password']) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($data['Email']) ?>">

    <label>電話</label>
    <input type="tel" name="phone" value="<?= htmlspecialchars($data['Phone']) ?>">

    <button type="submit">💾 儲存變更</button>
</form>

<?php if (!empty($message)): ?>
    <p class="message"><?= $message ?></p>
<?php endif; ?>

<!-- ✅ 正常返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='input002.php'">🔙 返回主控台</button>
</div>

</body>
</html>
