<?php
session_start();
require_once '../assets/db/db.php';
require_once '../assets/db/initDB.php';

global $db;

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales') {
    echo json_encode([]);
    exit;
}

$salesperson_id = $_SESSION['salesperson_id'];
$route_id = $_GET['route_id'] ?? null;

if (!$route_id) {
    echo json_encode([]);
    exit;
}

$customers = $db->query("SELECT id, name FROM customers WHERE route_id = ? AND salesperson_id = ? AND deleted_at IS NULL AND status = 'on' ORDER BY name", $route_id, $salesperson_id)->fetchAll();

echo json_encode($customers);
exit;
