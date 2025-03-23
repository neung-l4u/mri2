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
