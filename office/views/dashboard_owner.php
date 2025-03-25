<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$name = $_SESSION['name'];

// ยอดขายวันนี้
$sale_today = $db->query("SELECT SUM(total_amount) AS total FROM sales_orders WHERE DATE(withdrawal_date) = CURDATE() AND deleted_at IS NULL")->fetchArray();

// ยอดขายเดือนนี้
$sale_month = $db->query("SELECT SUM(total_amount) AS total FROM sales_orders WHERE MONTH(withdrawal_date) = MONTH(CURDATE()) AND YEAR(withdrawal_date) = YEAR(CURDATE()) AND deleted_at IS NULL")->fetchArray();

// ยอดค้างชำระทั้งหมด
$unpaid_total = $db->query("SELECT SUM(total_amount) AS total FROM sales_orders WHERE is_paid = 0 AND deleted_at IS NULL")->fetchArray();

// จำนวนลูกค้า
$total_customers = $db->query("SELECT COUNT(*) AS total FROM customers WHERE deleted_at IS NULL AND status = 'on'")->fetchArray();

// จำนวนเซล
$total_salespersons = $db->query("SELECT COUNT(*) AS total FROM users WHERE role = 'sales' AND deleted_at IS NULL AND status = 'on'")->fetchArray();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แดชบอร์ดเจ้าของ</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <h4>สวัสดีคุณ <?php echo htmlspecialchars($name); ?> (เจ้าของ)</h4>
    </div>

    <div class="row g-4 mb-4">
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
                <div class="text-muted small">ยอดค้างชำระ</div>
                <h5 class="text-danger">฿ <?php echo number_format($unpaid_total['total'] ?? 0, 2); ?></h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 rounded shadow-sm text-center">
                <div class="text-muted small">ลูกค้าทั้งหมด</div>
                <h5><?php echo $total_customers['total']; ?> ราย</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 rounded shadow-sm text-center">
                <div class="text-muted small">จำนวนเซล</div>
                <h5><?php echo $total_salespersons['total']; ?> คน</h5>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="user_list.php" class="btn btn-outline-primary">👥 จัดการผู้ใช้งาน</a>
        <a href="bank_list.php" class="btn btn-outline-primary">🏦 จัดการบัญชีธนาคาร</a>
        <a href="route_list.php" class="btn btn-outline-primary">🛣 จัดการสายลูกค้า</a>
        <a href="product_list.php" class="btn btn-outline-primary">📦 จัดการสินค้า</a>
        <a href="product_category_list.php" class="btn btn-outline-primary">📂 จัดการประเภทสินค้า</a>
        <a href="report_sales.php" class="btn btn-outline-primary">🧾 รายงานยอดขาย</a>
        <a href="logout.php" class="btn btn-outline-danger">ออกจากระบบ</a>
    </div>
</div>
</body>
</html>