<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$categories = $db->query("SELECT c.*, 
  (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id AND p.deleted_at IS NULL) AS product_count
  FROM product_categories c
  WHERE c.deleted_at IS NULL
  ORDER BY c.name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการประเภทสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard_owner.php">แดชบอร์ด</a></li>
                <li class="breadcrumb-item active" aria-current="page">ประเภทสินค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">รายการประเภทสินค้า</h4>
            <a href="product_category_create.php" class="btn btn-primary btn-sm">+ เพิ่มประเภทใหม่</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                <tr>
                    <th>ชื่อหมวดหมู่</th>
                    <th>จำนวนสินค้า</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cat['name']) ?></td>
                        <td class="text-center"><?php echo $cat['product_count'] ?></td>
                        <td><?php echo $cat['status'] === 'on' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?></td>
                        <td><a href="#" class="btn btn-sm btn-outline-secondary">แก้ไข</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>