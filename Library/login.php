<?php
session_start();

// 取得登入表單資料
$role = strtolower($_POST['role'] ?? '');
$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';

// 檢查欄位是否填寫
if (empty($role) || empty($account) || empty($password)) {
    echo "<script>alert('⚠️ 請填寫所有欄位'); history.back();</script>";
    exit;
}

// 資料庫連線資訊
$host = 'localhost';
$dbname = 'library';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 根據角色查詢帳號
    if ($role === 'reader') {
        $stmt = $pdo->prepare("SELECT * FROM reader WHERE account = :account AND password = :password");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE account = :account AND password = :password AND LOWER(role) = :role");
        $stmt->bindParam(':role', $role);
    }

    $stmt->bindParam(':account', $account);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // 查詢成功
    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ✅ 記錄必要的 session
        $_SESSION['role'] = $role;

        if ($role === 'reader') {
            $_SESSION['id'] = $user['ReaderID'];                       // ✅ 借書用
            $_SESSION['user'] = $user['Name'] ?? $user['account'] ?? '讀者';
            header("Location: input002.php");
        } else {
            $_SESSION['id'] = $user['AdminID'];
            $_SESSION['user'] = $user['Name'] ?? $user['account'] ?? '管理者';
            header("Location: admin_dashboard.php");
        }
        exit;

    } else {
        echo "<script>alert('❌ 登入失敗：帳號、密碼或身份錯誤'); history.back();</script>";
        exit;
    }

} catch (PDOException $e) {
    echo "<script>alert('❌ 資料庫錯誤：" . $e->getMessage() . "');</script>";
    exit;
}
?>
