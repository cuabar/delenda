<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$message = $_POST['message'];
$userto = $_POST['userto'];
$subject = $_POST['subject'];

$unsafe = array("'", ".", '"');
$userto = str_replace($unsafe, "", $userto);
$message =  str_replace("\r\n", "<br />", $message);
$message=stripslashes($message);
$userto=stripslashes($userto);
$subject=stripslashes($subject);

$message=mysql_real_escape_string($message);
$userto=mysql_real_escape_string($userto);
$subject=mysql_real_escape_string($subject);

if($_POST['message'] && $_POST['subject'] && $_POST['userto'])
{
	$insertmessage = mysql_query("INSERT INTO privatemessages(userto, userfrom, message, subject, timestamp, allegiance) VALUES('$userto', '$username', '$message', '$subject', CURDATE(), '$allegiance')") or die(mysql_error());
	header('Location: account.php');
}
else
{
	header('Location: account.php?msg=1');
}
?>