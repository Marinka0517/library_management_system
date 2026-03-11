<?php
session_start();

$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['admin', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

if (empty($type) || empty($id)) {
    echo "<script>alert('❌ 參數錯誤'); history.back();</script>";
    exit;
}

$host = 'localhost';
$dbname = 'library';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($type === 'admin') {
        $stmt = $pdo->prepare("DELETE FROM admin WHERE AdminID = ?");
    } else {
        $stmt = $pdo->prepare("DELETE FROM reader WHERE ReaderID = ?");
    }

    $stmt->execute([$id]);

    // ✅ 無論哪種角色刪除成功都回 input002.php
    echo "<script>alert('✅ 刪除成功'); window.location.href='input002.php';</script>";
    exit;

} catch (PDOException $e) {
    echo "<script>alert('刪除失敗：". addslashes($e->getMessage()) ."'); history.back();</script>";
    exit;
}
?>
