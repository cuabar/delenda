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
		<?php
		switch($allegiance)
		{
			case 'authority':
			$specops = 'Immortal';
			break;
			case 'mercantile':
			$specops = 'Marine';
			break;
			case 'solidarity':
			$specops = 'Stormtrooper';
			break;
		}
		
		echo '<h2>'.$specops.'s Training</h2>';
		?>
		<p>For every War Centre you have built you can train <b>ONE</b> Special Operations unit. All Spec Ops are trained according to the 'Kit' you select for them. The types are as follows, along with the typical mission that Kit is required for (though they can be used in other missions):</p><br>
		<ul>
			<li><b>Assassins:</b> Assassins are one of your starter Kits. They are used to assassinate (duh) leading generals and military personnel. The end result of this is a loss in deployment control, preventing large numbers of troops from being withdrawn by their owners.</li>
			<li><b>Elite Infantry:</b> Another starter Kit. Although frontline assaults are not technically the correct use for Spec Ops units, on occassion Spec Ops are equipped with heavy armour and powerful weapons to support standard troops in battle. Elite Infantry kill enemy troops in large numbers.</li>
			<li><b>Recon:</b> Recon units provide realtime information of the battlefield, allowing for more accurate orbital bombardment. Recon units can be deployed as spies against enemy factions, or can report on the state of enemy troops on the battlefield.</li>
			<li><b>Sabotage:</b> Sabotage units are the most useful, destroying logistical lines and breaking enemy morale. They lower enemy effectiveness.</li>
			<li><b>Support:</b> Support units are those specially designed for medevacs, recovery of downed pilots and vehicles and generally boosting the effectiveness of your own troops.</li>
			<li><b>Black Ops:</b> Although every nation will deny it, they all use Black Operations units for the more morally reprehensible missions. Black Ops are used in only the most powerful missions in battlezones, dealing massive damage to enemy troops and even population centres. They are very expensive and only available when Tier 10 of Spec Ops has been researched. Given everything else stops at Tier 6, there is a sizeable gap between the two.</li>
		</ul>
		<br>
		<p>On every battlezone, there will be several missions which your units can be deployed on. In the future, the War Room will be used to prep and deploy Spec Ops units. Troops and Superweapons will be deployed
		through the Star Map instead. Missions require a certain mix of Spec Ops types and tiers. On occassion, missions will also require other resources. A mission to sneak a bomb into an enemy base will require one bomb
		along with the required troop types. I hope to have <i>loads</i> of mission types eventually.</p><br>
		<hr>
		<br>
		<?php
		$getweapontech = mysql_query("SELECT tier FROM technology WHERE faction='$allegiance' AND techtype='weapontech'");
		$weapontecharray = mysql_fetch_array($getweapontech);
		$tier = $weapontecharray['tier'];
		
		if($message == 1)
		{
			echo '<strong>You cannot afford to train these troops!</strong><br><br>';
		}
		elseif($message == 2)
		{
			echo '<strong>You do not have any available war centres.</strong><br><br>';
		}
		?>
		<div id="trainingbox">
		<form id="traintroops" action="traintroops.php" method="POST">
		<table>
		<tr><th>Operations Kit</th><th>Technological Level</th><th>Training Period</th></tr>
		<tr><td><select name="kittype" id="kittype" onchange="disable(this.value); calculate();">
		<option value="assassin">Assassin</option>
		<?php if($tier >= 10){ echo '<option value="black">Black Operations</option>'; } ?>
		<option value="elite">Elite Infantry</option>
		<option value="recon">Recon</option>
		<?php if($tier >= 4){ echo '<option value="sabotage">Sabotage</option>'; } ?>
		<?php if($tier >= 2){ echo '<option value="support">Support</option>'; } ?>
		</select></td><td>
		<select name="kittier" id="kittier" onchange="calculate();">
		<option value="1">Tier 1</option>
		<?php if($tier >= 3){ echo '<option value="2">Tier 2</option>'; } ?>
		<?php if($tier >= 5){ echo '<option value="3">Tier 3</option>'; } ?>
		<?php if($tier >= 6){ echo '<option value="4">Tier 4</option>'; } ?>
		<?php if($tier >= 7){ echo '<option value="5">Tier 5</option>'; } ?>
		<?php if($tier >= 8){ echo '<option value="6">Tier 6</option>'; } ?>
		</select></td><td>
		<select name="training" id="training" onchange="calculate();">
		<option value="1">One Tick</option>
		<option value="5">Five Ticks</option>
		<option value="10">Ten Ticks</option>
		<option value="20">Twenty Ticks</option>
		<option value="30">Thirty Ticks</option>
		</select></td></tr>
		<tr><td colspan=3><input type="submit" value="Begin Training"></td></tr>
		</table>
		</form>
		</div>
		<br><center><strong><p id="totalcost">Cost: $13500</p></strong></center><br>
		<hr><br>
		<div id="recruitbox">
		<table>
		<tr><th>Kit Type</th><th>Tech Tier</th><th>Skill</th><th>Ready For Combat</th></tr>
		<?php
		$getrecruits = mysql_query("SELECT * FROM recruits WHERE factionuser='$username' AND colony='$chosencolony' AND allegiance='$allegiance'");
		
		while($recruitarray = mysql_fetch_array($getrecruits))
		{
			$kittype = $recruitarray['type'];
			if($kittype == 'black')
			{
				$kittype = 'black operations';
			}
			$traintime = $recruitarray['trainingtime'];
			if($traintime == 0)
			{
				$traintime = 'Ready for combat<br>
								<form id="retrain" action="retrain.php" method="POST">
								<input type="hidden" name="kitid" value="'.$recruitarray['id'].'">
								<select name="training" id="training">
								<option value="1">One Tick</option>
								<option value="5">Five Ticks</option>
								<option value="10">Ten Ticks</option>
								<option value="20">Twenty Ticks</option>
								<option value="30">Thirty Ticks</option>
								</select><input type="submit" value="Train"></form>';
			}
			elseif($traintime == 1)
			{
				$traintime = $traintime.' day remaining';
			}
			else
			{
				$traintime = $traintime.' days remaining';
			}
			$skill = $recruitarray['skill'];
			$tierlevel = $recruitarray['tier'];
			
			echo '<tr><td>'.ucwords($kittype).'</td><td>'.$tierlevel.'</td><td>'.$skill.'</td><td>'.$traintime.'</td></tr>';
		}
		?>
		</table>
		</div>
		</div>
	</div>
  </div>
