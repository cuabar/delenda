<?php
require '../../includes/connect.php';

require '../includes/playerdata.php';

$zoneget = $_GET['zonetarget'];
$zoneget = stripslashes($zoneget);
$zoneget = mysql_real_escape_string($zoneget);

$getmissions = mysql_query("SELECT * FROM zonemissions WHERE battlezone = '$zoneget' AND allegiance='$allegiance'") or die(mysql_error());
$missionoptions = mysql_fetch_array($getmissions);
$missions = array();

switch($allegiance)
{
	case 'authority':
	$permission = 'authdeploy';
	break;
	case 'mercantile':
	$permission = 'mercdeploy';
	break;
	case 'solidarity':
	$permission = 'soldeploy';
	break;
}
$getzoneinfo = mysql_query("SELECT * FROM battlezones WHERE zoneid='$zoneget'");
$zonerows = mysql_fetch_array($getzoneinfo);
$deploypermission = $zonerows[$permission];

if($deploypermission != 0)
{
//Select Missions
echo '<form id="missionform" action="">
	<select id="missionchoice">';
for($count = 0; $count < 6; $count++)
{
	if($count == 0)
	{
		$missionget = 'mission';
	}
	else
	{
		$missionnumber = $count+1;
		$missionget = 'mission'.$missionnumber;
	}
	$missionselect = $missionoptions[$missionget];
	
	$getmissiondetails = mysql_query("SELECT * FROM missions WHERE id='$missionselect'") or die(mysql_error());
	$missionarray = mysql_fetch_array($getmissiondetails);

	$missions[$count][0] = $missionarray['missiontype'];
	$missions[$count][1] = $missionarray['troop'];
	$missions[$count][2] = $missionarray['trooptier'];
	$missions[$count][3] = $missionarray['troopamount'];
	$missions[$count][4] = $missionarray['troop2'];
	$missions[$count][5] = $missionarray['troop2tier'];
	$missions[$count][6] = $missionarray['troop2amount'];
	$missions[$count][7] = $missionarray['troop3'];
	$missions[$count][8] = $missionarray['troop3tier'];
	$missions[$count][9] = $missionarray['troop3amount'];
	$missions[$count][10] = $missionarray['resourcerequire'];
	$missions[$count][11] = $missionarray['resourceamount'];
	
	if($missionarray['missiontype'] == 'killtroops')
	{
		$missiontext = 'Commit your soldiers to a full frontal assault on enemy firebases and positions.';
		$missiontitle = 'Siege Enemy Holdings';
	}
	elseif($missionarray['missiontype'] == 'assassin')
	{
		$missiontext = 'Assassinate a leading figure in your opponent\'s chain of command, stranding their units on the surface.';
		$missiontitle = 'Assassinate General';
	}
	elseif($missionarray['missiontype'] == 'sabotageammo')
	{
		$missiontext = 'Saboteur Units will plant explosives to eliminate enemy supply dumps, severly weakening the effectiveness of their forces.';
		$missiontitle = 'Attack Supply Depots';
	}
	elseif($missionarray['missiontype'] == 'reconbasic')
	{
		$missiontext = 'Recon units will scout out the enemy front lines, reporting back with enemy movements and concentrations to better co-ordinate your own forces.';
		$missiontitle = 'Reconnaissance';
	}
	elseif($missionarray['missiontype'] == 'supportbasic')
	{
		$missiontext = 'Support Units work to establish medical encampments to tend to wounded soldiers, increasing the effectiveness of your forces.';
		$missiontitle = 'Establish Medical Centres';
		if($allegiance == 'authority')
		{
			$missiontext = 'Support Units work to establish repair bays, allowing Drones to be tended to in the midst of engagements, increasing the effectiveness of your forces.';
			$missiontitle = 'Establish Repair Bays';
		}
	}
	elseif($missionarray['missiontype'] == 'jamcomms')
	{
		$missiontext = 'Recon units work in conjunction with sabotage units to install semi-sapient computer viruses into the enemy communication networks. Elite infantry are used to provide a suitable distraction.';
		$missiontitle = 'Jam Communications';
	}
	elseif($missionarray['missiontype'] == 'wormhole')
	{
		$missiontext = 'Support and Recon units are used to investigate natural wormhole formation throughout a sector, with the intended aim of creating a temporary artificial wormhole outside of the
		established and stabilised travel routes.';
		$missiontitle = 'Artifical Wormhole Creation';
	}
	elseif($missionarray['missiontype'] == 'offensiveprep')
	{
		$missiontext = 'Special Operations units sneak behind enemy lines to weaken the enemy position for your next offensive, targeting enemy defensive installations with the aim of demoralising enemy troops';
		$missiontitle = 'Offensive Preparations';
	}
	elseif($missionarray['missiontype'] == 'rollbarrage')
	{
		$missiontext = 'Artillery, Starships and Ground Forces co-ordinate a combined assault on all enemy positions to force the enemy from entrenched positions.';
		$missiontitle = 'Rolling Barrage';
	}
	elseif($missionarray['missiontype'] == 'bribery')
	{
		$missiontext = 'Between flat out bribery, blackmail and manipulation, your forces will try to convince enemy commanders to switch sides in the war.';
		$missiontitle = 'Bribery';
	}
	elseif($missionarray['missiontype'] == 'minefield')
	{
		$missiontext = 'Units will lay minefields in orbit and on the ground in the path of the enemy armies to slow their advance into the system.';
		$missiontitle = 'Minefield Deployment';
	}
	elseif($missionarray['missiontype'] == 'lockdown')
	{
		$missiontext = 'Units will destabilise gateways within the system, preventing enemy forces from deploying to the star system until the wormholes become stable again.';
		$missiontitle = 'System Lockdown';
	}
	elseif($missionarray['missiontype'] == 'utility')
	{
		$missiontext = 'A nanomachine based utility fog will be deployed in the system to aid in repairs, improve combat performance, and to hamper enemy forces.';
		$missiontitle = 'Utility Fog';
	}
	
	echo'<option value="'.$count.'">'.$missiontitle.'</option>';
}
echo '</select></form>';
for($count2 = 0; $count2 < 6; $count2++)
{
	$missiontype = $missions[$count2][0];
	$troop = $missions[$count2][1];
	$trooptier = $missions[$count2][2];
	$troopamount = $missions[$count2][3];
	$troop2 = $missions[$count2][4];
	$troop2tier = $missions[$count2][5];
	$troop2amount = $missions[$count2][6];
	$troop3 = $missions[$count2][7];
	$troop3tier = $missions[$count2][8];
	$troop3amount = $missions[$count2][9];
	$resource = $missions[$count2][10];
	$resourceamount = $missions[$count2][11];
	
	$missioncategory = 'blank';
	
	//Check the Mission Type
	if($missiontype == 'killtroops')
	{
		$missiontext = 'Commit your soldiers to a full frontal assault on enemy firebases and positions.';
		$missiontitle = 'Siege Enemy Holdings';
		$missioncategory = 'normal';
	}
	elseif($missiontype == 'assassin')
	{
		$missiontext = 'Assassinate a leading figure in your opponent\'s chain of command, stranding their units on the surface.';
		$missiontitle = 'Assassinate General';
		$missioncategory = 'normal';
	}
	elseif($missiontype == 'sabotageammo')
	{
		$missiontext = 'Saboteur Units will plant explosives to eliminate enemy supply dumps, severly weakening the effectiveness of their forces.';
		$missiontitle = 'Attack Supply Depots';
		$missioncategory = 'normal';
	}
	elseif($missiontype == 'reconbasic')
	{
		$missiontext = 'Recon units will scout out the enemy front lines, reporting back with enemy movements and concentrations to better co-ordinate your own forces.';
		$missiontitle = 'Frontline Reconnaissance';
		$missioncategory = 'normal';
	}
	elseif($missiontype == 'supportbasic')
	{
		$missiontext = 'Support Units work to establish medical encampments to tend to wounded soldiers, increasing the effectiveness of your forces.';
		$missiontitle = 'Establish Medical Centres';
		if($allegiance == 'authority')
		{
			$missiontext = 'Support Units work to establish repair bays, allowing Drones to be tended to in the midst of engagements, increasing the effectiveness of your forces.';
			$missiontitle = 'Establish Repair Bays';
		}
		$missioncategory = 'normal';
	}
	elseif($missiontype == 'wormhole')
	{
		$missiontext = 'Support and Recon units are used to investigate natural wormhole formation throughout a sector, with the intended aim of creating a temporary artificial wormhole outside of the
		established and stabilised travel routes.';
		$missiontitle = 'Artifical Wormhole Creation';
		$missioncategory = 'unique';
	}
	elseif($missiontype == 'jamcomms')
	{
		$missiontext = 'Recon units work in conjunction with sabotage units to install semi-sapient computer viruses into the enemy communication networks. Elite infantry are used to provide a suitable distraction.';
		$missiontitle = 'Jam Communications';
		$missioncategory = 'unique';
	}
	elseif($missionarray['missiontype'] == 'offensiveprep')
	{
		$missiontext = 'Special Operations units sneak behind enemy lines to weaken the enemy position for your next offensive, targeting enemy defensive installations with the aim of demoralising enemy troops';
		$missiontitle = 'Offensive Preparations';
		$missioncategory = 'unique';
	}
	elseif($missionarray['missiontype'] == 'rollbarrage')
	{
		$missiontext = 'Artillery, Starships and Ground Forces co-ordinate a combined assault on all enemy positions to force the enemy from entrenched positions.';
		$missiontitle = 'Rolling Barrage';
		$missioncategory = 'unique';
	}
	elseif($missionarray['missiontype'] == 'bribery')
	{
		$missiontext = 'Between flat out bribery, blackmail and manipulation, your forces will try to convince enemy commanders to switch sides in the war.';
		$missiontitle = 'Bribery';
		$missioncategory = 'unique';
	}
	elseif($missionarray['missiontype'] == 'minefield')
	{
		$missiontext = 'Units will lay minefields in orbit and on the ground in the path of the enemy armies to slow their advance into the system.';
		$missiontitle = 'Minefield Deployment';
		$missioncategory = 'unique';
	}
	elseif($missionarray['missiontype'] == 'lockdown')
	{
		$missiontext = 'Units will destabilise gateways within the system, preventing enemy forces from deploying to the star system until the wormholes become stable again.';
		$missiontitle = 'System Lockdown';
		$missioncategory = 'unique';
	}
	elseif($missionarray['missiontype'] == 'utility')
	{
		$missiontext = 'A nanomachine based utility fog will be deployed in the system to aid in repairs, improve combat performance, and to hamper enemy forces.';
		$missiontitle = 'Utility Fog';
		$missioncategory = 'unique';
	}
	
	switch($troop)
	{
		case 'elite':
		$troopneed = 'Elite Infantry';
		break;
		case 'assassin':
		$troopneed = 'Assassin';
		break;
		case 'support':
		$troopneed = 'Support Unit';
		break;
		case 'recon':
		$troopneed = 'Reconnaissance Patrol';
		break;
		case 'sabotage':
		$troopneed = 'Saboteur';
		break;
		
	}
	
	//Handle Uniques.
	if($missioncategory == 'unique')
	{	
		if($troop2 != 'none')
		{
			switch($troop2)
			{
				case 'elite':
				$troop2need = 'Elite Infantry';
				break;
				case 'assassin':
				$troop2need = 'Assassin';
				break;
				case 'support':
				$troop2need = 'Support Unit';
				break;
				case 'recon':
				$troop2need = 'Reconnaissance Patrol';
				break;
				case 'sabotage':
				$troop2need = 'Saboteur';
				break;
			}
		}
		else
		{
			$troop2need = ' ';
			$troop2amount = ' ';
			$troop2tier = ' ';
		}
		if($troop3 != 'none')
		{
			switch($troop3)
			{
				case 'elite':
				$troop3need = 'Elite Infantry';
				break;
				case 'assassin':
				$troop3need = 'Assassin';
				break;
				case 'support':
				$troop3need = 'Support Unit';
				break;
				case 'recon':
				$troop3need = 'Reconnaissance Patrol';
				break;
				case 'sabotage':
				$troop3need = 'Saboteur';
				break;
			}
		}
		else
		{
			$troop3need = ' ';
			$troop3amount = ' ';
			$troop3tier = ' ';
		}
		//Check if resources are needed.
		echo '<span class="missioninfo" id="mission'.$count2.'"><h3>'.$missiontitle.'</h3><strong>'.$missiontext.'</strong><br>
		<b>Requires: </b>Tier '.$trooptier.' '.$troopneed;
		$missionreq = 1;
		if($troop2 !='none'){ echo', Tier '.$troop2tier.' '.$troop2need;}
		if($troop3 != 'none'){echo', '.$troop3tier.' '.$troop3need;}
		if($resource != 'none'){echo', '.number_format($resourceamount).' '.$resource;}
		//Get Available Kits
		$getavailablekits = mysql_query("SELECT * FROM recruits WHERE factionuser = '$username' AND colony='$chosencolony' AND type='$troop' AND tier>='$trooptier' AND trainingtime='0'") or die(mysql_error());
		if($troop2 !='none')
		{
			$getavailablekits2 = mysql_query("SELECT * FROM recruits WHERE factionuser = '$username' AND colony='$chosencolony' AND type='$troop2' AND tier>='$troop2tier' AND trainingtime='0'") or die(mysql_error());
			$missionreq = 2;
		}
		if($troop3 != 'none')
		{
			$getavailablekits3 = mysql_query("SELECT * FROM recruits WHERE factionuser = '$username' AND colony='$chosencolony' AND type='$troop3' AND tier>='$troop3tier' AND trainingtime='0'") or die(mysql_error());
			$missionreq = 3;
		}
		
		//Get Active Missions
		$missiondetails = mysql_query("SELECT * FROM missions WHERE allegiance='$allegiance' AND missiontype='$missiontype'");
		$missioninfo = mysql_fetch_array($missiondetails);
		$getactives = mysql_query("SELECT * FROM activemissions WHERE user='$username' AND colony='$chosencolony' AND battlezone='$zoneget' AND mission='$missioninfo[id]' AND allegiance='authority'");
		$activemissions = mysql_num_rows($getactives);
		$nodeploy = 'allow';
		if($activemissions == 0)
		{
			echo '<form id="missionform'.$count2.'" action="">
			<input type="hidden" name="formmissiontype" value="'.$missiontype.'">
			<input type="hidden" name="formmissionid" value="missionid'.$count2.'">
			<input type="hidden" name="formmissionzone" value="'.$zoneget.'">
			<input type="hidden" name="formmissionreq" value="'.$missionreq.'">
			<input type="hidden" name="formmissionres" value="'.$resource.'">
			<input type="hidden" name="formmissionresreq" value="'.$resourceamount.'">';
			if(mysql_num_rows($getavailablekits))
			{
				echo'<select name="formmissiontroops">';
				while($availabletroops = mysql_fetch_array($getavailablekits))
				{
					echo '<option value="'.$availabletroops['id'].'">'.$troopneed.' | Skill: '.$availabletroops['skill'].', Tier: '.$availabletroops['tier'].'</option>';
				}
				echo '</select><br>';
			}
			else
			{
				echo '<br>No available '.$troopneed.' units to dispatch.<br>';
				$nodeploy = 'deny';
			}
			
			if($troop2 != 'none' && mysql_num_rows($getavailablekits2))
			{
				if(mysql_num_rows($getavailablekits2))
				{
					echo '<br><select name="formmissiontroops2">';
					while($availabletroops2 = mysql_fetch_array($getavailablekits2))
					{
						echo '<option value="'.$availabletroops2['id'].'">'.$troop2need.' | Skill: '.$availabletroops2['skill'].', Tier: '.$availabletroops2['tier'].'</option>';
					}
					echo '</select><br>';
				}
				else
				{
					echo '<br>No available '.$troop2need.' units to dispatch.<br>';
					$nodeploy = 'deny';
				}
			}
			if($troop3 != 'none')
			{
				if(mysql_num_rows($getavailablekits3))
				{
					echo '<br><select name="formmissiontroops3">';
					while($availabletroops3 = mysql_fetch_array($getavailablekits3))
					{
						echo '<option value="'.$availabletroops3['id'].'">'.$troop3need.' | Skill: '.$availabletroops3['skill'].', Tier: '.$availabletroops3['tier'].'</option>';
					}
					echo '</select><br>';
				}
				else
				{
					echo '<br>No available '.$troop3need.' units to dispatch.<br>';
					$nodeploy = 'deny';
				}
			}
			if($resource != 'none')
			{
				$getresource = mysql_query("SELECT $resource FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'");
				$resourcetotal = mysql_result($getresource, 0);
				if($resourcetotal > $resourceamount)
				{
					echo '<b>Available '.ucwords($resource).':</b> '.number_format($resourcetotal).'<br>';
				}
				else
				{
					echo '<b>Available '.ucwords($resource).':</b> '.number_format($resourcetotal).'<br>';
					$nodeploy = 'deny';
				}
			}
			echo'<br>';
			if($nodeploy == 'allow')
			{
				echo'<input type="submit" class="specButton2" id="specButton'.$count2.' "value="Dispatch"></form>
				<br><br></span>';
			}
			else
			{
				echo '<b> Must have requirements.</b><br><br></span>';
			}
		}
		else
		{
			$activeinfo = mysql_fetch_array($getactives);
			echo '<br><br><strong>Mission Underway. Time to Completion: </strong>'.$activeinfo['ticksleft'].' Weeks.</span>';
		}
	}
	elseif($missioncategory == 'normal')
	{
		$getavailablekits = mysql_query("SELECT * FROM recruits WHERE factionuser = '$username' AND colony='$chosencolony' AND type='$troop' AND trainingtime='0'") or die(mysql_error());
		echo '<span class="missioninfo" id="mission'.$count2.'"><h3>'.$missiontitle.'</h3><strong>'.$missiontext.'</strong><br>
		<b>Requires: </b>'.$troopamount.' '.$troopneed;
		//Get Active Missions
		$missiondetails = mysql_query("SELECT * FROM missions WHERE allegiance='$allegiance' AND missiontype='$missiontype'");
		$missioninfo = mysql_fetch_array($missiondetails);
		$getactives = mysql_query("SELECT * FROM activemissions WHERE user='$username' AND colony='$chosencolony' AND battlezone='$zoneget' AND mission='$missioninfo[id]' AND allegiance='authority'");
		$activemissions = mysql_num_rows($getactives);
		if($activemissions == 0)
		{
			if(mysql_num_rows($getavailablekits))
			{
				echo '<span id="missionform'.$count2.'"><form id="missionform'.$count2.'" action="">
				<input type="hidden" name="formmissiontype" value="'.$missiontype.'">
				<input type="hidden" name="formmissionid" value="missionid'.$count2.'">
				<input type="hidden" name="formmissionzone" value="'.$zoneget.'">
				<select name="formmissiontroops">';
				while($availabletroops = mysql_fetch_array($getavailablekits))
				{
					echo '<option value="'.$availabletroops['id'].'">'.$troopneed.' | Skill: '.$availabletroops['skill'].', Tier: '.$availabletroops['tier'].'</option>';
				}
		
				echo '</select>
				<input type="submit" class="specButton" id="specButton'.$count2.' "value="Dispatch"></form></span>';
			}
			else
			{
				echo '<br>No available units to dispatch.';
			}
			
		}
		else
		{
			$activeinfo = mysql_fetch_array($getactives);
			echo '<br><br><strong>Mission Underway. Time to Completion: </strong>'.$activeinfo['ticksleft'].' Weeks.';
		}
		echo '<br><br></span>';
	}
}
}
else
{
	//Check for wormhole
	$wormholecheck = mysql_query("SELECT mission6 FROM zonemissions WHERE battlezone='$zoneget'");
	$wormhole = mysql_result($wormholecheck, 0);
	$sectorcheck = $zonerows['zonesector'];
	$allowedworm = mysql_query("SELECT zoneid FROM battlezones WHERE zonesector='$sectorcheck' AND owner='authority'");
	$wormallowed = mysql_num_rows($allowedworm);
	if($wormhole==17 && $wormallowed > 0)
	{
		$getmissiondetails = mysql_query("SELECT * FROM missions WHERE id='17'") or die(mysql_error());
		$missionarray = mysql_fetch_array($getmissiondetails);
		$missiontype = $missionarray['missiontype'];
		$troop = $missionarray['troop'];
		$trooptier = $missionarray['trooptier'];
		$troopamount = $missionarray['troopamount'];
		$troop2 = $missionarray['troop2'];
		$troop2tier = $missionarray['troop2tier'];
		$troop2amount = $missionarray['troop2amount'];
		$troop3 = $missionarray['troop3'];
		$troop3tier = $missionarray['troop3tier'];
		$troop3amount = $missionarray['troop3amount'];
		$resource = $missionarray['resourcerequire'];
		$resourceamount = $missionarray['resourceamount'];
		
		$missiontext = 'Support and Recon units are used to investigate natural wormhole formation throughout a sector, with the intended aim of creating a temporary artificial wormhole outside of the
		established and stabilised travel routes.';
		$missiontitle = 'Artifical Wormhole Creation';
		switch($troop)
	{
		case 'elite':
		$troopneed = 'Elite Infantry';
		break;
		case 'assassin':
		$troopneed = 'Assassin';
		break;
		case 'support':
		$troopneed = 'Support Unit';
		break;
		case 'recon':
		$troopneed = 'Reconnaissance Patrol';
		break;
		case 'sabotage':
		$troopneed = 'Saboteur';
		break;
		
	}
	
	//Handle Uniques.
		if($troop2 != 'none')
		{
			switch($troop2)
			{
				case 'elite':
				$troop2need = 'Elite Infantry';
				break;
				case 'assassin':
				$troop2need = 'Assassin';
				break;
				case 'support':
				$troop2need = 'Support Unit';
				break;
				case 'recon':
				$troop2need = 'Reconnaissance Patrol';
				break;
				case 'sabotage':
				$troop2need = 'Saboteur';
				break;
			}
		}
		else
		{
			$troop2need = ' ';
			$troop2amount = ' ';
			$troop2tier = ' ';
		}
		if($troop3 != 'none')
		{
			switch($troop3)
			{
				case 'elite':
				$troop3need = 'Elite Infantry';
				break;
				case 'assassin':
				$troop3need = 'Assassin';
				break;
				case 'support':
				$troop3need = 'Support Unit';
				break;
				case 'recon':
				$troop3need = 'Reconnaissance Patrol';
				break;
				case 'sabotage':
				$troop3need = 'Saboteur';
				break;
			}
		}
		else
		{
			$troop3need = ' ';
			$troop3amount = ' ';
			$troop3tier = ' ';
		}
		//Check if resources are needed.
		echo '<span class="missioninfo" id="mission1"><h3>'.$missiontitle.'</h3><strong>'.$missiontext.'</strong><br>
		<b>Requires: </b>Tier '.$trooptier.' '.$troopneed;
		$missionreq = 1;
		if($troop2 !='none'){ echo', Tier '.$troop2tier.' '.$troop2need;}
		if($troop3 != 'none'){echo', '.$troop3tier.' '.$troop3need;}
		if($resource != 'none'){echo', '.number_format($resourceamount).' '.$resource;}
		//Get Available Kits
		$getavailablekits = mysql_query("SELECT * FROM recruits WHERE factionuser = '$username' AND colony='$chosencolony' AND type='$troop' AND tier>='$trooptier' AND trainingtime='0'") or die(mysql_error());
		if($troop2 !='none')
		{
			$getavailablekits2 = mysql_query("SELECT * FROM recruits WHERE factionuser = '$username' AND colony='$chosencolony' AND type='$troop2' AND tier>='$troop2tier' AND trainingtime='0'") or die(mysql_error());
			$missionreq = 2;
		}
		if($troop3 != 'none')
		{
			$getavailablekits3 = mysql_query("SELECT * FROM recruits WHERE factionuser = '$username' AND colony='$chosencolony' AND type='$troop3' AND tier>='$troop3tier' AND trainingtime='0'") or die(mysql_error());
			$missionreq = 3;
		}
		
		//Get Active Missions
		$missiondetails = mysql_query("SELECT * FROM missions WHERE allegiance='$allegiance' AND missiontype='$missiontype'");
		$missioninfo = mysql_fetch_array($missiondetails);
		$getactives = mysql_query("SELECT * FROM activemissions WHERE user='$username' AND colony='$chosencolony' AND battlezone='$zoneget' AND mission='$missioninfo[id]' AND allegiance='authority'");
		$activemissions = mysql_num_rows($getactives);
		$nodeploy = 'allow';
		if($activemissions == 0)
		{
			echo '<form id="missionform1" action="">
			<input type="hidden" name="formmissiontype" value="'.$missiontype.'">
			<input type="hidden" name="formmissionid" value="missionid1">
			<input type="hidden" name="formmissionzone" value="'.$zoneget.'">
			<input type="hidden" name="formmissionreq" value="'.$missionreq.'">
			<input type="hidden" name="formmissionres" value="'.$resource.'">
			<input type="hidden" name="formmissionresreq" value="'.$resourceamount.'">';
			if(mysql_num_rows($getavailablekits))
			{
				echo'<select name="formmissiontroops">';
				while($availabletroops = mysql_fetch_array($getavailablekits))
				{
					echo '<option value="'.$availabletroops['id'].'">'.$troopneed.' | Skill: '.$availabletroops['skill'].', Tier: '.$availabletroops['tier'].'</option>';
				}
				echo '</select><br>';
			}
			else
			{
				echo '<br>No available '.$troopneed.' units to dispatch.<br>';
				$nodeploy = 'deny';
			}
			
			if($troop2 != 'none' && mysql_num_rows($getavailablekits2))
			{
				if(mysql_num_rows($getavailablekits2))
				{
					echo '<br><select name="formmissiontroops2">';
					while($availabletroops2 = mysql_fetch_array($getavailablekits2))
					{
						echo '<option value="'.$availabletroops2['id'].'">'.$troop2need.' | Skill: '.$availabletroops2['skill'].', Tier: '.$availabletroops2['tier'].'</option>';
					}
					echo '</select><br>';
				}
				else
				{
					echo '<br>No available '.$troop2need.' units to dispatch.<br>';
					$nodeploy = 'deny';
				}
			}
			if($troop3 != 'none')
			{
				if(mysql_num_rows($getavailablekits3))
				{
					echo '<br><select name="formmissiontroops3">';
					while($availabletroops3 = mysql_fetch_array($getavailablekits3))
					{
						echo '<option value="'.$availabletroops3['id'].'">'.$troop3need.' | Skill: '.$availabletroops3['skill'].', Tier: '.$availabletroops3['tier'].'</option>';
					}
					echo '</select><br>';
				}
				else
				{
					echo '<br>No available '.$troop3need.' units to dispatch.<br>';
					$nodeploy = 'deny';
				}
			}
			if($resource != 'none')
			{
				$getresource = mysql_query("SELECT $resource FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'");
				$resourcetotal = mysql_result($getresource, 0);
				if($resourcetotal > $resourceamount)
				{
					echo '<b>Available '.ucwords($resource).':</b> '.number_format($resourcetotal).'<br>';
				}
				else
				{
					echo '<b>Available '.ucwords($resource).':</b> '.number_format($resourcetotal).'<br>';
					$nodeploy = 'deny';
				}
			}
			echo'<br>';
			if($nodeploy == 'allow')
			{
				echo'<input type="submit" class="specButton2" id="specButton1" value="Dispatch"></form>
				<br><br></span>';
			}
			else
			{
				echo '<b> Must have requirements.</b><br><br></span>';
			}
		}
		else
		{
			$activeinfo = mysql_fetch_array($getactives);
			echo '<br><br><strong>Mission Underway. Time to Completion: </strong>'.$activeinfo['ticksleft'].' Weeks.</span>';
		}
	}
	else
	{
		echo '<center><strong>You cannot deploy Special Forces here.</strong></center>';
	}
}
?>