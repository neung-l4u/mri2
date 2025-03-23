<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$category = $db->query("SELECT * FROM product_categories WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$category) {
    exit('ไม่พบประเภทสินค้า');
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขประเภทสินค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="product_category_list.php">ประเภทสินค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">แก้ไขประเภท</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">แก้ไขประเภทสินค้า</h4>
        <form method="POST" action="product_category_update.php">
            <input type="hidden" name="id" value="<?php echo $category['id'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อประเภทสินค้า</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="on" <?php echo $category['status'] === 'on' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                        <option value="off" <?php echo $category['status'] === 'off' ? 'selected' : '' ?>>ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>