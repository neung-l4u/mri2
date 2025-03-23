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
$route = $db->query("SELECT * FROM customer_routes WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$route) {
    exit('ไม่พบสายลูกค้า');
}

$salespersons = $db->query("SELECT id, name FROM users WHERE role = 'sales' AND deleted_at IS NULL AND status = 'on' ORDER BY name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขสายลูกค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="route_list.php">สายลูกค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">แก้ไขสาย</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">แก้ไขสายลูกค้า</h4>
        <form method="POST" action="route_update.php">
            <input type="hidden" name="id" value="<?php echo $route['id'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">รหัสสาย</label>
                    <input type="text" name="route_code" class="form-control" value="<?php echo htmlspecialchars($route['route_code']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ชื่อสาย</label>
                    <input type="text" name="route_name" class="form-control" value="<?php echo htmlspecialchars($route['route_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เซลที่ดูแล</label>
                    <select name="salesperson_id" class="form-select">
                        <option value="">-- ยังไม่กำหนด --</option>
                        <?php foreach ($salespersons as $s): ?>
                            <option value="<?php echo $s['id'] ?>" <?php echo $route['salesperson_id'] == $s['id'] ? 'selected' : '' ?>>
                                <?php echo $s['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="on" <?php echo $route['status'] === 'on' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                        <option value="off" <?php echo $route['status'] === 'off' ? 'selected' : '' ?>>ปิดใช้งาน</option>
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