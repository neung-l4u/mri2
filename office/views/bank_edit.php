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
$bank = $db->query("SELECT * FROM bank_accounts WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$bank) {
    exit('ไม่พบข้อมูลบัญชีธนาคาร');
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขบัญชีธนาคาร</title>
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
        <h4 class="mb-4">แก้ไขข้อมูลบัญชีธนาคาร</h4>
        <form method="POST" action="bank_update.php">
            <input type="hidden" name="id" value="<?php echo $bank['id'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อเรียกบัญชี</label>
                    <input type="text" name="display_name" class="form-control" value="<?php echo htmlspecialchars($bank['display_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ชื่อบัญชี</label>
                    <input type="text" name="account_name" class="form-control" value="<?php echo htmlspecialchars($bank['account_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เลขที่บัญชี</label>
                    <input type="text" name="account_number" class="form-control" value="<?php echo htmlspecialchars($bank['account_number']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ธนาคาร</label>
                    <input type="text" name="bank_name" class="form-control" value="<?php echo htmlspecialchars($bank['bank_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">สาขา</label>
                    <input type="text" name="branch" class="form-control" value="<?php echo htmlspecialchars($bank['branch']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">วันที่เปิดใช้</label>
                    <input type="date" name="opened_at" class="form-control" value="<?php echo $bank['opened_at'] ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="active" <?php echo $bank['status'] === 'active' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                        <option value="inactive" <?php echo $bank['status'] === 'inactive' ? 'selected' : '' ?>>ปิดใช้งาน</option>
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
