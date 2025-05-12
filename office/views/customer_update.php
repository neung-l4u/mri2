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

$id = $_POST['id'] ?? 0;
$code = !empty($_POST['customer_code']) ? $_POST['customer_code'] : $customerCode;
$name = !empty($_POST['name']) ? trim($_POST['name']) : null;
$nickname = !empty($_POST['$nickname']) ? trim($_POST['$nickname']) : NULL;
$phone = !empty($_POST['phone']) ? trim($_POST['phone']) : NULL;
$email = !empty($_POST['email']) ? trim($_POST['email']) : NULL;
$lindID = !empty($_POST['lindID']) ? trim($_POST['lindID']) : NULL;
$address = !empty($_POST['address']) ? trim($_POST['address']) : NULL;
$remark = !empty($_POST['remark']) ? trim($_POST['remark']) : NULL;
$route_id = !empty($_POST['route_id']) ? trim($_POST['route_id']) : NULL;
$salesperson_id = !empty($_POST['salesperson_id']) ? trim($_POST['salesperson_id']) : NULL;
$payment_type = !empty($_POST['payment_type']) ? trim($_POST['payment_type']) : NULL;
$bank_account_id = !empty($_POST['bank_account_id']) ? trim($_POST['bank_account_id']) : NULL;
$vat_type = !empty($_POST['vat_type']) ? trim($_POST['vat_type']) : 'no_vat';
$status = !empty($_POST['status']) ? trim($_POST['status']) : 'on';
$updated_by = $_SESSION['user_id'];


if ($id == 0 || $name === '' || !$route_id) {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("UPDATE customers SET
  name = ?,
  nickname = ?,
  phone = ?,
  email = ?,
  lineID = ?,
  address = ?,
  remark = ?,
  route_id = ?,
  salesperson_id = ?,
  payment_type = ?,
  bank_account_id = ?,
  vat_type = ?,
  status = ?,
  updated_by = ?,
  updated_at = NOW()
  WHERE id = ?",
    $name, $nickname, $phone, $email, $lindID, $address, $remark, $route_id, $salesperson_id, $payment_type, $bank_account_id, $vat_type, $status, $updated_by, $id);

header("Location: customer_list.php?route_id=$route_id");
exit;