<?php
global $db;
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    header("Location: ../index.php");
    exit;
}

$salesperson_id = $_SESSION['salesperson_id'];
$name = $_SESSION['name'];

// ยอดขายวันนี้
$sale_today = $db->query("SELECT SUM(total_amount) AS total FROM sales_orders WHERE salesperson_id = ? AND DATE(withdrawal_date) = CURDATE() AND deleted_at IS NULL", $salesperson_id)->fetchArray();

// ยอดขายเดือนนี้
$sale_month = $db->query("SELECT SUM(total_amount) AS total FROM sales_orders WHERE salesperson_id = ? AND MONTH(withdrawal_date) = MONTH(CURDATE()) AND YEAR(withdrawal_date) = YEAR(CURDATE()) AND deleted_at IS NULL", $salesperson_id)->fetchArray();

// จำนวนลูกค้าในสาย
$customer_count = $db->query("SELECT COUNT(*) AS total FROM customers WHERE salesperson_id = ? AND deleted_at IS NULL AND status = 'on'", $salesperson_id)->fetchArray();

// จำนวนบิลค้างชำระ
$unpaid_count = $db->query("SELECT COUNT(*) AS total FROM sales_orders WHERE salesperson_id = ? AND is_paid = 0 AND deleted_at IS NULL", $salesperson_id)->fetchArray();
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แดชบอร์ดเซล</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <?php include "topNav.php"; ?>
    <div class="mb-3 card rounded border-0 shadow-sm">
        <nav class="card-body" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">แดชบอร์ด</li>
            </ol>
        </nav>
    </div>
    <div class="mb-4">
        <h4>สวัสดีคุณ <?php echo htmlspecialchars($name); ?> (เซล)</h4>
    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="bg-white p-3 rounded shadow-sm text-center">
                <div class="text-muted small">ยอดขายวันนี้</div>
                <h5 class="text-primary">฿ <?php echo number_format($sale_today['total'] ?? 0, 2); ?></h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 rounded shadow-sm text-center">
                <div class="text-muted small">ยอดขายเดือนนี้</div>
                <h5 class="text-success">฿ <?php echo number_format($sale_month['total'] ?? 0, 2); ?></h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 rounded shadow-sm text-center">
                <div class="text-muted small">จำนวนลูกค้า</div>
                <h5><?php echo $customer_count['total']; ?> ราย</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 rounded shadow-sm text-center">
                <div class="text-muted small">บิลค้างชำระ</div>
                <h5 class="text-danger"><?php echo $unpaid_count['total']; ?> บิล</h5>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex flex-wrap gap-2">
        <a href="withdraw_create.php" class="btn btn-primary">+ เบิกสินค้า</a>
        <a href="withdraw_list.php" class="btn btn-outline-secondary">📋 รายการเบิกย้อนหลัง</a>
        <a href="payment_create.php" class="btn btn-outline-success">💰 รับชำระเงิน</a>
        <a href="payment_list.php" class="btn btn-outline-info">🧾 ประวัติรับชำระ</a>
    </div>
</div>
</body>
</html>