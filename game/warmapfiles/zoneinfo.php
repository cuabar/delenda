<?php
require '../../includes/connect.php';

require '../includes/playerdata.php';

$zoneget = $_GET['zonetarget'];
$zoneget = stripslashes($zoneget);
$zoneget = mysql_real_escape_string($zoneget);

$getzoneinfo = mysql_query("SELECT * FROM battlezones WHERE zoneid='$zoneget'");
$zonerows = mysql_fetch_array($getzoneinfo);

$lockdown = 0;

switch($allegiance)
{
	case 'authority':
	$permission = 'authdeploy';
	break;
	case 'mercantile':
	$permission = 'mercdeploy';
	break;
	case 'solidarity':
	$permission = 'soldeploy';
	break;
}

$deploypermission = $zonerows[$permission];
$reconrows = 0;
$visionallowed = 0;
$recontier = 0;
$getvision = mysql_query("SELECT * FROM missioneffects WHERE missiontype='reconbasic' AND allegiance='$allegiance' AND battlezone='$zoneget'");

if($allegiance != 'authority')
{
	$lockdowncheck = mysql_query("SELECT * FROM missioneffects WHERE missiontype='lockdown' AND battlezone='$zoneget'");
	$lockdown = mysql_num_rows($lockdowncheck);
	if($lockdown > 0)
	{
		$deploypermission=0;
	}
}

if($getvision)
{
	$reconrows = mysql_num_rows($getvision);
	$reconarray = mysql_fetch_array($getvision);
	$recontier = $reconarray['effectamount'];
}

$friendlytroops = $allegiance.'troops';

$checkjam = mysql_query("SELECT * FROM missioneffects WHERE missiontype='jamcomms' AND battlezone='$zoneget'");
if($checkjam)
{
	$jamcomm = mysql_num_rows($checkjam);
}

if($reconrows > 0 || $zonerows[$friendlytroops] > 0 || $zonerows['owner'] == $allegiance)
{
	if($jamcomm == 0)
	{
		$visionallowed = 1;
	}
	else
	{
		$visionallowed = 0;
	}
}
else
{
	$visionallowed = 0;
}

