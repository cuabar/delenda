<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$factoriesbuilt = $_POST['construct'];
$factoriesbuilt = stripslashes($factoriesbuilt);
$factoriesbuilt = mysql_real_escape_string($factoriesbuilt);

if($factoriesbuilt < 0)
{
	$totalcost = $factoriesbuilt*125000;
}
else
{
	$totalcost = $factoriesbuilt*250000;
}

$totalland = $factoriesbuilt*50;

$getcolonyinfo = mysql_query("SELECT factionbank,land,warfactories FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'") or die(mysql_error());
$colonyinfo = mysql_fetch_array($getcolonyinfo);
echo $colonyinfo['factionbank'];

if($totalcost > $colonyinfo['factionbank'])
{
	header('Location: superweapons.php?msg=2');
}
elseif($totalland > $colonyinfo['land'])
{
	header('Location: superweapons.php?msg=3');
}
else
{
	$newfactories = $colonyinfo['warfactories'] + $factoriesbuilt;
	if($newfactories < 0)
	{
		header('Location: superweapons.php?msg=4');
	}
	else
	{
		$updatecolony = mysql_query("UPDATE $factiondb SET warfactories=$newfactories,factionbank=factionbank-$totalcost,land=land-$totalland,freefactories=freefactories+$factoriesbuilt WHERE factionuser='$username AND factionname='$chosencolony'");
		header('Location: superweapons.php?msg=0');
	}
}
?>