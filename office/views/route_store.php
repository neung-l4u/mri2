<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$route_code_random = 'R-' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

$level = $_POST['route_level'] ?? 'down';
$route_code = trim($_POST['route_code'] ?? $route_code_random);
$route_name = trim($_POST['route_name'] ?? '');
$description = trim($_POST['description'] ?? null);
$status = $_POST['status'] ?? 'on';
$created_by = $_SESSION['user_id'];

if ($route_code === '' || $route_name === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("INSERT INTO customer_routes (route_code, route_level, route_name, description, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?)",
    $route_code, $level, $route_name, $description ?: null, $status, $created_by);

header("Location: route_list.php");
exit;
