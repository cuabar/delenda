<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$message = $_GET['msg'];
$message = stripslashes($message);
$message = mysql_real_escape_string($message);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Delenda Est</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<!-- Begin Wrapper -->
<div id="wrapper">
  <!-- Begin Header -->
  <div id="header"><h1><?php include'includes/header.php' ?></h1></div>
  <!-- End Header -->
  <!-- Begin Naviagtion -->
  <div id="navigation"><?php include 'includes/menu.php' ?></div>
  <!-- End Naviagtion -->
  <!-- Begin Content -->
  <div id="content">
	<div id="gamecontent">
		<div id="constructinfo">
		<h2>Research</h2>
		<p>Research at the moment only governs acquisition of RP and construction of research labs. I want to develop a full tech tree for each faction before going further with it.</p>
		<p>Complexes cost 500,000 to build, and take up 50 km<sup>2</sup>.</p>
		<?php $getcomplexquery = mysql_query("SELECT techcomplex,researchpoints,land,factionbank FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
		$getcomplexrows = mysql_fetch_row($getcomplexquery);
		$techcomplex = $getcomplexrows[0];
		$researchpoints = $getcomplexrows[1];
		$availableland = $getcomplexrows[2];
		$balance = $getcomplexrows[3];
		
		if($message == 1)
		{
			echo '<strong>You cannot afford this construction project</strong><br>';
		}
		if($message == 2)
		{
			echo '<strong>You do not have enough free space.</strong><br>';
		}
		if($message == 3)
		{
			echo '<strong>You must enter a numerical value.</strong><br>';
		}
		if($message == 4)
		{
			echo '<strong>You do not have enough complexes to demolish this many.</strong><br>';
		}?>
		<br>
		<b>Available Land: </b> <?php echo number_format($availableland); ?><br>
		<b>Bank Balance: </b><?php echo number_format($balance); ?><br><br>
		<b>Number of Complexes: </b><?php echo number_format($techcomplex); ?><br>
		<b>Research Points: </b><?php echo number_format($researchpoints); ?><br><br>
		
		<form id='research' action='researchbuild.php' method='post'>
		<label for='buildings'>Complexes to Construct: </label>
		<input type='number' name='buildings' id='buildings' maxlength='3' value='0'><br>
		<input type='submit' name='submit' id='submit' value='Begin Construction'>
		</form>
		<br><br>
		<hr>
		<br>
		<h2>Available Technologies</h2><br>
		<?php $gettechnologies = mysql_query("SELECT * FROM technology WHERE faction = '$allegiance'");
		$getplayerdata = mysql_query("SELECT researchpoints, factionbank FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
		$getplayerresources = mysql_query("SELECT * FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'");
		$playerdatarow=mysql_fetch_array($getplayerdata);
		$playerrp = $playerdatarow['researchpoints'];
		$playerbank = $playerdatarow['factionbank'];
		$playerresource = mysql_fetch_array($getplayerresources);
		$oil = $playerresource['oil']; $gold = $playerresource['gold']; $silver = $playerresource['silver'];
		$tungsten = $playerresource['tungsten']; $palladium = $playerresource['palladium']; $livestock = $playerresource['livestock'];
		$illucite = $playerresource['illucite']; $diamonds = $playerresource['diamonds']; $copper = $playerresource['copper'];
		$coal = $playerresource['coal'];
		
		if($message == 4)
		{
			echo 'You do not have enough research points';
		}
		if($message == 7)
		{
			echo 'You do not have enough money.';
		}
		if($message == 8)
		{
			echo 'You do not have enough resources.';
		}
		if($message == 5)
		{
			echo 'You cannot enter a negative amount.';
		}
		if($message == 6)
		{
			echo 'You must enter a numerical value.';
		}
		
		while($techrows = mysql_fetch_array($gettechnologies))
		{
			$techname = $techrows['techname'];
			$techtype = $techrows['techtype'];
			$rprequired = $techrows['rprequired'];
			$rpacquired = $techrows['rpacquired'];
			$moneyrequired = $techrows['moneyrequired'];
			$moneyacquired = $techrows['moneyacquired'];
			$resourcetype = $techrows['resourcetype'];
			$resourceacquired = $techrows['resourceacquired'];
			$resourcerequired = $techrows['resourcerequired'];
			$ticksleft = $techrows['ticksrequired'];
			$techid = $techrows['techid'];
			$tier = $techrows['tier'];
			
			if($techtype == 'mining')
			{
				$techtext = 'Government sponsored mining efforts provides 10,000 of every non-Illucite resource and 1,000 Illucite to the markets.';
			}
			elseif($techtype == 'troopeffective')
			{
				if($allegiance == 'solidarity')
				{
					$techtext = 'By enhancing the realism and safety of Solidarity live fire drills, troops come out more equipped to deal with the harsh environment of war.';
				}
				elseif($allegiance == 'mercantile')
				{
					$techtext = 'Improved virtual reality simulators massively improves Guardian\'s combat capabilities on the battlefield.';
				}
				elseif($allegiance == 'authority')
				{
					$techtext = 'More efficient programming algorithms decreases response times and increases the effectiveness of Drones.';
				}
			}
			elseif($techtype == 'troopbuild')
			{
				$techtext = 'Increases in propaganda efficiency have driven more people to join the war effort.';
			}
			elseif($techtype == 'troopregen')
			{
				$techtext = 'Nanotechnology has been perfected to a point where individual Drones can self repair when destroyed on the battlefield';
			}
			elseif($techtype == 'weapontech' && $tier < 11)
			{
				switch($tier)
				{
					case 1:
					$techtext = 'Develop combat exoskeletons designed for support roles. Unlocks Support special operations units.';
					break;
					case 2:
					$techtext = 'Improve existing combat exoskeleton designs. Upgrades all kits to Tier 2.';
					break;
					case 3:
					$techtext = 'Develop combat exoskeletons designed for saboteur roles. Unlocks Sabotage special operation units.';
					break;
					case 4:
					$techtext = 'Improve existing combat exoskeleton designs. Upgrades all kits to Tier 3.';
					break;
					case 5:
					$techtext = 'Improve existing combat exoskeleton designs. Upgrades all kits to Tier 4.';
					break;
					case 6:
					$techtext = 'Improve existing combat exoskeleton designs. Upgrades all kits to Tier 5.';
					break;
					case 7:
					$techtext = 'Improve existing combat exoskeleton designs. Upgrades all kits to Tier 6.';
					break;
					case 8:
					$techtext = 'New Superweapon designs are drafted, unlocking mid-tier superweapon types.';
					break;
					case 9:
					$techtext = 'Experimental prototype designs are funded and given permission to be utilised in combat zones. Unlocks Black Operations kits.';
					break;
					case 10:
					$techtext = 'Faction command authorises research into formerly blacklisted technologies. Unlocks end-game superweapons and bonuses.';
					break;
				}
			}
			elseif($techtype == 'extractorauto')
			{
				$techtext = 'Further automation of Extractor processes improves efficiency of the mining process. Extractors will see a 1% increase in extraction rates.';
			}
			elseif($techtype == 'defensiveprep')
			{
				$techtext = 'Improvements in starbases, ground based installations and defensive tactics training improves troop effectiveness by 1% in all star systems owned by the Union.';
			}
			elseif($techtype == 'scorched')
			{
				$techtext = 'Starbases, ground installations and starship refuelling stations are designed to allow for easy sabotage. Troop effectiveness is increased by 1% when defending worlds own by the Solidarity.';
			}
			elseif($techtype == 'autowars')
			{
				$techtext = 'Orbital Defences are advanced, improving their ability to strike at enemy forces as they deploy to a star system. Enemy forces will suffer an additional 1% casualties every time they deploy to star systems owned by the Authority.';
			}
			
			if($techtype != 'weapontech')
			{
				echo '<h3>'.$techname.'</h3><br>'.$techtext.'<br><b>RP Required:</b> '.number_format($rprequired).' - <b>RP Acquired:</b> '.number_format($rpacquired).' <br><b>Money Required:</b> $'.number_format($moneyrequired).' - <b>Money Acquired:</b> $'.number_format($moneyacquired).'<br>
				<b>Resource Required:</b> '.ucwords($resourcetype).' - <b>Resource Amount Required:</b> '.number_format($resourcerequired).' - <b>Resource Acquired:</b> '.number_format($resourceacquired).' <br><b>Ticks to Completion:</b> '.$ticksleft.'<br>
				<form id="tech" method="POST" action="researchtech.php">
				<b>Contribute: </b><input type="number" value="0" name="contribute" id="contribute"><select name="contributetype">
				<option value="research">Research</option>
				<option value="money">Money</option>';
				if($resourcetype != 'none')
				{
					echo '<option value="resources">'.ucwords($resourcetype).'</option>';
				}
				echo '</select><input type="hidden" value="'.$techid.'" name="id"><input type="submit" value="Donate"></form>
				<br><br>';
			}
			elseif($techtype == 'weapontech' && $tier < 11)
			{
				echo '<h3>'.$techname.'</h3><br>'.$techtext.'<br><b>RP Required:</b> '.number_format($rprequired).' - <b>RP Acquired:</b> '.number_format($rpacquired).' <br><b>Money Required:</b> $'.number_format($moneyrequired).' - <b>Money Acquired:</b> $'.number_format($moneyacquired).'<br>
				<b>Resource Required:</b> '.ucwords($resourcetype).' - <b>Resource Amount Required:</b> '.number_format($resourcerequired).' - <b>Resource Acquired:</b> '.number_format($resourceacquired).' <br><b>Ticks to Completion:</b> '.$ticksleft.'<br>
				<form id="tech" method="POST" action="researchtech.php">
				<b>Contribute: </b><input type="number" value="0" name="contribute" id="contribute"><select name="contributetype">
				<option value="research">Research</option>
				<option value="money">Money</option>';
				if($resourcetype != 'none')
				{
					echo '<option value="resources">'.ucwords($resourcetype).'</option>';
				}
				echo '</select><input type="hidden" value="'.$techid.'" name="id"><input type="submit" value="Donate"></form>
				<br><br>';
			}
			else
			{
				echo '<br><br>';
			}
		}
		?>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