$getplayertroops = mysql_query("SELECT troops,logistics FROM $factiondb WHERE factionuser='$username' AND factionname = '$chosencolony'");
$playertroopinfo = mysql_fetch_array($getplayertroops);
	$playertroops = $playertroopinfo['troops'];
	$playerlogistics = $playertroopinfo['logistics'];
			
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
				
				$authshield = 'false'; $mercshield = 'false'; $solshield = 'false';
				
				if($allegiance == 'authority')
				{
					$authshield = 'true';
					if($visionallowed == 1 && $recontier >= 5)
					{
						$mercshield = 'true';
						$solshield = 'true';
					}
				}
				elseif($allegiance == 'mercantile')
				{
					$mercshield = 'true';
					if($visionallowed == 1 && $recontier >= 5)
					{
						$authshield = 'true';
						$solshield = 'true';
					}
				}
				else
				{
					$solshield = 'true';
					if($visionallowed == 1 && $recontier >= 5)
					{
						$mercshield = 'true';
						$authshield = 'true';
					}
				}
				
				$authtroopvis = 'Unknown'; $merctroopvis = 'Unknown'; $soltroopvis = 'Unknown';
				
				if($allegiance == 'authority')
				{
					$authtroopvis = number_format($authoritytroops);
					if($visionallowed == 1)
					{
						$merctroopvis = number_format($mercantiletroops);
						$soltroopvis = number_format($solidaritytroops);
					}
				}
				elseif($allegiance == 'mercantile')
				{
					$merctroopvis = number_format($mercantiletroops);
					if($visionallowed == 1)
					{
						$authtroopvis = number_format($authoritytroops);
						$soltroopvis = number_format($solidaritytroops);
					}
				}
				elseif($allegiance == 'solidarity')
				{
					$soltroopvis = number_format($solidaritytroops);
					if($visionallowed == 1)
					{
						$merctroopvis = number_format($mercantiletroops);
						$authtroopvis = number_format($authoritytroops);
					}
				}
				
				$ownervis = 'Unknown';
				if($visionallowed == 1)
				{
					$ownervis = $str;
				}
				
				echo '<center><strong>You have '.number_format($playertroops).' troops to deploy.</strong><br>
				You have <strong>'.number_format($playerlogistics).'</strong> logistical points.<br>
				<div id="formmessage">&nbsp</span></center>';
				
				if($reconrows > 0)
				{
					echo '<br><center><strong>Recon Units are reporting on enemy activities.</strong></center>';
				}
				echo '<h2>'.$zonename.'</h2><br>
				<b>Owned By: </b>'.$ownervis.'<br>
				<br><b>Authority Troops: </b>'.$authtroopvis.'<br>';
				if($authshield == 'true')
				{ $getshields = mysql_query("SELECT authorityshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Authority '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				
				echo '<br><b>Mercantile Troops: </b>'.$merctroopvis.'<br>';
				if($mercshield == 'true')
				{ $getshields = mysql_query("SELECT mercantileshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Mercantile '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				
				echo '<br><b>Solidarity Troops: </b>'.$soltroopvis.'<br>';
				if($solshield == 'true')
				{ $getshields = mysql_query("SELECT solidarityshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Solidarity '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				if($visionallowed == 1)
				{
				echo '<span style="color:#980000">'.$authinfluence.'</span> &nbsp|&nbsp <span style="color:#000099">'.$mercinfluence.'</span> &nbsp|&nbsp <span style="color:#009900">'.$solinfluence.'</span><br>';
				}
				if($deploypermission !=0)
				{
				echo '<br><h3>Deploy Troops to '.$zonename.'</h3><div id="deploymentform">
				<form name="troopdeploy" id="troopdeploy" action="">
				<input type="number" name="deploy" id="deploy" value="0" maxlength="10">
				<input type="hidden" name="allegiance" id="allegiance" value="'.$allegiance.'troops">
				<input type="hidden" name="location" id="location" value="'.$zonename.'">
				<input type="hidden" name="zoneid" id="zoneid" value="'.$zoneid.'">
				<input type="submit" name="submit" id="deployButton" value="Deploy Troops"><br>
				<label class="error" for="deploy" id="deploy_error">You do not have that many troops.</label><br><div id="deploymessage"></div><br></form></div>';
				}
				else
				{
					if($lockdown > 0)
					{
						echo '<center><strong>You cannot deploy troops here. Wormhole gates are unstable within the system!</strong></center>';
					}
					else
					{
						echo '<center><strong>You cannot deploy troops here.</strong></center>';
					}
				}
				//Troop Withdrawal
				$checkdeployed=mysql_query("SELECT deployment FROM deployments WHERE factionname='$chosencolony' AND username='$username' AND battlezone='$zoneid'");
				$checkdeployednum = mysql_num_rows($checkdeployed);
				if($checkdeployednum > 0 && $deploypermission !=0)
				{
					$deploymentvalue = mysql_fetch_array($checkdeployed);
					$deploymentnumber = $deploymentvalue['deployment'];
					echo 'You have <strong>'.number_format($deploymentnumber).'</strong> troops deployed here.<br>';
					echo '<form id="troopwithdrawform" action="">
						<input type="number" id="troopwithdraw" name="deploy" value="0" maxlength="10">
						<input type="hidden" id="withdrawallegiance" name="allegiance" value="'.$allegiance.'troops">
						<input type="hidden" id="withdrawlocation" name="location" value="'.$zonename.'">
						<input type="hidden" id="withdrawmax" name="withdrawmax" value="'.$deploymentnumber.'">
						<input type="hidden" name="withdrawzoneid" id="withdrawzoneid" value="'.$zoneid.'">
						<input type="submit" name="submit" id="withdrawButton" value="Withdraw Troops"><br>
						<label class="error" for="deploy" id="withdraw_error">You do not have command of that many troops.</label><br><div id="deploymessage2"></div><br></form>';
				}
				
				//Weapon Deployment
				$checkweapons=mysql_query("SELECT bombs, shields FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
				$weaponscheck = mysql_fetch_array($checkweapons);
				if($weaponscheck['bombs'] > 0 && $deploypermission != 0 || $weaponscheck['shields'] > 0 && $deploypermission != 0)
				{
					echo '<strong>You have: '.$weaponscheck['bombs'].' '.$bombtype.'.<br>You have: '.$weaponscheck['shields'].' '.$shieldtype.'.</strong>';
					echo '<br><form name="weapondeploy" id="weapondeploy" action="">
					<input type="number" name="weapons" id="weapondeploynum" value="0">
					<input type="hidden" name="allegiance" id="weaponallegiance" value="'.$allegiance.'">
					<input type="hidden" name="location" id="weapondeployid" value="'.$zoneid.'">
					<input type="hidden" name="bombamount" id="bombamount" value="'.$weaponscheck['bombs'].'">
					<input type="hidden" name="shieldamount" id="shieldamount" value="'.$weaponscheck['shields'].'">
					<select name="weapontype" id="weapontype">';
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
					echo '<input type="radio" id="targetzone" name="targetzone" value="none" style="display: none;" >';
					if($allegiance != 'solidarity')
					{
						echo '<input type="radio" name="targetzone" '.$soldisable.' value="solidarity">Solidarity &nbsp';
					}
					if($allegiance != 'mercantile')
					{
						echo '<input type="radio" name="targetzone" '.$mercdisable.' value="mercantile">Mercantile Union &nbsp';
					}
					if($allegiance != 'authority')
					{
						echo '<input type="radio" name="targetzone" '.$authdisable.' value="authority">Origin Authority &nbsp';
					}
					echo '  <input type="submit" id="weaponButton" value="Deploy"><br>
					<label class="error" for="weapons" id="weapon_error">You do not have enough superweapons to deploy.</label><br><div id="deploymessage3"></div></form>';
				}
				echo '<br>';
?>