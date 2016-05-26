<?php
require 'includes/connect.php';

session_start();

if(!isset($_SESSION['username']))
{
	header('Location: ../index.php');
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Delenda Est</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="game/styles.css" />
</head>
<body>
<!-- Begin Wrapper -->
<div id="wrapper">
  <!-- Begin Header -->
  <div id="header"><h1>Delenda Est</h1></div>
  <!-- End Header -->
  <!-- Begin Naviagtion -->
  <div id="navigation"> <center><ul class="navul">
      <li><a href="index.php">Home</a></li>
	  <li><a href="about.php">About</a></li>
	  <li><a href="newaccount.php">Register</a></li>
</ul></center></div>
  <!-- End Naviagtion -->
  <!-- Begin Content -->
  <div id="content">
	<div id="gamecontent">
		<div id="constructinfo">
		<!--PHP CODE
	Examines whether the player controls one or two colonies.
	Then checks which faction player is a member of.
	Then presents a form to select colony. First 'if' branch provides list for one faction, else branch provides list for two factions.-->
      <h2>Choose Your Outpost</h2>
	  <?php
		$playerfactionnum = mysql_query("SELECT numoffactions FROM users WHERE username = '{$_SESSION['username']}'") or die(mysql_error());
		$factionnumrows = mysql_fetch_row($playerfactionnum);
		$factionamount = $factionnumrows[0];
		if($factionamount == 1)
		{
			$factquery = mysql_query("SELECT allegiance FROM users WHERE username = '{$_SESSION['username']}'");
			$factqueryrows = mysql_fetch_row($factquery);
			$playerteam = $factqueryrows[0];
			$playerallegiance = $factqueryrows[0].'factions';

			$playerfactionquery = mysql_query("SELECT factionname FROM $playerallegiance WHERE factionuser = '{$_SESSION['username']}'") or die(mysql_error());
			$playerfactionrows = mysql_fetch_row($playerfactionquery);
			$playerfaction1 = $playerfactionrows[0];
			
			echo'<form id=\'factionchoice\' action=\'factionchoose.php\' method=\'post\'>
				<label for=\'colony\' >Outpost:</label>
				<select name="colony">
				<option value="'.$playerfaction1.'">'.$playerfaction1.'</option>
				</select>
				<input type="hidden" name="playerteam" id="playerteam" value="'.$playerteam.'" /><br />
				<input type=\'submit\' name=\'Submit\' value=\'Submit\' />
				</form> <br/>
				<br/>
				<h2>Found a new Outpost</h2>
				<form id=\'newcolony\' action=\'newcolony.php\' method=\'post\'>
				<label for=\'colonyname\' >Outpost Name:</label>
				<input type=\'text\' name=\'colonyname\' id=\'colonyname\'  maxlength="30" /><br>
				<input type=\'submit\' name=\'Submit\' value=\'Submit\' />
				</form>';			
		}
		else
		{
			$factquery = mysql_query("SELECT allegiance FROM users WHERE username = '{$_SESSION['username']}'") or die(mysql_error());
			$factqueryrows = mysql_fetch_row($factquery);
			$playerteam = $factqueryrows[0];
			$playerallegiance = $factqueryrows[0].'factions';
			
			$playerfactionquery = mysql_query("SELECT factionname FROM $playerallegiance WHERE factionuser = '{$_SESSION['username']}'");
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
				</select>
				<input type="hidden" name="playerteam" id="playerteam" value="'.$playerteam.'" /><br /><br />
				<input type=\'submit\' name=\'Submit\' value=\'Submit\' />
				</form> ';
		}
	  ?>
		</div>
	</div>
  </div>
</div>
</body>
</html>