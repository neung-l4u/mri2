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
$category_id = $_POST['category_id'] ?? '';
$package_size_grams = $_POST['package_size_grams'] ?? 0;
$default_price_per_pack = $_POST['default_price_per_pack'] ?? 0;
$status = $_POST['status'] ?? 'on';
$updated_by = $_SESSION['user_id'];

if ($id == 0 || $name === '' || !$category_id || $package_size_grams <= 0) {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("UPDATE products SET
  name = ?,
  category_id = ?,
  package_size_grams = ?,
  default_price_per_pack = ?,
  status = ?,
  updated_by = ?,
  updated_at = NOW()
  WHERE id = ?",
    $name, $category_id, $package_size_grams, $default_price_per_pack, $status, $updated_by, $id);

header("Location: main.php?p=products");
exit;
