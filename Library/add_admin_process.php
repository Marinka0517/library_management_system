<?php
session_start();
$role = $_SESSION['role'] ?? '';

// ✅ 僅允許 admin 或 superadmin 登入者執行
if (!in_array($role, ['admin', 'superadmin'])) {
    header("Location: input002.php");
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST["name"] ?? '';
        $account = $_POST["account"] ?? '';
        $password = $_POST["password"] ?? '';
        $role_new = $_POST["role"] ?? '';

        // ✅ 基本欄位檢查
        if ($name && $account && $password && $role_new) {

            // ✅ 檢查帳號是否重複
            $check = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE Account = ?");
            $check->execute([$account]);
            if ($check->fetchColumn() > 0) {
                echo "<script>alert('⚠️ 帳號已存在，請使用其他帳號'); history.back();</script>";
                exit;
            }

            // ✅ 產生新的 AdminID
            $stmt = $pdo->query("SELECT MAX(AdminID) FROM admin WHERE AdminID REGEXP '^A[0-9]{4}$'");
            $max_id = $stmt->fetchColumn();
            $new_number = $max_id ? intval(substr($max_id, 1)) + 1 : 1;
            $new_id = 'A' . str_pad($new_number, 4, '0', STR_PAD_LEFT);

            // ✅ 插入新帳號（此處密碼尚未加密，可再優化）
            $stmt = $pdo->prepare("INSERT INTO admin (AdminID, Name, Account, Password, Role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$new_id, $name, $account, $password, $role_new]);

            // ✅ 成功提示並跳轉
            echo "<script>alert('✅ 管理員新增成功！'); window.location.href='manage_admins.php';</script>";
            exit;
        } else {
            echo "<script>alert('⚠️ 請填寫所有欄位'); history.back();</script>";
            exit;
        }
    }
} catch (PDOException $e) {
    die("資料庫錯誤：" . $e->getMessage());
}
?>
