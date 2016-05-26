<?php
include 'includes/connect.php';

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'delendaTECH13cycleDystract')
{
	$gettechquery = mysql_query("SELECT * FROM technology");
	
	while($gettechrow = mysql_fetch_array($gettechquery))
	{
		$techname = $gettechrow['techname'];
		$techtype = $gettechrow['techtype'];
		$rprequired = $gettechrow['rprequired'];
		$rpacquired = $gettechrow['rpacquired'];
		$moneyrequired = $gettechrow['moneyrequired'];
		$moneyacquired = $gettechrow['moneyacquired'];
		$resourcerequired = $gettechrow['resourcerequired'];
		$resourceacquired = $gettechrow['resourceacquired'];
		$allegiance = $gettechrow['faction'];
		$ticksrequired = $gettechrow['ticksrequired'];
		$techid = $gettechrow['techid'];
		$tier = $gettechrow['tier'];
		
		if($rprequired == $rpacquired && $resourcerequired == $resourceacquired && $moneyrequired == $moneyacquired)
		{
			if($ticksrequired == 1)
			{
				if($techtype == 'mining')
				{
					$updatetech = mysql_query("UPDATE technology SET rpacquired = 0, resourceacquired = 0, moneyacquired = 0 WHERE techid = '$techid'");
					$marketcoal = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','coal',10000,500)") or die(mysql_error());
					$marketcopper = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','copper',10000,500)") or die(mysql_error());
					$marketdiamonds = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','diamonds',10000,500)") or die(mysql_error());
					$marketgold = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','gold',10000,500)") or die(mysql_error());
					$marketlivestock = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','livestock',10000,'500')") or die(mysql_error());
					$marketoil = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','oil',10000,500)") or die(mysql_error());
					$marketpalladium = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','palladium',10000,500)") or die(mysql_error());
					$marketsilver = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','silver',10000,500)") or die(mysql_error());
					$markettungsten = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','tungsten',10000,500)") or die(mysql_error());
					$marketillucite = mysql_query("INSERT INTO markets(factionname,colonyname,resource,amount,cost) VALUES ('$allegiance','Central Command','illucite',1000,1000)") or die(mysql_error());
					echo 'Done!';
				}
				elseif($techtype == 'troopeffective')
				{
					$newticks = $tier/3;
					$newticks = floor($newticks);
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$newrprequired = $rprequired * 1.5;
					$newmoneyrequired = $moneyrequired * 2;
					$newresourcerequired = $resourcerequired * 1.5;
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = $newresourcerequired, resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					$updatetroop = mysql_query("UPDATE factionstats SET troopeffective=troopeffective+0.01 WHERE factionname = '$allegiance'");
					echo 'Troop tech done.';
				}
				elseif($techtype == 'troopbuild')
				{
					$newticks = $tier/3;
					$newticks = floor($newticks);
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$newrprequired = $rprequired * 1.5;
					$newmoneyrequired = $moneyrequired * 2;
					$newresourcerequired = $resourcerequired * 1.5;
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = $newresourcerequired, resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					$updatetroop = mysql_query("UPDATE factionstats SET troopbuild=troopbuild+0.01 WHERE factionname = '$allegiance'");
					echo 'Troop build done.';
				}
				elseif($techtype == 'troopregen')
				{
					$newticks = $tier/3;
					$newticks = floor($newticks);
					$newrprequired = $rprequired * 1.5;
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$newmoneyrequired = $moneyrequired * 2;
					$newresourcerequired = $resourcerequired * 1.5;
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = $newresourcerequired, resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					$updatetroop = mysql_query("UPDATE factionstats SET troopregen=troopregen+0.01 WHERE factionname = '$allegiance'");
					echo 'Troop regen done.';
				}
				elseif($techtype == 'weapontech')
				{
					$newrprequired = $rprequired * 1.05;
					$newmoneyrequired = $moneyrequired * 1.05;
					$newticks = $tier;
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = 'none', resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					echo 'Weapons done.';
				}
				elseif($techtype == 'extractorauto')
				{
					$newrprequired = $rprequired * 1.05;
					$newmoneyrequired = $moneyrequired * 1.05;
					$newresource = $resourcerequired * 1.25;
					$newticks = $tier/3;
					$newticks = floor($newticks);
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = $newresource, resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					echo 'Extractors done.';
				}
				elseif($techtype == 'defensiveprep')
				{
					$newrprequired = $rprequired * 1.1;
					$newmoneyrequired = $moneyrequired * 1.5;
					$newresource = $resourcerequired * 1.25;
					$newticks = $tier/3;
					$newticks = floor($newticks);
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = $newresource, resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					echo 'Extractors done.';
				}
				elseif($techtype == 'autowars')
				{
					$newrprequired = $rprequired * 1.5;
					$newmoneyrequired = $moneyrequired * 1.75;
					$newticks = $tier/3;
					$newticks = floor($newticks);
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = 'none', resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					echo 'Autowars done.';
				}
				elseif($techtype == 'scorched')
				{
					$newrprequired = $rprequired * 1.1;
					$newmoneyrequired = $moneyrequired * 1.5;
					$newticks = $tier/3;
					$newticks = floor($newticks);
					$newresource = $resourcerequired*1.25;
					if($newticks < 1)
					{
						$newticks = 1;
					}
					$updatetech = mysql_query("UPDATE technology SET rprequired = $newrprequired, rpacquired = 0, moneyrequired = $newmoneyrequired, resourcerequired = $newresource, resourceacquired = 0, moneyacquired = 0, tier=tier+1, ticksrequired=$newticks WHERE techid='$techid'");
					echo 'Extractors done.';
				}
			}
			else
			{
				$newticks = $ticksrequired - 1;
				$updateticks = mysql_query("UPDATE technology SET ticksrequired = ticksrequired - 1 WHERE techid='$techid'");
				echo 'Problem.';
			}
			echo 'All thing complete';
		}
		else
		{
			echo 'Problem<br>';
		}
	}
	echo 'Password correct';
}

?>