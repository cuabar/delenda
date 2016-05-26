<?php
require 'includes/connect.php';
require("PasswordHash.php");

$hasher = new PasswordHash(8, false);

$password = $_POST['password'];
$hash=$hasher->HashPassword($password);

$editpass = mysql_query("UPDATE users SET password=$hash WHERE userid=6") or die(mysql_error());
?>