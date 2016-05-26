<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$message = $_GET['id'];

$message=stripslashes($message);

$message=mysql_real_escape_string($message);

$delete = mysql_query("DELETE FROM privatemessages WHERE id='$message'");
header('Location: account.php');
?>