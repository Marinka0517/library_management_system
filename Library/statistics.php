<?php
session_start();
$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'superadmin', 'librarian'])) {
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

    $totalBooks = $pdo->query("SELECT COUNT(*) FROM book")->fetchColumn();
    $totalLoans = $pdo->query("SELECT COUNT(*) FROM loan")->fetchColumn();
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📊 系統統計報表</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
            text-align: center;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            margin: 20px auto;
            width: 350px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .stat-icon {
            font-size: 36px;
            margin-bottom: 15px;
            color: #3498db;
        }
        .stat-title {
            font-size: 18px;
            color: #666;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
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

<h1>📊 系統統計報表</h1>

<div class="stat-card">
    <div class="stat-icon">📚</div>
    <div class="stat-title">總書籍數</div>
    <div class="stat-value"><?= $totalBooks ?></div>
</div>

<div class="stat-card">
    <div class="stat-icon">📖</div>
    <div class="stat-title">借閱紀錄總數</div>
    <div class="stat-value"><?= $totalLoans ?></div>
</div>

<!-- 返回主控台按鈕 -->
<div class="back-btn">
    <button onclick="location.href='admin_dashboard.php'">🔙 返回主控台</button>
</div>


</body>
</html>
