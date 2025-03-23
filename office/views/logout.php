<?php
session_start();

// ล้าง session ทั้งหมด
$_SESSION = array();
session_destroy();

// ล้าง cookie จดจำผู้ใช้ (ถ้ามี)
if (isset($_COOKIE['remember_username'])) {
    setcookie('remember_remember', '', time() - 3600, '/');
    setcookie('remember_username', '', time() - 3600, '/');
    setcookie('remember_password', '', time() - 3600, '/');
}

// กลับไปหน้า login
header("Location: index.php");
exit;