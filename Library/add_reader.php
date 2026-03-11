<?php
session_start();

// ✅ 檢查登入與角色
$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['librarian', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $account = $_POST['account'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($name && $account && $password) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // ✅ 檢查帳號是否已存在
            $stmt = $pdo->prepare("SELECT * FROM reader WHERE account = ?");
            $stmt->execute([$account]);

            if ($stmt->rowCount() > 0) {
                $message = "⚠️ 此帳號已存在，請重新輸入";
            } else {
                // ✅ 自動產生 ReaderID（格式 U1001、U1002...）
                $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(ReaderID, 2) AS UNSIGNED)) AS max_id FROM reader");
                $max_id = $stmt->fetchColumn();
                $next_id = 'R' . str_pad(($max_id + 1), 4, '0', STR_PAD_LEFT);

                // ✅ 插入讀者帳號
                $stmt = $pdo->prepare("INSERT INTO reader (ReaderID, Name, Account, Password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$next_id, $name, $account, $password]);

                $message = "✅ 讀者新增成功，ID 為：{$next_id}";
            }
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
    <title>➕ 新增讀者</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
        }
        h2 {
            color: #2c3e50;
        }
        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            width: 350px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
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
        }
        button:hover {
            background-color: #2980b9;
        }
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

<h2 align="center">➕ 新增讀者帳號</h2>

<form method="post">
    <label>姓名</label>
    <input type="text" name="name" required>

    <label>帳號</label>
    <input type="text" name="account" required>

    <label>密碼</label>
    <input type="password" name="password" required>

    <button type="submit">新增讀者</button>
</form>

<?php if (!empty($message)): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php'">🔙 返回主控台</button>
</div>

</body>
</html>
