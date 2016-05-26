<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

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
if($weapontype == 'bombs')
{
	if(!$_POST['targetzone'.$location])
	{
		header('Location:warroom.php?msg==7');
	}
	else
	{
	$target = $_POST['targetzone'.$location];
	$target = stripslashes($target);
	$target = mysql_real_escape_string($target);
	
	$checkbombamount = mysql_query("SELECT bombs FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
	$bombamount = mysql_result($checkbombamount, 0);
	
	if($deployment < 0)
	{
		header('Location:warroom.php?msg=2');
	}
	elseif($bombamount < $deployment)
	{
		header('Location:warroom.php?msg=6');
	}
	elseif(!is_numeric($deployment))
	{
		header('Location:warroom.php?msg=3');
	}
	else
	{
	$counter = 0;
	while($counter < $deployment)
	{
		$targettroops = $target.'troops';
		$targetshields = $target.'shields';
		$gettargetinfo = mysql_query("SELECT $targettroops, $targetshields FROM battlezones WHERE zoneid='$location'") or die(mysql_error());
		$targetinfoarray = mysql_fetch_array($gettargetinfo);
		$damagedone = 0;
	if($targetinfoarray[$targetshields] == 0)
	{
		//Decide damage.
		if($allegiance == 'solidarity')
		{
			$damagedone = rand(90000, 125000);
		}
		elseif($allegiance == 'mercantile')
		{
			$damagedone = rand(70000, 100000);
		}
		else
		{
			$damagedone = rand(100000, 140000);
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
			$newshields = $targetinfoarray[$targetshields] - 1;
		}
		if($allegiance == 'solidarity')
		{
			$shielddamage = 2;
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
			if($killthird > 7)
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
	header('Location:warroom.php?kill='.$troopskilled.'&shields='.$shieldskilled.'');
	}
	}
}
elseif($weapontype == 'shields')
{
	$checkbombamount = mysql_query("SELECT shields FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
	$bombamount = mysql_result($checkbombamount, 0);
	
	if($deployment < 0)
	{
		header('Location:warroom.php?msg=6');
	}
	elseif($bombamount < $deployment)
	{
		header('Location:warroom.php?msg=7');
	}
	elseif(!is_numeric($deployment))
	{
		header('Location:warroom.php?msg=3');
	}
	else
	{
		$factionshields = $allegiance.'shields';
		$updateshields = mysql_query("UPDATE battlezones SET $factionshields=$factionshields+$deployment WHERE zoneid='$location'");
		$updateplayerarsenal = mysql_query("UPDATE $factiondb SET shields=shields-$deployment WHERE factionuser='$username' AND factionname='$chosencolony'");
	}
	header('Location:warroom.php');
}
?>