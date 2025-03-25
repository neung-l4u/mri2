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
$transaction_count = $_POST['transaction_count'] ?? 0;
$bank_id = $_POST['bank_id'] ?? '';
$display_name = $_POST['display_name'] ?? '';
$account_name = $_POST['account_name'] ?? '';
$account_number = $_POST['account_number'] ?? '';
$branch = $_POST['branch'] ?? '';
$opened_at = $_POST['opened_at'] ?? date('Y-m-d');
$status = $_POST['status'] ?? 'active';
$updated_by = $_SESSION['user_id'];

if ($display_name === '' || $account_name === '' || $account_number === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("UPDATE bank_accounts SET
  display_name = ?,
  account_name = ?,
  account_number = ?,
  branch = ?,
  open_date = ?,
  transaction_count = ?,
  status = ?,
  updated_by = ?,
  updated_at = NOW()
  WHERE id = ?",
    $display_name, $account_name, $account_number, $branch, $opened_at, $transaction_count, $status, $updated_by, $id);

header("Location: bank_account_list.php?id=" . $bank_id);
exit;
