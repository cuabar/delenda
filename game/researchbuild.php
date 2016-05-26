<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$complexesbuilt = $_POST['buildings'];
$complexesbuilt=stripslashes($complexesbuilt);
$complexesbuilt=mysql_real_escape_string($complexesbuilt);

$colonyquery = mysql_query("SELECT factionbank,land,techcomplex FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$colonyrow = mysql_fetch_row($colonyquery);
$balance = $colonyrow[0];
$freeland = $colonyrow[1];
$existcomplexes = $colonyrow[2];

$complexcost = $complexesbuilt*500000;
$resourcecheck = mysql_query("SELECT copper FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'") or die (mysql_error());
$resourcearray = mysql_fetch_array($resourcecheck);
$copperamount = $resourcearray['copper'];
if($copperamount > 0)
{
	$complexcost = $complexcost*0.99;
}
$complexspace = $complexesbuilt*50;

if($complexcost > $balance)
{
	header('Location: research.php?msg=1');
}
elseif($complexspace > $freeland)
{
	header('Location: research.php?msg=2');
}
elseif(!is_numeric($complexesbuilt))
{
	header('Location: research.php?msg=3');
}
else
{
	$newbalance = $balance - $complexcost;
	$newland = $freeland - $complexspace;
	$newcomplex = $existcomplexes + $complexesbuilt;
	
	if($newcomplex < 0)
	{
		header('Location: research.php?msg=4');
	}
	else
	{
		$updatecolony = mysql_query("UPDATE $factiondb SET techcomplex=$newcomplex, factionbank=$newbalance, land=$newland WHERE factionname='$chosencolony' AND factionuser='$username'");
	
		header('Location: research.php?msg=0');
	}
} ?>