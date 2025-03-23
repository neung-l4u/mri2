<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$id = $_POST['id'] ?? 0;
$display_name = $_POST['display_name'] ?? '';
$account_name = $_POST['account_name'] ?? '';
$account_number = $_POST['account_number'] ?? '';
$bank_name = $_POST['bank_name'] ?? '';
$branch = $_POST['branch'] ?? '';
$opened_at = $_POST['opened_at'] ?? date('Y-m-d');
$status = $_POST['status'] ?? 'active';
$updated_by = $_SESSION['user_id'];

if ($display_name === '' || $account_name === '' || $account_number === '' || $bank_name === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("UPDATE bank_accounts SET
  display_name = ?,
  account_name = ?,
  account_number = ?,
  bank_name = ?,
  branch = ?,
  opened_at = ?,
  status = ?,
  updated_by = ?,
  updated_at = NOW()
  WHERE id = ?",
    $display_name, $account_name, $account_number, $bank_name, $branch, $opened_at, $status, $updated_by, $id);

header("Location: bank_list.php");
exit;
