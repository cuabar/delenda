<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$deployment = $_POST['deploy'];
$location = $_POST['location'];
$allegiancetroops = $_POST['allegiance'];

$deployment=stripslashes($deployment);
$location = stripslashes($location);
$allegiancetroops = stripslashes($allegiancetroops);

$deployment = mysql_real_escape_string($deployment);
$location = mysql_real_escape_string($location);
$allegiancetroops = mysql_real_escape_string($allegiancetroops);

if($deployment < 0)
{
	echo'You must enter a positive number.';
}
elseif(!is_numeric($deployment))
{
	echo'You must enter a numerical value. ';
}
else
{
	//get zone id
	$getzoneid = mysql_query("SELECT zoneid FROM battlezones WHERE zonename='$location'");
	$zoneid = mysql_result($getzoneid, 0);

	//check deployment
	$checkdeployed=mysql_query("SELECT deployment FROM deployments WHERE factionname='$chosencolony' AND username='$username' AND battlezone='$zoneid'");
	$deployedarray = mysql_fetch_array($checkdeployed);
	$deployedalready = $deployedarray['deployment'];
	
	//check player capacity
	$checkcapacity = mysql_query("SELECT troops, troopmax, logistics FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
	$capacityarray = mysql_fetch_array($checkcapacity);
	$freespace = $capacityarray['troopmax'] - $capacityarray['troops'];
	$playerlogistics = $capacityarray['logistics'];
	
	$troopsallegiance = $allegiance.'troops';
	
	$requiredlogistics = $deployment/750;
	$requiredlogistics = ceil($requiredlogistics);
	
	if($deployment > $deployedalready)
	{
		echo'You do not have that many troops deployed.';
	}
	elseif($deployment > $freespace)
	{
		echo'You do not have the capacity to withdraw that many troops.';
	}
	elseif($requiredlogistics > $playerlogistics)
	{
		echo'You do not have enough logistical assets to withdraw that many troops.';
	}
	elseif($deployment == $deployedalready)
	{
		$updateplayer = mysql_query("UPDATE $factiondb SET troops=troops+$deployment, logistics=logistics-$requiredlogistics WHERE factionuser='$username' AND factionname='$chosencolony'") or die(mysql_error());
		$updatebattlezone = mysql_query("UPDATE battlezones SET $troopsallegiance=$troopsallegiance-$deployment WHERE zoneid='$zoneid'");
		$removedeployment = mysql_query("DELETE FROM deployments WHERE battlezone='$zoneid' AND username='$username' AND factionname='$chosencolony'");
		echo'Troops withdrawn.';
	}
	else
	{
		$updateplayer = mysql_query("UPDATE $factiondb SET troops=troops+$deployment, logistics=logistics-$requiredlogistics WHERE factionuser='$username' AND factionname='$chosencolony'") or die(mysql_error());
		$updatebattlezone = mysql_query("UPDATE battlezones SET $troopsallegiance=$troopsallegiance-$deployment WHERE zoneid='$zoneid'");
		$updatedeployment = mysql_query("UPDATE deployments SET deployment=deployment-$deployment WHERE battlezone='$zoneid' AND username='$username' AND factionname='$chosencolony'");
		echo'Troops withdrawn.';
	}
}
?>