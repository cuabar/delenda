<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$kit = $_POST['kitid'];
$kit = stripslashes($kit);
$kit = mysql_real_escape_string($kit);

$training = $_POST['training'];
$training = stripslashes($training);
$training = mysql_real_escape_string($training);

$getunitinfo = mysql_query("SELECT * FROM recruits WHERE id='$kit'");
$unitinfo = mysql_fetch_array($getunitinfo);
$kittype = $unitinfo['type'];

$getcost = mysql_query("SELECT cost FROM operationkits WHERE type='$kittype'");
$basecost = mysql_result($getcost, 0);


if($kittype != 'black')
{
	$totalcost = $basecost*$unitinfo['tier'];
	$trainfactor = $training*1.5;
	$totalcost = $totalcost*$trainfactor;
}
else
{
	$tier = 10;
	$trainfactor = $training*1.5;
	$totalcost = $basecost*$trainfactor;
}

$getplayerinfo = mysql_query("SELECT * FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$playerarray = mysql_fetch_array($getplayerinfo);
$playerbank = $playerarray['factionbank'];

if($totalcost > $playerbank)
{
	header('Location: training.php?msg=1');
}
else
{
	$updateplayerbank = mysql_query("UPDATE $factiondb SET factionbank=factionbank-$totalcost WHERE factionuser='$username' AND factionname='$chosencolony'");
	$traintroops = mysql_query("UPDATE recruits SET trainingtime='$training' WHERE id='$kit'");
}

header('Location: training.php?msg=0');
?>