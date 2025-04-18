<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$routes = $db->query("SELECT r.*, 
  (SELECT COUNT(*) FROM customers c WHERE c.route_id = r.id AND c.deleted_at IS NULL) AS customer_count,
  (
    SELECT GROUP_CONCAT(DISTINCT u.name SEPARATOR ', ')
    FROM customers c
    JOIN users u ON u.salesperson_id = c.salesperson_id
    WHERE c.route_id = r.id AND u.deleted_at IS NULL
  ) AS salesperson_names
  FROM customer_routes r
  WHERE r.deleted_at IS NULL
  ORDER BY r.route_code")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการสายลูกค้า</title>
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
                <li class="breadcrumb-item active" aria-current="page">สายลูกค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายการสายลูกค้า</h4>
            <a href="route_create.php" class="btn btn-primary btn-sm">+ เพิ่มสายใหม่</a>
        </div>

        <div class="table-responsive">
            <table id="tableData" class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th style="width: 100px">รหัสสาย</th>
                    <th>ชื่อสาย</th>
                    <th style="width: 150px">เซลที่ดูแล</th>
                    <th style="width: 120px">จำนวนลูกค้า</th>
                    <th style="width: 90px">สถานะ</th>
                    <th style="width: 200px; text-align: center">จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($routes as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['route_code']) ?></td>
                        <td><?php echo htmlspecialchars($r['route_name']) ?></td>
                        <td><?php echo $r['salesperson_names'] ?: '-' ?></td>
                        <td class="text-center"><?php echo $r['customer_count'] ?></td>
                        <td><?php echo $r['status'] === 'on' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
                        <td style="text-align: right">
                            <a href="customer_list.php?route_id=<?php echo $r['id'] ?>" class="btn btn-sm btn-outline-primary">ลูกค้า</a>
                            <a href="route_edit.php?id=<?php echo $r['id'] ?>" class="btn btn-sm btn-outline-secondary">แก้ไข</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
