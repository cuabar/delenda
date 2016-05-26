<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

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
		<center><a href="#missions">Mission Reports</a> | <a href="#warlog">War Log</a></center><br><hr></hr><br>
			<div id="combatinfo">
				<h3><a id="missions">Mission Reports</a></h3>
				<?php
					$getreports = mysql_query("SELECT * FROM missionresults WHERE allegiance='$allegiance' AND colony='$chosencolony' AND user='$username'");
					echo '<center><a href="deletereports.php">Clear Mission Reports</a></center>';
					while($reportsarray = mysql_fetch_array($getreports))
					{
						echo $reportsarray['missionresults'].'<br>';
					}
				?>
				<br><hr></hr><br>
				<h3><a id="warlog">War Log</a></h3>
				<?php
					//Maginot
					$maginot_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='6' GROUP BY owner DESC");
					while($maginot_control_row = mysql_fetch_array($maginot_control))
					{
						if($maginot_control_row['COUNT(*)'] == 10)
						{
							switch($maginot_control_row['owner'])
							{
								case 'solidarity':
								$magowner = 'the Solidarity';
								break;
								case 'mercantile':
								$magowner = 'the Mercantile Union';
								break;
								case 'authority':
								$magowner = 'the Origin Authority';
								break;
							}
							echo '<center><b>The Maginot Sector is controlled by '.$magowner.'.</b></center><br>';
						}
					}
					//Erebus
					$erebus_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='4' GROUP BY owner DESC");
					while($erebus_control_row = mysql_fetch_array($erebus_control))
					{
						if($erebus_control_row['COUNT(*)'] == 10)
						{
							switch($erebus_control_row['owner'])
							{
								case 'solidarity':
								$erebowner = 'the Solidarity';
								break;
								case 'mercantile':
								$erebowner = 'the Mercantile Union';
								break;
								case 'authority':
								$erebowner = 'the Origin Authority';
								break;
							}
							echo '<center><b>The Erebus Sector is controlled by '.$erebowner.'.</b></center><br>';
						}
					}	
					//Hadrian
					$hadrian_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='5' GROUP BY owner DESC");
					while($hadrian_control_row = mysql_fetch_array($hadrian_control))
					{
						if($hadrian_control_row['COUNT(*)'] == 10)
						{
							switch($hadrian_control_row['owner'])
							{
								case 'solidarity':
								$hadowner = 'the Solidarity';
								break;
								case 'mercantile':
								$hadowner = 'the Mercantile Union';
								break;
								case 'authority':
								$hadowner = 'the Origin Authority';
								break;
							}
							echo '<center><b>The Hadrian Sector is controlled by '.$hadowner.'.</b></center><br>';
						}
					}
				?>
				</div>
				<?php
					$getlogdate = mysql_query("SELECT DISTINCT date FROM warlogs WHERE allegiance='$allegiance' ORDER BY date DESC");
					while($logsarray = mysql_fetch_array($getlogdate))
					{
						echo '<h3>'.$logsarray['date'].'</h3>';
						$date = $logsarray['date'];
						$getlogs = mysql_query("SELECT * FROM warlogs WHERE allegiance='$allegiance' AND date='$date' ORDER BY time DESC");
						while($warlogs = mysql_fetch_array($getlogs))
						{
							$battlezone = $warlogs['battlezone'];
							$getsector = mysql_query("SELECT zonesector FROM battlezones WHERE zonename = '$battlezone'") or die(mysql_error());
							$sectorarray = mysql_fetch_array($getsector);
							$sector = $sectorarray['zonesector'];
							switch($sector)
							{
								case '1':
								$sectorname = 'Centauri Sector';
								break;
								case '2':
								$sectorname = 'Liberty Sector';
								break;
								case '3':
								$sectorname = 'Fortitude Sector';
								break;
								case '4':
								$sectorname = 'Erebus Sector';
								break;
								case '5':
								$sectorname = 'Hadrian Sector';
								break;
								case '6':
								$sectorname = 'Maginot Sector';
								break;
							}
							if($warlogs['type'] == 'loss')
							{
								echo 'Your faction lost control of <strong>'.$warlogs['battlezone'].'</strong> in the <strong>'.$sectorname.'</strong> at: '.date("H", strtotime($warlogs['time'])).'00 hours.<br>';
							}
							elseif($warlogs['type'] == 'capture')
							{
								echo 'Your faction captured <strong>'.$warlogs['battlezone'].'</strong> in the <strong>'.$sectorname.'</strong> at: '.date("H", strtotime($warlogs['time'])).'00 hours.<br>';
							}
							elseif($warlogs['type'] == 'casualties')
							{
								if($warlogs['value2'] > 0)
								{
									echo 'Your faction suffered <strong>'.number_format($warlogs['value']).'</strong> casualties on '.$warlogs['battlezone'].'. '.number_format($warlogs['value2']).' managed to self-repair.<br>';
								}
								else
								{
									echo 'Your faction suffered <strong>'.number_format($warlogs['value']).'</strong> casualties on '.$warlogs['battlezone'].'. <br>';
								}
							}
						}
						echo '<br><div id="combatinfo"><hr></hr></div><br>';
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