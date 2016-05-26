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

$residential = $_POST['residential'];
$commercial = $_POST['commercial'];
$educational = $_POST['educational'];
$healthcare = $_POST['healthcare'];
$law = $_POST['law'];

$residential = stripslashes($residential);
$commercial = stripslashes($commercial);
$educational = stripslashes($educational);
$healthcare = stripslashes($healthcare);
$law = stripslashes($law);

$residential = mysql_real_escape_string($residential);
$commercial = mysql_real_escape_string($commercial);
$educational = mysql_real_escape_string($educational);
$healthcare = mysql_real_escape_string($healthcare);
$law = mysql_real_escape_string($law);

$resourcecheck = mysql_query("SELECT copper,livestock FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'") or die (mysql_error());
$resourcearray = mysql_fetch_array($resourcecheck);
$copperamount = $resourcearray['copper'];
$livestockamount = $resourcearray['livestock'];

if($residential < 0)
{
	$rescost = $residential * 5000;
}
else
{
	$rescost = $residential*10000;
}
if($livestockamount > 0)
{
	$rescost = $rescost*0.99;
}
if($commercial < 0)
{
	$commercecost = $commercial * 20000;
}
else
{
	$commercecost = $commercial*40000;
}
if($educational < 0)
{
	$educost = $educational * 7500;
}
else
{
	$educost = $educational*15000;
}
if($healthcare < 0)
{
	$healthcost = $healthcare*10000;
}
else
{
	$healthcost = $healthcare*20000;
}
if($law < 0)
{
	$lawcost = $law*10000;
}
else
{
	$lawcost = $law*20000;
}

$totalcost = $rescost+$commercecost+$educost+$healthcost+$lawcost;

if($copperamount > 0)
{
	$totalcost = $totalcost*0.99;
}

$balancequery = mysql_query("SELECT factionbank,land,happiness,commerce,residential FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$balancerow = mysql_fetch_row($balancequery);
$balance = $balancerow[0];
$freeland = $balancerow[1];
$happinesspercent = $balancerow[2];
$commercelimit = $balancerow[4]/2;
$commercelimcheck = $balancerow[3] + $commercial;

$healthland = $healthcare*2;
$lawland = $law*2;
$totalland = $residential + $commercial + $educational + $healthland + $lawland;

$toomanycom = 0;

$popchange = $residential *10000;

if($totalcost > $balance)
{
	header('Location: construction.php?msg=1');
}
elseif($totalland > $freeland)
{
	header('Location: construction.php?msg=2');
}
elseif($commercelimcheck > $commercelimit && $commercial > 0)
{
	header('Location: construction.php?msg=3');
}
else
{
	$colonystats = mysql_query("SELECT factionbank,factionpop,land,residential,commerce,educational,law,hospitals FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'") or die(mysql_error());
	$statsrows=mysql_fetch_row($colonystats);
	$newbalance = $statsrows[0]-$totalcost;
	$newpop = $statsrows[1]+$popchange;
	$newland=$statsrows[2]-$totalland;
	$newres = $statsrows[3]+$residential; $newcom=$statsrows[4]+$commercial; $newedu=$statsrows[5]+$educational;
	$newhealth = $statsrows[7]+$healthcare; $newlaw=$statsrows[6]+$law;
	
	if($newhealth < 0 || $newlaw < 0 || $newres < 0 || $newcom < 0 || $newedu < 0)
	{
		header('Location: construction.php?msg=5');
	}
	else
	{
		$healthcoverage = $newhealth*15000;
		$healthcoverage = $healthcoverage/$newpop;
		$healthcoverage = $healthcoverage*100;
		if($healthcoverage > 100)
		{
			$healthcoverage = 100;
		}
		$educoverage = $newedu*12000;
		$educoverage = $educoverage/$newpop;
		$educoverage = $educoverage*100;
		if($educoverage > 100)
		{
			$educoverage = 100;
		}
		$lawcoverage = $newlaw*15000;
		$lawcoverage = $lawcoverage/$newpop;
		$lawcoverage = $lawcoverage*100;
		if($lawcoverage > 100)
		{
			$lawcoverage = 100;
		}
		$crimerate=100-$lawcoverage;
	
		$str='Construction Complete';
	
		if($newcom > $newres/2)
		{
			$comtemp = $newcom;
			$newcom = floor($newres/2);
			$comtemp = $comtemp - $newcom;
			$costrecover = $comtemp*20000;
			$landrecover = $comtemp*1;
			$newland = $newland + $landrecover;
			$newbalance = $newbalance + $costrecover;
			$toomanycom = 1;
		}
	
		$updatecolony = mysql_query("UPDATE $factiondb SET crime=$crimerate, healthcare=$healthcoverage, education=$educoverage, residential=$newres, commerce=$newcom, educational=$newedu, hospitals=$newhealth, law=$newlaw, factionbank=$newbalance, land=$newland, factionpop=$newpop WHERE factionname='$chosencolony' AND factionuser='$username'") or die(mysql_error());
	
		include'includes/happinesscheck.php';
	
		if($toomanycom == 1)
		{
			header('Location:construction.php?msg=4');
		}
		else
		{
			header('Location:construction.php?msg=0');
		}
	}
}
?>