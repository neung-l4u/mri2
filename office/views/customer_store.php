<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$code = $_POST['customer_code'] ?? '';
$name = $_POST['name'] ?? '';
$nickname = $_POST['nickname'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';
$route_id = $_POST['route_id'] ?? '';
$salesperson_id = $_POST['salesperson_id'] ?? null;
$payment_type = $_POST['payment_type'] ?? 'โอน';
$bank_account_id = $_POST['bank_account_id'] ?? null;
$vat_type = $_POST['vat_type'] ?? 'no_vat';
$status = $_POST['status'] ?? 'on';
$created_by = $_SESSION['user_id'];

if ($code === '' || $name === '' || !$route_id) {
    die('กรุณากรอกข้อมูลให้ครบถ้วน');
}

$db->query("INSERT INTO customers (customer_code, name, nickname, phone, email, address, route_id, salesperson_id, payment_type, bank_account_id, vat_type, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
    $code, $name, $nickname, $phone, $email, $address, $route_id, $salesperson_id, $payment_type, $bank_account_id, $vat_type, $status, $created_by);

header("Location: customer_list.php?route_id=$route_id");
exit;
