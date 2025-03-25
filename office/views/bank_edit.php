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
$bank = $db->query("SELECT * FROM banks WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$bank) {
    exit('ไม่พบข้อมูลธนาคาร');
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขธนาคาร</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="bank_list.php">บัญชีธนาคาร</a></li>
                <li class="breadcrumb-item active" aria-current="page">แก้ไขบัญชี</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">แก้ไขข้อมูลธนาคาร</h4>
        <form method="POST" action="bank_update.php">
            <input type="hidden" name="id" value="<?php echo $bank['id'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="bank_name" class="form-label">ธนาคาร</label>
                    <input type="text" id="bank_name" name="bank_name" class="form-control" value="<?php echo htmlspecialchars($bank['bank_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">สถานะ</label>
                    <select id="status" name="status" class="form-select">
                        <option value="on" <?php echo $bank['status'] === 'on' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                        <option value="off" <?php echo $bank['status'] === 'off' ? 'selected' : '' ?>>ปิดใช้งาน</option>
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
