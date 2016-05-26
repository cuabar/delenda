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
		<?php
			$getmessages = mysql_query("SELECT * FROM privatemessages WHERE userto='$username' AND allegiance='$allegiance' AND messageread='unread'");
			$messagenum = mysql_num_rows($getmessages);
			if($messagenum > 0)
			{
				echo '<div id="messagealert"><b>You have <a href="account.php#messages">private messages</a></b></div>';
			}
		?>
		<div id="colonyinfo">
		<!--Script for getting and displaying relevant colony info-->
			<?php	$getcolonyinfo = mysql_query("SELECT factionname,FORMAT(factionbank,0),FORMAT(factionpop,0),happiness,crime,healthcare,education,resource,FORMAT(land,0) FROM $factiondb WHERE factionname = '$chosencolony' AND factionuser = '$username'") or die(mysql_error());
			$colonyinforows = mysql_fetch_row($getcolonyinfo);
			echo '<center><h2>'.$colonyinforows[0].'</h2><br><table>
			<tr><th><b>Population: </b></th><td>'.$colonyinforows[2].'</td></tr>
			<tr><th><b>Bank Balance: </b></th><td>'.$colonyinforows[1].'</td></tr>
			<tr><th><b>Happiness: <b></th><td>'.$colonyinforows[3].'%</td></tr>
			<tr><th><b>Crime Rate: <b></th><td>'.$colonyinforows[4].'%</td></tr>
			<tr><th><b>Healthcare Coverage: <b></th><td>'.$colonyinforows[5].'%</td></tr>
			<tr><th><b>Education: <b></th><td>'.$colonyinforows[6].'%</td></tr>
			<tr><th><b>Resources: <b></th><td><div id="capitalise">'.$colonyinforows[7].'</div></td></tr>
			<tr><th><b>Available Land: <b></th><td>'.$colonyinforows[8].' km<sup>2</sup></td></tr></table></center>';?>
			<!--End Script-->
		</div>
		<div id="factioninfo">
			<center><?php include 'includes/imageselect.php' ?></center>
			<!--Script for getting relevant faction info-->
			<?php $getfactioninfo = mysql_query("SELECT worlds,colonies FROM factionstats WHERE factionname = '$allegiance'");
			$factioninforows = mysql_fetch_row($getfactioninfo);
			echo '<center><table>
			<tr><th><b>Systems Controlled: </b></th><td>'.$factioninforows[0].'</td></tr>
			<tr><th><b>Colonies: </b></th><td>'.$factioninforows[1].'</td></tr></table></center>';?>
			<!--End Script-->
		</div>
		<div id="clearfloat">
		&nbsp
		</div>
		<div id="projectinfo">
			<?php $getcombatinfo = mysql_query("SELECT FORMAT(trainingground,0),FORMAT(troops,0),FORMAT(troopmax,0) FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'") or die(mysql_error());
			$combatinforows = mysql_fetch_row($getcombatinfo);
			$trainingnumber = $combatinforows[0];
			$troopnumber = $combatinforows[1];
			$troopmax = $combatinforows[2];?>
			<div id="combatinfo">
			<table>
			<tr><th>Training Grounds</th><td><?php echo $trainingnumber; ?></td></tr>
			<tr><th>Troops Ready to Deploy</th><td><?php echo $troopnumber; ?></td></tr>
			<tr><th>Maximum Troops</th><td><?php echo $troopmax; ?></td></tr>
			</table>
			</div>
		
		<!--Get All Research Info And Display it-->
			<?php $getresearchinfo = mysql_query("SELECT FORMAT(techcomplex,0),FORMAT(researchpoints,0) FROM $factiondb WHERE factionname = '$chosencolony' AND factionuser = '$username'") or die(mysql_error());
			$researchinforows = mysql_fetch_row($getresearchinfo);
			$researchcomplexes = $researchinforows[0];
			$researchpoints = $researchinforows[1];?>
			<div id="researchinfo">
			<table>
			<tr><th>Tech Complexes</th><td><?php echo $researchcomplexes; ?></td></tr>
			<tr><th>Research Points</th><td><?php echo $researchpoints; ?></td></tr>
			</table>
			</div>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
