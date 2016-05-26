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
		<div id="budgetinfo">
		<center><h2>Choose Your Outpost</h2></center><br>
		<?php
		if($msg==1)
		{
			echo '<center><strong>You must use a unique name for your outpost.</strong></center><br>';
		}
		?>
	  <?php
		$playerfactionnum = mysql_query("SELECT numoffactions FROM users WHERE username = '$username'") or die(mysql_error());
		$factionnumrows = mysql_fetch_row($playerfactionnum);
		$factionamount = $factionnumrows[0];
		if($factionamount == 1)
		{
			$playerfactionquery = mysql_query("SELECT factionname FROM $factiondb WHERE factionuser = '$username'") or die(mysql_error());
			$playerfactionrows = mysql_fetch_row($playerfactionquery);
			$playerfaction1 = $playerfactionrows[0];
			
			echo'<form id=\'factionchoice\' action=\'factionchoose.php\' method=\'post\'>
				<label for=\'colony\' >Outpost:</label>
				<select name="colony">
				<option value="'.$playerfaction1.'">'.$playerfaction1.'</option>
				</select>
				<input type=\'submit\' name=\'Submit\' value=\'Submit\' />
				</form> <br/>
				<br/>
				<h2>Found a new Command Outpost</h2>
				<form id=\'newcolony\' action=\'newcolony.php\' method=\'post\'>
				<label for=\'colonyname\' >Outpost Name:</label>
				<input type=\'text\' name=\'colonyname\' id=\'colonyname\'  maxlength="30" /><br>
				<input type=\'submit\' name=\'Submit\' value=\'Submit\' />
				</form>';			
		}
		else
		{
			$playerfactionquery = mysql_query("SELECT factionname FROM $factiondb WHERE factionuser = '$username'");
			while ($row = mysql_fetch_assoc($playerfactionquery)) 
			{
				$factions[] = $row['factionname'];
			}
			$playerfaction1 = $factions[0];
			$playerfaction2 = $factions[1];
			
			echo'<form id=\'factionchoice\' action=\'factionchoose.php\' method=\'post\'>
				<label for=\'colony\' >Outpost:</label>
				<select name="colony">
				<option value="'.$playerfaction1.'">'.$playerfaction1.'</option>
				<option value="'.$playerfaction2.'">'.$playerfaction2.'</option>
				</select><br>
				<input type=\'submit\' name=\'Submit\' value=\'Submit\' />
				</form> ';
		}
	  ?><br><br>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
