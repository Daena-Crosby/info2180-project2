<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

$_SESSION = [];
session_destroy();
header("Location: login.php");
exit();
?>
