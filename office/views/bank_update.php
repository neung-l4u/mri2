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
$bank_name = $_POST['bank_name'] ?? '';
$status = $_POST['status'] ?? 'off';
$updated_by = $_SESSION['user_id'];

if ($bank_name === '') {
    die('กรุณากรอกข้อมูลให้ครบ');
}

$db->query("UPDATE banks SET
  bank_name = ?,
  status = ?,
  updated_by = ?,
  updated_at = NOW()
  WHERE id = ?",
    $bank_name, $status, $updated_by, $id);

header("Location: bank_list.php");
exit;
