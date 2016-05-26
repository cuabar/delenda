<?php
include '../includes/connect.php';

session_start();

if(!isset($_SESSION['username']))
{
	header('Location: index.php');
}

if($_POST['colonyname'])
{
	$unsafe = array("'", ".", '"');
	$factionname = str_replace($unsafe, "", $factionname);
	$factionname = $_POST['colonyname'];
	$factionname = stripslashes($factionname);
	$factionname = mysql_real_escape_string($factionname);

	
	$factquery = mysql_query("SELECT allegiance FROM users WHERE username = '{$_SESSION['username']}'");
	$factqueryrows = mysql_fetch_row($factquery);
	$playerallegiance = $factqueryrows[0].'factions';
	$playerfaction = $factqueryrows[0];
	
	$getfirstoutpost = mysql_query("SELECT factionname FROM $playerallegiance WHERE factionuser='{$_SESSION['username']}'");
	$firstoutpost = mysql_result($getfirstoutpost, 0);
	
	if($firstputpost == $factionname)
	{
		header('Location: factionchange.php?msg=1');
	}
	else
	{	
		$resources = array("palladium", "tungsten", "gold", "oil", "diamonds", "copper");
		$randresource = $resources[array_rand($resources, 1)];
		$randresource2 = $resources[array_rand($resources, 1)];
	
		$factionstatsquery = mysql_query("SELECT colonies FROM factionstats WHERE factionname = '$playerfaction'");
		$factionqueryrow = mysql_fetch_row($factionstatsquery);
		$colonies = $factionqueryrow[0];
		$newcolonies = $colonies + 1;
	
		$resregister=mysql_query("INSERT INTO resourcebanks(factionuser,factionname,diamonds,oil,copper,silver,gold,palladium,tungsten,coal,livestock,illucite) VALUES ('{$_SESSION['username']}', '$factionname', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0')") or die(mysql_error());
	
		if($factqueryrows[0] != 'mercantile')
		{
			$armyidreg = mysql_query("INSERT INTO $playerallegiance(factionuser, factionname, factionbank, factionpop, happiness, crime, healthcare, education, residential, law, hospitals, educational, barracks, warcentre, techcomplex, commerce, extractor, resource, land, tax) VALUES ('{$_SESSION['username']}', '$factionname', '1000000', '10000', '100', '0', '100', '100', '1', '1', '1', '1', '0', '0', '0', '0', '0', '$randresource', '4996', '5')");
			$playerfactnum = mysql_query("UPDATE users SET numoffactions = 2 WHERE username='{$_SESSION['username']}'");
			$factionstatsupdate = mysql_query("UPDATE factionstats SET colonies=$newcolonies WHERE factionname='$playerfaction'");
		}
		else
		{
			$armyidreg = mysql_query("INSERT INTO $playerallegiance(factionuser, factionname, factionbank, factionpop, happiness, crime, healthcare, education, residential, law, hospitals, educational, barracks, warcentre, techcomplex, commerce, extractor, resource, mercresource, land, tax) VALUES ('{$_SESSION['username']}', '$factionname', '1000000', '10000', '100', '0', '100', '100', '1', '1', '1', '1', '0', '0', '0', '0', '0', '$randresource', '$randresource2', '4996', '5')");
			$playerfactnum = mysql_query("UPDATE users SET numoffactions = 2 WHERE username='{$_SESSION['username']}'");
			$factionstats = mysql_query("UPDATE factionstats SET colonies=$newcolonies WHERE factionname='$playerfaction'");
		}
	
		header('Location: factionchange.php');
	}
}
else
{
	echo 'Your Colony Needs a Name!';
}
?>