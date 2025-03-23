<?php
global $db;
session_start();
include_once('../assets/db/db.php');
include_once('../assets/db/initDB.php');

// สมมุติว่าผู้ใช้งานล็อกอินแล้ว และ user_id = 1
$user_id = 1;

// รับข้อมูลจากฟอร์ม
$customer_id = $_POST['customer_id'] ?? null;
$withdrawal_date = $_POST['withdrawal_date'] ?? date('Y-m-d');
$product_ids = $_POST['product_id'] ?? [];
$qtys = $_POST['qty'] ?? [];
$prices = $_POST['price'] ?? [];

// ตรวจสอบข้อมูล
if (!$customer_id || empty($product_ids)) {
    echo "ไม่พบข้อมูลลูกค้าหรือสินค้า";
    exit;
}

// คำนวณราคารวมและเตรียมรายการ
$order_items = array();
$total_amount = 0;

for ($i = 0; $i < count($product_ids); $i++) {
    $qty = (float)$qtys[$i];
    $price = (float)$prices[$i];
    if ($qty > 0) {
        $total = $qty * $price;
        $total_amount += $total;
        $order_items[] = array(
            'product_id' => $product_ids[$i],
            'qty' => $qty,
            'price_per_unit' => $price,
            'total_price' => $total
        );
    }
}

if (count($order_items) === 0) {
    echo "กรุณาระบุจำนวนสินค้าอย่างน้อย 1 รายการ";
    exit;
}

// บันทึกข้อมูลลงฐานข้อมูล
try {
    // เพิ่มบิลใหม่
    $db->query("INSERT INTO sales_orders (customer_id, salesperson_id, withdrawal_date, total_amount, is_paid, created_by) VALUES (?, ?, ?, ?, 0, ?)",
        $customer_id, $user_id, $withdrawal_date, $total_amount, $user_id);

    $sales_order_id = $db->lastInsertID();

    // เพิ่มรายการสินค้า
    foreach ($order_items as $item) {
        $db->query("INSERT INTO sales_order_items (sales_order_id, product_id, qty, price_per_unit, total_price, created_by) VALUES (?, ?, ?, ?, ?, ?)",
            $sales_order_id,
            $item['product_id'],
            $item['qty'],
            $item['price_per_unit'],
            $item['total_price'],
            $user_id);
    }

    header("Location: withdraw_success.php?order_id=" . $sales_order_id);
    exit;

} catch (Exception $e) {
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage();
    exit;
}
?>
