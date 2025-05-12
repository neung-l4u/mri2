<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$route_id = $_GET['route_id'] ?? '';
if ($route_id) {
    $route = $db->query("SELECT * FROM customer_routes WHERE id = ? AND deleted_at IS NULL", $route_id)->fetchArray();
}
$banks = $db->query("SELECT ba.bank_id, b.bank_name, ba.display_name, ba.account_name, ba.account_number, ba.branch, ba.transaction_count, ba.status FROM bank_accounts ba LEFT JOIN banks b ON ba.bank_id = b.id WHERE ba.deleted_at IS NULL AND ba.status = 'active' ORDER BY ba.display_name;")->fetchAll();
$routes = $db->query("SELECT * FROM customer_routes WHERE deleted_at IS NULL AND status = 'on' ORDER BY route_name")->fetchAll();
$salespersons = $db->query("SELECT DISTINCT u.salesperson_id, u.name FROM users u WHERE u.role = 'sales' AND u.salesperson_id IS NOT NULL AND u.deleted_at IS NULL AND u.status = 'on' ORDER BY u.name")->fetchAll();

$randNum = (rand(10,99));
$randTxt = substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(2/strlen($x)) )),1,2);
$rand = $randTxt.'-'.$randNum;
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มลูกค้า</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        ::placeholder{
            color: #cccccc !important;
        }
        .red{
            color: red;
        }

        label{
            cursor: pointer;
        }
    </style>
    <script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
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
                <li class="breadcrumb-item"><a href="main.php?p=lines">สายลูกค้า</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="customer_list.php?route_id=<?php echo $route_id; ?>">ลูกค้า<?php echo $route ? 'ในสาย ' . htmlspecialchars($route['route_name']) : '' ?></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">เพิ่มลูกค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">เพิ่มลูกค้าใหม่</h4>
        <form method="POST" action="customer_store.php">
            <h6 class="mb-3 text-info">รายละเอียดบัญชี</h6>
            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <label class="form-label" for="status">สถานะ <span class="red">*</span></label>
                    <select name="status" id="status" class="form-select">
                        <option value="on" selected>เปิดใช้งาน</option>
                        <option value="off">ปิดใช้งาน</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="vat_type">ประเภทลูกค้า <span class="red">*</span></label>
                    <select name="vat_type" id="vat_type" class="form-select">
                        <option value="no_vat">ไม่มี VAT</option>
                        <option value="vat">มี VAT</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="salesperson_id">เซลที่ดูแล <span class="red">*</span></label>
                    <select name="salesperson_id" id="salesperson_id" class="form-select" required>
                        <option value="">-- เลือกเซล --</option>
                        <?php foreach ($salespersons as $s): ?>
                            <option value="<?php echo $s['salesperson_id'] ?>"><?php echo $s['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <h6 class="mb-3 text-info">รายละเอียดสาย</h6>
            <div class="row g-3 mb-5">
                <div class="col-md-6">
                    <label class="form-label">สายลูกค้า <span class="red">*</span></label>
                    <div class="text-primary"><?php echo $route['route_name']; ?></div>
                    <select name="route_id" class="form-select" style="display: none;" aria-label="route_id">
                        <option value="">-- เลือกสาย --</option>
                        <?php foreach ($routes as $r): ?>
                            <option value="<?php echo $r['id'] ?>" <?php echo $r['id'] == $route_id ? 'selected' : '' ?>><?php echo $r['route_code'].' : '.$r['route_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="customer_code">รหัสลูกค้า</label>
                    <input type="text" id="customer_code" name="customer_code" class="form-control" onclick="this.select();" autocomplete="off" value="<?php echo $rand; ?>">
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="name">ชื่อลูกค้า <span class="red">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="ระบุชื่อลูกค้า" autocomplete="off" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="nickname">ชื่อเล่น</label>
                    <input type="text" id="nickname" name="nickname" placeholder="หากมี" autocomplete="off" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="phone">เบอร์โทร</label>
                    <input type="text" id="phone" name="phone" placeholder="0898765432" autocomplete="off" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="email">อีเมล</label>
                    <input type="email" id="email" name="email" placeholder="mail@gmail.com" autocomplete="off" class="form-control">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="address">ที่อยู่</label>
                    <textarea name="address" id="address" placeholder="เช่น ตลาดมีน" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <h6 class="mb-3 text-info">รายละเอียดการเงิน</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="payment_type">การชำระเงิน <span class="red">*</span></label>
                    <select name="payment_type" id="payment_type" class="form-select" required onchange="showBank(this.value);">
                        <option value="เงินสด" selected>เงินสด</option>
                        <option value="โอน">โอน</option>
                    </select>
                </div>
                <div class="col-md-4" id="optionBankAccount">
                    <label class="form-label" for="bank_account_id">บัญชีที่ใช้รับโอน <span class="red">*</span></label>
                    <select name="bank_account_id" id="bank_account_id" class="form-select">
                        <option value="">-- เลือกบัญชี --</option>
                        <?php foreach ($banks as $b): ?>
                            <option value="<?php echo $b['id'] ?>"><?php echo $b['display_name'].' - '.$b['bank_name'].' '.$b['account_name'].' ('.$b['account_number'].')'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const optionBankAccount = $("#optionBankAccount");

    $(()=>{
        optionBankAccount.hide();
    });//ready

    function showBank(sel) {
        if (sel === 'เงินสด'){
            optionBankAccount.hide();
            $("#optionBankAccount option:first").attr('selected','selected');
        }else if (sel === 'โอน'){
            optionBankAccount.show();
        }
    }
</script>
</body>
</html>