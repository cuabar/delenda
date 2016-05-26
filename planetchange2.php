<?php
set_time_limit(0);
ignore_user_abort(1);
include 'includes/connect.php';
$mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $starttime = $mtime; 

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'planetCHECKdel413tSs')
{
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
	//Sector Control Check
	/*
	Make sure still all worlds held by a faction.
	*/
	$erebus_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='4' GROUP BY owner DESC");
	$erebus_mercantile = 0; $erebus_solidarity = 0; $erebus_authority = 0; $erebus_controlled = 'none';
	while($erebus_control_row = mysql_fetch_array($erebus_control))
	{
		if($erebus_control_row['COUNT(*)'] == 10)
		{
			$erebus_controlled = $erebus_control_row['owner'];
		}
		else
		{
			$update_control = mysql_query("UPDATE sectorcontrol SET control = 'none' WHERE name='erebus'");
		}
	}
	$hadrian_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='5' GROUP BY owner DESC");
	$hadrian_mercantile = 0; $hadrian_solidarity = 0; $hadrian_authority = 0; $hadrian_controlled = 'none';
	while($hadrian_control_row = mysql_fetch_array($hadrian_control))
	{
		if($hadrian_control_row['COUNT(*)'] == 10)
		{
			$hadrian_controlled = $hadrian_control_row['owner'];
		}
		else
		{
			$update_control = mysql_query("UPDATE sectorcontrol SET control = 'none' WHERE name='hadrian'");
		}
	}
	$maginot_control = mysql_query("SELECT owner, COUNT(*) FROM battlezones WHERE zonesector='6' GROUP BY owner DESC");
	$maginot_mercantile = 0; $maginot_solidarity = 0; $maginot_authority = 0; $maginot_controlled = 'none';
	while($maginot_control_row = mysql_fetch_array($maginot_control))
	{
		if($maginot_control_row['COUNT(*)'] == 10)
		{
			$maginot_controlled = $maginot_control_row['owner'];
		}
		else
		{
			$update_control = mysql_query("UPDATE sectorcontrol SET control = 'none' WHERE name='maginot'");
		}
	}
	/*
	Check sector control database. If != none, set deployment for owners.
	*/
	$erebuscheck = mysql_query("SELECT * FROM sectorcontrol WHERE name = 'erebus'");
	$erebusarray = mysql_fetch_array($erebuscheck);
	$erebuscontrol = $erebusarray['control'];
	if($erebuscontrol != 'none')
	{
		switch($erebuscontrol)
		{
			case 'authority':
			$permission = 'authdeploy';
			break;
			case 'solidarity':
			$permission = 'soldeploy';
			break;
			case 'mercantile':
			$permission = 'mercdeploy';
			break;
		}
		if($erebuscontrol == $erebus_controlled)
		{
			$update_erebus = mysql_query("UPDATE battlezones SET $permission = true WHERE zoneid='51' OR zoneid='50' OR zoneid='26' OR zoneid='29' OR zoneid='12' OR zoneid='9'");
		}
	}
	$hadriancheck = mysql_query("SELECT * FROM sectorcontrol WHERE name = 'hadrian'");
	$hadrianarray = mysql_fetch_array($hadriancheck);
	$hadriancontrol = $hadrianarray['control'];
	if($hadriancontrol != 'none')
	{
		switch($hadriancontrol)
		{
			case 'authority':
			$permission = 'authdeploy';
			break;
			case 'solidarity':
			$permission = 'soldeploy';
			break;
			case 'mercantile':
			$permission = 'mercdeploy';
			break;
		}
		if($hadriancontrol == $hadrian_controlled)
		{
			$update_hadrian = mysql_query("UPDATE battlezones SET $permission = true WHERE zoneid='10' OR zoneid='11' OR zoneid='20' OR zoneid='21' OR zoneid='40' OR zoneid='56'");
		}	
	}
	$maginotcheck = mysql_query("SELECT * FROM sectorcontrol WHERE name = 'maginot'");
	$maginotarray = mysql_fetch_array($maginotcheck);
	$maginotcontrol = $maginotarray['control'];
	if($maginotcontrol != 'none')
	{
		switch($maginotcontrol)
		{
			case 'authority':
			$permission = 'authdeploy';
			break;
			case 'solidarity':
			$permission = 'soldeploy';
			break;
			case 'mercantile':
			$permission = 'mercdeploy';
			break;
		}
		if($maginotcontrol == $maginot_controlled)
		{
			$update_maginot = mysql_query("UPDATE battlezones SET $permission = true WHERE zoneid='50' OR zoneid='33' OR zoneid='18' OR zoneid='19' OR zoneid='24' OR zoneid='28'") or die(mysql_error());
			echo 'Maginot Controlled by: '.$permission;
		}	
	}
	//Permanent Deployment Permissions
	/*
	Factions can always deploy to their homeworld, and two worlds in their neighbouring border sectors. EG: The Solidarity will always be able to deploy to Fortitude and
	two planets in the Erebus and Hadrian Sectors.
	*/
	$updateauthpermanents = mysql_query("UPDATE battlezones SET authdeploy=true WHERE zoneid='1'");
	$updatesolpermanents = mysql_query("UPDATE battlezones SET soldeploy = true WHERE zoneid='3'");
	$updatemercpermanents = mysql_query("UPDATE battlezones SET mercdeploy=true WHERE zoneid='2'");
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