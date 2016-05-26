<?php
include 'includes/connect.php';

require("PasswordHash.php");

session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$unsafe = array("'", ".", '"');
$username = str_replace($unsafe, "", $username);

$hasher=new PasswordHash(8,false);
$storedhash = "*";

$sql = mysql_query("SELECT password FROM users WHERE username = '$username'");
$result = mysql_fetch_row($sql);
$storedhash = $result[0];

$check=$hasher->CheckPassword($password,$storedhash);

if($check)
{
	$allegiancequery = mysql_query("SELECT allegiance FROM users WHERE username = '$username'");
	$allegiancerows = mysql_fetch_row($allegiancequery);
	$allegiance = $allegiancerows[0];
	$_SESSION['username'] = $username;
	$_SESSION['allegiance'] = $allegiance;
	$updatelast = mysql_query("UPDATE users SET lastlogin = NOW() WHERE username='$username' AND allegiance='$allegiance'");
	header('Location: game.php');
	echo 'Username: '.$username.'<br>'.$password;
}
else
{
	echo "Wrong Login Details!<br>".$username.'<br>'.$password;
}
?>

<br>
<a href="index.php">Return to Main</a>