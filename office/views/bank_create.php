<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มบัญชีธนาคาร</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="bank_list.php">บัญชีธนาคาร</a></li>
                <li class="breadcrumb-item active" aria-current="page">เพิ่มบัญชีใหม่</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">เพิ่มบัญชีธนาคารใหม่</h4>
        <form method="POST" action="bank_store.php">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อเรียกบัญชี</label>
                    <input type="text" name="display_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ชื่อบัญชี</label>
                    <input type="text" name="account_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เลขที่บัญชี</label>
                    <input type="text" name="account_number" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ธนาคาร</label>
                    <input type="text" name="bank_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">สาขา</label>
                    <input type="text" name="branch" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">วันที่เปิดใช้</label>
                    <input type="date" name="opened_at" class="form-control" value="<?php echo date('Y-m-d') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="active" selected>เปิดใช้งาน</option>
                        <option value="inactive">ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึกบัญชี</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>