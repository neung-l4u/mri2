<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$routes = $db->query("SELECT id, route_name FROM customer_routes WHERE deleted_at IS NULL AND status = 'on' ORDER BY route_name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มผู้ใช้งาน</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="user_list.php">ผู้ใช้งาน</a></li>
                <li class="breadcrumb-item active" aria-current="page">เพิ่มผู้ใช้งาน</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">เพิ่มผู้ใช้งานใหม่</h4>
        <form method="POST" action="user_store.php">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อ - นามสกุล</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ชื่อเล่น</label>
                    <input type="text" name="nickname" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">บทบาท</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">-- เลือกบทบาท --</option>
                        <option value="sales">เซล</option>
                        <option value="owner">เจ้าของ</option>
                        <option value="admin">แอดมิน</option>
                    </select>
                </div>
                <div class="col-12" id="route-section" style="display: none;">
                    <label class="form-label">สายลูกค้าที่ดูแล (เฉพาะเซล)</label>
                    <select name="route_ids[]" class="form-select" multiple id="route_ids">
                        <?php foreach ($routes as $r): ?>
                            <option value="<?php echo $r['id'] ?>"><?php echo $r['route_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึกผู้ใช้งาน</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
<script src="../assets/libs/select2/js/select2.min.js"></script>
<script>
    $(function () {
        $('#route_ids').select2({
            theme: 'bootstrap-5',
            placeholder: 'เลือกสายลูกค้า',
            width: '100%'
        });

        $('#role').on('change', function () {
            if ($(this).val() === 'sales') {
                $('#route-section').slideDown();
            } else {
                $('#route-section').slideUp();
                $('#route_ids').val(null).trigger('change');
            }
        });
    });
</script>
</body>
</html>