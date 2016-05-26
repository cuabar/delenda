<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$kit = $_POST['kittype'];
$kit = stripslashes($kit);
$kit = mysql_real_escape_string($kit);

if($kit != 'black')
{
	$tier = $_POST['kittier'];
	$tier = stripslashes($tier);
	$tier = mysql_real_escape_string($tier);
}

$training = $_POST['training'];
$training = stripslashes($training);
$training = mysql_real_escape_string($training);

$getcost = mysql_query("SELECT cost FROM operationkits WHERE type='$kit'");
$basecost = mysql_result($getcost, 0);

if($kit != 'black')
{
	$totalcost = $basecost*$tier;
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
$playercentres = $playerarray['warcentre'];

$gettraining = mysql_query("SELECT COUNT(*) FROM recruits WHERE factionuser='$username' AND factionname='$chosencolony' AND trainingtime > 0 AND allegiance='$allegiance'");
$currenttrain = mysql_result($gettraining);

if($allegiance == 'authority')
{
	$trainavail = $playercentres*2;
	$trainavail = $trainavail - $currenttrain;
}
else
{
	$trainavail = $playercentres - $currenttrain;
}

if($totalcost > $playerbank)
{
	header('Location: training.php?msg=1');
}
elseif($trainavail == 0)
{
	header('Location: training.php?msg=2');
}
else
{
	$updateplayerbank = mysql_query("UPDATE $factiondb SET factionbank=factionbank-$totalcost WHERE factionuser='$username' AND factionname='$chosencolony'");
	$traintroops = mysql_query("INSERT INTO recruits(type, tier, allegiance, skill, trainingtime, factionuser, colony) VALUES('$kit','$tier','$allegiance','1','$training','$username','$chosencolony')");
}

header('Location: training.php');
?>