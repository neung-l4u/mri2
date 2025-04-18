<?php
global $db;
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    header("Location: ../index.php");
    exit;
}

$salesperson_id = $_SESSION['salesperson_id'];

$customers = $db->query("SELECT id, customer_code, name FROM customers WHERE salesperson_id = ? AND deleted_at IS NULL AND status = 'on'", $salesperson_id)->fetchAll();
$banks = $db->query("SELECT id, display_name FROM bank_accounts WHERE status = 'active' AND deleted_at IS NULL ORDER BY display_name")->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>บันทึกรับชำระเงิน</title>
    <link href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2.min.css" rel="stylesheet">
    <link href="../assets/libs/select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <script src="../assets/libs/jQuery-v3.7.1/jquery-3.7.1.min.js"></script>
    <style>
        select.form-select option {
            white-space: normal !important;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <?php include "topNav.php"; ?>
    <div class="mb-3 card rounded border-0 shadow-sm">
        <nav class="card-body" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="main.php?p=dashboard">
                        <svg class="nav-icon mr-2" height="1.2em" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        แดชบอร์ด
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">รับชำระเงิน</li>
            </ol>
        </nav>
    </div>
    <h4 class="mb-4">บันทึกรับชำระเงินจากลูกค้า</h4>
    <div class="bg-white p-4 rounded shadow-sm">
        <form method="POST" action="payment_store.php">
            <div class="mb-3">
                <label class="form-label">เลือกลูกค้า</label>
                <select name="customer_id" id="customer_id" class="form-select" required>
                    <option value="">-- เลือกลูกค้า --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id'] ?>">
                            <?php echo $customer['customer_code'] ?> - <?php echo $customer['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="invoice-list"></div>

            <div class="mb-3">
                <label class="form-label">วันที่รับชำระ</label>
                <input type="date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d') ?>" required>
            </div>

            <div class="row mb-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">วิธีการชำระเงิน</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="method" id="method_cash" value="เงินสด">
                        <label class="form-check-label" for="method_cash">เงินสด</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="method" id="method_transfer" value="โอนเงิน">
                        <label class="form-check-label" for="method_transfer">โอนเงิน</label>
                    </div>
                </div>

                <div class="col-md-6" id="bank-account-group" style="display: none;">
                    <label class="form-label">รับเงินเข้าบัญชี</label>
                    <select name="bank_account_id" id="bank_account_id" class="form-select">
                        <option value="">-- เลือกบัญชี --</option>
                        <?php foreach ($banks as $bank): ?>
                            <option value="<?php echo $bank['id'] ?>">
                                <?php echo $bank['display_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">บันทึกเพิ่มเติม (ถ้ามี)</label>
                <textarea name="note" class="form-control" rows="2"></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">บันทึกการรับชำระเงิน</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/libs/select2/js/select2.min.js"></script>
<script>
    $(function () {
        $('#customer_id').select2({
            theme: 'bootstrap-5',
            placeholder: '-- เลือกลูกค้า --',
            width: '100%'
        });

        $('#bank_account_id').select2({
            theme: 'bootstrap-5',
            placeholder: '-- เลือกบัญชี --',
            width: '100%'
        });

        $('input[name="method"]').on('change', function () {
            if (this.value === 'โอนเงิน') {
                $('#bank-account-group').slideDown();
            } else {
                $('#bank-account-group').slideUp();
                $('#bank_account_id').val('').trigger('change');
            }
        });

        $('#customer_id').on('change', function () {
            let id = $(this).val();
            if (!id) return;
            $('#invoice-list').html('<div class="text-muted">กำลังโหลดรายการบิล...</div>');
            $.get('payment_invoices.php', { customer_id: id }, function (html) {
                $('#invoice-list').html(html);
            });
        });
    });
</script>
</body>
</html>