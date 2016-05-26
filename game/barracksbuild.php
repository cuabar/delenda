<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$barracksbuilt = $_POST['barracks'];
$trainingbuilt = $_POST['training'];
$warcentresbuilt = $_POST['warcentre'];
$commandbuilt = $_POST['command'];

$barracksbuilt = stripslashes($barracksbuilt);
$trainingbuilt = stripslashes($trainingbuilt);
$warcentresbuilt = stripslashes($warcentresbuilt);
$commandbuilt = stripslashes($commandbuilt);

$barracksbuilt = mysql_real_escape_string($barracksbuilt);
$trainingbuilt = mysql_real_escape_string($trainingbuilt);
$warcentresbuilt = mysql_real_escape_string($warcentresbuilt);
$commandbuilt = mysql_real_escape_string($commandbuilt);

$colonyquery = mysql_query("SELECT factionbank,land,barracks,warcentre,trainingground,commandcentre FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$colonyrow = mysql_fetch_row($colonyquery);
$balance = $colonyrow[0];
$freeland = $colonyrow[1];
$existbarracks = $colonyrow[2];
$existwarcentre = $colonyrow[3];
$existtraining = $colonyrow[4];
$existcommand = $colonyrow[5];

if($barracksbuilt < 0)
{
	$barrackscost = $barracksbuilt * 10000;
}
else
{
	$barrackscost = $barracksbuilt*20000;
}
if($warcentresbuilt < 0)
{
	$centrecost = $warcentresbuilt*125000;
}
else
{
	$centrecost = $warcentresbuilt*250000;
}
if($trainingbuilt < 0)
{
	$trainingcost = $trainingbuilt*50000;
}
else
{
	$trainingcost = $trainingbuilt*100000;
}
if($commandbuilt > 0)
{
	$commandcost = $commandbuilt * 250000;
}
else
{
	$commandcost = $commandbuilt*125000;
}
$trainingspace = $trainingbuilt*20;
$barracksspace = $barracksbuilt*20;
$centrespace = $warcentresbuilt*30;
$commandspace = $commandbuilt*25;

$totalcost = $centrecost + $barrackscost + $trainingcost+$commandcost;
//Check Resources
$resourcecheck = mysql_query("SELECT copper FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'") or die (mysql_error());
$resourcearray = mysql_fetch_array($resourcecheck);
$copperamount = $resourcearray['copper'];
if($copperamount > 0)
{
	$totalcost = $totalcost*0.99;
}
$totalspace = $barracksspace + $centrespace + $trainingspace+$commandspace;

if($totalcost > $balance)
{
	header('Location: milbuild.php?msg=1');
}
elseif($totalspace > $freeland)
{
	header('Location: milbuild.php?msg=2');
}
else
{
	$newbalance = $balance - $totalcost;
	$newland = $freeland - $totalspace;
	$newbarracks = $existbarracks + $barracksbuilt;
	$newcentres = $existwarcentre + $warcentresbuilt;
	$newtraining = $existtraining + $trainingbuilt;
	$newcommand = $existcommand + $commandbuilt;
	if($newtraining < 0 || $newcentres < 0 || $newbarracks < 0 || $newcommand < 0)
	{
		header('Location: milbuild.php?msg=3');
	}
	else
	{
		if($allegiance == 'solidarity')
		{
			$newtroopmax1 = $newtraining * 8000;
			$newtroopmax2 = $newbarracks * 15000;
		}
		else
		{
			$newtroopmax1 = $newtraining * 5000;
			$newtroopmax2 = $newbarracks * 10000;
		}
		$newtroopmax = $newtroopmax1 + $newtroopmax2;
	
		$updatecolony = mysql_query("UPDATE $factiondb SET commandcentre=$newcommand, troopmax=$newtroopmax, barracks=$newbarracks, trainingground=$newtraining, factionbank=$newbalance, land=$newland, warcentre = $newcentres WHERE factionname='$chosencolony' AND factionuser='$username'");
	
		header('Location: milbuild.php?msg=0');
	}
}