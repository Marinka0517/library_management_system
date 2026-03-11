<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'reader') {
    header("Location: input002.php");
    exit;
}

$readerID = $_SESSION['id'] ?? '';

$host = "localhost";
$dbname = "library";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT l.BookID, b.Title, l.BorrowDate, l.DueDate, l.ReturnDate, l.Fine
        FROM loan l
        JOIN book b ON l.BookID = b.BookID
        WHERE l.ReaderID = :reader_id AND l.ReturnDate IS NOT NULL
        ORDER BY l.ReturnDate DESC
    ");
    $stmt->execute(['reader_id' => $readerID]);
    $records = $stmt->fetchAll();

    $hasFine = false;
    foreach ($records as $r) {
        if ($r['Fine'] > 0) {
            $hasFine = true;
            break;
        }
    }

} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📋 我的罰金紀錄</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 12px 20px;
            font-size: 20px;
        }
        h2 {
            color: #2c3e50;
            margin-top: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        td {
            background-color: #fafafa;
        }
        .fine-red {
            color: red;
            font-weight: bold;
        }
        .fine-green {
            color: green;
            font-weight: bold;
        }
        .back-btn {
            margin-top: 30px;
            text-align: center;
        }
        .back-btn button {
            background-color: #3498db;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .back-btn button:hover {
            background-color: #2980b9;
        }
        .img-center {
            text-align: center;
            margin-top: 20px;
        }
        .img-center img {
            width: 300px;
            max-width: 90%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<header>📋 我的罰金紀錄</header>

<?php if (count($records) === 0): ?>
    <h2>✅ 目前沒有任何罰金紀錄！</h2>
    <div class="img-center">
        <img src="img/nofine.gif" alt="無紀錄圖">
        <p>今天錢包很安全 😎</p>
    </div>

<?php else: ?>

    <?php if ($hasFine): ?>
        <div class="img-center">
            <img src="img/fine.gif" alt="有罰金圖">
            <p>你最近有在還書喔～ 💸</p>
        </div>
    <?php else: ?>
        <div class="img-center">
            <img src="img/nofine.gif" alt="準時歸還圖">
            <p>📢 所有書都準時還，太棒了！錢包安全啦～ 🧧</p>
        </div>
    <?php endif; ?>

    <h2>📚 歷史還書與罰金明細</h2>
    <table>
        <tr>
            <th>書籍編號</th>
            <th>書名</th>
            <th>借書日期</th>
            <th>應還日期</th>
            <th>實際還書</th>
            <th>罰金（元）</th>
        </tr>
        <?php foreach ($records as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['BookID']) ?></td>
            <td><?= htmlspecialchars($r['Title']) ?></td>
            <td><?= $r['BorrowDate'] ?></td>
            <td><?= $r['DueDate'] ?></td>
            <td><?= $r['ReturnDate'] ?></td>
            <td class="<?= $r['Fine'] > 0 ? 'fine-red' : 'fine-green' ?>">
                <?= $r['Fine'] ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<div class="back-btn">
    <button onclick="location.href='input002.php'">🔙 返回主控台</button>
</div>

</body>
</html>
