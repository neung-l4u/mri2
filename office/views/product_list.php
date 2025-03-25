<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$products = $db->query("SELECT p.*, c.name AS category_name
                        FROM products p
                        LEFT JOIN product_categories c ON p.category_id = c.id
                        WHERE p.deleted_at IS NULL
                        ORDER BY c.name, p.name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active" aria-current="page">สินค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายการสินค้า</h4>
            <a href="product_create.php" class="btn btn-primary btn-sm">+ เพิ่มสินค้าใหม่</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th>ชื่อสินค้า</th>
                    <th>ประเภท</th>
                    <th>ขนาดบรรจุ (กรัม)</th>
                    <th class="text-end">ราคาตั้งต้น (บาท)</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['name']) ?></td>
                        <td><?php echo htmlspecialchars($p['category_name']) ?></td>
                        <td><?php echo number_format($p['package_size_grams']) ?></td>
                        <td class="text-end">฿<?php echo number_format($p['default_price_per_pack'], 2) ?></td>
                        <td><?php echo $p['status'] === 'on' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
                        <td><a href="product_edit.php?id=<?php echo $p['id'] ?>" class="btn btn-sm btn-outline-secondary">แก้ไข</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>