</div>
<script type="text/javascript">
function disable(val)
{
	var tier = document.getElementById("kittier");
	if(val == "black")
	{
		tier.disabled = true;
	}
	else
	{
		tier.disabled = false;
	}
}

function calculate()
{
	var kittype = document.getElementById("kittype").value;
	var kittier = document.getElementById("kittier").value;
	var traintime = document.getElementById("training").value;
	var costtext = document.getElementById("totalcost");
	
	if(kittype == "assassin")
	{
		var cost = 5000*kittier;
		var trainfactor = 1.5*traintime;
		cost = cost*trainfactor;
		costtext.innerHTML = "Cost: $"+cost;
	}
	else if(kittype == "elite")
	{
		var cost = 3000*kittier;
		var trainfactor = 1.5*traintime;
		cost = cost*trainfactor;
		costtext.innerHTML = "Cost: $"+cost;
	}
	else if(kittype == "support")
	{
		var cost = 3500*kittier;
		var trainfactor = 1.5*traintime;
		cost = cost*trainfactor;
		costtext.innerHTML = "Cost: $"+cost;
	}
	else if(kittype == "black")
	{
		var cost = 75000;
		var trainfactor = 1.5*traintime;
		cost = cost*trainfactor;
		costtext.innerHTML = "Cost: $"+cost;
	}
	else if(kittype == "recon")
	{
		var cost = 1500*kittier;
		var trainfactor = 1.5*traintime;
		cost = cost*trainfactor;
		costtext.innerHTML = "Cost: $"+cost;
	}
	else if(kittype == "sabotage")
	{
		var cost = 9000*kittier;
		var trainfactor = 1.5*traintime;
		cost = cost*trainfactor;
		costtext.innerHTML = "Cost: $"+cost;
	}
}
</script>
</body>
</html>