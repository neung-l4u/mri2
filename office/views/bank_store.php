<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$display_name = $_POST['display_name'] ?? '';
$account_name = $_POST['account_name'] ?? '';
$account_number = $_POST['account_number'] ?? '';
$bank_name = $_POST['bank_name'] ?? '';
$branch = $_POST['branch'] ?? '';
$opened_at = $_POST['opened_at'] ?? date('Y-m-d');
$status = $_POST['status'] ?? 'active';
$created_by = $_SESSION['user_id'];

if ($display_name === '' || $account_name === '' || $account_number === '' || $bank_name === '') {
    die('กรุณากรอกข้อมูลให้ครบถ้วน');
}

$db->query("INSERT INTO bank_accounts
  (display_name, account_name, account_number, bank_name, branch, opened_at, status, created_by)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
    $display_name, $account_name, $account_number, $bank_name, $branch, $opened_at, $status, $created_by);

header("Location: bank_list.php");
exit;