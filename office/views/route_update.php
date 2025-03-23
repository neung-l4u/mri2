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
$route_code = $_POST['route_code'] ?? '';
$route_name = $_POST['route_name'] ?? '';
$salesperson_id = $_POST['salesperson_id'] ?? null;
$status = $_POST['status'] ?? 'on';
$updated_by = $_SESSION['user_id'];

if ($route_code === '' || $route_name === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("UPDATE customer_routes SET
  route_code = ?,
  route_name = ?,
  salesperson_id = ?,
  status = ?,
  updated_by = ?,
  updated_at = NOW()
  WHERE id = ?",
    $route_code, $route_name, $salesperson_id ?: null, $status, $updated_by, $id);

header("Location: route_list.php");
exit;
