<?php
global $db;
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$customer_id = $_POST['customer_id'] ?? null;
$payment_date = $_POST['payment_date'] ?? date('Y-m-d');
$method = $_POST['method'] ?? '';
$note = $_POST['note'] ?? '';
$bank_account_id = $_POST['bank_account_id'] ?? null;
$sales_order_ids = $_POST['sales_order_ids'] ?? [];

if (!$customer_id || !$bank_account_id || empty($sales_order_ids)) {
    echo "ข้อมูลไม่ครบถ้วน";
    exit;
}

// ดึงบิลที่เลือกมาทั้งหมด
$placeholders = implode(',', array_fill(0, count($sales_order_ids), '?'));
$params = $sales_order_ids;
array_unshift($params, $customer_id); // เพิ่ม customer_id เป็นตัวแรก
$sql = "SELECT id, total_amount FROM sales_orders WHERE customer_id = ? AND id IN ($placeholders) AND is_paid = 0 AND deleted_at IS NULL";
$invoices = $db->query($sql, ...$params)->fetchAll();

if (count($invoices) === 0) {
    echo "ไม่มีบิลที่สามารถรับชำระได้";
    exit;
}

// คำนวณยอดรวม
$total_paid = 0;
foreach ($invoices as $inv) {
    $total_paid += $inv['total_amount'];
}

// บันทึกลง payments
$db->query("INSERT INTO payments (customer_id, payment_date, amount_paid, method, note, bank_account_id, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)",
    $customer_id, $payment_date, $total_paid, $method, $note, $bank_account_id, $user_id);
$payment_id = $db->lastInsertID();

// บันทึกลง payment_details และอัปเดตสถานะบิล
foreach ($invoices as $inv) {
    $db->query("INSERT INTO payment_details (payment_id, sales_order_id, amount_applied, created_by) VALUES (?, ?, ?, ?)",
        $payment_id, $inv['id'], $inv['total_amount'], $user_id);

    $db->query("UPDATE sales_orders SET is_paid = 1, updated_by = ? WHERE id = ?", $user_id, $inv['id']);
}

header("Location: payment_success.php?payment_id=" . $payment_id);
exit;
