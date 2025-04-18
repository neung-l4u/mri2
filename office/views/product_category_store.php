<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$name = $_POST['name'] ?? '';
$status = $_POST['status'] ?? 'on';
$created_by = $_SESSION['user_id'];

if ($name === '') {
    die('กรุณากรอกชื่อประเภทสินค้า');
}

$db->query("INSERT INTO product_categories (name, status, created_by) VALUES (?, ?, ?)",
    $name, $status, $created_by);

header("Location: main.php?p=category");
exit;