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
  <div id="navigation"> <?php include 'includes/menu.php' ?> </div>
  <!-- End Naviagtion -->
  <!-- Begin Content -->
  <div id="content">
	<div id="gamecontent">
		<div id="constructinfo">
		<?php $colonyconstructquery = mysql_query("SELECT FORMAT(residential,0),FORMAT(commerce,0),FORMAT(educational,0),FORMAT(hospitals,0),FORMAT(law,0),FORMAT(land,0),FORMAT(factionbank,0) FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
		$colonyconstructrows = mysql_fetch_row($colonyconstructquery);
		$residentialdistricts = $colonyconstructrows[0];
		$commercialdistricts = $colonyconstructrows[1];
		$educationdistricts = $colonyconstructrows[2];
		$hospitaldistricts = $colonyconstructrows[3];
		$lawdistricts = $colonyconstructrows[4];
		$availableland = $colonyconstructrows[5];
		$balance = $colonyconstructrows[6];
		
		$citizens = $residentialdistricts * 10000;
		$citizens = number_format($citizens);
		$educover = $educationdistricts * 12000;
		$educover = number_format($educover);
		$hoscover = $hospitaldistricts * 15000;
		$hoscover = number_format($hoscover);
		$lawcover = $lawdistricts * 15000;
		$lawcover = number_format($lawcover);?>
			<h2>Districts</h2>
			<p>Districts form the majority of your colony's infrastructure. By zoning areas for construction of various districts, you can maintain your colony's
			population.</p>
			<b>Residential Districts</b>
			<p>Provide additional living space for 10,000 civilians. Consumes 1 area of land per unit.</p>
			<b>Commercial</b>
			<p>Provide an additional 7,500 income per district. Consumes 1 area of land per unit. Only one commercial district can be built per every 2 residential districts.</p>
			<b>Educational Districts</b>
			<p>Provides education for 12,000 civilians. Consumes 1 area of land per unit.</p>
			<b>Healthcare Districts</b>
			<p>Provide healthcare and support services for 15,000 civilians. Consumes 2 areas of land per unit.</p>
			<b>Law Enforcement Districts</b>
			<p>Provide law enforcement and emergency services for 15,000 civilians. Consumes 2 areas of land per unit.</p>
			<br>
			<center><b>Available Land: </b> <?php echo $availableland ?><br>
			<b>Bank Balance: </b><?php echo $balance ?><br>
			<?php if($message == 1)
			{
				echo '<strong>You cannot afford this construction project.</strong><br>';
			}
			if($message == 2)
			{
				echo '<strong>You do not have enough space for this construction project.</strong><br>';
			}
			if($message == 3)
			{
				echo '<strong>You must build more residential districts to accomodate this many commercial districts.</strong><br>';
			}
			if($message == 4)
			{
				echo '<strong>Several commercial districts have been left abandoned as your population depletes. They have been torn down as a result.</strong><br>';
			}
			if($message == 5)
			{
				echo '<strong>You do not have enough buildings to demolish!</strong>';
			}?>
			</center>
			<form id='construct' action='construct.php' method='post'>
			<center><table width="90%">
			<tr><th width="20%">Residential</th>
			<th width="20%">Commercial</th>
			<th width="20%">Educational</th>
			<th width="20%">Healthcare</th>
			<th width="20%">Law</th></tr>
			<tr><td><?php echo $residentialdistricts.' Districts'; ?></td>
			<td><?php echo $commercialdistricts.' Districts'; ?></td>
			<td><?php echo $educationdistricts.' Districts'; ?></td>
			<td><?php echo $hospitaldistricts.' Districts'; ?></td>
			<td><?php echo $lawdistricts.' Districts'; ?></td></tr>
			<tr><td><?php echo $citizens.' Citizens'; ?></td>
			<td>&nbsp</td>
			<td><?php echo $educover.' Citizens Covered'; ?></td>
			<td><?php echo $hoscover.' Citizens Covered'; ?></td>
			<td><?php echo $lawcover.' Citizens Covered'; ?></td></tr>
			<tr><td>$10,000</td>
			<td>$40,000</td>
			<td>$15,000</td>
			<td>$20,000</td>
			<td>$20,000</td></tr>
			<tr><td><input type="number" name="residential" maxlength="3" value="0" size="3"></td>
			<td><input type="number" name="commercial" maxlength="3" value="0" size="3"></td>
			<td><input type="number" name="educational" maxlength="3" value="0" size="3"></td>
			<td><input type="number" name="healthcare" maxlength="3" value="0" size="3"></td>
			<td><input type="number" name="law" maxlength="3" value="0" size="3"></td></tr>
			<tr><td></td><td></td><td><input type="submit" value="Begin Construction"></td><td></td><td></td></tr>
			</table></center>
			</form>
			
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>