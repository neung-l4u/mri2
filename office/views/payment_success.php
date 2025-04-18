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
                <li class="breadcrumb-item">
                    <a href="main.php?p=dashboard">
                        <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        แดชบอร์ด
                    </a>
                </li>
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