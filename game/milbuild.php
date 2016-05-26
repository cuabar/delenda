<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$message = $_GET['msg'];
$message = stripslashes($message);
$message = mysql_real_escape_string($message);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Delenda Est</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
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
		<h2>Military Buildings</h2>
		<p>There are four types of military buildings. The Training Grounds builds the basic troop type (Conscripts, Guardians, Drones), the War Centre trains crews for Special Forces gear. The Barracks increases the amount of troops you can have on standby at one time. Command Centres increase the number of Logistical points you are given to use every tick.</p>
		<p>Barracks cost 20,000 to build, and take up 20 km<sup>2</sup>.</p>
		<p>Training Centres cost 100,000 to build and take up 20 km<sup>2</sup>.</p>
		<p>War Centres cost 250,000 to build and take up 30 km<sup>2</sup>.</p>
		<p>Command Centres cost 250,000 to build and take up 25km<sup>2</sup>.</p>
		<?php 
		if($message == 1)
		{
			echo '<br><strong>You cannot afford that construction project</strong>';
		}
		elseif($message == 2)
		{
			echo '<br><strong>You do not have enough space for this construction project.</strong>';
		}
		elseif($message == 3)
		{
			echo '<br><strong>You do not have that many buildings to demolish!</strong>';
		}
		$getcomplexquery = mysql_query("SELECT barracks,warcentre,land,factionbank,trainingground,commandcentre FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
		$getcomplexrows = mysql_fetch_row($getcomplexquery);
		$barracks = $getcomplexrows[0];
		$warcentres = $getcomplexrows[1];
		$availableland = $getcomplexrows[2];
		$balance = $getcomplexrows[3];
		$trainingground = $getcomplexrows[4];
		$commandcentres = $getcomplexrows[5];?>
		<br>
		<b>Available Land: </b> <?php echo number_format($availableland); ?><br>
		<b>Bank Balance: </b><?php echo number_format($balance); ?><br><br>
		<b>Number of Training Grounds: </b><?php echo number_format($trainingground); ?><br>
		<b>Number of Barracks: </b> <?php echo number_format($barracks); ?><br>
		<b>War Centres: </b><?php echo number_format($warcentres); ?><br>
		<b>Command Centres: </b><?php echo number_format($commandcentres); ?><br><br>
		
		<form id='research' action='barracksbuild.php' method='post'>
		<label for='training'>Training Grounds to Construct: </label>
		<input type='number' name='training' id='training' maxlength='3' value='0'><br>
		<label for='barracks'>Barracks to Construct: </label>
		<input type='number' name='barracks' id='barracks' maxlength='3' value='0'><br>
		<label for='warcentre'>War Centres to Construct: </label>
		<input type='number' name='warcentre' id='warcentre' maxlength='3' value='0'><br>
		<label for='warcentre'>Command Centres to Construct: </label>
		<input type='number' name='command' id='command' maxlength='3' value='0'><br>
		<input type='submit' name='submit' id='submit' value='Begin Construction'>
		</form>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
