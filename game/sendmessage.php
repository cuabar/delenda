<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

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
			?>
				<form id="sendmessage" action="message.php" method="POST">
				<b>To:</b> <input type="text" name="userto" maxlength="30"><br>
				<b>Subject: </b> <input type="text" name="subject" maxlength="40"><br>
				<b>Message:</b><br>
				<textarea name="message" rows="10" cols="50"></textarea><br>
				<input type="submit" value="Send">
				</form>
				<br>
			</center>
			<hr></hr><br>
			<?php
			$getallmessages = mysql_query("SELECT * FROM privatemessages WHERE userto='$username' and allegiance='$allegiance'");
			
			while($messagesarray=mysql_fetch_array($getallmessages))
			{
				echo '<b>FROM: </b>'.$messagesarray['userfrom'].' | <b>Date Sent: </b>'.$messagesarray['timestamp'].'
				<br><b>'.$messagesarray['subject'].'<br>'.$messagesarray['message'].'<br><hr></hr>';
			}
			?>
			</div>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>