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
                <li class="breadcrumb-item">
                    <a href="main.php?p=dashboard">
                        <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        แดชบอร์ด
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">ประเภทสินค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-list-task"></i> รายการประเภทสินค้า</h4>
            <a href="product_category_create.php" class="btn btn-primary btn-sm">+ เพิ่มประเภทใหม่</a>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th style="width: 50px;" class="text-center">#</th>
                    <th>ชื่อหมวดหมู่</th>
                    <th style="width: 150px;" class="text-center">จำนวนสินค้า</th>
                    <th style="width: 80px;" class="text-center">สถานะ</th>
                    <th style="width: 80px;"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=1;
                foreach ($categories as $cat):
                    ?>
                    <tr>
                        <td class="text-end"><?php echo $i; ?></td>
                        <td class="text-start"><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td class="text-end">
                            <a href="main.php?p=products&cateID=<?php echo $cat['id'];?>" style="text-decoration: none;"><?php echo number_format($cat['product_count']); ?></a> <i class="bi bi-menu-button-wide-fill"></i>
                        </td>
                        <td class="text-center"><?php echo $cat['status'] === 'on' ? '<i class="bi bi-check text-success" title="เปิดใช้งาน"></i>' : '<i class="bi bi-x text-danger" title="ปิดใช้งาน"></i>'; ?></td>
                        <td class="text-end pr-3">
                            <a href="product_category_edit.php?id=<?php echo $cat['id'];?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill" title="แก้ไข"></i></a>
                        </td>
                    </tr>
                <?php
                    $i++;
                    endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>