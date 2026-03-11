<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (isset($_GET['debug'])) {
    echo "<pre>🔍 DEBUG 模式：
_POST:\n";
    print_r($_POST);
    echo "\n_SESSION:\n";
    print_r($_SESSION);
    echo "</pre>";
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'reader') {
    echo "<script>alert('⚠️ 非讀者身份或尚未登入'); window.location.href='login.php';</script>";
    exit;
}

$reader_id = $_SESSION['id'] ?? '';
$book_id = $_POST['book_id'] ?? '';

if (empty($reader_id) || empty($book_id)) {
    echo "<script>alert('⚠️ 請提供完整資訊'); history.back();</script>";
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=library;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM book WHERE BookID = ? AND Status = '可借'");
    $stmt->execute([$book_id]);

    if ($stmt->rowCount() === 0) {
        echo "<script>alert('❌ 此書目前不可借'); window.location.href='search_books.php';</script>";
        exit;
    }

    $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(LoanID, 2) AS UNSIGNED)) AS max_id FROM loan");
    $max_id = $stmt->fetchColumn();
    $new_id = 'L' . str_pad(($max_id + 1), 4, '0', STR_PAD_LEFT);

    $today = date("Y-m-d");
    $due = date("Y-m-d", strtotime("+7 days"));
    $admin_id = 'A0001'; // 請確認這個 admin 存在
    $fine = 0;

    // ✅ 寫入完整欄位（ReturnDate 寫 NULL）
    $stmt = $pdo->prepare("INSERT INTO loan (LoanID, BookID, ReaderID, AdminID, BorrowDate, ReturnDate, DueDate, Fine)
                           VALUES (?, ?, ?, ?, ?, NULL, ?, ?)");
    $stmt->execute([$new_id, $book_id, $reader_id, $admin_id, $today, $due, $fine]);

    $stmt = $pdo->prepare("UPDATE book SET Status = '已借出' WHERE BookID = ?");
    $stmt->execute([$book_id]);

    echo "<script>alert('✅ 借書成功！'); window.location.href='search_books.php';</script>";

} catch (PDOException $e) {
    echo "<script>alert('❌ 資料庫錯誤：" . addslashes($e->getMessage()) . "'); window.location.href='search_books.php';</script>";
}
?>
