<?php
require '../../includes/connect.php';

require '../includes/playerdata.php';

$deployment = $_POST['weapons'];
$location = $_POST['location'];
$weapontype = $_POST['weapontype'];

$deployment=stripslashes($deployment);
$location = stripslashes($location);
$weapontype = stripslashes($weapontype);

$deployment = mysql_real_escape_string($deployment);
$location = mysql_real_escape_string($location);
$weapontype = mysql_real_escape_string($weapontype);

$shieldskilled = 0;
$troopskilled = 0;

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
if($weapontype == 'bombs')
{
	if($_POST['targetzone'] == 'none')
	{
		echo 'Must designate a target.';
	}
	else
	{
	$target = $_POST['targetzone'];
	$target = stripslashes($target);
	$target = mysql_real_escape_string($target);
	
	$checkbombamount = mysql_query("SELECT bombs FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
	$bombamount = mysql_result($checkbombamount, 0);
	
	if($deployment < 0)
	{
		echo 'Must be a positive number.';
	}
	elseif($bombamount < $deployment)
	{
		echo 'Not enough '.$bombtype.'s.';
	}
	elseif(!is_numeric($deployment))
	{
		echo 'Must be a numerical value.';
	}
	else
	{
	$counter = 0;
	while($counter < $deployment)
	{
		$targettroops = $target.'troops';
		$targetshields = $target.'shields';
		$gettargetinfo = mysql_query("SELECT $targettroops, $targetshields FROM battlezones WHERE zoneid='$location'") or die(mysql_error());
		if($target == 'authority')
		{
			$getregen = mysql_query("SELECT troopregen FROM factionstats WHERE factionname='authority'");
			$regenarray = mysql_fetch_array($getregen);
			$regenvalue = $regenarray['troopregen'];
		}
		$targetinfoarray = mysql_fetch_array($gettargetinfo);
		$damagedone = 0;
	if($targetinfoarray[$targetshields] == 0)
	{
		//Decide damage.
		if($allegiance == 'solidarity')
		{
			$damagedone = rand(125000, 180000);
			if($target == 'authority')
			{
				$troopregen = $damagedone*$regenvalue;
				$damagedone = $damagedone - $regenvalue;
			}
		}
		elseif($allegiance == 'mercantile')
		{
			$damagedone = rand(50000, 80000);
			if($target == 'authority')
			{
				$troopregen = $damagedone*$regenvalue;
				$damagedone = $damagedone - $regenvalue;
			}
		}
		else
		{
			$damagedone = rand(180000, 250000);
		}
		if($damagedone > $targetinfoarray[$targettroops])
		{
			$damagedone = $targetinfoarray[$targettroops];
		}
		
		$getdeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$location' AND allegiance='$target'") or die(mysql_error());
		while($deploymentrow = mysql_fetch_array($getdeployments))
		{
			$killratio = $deploymentrow['deployment']/$targetinfoarray[$targettroops];
			$casualties = $damagedone*$killratio;
			$casualties = ceil($casualties);
			$deploymentid = $deploymentrow['deploymentid'];
			$updatedeploy = mysql_query("UPDATE deployments SET deployment=deployment-$casualties WHERE deploymentid='$deploymentid'");
		}
		$removezerodeploys = mysql_query("DELETE FROM deployments WHERE deployment='0'");
		
		$updatezonetroops = mysql_query("UPDATE battlezones SET $targettroops=$targettroops-$damagedone WHERE zoneid='$location'");
		
		$troopskilled = $troopskilled + $damagedone;
	}
	elseif($targetinfoarray[$targetshields] > 0)
	{
		if($allegiance == 'mercantile')
		{
			$shielddamage = 1;
			$killthird = rand(1, 10);
			if($killthird > 8)
			{
				$shielddamage = 2;
			}
			if($shielddamage > $targetinfoarray[$targetshields])
			{
				$shielddamage = $targetinfoarray[$targetshields];
			}
			$newshields = $targetinfoarray[$targetshields] - $shielddamage;
		}
		if($allegiance == 'solidarity')
		{
			$shielddamage = 2;
			$killthird = rand(1, 10);
			if($killthird > 9)
			{
				$shielddamage = 3;
			}
			if($shielddamage > $targetinfoarray[$targetshields])
			{
				$shielddamage = $targetinfoarray[$targetshields];
			}
			$newshields = $targetinfoarray[$targetshields] - $shielddamage;
		}
		if($allegiance == 'authority')
		{
			$shielddamage = 2;
			$killthird = rand(1, 10);
			if($killthird > 4)
			{
				$shielddamage = 3;
			}
			if($shielddamage > $targetinfoarray[$targetshields])
			{
				$shielddamage = $targetinfoarray[$targetshields];
			}
			$newshields = $targetinfoarray[$targetshields] - $shielddamage;
		}
		$updateshields = mysql_query("UPDATE battlezones SET $targetshields = $newshields WHERE zoneid='$location'") or die(mysql_error());
		$shieldskilled = $shieldskilled + $shielddamage;
	}
	$counter++;
	}
	$updateplayerarsenal = mysql_query("UPDATE $factiondb SET bombs=bombs-$deployment WHERE factionuser='$username' AND factionname='$chosencolony'");
	if($troopskilled == 0)
	{
		echo 'You destroyed '.number_format($shieldskilled).' enemy fortifications.';
	}
	else if($shieldskilled == 0)
	{
		echo 'You killed '.number_format($troopskilled).' enemy soldiers.';
	}
	else
	{
		echo 'You destroyed '.number_format($shieldskilled).' enemy fortifications, and killed '.number_format($troopskilled).' enemy soldiers.';
	}
	}
	}
}
elseif($weapontype == 'shields')
{
	$checkbombamount = mysql_query("SELECT shields FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
	$bombamount = mysql_result($checkbombamount, 0);
	
	if($deployment < 0)
	{
		echo 'Must be a positive number!';
	}
	elseif($bombamount < $deployment)
	{
		echo 'Not enough '.$shieldtype.'s.';
	}
	elseif(!is_numeric($deployment))
	{
		echo 'Must be a numerical value.';
	}
	else
	{
		$factionshields = $allegiance.'shields';
		$updateshields = mysql_query("UPDATE battlezones SET $factionshields=$factionshields+$deployment WHERE zoneid='$location'");
		$updateplayerarsenal = mysql_query("UPDATE $factiondb SET shields=shields-$deployment WHERE factionuser='$username' AND factionname='$chosencolony'");
	}
	echo 'Defences Deployed.';
}
?>