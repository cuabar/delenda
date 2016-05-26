				<?php 
				require '../../includes/connect.php';

require '../includes/playerdata.php';

$zoneget = $_GET['zonetarget'];
$zoneget = stripslashes($zoneget);
$zoneget = mysql_real_escape_string($zoneget);

$getzoneinfo = mysql_query("SELECT * FROM battlezones WHERE zoneid='$zoneget'");
$zonerows = mysql_fetch_array($getzoneinfo);

$getplayertroops = mysql_query("SELECT troops,logistics FROM $factiondb WHERE factionuser='$username' AND factionname = '$chosencolony'");
$playertroopinfo = mysql_fetch_array($getplayertroops);
	$playertroops = $playertroopinfo['troops'];
	$playerlogistics = $playertroopinfo['logistics'];
			
		switch($allegiance)
		{
			case 'authority':
			$bombtype='Conversion Bombs';
			$shieldtype='Theatre Shields';
			break;
			case 'solidarity':
			$bombtype='Nuclear Warheads';
			$shieldtype='Bunkers';
			break;
			case 'mercantile':
			$bombtype='Kinetic Impactors';
			$shieldtype='Interceptors';
			break;
		}

				$zonename = $zonerows['zonename'];
				$zonesector = $zonerows['zonesector'];
				$owner = $zonerows['owner'];
				$str="";
				$zoneid = $zonerows['zoneid'];
				switch($owner)
				{
					case 'authority':
					$str = 'The Origin Authority';
					break;
					case 'solidarity':
					$str = 'The Solidarity';
					break;
					case 'mercantile':
					$str = 'The Mercantile Union';
					break;
				}
				$authoritytroops = $zonerows['authoritytroops'];
				$mercantiletroops = $zonerows['mercantiletroops'];
				$solidaritytroops = $zonerows['solidaritytroops'];
				$authinfluence = $zonerows['authorityinfluence'];
				$solinfluence = $zonerows['solidarityinfluence'];
				$mercinfluence = $zonerows['mercantileinfluence'];
				echo'<center><strong>You have '.number_format($playertroops).' troops to deploy.</strong><br>
				You have <strong>'.number_format($playerlogistics).'</strong> logistical points.</center>';
				echo '
				<h2>'.$zonename.'</h2><br>
				<b>Owned By: </b>'.$str.'<br>
				<b>Authority Troops: </b>'.number_format($authoritytroops).'<br>';
				if($allegiance == 'authority')
				{ $getshields = mysql_query("SELECT authorityshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Authority '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				echo '<b>Mercantile Troops: </b>'.number_format($mercantiletroops).'<br>';
				if($allegiance == 'mercantile')
				{ $getshields = mysql_query("SELECT mercantileshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Mercantile '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				echo '<b>Solidarity Troops: </b>'.number_format($solidaritytroops).'<br>';
				if($allegiance == 'solidarity')
				{ $getshields = mysql_query("SELECT solidarityshields FROM battlezones WHERE zoneid='$zoneid'");
				$shieldnumber = mysql_result($getshields, 0);
				echo '<b>Solidarity '.$shieldtype.': </b>'.number_format($shieldnumber).'<br>'; }
				echo '<span style="color:#980000">'.$authinfluence.'</span> &nbsp|&nbsp <span style="color:#000099">'.$mercinfluence.'</span> &nbsp|&nbsp <span style="color:#009900">'.$solinfluence.'</span><br>';
				?>