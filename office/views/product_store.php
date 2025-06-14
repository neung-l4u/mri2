<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$name = trim($_POST['name'] ?? '');
$productLevel = $_POST['productLevel'] ?? 'down';
$category_id = $_POST['category_id'] ?? '';
$package_size_grams = $_POST['package_size_grams'] ?? 0;
$default_price_per_pack = $_POST['default_price_per_pack'] ?? 0;
$status = $_POST['status'] ?? 'on';
$created_by = $_SESSION['user_id'];

if ($name === '' || !$category_id || $package_size_grams <= 0) {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("INSERT INTO products (name, productLevel, category_id, package_size_grams, default_price_per_pack, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)",
    $name, $productLevel, $category_id, $package_size_grams, $default_price_per_pack, $status, $created_by);

header("Location: main.php?p=products");
exit;
