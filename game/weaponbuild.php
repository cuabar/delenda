<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$count = $_POST['freefactories'];
$count = stripslashes($count);
$count = mysql_real_escape_string($count);
$counter = 0;
$freefactories = $count;
$totalcost = 0;
$factoryconsume = 0;

$getfactioninfo = mysql_query("SELECT factionbank,freefactories FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$factioninfo = mysql_fetch_array($getfactioninfo);
$getdesperation = mysql_query("SELECT desperation FROM factionstates WHERE factionname='$allegiance'");
$desperation = mysql_result($getdesperation,0);
if($desperation > 0)
{
	$desperationeffect = 1-$desperation;
}
else
{
	$desperationeffect = 1;
}

while($counter < $count)
{
	$factnumber = $counter + 1;
	$factories[$counter] = $_POST['factory'.$factnumber];
	$factories[$counter] = stripslashes($factories[$counter]);
	$factories[$counter] = mysql_real_escape_string($factories[$counter]);
	$counter++;
}
foreach($factories as $cost)
{
	if($cost == 'bomb')
		{
			if($allegiance == 'authority')
			{
				$cost = 2000000*$desperationeffect;
				$totalcost = $totalcost + $cost;
			}
			elseif($allegiance == 'solidarity')
			{
				$cost = 1600000*$desperationeffect;
				$totalcost = $totalcost + $cost;
			}
			elseif($allegiance == 'mercantile')
			{
				$cost = 800000*$desperationeffect;
				$totalcost = $totalcost + $cost;
			}
		}
		elseif($cost == 'shield')
		{
			$cost = 750000 * $desperationeffect;
			$totalcost = $totalcost + $cost;
		}
	$factoryconsume++;
}

if($totalcost > $factioninfo['factionbank'])
{
	header('Location: superweapons.php?msg=1');
}
if($factoryconsume > $factioninfo['freefactories'])
{
	header('Location: superweapons.php?msg=2');
}
else
{
	foreach($factories as $production)
	{
		echo $production.'<br>';
		if($production != 'none')
		{
			echo $allegiance.'|'.$chosencolony.'|'.$username.'|'.$production;
			$beginproduction = mysql_query("INSERT INTO warfactories(allegiance,colony,user,building) VALUES('$allegiance','$chosencolony','$username','$production')") or die(mysql_error());
			$freefactories--;
		}
	}
	
	$updatefreefactories = mysql_query("UPDATE $factiondb SET freefactories=$freefactories,factionbank=factionbank-$totalcost WHERE factionuser='$username' AND factionname='$chosencolony'");
	header('Location:superweapons.php?msg=0');
}
echo $totalcost.' | '.$factioninfo['factionbank'];
?>