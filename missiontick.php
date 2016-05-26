<?php
set_time_limit(0);
ignore_user_abort(true);
include 'includes/connect.php';

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'missionDELtick2401REFRESH')
{
/*  +++ SECTION DETAILS+++
This section handles checking current active mission effects
If a mission only has 1 tick left on its effects, then the mission is removed and its effect erased.
Only applies to missions with ongoing effects. Assassinations, and Troop Killings do not require checking.
*/
$getmissioneffects = mysql_query("SELECT * FROM missioneffects");
while($missioneffects = mysql_fetch_array($getmissioneffects))
{
	$effectid = $missioneffects['id'];
	if($missioneffects['ticksleft'] == 1)
	{
		//Remove the Effect
		$effectbattlezone = $missioneffects['battlezone'];
		$effecttype = $missioneffects['missiontype'];
		$effectallegiance = $missioneffects['allegiance'];
		if($effecttype == 'sabotageammo')
		{
			$effectamount = $missioneffects['effectamount'];
			if($effectallegiance == 'solidarity')
			{
				$updatepenalty = mysql_query("UPDATE battlezones SET autheffective=autheffective+$effectamount, merceffective=merceffective+$effectamount WHERE zoneid='$effectbattlezone'");
			}
			elseif($effectallegiance == 'authority')
			{
				$updatepenalty = mysql_query("UPDATE battlezones SET soleffective=soleffective+$effectamount, merceffective=merceffective+$effectamount WHERE zoneid='$effectbattlezone'");
			}
			elseif($effectallegiance == 'mercantile')
			{
				$updatepenalty = mysql_query("UPDATE battlezones SET autheffective=autheffective+$effectamount, soleffective=soleffective+$effectamount WHERE zoneid='$effectbattlezone'");
			}
		}
		elseif($effecttype == 'supportbasic')
		{
			$effectamount = $missioneffects['effectamount'];
			if($effectallegiance == 'solidarity')
			{
				$updatepenalty = mysql_query("UPDATE battlezones SET soleffective=soleffective-$effectamount WHERE zoneid='$effectbattlezone'");
			}
			elseif($effectallegiance == 'authority')
			{
				$updatepenalty = mysql_query("UPDATE battlezones SET autheffective=autheffective-$effectamount WHERE zoneid='$effectbattlezone'");
			}
			elseif($effectallegiance == 'mercantile')
			{
				$updatepenalty = mysql_query("UPDATE battlezones SET merceffective=merceffective-$effectamount WHERE zoneid='$effectbattlezone'");
			}
		}
		elseif($effecttype=='wormhole')
		{
			$removedeploy = mysql_query("UPDATE battlezones SET authdeploy = '0' WHERE zoneid='$effectbattlezone'");
			//Make sure can deploy normally
			$checkroutes = mysql_query("SELECT * FROM zoneneighbours WHERE zoneid='$effectbattlezone'");
			$routearray = mysql_fetch_array($checkroutes);
			for($x=1; $x < 5; $x++)
			{
				$neighbour = $routearray['neighbour'.$x];
				if($neighbour != 0)
				{
					$getneighowner = mysql_query("SELECT owner FROM battlezones WHERE zoneid='$neighbour'");
					$owner = mysql_result($getneighowner, 0);
					if($owner == 'authority')
					{
						$updateneighbour = mysql_query("UPDATE battlezones SET authdeploy=true WHERE zoneid='$effectbattlezone'");
					}
				}
				else
				{
					$x = 5;
				}
			}
		}
		elseif($effecttype=='offensiveprep')
		{
			$effectamount = $missioneffects['effectamount'];
			$updatepenalty = mysql_query("UPDATE battlezones SET autheffective=autheffective+$effectamount, merceffective=merceffective+$effectamount WHERE zoneid='$effectbattlezone'");
		}
		elseif($effecttype=='utility')
		{
			$removeeffect = mysql_query("UPDATE SET autheffective=autheffective-10, soleffective=soleffective+2, merceffective=merceffective+2 WHERE zoneid='$effectbattlezone'");
		}
		
		$removeeffect = mysql_query("DELETE FROM missioneffects WHERE id='$effectid'");
	}
	else
	{
		$updateeffect = mysql_query("UPDATE missioneffects SET ticksleft=ticksleft-1 WHERE id='$effectid'");
	}
}
}
?>