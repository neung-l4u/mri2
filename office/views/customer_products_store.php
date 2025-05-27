<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$customer_id = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : 0;
$custom_prices = isset($_POST['custom_prices']) ? $_POST['custom_prices'] : [];

if ($customer_id <= 0) {
    die("Customer ID ไม่ถูกต้อง");
}

foreach ($custom_prices as $product_id => $price) {
    $product_id = (int)$product_id;
    $price = trim($price);

    if ($price === '') {
        // ลบราคาพิเศษเดิมออก (soft delete)
        $db->query("UPDATE customer_product_prices SET deleted_at = NOW(), deleted_by = ? WHERE customer_id = ? AND product_id = ? AND deleted_at IS NULL", $_SESSION['user_id'], $customer_id, $product_id);
    } else {
        $price = floatval($price);
        // เช็คว่ามีอยู่แล้วไหม
        $exists = $db->query("SELECT id FROM customer_product_prices WHERE customer_id = ? AND product_id = ? AND deleted_at IS NULL", $customer_id, $product_id)->fetchArray();
        if ($exists) {
            // อัปเดตราคา
            $db->query("UPDATE customer_product_prices SET custom_price = ?, updated_by = ?, updated_at = NOW() WHERE customer_id = ? AND product_id = ?", $price, $_SESSION['user_id'], $customer_id, $product_id);
        } else {
            // เพิ่มใหม่
            $db->query("INSERT INTO customer_product_prices (customer_id, product_id, custom_price, created_by) VALUES (?, ?, ?, ?)", $customer_id, $product_id, $price, $_SESSION['user_id']);
        }
    }
}

header("Location: customer_products.php?customer_id={$customer_id}&success=1");
exit;
?>
