<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$name = $_SESSION['name'] ?? 'ไม่ทราบชื่อ';
$role = $_SESSION['role'] ?? '-';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แดชบอร์ด</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="p-4 bg-white rounded shadow-sm">
        <h4 class="mb-3">สวัสดีคุณ <?php echo htmlspecialchars($name); ?></h4>
        <p class="text-muted">บทบาทของคุณ: <strong><?php echo htmlspecialchars($role); ?></strong></p>

        <hr>
        <div class="row g-3">
            <div class="col-6 col-md-4">
                <a href="withdraw_create.php" class="btn btn-outline-primary w-100">+ เบิกสินค้า</a>
            </div>
            <div class="col-6 col-md-4">
                <a href="withdraw_list.php" class="btn btn-outline-secondary w-100">📋 รายการเบิกย้อนหลัง</a>
            </div>
            <div class="col-6 col-md-4">
                <a href="logout.php" class="btn btn-outline-danger w-100">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>