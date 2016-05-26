<?php
require '../includes/connect.php';

require 'includes/playerdata.php';
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
		<div id="budgetinfo">
			<!--Get Budget Details-->
			<?php $budgetquery = mysql_query("SELECT factionbank,factionpop,tax,happiness,commerce,techcomplex,trainingground,barracks,warfactories FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'") or die(mysql_error());
			$budgetarray = mysql_fetch_array($budgetquery);
			$balance = $budgetarray['factionbank'];
			$population = $budgetarray['factionpop'];
			$taxrate = $budgetarray['tax'];
			$happiness = $budgetarray['happiness'];
			$commercialamount = $budgetarray['commerce'] * 7500;
			$five = "";
			$ten="";
			$twenty="";
			$quarter="";
			
			$resquery = mysql_query("SELECT illucite, gold, silver FROM resourcebanks WHERE factionuser='$username' AND factionname='$chosencolony'");
			$resarray = mysql_fetch_array($resquery);
			$illuciteamount = $resarray['illucite'];
			$silveramount = $resarray['silver'];
			$goldamount = $resarray['gold'];
			
			switch($taxrate)
			{
				case '5':
				$five = "selected";
				break;
				case '10':
				$ten = "selected";
				break;
				case '20':
				$twenty = "selected";
				break;
				case '25':
				$quarter = "selected";
				break;
			}

			$happinessfactor = $happiness/100;
			$taxfactor = $taxrate/100;
			
			$income = $population * 2;
			$income = $income*$happinessfactor;
			$income = $income*$taxfactor;
			if($silveramount > 0)
			{
				$income = $income*1.01;
			}
			$taxesvalue = $income;
			if($goldamount > 0)
			{
				$commercialamount = $commercialamount*1.01;
			}
			$income = $income + $commercialamount;
			//Training Ground Expenses
			$tgexpense = $budgetarray['trainingground'];
			$tgexpense = $tgexpense*4000;
			$barexpense = $budgetarray['barracks'];
			$barexpense = $barexpense*1000;
			//Administration Costs for Population
			$popexpense = $budgetarray['factionpop'];
			$popexpensefactor = $popexpense/10000000;
			$popexpensefactor = $popexpensefactor*$popexpense;
			$popexpense = $popexpense*0.02;
			$popexpense = $popexpensefactor+$popexpense;
			//Research Costs
			$tccosts = $budgetarray['techcomplex'];
			$tccosts = $tccosts * 4000;
			//War Factory Costs
			$warfactoryexpense = $budgetarray['warfactories'];
			$warfactoryexpense = $warfactoryexpense*800;
	
			$expenses = $tgexpense+$popexpense+$tccosts+$barexpense;

			$projected_total = $balance + $income;
			$projected_total=$projected_total - $expenses;?>
			<table>
			<tr><th colspan="2">Income</th><th colspan="2">Expenses</th></tr>
			<tr><th>Balance</th><td><?php echo number_format($balance); ?></td><th>Administration</th><td><?php echo number_format($popexpense); ?></td></tr>
			<tr><th>Population</th><td><?php echo number_format($population); ?></td><th>Training Grounds</th><td><?php echo number_format($tgexpense); ?></td></tr>
			<tr><th>Happiness</th><td><?php echo $happiness; ?></td><th>Barracks</th><td><?php echo number_format($barexpense); ?></td></tr>
			<tr><th>Tax Rate</th><td><?php echo $taxrate.'%'; ?></td><th>War Centres</th><td>0</td></tr>
			<tr><th>Tax Income</th><td><?php echo number_format($taxesvalue); ?></td><th>War Factories</th><td><?php echo number_format($warfactoryexpense); ?></td></tr>
			<?php if($silveramount > 0){echo '<tr><th>Silver Tax Bonus</th></td><td>1%</td></tr>';} ?>
			<tr><th>Commercial</th><td><?php echo number_format($commercialamount); ?></td><th>Tech Complexes</th><td><?php echo number_format($tccosts); ?></td></tr>
			<?php if($goldamount > 0){echo '<tr><th>Gold Commerce Bonus</th></td><td>1%</td></tr>';} ?>
			<tr><th>Projected Income</th><td><?php echo number_format($income); ?></td><th>Total Expense</th><td><?php echo number_format($expenses); ?></td></tr>
			<tr><th colspan="2">Projected Total</th><td colspan="2"><?php echo number_format($projected_total); ?></td></tr>
			</table>
			<br><br>
			<form id='changetax' action='changetax.php' method='post'>
			<label for='taxrate'>Tax Rate:</label>
			<select name="rates">
			<option value="five" <?php echo $five; ?>>5%</option>
			<option value="ten" <?php echo $ten; ?>>10%</option>
			<option value="twenty" <?php echo $twenty; ?>>20%</option>
			<option value="quarter" <?php echo $quarter; ?>>25%</option>
			</select>
			<input type='submit' value='Change Rate' />
		</div>
	</div>
  </div>
  <!-- Begin Content -->
 </div>
<!-- End Wrapper -->
</body>
</html>
