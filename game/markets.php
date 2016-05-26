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
		<center><form id="sell" action="sellresource.php" method="POST">
		<label for="volume">Amount: </label><input type="number" name="volume" id="volume" min="0" value="0"> <select name="resource">
		<option value="coal">Coal</option><option value="copper">Copper</option><option value="diamonds">Diamonds</option>
		<option value="gold">Gold</option><option value="illucite">Illucite</option><option value="livestock">Livestock</option>
		<option value="oil">Oil</option><option value="palladium">Palladium</option><option value="silver">Silver</option>
		<option value="tungsten">Tungsten</option></select><br>
		<label for="cost">Cost per unit: </label> <input type="number" name="cost" id="cost" min="0" value="0"><br><input type="submit" name="submit" value="Sell"></form>
		<?php
		if($message == 1)
		{
			echo '<br><strong>You must enter a value greater than zero.</strong>';
		}
		elseif($message == 2)
		{
			echo '<br><strong>Only numerical values are accepted.</strong>';
		}
		elseif($message == 3)
		{
			echo '<br><strong>You cannot afford to buy this.</strong>';
		}?>
		<br><br>
		
		<center><?php $getmarketsquery = mysql_query("SELECT * FROM markets WHERE factionname='$allegiance' ORDER BY cost, resource, amount");
		echo '<table>
		<tr><th>Owner</th><th>Resource</th><th>Amount Available</th><th>Cost per Unit</th><th></th></tr>';
		while($getmarketsrow = mysql_fetch_array($getmarketsquery))
		{
			$colony = $getmarketsrow['colonyname'];
			$resource = $getmarketsrow['resource'];
			$amount = $getmarketsrow['amount'];
			$cost = $getmarketsrow['cost'];
			$id = $getmarketsrow['entryid'];
			
			echo '<tr><td>'.$colony.'</td><td>'.ucwords($resource).'</td><td>'.number_format($amount).'</td><td>'.number_format($cost).'</td><td><form id=\'purchase\' action=\'purchaseresource.php\' method=\'POST\'><input type=\'number\' name=\'volume\' id=\'volume\' min=\'0\' max=\''.$amount.'\'><input type=\'hidden\' name=\'id\' id=\'id\' value=\''.$id.'\'><input type=\'submit\' name=\'Submit\' value=\'Purchase\'></form></td></tr>';
		}
		echo '</table>';
		?></center>
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
