<?php

include 'includes/connect.php';
//Check Distances and Deployment Permission
	/*
	Players will only be able to deploy troops to planets they can reach. If a faction does not control any neighbours to planet A, then they cannot deploy to planet A at all.
	*/
	$checkcentauri = mysql_query("SELECT * FROM battlezones WHERE zonesector='1'");
	$checkliberty = mysql_query("SELECT * FROM battlezones WHERE zonesector='2'");
	$checkcentauri = mysql_query("SELECT * FROM battlezones WHERE zonesector='3'");
	$checkhadrian = mysql_query("SELECT * FROM battlezones WHERE zonesector='5'");
	$checkerebus = mysql_query("SELECT * FROM battlezones WHERE zonesector='4'");
	$checkmaginot = mysql_query("SELECT * FROM battlezones WHERE zonesector='6'");
	//Refresh
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
	$erebusmerc = 0; $erebussol = 0; $erebusauth = 0;
	$maginotmerc = 0; $maginotsol = 0; $maginotauth = 0;
	$hadrianmerc = 0; $hadriansol = 0; $hadrianauth = 0;
	//Erebus Check
	while($erebusborder = mysql_fetch_array($checkerebus))
	{
		if($erebusborder['owner'] == 'solidarity')
		{
			$erebussol++;
		}
		elseif($erebusborder['owner'] == 'mercantile')
		{
			$erebusmerc++;
		}
		elseif($erebusborder['owner'] == 'authority')
		{
			$erebusauth++;
		}
	}
	if($erebussol == 10)
	{
		$update_erebus = mysql_query("UPDATE battlezones SET soldeploy=true WHERE zoneid='54' OR zoneid='45' OR zoneid='26' OR zoneid='29' OR zoneid='12' OR zoneid='9'");
	}
	elseif($erebusmerc == 10)
	{	
		$update_erebus = mysql_query("UPDATE battlezones SET mercdeploy=true WHERE zoneid='54' OR zoneid='45' OR zoneid='26' OR zoneid='29' OR zoneid='12' OR zoneid='9'");
	}
	elseif($erebusauth == 10)
	{   
		$update_erebus = mysql_query("UPDATE battlezones SET authdeploy=true WHERE zoneid='54' OR zoneid='45' OR zoneid='26' OR zoneid='29' OR zoneid='12' OR zoneid='9'");
	}
	//Hadrian Check
	while($hadrianborder = mysql_fetch_array($checkhadrian))
	{
		if($hadrianborder['owner'] == 'solidarity')
		{
			$hadriansol++;
		}
		elseif($hadrianborder['owner'] == 'mercantile')
		{
			$hadrianmerc++;
		}
		elseif($hadrianborder['owner'] == 'authority')
		{
			$hadrianauth++;
		}
	}
	if($hadriansol == 10)
	{
		$update_hadrian = mysql_query("UPDATE battlezones SET soldeploy=true WHERE zoneid='10' OR zoneid='11' OR zoneid='20' OR zoneid='21' OR zoneid='40' OR zoneid='56'");
	}
	elseif($hadrianmerc == 10)
	{	
		$update_hadrian = mysql_query("UPDATE battlezones SET mercdeploy=true WHERE zoneid='10' OR zoneid='11' OR zoneid='20' OR zoneid='21' OR zoneid='40' OR zoneid='56'");
	}
	elseif($hadrianauth == 10)
	{   
		$update_hadrian = mysql_query("UPDATE battlezones SET authdeploy=true WHERE zoneid='10' OR zoneid='11' OR zoneid='20' OR zoneid='21' OR zoneid='40' OR zoneid='56'");
	}
	//Maginot Check
	while($maginotborder = mysql_fetch_array($checkmaginot))
	{
		if($maginotborder['owner'] == 'solidarity')
		{
			$maginotsol++;
		}
		elseif($maginotborder['owner'] == 'mercantile')
		{
			$maginotmerc++;
		}
		elseif($maginotborder['owner'] == 'authority')
		{
			$maginotauth++;
		}
	}
	if($maginotsol == 10)
	{
		$update_maginot = mysql_query("UPDATE battlezones SET soldeploy=true WHERE zoneid='50' OR zoneid='33' OR zoneid='18' OR zoneid='19' OR zoneid='24' OR zoneid='28'");
	}
	elseif($maginotmerc == 10)
	{	
		$update_maginot = mysql_query("UPDATE battlezones SET mercdeploy=true WHERE zoneid='50' OR zoneid='33' OR zoneid='18' OR zoneid='19' OR zoneid='24' OR zoneid='28'");
	}
	elseif($maginotauth == 10)
	{   
		$update_maginot = mysql_query("UPDATE battlezones SET authdeploy=true WHERE zoneid='50' OR zoneid='33' OR zoneid='18' OR zoneid='19' OR zoneid='24' OR zoneid='28'");
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
	$updateauthpermanents = mysql_query("UPDATE battlezones SET authdeploy=true WHERE zoneid='1' OR zoneid='53' OR zoneid='52' OR zoneid='31' OR zoneid='33'");
	$updatesolpermanents = mysql_query("UPDATE battlezones SET soldeploy = true WHERE zoneid='3' OR zoneid='36' OR zoneid='39' OR zoneid='41' OR zoneid='45'");
	$updatemercpermanents = mysql_query("UPDATE battlezones SET mercdeploy=true WHERE zoneid='2' OR zoneid='47' OR zoneid='50' OR zoneid='60' OR zoneid='59'");
	
	echo 'All Done...I think.';
	?>