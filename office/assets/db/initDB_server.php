<?php
date_default_timezone_set("Asia/Bangkok");
error_reporting(E_ERROR | E_PARSE);

$dbHost = 'localhost';
$dbUser = 'u875011897_dev_neung';
$dbPass = 'qhrXQ6T2c?wAt_Z!';
$dbName = 'u875011897_mri_inventory';

$db = new db($dbHost, $dbUser, $dbPass, $dbName);
