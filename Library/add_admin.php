<?php
session_start();
$role = $_SESSION['role'] ?? '';
if ($role !== 'superadmin') {
    header("Location: input002.php");
    exit;
}

// 取得輸入值
$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';
$adminRole = $_POST['role'] ?? '';

if (!$account || !$password || !$adminRole) {
    echo "<script>alert('請完整填寫資料'); history.back();</script>";
    exit;
}

$host = 'localhost';
$dbname = 'library';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 產生 AdminID：例如 A0006（自動接續）
    $stmt = $pdo->query("SELECT AdminID FROM admin ORDER BY AdminID DESC LIMIT 1");
    $lastId = $stmt->fetchColumn();
    $newIdNum = $lastId ? intval(substr($lastId, 1)) + 1 : 1;
    $newAdminID = "A" . str_pad($newIdNum, 4, "0", STR_PAD_LEFT);

    // 插入資料
    $insert = $pdo->prepare("INSERT INTO admin (AdminID, Name, Account, Password, Role)
                             VALUES (?, ?, ?, ?, ?)");
    $insert->execute([$newAdminID, $account, $account, $password, $adminRole]);

    echo "<script>alert('新增成功'); window.location.href='manage_admins.php';</script>";
    exit;

} catch (PDOException $e) {
    echo "新增失敗：" . $e->getMessage();
    exit;
}
?>
