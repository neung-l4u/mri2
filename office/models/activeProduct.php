<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;



if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$product = $db->query("SELECT * FROM products WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$product) {
    exit('ไม่พบข้อมูลสินค้า');
}

$categories = $db->query("SELECT id, name FROM product_categories WHERE deleted_at IS NULL AND status = 'on' ORDER BY name")->fetchAll();
?>
