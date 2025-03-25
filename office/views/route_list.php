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
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
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
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
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
