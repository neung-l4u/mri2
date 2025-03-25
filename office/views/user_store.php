<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;
$salt = "MRI";

$timestamp = round(microtime(true) * 1000);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$name = $_POST['name'] ?? '';
$nickname = $_POST['nickname'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$route_ids = $_POST['route_ids'] ?? [];
$created_by = $_SESSION['user_id'];
$username = $timestamp;

if ($name === '' || $phone === '' || $password === '' || $role === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}else{
    $password = $salt.$password;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

// หากเป็นเซล ต้องสร้าง salesperson_id ใหม่
$salesperson_id = null;
if ($role === 'sales') {
    $sp_code = 'SP-' . date('YmdHis');
    $db->query("INSERT INTO salespersons (code, name, created_by) VALUES (?, ?, ?)", $sp_code, $name, $created_by);
    $salesperson_id = $db->lastInsertID();

    // อัปเดตสายลูกค้าให้เชื่อมกับ salesperson_id นี้
    if (!empty($route_ids)) {
        foreach ($route_ids as $rid) {
            $db->query("UPDATE customer_routes SET salesperson_id = ?, updated_by = ?, updated_at = NOW() WHERE id = ?", $salesperson_id, $created_by, $rid);
        }
    }
}

// บันทึก user
$db->query("INSERT INTO users (username, name, nickname, phone, email, password_hash, role, salesperson_id, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'on', ?)",
    $username, $name, $nickname, $phone, $email, $hashed_password, $role, $salesperson_id, $created_by);

header("Location: user_list.php");
exit;