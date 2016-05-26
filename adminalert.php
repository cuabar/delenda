<?php
require 'includes/connect.php';

$msg = $_GET['msg'];
$msg = stripslashes($msg);
$msg = mysql_real_escape_string($msg);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Delenda Est</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body onload="recordDisable()">
<!-- Begin Wrapper -->
<div id="wrapper">
  <!-- Begin Header -->
  <div id="header"><h1><?php include'includes/header.php' ?></h1></div>
  <!-- End Header -->
  <!-- Begin Naviagtion -->
  <div id="navigation"><?php include 'includes/menu.php' ?></div>
  <!-- End Naviagtion -->
  <!-- Begin Content -->
  <div id="content">
	<div id="gamecontent">
		<div id="constructinfo">
			<div id="combatinfo">
			<center>
			<?php
			if($msg == 1)
			{
				echo '<strong>You must fill in all fields!</strong><br><br>';
			}
			elseif($msg == 2)
			{
				echo '<strong>Incorrect Password</strong><br><br>';
			}
			?>
				<form id="sendmessage" action="alertsend.php" method="POST">
				<b>Subject: </b> <input type="text" name="subject" maxlength="40"><br>
				<b>Message:</b><br>
				<textarea name="message" rows="10" cols="50"></textarea><br>
				<input type="password" name="admincheck" maxlength="40"><br>
				<input type="submit" value="Send">
				</form>
				<br>
			</center>
			</div>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>