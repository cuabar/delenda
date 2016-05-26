<?php
require 'includes/connect.php';

$message = $_POST['message'];
$subject = $_POST['subject'];
$password = $_POST['password'];

$message=stripslashes($message);
$subject=stripslashes($subject);
$password = stripslashes($password);

$message=mysql_real_escape_string($message);
$subject=mysql_real_escape_string($subject);
$password = mysql_real_escape_string($password);

if($_POST['message'] && $_POST['subject'])
{
	if($password == 'adminPassAlert1331')
	{
		$getallusers = mysql_query("SELECT * FROM users");
		while($usersarray=mysql_fetch_array($getallusers))
		{
			$userto = $usersarray['username'];
			$allegiance = $usersarray['allegiance'];
			$insertmessage = mysql_query("INSERT INTO privatemessages(userto, userfrom, message, subject, timestamp, allegiance) VALUES('$userto', 'ADMIN', '$message', '$subject', CURDATE(), '$allegiance')") or die(mysql_error());
		}
		header('Location: adminalert.php');
	}
	else
	{
		echo $password;
	}
}
else
{
	header('Location: adminalert.php?msg=1');
}
?>