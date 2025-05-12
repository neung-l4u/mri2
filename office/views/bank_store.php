<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';
global $db;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../index.php");
    exit;
}

$bank_name = trim($_POST['bank_name'] ?? '');
$status = $_POST['status'] ?? 'off';
$created_by = $_SESSION['user_id'];

if ($bank_name === '') {
    die('กรุณากรอกข้อมูลให้ครบถ้วน');
}

$db->query("INSERT INTO banks
  (bank_name, status, created_by)
  VALUES (?, ?, ?)",
    $bank_name, $status, $created_by);

header("Location: main.php?p=banks");
exit;