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
$customer = $db->query("SELECT * FROM customers WHERE id = ? AND deleted_at IS NULL", $id)->fetchArray();
if (!$customer) {
    exit('ไม่พบข้อมูลลูกค้า');
}

$route_id = $_GET['route_id'] ?? '';
if ($route_id) {
    $route = $db->query("SELECT * FROM customer_routes WHERE id = ? AND deleted_at IS NULL", $route_id)->fetchArray();
}
$banks = $db->query("SELECT ba.id, ba.bank_id, b.bank_name, ba.display_name, ba.account_name, ba.account_number, ba.branch, ba.transaction_count, ba.status FROM bank_accounts ba LEFT JOIN banks b ON ba.bank_id = b.id WHERE ba.deleted_at IS NULL AND ba.status = 'active' ORDER BY ba.display_name;")->fetchAll();
$routes = $db->query("SELECT * FROM customer_routes WHERE deleted_at IS NULL AND status = 'on' ORDER BY route_name")->fetchAll();
$salespersons = $db->query("SELECT DISTINCT u.salesperson_id, u.name FROM users u WHERE u.role = 'sales' AND u.salesperson_id IS NOT NULL AND u.deleted_at IS NULL AND u.status = 'on' ORDER BY u.name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขลูกค้า</title>
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
                <li class="breadcrumb-item"><a href="customer_list.php?route_id=<?php echo $customer['route_id'] ?>">ลูกค้า<?php echo $route ? 'ในสาย ' . htmlspecialchars($route['route_name']) : '' ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">แก้ไขลูกค้า</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">แก้ไขข้อมูลลูกค้า</h4>
        <form method="POST" action="customer_update.php">
            <input type="hidden" name="id" value="<?php echo $customer['id'] ?>">
            <h6 class="mb-3 text-info">รายละเอียดบัญชี</h6>
            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <label class="form-label" for="status">สถานะ <span class="red">*</span></label>
                    <select name="status" id="status" class="form-select">
                        <option value="on" <?php if ($customer['status'] === 'on') echo 'selected'; ?>>เปิดใช้งาน</option>
                        <option value="off" <?php if ($customer['status'] === 'off') echo 'selected'; ?>>ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <label class="form-label" for="vat_type">ประเภทลูกค้า <span class="red">*</span></label>
                    <select name="vat_type" id="vat_type" class="form-select">
                        <option value="no_vat" <?php if ($customer['vat_type'] === 'no_vat') echo 'selected'; ?>>ไม่มี VAT</option>
                        <option value="vat" <?php if ($customer['vat_type'] === 'vat') echo 'selected'; ?>>มี VAT</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="salesperson_id">เซลที่ดูแล <span class="red">*</span></label>
                    <select name="salesperson_id" id="salesperson_id" class="form-select" required>
                        <option value="">-- เลือกเซล --</option>
                        <?php foreach ($salespersons as $s): ?>
                            <option value="<?php echo $s['salesperson_id'] ?>" <?php echo $s['salesperson_id'] == $customer['salesperson_id'] ? 'selected' : '' ?>><?php echo $s['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="shipFee">ค่าส่งสินค้า  <span class="red">*</span></label>
                    <select name="shipFee" id="shipFee" class="form-select">
                        <?php for ($i=0; $i<=10; $i++){ ?>
                            <option value="<?php echo $i; ?>" <?php echo $customer['shipFee'] == $i ? 'selected':''; ?>><?php echo $i; ?> บาท</option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <h6 class="mb-3 text-info">รายละเอียดสาย</h6>
            <div class="row g-3 mb-5">
                <div class="col-md-6">
                    <label class="form-label" for="route_id">สายลูกค้า <span class="red">*</span></label>
                    <select name="route_id" id="route_id" class="form-select" required>
                        <?php foreach ($routes as $r): ?>
                            <option value="<?php echo $r['id'] ?>" <?php echo $r['id'] == $customer['route_id'] ? 'selected' : '' ?>><?php echo $r['route_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="customer_code">รหัสลูกค้า</label>
                    <input type="text" name="customer_code" id="customer_code" class="form-control" onclick="this.select();" value="<?php echo htmlspecialchars($customer['customer_code']) ?>" autocomplete="off">
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="name">ชื่อลูกค้า <span class="red">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($customer['name']) ?>" autocomplete="off" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="nickname">ชื่อเล่น</label>
                    <input type="text" name="nickname" id="nickname" class="form-control" value="<?php echo htmlspecialchars($customer['nickname']) ?>" autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="phone">เบอร์โทร</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($customer['phone']) ?>" autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="email">อีเมล</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']) ?>" autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="lindID">ไอดีไลน์</label>
                    <input type="text" id="lindID" name="lindID" value="<?php echo htmlspecialchars($customer['lineID']) ?>" autocomplete="off" class="form-control">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="address">ที่อยู่</label>
                    <textarea name="address" id="address" class="form-control" rows="2"><?php echo htmlspecialchars($customer['address']) ?></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="remark">หมายเหตุ</label>
                    <textarea name="remark" id="remark" placeholder="บันทึกช่วยจำ" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <h6 class="mb-3 text-info">รายละเอียดการเงิน</h6>
            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <label class="form-label" for="payment_type">การชำระเงิน</label>
                    <select name="payment_type" id="payment_type" class="form-select" required onchange="showBank(this.value);">
                        <option value="เงินสด" <?php if ($customer['payment_type'] === 'เงินสด') echo 'selected'; ?>>เงินสด</option>
                        <option value="โอน" <?php if ($customer['payment_type'] === 'โอน') echo 'selected'; ?>>โอน</option>
                    </select>
                </div>
                <div class="col-md-4" id="optionBankAccount" style="<?php if ($customer['payment_type'] === 'เงินสด') echo 'display: none;'; ?>" >
                    <label class="form-label" for="bank_account_id">บัญชีที่ใช้รับโอน <span class="red">*</span></label>
                    <select name="bank_account_id" id="bank_account_id" class="form-select">
                        <option value="">-- เลือกบัญชี --</option>
                        <?php foreach ($banks as $b): ?>
                            <option value="<?php echo $b['id']; ?>" <?php echo $b['id'] == $customer['bank_account_id'] ? 'selected' : '' ?>><?php echo $b['display_name'].' - '.$b['bank_name'].' '.$b['account_name'].' ('.$b['account_number'].')'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const optionBankAccount = $("#optionBankAccount");

    $(()=>{
        // optionBankAccount.hide();
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