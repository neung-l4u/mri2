<?php
global $db;
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['owner', 'sales'])) {
    exit;
}

$customer_id = $_GET['customer_id'] ?? null;
$status = $_GET['status'] ?? null;

?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บิลค้างชำระ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="main.php?p=dashboard">
                        <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        แดชบอร์ด
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">รายการบิลที่ยังไม่ชำระ</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <?php
        if ($status === 'unpaid') {
            $invoices = $db->query("
        SELECT s.id, s.withdrawal_date, s.total_amount, c.name AS customer_name, r.route_name
        FROM sales_orders s
        LEFT JOIN customers c ON s.customer_id = c.id
        LEFT JOIN customer_routes r ON c.route_id = r.id
        WHERE s.is_paid = 0 AND s.deleted_at IS NULL
        ORDER BY s.withdrawal_date DESC
    ")->fetchAll();

            echo '<h4 class="mb-4">รายการบิลที่ยังไม่ชำระทั้งหมด</h4>';
            if (count($invoices) === 0) {
                echo '<div class="alert alert-info">ไม่มีบิลค้างชำระในระบบ</div>';
            } else {
                $total_sum = array_sum(array_column($invoices, 'total_amount'));
                echo '<div class="table-responsive"><table class="table table-bordered table-hover align-middle">';
                echo '<thead class="table-light"><tr><th style="width: 60px;">#</th><th style="width: 180px;">วันที่เบิก</th><th>ชื่อลูกค้า</th><th>สายลูกค้า</th><th class="text-end">ยอดบิล (บาท)</th></tr></thead><tbody>';
                $i = 1;
                foreach ($invoices as $row) {
                    echo '<tr>';
                    echo '<td class="text-center">' . $i++ . '</td>';
                    echo '<td>' . htmlspecialchars($row['withdrawal_date']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['customer_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['route_name']) . '</td>';
                    echo '<td class="text-end text-danger">' . number_format($row['total_amount'], 2) . ' บาท</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '<tfoot><tr class="table-light"><td colspan="4" class="text-end"><strong>รวมทั้งหมด</strong></td><td class="text-end"><strong class="text-danger">' . number_format($total_sum, 2) . '</strong> บาท</td></tr></tfoot>';
                echo '</table></div>';
            }
        } else {
            echo '<div class="alert alert-warning mt-3">ไม่ได้ระบุสถานะการค้นหา</div>';
        }
        ?>
    </div>
</body>
</html>
