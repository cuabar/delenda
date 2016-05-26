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

//check troops
$checktroopquery=mysql_query("SELECT troops,logistics FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$checktrooprow=mysql_fetch_row($checktroopquery);
$troopamount = $checktrooprow[0];
$logisticsamount = $checktrooprow[1];

//Get Zone ID
$battlezoneidcheckquery = mysql_query("SELECT zoneid, owner FROM battlezones WHERE zonename='$location'") or die(mysql_error());
$zoneidcheckrow = mysql_fetch_row($battlezoneidcheckquery);
$zoneidcheck = $zoneidcheckrow[0];
$zoneowner = $zoneidcheckrow[1];

//check deployed already troops
$checkdeployed=mysql_query("SELECT deployment FROM deployments WHERE factionname='$chosencolony' AND username='$username' AND battlezone='$zoneidcheck'");
$checkdeployednum = mysql_num_rows($checkdeployed);
if($checkdeployednum > 0)
{
	$checkdeployedrow=mysql_fetch_row($checkdeployed);
	$deployedamount = $checkdeployedrow[0];
}

if($troopamount < $deployment)
{
	echo 'You do not have enough troops.';
}
elseif($deployment < 0)
{
	echo 'Must be a positive number';
}
elseif(!is_numeric($deployment))
{
	echo 'Must be a numerical number';
}
else
{
	if($allegiance == 'solidarity')
	{
		$requiredlogistics = $deployment/1250;
		$requiredlogistics = ceil($requiredlogistics);
	}
	else
	{
		$requiredlogistics = $deployment/1000;
		$requiredlogistics = ceil($requiredlogistics);
	}
	
	if($requiredlogistics > $logisticsamount)
	{
		echo 'Not enough logistical points for that deployment.';
	}
	else
	{
		$newtroopamount = $troopamount - $deployment;
		$updatefactiontroops = mysql_query("UPDATE $factiondb SET troops=$newtroopamount, logistics=logistics-$requiredlogistics WHERE factionuser='$username' AND factionname='$chosencolony'") or die(mysql_error());
		$deploymentresult = 'Troops deployed.';
		if($allegiance == 'mercantile' && $zoneowner=='authority' || $allegiance == 'solidarity' && $zoneowner=='authority')
		{
			$getautowars = mysql_query("SELECT tier FROM technology WHERE faction='authority' AND techtype='autowars'");
			$autowarsrow = mysql_fetch_array($getautowars);
			$autowars = $autowarsrow['tier'];
			$autowars = $autowars - 1;
			$autowars = $autowars*0.01;
			
			$autowarkills = $deployment * $autowars;
			$deployment = $deployment - $autowarkills;
			if($autowarkills > 0)
			{
			$deploymentresult = $deploymentresult.'<br>Some troops were attacked by Automated Defences while deploying.';
			}
		}
	
		$oldtroopnumbers = mysql_query("SELECT $allegiancetroops FROM battlezones WHERE zonename='$location'") or die(mysql_error());
		$oldtrooprow = mysql_fetch_row($oldtroopnumbers);
		$oldtroopamount = $oldtrooprow[0];

		$newtroops = $oldtroopamount + $deployment;
		$updatetroops = mysql_query("UPDATE battlezones SET $allegiancetroops=$newtroops WHERE zonename='$location'");

		if($checkdeployednum > 0)
		{
			$battlezoneidquery = mysql_query("SELECT zoneid FROM battlezones WHERE zonename='$location'") or die(mysql_error());
			$zoneidrow = mysql_fetch_row($battlezoneidquery);
			$zoneid = $zoneidrow[0];
			$newdeployedamount = $deployedamount + $deployment;
			if($newdeployedamount > 0)
			{
				$updatedeployment = mysql_query("UPDATE deployments SET deployment=$newdeployedamount WHERE battlezone='$zoneid' AND factionname='$chosencolony' AND username='$username'");
			}
			elseif($newdeployedamount == 0)
			{
				$updatedeployment = mysql_query("DELETE FROM deployments WHERE battlezone='$zoneid' AND factionname='$chosencolony' AND username='$username'");
			}
		}
		else
		{
			$battlezoneidquery = mysql_query("SELECT zoneid FROM battlezones WHERE zonename='$location'") or die(mysql_error());
			$zoneidrow = mysql_fetch_row($battlezoneidquery);
			$zoneid = $zoneidrow[0];
			$insertdeployment = mysql_query("INSERT INTO deployments(username,factionname,allegiance,deployment,battlezone) VALUES ('$username','$chosencolony','$allegiance','$deployment','$zoneid')") or die(mysql_error());
		}
		echo $deploymentresult;
		//header('Location:warroom.php');
	}
}
?>