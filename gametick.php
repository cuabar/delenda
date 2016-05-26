<?php 
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $starttime = $mtime; 
;?> 
<?php
set_time_limit(0);
ignore_user_abort(true);

include 'includes/connect.php';

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'delendaEstMAIN01cycle90PASS')
{
//Authority Update
//Taxes
$authtaxesquery = mysql_query("SELECT factionbank,factionpop,happiness,commerce,factionname,factionuser,tax,resource FROM authorityfactions");
$authaffluencequery = mysql_query("SELECT affluence FROM factionstats WHERE factionname='authority'");
$authaffluence = mysql_result($authaffluencequery, 0);

while($authtaxesrow = mysql_fetch_array($authtaxesquery))
{
	$factionname = $authtaxesrow['factionname'];
	$factionuser = $authtaxesrow['factionuser'];
	
	//Check Resources
	$authrescheck = mysql_query("SELECT gold, silver, illucite FROM resourcebanks WHERE factionuser='$factionuser' AND factionname='$factionname'");
	$authresrow = mysql_fetch_row($authrescheck);
	$authgoldamount = $authresrow[0];
	$authsilveramount = $authresrow[1];
	$authilluciteamount = $authresrow[2];
	
	$authbalance = $authtaxesrow['factionbank'];
	$authpop = $authtaxesrow['factionpop'];
	$authpop = $authpop * 2;
	$authhappy = $authtaxesrow['happiness'];
	$authtax = $authtaxesrow['tax'];
	$authtaxfactor = $authtax/100;
	$authhappypercent = $authhappy/100;
	$authtaxesvalue = $authpop*$authhappypercent;
	$authtaxesvalue = $authtaxesvalue*$authtaxfactor;
	if($authsilveramount > 0)
	{
		$authtaxesvalue = $authtaxesvalue*1.01;
	}	
	
	$authcommerce = $authtaxesrow['commerce'];
	$authcommercevalue = $authcommerce*7500;
	if($authgoldamount> 0)
	{
		$authcommercevalue = $authcommercevalue*1.01;
	}
	
	$authincome = $authtaxesvalue + $authcommercevalue;
	$authincome = $authincome*$authaffluence;
	if($authilluciteamount > 0)
	{
		$authincome = $authincome*1.05;
	}
	//Expenses
	$authexpensesquery=mysql_query("SELECT trainingground,barracks,factionpop,techcomplex,hospitals,law,educational,factionbank,warfactories FROM authorityfactions WHERE factionuser='$factionuser' AND factionname='$factionname'");
	$authexpensesrow = mysql_fetch_array($authexpensesquery);
	//Training Ground Expenses
	$authtgexpense = $authexpensesrow['trainingground'];
	$authtgexpense = $authtgexpense*4000;
	$authbarexpense = $authexpensesrow['barracks'];
	$authbarexpense = $authbarexpense*1000;
	//Administration Costs for Population
	$authpopexpense = $authexpensesrow['factionpop'];
	$authpopexpensefactor = $authpopexpense/10000000;
	$authpopexpensefactor = $authpopexpensefactor*$authpopexpense;
	$authpopexpense = $authpopexpense*0.02;
	$authpopexpense = $authpopexpensefactor+$authpopexpense;
	//Research Costs
	$authtccosts = $authexpensesrow['techcomplex'];
	$authtccosts = $authtccosts * 4000;
	//War Factories
	$warfactoryexpense = $authexpensesrow['warfactories'];
	$warfactoryexpense = $warfactoryexpense*800;
	
	$authexpenses = $authtgexpense+$authpopexpense+$authtccosts+$authbarexpense+$warfactoryexpense;
	
	$authincome = $authincome-$authexpenses;
	
	$authnewbalance = $authbalance + $authincome;
	$authupdate = mysql_query("UPDATE authorityfactions SET factionbank=$authnewbalance WHERE factionname='$factionname' AND factionuser='$factionuser'") or die(mysql_error());
}

//Troops
$authtroopquery = mysql_query("SELECT troops,barracks,factionname,factionuser,trainingground,troopmax,factionbank,happiness FROM authorityfactions");
$authtrooppenquery = mysql_query("SELECT troopbuild FROM factionstats WHERE factionname = 'authority'") or die(mysql_error());
$authtrooppenrow=mysql_fetch_row($authtrooppenquery);
$trooppen = $authtrooppenrow[0];

while($authtrooprow = mysql_fetch_array($authtroopquery))
{
	$factiontroopname = $authtrooprow['factionname'];
	$factiontroopuser = $authtrooprow['factionuser'];
	
	$authtrooprescheck = mysql_query("SELECT tungsten FROM resourcebanks WHERE factionuser='$factiontroopuser' AND factionname = '$factiontroopname'") or die(mysql_error());
	$authtroopresrow = mysql_fetch_array($authtrooprescheck);
	$authtungstenamount = $authtroopresrow['tungsten'];
	
	$authhappinesstroop = 1.00;
	
	if($authtrooprow['happiness'] < 50)
	{
		$authhappinesstroop = $authtrooprow['happiness'];
		$authhappinesstroop = $authhappinesstroop*0.01;
	}
	
	$authtroopbank = 1.00;
	if($authtrooprow['factionbank'] < 0)
	{
		$authtroopbank = 0;
	}
	
	$authtroopbalance = $authtrooprow['troops'];
	$authbarracksamount = $authtrooprow['barracks'];
	$authtroopmax = $authtrooprow['troopmax'];
	$authtrainingamount = $authtrooprow['trainingground'];
	
	$authtroopincrease = $authtrainingamount*500;
	$authtroopincrease = $authtroopincrease*$trooppen;
	
	if($authtungstenamount > 0)
	{
		$authtroopincrease = $authtroopincrease*1.01;
	}
	$authtroopincrease = $authtroopincrease*$authhappinesstroop;
	$authtroopincrease = $authtroopincrease*$authtroopbank;
	$authnewtroop = $authtroopbalance + $authtroopincrease;
	if($authnewtroop > $authtroopmax)
	{
		$authnewtroop = $authtroopmax;
	}
	
	$authtroopupdate = mysql_query("UPDATE authorityfactions SET troops=$authnewtroop WHERE factionname='$factiontroopname' AND factionuser='$factiontroopuser'")or die(mysql_error());
}

//Logistics
$getauthcommand = mysql_query("SELECT commandcentre,factionname,factionuser,factionbank,factionpop,happiness FROM authorityfactions");
while($authlogarray = mysql_fetch_array($getauthcommand))
{
	$authcommand = $authlogarray['commandcentre'];
	$authloggain = $authcommand * 10;
	if($authlogarray['happiness'] < 50)
	{
		$authhappinesslog = $authlogarray['happiness'];
		$authhappinesslog = $authhappinesslog*0.01;
		$authloggain = $authloggain*$authhappinesslog;
		$authloggain = round($authloggain);
	}
	$authlogpop = $authcommand*1000;
	if($authlogarray['factionpop'] < $authlogpop)
	{
		$authactivelog = $authlogarray['factionpop'] / 1000;
		$authactivelog = floor($authactivelog);
		$authloggain = $authactivelog*10;
	}
	
	if($authlogarray['factionbank'] < 0)
	{
		$authloggain = 0;
	}
	$authlogname = $authlogarray['factionuser'];
	$authlogfaction = $authlogarray['factionname'];
	$updateauthlogistics = mysql_query("UPDATE authorityfactions SET logistics=$authloggain WHERE factionuser='$authlogname' AND factionname='$authlogfaction'") or die(mysql_error());
}

//Solidarity Update
//Taxes
$soltaxesquery = mysql_query("SELECT factionbank,factionpop,happiness,commerce,factionname,factionuser,tax FROM solidarityfactions");
$solaffluencequery = mysql_query("SELECT affluence FROM factionstats WHERE factionname='solidarity'");
$solaffluence = mysql_result($solaffluencequery, 0);

while($soltaxesrow = mysql_fetch_array($soltaxesquery))
{
	$solfactionname = $soltaxesrow['factionname'];
	$solfactionuser = $soltaxesrow['factionuser'];
	
	//Check Resources
	$solrescheck = mysql_query("SELECT gold, silver, illucite FROM resourcebanks WHERE factionuser='$solfactionuser' AND factionname='$solfactionname'");
	$solresrow = mysql_fetch_row($solrescheck);
	$solgoldamount = $solresrow[0];
	$solsilveramount = $solresrow[1];
	$solilluciteamount = $solresrow[2];
	
	$solbalance = $soltaxesrow['factionbank'];
	$solpop = $soltaxesrow['factionpop'];
	$solpop = $solpop*2;
	$solhappy = $soltaxesrow['happiness'];
	$soltax = $soltaxesrow['tax'];
	$soltaxfactor = $soltax/100;
	$solhappypercent = $solhappy/100;
	$soltaxesvalue = $solpop*$solhappypercent;
	$soltaxesvalue = $soltaxesvalue*$soltaxfactor;
	if($solsilveramount > 0)
	{
		$soltaxesvalue = $soltaxesvalue*1.01;
	}
	$solcommerce = $soltaxesrow['commerce'];
	$solcommercevalue = $solcommerce*7500;
	if($solgoldamount> 0)
	{
		$solcommercevalue = $solcommercevalue*1.01;
	}
	
	$solincome = $soltaxesvalue + $solcommercevalue;
	$solincome = $solincome*$solaffluence;
	if($solilluciteamount > 0)
	{
		$solincome = $solincome*1.05;
	}
	//Expenses
	$solexpensesquery=mysql_query("SELECT trainingground,barracks,factionpop,techcomplex,hospitals,law,educational,factionbank,warfactories FROM solidarityfactions WHERE factionuser='$solfactionuser' AND factionname='$solfactionname'");
	$solexpensesrow = mysql_fetch_array($solexpensesquery);
	//Training Ground Expenses
	$soltgexpense = $solexpensesrow['trainingground'];
	$soltgexpense = $soltgexpense*4000;
	$solbarexpense = $solexpensesrow['barracks'];
	$solbarexpense = $solbarexpense*1000;
	//Administration Costs for Population
	$solpopexpense = $solexpensesrow['factionpop'];
	$solpopexpensefactor = $solpopexpense/10000000;
	$solpopexpensefactor = $solpopexpensefactor*$solpopexpense;
	$solpopexpense = $solpopexpense*0.02;
	$solpopexpense = $solpopexpensefactor+$solpopexpense;
	//Research Costs
	$soltccosts = $solexpensesrow['techcomplex'];
	$soltccosts = $soltccosts * 4000;
	//War Factories
	$solwarfactoryexpense = $solexpensesrow['warfactories'];
	$solwarfactoryexpense = $solwarfactoryexpense*800;
	
	$solexpenses = $soltgexpense+$solpopexpense+$soltccosts+$solbarexpense+$solwarfactoryexpense;
	
	$solincome = $solincome-$solexpenses;
	
	$solnewbalance = $solbalance + $solincome;
	
	$solupdate = mysql_query("UPDATE solidarityfactions SET factionbank=$solnewbalance WHERE factionname='$solfactionname' AND factionuser='$solfactionuser'");
}

//Troops
$soltroopquery = mysql_query("SELECT troops,barracks,factionname,factionuser,trainingground,troopmax,factionbank,happiness FROM solidarityfactions");
$soltroopbonusquery = mysql_query("SELECT troopbuild FROM factionstats WHERE factionname = 'solidarity'");
$soltroopbonusrow=mysql_fetch_row($soltroopbonusquery);
$troopbonus = $soltroopbonusrow[0];

while($soltrooprow = mysql_fetch_array($soltroopquery))
{
	$solfactiontroopname = $soltrooprow['factionname'];
	$solfactiontroopuser = $soltrooprow['factionuser'];
	
	$soltrooprescheck=mysql_query("SELECT tungsten FROM resourcebanks WHERE factionuser='$solfactiontroopuser' AND factionname = '$solfactiontroopname'");
	$soltroopresrow=mysql_fetch_row($soltrooprescheck);
	$soltungstenamount = $soltroopresrow[0];
	$solhappinesstroop = 1.00;
	
	if($soltrooprow['happiness'] < 50)
	{
		$solhappinesstroop = $soltrooprow['happiness'];
		$solhappinesstroop = $solhappinesstroop*0.01;
	}
	
	$soltroopbank = 1.00;
	if($soltrooprow['factionbank'] < 0)
	{
		$soltroopbank = 0;
	}
	$soltroopbalance = $soltrooprow['troops'];
	$solbarracksamount = $soltrooprow['barracks'];
	$soltroopmax = $soltrooprow['troopmax'];
	$soltrainingamount = $soltrooprow['trainingground'];
	
	$soltroopincrease = $soltrainingamount*500;
	$soltroopincrease = $soltroopincrease*$troopbonus;
	if($soltungstenamount > 0)
	{
		$soltroopincrease = $soltroopincrease*1.01;
	}
	$soltroopincrease = $soltroopincrease*$solhappinesstroop;
	$soltroopincrease = $soltroopincrease*$soltroopbank;
	$solnewtroop = $soltroopbalance + $soltroopincrease;
	if($solnewtroop > $soltroopmax)
	{
		$solnewtroop = $soltroopmax;
	}
	
	$soltroopupdate = mysql_query("UPDATE solidarityfactions SET troops=$solnewtroop WHERE factionname='$solfactiontroopname' AND factionuser='$solfactiontroopuser'");
}

//Logistics
$getsolcommand = mysql_query("SELECT commandcentre,factionname,factionuser,factionbank,factionpop,happiness FROM solidarityfactions");
while($sollogarray = mysql_fetch_array($getsolcommand))
{
	$solcommand = $sollogarray['commandcentre'];
	$solloggain = $solcommand * 10;
	if($sollogarray['happiness'] < 50)
	{
		$solhappinesslog = $sollogarray['happiness'];
		$solhappinesslog = $solhappinesslog*0.01;
		$solloggain = $solloggain*$solhappinesslog;
		$solloggain = round($solloggain);
	}
	$sollogpop = $solcommand*1000;
	if($sollogarray['factionpop'] < $sollogpop)
	{
		$solactivelog = $sollogarray['factionpop'] / 1000;
		$solactivelog = floor($solactivelog);
		$solloggain = $solactivelog*10;
	}
	
	if($sollogarray['factionbank'] < 0)
	{
		$solloggain = 0;
	}
	$sollogname = $sollogarray['factionuser'];
	$sollogfaction = $sollogarray['factionname'];
	$updatesollogistics = mysql_query("UPDATE solidarityfactions SET logistics=$solloggain WHERE factionuser='$sollogname' AND factionname='$sollogfaction'");
}

//Mercantile Union Update
//Taxes
$merctaxesquery = mysql_query("SELECT factionbank,factionpop,happiness,commerce,factionname,factionuser,tax FROM mercantilefactions");
$mercaffluencequery = mysql_query("SELECT affluence FROM factionstats WHERE factionname='mercantile'");
$mercaffluence = mysql_result($mercaffluencequery, 0);

while($merctaxesrow = mysql_fetch_array($merctaxesquery))
{
	$mercfactionname = $merctaxesrow['factionname'];
	$mercfactionuser = $merctaxesrow['factionuser'];
	
	//Check Resources
	$mercrescheck = mysql_query("SELECT gold, silver, illucite FROM resourcebanks WHERE factionuser='$mercfactionuser' AND factionname='$mercfactionname'");
	$mercresrow = mysql_fetch_row($mercrescheck);
	$mercgoldamount = $mercresrow[0];
	$mercsilveramount = $mercresrow[1];
	$mercilluciteamount = $mercresrow[2];
	
	$mercbalance = $merctaxesrow['factionbank'];
	$mercpop = $merctaxesrow['factionpop'];
	$mercpop = $mercpop*2;
	$merchappy = $merctaxesrow['happiness'];
	$merctax=$merctaxesrow['tax'];
	$merctaxfactor=$merctax/100;
	$merchappypercent = $merchappy/100;
	$merctaxesvalue = $mercpop*$merchappypercent;
	$merctaxesvalue = $merctaxesvalue*$merctaxfactor;
	if($mercsilveramount > 0)
	{
		$merctaxesvalue = $merctaxesvalue*1.01;
	}
	$merccommerce = $merctaxesrow['commerce'];
	$merccommercevalue = $merccommerce*7500;
	if($solgoldamount> 0)
	{
		$solcommercevalue = $solcommercevalue*1.01;
	}
	
	$mercincome = $merctaxesvalue + $merccommercevalue;
	$mercincome = $mercincome*1.05;
	$mercincome = $mercincome*$mercaffluence;
	if($mercilluciteamount > 0)
	{
		$mercincome = $mercincome*1.05;
	}
	//Expenses
	$mercexpensesquery=mysql_query("SELECT trainingground,barracks,factionpop,techcomplex,hospitals,law,educational,factionbank,warfactories FROM mercantilefactions WHERE factionuser='$mercfactionuser' AND factionname='$mercfactionname'");
	$mercexpensesrow = mysql_fetch_array($mercexpensesquery);
	//Training Ground Expenses
	$merctgexpense = $mercexpensesrow['trainingground'];
	$merctgexpense = $merctgexpense*4000;
	$mercbarexpense = $mercexpensesrow['barracks'];
	$mercbarexpense = $mercbarexpense*1000;
	//Administration Costs for Population
	$mercpopexpense = $mercexpensesrow['factionpop'];
	$mercpopexpensefactor = $mercpopexpense/10000000;
	$mercpopexpensefactor = $mercpopexpensefactor*$mercpopexpense;
	$mercpopexpense = $mercpopexpense*0.02;
	$mercpopexpense = $mercpopexpensefactor+$mercpopexpense;
	//Research Costs
	$merctccosts = $mercexpensesrow['techcomplex'];
	$merctccosts = $merctccosts * 4000;
	//War Factories
	$mercwarfactoryexpense = $mercexpensesrow['warfactories'];
	$mercwarfactoryexpense = $mercwarfactoryexpense*800;
	
	$mercexpenses = $merctgexpense+$mercpopexpense+$merctccosts+$mercbarexpense+$mercwarfactoryexpense;
	
	$mercincome = $mercincome-$mercexpenses;
	
	$mercnewbalance = $mercbalance + $mercincome;

	$mercupdate = mysql_query("UPDATE mercantilefactions SET factionbank=$mercnewbalance WHERE factionname='$mercfactionname' AND factionuser='$mercfactionuser'");
}

//Troops
$merctroopquery = mysql_query("SELECT troops,barracks,factionname,factionuser,trainingground,troopmax,factionbank,happiness FROM mercantilefactions") or die(mysql_error());

while($merctrooprow = mysql_fetch_array($merctroopquery))
{
	$mercfactiontroopname = $merctrooprow['factionname'];
	$mercfactiontroopuser = $merctrooprow['factionuser'];
	
	$merctrooprescheck=mysql_query("SELECT tungsten FROM resourcebanks WHERE factionuser='$mercfactiontroopuser' AND factionname = '$mercfactiontroopname'");
	$merctroopresrow=mysql_fetch_row($merctrooprescheck);
	$merctungstenamount = $merctroopresrow[0];
	$merchappinesstroop = 1.00;
	
	if($merctrooprow['happiness'] < 50)
	{
		$merchappinesstroop = $merctrooprow['happiness'];
		$merchappinesstroop = $merchappinesstroop*0.01;
	}
	
	$merctroopbank = 1.00;
	if($merctrooprow['factionbank'] < 0)
	{
		$merctroopbank = 0;
	}
	$merctroopbalance = $merctrooprow['troops'];
	$mercbarracksamount = $merctrooprow['barracks'];
	$merctrainingamount = $merctrooprow['trainingground'];
	$merctroopmax = $merctrooprow['troopmax'];
	
	$merctroopincrease = $merctrainingamount*500;
	if($merctungstenamount > 0)
	{
		$merctroopincrease = $merctroopincrease*1.01;
	}
	$merctroopincrease = $merctroopincrease*$merchappinesstroop;
	$merctroopincrease = $merctroopincrease*$merctroopbank;
	
	$mercnewtroop = $merctroopbalance + $merctroopincrease;
	if($mercnewtroop > $merctroopmax)
	{
		$mercnewtroop = $merctroopmax;
	}
	
	$merctroopupdate = mysql_query("UPDATE mercantilefactions SET troops=$mercnewtroop WHERE factionname='$mercfactiontroopname' AND factionuser='$mercfactiontroopuser'");
}

//Logistics
$getmerccommand = mysql_query("SELECT commandcentre,factionname,factionuser,factionbank,factionpop,happiness FROM mercantilefactions");
while($merclogarray = mysql_fetch_array($getmerccommand))
{
	$merccommand = $merclogarray['commandcentre'];
	$mercloggain = $merccommand * 10;
	if($merclogarray['happiness'] < 50)
	{
		$merchappinesslog = $merclogarray['happiness'];
		$merchappinesslog = $merchappinesslog*0.01;
		$mercloggain = $mercloggain*$merchappinesslog;
		$mercloggain = round($mercloggain);
	}
	$merclogpop = $merccommand*1000;
	if($merclogarray['factionpop'] < $merclogpop)
	{
		$mercactivelog = $merclogarray['factionpop'] / 1000;
		$mercactivelog = floor($mercactivelog);
		$mercloggain = $mercactivelog*10;
	}
	
	if($merclogarray['factionbank'] < 0)
	{
		$mercloggain = 0;
	}
	$merclogname = $merclogarray['factionuser'];
	$merclogfaction = $merclogarray['factionname'];
	$updatemerclogistics = mysql_query("UPDATE mercantilefactions SET logistics=$mercloggain WHERE factionuser='$merclogname' AND factionname='$merclogfaction'");
}


echo 'Tick has run. Or should have anyway.<br>
<b>It worked.</b>';
}
else
{
	echo 'Password not given. Tick not run.';
}
?>
<?php 
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   echo "This page was created in ".$totaltime." seconds"; 
;?>