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
	$getallplanets = mysql_query("SELECT * FROM battlezones WHERE zoneid > 30");
	
	while($planetarray = mysql_fetch_array($getallplanets))
	{
		$zoneid = $planetarray['zoneid'];
		$sector = $planetarray['zonesector'];
		$zone = $planetarray['zonename'];
		$oldowner = $planetarray['owner'];
		$authinfluence = $planetarray['authorityinfluence'];
		$mercinfluence = $planetarray['mercantileinfluence'];
		$solinfluence = $planetarray['solidarityinfluence'];
		$authtroops = $planetarray['authoritytroops'];
		$merctroops = $planetarray['mercantiletroops'];
		$soltroops = $planetarray['solidaritytroops'];
		
		$newowner = $oldowner;
		
		//Check New Ownership
		if($authinfluence > $mercinfluence && $authinfluence > $solinfluence)
		{
			$newowner = 'authority';
		}
		elseif($mercinfluence > $solinfluence && $mercinfluence > $authinfluence)
		{
			$newowner = 'mercantile';
		}
		elseif($solinfluence > $mercinfluence && $solinfluence > $authinfluence)
		{
			$newowner = 'solidarity';
		}
		
		$updateowner = mysql_query("UPDATE battlezones SET owner='$newowner' WHERE zoneid='$zoneid'") or die(mysql_error());
		if($newowner != $oldowner)
		{
			$zonename = $planetarray['zonename'];
			$warlog = mysql_query("INSERT INTO warlogs(allegiance, battlezone, date, time, type) VALUES('$oldowner', '$zonename', CURDATE(), CURTIME(), 'loss')");
			$warlog2 = mysql_query("INSERT INTO warlogs(allegiance, battlezone, date, time, type) VALUES('$newowner', '$zonename', CURDATE(), CURTIME(), 'capture')");
		}
		
		
		//Give Missions
		//Basic Missions
		$updateauthmissions = mysql_query("UPDATE zonemissions SET mission=1, mission2=4, mission3=7, mission4=13, mission5=10 WHERE battlezone='$zoneid' AND allegiance='authority'");
		$updatemercmissions = mysql_query("UPDATE zonemissions SET mission=2, mission2=5, mission3=8, mission4=14, mission5=11 WHERE battlezone='$zoneid' AND allegiance='mercantile'");
		$updatesolmissions = mysql_query("UPDATE zonemissions SET mission=3, mission2=6, mission3=9, mission4=15, mission5=12 WHERE battlezone='$zoneid' AND allegiance='solidarity'");
		
		//Complex Missions
		//Authority Missions
		$getauthmissions = mysql_query("SELECT id FROM missions WHERE allegiance='authority' && id > 15");
		$missionmax = mysql_num_rows($getauthmissions);
		$authmissions = mysql_fetch_row($getauthmissions);
		$randmission = rand(1,$missionmax);
		$randmission = $randmission-1;
		$chosenmission = $authmissions[$randmission];
		$updateauthmissions = mysql_query("UPDATE zonemissions SET mission6='$chosenmission' WHERE battlezone='$zoneid' AND allegiance='authority'");
		//Mercantile Missions
		$getmercmissions = mysql_query("SELECT id FROM missions WHERE allegiance='mercantile' && id > 15");
		$missionmax = mysql_num_rows($getmercmissions);
		$mercmissions = mysql_fetch_row($getmercmissions);
		$randmission = rand(1,$missionmax);
		$randmission = $randmission-1;
		$chosenmission = $mercmissions[$randmission];
		$updatemercmissions = mysql_query("UPDATE zonemissions SET mission6='$chosenmission' WHERE battlezone='$zoneid' AND allegiance='mercantile'");
		//Solidarity Missions
		$getsolmissions = mysql_query("SELECT id FROM missions WHERE allegiance='solidarity' && id > 15");
		$missionmax = mysql_num_rows($getsolmissions);
		$solmissions = mysql_fetch_row($getsolmissions);
		$randmission = rand(1,$missionmax);
		$randmission = $randmission-1;
		$chosenmission = $solmissions[$randmission];
		$updatesolmissions = mysql_query("UPDATE zonemissions SET mission6='$chosenmission' WHERE battlezone='$zoneid' AND allegiance='solidarity'");
	}
	//Check Distances and Deployment Permission
	/*
	Players will only be able to deploy troops to planets they can reach. If a faction does not control any neighbours to planet A, then they cannot deploy to planet A at all.
	*/
	//Refresh
	
	
	/*Planet Permissions used to go below here*/
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