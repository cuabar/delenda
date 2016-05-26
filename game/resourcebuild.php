<?php
include '../includes/connect.php';

session_start();

if(!isset($_SESSION['username']))
{
	header('Location: ../index.php');
}

$chosencolony = $_SESSION['playercolony'];
$username = $_SESSION['username'];
$allegiance = $_SESSION['allegiance'];
$factiondb = $allegiance.'factions';
$factionmenu=$allegiance.'.php';

$extractorsbuilt = $_POST['buildings'];
$extractorsbuilt = stripslashes($extractorsbuilt);
$extractorsbuilt = mysql_real_escape_string($extractorsbuilt);

$colonyquery = mysql_query("SELECT factionbank,land,extractor FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'") or die (mysql_error());
$colonyrow = mysql_fetch_row($colonyquery);
$balance = $colonyrow[0];
$freeland = $colonyrow[1];
$existextractors = $colonyrow[2];

//Check Resources
$resourcecheck = mysql_query("SELECT copper FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'") or die (mysql_error());
$resourcearray = mysql_fetch_array($resourcecheck);
$copperamount = $resourcearray['copper'];

$extractorcost = $extractorsbuilt*10000;
if($copperamount > 0)
{
	$extractorcost = $extractorcost*0.99;
}
$extractorspace = $extractorsbuilt*50;

if($extractorcost > $balance)
{
	header('Location: storage.php?msg=1');
}
elseif($extractorspace > $freeland)
{
	header('Location: storage.php?msg=2');
}
else
{
	$newbalance = $balance - $extractorcost;
	$newland = $freeland - $extractorspace;
	$newcomplex = $existextractors + $extractorsbuilt;
	
	if($newcomplex < 0)
	{
		header('Location: storage.php?msg=3');
	}
	else
	{
		$updatecolony = mysql_query("UPDATE $factiondb SET extractor=$newcomplex, factionbank=$newbalance, land=$newland WHERE factionname='$chosencolony' AND factionuser='$username'");
		header('Location: storage.php?msg=0');
	}
}