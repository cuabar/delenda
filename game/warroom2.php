<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$message = $_GET['msg'];
$message = stripslashes($message);
$message = mysql_real_escape_string($message);

$kills = $_GET['kill'];
$kills = stripslashes($kills);
$kills = mysql_real_escape_string($kills);

$shielddestroy = $_GET['shields'];
$shielddestroy = stripslashes($shielddestroy);
$shielddestroy = stripslashes($shielddestroy);
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
				<?php
					$getfactioninfo = mysql_query("SELECT * FROM factionstats WHERE factionname = '$allegiance'") or die(mysql_error());
					$factioninfo = mysql_fetch_array($getfactioninfo);
					$factiontroopvar = $allegiance.'troops';
					$factionshieldvar = $allegiance.'shields';
					$getfactiontroops = mysql_query("SELECT $factiontroopvar FROM battlezones");
					$totaltroops = 0;
					$gettotalshields = mysql_query("SELECT SUM($factionshieldvar) FROM battlezones");
					$totalshields = mysql_result($gettotalshields, 0);
					if($allegiance == 'authority')
					{
						$getautowars = mysql_query("SELECT tier FROM technology WHERE techtype = 'autowars'");
						$autowars = mysql_result($getautowars, 0);
						$autowars = $autowars - 1;
						$autowars = $autowars*0.01;
					}
					while($factiontroopsarray = mysql_fetch_array($getfactiontroops))
					{
						$totaltroops = $totaltroops + $factiontroopsarray[$factiontroopvar];
					}
					switch($allegiance)
					{
						case 'authority':
						$allegiancename = 'The Origin Authority';
						$shieldname = 'Theatre Shields';
						break;
						case 'solidarity':
						$allegiancename = 'The Solidarity';
						$shieldname = 'Bunkers';
						break;
						case 'mercantile':
						$allegiancename = 'The Mercantile Union';
						$shieldname = 'Interceptor';
						break;
					}
				?>
				<center><h2><?php 
				if($allegiance=='solidarity')
				{
					echo '<br><img src="solidarityfiles/images/emblem2.png" width="200" height="190"/><br>';
				}
				elseif($allegiance=='mercantile')
				{
					echo '<br><img src="mercantilefiles/images/emblem.png" width="97" height="200"/><br>';
				}
				elseif($allegiance=='authority')
				{
					echo '<br><img src="authorityfiles/images/emblem2.png" width="97" height="200"/><br>';
				}
				echo '<br>'.$allegiancename; ?></h2><br></center>
				<div id="warroomcolumn">
				<b>Systems Controlled:</b> <?php echo $factioninfo['worlds']; ?><br>
				<b>Active Command Outposts:</b> <?php echo $factioninfo['colonies']; ?><br>
				<b>Troops Deployed:</b> <?php echo number_format($totaltroops); ?><br>
				<b>Troop Effectiveness:</b> <?php echo $factioninfo['troopeffective']; ?><br>
				<b><?php echo $shieldname; ?>:</b> <?php echo $totalshields; ?><br>
				<?php if($allegiance == 'authority')
				{ echo '<b>Self Repair Effectiveness:</b> '.$factioninfo['troopregen'].'<br>
				<b>Drone Construction Rate:</b> '.$factioninfo['troopbuild'].'<br>
				<b>Autowar Defensive Effectiveness:</b> '.$autowars.'<br>';}
					else
					{ echo '<b>Troop Conscription Rate: </b>'.$factioninfo['troopbuild'].'<br>';}?>
				<b>Desperation:</b> <?php echo $factioninfo['desperation']; ?><br>
				<b>Affluence:</b> <?php echo $factioninfo['affluence']; ?><br>
				</div>
			</div>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>