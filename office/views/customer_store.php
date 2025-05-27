<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$customerCode = 'C-' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);


$code = !empty($_POST['customer_code']) ? $_POST['customer_code'] : $customerCode;
$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$nickname = !empty($_POST['$nickname']) ? trim($_POST['$nickname']) : NULL;
$phone = !empty($_POST['phone']) ? trim($_POST['phone']) : NULL;
$email = !empty($_POST['email']) ? trim($_POST['email']) : NULL;
$lindID = !empty($_POST['lindID']) ? trim($_POST['lindID']) : NULL;
$address = !empty($_POST['address']) ? trim($_POST['address']) : NULL;
$remark = !empty($_POST['remark']) ? trim($_POST['remark']) : NULL;
$route_id = !empty($_POST['route_id']) ? $_POST['route_id'] : NULL;
$salesperson_id = !empty($_POST['salesperson_id']) ? $_POST['salesperson_id'] : NULL;
$payment_type = !empty($_POST['payment_type']) ? trim($_POST['payment_type']) : NULL;
$shipFee = !empty($_POST['shipFee']) ? $_POST['shipFee'] : '0';
$bank_account_id = !empty($_POST['bank_account_id']) ? $_POST['bank_account_id'] : NULL;
$vat_type = !empty($_POST['vat_type']) ? trim($_POST['vat_type']) : 'no_vat';
$status = !empty($_POST['status']) ? trim($_POST['status']) : 'on';
$created_by = $_SESSION['user_id'];

if ($code === '' || $name === '' || !$route_id) {
    die('กรุณากรอกข้อมูลให้ครบถ้วน');
}

$db->query("INSERT INTO customers (customer_code, name, nickname, phone, email, lineID, address, remark, route_id, salesperson_id, payment_type, shipFee, bank_account_id, vat_type, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
    $code, $name, $nickname, $phone, $email, $lindID, $address, $remark, $route_id, $salesperson_id, $payment_type, $shipFee, $bank_account_id, $vat_type, $status, $created_by);

header("Location: customer_list.php?route_id=$route_id");
exit;
