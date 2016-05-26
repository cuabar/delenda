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
			<?php $getzoneinfo = mysql_query("SELECT * FROM battlezones") or die(mysql_error());
			$getplayertroops = mysql_query("SELECT troops FROM $factiondb WHERE factionuser='$username' AND factionname = '$chosencolony'");
			$playertroops = mysql_result($getplayertroops, 0);
			
			switch($allegiance)
			{
				case 'authority':
				$bombtype='Conversion Bombs';
				$shieldtype='Theatre Shields';
				break;
				case 'solidarity':
				$bombtype='Nuclear Warheads';
				$shieldtype='Bunkers';
				break;
				case 'mercantile':
				$bombtype='Kinetic Impactors';
				$shieldtype='Interceptors';
				break;
			}
			
			echo '<strong>You have: '.number_format($playertroops).' troops to deploy.</strong><br><br>';
			
			if($message == 1)
			{
				echo '<strong>You cannot deploy that many troops.</strong><br><br>';
			}
			elseif($message == 2)
			{
				echo '<strong>You must enter a positive value.</strong><br><br>';
			}
			elseif($message == 3)
			{
				echo '<strong>You must enter a a numerical value.</strong><br><br>';
			}
			elseif($message == 4)
			{
				echo '<strong>You do not have that many troops deployed.</strong><br><br>';
			}
			elseif($message == 5)
			{
				echo '<strong>You do not have the capacity to withdraw that many troops.</strong><br><br>';
			}
			elseif($message == 6)
			{
				echo '<strong>You cannot deploy that many superweapons.</strong><br><br>';
			}
			elseif($message == 7)
			{
				echo '<strong>You must elect a target.</strong><br><br>';
			}
			
			if($kills > 0 || $shielddestroy > 0)
			{
				if($kills > 0)
				{
					echo '<strong>You killed '.number_format($kills).' enemy soldiers.</strong><br>';
				}
				if($shielddestroy > 0)
				{
					echo '<strong>You destroyed '.number_format($shielddestroy).' enemy defences.</strong><br>';
				}
				echo '<br>';
			}
			
			while($zonerows = mysql_fetch_array($getzoneinfo))
			{
				$zonename = $zonerows['zonename'];
				$zonesector = $zonerows['zonesector'];
				$owner = $zonerows['owner'];
				$str="";
				$zoneid = $zonerows['zoneid'];
				switch($owner)
				{
					case 'authority':
					$str = 'The Origin Authority';
					break;
					case 'solidarity':
					$str = 'The Solidarity';
					break;
					case 'mercantile':
					$str = 'The Mercantile Union';
					break;
				}
				$authoritytroops = $zonerows['authoritytroops'];
				$mercantiletroops = $zonerows['mercantiletroops'];
				$solidaritytroops = $zonerows['solidaritytroops'];
				$authinfluence = $zonerows['authorityinfluence'];
				$solinfluence = $zonerows['solidarityinfluence'];
				$mercinfluence = $zonerows['mercantileinfluence'];
				
				echo '<h2>Sector: '.$zonesector.'</h2><br>
				<b>Name: </b>'.$zonename.'<br>
				<b>Owned By: </b>'.$str.'<br>
				<b>Authority Troops: </b>'.number_format($authoritytroops).'<br>';
				if($allegiance == 'authority')
				{ $getshields = mysql_query("SELECT authorityshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Authority '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				echo '<b>Mercantile Troops: </b>'.number_format($mercantiletroops).'<br>';
				if($allegiance == 'mercantile')
				{ $getshields = mysql_query("SELECT mercantileshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Mercantile '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				echo '<b>Solidarity Troops: </b>'.number_format($solidaritytroops).'<br>';
				if($allegiance == 'solidarity')
				{ $getshields = mysql_query("SELECT solidarityshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Solidarity '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				echo '<span style="color:#980000">'.$authinfluence.'</span> &nbsp|&nbsp <span style="color:#000099">'.$mercinfluence.'</span> &nbsp|&nbsp <span style="color:#009900">'.$solinfluence.'</span><br>';
				echo '<h3>Deploy Troops to '.$zonename.'</h3>
				<form id="troopdeploy" action="battledeploy.php" method="post">
				<input type="number" name="deploy" value="0" maxlength="10"> 
				<input type="hidden" name="allegiance" value="'.$allegiance.'troops">
				<input type="hidden" name="location" value="'.$zonename.'">
				<input type="submit" value="Deploy Troops"><br><br></form>';
				
				$checkdeployed=mysql_query("SELECT deployment FROM deployments WHERE factionname='$chosencolony' AND username='$username' AND battlezone='$zoneid'");
				$checkdeployednum = mysql_num_rows($checkdeployed);
				if($checkdeployednum > 0)
				{
					$deploymentvalue = mysql_fetch_array($checkdeployed);
					$deploymentnumber = $deploymentvalue['deployment'];
					echo 'You have <strong>'.$deploymentnumber.'</strong> troops deployed here.<br>';
					echo '<form id="troopdeploy" action="battlewithdraw.php" method="post">
						<input type="number" name="deploy" value="0" maxlength="10"> 
						<input type="hidden" name="allegiance" value="'.$allegiance.'troops">
						<input type="hidden" name="location" value="'.$zonename.'">
						<input type="submit" value="Withdraw Troops"><br><br></form>';
				}
				$checkweapons=mysql_query("SELECT bombs, shields FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
				$weaponscheck = mysql_fetch_array($checkweapons);
				if($weaponscheck['bombs'] > 0 || $weaponscheck['shields'] > 0)
				{
					echo '<br><form name="weapondeploy" id="weapondeploy" action="weaponsdeploy.php" method="post">
					<input type="number" name="weapons" value="0">
					<input type="hidden" name="location" value="'.$zoneid.'">
					<select name="weapontype" id="zone'.$zoneid.'" onchange="disable(this.value,this.id);">';
					if($weaponscheck['bombs'] > 0)
					{
						echo '<option value="bombs">'.$bombtype.'</option>';
					}
					if($weaponscheck['shields'] > 0)
					{
						echo '<option value="shields">'.$shieldtype.'</option>';
					}
					echo '</select><br>';
					
					$soldisable = ' ';
					$mercdisable =' ';
					$authdisable =' ';
					if($solidaritytroops == 0)
					{
						$soldisable = 'disabled';
					}
					if($mercantiletroops == 0)
					{
						$mercdisable = 'disabled';
					}
					if($authoritytroops == 0)
					{
						$authdisable = 'disabled';
					}
					
					if($allegiance != 'solidarity')
					{
						echo '<input type="radio" name="targetzone'.$zoneid.'" '.$soldisable.' value="solidarity">Solidarity &nbsp';
					}
					if($allegiance != 'mercantile')
					{
						echo '<input type="radio" name="targetzone'.$zoneid.'" '.$mercdisable.' value="mercantile">Mercantile Union &nbsp';
					}
					if($allegiance != 'authority')
					{
						echo '<input type="radio" name="targetzone'.$zoneid.'" '.$authdisable.' value="authority">Origin Authority &nbsp';
					}
					echo '  <input type="submit" value="Deploy"></form>';
				}
				echo '<br>';
			}
			?>
			
			</div>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
<script type="text/javascript">
var target1disable;
var target2disable;
function disable(val,targetid)
{
	var target = document.getElementsByName("target"+targetid);
	if(val == 'shields')
	{
		target1disable = target.item(0).getAttribute("disabled");
		target2disable = target.item(1).getAttribute("disabled");
		target.item(0).disabled = true;
		target.item(1).disabled = true;
	}
	else
	{
		if(target1disable != "")
		{
			target.item(0).disabled = false;
		}
		if(target2disable != "")
		{
			target.item(1).disabled = false;
		}
	}
}
</script>
</body>
</html>