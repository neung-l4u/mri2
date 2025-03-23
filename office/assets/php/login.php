<?php
global $db;
session_start();
include_once('../db/db.php');
include_once('../db/initDB.php');

$salt = "MRI";
$response["result"] = "";
$response["msg"] = "";

$act = $_POST['act'] ?? '';
$username = trim($_POST['username']) ?? '';
$password = trim($_POST['password']) ?? '';
$remember = isset($_POST['remember']);

if ($act === 'login') {
    if ($username === '' || $password === '') {
        $response['result'] = 'fail';
        $response["msg"] = "กรุณากรอกข้อมูลให้ครบ";
    }else{
        $passwordAddSalt = $salt . $password;
        $data["passwordHash"] = $passwordAddSalt;
        $data["action"] = $act;
        $data["user"] = $username;
        $data["remember"] = $remember;
        $response['data'] = $data;

        // ค้นหาจากอีเมลหรือเบอร์โทร
        $user = $db->query("SELECT * FROM users WHERE (email = ? OR phone = ?) AND deleted_at IS NULL AND status = 'on' LIMIT 1", $data["user"], $data["user"])->fetchArray();
        if (!$user) {
            $response['result'] = 'fail';
            $response["msg"] = "ไม่พบผู้ใช้นี้";
        }else{
            if (!password_verify($data["passwordHash"], $user['password_hash'])) { // ตรวจสอบรหัสผ่าน (เข้ารหัส)
                $response['result'] = 'fail';
                $response["msg"] = "รหัสผ่านไม่ถูกต้อง";
            }else{
                $response['result'] = 'success';
                $response["msg"] = "เข้าสู่ระบบสำเร็จ!";
                $response["role"] = $user['role'];

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;

                if (!empty($user['salesperson_id'])) {
                    $_SESSION['salesperson_id'] = $user['salesperson_id'];
                }

                // remember me (cookie แบบพื้นฐาน)
                if ($remember) {
                    setcookie("remember_remember", $remember, time() + (86400 * 30), "/");
                    setcookie("remember_username", $username, time() + (86400 * 30), "/");
                    setcookie("remember_password", $password, time() + (86400 * 30), "/");
                }else{
                    setcookie('remember_remember', '', time() - 3600, '/');
                    setcookie('remember_username', '', time() - 3600, '/');
                    setcookie('remember_password', '', time() - 3600, '/');
                }

                // อัปเดตเวลา login ล่าสุด
                $db->query("UPDATE users SET last_login = NOW() WHERE id = ?", $user['id']);
            }//else
        }//else

    }
    echo json_encode($response);
    exit;

}else{
    $response['result'] = 'fail';
    $response["msg"] = "เข้าสู่ระบบไม่ถูกต้อง";
    echo json_encode($response);
    exit;
}