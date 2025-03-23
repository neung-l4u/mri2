<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$id = $_POST['id'] ?? 0;
$name = $_POST['name'] ?? '';
$nickname = $_POST['nickname'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$route_ids = $_POST['route_ids'] ?? [];
$updated_by = $_SESSION['user_id'];

$user = $db->query("SELECT * FROM users WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$user) {
    exit('ไม่พบผู้ใช้งาน');
}

// ถ้ามีการเปลี่ยนรหัสผ่าน
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $db->query("UPDATE users SET password_hash = ?, updated_by = ?, updated_at = NOW() WHERE id = ?",
        $hashed_password, $updated_by, $id);
}

$db->query("UPDATE users SET name = ?, nickname = ?, phone = ?, email = ?, updated_by = ?, updated_at = NOW() WHERE id = ?",
    $name, $nickname, $phone, $email, $updated_by, $id);

if ($user['role'] === 'sales' && $user['salesperson_id']) {
    // ล้างสายเดิมที่เคยผูกกับเซลนี้ก่อน
    $db->query("UPDATE customer_routes SET salesperson_id = NULL, updated_by = ?, updated_at = NOW() WHERE salesperson_id = ?",
        $updated_by, $user['salesperson_id']);

    // อัปเดตสายใหม่
    if (!empty($route_ids)) {
        foreach ($route_ids as $rid) {
            $db->query("UPDATE customer_routes SET salesperson_id = ?, updated_by = ?, updated_at = NOW() WHERE id = ?",
                $user['salesperson_id'], $updated_by, $rid);
        }
    }
}

header("Location: user_list.php");
exit;