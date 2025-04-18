<?php
session_start();
include '../assets/db/db.php';
include "../assets/db/initDB.php";
global $db;

$param['action'] = !empty($_POST['action']) ? $_POST['action'] : "";
$param['userID'] = !empty($_POST['userID']) ? $_POST['userID'] : "";
$param['editID'] = !empty($_POST['editID']) ? $_POST['editID'] : "";
$param['delID'] = !empty($_POST['delID']) ? $_POST['delID'] : "";
$param['inputName'] = !empty($_POST['inputName']) ? $_POST['inputName'] : "no name";
$param['token'] = !empty($_POST['token']) ? $_POST['token'] : "";

$return['result'] = '';
$return['msg'] = '';
$return['data'] = '';
$return['act'] = $param['action'];

if($param['action'] == "add"){
    //insert
    // บันทึก user
    /*$db->query("INSERT INTO users (username, name, nickname, phone, email, password_hash, role, salesperson_id, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'on', ?)",
    $username, $name, $nickname, $phone, $email, $hashed_password, $role, $salesperson_id, $created_by);*/

    $return['result'] = 'success';
    $return['msg'] = 'add user';
}else if ($param['action'] == "edit"){
    //update
//    $db->query("UPDATE users SET name = ?, nickname = ?, phone = ?, email = ?, updated_by = ?, updated_at = NOW() WHERE id = ?",
//        $name, $nickname, $phone, $email, $updated_by, $id);
    $return['msg'] = 'update user';
}else if ($param['action'] == "getEdit"){
    //select 1 row
    $user = $db->query("SELECT * FROM users WHERE id = ? AND deleted_at IS NULL", $param['editID'])->fetchArray();

    $row = array();
    $row[] = $user;

    $return['result'] = 'success';
    $return['data'] = $row;
    $return['msg'] = 'get edit user';

}else if ($param['action'] == "delete"){
    //delete
    $user = $db->query('UPDATE users SET `deleted_at` = NOW(), `deleted_by` = ?  WHERE `id` = ?'
        , $param['userID'], $param['delID']);
    $return['result'] = 'success';
    $return['msg'] = 'delete user';

}else if ($param['action'] == "read"){
    //select all row
    $users = $db->query("SELECT * FROM users WHERE deleted_at IS NULL")->fetchArray();

    $row = array();
    $i = 0;
    foreach ($users as $user) {
        $row[$i] = $user;
        $i++;
    }

    $return['result'] = 'success';
    $return['msg'] = count($row).' users found';
    $return['data'] = $row;
}

echo json_encode($return);