<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$message = $_GET['msg'];
$message = stripslashes($message);
$message = mysql_real_escape_string($message);

$getbalance = mysql_query("SELECT factionbank FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
$balance = mysql_result($getbalance, 0);
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
		<h2>Superweapons</h2>
		<p>There are two types of superweapons (which are named differently depending on your faction), the Bombs and the Shields. Bombs will kill large numbers of deployed enemy troops, so long as there are no shields present. If Shields are deployed in a battlezone, they will absorb the impact of bombs when they are dropped.
		War Factories produce bombs and shields. Each War Factory can produce one thing per tick, but can potentially store an infinite amount of each. Fluff wise
		The bombs are considered to be carried by your starships, and the shields by engineer batallions in your troop armies, rather than stored in your colony.</p>
		<br>
		War Factories cost 250,000 and take up 50km<sup>2</sup>.<br>
		<?php
		echo '<strong>Balance:</strong> $'.number_format($balance);
		?><br><br>
		<h3>War Factories</h3>
		<?php if($message == 2)
		{
			echo '<strong>You cannot afford this construction project!</strong><br>';
		}
		if($message == 3)
		{
			echo '<strong>You do not have enough land for this construction project.</strong><br>';
		}
		?>
		<form id="warfactory" method="POST" action="warfactory.php">
		<input type="number" name="construct" value="0"><input type="submit" value="Construct"></form>
		<br><hr>
		<?php
		$getsuperweapons = mysql_query("SELECT bombs,shields FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
		$superweaponarray = mysql_fetch_array($getsuperweapons);
		$getwarfactories = mysql_query("SELECT freefactories,warfactories FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
		$factoryarray = mysql_fetch_array($getwarfactories);
		switch($allegiance)
		{
			case 'authority':
			$bombs = 'Conversion Bombs';
			$bombtext = '<h3>Conversion Bombs</h3><p>Conversion Bombs are incredibly powerful warheads used in orbital bombardment by Authority forces. Unlike more conventional nuclear warheads,
			Conversion Bombs leave no radioactive fallout. They operate through the use of micro black holes, which are carefully maintained until the moment of detonation.
			When detonated (or if containment is breached), the phenomenal mass of the black hole is instantly converted into energy. Conversion Bombs are the most destructive weapon
			on the modern battlefield as a result, but are very expensive and time consuming to construct.</p><br>Conversion Bombs cost 2,000,000. They take 6 ticks to construct.<br><br>';
			$shields = 'Theatre Shields';
			$shieldtext = '<h3>Theatre Shields</h3><p>Theatre Shields are used by Authority forces to defend themselves from orbital bombardment. Despite the advanced nature of the shields, they are no more effective
			than even the Solidarity bunker networks at defending Drones from the weaponry carried by orbiting starships. They do have the advantage however of being very easy to relocate where needed
			by comparison to bunker networks, but not to the same degree Mercantile Interceptors are.</p><br>Theatre Shields cost 750,000. They take 6 ticks to construct.<br><br>';
			$buster = 'Lancers';
			$bustertext = '<h3>Lancers</h3><p>During the protracted conflict with the Solidarity, Mercantile forces realised that the focused delivery of energy provided by an Impactor did little to stave the sheer numbers of conscripts the Solidarity was capable of producing.
			Eventually, Impactors fitted with thermobaric warheads were developed to work in conjunction with standard kinetic Impactors. Unfortunately, the resulting weapon proved useless against defences such as the sturdy Solidarity bunkers and the Authority theatre shields,
			meaning the weapon was only of use against exposed enemy forces.</p><br>Thermobaric Detonators cost 900,000. They take 6 ticks to construct.<br><br>';
			$midweapon = 'Drone Hosts';
			$midtext = '<h3>Drone Hosts</h3><p>To make the most out of their ability to create artificial wormholes, the Authority developed a special type of interstellar missile. The Drone Host
			is an all-in-one assault weapon. Upon entering the system, the Host deploys a swarm of combat drones before reverting into a kinetic kill vehicle and destroying enemy forces and defences were possible.
			Combined with their ability to strike behind enemy lines at previously safe star systems, Drone Hosts are a terrifying weapon for defenders to have to face.</p><br>Drone Host cost 1,500,000. They take 8 ticks to construct.<br><br>';
			break;
			case 'mercantile':
			$bombs = 'Kinetic Impactors';
			$bombtext = '<h3>Kinetic Impactor</h3><p>Mercantile Kinetic Impactors are clean and efficient weapons used to support ground troops by Union starships in orbit. They rely on kinetic energy to
			to devastate enemy forces but unlike older designs which relied on gravity to provide acceleration, Union Impactors are launched via railcannons. While the Impactors themselves are little more than massive tungsten cylinders with some small degree of guidance systems, the massive railguns
			used to launch them are very expensive to construct and maintain. Even with their advanced railgun technology, the massive amount of energy used in launching an Impactor has
			disastrous effects on the launching cannon, melting the rails and rendering the gun inoperable until repairs can be effected.</p><br>Impactors cost 800,000. They take 6 ticks to construct.<br><br>';
			$shields = 'Interceptors';
			$shieldtext = '<h3>Interceptors</h3><p>Given their predisposition towards defensive warfare, it is no surprise that the Mercantile Interceptor is often hailed as the ultimate in defence against orbital bombardment.
			Interceptors are no more effective than any other form of defence, but the Union has perfected their construction to such a degree that they are extremely easy to produce. Interceptors
			are self operating aerial units which patrol the skies above Union forces. As soon as an enemy munition enters the atmosphere, the nearest Interceptor will guide itself on a strike run against the incoming warhead.
			An internal micro nuclear warhead is detonated, providing the energy for a powerful one use laser which obliterates the warhead. This laser is so powerful it can even punch through the shielding on a Conversion Bomb.
			Unfortunately, the nuclear detonation destroys the Interceptor. </p><br>Interceptors cost 750,000. They take 6 ticks to construct.<br><br>';
			$buster = 'Thermobaric Impactors';
			$bustertext = '<h3>Thermobaric Impactors</h3><p>During the protracted conflict with the Solidarity, Mercantile forces realised that the focused delivery of energy provided by an Impactor did little to stave the sheer numbers of conscripts the Solidarity was capable of producing.
			Eventually, Impactors fitted with thermobaric warheads were developed to work in conjunction with standard kinetic Impactors. Unfortunately, the resulting weapon proved useless against defences such as the sturdy Solidarity bunkers and the Authority theatre shields,
			meaning the weapon was only of use against exposed enemy forces.</p><br>Thermobaric Detonators cost 900,000. They take 6 ticks to construct.<br><br>';
			$midweapon = 'Interceptor Grids';
			$midtext = '<h3>Interceptor Grids</h3><p>The weakness of Mercantile Interceptors is their standalone nature. An Interceptor is forced to rely on its own sensors to detect inbound attacks, and a single Interceptor cannot be everywhere.
			In response to this, the Mercantiles developed a way to link multiple Interceptors together for greater efficiency. The resulting Grid allows for the maintenance of more advanced Interceptors, which use a fusion reactor to power their 
			anti ballistic laser. This gives a single Interceptor multiple uses before the reactor overloads and destroys the Interceptor.</p><br>Interceptor Grids cost 2,000,000. They take 10 ticks to construct.<br><br>';
			break;
			case 'solidarity':
			$bombs = 'Nuclear Warheads';
			$bombtext = '<h3>Nuclear Warheads</h3><p>For centuries the Hydrogen Bomb served humanity as its ultimate weapon of mass destruction. Though technology has advanced siginifcantly in orhter areas, the Nuclear bomb remains at the
			top of the list of cost effective WMDs. The Solidarity uses nuclear weapons regularly, from tactical nuclear devices in ground based artillery to the massive warheads used in orbital strikes. Although some decry the relatively ancient technology
			that makes up nuclear weapons (especially when compared to other weapons technologies which have changed massively over time), the nuke offers such a ferocious release of energy for its size that it is impossible for other weapon systems to easily match it
			without an obscene cost being associated with them. Unlike the Union which prefers cleaner weapons, or the Authority which doesn\'t care for costs, the Solidarity still uses this most efficient of weapons.</p><br>Nukes cost 1,600,000. They take 6 ticks to construct.<br><br>';
			$shields = 'Bunkers';
			$shieldtext = '<h3>Bunkers</h3><p>Despite their meagre appearance, Solidarity Bunkers are actually very resilient and serve to protect their troops from orbital bombardment. Layers of solid earth and reinforced concrete still serve to protect troops from bombardment just as well as in the past.
			Unfortunately, as resilient as the bunker networks are, in the age of modern weaponry a network is usually good for one single bombardment. The force of whatever weapon strikes is usually enough to shatter the outer layers of concrete and throw off massive amounts of earth and rock, leaving the bunker
			much more vulnerable. If the Bunker is not repaired, or the troops do not relocate to a new network, any secondary bombardments can bring the bunker down on top of those sheltering within.</p><br>Bunkers cost 750,000. They take 6 ticks to construct.<br><br>';
			$buster = 'Penetrators';
			$bustertext = '<h3>Penetrators</h3><p>When facing down entrenched opponents, the Solidarity deploys specially designed missile weapons which penetrate armour and shields with equal efficiency. Against either ground fortifications or orbital starbases, 
			Penetrators are used to punch a hole in static defences leaving the armies inside vulnerable to bombardment with more destructive weaponry.</p><br>Penetrators cost 800,000. They take 6 ticks to construct.<br><br>';
			$midweapon = 'Chemical Warheads';
			$midtext = '<h3>Chemical Warheads</h3><p>Chemical Warhead are specifically designed to eliminate large numbers of enemy troops. Typically deployed only when all enemy defences are destroyed,
			Chemical Warheads excel at wiping out entire battalions and also rendering the battlefield hostile to enemy forces through the remnants of the attack. They do not, unfortunately,
			have the strength to destroy enemy defences.</p><br>Chemical Warheads cost 1,200,000. They take 8 ticks to construct.<br><br>';
			break;
		}
		echo $bombtext.number_format($superweaponarray['bombs']).' <strong>'.$bombs.'</strong><br><br>'.$shieldtext.number_format($superweaponarray['shields']).' <strong>'.$shields.'</strong>';
		?><br><br><h3>Weapon Manufacturing</h3>
		<p>You have <?php echo number_format($factoryarray['freefactories']); ?> War Factories available.</p><br>
		<?php if($message == 1){echo 'You cannot afford construction of these superweapons<br><br>'; } ?>
		<?php $getweaponqueues = mysql_query("SELECT * FROM warfactories WHERE allegiance='$allegiance' AND colony='$chosencolony' AND user='$username'");
		$buildnumber = 1;
		while ($weaponbuildinfo = mysql_fetch_array($getweaponqueues))
		{
			if($weaponbuildinfo['building'] == 'bomb')
			{
				if($allegiance == 'authority')
				{
					$building = 'Conversion Bomb';
				}
				elseif($allegiance == 'solidarity')
				{
					$building = 'Nuclear Warhead';
				}
				elseif($allegiance == 'mercantile')
				{
					$building = 'Kinetic Impactor';
				}
			}
			elseif($weaponbuildinfo['building'] == 'shield')
			{
				if($allegiance == 'authority')
				{
					$building = 'Theatre Shield';
				}
				elseif($allegiance == 'solidarity')
				{
					$building = 'Bunker';
				}
				elseif($allegiance == 'mercantile')
				{
					$building = 'Interceptor';
				}
			}
			echo $buildnumber.'. '.$building.' | <strong>'.$weaponbuildinfo['ticksleft'].' Ticks Left</strong><br> ';
			$buildnumber++;
		}		
		?>
		<?php if($factoryarray['freefactories'] > 0){echo '<h3>Weapon Construction</h3>';}?>
		<form id="weaponsbuild" action="weaponbuild.php" method="POST">
		<?php
		for($counter = 1; $counter <= $factoryarray['freefactories']; $counter++)
		{
			echo $counter.'. <input type="radio" name="factory'.$counter.'" value="none" checked="checked">None <input type="radio" name="factory'.$counter.'" value="bomb">'.$bombs.' <input type="radio" name="factory'.$counter.'" value="shield">'.$shields.'<br><br>';
		}
		?>
		<input type="hidden" name="freefactories" value="<?php echo $factoryarray['freefactories']; ?>">
		<?php if($factoryarray['freefactories'] > 0){echo '<input type="submit" value="Order Construction">';} ?>
		</form>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
