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
                <li class="breadcrumb-item">
                    <a href="main.php?p=dashboard">
                        <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        แดชบอร์ด
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="main.php?p=users">ผู้ใช้งาน</a></li>
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