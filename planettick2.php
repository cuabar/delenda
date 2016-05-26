<?php

set_time_limit(0);
ignore_user_abort(true);
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $starttime = $mtime; 

include 'includes/connect.php';

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'planetCHECKdel413tSs')
{
	/* Automatically set all planets to undeployable for all factions */
	$updateallzones = mysql_query("UPDATE battlezones SET authdeploy=false, mercdeploy=false, soldeploy=false");
	//Individual Planet Neighbour Checks
	/*
	Checks to see if a planet is deployable from neighbours. If a faction does not control any neighbours to planet A, they cannot deploy to planet A. This will be
	overridden by the sector control check below if necessary.
	*/
	$checkroutes = mysql_query("SELECT * FROM zoneneighbours");
	while($routearray = mysql_fetch_array($checkroutes))
	{
		$routeorigin = $routearray['battlezone'];
		//Check Owner
		$checkoriginowner = mysql_query("SELECT owner FROM battlezones WHERE zoneid='$routeorigin'");
		$originowner = mysql_fetch_array($checkoriginowner);
		$owner = $originowner['owner'];
		switch($owner)
		{
			case 'solidarity':
			$permission = 'soldeploy';
			break;
			case 'authority':
			$permission = 'authdeploy';
			break;
			case 'mercantile':
			$permission = 'mercdeploy';
			break;
		}
		for($x=1; $x < 5; $x++)
		{
			$neighbour = $routearray['neighbour'.$x];
			if($neighbour != 0)
			{
				$updateneighbour = mysql_query("UPDATE battlezones SET $permission=true WHERE zoneid='$neighbour'");
			}
			else
			{
				$x = 5;
			}
		}
	}
//Access to Sector Checks
	/*
	If an entire BORDER sector is controlled by a single faction, that faction is given access to all neighbouring sectors. For example: if the Solidarity captures every battlezone 
	in the Erebus Sector, then they will be given access to two border planets in the Centauri Sector and one planet in the Maginot and Hadrian Sectors.
	Factions can always deploy to border sectors which border their home sector.
	*/
	$update_control = mysql_query("UPDATE sectorcontrol SET control='none'");
	//Erebus Check
	$erebus_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector = '4' GROUP BY owner DESC");

	while($erebus_control_row = mysql_fetch_array($erebus_control))
	{
		if($erebus_control_row['COUNT(*)'] == 10)
		{
			$erebus_control = $erebus_control_row['owner'];
			switch($erebus_control)
			{
				case 'solidarity':
				$permission = 'soldeploy';
				break;
				case 'mercantile':
				$permission = 'mercdeploy';
				break;
				case 'authority':
				$permission = 'authdeploy';
				break;
			}
			$update_erebus = mysql_query("UPDATE battlezones SET $permission=true WHERE zoneid='51' OR zoneid='50' OR zoneid='26' OR zoneid='29' OR zoneid='12' OR zoneid='9'");
			$update_control = mysql_query("UPDATE sectorcontrol SET control = '$erebus_control' WHERE name='erebus'");
			echo $permission;
		}
	}
	//Hadrian Check
	$hadrian_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='5' GROUP BY owner DESC");
	$hadrian_mercantile = 0; $hadrian_solidarity = 0; $hadrian_authority = 0; $hadrian_controlled = 'none';
	while($hadrian_control_row = mysql_fetch_array($hadrian_control))
	{
		if($hadrian_control_row['COUNT(*)'] == 10)
		{
			$hadrian_control = $hadrian_control_row['owner'];
			switch($hadrian_control)
			{
				case 'solidarity':
				$permission = 'soldeploy';
				break;
				case 'mercantile':
				$permission = 'mercdeploy';
				break;
				case 'authority':
				$permission = 'authdeploy';
				break;
			}
			$update_hadrian = mysql_query("UPDATE battlezones SET $permission=true WHERE zoneid='10' OR zoneid='11' OR zoneid='20' OR zoneid='21' OR zoneid='40' OR zoneid='56'");
			$update_control = mysql_query("UPDATE sectorcontrol SET control = '$hadrian_control' WHERE name='hadrian'");
		}
	}
	//Maginot Check
	$maginot_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='6' GROUP BY owner DESC");
	$maginot_mercantile = 0; $maginot_solidarity = 0; $maginot_authority = 0; $maginot_controlled = 'none';
	while($maginot_control_row = mysql_fetch_array($maginot_control))
	{
		if($maginot_control_row['COUNT(*)'] == 10)
		{
			$maginot_control = $maginot_control_row['owner'];
			switch($maginot_control)
			{
				case 'solidarity':
				$permission = 'soldeploy';
				break;
				case 'mercantile':
				$permission = 'mercdeploy';
				break;
				case 'authority':
				$permission = 'authdeploy';
				break;
			}
			$update_maginot = mysql_query("UPDATE battlezones SET $permission=true WHERE zoneid='50' OR zoneid='33' OR zoneid='18' OR zoneid='19' OR zoneid='24' OR zoneid='28'");
			$update_control = mysql_query("UPDATE sectorcontrol SET control = '$maginot_control' WHERE name='maginot'");
		}
	}
	//Individual Planet Owner Checks
	/*
	Factions must always be able to deploy to planets they have control of.
	*/
	$checkallplanetowner = mysql_query("SELECT * FROM battlezones");
	while($allowners = mysql_fetch_array($checkallplanetowner))
	{
		$planetid = $allowners['zoneid'];
		$planetowner = $allowners['owner'];
		switch($planetowner)
		{
			case 'solidarity':
			$permission2 = 'soldeploy';
			break;
			case 'authority':
			$permission2 = 'authdeploy';
			break;
			case 'mercantile':
			$permission2 = 'mercdeploy';
			break;
		}
		$updateownerdeploy = mysql_query("UPDATE battlezones SET $permission2=true WHERE zoneid='$planetid'");
	}
	//Permanent Deployment Permissions
	/*
	Factions can always deploy to their homeworld, and two worlds in their neighbouring border sectors. EG: The Solidarity will always be able to deploy to Fortitude and
	two planets in the Erebus and Hadrian Sectors.
	*/
	$updateauthpermanents = mysql_query("UPDATE battlezones SET authdeploy=true WHERE zoneid='1'");
	$updatesolpermanents = mysql_query("UPDATE battlezones SET soldeploy = true WHERE zoneid='3'");
	$updatemercpermanents = mysql_query("UPDATE battlezones SET mercdeploy=true WHERE zoneid='2'");
	
	//Remove Old War Logs
	$datecutoff = mysql_query("DELETE FROM warlogs WHERE date < DATE_SUB(CURDATE(), INTERVAL 10 DAY)");
	}
else
{
	echo 'Password not given. Planet ownership change not run.';
}
?>
<?php 
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   echo "This page was created in ".$totaltime." seconds"; 
;?>