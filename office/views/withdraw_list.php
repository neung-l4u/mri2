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
<div class="container py-5">
<?php include "topNav.php"; ?>
<div class="mb-3 card rounded border-0 shadow-sm">
    <nav class="card-body" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="main.php?p=dashboard">
                    <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                    แดชบอร์ด
                </a>
            </li>
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
    </div>

</body>
</html>
