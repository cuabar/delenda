<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$contribution = $_POST['contribute'];
$techid = $_POST['id'];
$contributetype = $_POST['contributetype'];

$contribution = stripslashes($contribution);
$techid = stripslashes($techid);
$contributetype = stripslashes($contributetype);
$contribution = mysql_real_escape_string($contribution);
$techid = mysql_real_escape_string($techid);
$contributetype = mysql_real_escape_string($contributetype);

$getplayerinfo = mysql_query("SELECT researchpoints, factionbank FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$playerdatarow = mysql_fetch_array($getplayerinfo);
$playerrp = $playerdatarow['researchpoints'];
$playermoney = $playerdatarow['factionbank'];
$getplayerresources = mysql_query("SELECT * FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'");
$playerresource = mysql_fetch_array($getplayerresources);
$oil = $playerresource['oil']; $gold = $playerresource['gold']; $silver = $playerresource['silver'];
$tungsten = $playerresource['tungsten']; $palladium = $playerresource['palladium']; $livestock = $playerresource['livestock'];
$illucite = $playerresource['illucite']; $diamonds = $playerresource['diamonds']; $copper = $playerresource['copper'];
$coal = $playerresource['coal'];

$gettechdata = mysql_query("SELECT * FROM technology WHERE techid='$techid'");
$techrows = mysql_fetch_array($gettechdata);

if($contributetype == 'research')
{
	if($contribution > $playerrp)
	{
		echo 'Contribution is greater. Contribution: '.$contribution.'<br> RP: '.$playerrp;
		header('Location: research.php?msg=4');
	}
	elseif($contribution < 0)
	{
		echo ' Contribution is less than 0. Contribution: '.$contribution.'<br> RP: '.$playerrp;
		header('Location: research.php?msg=5');
	}
	elseif(!is_numeric($contribution))
	{
		echo 'Contribution is not numerical. Contribution: '.$contribution.'<br> RP: '.$playerrp;
		header('Location: research.php?msg=6');
	}
	else
	{
		$amountleft = $techrows['rprequired'] - $techrows['rpacquired'];
		if($contribution > $amountleft)
		{
			$contribution = $amountleft;
		}
		$newresearch = $techrows['rpacquired'] + $contribution;
		$alterplayerdata = mysql_query("UPDATE $factiondb SET researchpoints = researchpoints-$contribution WHERE factionuser='$username' AND factionname='$chosencolony'");
		$altertechdata = mysql_query("UPDATE technology SET rpacquired = $newresearch WHERE techid='$techid'");
		header('Location: research.php');
		echo 'Contribution: '.$contribution.'<br> RP: '.$playerrp;
	}
}
elseif($contributetype == 'money')
{
	if($contribution > $playermoney)
	{
		header('Location: research.php?msg=7');
	}
	elseif($contribution < 0)
	{
		header('Location: research.php?msg=5');
	}
	elseif(!is_numeric($contribution))
	{
		header('Location: research.php?msg=6');
	}
	else
	{
		$amountleft = $techrows['moneyrequired'] - $techrows['moneyacquired'];
		if($contribution > $amountleft)
		{
			$contribution = $amountleft;
		}
		$newmoney = $techrows['moneyacquired'] + $contribution;
		$alterplayerdata = mysql_query("UPDATE $factiondb SET factionbank = factionbank-$contribution WHERE factionuser='$username' AND factionname='$chosencolony'");
		$altercashedata = mysql_query("UPDATE technology SET moneyacquired = $newmoney WHERE techid='$techid'");
		header('Location: research.php');
	}
}
elseif($contributetype == 'resources')
{
	$getresourcetype = mysql_query("SELECT resourcetype FROM technology WHERE techid = '$techid'");
	$resourcetype = mysql_result($getresourcetype, 0);
	if($contribution > $playerresource[$resourcetype])
	{
		header('Location: research.php?msg=8');
		echo $contribution. ' '.$resourcetype. ': '.$playerresource[$resourcetype];
	}
	elseif($contribution < 0)
	{
		header('Location: research.php?msg=5');
	}
	elseif(!is_numeric($contribution))
	{
		header('Location: research.php?msg=6');
	}
	else
	{
		$amountleft = $techrows['resourcerequired'] - $techrows['resourceacquired'];
		if($contribution > $amountleft)
		{
			$contribution = $amountleft;
		}
		$newresource = $techrows['resourceacquired'] + $contribution;
		$alterplayerdata = mysql_query("UPDATE resourcebanks SET $resourcetype = $resourcetype-$contribution WHERE factionuser='$username' AND factionname='$chosencolony'");
		$altertechdata = mysql_query("UPDATE technology SET resourceacquired = $newresource WHERE techid='$techid'");
		header('Location: research.php');
		echo 'Boo.';
	}
}
?>