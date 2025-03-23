<?php
global $db;
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    header("Location: ../index.php");
    exit;
}

$payment_id = $_GET['payment_id'] ?? 0;
if (!$payment_id) {
    echo "ไม่พบข้อมูลการชำระเงิน";
    exit;
}

$payment = $db->query("SELECT p.*, c.customer_code, c.name AS customer_name, b.display_name AS bank_name
                        FROM payments p
                        JOIN customers c ON p.customer_id = c.id
                        JOIN bank_accounts b ON p.bank_account_id = b.id
                        WHERE p.id = ?", $payment_id)->fetchArray();

$details = $db->query("SELECT d.amount_applied, s.id AS sales_order_id, s.withdrawal_date
                       FROM payment_details d
                       JOIN sales_orders s ON d.sales_order_id = s.id
                       WHERE d.payment_id = ?", $payment_id)->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>บันทึกการชำระเงินสำเร็จ</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <?php include "topNav.php"; ?>
    <div class="mb-3 card rounded border-0 shadow-sm">
        <nav class="card-body" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item"><a href="payment_list.php">ประวัติการรับชำระเงิน</a></li>
                <li class="breadcrumb-item active" aria-current="page">รายละเอียดการรับชำระเงิน</li>
            </ol>
        </nav>
    </div>
    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="text-success mb-4">✅ บันทึกรับชำระเงินสำเร็จ</h4>
        <p><strong>ลูกค้า:</strong> <?php echo $payment['customer_code'] ?> - <?php echo $payment['customer_name'] ?></p>
        <p><strong>วันที่รับชำระ:</strong> <?php echo $payment['payment_date'] ?></p>
        <p><strong>ยอดเงินที่รับ:</strong> ฿<?php echo number_format($payment['amount_paid'], 2) ?></p>
        <p><strong>ช่องทาง:</strong> <?php echo $payment['method'] ?> | <strong>บัญชี:</strong> <?php echo $payment['bank_name'] ?></p>
        <?php if ($payment['note']): ?>
            <p><strong>หมายเหตุ:</strong> <?php echo $payment['note'] ?></p>
        <?php endif; ?>

        <h6 class="mt-4">บิลที่ชำระแล้ว:</h6>
        <ul>
            <?php foreach ($details as $d): ?>
                <li>บิล #<?php echo $d['sales_order_id'] ?> | วันที่เบิก: <?php echo $d['withdrawal_date'] ?> | ฿<?php echo number_format($d['amount_applied'], 2) ?></li>
            <?php endforeach; ?>
        </ul>

        <a href="payment_create.php" class="btn btn-primary mt-3">+ รับชำระเงินรายการใหม่</a>
    </div>
</div>
</body>
</html>