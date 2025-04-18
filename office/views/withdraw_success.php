<?php
session_start();
include_once('../assets/db/db.php');
include_once('../assets/db/initDB.php');

$order_id = $_GET['order_id'] ?? 0;
if (!$order_id) {
    echo "ไม่พบข้อมูลบิล";
    exit;
}

// ดึงข้อมูลหัวบิล
$order = $db->query("SELECT o.id, o.withdrawal_date, o.total_amount, c.customer_code, c.name AS customer_name, s.name AS salesperson_name
                     FROM sales_orders o
                     JOIN customers c ON o.customer_id = c.id
                     JOIN salespersons s ON o.salesperson_id = s.id
                     WHERE o.id = ?", $order_id)->fetchArray();

if (!$order) {
    echo "ไม่พบบิลที่ระบุ";
    exit;
}

// ดึงรายการสินค้าในบิล
$order_items = $db->query("SELECT si.qty, si.price_per_unit, si.total_price, p.name
                            FROM sales_order_items si
                            JOIN products p ON si.product_id = p.id
                            WHERE si.sales_order_id = ?", $order_id)->fetchAll();
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>บันทึกเรียบร้อย</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
<div class="mb-3 card rounded border-0 shadow-sm">
    <nav class="card-body" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="main.php?p=dashboard">
                    <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                    แดชบอร์ด
                </a>
            </li>
            <li class="breadcrumb-item"><a href="main.php?p=withdrawHis">รายการเบิกย้อนหลัง</a></li>
            <li class="breadcrumb-item active" aria-current="page">รายการสินค้า</li>
        </ol>
    </nav>

</div>
<div class="alert alert-success">
    <h4 class="alert-heading">✅ บันทึกการเบิกเรียบร้อยแล้ว!</h4>
    <p>บิลเลขที่: <?php echo $order['id'] ?> | วันที่: <?php echo $order['withdrawal_date'] ?></p>
    <p>ลูกค้า: <?php echo $order['customer_code'] ?> - <?php echo $order['customer_name'] ?></p>
    <p>เซล: <?php echo $order['salesperson_name'] ?></p>
</div>

<h5>รายการสินค้า</h5>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>สินค้า</th>
        <th>จำนวน (กก.)</th>
        <th>ราคาต่อกก.</th>
        <th>รวม</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($order_items as $item): ?>
        <tr>
            <td><?php echo $item['name'] ?></td>
            <td><?php echo number_format($item['qty'], 2) ?></td>
            <td><?php echo number_format($item['price_per_unit'], 2) ?></td>
            <td><?php echo number_format($item['total_price'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr class="table-light">
        <th colspan="3" class="text-end">รวมทั้งหมด</th>
        <th><?php echo number_format($order['total_amount'], 2) ?></th>
    </tr>
    </tfoot>
</table>

<a href="withdraw_create.php" class="btn btn-primary">+ เบิกรายการใหม่</a>
</body>
</html>
