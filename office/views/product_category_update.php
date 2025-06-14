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
$name = trim($_POST['name'] ?? '-');
$status = $_POST['status'] ?? 'on';
$updated_by = $_SESSION['user_id'];

if ($id == 0 || $name === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("UPDATE product_categories SET
  name = ?,
  status = ?,
  updated_by = ?,
  updated_at = NOW()
  WHERE id = ?",
    $name, $status, $updated_by, $id);

header("Location: main.php?p=category");
exit;