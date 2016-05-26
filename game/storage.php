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
		<h2>Resources</h2>
		<p>Resources give your colony certain bonuses so long as you have those resources present in your storage facilities. All resources drain at a rate of 1 per thousand population, per tick. As of now, the effects of resources are not in place. I don't want to include them until I have more resources in play and trade worked out so that I can cover the code all at once and not be continually adding to it and editing it.</p>
		<p>Resource extractors cost 10,000 and take up 50 km<sup>2</sup>. They provide your resources at a rate of 10 per extractor per tick.</p>
		<?php 
		if($allegiance != 'mercantile')
		{
			$getresourcequery = mysql_query("SELECT extractor,resource,land,factionbank FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
			$getresourcerows = mysql_fetch_row($getresourcequery);
			$extractors = $getresourcerows[0];
			$resource = $getresourcerows[1];
			$availableland = $getresourcerows[2];
			$balance = $getresourcerows[3];
			$getresamountquery=mysql_query("SELECT * FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'") or die(mysql_error());
			$getresamountrow=mysql_fetch_array($getresamountquery);
			echo '<br>
			<b>Available Land: </b> '.number_format($availableland).'<br>
			<b>Bank Balance: </b>'.number_format($balance).'<br><br>
			<b>Number of Extractors: </b>'.number_format($extractors).'<br>
			<b>Resources Available: </b><div id="capitalise">'.$resource.'</div><br><br>';
	
		}
		else
		{
			$getresourcequery = mysql_query("SELECT extractor,resource,land,factionbank,mercresource FROM $factiondb WHERE factionuser='$username' AND factionname='$chosencolony'");
			$getresourcerows = mysql_fetch_row($getresourcequery);
			$extractors = $getresourcerows[0];
			$resource = $getresourcerows[1];
			$availableland = $getresourcerows[2];
			$balance = $getresourcerows[3];
			$resource2 = $getresourcerows[4];
			
			$getresamountquery=mysql_query("SELECT * FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'");
			$getresamountrow=mysql_fetch_array($getresamountquery);
			$resourceamount=$getresamountrow[$resource];
			$resource2amount=$getresamountrow[$resource2];
			echo '<br>
			<b>Available Land: </b> '.number_format($availableland).'<br>
			<b>Bank Balance: </b>'.number_format($balance).'<br><br>
			<b>Number of Extractors: </b>'.number_format($extractors).'<br>
			<b>Resources Available: </b><div id="capitalise">'.$resource.'</div> and <div id="capitalise">'.$resource2.'</div><br><br>';
		}?>
		<table><tr><th>Coal</th><th>Copper</th><th>Diamonds</th><th>Gold</th><th>Livestock</th><th>Oil</th><th>Palladium</th><th>Silver</th><th>Tungsten</th><th>Illucite</th></tr>
		<tr><td><?php echo $getresamountrow['coal']; ?></td><td><?php echo $getresamountrow['copper']; ?></td>
		<td><?php echo $getresamountrow['diamonds']; ?></td><td><?php echo $getresamountrow['gold']; ?></td>
		<td><?php echo $getresamountrow['livestock']; ?></td><td><?php echo $getresamountrow['oil']; ?></td>
		<td><?php echo $getresamountrow['palladium']; ?></td><td><?php echo $getresamountrow['silver']; ?></td>
		<td><?php echo $getresamountrow['tungsten']; ?></td><td><?php echo $getresamountrow['illucite']; ?></td></tr><table><br>&nbsp<br>
		<form id='extractor' action='resourcebuild.php' method='post'>
		<label for='buildings'>Extractors to Construct: </label>
		<input type='number' name='buildings' id='buildings' maxlength='3' value='0'><br>
		<input type='submit' name='submit' id='submit' value='Begin Construction'>
		</form>
		<center><?php if($message == 1){ echo '<strong>You cannot afford this many extractors.</strong>'; }
		if($message == 2){ echo '<strong>You do not have land for this many extractors.</strong>'; }
		if($message == 1){ echo '<strong>You do not have that many extractors to destroy.</strong>'; }?>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
