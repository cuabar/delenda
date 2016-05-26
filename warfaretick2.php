<?php
include 'includes/connect.php';

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'delendaEstMAIN01cycle90PASS')
{

//Combat Troop Destruction
$battlezonequery=mysql_query("SELECT * FROM battlezones WHERE zoneid > 30") or die (mysql_error());
$factionstatquery=mysql_query("SELECT troopeffective FROM factionstats") or die (mysql_error());
$factionregenquery = mysql_query("SELECT troopregen FROM factionstats");
$factiondespquery = mysql_query("SELECT desperation FROM factionstats") or die(mysql_error());
$authorityeffective = mysql_result($factionstatquery, 0);
$mercantileeffective = mysql_result($factionstatquery, 1);
$solidarityeffective = mysql_result($factionstatquery, 2);
$regenfactor = mysql_result($factionregenquery, 0);
$authdesperation = mysql_result($factiondespquery, 0);
$mercdesperation = mysql_result($factiondespquery, 1);
$soldesperation = mysql_result($factiondespquery, 2);

while($battlezonearray = mysql_fetch_array($battlezonequery))
{
	//get battlezone info
	$sector = $battlezonearray['zonesector'];
	$zone = $battlezonearray['zonename'];
	$zoneid = $battlezonearray['zoneid'];
	$owner = $battlezonearray['owner'];
	//getdeployedtroops
	$authoritytroops = $battlezonearray['authoritytroops'];
	$mercantiletroops = $battlezonearray['mercantiletroops'];
	$solidaritytroops = $battlezonearray['solidaritytroops'];
	//get troop effectiveness
	$autheffective = $battlezonearray['autheffective'];
	$merceffective = $battlezonearray['merceffective'];
	$soleffective = $battlezonearray['soleffective'];
	//Total troop effectiveness
	$authtotaleffective = $authorityeffective+$autheffective+$authdesperation;
	if($authtotaleffective < 0.1)
	{
		$authtotaleffective = 0.1;
	}
	$soltotaleffective = $solidarityeffective+$soleffective+$soldesperation;
	if($soltotaleffective < 0.1)
	{
		$soltotaleffective = 0.1;
	}
	$merctotaleffective = $mercantileeffective+$merceffective+$mercdesperation;
	if($merctotaleffective < 0.1){$merctotaleffective = 0.1;}
	
	$authdamagefactor=rand(16, 25);
	$authdamagefactor = $authdamagefactor*0.01;
	$soldamagefactor = rand(16, 25);
	$soldamagefactor = $soldamagefactor*0.01;
	$mercdamagefactor = rand(16, 25);
	$mercdamagefactor = $mercdamagefactor*0.01;
	
	$authdamageinflict = $authoritytroops*$authdamagefactor;
	$authdamageinflict = round($authdamageinflict);
	$authdamageinflict = $authdamageinflict*$authtotaleffective;
	$authdamageinflict = round($authdamageinflict);
	$soldamageinflict = $solidaritytroops*$soldamagefactor;
	$soldamageinflict = round($soldamageinflict);
	$soldamageinflict = $soldamageinflict*$soltotaleffective;
	$soldamageinflict = round($soldamageinflict);
	$mercdamageinflict = $mercantiletroops*$mercdamagefactor;
	$mercdamageinflict = round($mercdamageinflict);
	$mercdamageinflict = $mercdamageinflict*$merctotaleffective;
	$mercdamageinflict = round($mercdamageinflict);
	
	$authregen = $mercdamageinflict + $soldamageinflict;
	$authregen = $authregen * $regenfactor;
	
	$newmercantiletroops = $mercantiletroops - $soldamageinflict;
	$newmercantiletroops = $newmercantiletroops - $authdamageinflict;

	$newauthoritytroops = $authoritytroops - $soldamageinflict;
	$newauthoritytroops = $newauthoritytroops - $mercdamageinflict;
	$newauthtroops = $newauthoritytroops;
	$newauthoritytroops = $newauthoritytroops + $authregen;
	
	$newsolidaritytroops = $solidaritytroops - $authdamageinflict;
	$newsolidaritytroops = $newsolidaritytroops - $mercdamageinflict;
	
	if($newmercantiletroops < 0)
	{
		$newmercantiletroops = 0;
	}
	if($newauthoritytroops < 0)
	{
		$newauthoritytroops = 0;
	}
	if($newsolidaritytroops < 0)
	{
		$newsolidaritytroops = 0;
	}
	
	$updatetroops = mysql_query("UPDATE battlezones SET authoritytroops=$newauthoritytroops, mercantiletroops=$newmercantiletroops, solidaritytroops=$newsolidaritytroops WHERE zoneid='$zoneid'") or die(mysql_error());

	//War Logs
	if($mercantiletroops != $newmercantiletroops)
	{
		$merctroopslost = $mercantiletroops - $newmercantiletroops;
		mysql_query("INSERT INTO warlogs(allegiance, battlezone, type, date, time, value) VALUES('mercantile', '$zone', 'casualties', CURDATE(), CURTIME(), '$merctroopslost')");
	}
	if($solidaritytroops != $newsolidaritytroops)
	{
		$soltroopslost = $solidaritytroops - $newsolidaritytroops;
		mysql_query("INSERT INTO warlogs(allegiance, battlezone, type, date, time, value) VALUES('solidarity', '$zone', 'casualties', CURDATE(), CURTIME(), '$soltroopslost')");
	}
	if($newauthoritytroops != $authoritytroops)
	{
		if($authregen > 0)
		{
			$authtroopslost = $authoritytroops - $newauthtroops;
			mysql_query("INSERT INTO warlogs(allegiance, battlezone, type, date, time, value, value2) VALUES('authority', '$zone', 'casualties', CURDATE(), CURTIME(), '$authtroopslost', '$authregen')");
		}
		else
		{
			$authtroopslost = $authoritytroops - $newauthoritytroops;
			mysql_query("INSERT INTO warlogs(allegiance, battlezone, type, date, time, value) VALUES('authority', '$zone', 'casualties', CURDATE(), CURTIME(), '$authtroopslost')");
		}
	}
	
	//Remove from individual deployments
	$getauthoritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$zoneid' AND allegiance='authority'") or die(mysql_error());
	$authoritydeployments = mysql_num_rows($getauthoritydeployments);
	$getsolidaritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$zoneid' AND allegiance='solidarity'") or die(mysql_error());
	$solidaritydeployments = mysql_num_rows($getsolidaritydeployments);
	$getmercantiledeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$zoneid' AND allegiance='mercantile'") or die(mysql_error());
	$mercantiledeployments = mysql_num_rows($getmercantiledeployments);
	
	//Remove from Authority Deployments
	if($authoritydeployments > 0)
	{
		$authdamagedone = $soldamageinflict+$mercdamageinflict;
		
		while($authoritydeploymentrow = mysql_fetch_array($getauthoritydeployments))
		{
			$authratio = $authoritydeploymentrow['deployment']/$authoritytroops;
			$authcasualties = $authdamagedone*$authratio;
			$authcasualties = ceil($authcasualties);
			$deploymentid = $authoritydeploymentrow['deploymentid'];
			$updateauthdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$authcasualties WHERE deploymentid='$deploymentid'");
		}
	}
	//Solidarity Deployments
	if($solidaritydeployments > 0)
	{
		$soldamagedone = $authdamageinflict+$mercdamageinflict;
		
		while($solidaritydeploymentrow = mysql_fetch_array($getsolidaritydeployments))
		{
			$solratio = $solidaritydeploymentrow['deployment']/$solidaritytroops;
			$solcasualties = $soldamagedone*$solratio;
			$solcasualties = ceil($solcasualties);
			$deploymentid = $solidaritydeploymentrow['deploymentid'];
			$updatesoldeploy = mysql_query("UPDATE deployments SET deployment=deployment-$solcasualties WHERE deploymentid='$deploymentid'");
		}
	}
	//Mercantile Deployments
	if($mercantiledeployments > 0)
	{
		$mercdamagedone = $authdamageinflict+$soldamageinflict;
		
		while($mercantiledeploymentrow = mysql_fetch_array($getmercantiledeployments))
		{
			$mercratio = $mercantiledeploymentrow['deployment']/$mercantiletroops;
			$merccasualties = $mercdamagedone*$mercratio;
			$merccasualties = ceil($merccasualties);
			$deploymentid = $mercantiledeploymentrow['deploymentid'];
			$updatemercdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$merccasualties WHERE deploymentid='$deploymentid'");
		}
	}
}
$removeallzeroes = mysql_query("DELETE FROM deployments WHERE deployment<='0'");

//Influence Calculations
$getallbattlezones = mysql_query("SELECT * FROM battlezones") or die (mysql_error());
$merc_authgain = 0; $sol_authgain = 0;
$merc_solgain = 0; $auth_solgaine = 0;
$sol_mercgain = 0; $auth_mercgain = 0;
while($planetarray = mysql_fetch_array($getallbattlezones))
{
	$zoneid = $planetarray['zoneid'];
	$sector = $planetarray['zonesector'];
	$zone = $planetarray['zonename'];
	$owner = $planetarray['owner'];
	$authinfluence = $planetarray['authorityinfluence'];
	$mercinfluence = $planetarray['mercantileinfluence'];
	$solinfluence = $planetarray['solidarityinfluence'];
	$authtroops = $planetarray['authoritytroops'];
	$merctroops = $planetarray['mercantiletroops'];
	$soltroops = $planetarray['solidaritytroops'];
	
	$totaltroops = $soltroops+$merctroops+$authtroops;
	
	$authratio = $authtroops*$authorityeffective;
	$authratio = $authratio/$totaltroops;
	$authratio = round($authratio, 2);
	if($authratio == 0)
	{
		$authratio = 0.01;
	}
	$mercratio = $merctroops*$mercantileeffective;
	$mercratio = $mercratio/$totaltroops;
	$mercratio = round($mercratio, 2);
	if($mercratio == 0)
	{
		$mercratio = 0.01;
	}
	$solratio = $soltroops*$solidarityeffective;
	$solratio = $solratio/$totaltroops;
	$solratio = round($solratio, 2);
	if($solratio == 0)
	{
		$solratio = 0.01;
	}
	
	//Authority Change
	if($authratio > $solratio)
	{
		$auth_soldiff = $authratio/$solratio;
		$auth_soldiff = round($auth_soldiff, 2);
		
		$auth_solgain = 2*$auth_soldiff;
		$auth_solgain = ceil($auth_solgain);
		if($auth_solgain > $solinfluence)
		{
			$auth_solgain = $solinfluence;
		}
		if($auth_solgain > 10)
		{
			$auth_solgain = 10;
		}
		$solinfluence = $solinfluence - $auth_solgain;
		$authinfluence = $authinfluence + $auth_solgain;
	}
	if($authratio > $mercratio)
	{
		$auth_mercdiff = $authratio/$mercratio;
		$auth_mercdiff = round($auth_mercdiff, 2);
		
		$auth_mercgain = 2*$auth_mercdiff;
		$auth_mercgain = ceil($auth_mercgain);
		//check for minefield
		$minefield = mysql_query("SELECT * FROM missioneffects WHERE missiontype='minefield' AND battlezone='$zoneid'");
		$minefieldcheck = mysql_num_rows($minefield);
		if($auth_mercgain > $mercinfluence)
		{
			$auth_mercgain = $mercinfluence;
		}
		if($auth_mercgain > 10)
		{
			$auth_mercgain = 10;
		}
		if($minefieldcheck > 0)
		{
			$auth_mercgain = floor($auth_mercgain/2);
		}
		$mercinfluence = $mercinfluence - $auth_mercgain;
		$authinfluence = $authinfluence + $auth_mercgain;
	}
	//Mercantile Change
	if($mercratio > $solratio)
	{
		$merc_soldiff = $mercratio/$solratio;
		$merc_soldiff = round($merc_soldiff, 2);
		
		$merc_solgain = 2*$merc_soldiff;
		$merc_solgain = ceil($merc_solgain);
		if($merc_solgain > $solinfluence)
		{
			$merc_solgain = $solinfluence;
		}
		if($merc_solgain > 10)
		{
			$merc_solgain = 10;
		}
		$solinfluence = $solinfluence - $merc_solgain;
		$mercinfluence = $mercinfluence + $merc_solgain;
	}
	if($mercratio > $authratio)
	{
		$merc_authdiff = $mercratio/$authratio;
		$merc_authdiff = round($merc_authdiff, 2);
		
		$merc_authgain = 2*$merc_authdiff;
		$merc_authgain = ceil($merc_authgain);
		if($merc_authgain > $authinfluence)
		{
			$merc_authgain = $authinfluence;
		}
		if($merc_authgain > 10)
		{
			$merc_authgain = 10;
		}
		$authinfluence = $authinfluence - $merc_authgain;
		$mercinfluence = $mercinfluence + $merc_authgain;
	}
	//Solidarity Change
	if($solratio > $authratio)
	{
		$sol_authdiff = $solratio/$authratio;
		$sol_authdiff = round($sol_authdiff, 2);
		
		$sol_authgain = 2*$sol_authdiff;
		$sol_authgain = ceil($sol_authgain);
		if($sol_authgain > $authinfluence)
		{
			$sol_authgain = $authinfluence;
		}
		if($sol_authgain > 10)
		{
			$sol_authgain = 10;
		}
		
		$authinfluence = $authinfluence - $sol_authgain;
		$solinfluence = $solinfluence + $sol_authgain;
	}
	if($solratio > $mercratio)
	{
		$sol_mercdiff = $solratio/$mercratio;
		$sol_mercdiff = round($sol_mercdiff, 2);
		
		$sol_mercgain = 2*$sol_mercdiff;
		$sol_mercgain = ceil($sol_mercgain);
		if($sol_mercgain > $mercinfluence)
		{
			$sol_mercgain = $mercinfluence;
		}
		if($sol_mercgain > 10)
		{
			$sol_mercgain = 10;
		}
		//check for minefield
		$minefield = mysql_query("SELECT * FROM missioneffects WHERE missiontype='minefield' AND battlezone='$zoneid'");
		$minefieldcheck = mysql_num_rows($minefield);
		if($minefieldcheck > 0)
		{
			$sol_mercgain = floor($sol_mercgain/2);
		}
		$mercinfluence = $mercinfluence - $sol_mercgain;
		$solinfluence = $solinfluence + $sol_mercgain;
	}
	
	$totalinfluence = $solinfluence + $authinfluence + $mercinfluence;
	if($totalinfluence > 100)
	{
		$authcheck = 0;
		$solcheck = 0;
		$merccheck = 0;
		$totalcheck = 0;
		while($totalinfluence > 100)
		{
			if($authratio < $solratio && $authratio < $mercratio && $totalcheck < 3)
			{
				if($authcheck !=1)
				{
					$authinfluence-- ;
					$totalinfluence--;
					$authcheck = 1;
					$totalcheck++;
				}
				elseif($solratio < $mercratio)
				{
					if($solcheck !=1)
					{
						$solinfluence--;
						$totalinfluence--;
						$solcheck = 1;
						$totalcheck++;
					}
					else
					{
						$mercinfluence--;
						$totalinfluence--;
						$totalcheck++;
					}
				}
				elseif($mercratio < $solratio)
				{
					if($merccheck !=1)
					{
						$mercinfluence--;
						$totalinfluence--;
						$merccheck = 1;
						$totalcheck++;
					}
					else
					{
						$solinfluence--;
						$totalinfluence--;
						$totalcheck++;
					}
				}
			}
			if($solratio < $mercratio && $solratio < $authratio && $totalcheck < 3)
			{
				if($solcheck !=1)
				{
					$solinfluence-- ;
					$totalinfluence--;
					$solcheck = 1;
					$totalcheck++;
				}
				elseif($authratio < $mercratio)
				{
					if($authcheck !=1)
					{
						$authinfluence--;
						$totalinfluence--;
						$authcheck = 1;
						$totalcheck++;
					}
					else
					{
						$mercinfluence--;
						$totalinfluence--;
						$totalcheck++;
					}
				}
				elseif($mercratio < $authratio)
				{
					if($merccheck !=1)
					{
						$mercinfluence--;
						$totalinfluence--;
						$merccheck = 1;
						$totalcheck++;
					}
					else
					{
						$authinfluence--;
						$totalinfluence--;
						$totalcheck++;
					}
				}
			}
			if($mercratio < $solratio && $mercratio < $authratio && $totalcheck < 3)
			{
				if($merccheck !=1)
				{
					$mercinfluence--;
					$totalinfluence--;
					$merccheck = 1;
					$totalcheck++;
				}
				elseif($solratio < $authratio)
				{
					if($solcheck !=1)
					{
						$solinfluence--;
						$totalinfluence--;
						$solcheck = 1;
						$totalcheck++;
					}
					else
					{
						$authinfluence--;
						$totalinfluence--;
						$totalcheck++;
					}
				}
				elseif($authratio < $solratio)
				{
					if($authcheck !=1)
					{
						$authinfluence--;
						$totalinfluence--;
						$authcheck = 1;
						$totalcheck++;
					}
					else
					{
						$solinfluence--;
						$totalinfluence--;
						$totalcheck++;
					}
				}
			}
			if($totalcheck = 3)
			{
				$totalcheck = 0;
				$authcheck = 0;
				$solcheck = 0;
				$merccheck = 0;
			}
		}
	}	
	$updateinfluence = mysql_query("UPDATE battlezones SET authorityinfluence=$authinfluence, mercantileinfluence=$mercinfluence, solidarityinfluence=$solinfluence WHERE zoneid='$zoneid'");
	
	//Check for minimum Garrisson
	if($owner == 'solidarity' && $soltroops < 10000 && $solinfluence > 0)
	{
		$updateinfluence = mysql_query("UPDATE battlezones SET solidarityinfluence=solidarityinfluence-1 WHERE zoneid='$zoneid'");
	}
	elseif($owner == 'mercantile' && $merctroops < 10000 && $mercinfluence > 0)
	{
		$updateinfluence = mysql_query("UPDATE battlezones SET mercantileinfluence=mercantileinfluence-1 WHERE zoneid='$zoneid'");
	}
	elseif($owner == 'authority' && $authtroops < 10000 && $authinfluence > 0)
	{
		$updateinfluence = mysql_query("UPDATE battlezones SET authorityinfluence=authorityinfluence-1 WHERE zoneid='$zoneid'");
	}
	
	//Make Sure 100% Influence in total.
	$getallinfluence = mysql_query("SELECT authorityinfluence, solidarityinfluence, mercantileinfluence, owner FROM battlezones WHERE zoneid='$zoneid'") or die(mysql_error());
	$allinfluence = mysql_fetch_array($getallinfluence);
	$mercinfcheck = $allinfluence['mercantileinfluence'];
	$solinfcheck = $allinfluence['solidarityinfluence'];
	$authinfcheck = $allinfluence['authorityinfluence'];
	
	$totalinfluence = $mercinfcheck + $solinfcheck + $authinfcheck;
	if($totalinfluence < 100)
	{
		while($totalinfluence < 100)
		{
			$ownerinfluence = $allinfluence['owner'].'influence';
			$updateownerinfluence = mysql_query("UPDATE battlezones SET $ownerinfluence=$ownerinfluence+1 WHERE zoneid='$zoneid'");
			$totalinfluence++;
		}
	}
}

//Superweapons
$getsuperweapons = mysql_query("SELECT * FROM warfactories");

while($superweaponarray = mysql_fetch_array($getsuperweapons))
{
	$id = $superweaponarray['id'];
	$building = $superweaponarray['building'];
	$weaponallegiance = $superweaponarray['allegiance'];
	$weaponcolony = $superweaponarray['colony'];
	$weaponuser = $superweaponarray['user'];
	$ticksleft = $superweaponarray['ticksleft'];
	$building = $building.'s';
	
	$weaponfactiondb = $weaponallegiance.'factions';
	
	if($ticksleft == 1)
	{
		$getwarfactories = mysql_query("SELECT warfactories, freefactories FROM $weaponfactiondb WHERE factionname='$weaponcolony' AND factionuser='$weaponuser'");
		$warfactoryarray = mysql_fetch_array($getwarfactories);
		$factoryamount = $warfactoryarray['warfactories'];
		$freefactories = $warfactoryarray['freefactories'];
		$newfree = $freefactories + 1;
		if($newfree > $factoryamount)
		{
			$updatecolony = mysql_query("UPDATE $weaponfactiondb SET $building=$building+1 WHERE factionname='$weaponcolony' AND factionuser='$weaponuser'");
		}
		else
		{
			$updatecolony = mysql_query("UPDATE $weaponfactiondb SET $building=$building+1, freefactories=freefactories+1 WHERE factionname='$weaponcolony' AND factionuser='$weaponuser'");
		}
		$removeconstruction = mysql_query("DELETE FROM warfactories WHERE id='$id'");
	}
	else
	{
		$getwarfactories = mysql_query("SELECT warfactories, freefactories,factionbank,factionpop FROM $weaponfactiondb WHERE factionname='$weaponcolony' AND factionuser='$weaponuser'");
		$warfactoryarray = mysql_fetch_array($getwarfactories);
		$factoryamount = $warfactoryarray['warfactories'];
		$freefactories = $warfactoryarray['freefactories'];
		$warfactorypop = $factoryamount - $freefactories;
		$warfactorypop = $warfactorypop * 2500;
		if($warfactoryarray['factionpop'] >= $warfactorypop)
		{
			if($warfactoryarray['factionbank'] > 0)
			{
				$updateconstruction = mysql_query("UPDATE warfactories SET ticksleft=$ticksleft-1 WHERE id='$id'");
			}
		}		
	}
}

echo 'Tick has run. Or should have anyway.<br>
<b>It worked.</b>';
}