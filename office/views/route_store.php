<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$route_code = $_POST['route_code'] ?? '';
$route_name = $_POST['route_name'] ?? '';
$salesperson_id = $_POST['salesperson_id'] ?? null;
$status = $_POST['status'] ?? 'on';
$created_by = $_SESSION['user_id'];

if ($route_code === '' || $route_name === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("INSERT INTO customer_routes (route_code, route_name, salesperson_id, status, created_by)
            VALUES (?, ?, ?, ?, ?)",
    $route_code, $route_name, $salesperson_id ?: null, $status, $created_by);

header("Location: route_list.php");
exit;
