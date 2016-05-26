<?php
set_time_limit(0);
ignore_user_abort(true);
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $starttime = $mtime; 

include 'includes/connect.php';
//Update Faction Stats (Desperation, Affluence, Defensive Bonuses)
$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'STATSupdate4delFACTion')
{
	//Update factionstats table
	//Authority
	$getauthworlds = mysql_query("SELECT COUNT(*) FROM battlezones WHERE owner = 'authority'");
	$authworlds = mysql_result($getauthworlds, 0);
	$authdesperation = 20 - $authworlds;
	$authdesperation = $authdesperation * 0.01;
	if($authdesperation < -0.1)
	{
		$authdesperation = -0.1;
	}
	$authaffluence = $authworlds - 20;
	$authaffluence = $authaffluence *0.01;
	$authaffluence = $authaffluence + 1;
	if($authaffluence < 0.9)
	{
		$authaffluence = 0.9;
	}
	
	$getauthoutposts = mysql_query("SELECT COUNT(*) FROM authorityfactions");
	$authoutposts = mysql_result($getauthoutposts, 0);
	
	$updateauthstats = mysql_query("UPDATE factionstats SET worlds='$authworlds', colonies='$authoutposts', desperation='$authdesperation', affluence='$authaffluence' WHERE factionname='authority'");
	//Mercantile
	$getmercworlds = mysql_query("SELECT COUNT(*) FROM battlezones WHERE owner = 'mercantile'");
	$mercworlds = mysql_result($getmercworlds, 0);
	$mercdesperation = 20 - $mercworlds;
	$mercdesperation = $mercdesperation*0.01;
	if($mercdesperation < -0.1)
	{
		$mercdesperation = -0.1;
	}
	$mercaffluence = $mercworlds - 20;
	$mercaffluence = $mercaffluence*0.01;
	$mercaffluence = $mercaffluence + 1;
	if($mercaffluence < 0.9)
	{
		$mercaffluence = 0.9;
	}
	$getmercoutposts = mysql_query("SELECT COUNT(*) FROM mercantilefactions");
	$mercoutposts = mysql_result($getmercoutposts, 0);
	$mercdefence = 0.05;
	$getmercdefenceprep = mysql_query("SELECT tier FROM technology WHERE faction = 'mercantile' AND techtype = 'defensiveprep'");
	$mercdefenceprep = mysql_result($getmercdefenceprep, 0);
	$mercdefenceprep = $mercdefenceprep - 1;
	$mercdefenceprep = $mercdefenceprep * 0.01;
	$mercdefence = $mercdefence + $mercdefenceprep;
	
	$mercoffenceweak = -0.02;
	$updatemercstats = mysql_query("UPDATE factionstats SET worlds='$mercworlds', colonies='$mercoutposts', desperation='$mercdesperation', affluence='$mercaffluence' WHERE factionname='mercantile'");
	$updatemercdef = mysql_query("UPDATE battlezones SET merceffective='$mercdefence' WHERE owner='mercantile'");
	$updatemercweak = mysql_query("UPDATE battlezones SET merceffective='$mercoffenceweak' WHERE owner != 'mercantile'");
	//Solidarity
	$getsolworlds = mysql_query("SELECT COUNT(*) FROM battlezones WHERE owner = 'solidarity'");
	$solworlds = mysql_result($getsolworlds, 0);
	$soldesperation = 20 - $solworlds;
	$soldesperation = $soldesperation*0.01;
	if($soldesperation < -0.1)
	{
		$soldesperation = -0.1;
	}
	$solaffluence = $solworlds - 20;
	$solaffluence = $solaffluence*0.01;
	$solaffluence = $solaffluence + 1;
	if($solaffluence < 0.9)
	{
		$solaffluence = 0.9;
	}
	$getsoloutposts = mysql_query("SELECT COUNT(*) FROM solidarityfactions");
	$soloutposts = mysql_result($getsoloutposts, 0);
	$getsoldefenceprep = mysql_query("SELECT tier FROM technology WHERE faction = 'solidarity' AND techtype = 'scorched'");
	$soldefenceprep = mysql_result($getsoldefenceprep, 0);
	$soldefenceprep = $soldefenceprep - 1;
	$soldefenceprep = $soldefenceprep * 0.01;
	$soldefence = $soldefenceprep;
	$updatesolstats = mysql_query("UPDATE factionstats SET worlds='$solworlds', colonies='$soloutposts', desperation='$soldesperation', affluence='$solaffluence' WHERE factionname='solidarity'");
	$updatesoldef = mysql_query("UPDATE battlezones SET soleffective='$soldefence' WHERE owner='solidarity'");
}
else
{
	echo 'Password not provided';
}
$mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   echo "This page was created in ".$totaltime." seconds"; 
?>