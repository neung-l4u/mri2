<?php
global $db;
session_start();
include_once('../assets/db/db.php');
include_once('../assets/db/initDB.php');

// สมมุติว่า user_id = 1 (เซลล์)
$user_id = 1;

// ดึงรายการบิลทั้งหมดของเซลนี้ (ยังไม่ลบ, แสดงเรียงล่าสุดก่อน)
$orders = $db->query("SELECT o.id, o.withdrawal_date, o.total_amount, o.is_paid, c.customer_code, c.name AS customer_name
                      FROM sales_orders o
                      JOIN customers c ON o.customer_id = c.id
                      WHERE o.salesperson_id = ? AND o.deleted_at IS NULL
                      ORDER BY o.withdrawal_date DESC, o.id DESC", $user_id)->fetchAll();
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายการเบิกย้อนหลัง</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
<?php include "topNav.php"; ?>
<div class="mb-3 card rounded border-0 shadow-sm">
    <nav class="card-body" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">แดชบอร์ด</a></li>
            <li class="breadcrumb-item active" aria-current="page">รายการเบิกย้อนหลัง</li>
        </ol>
    </nav>
</div>
<h4 class="mb-4">รายการเบิกย้อนหลัง</h4>
<table class="table table-bordered table-hover">
    <thead class="table-light">
    <tr>
        <th>วันที่เบิก</th>
        <th>ลูกค้า</th>
        <th>ยอดรวม (บาท)</th>
        <th>สถานะ</th>
        <th>ดูรายละเอียด</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order['withdrawal_date'] ?></td>
            <td><?php echo $order['customer_code'] ?> - <?php echo $order['customer_name'] ?></td>
            <td><?php echo number_format($order['total_amount'], 2) ?></td>
            <td>
                <?php echo $order['is_paid'] ? '<span class="badge bg-success">จ่ายแล้ว</span>' : '<span class="badge bg-warning text-dark">ค้างชำระ</span>'; ?>
            </td>
            <td><a href="withdraw_success.php?order_id=<?php echo $order['id'] ?>" class="btn btn-sm btn-outline-primary">ดู</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="withdraw_create.php" class="btn btn-secondary">+ เบิกรายการใหม่</a>
</body>
</html>
