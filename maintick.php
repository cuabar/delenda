<?php
include 'includes/connect.php';

//Authority Update
//Taxes
$authtaxesquery = mysql_query("SELECT factionname FROM authorityfactions");
$authtaxesrow

while($authtaxesrow = mysql_fetch_array($authtaxesquery))
{
	$factionname = $authtaxesrow['factionname'];
	$factionuser = $authtaxesrow['factionuser'];
	
	$authbalance = $authtaxesrow['factionbank'];
	$authpop = $authtaxesrow['factionpop'];
	$authhappy = $authtaxesrow['happiness'];
	$authhappypercent = $authhappy/100;
	$authtaxesvalue = $authpop*$authhappypercent;
	$authtaxesvalue = $authtaxesvalue*0.1;
	$authcommerce = $authtaxesrow['commerce'];
	$authcommercevalue = $authcommerce*2500;
	
	$authincome = $authtaxesvalue + $authcommercevalue;
	$authnewbalance = $authbalance + $authincome;
	
	$authupdate = mysql_query("UPDATE authorityfactions SET factionbank=$authnewbalance WHERE factionname=$factionname AND factionuser=$factionuser");
}
//Research Points
$authresearchquery = mysql_query("SELECT techcomplex,researchpoints,factionname,factionuser FROM authorityfactions");
$authresearchbonusquery = mysql_query("SELECT research FROM factionstates WHERE factionname = 'authority'");
$authresearchbonusrow=mysql_fetch_row($authresearchbonusquery);
$researchbonus = $authresearchbonusrow[0];

while($authresearchrow = mysql_fetch_array($authresearchquery)
{
	$factionrpname = $authresearchrow['factionname'];
	$factionrpuser = $authresearchrow['factionuser'];
	
	$authrpbalance = $authresearchrow['researchpoints'];
	$authtcamount = $authresearchrow['techcomplex'];
	
	$authrpincrease = $authtcamount*100;
	$authrpincrease = $authrpincrease*$researchbonus;
	$authnewrp = $authrpbalance + $authrpincrease;
	
	$authresearchupdate = mysql_query("UPDATE authorityfactions SET researchpoints=$authnewrp WHERE factionname=$factionrpname AND factionuser=$factionrpuser");
}
//Troops
$authtroopquery = mysql_query("SELECT troops,barracks,factionname,factionuser FROM authorityfactions");
$authtrooppenquery = mysql_query("SELECT troopbuild FROM factionstates WHERE factionname = 'authority'");
$authtrooppenrow=mysql_fetch_row($authtrooppenquery);
$trooppen = $authtrooppenrow[0];

while($authtrooprow = mysql_fetch_array($authtroopquery)
{
	$factiontroopname = $authtrooprow['factionname'];
	$factiontroopuser = $authtrooprow['factionuser'];
	
	$authtroopbalance = $authtrooprow['troops'];
	$authbarracksamount = $authtrooprow['barracks'];
	
	$authtroopincrease = $authbarracksamount*1000;
	$authtroopincrease = $authtroopincrease*$trooppen;
	$authnewtroop = $authtroopbalance + $authtroopincrease;
	
	$authtroopupdate = mysql_query("UPDATE authorityfactions SET troops=$authnewtroop WHERE factionname=$factiontroopname AND factionuser=$factiontroopuser");
}

//Solidarity Update
//Taxes
$soltaxesquery = mysql_query("SELECT factionbank,factionpop,happiness,commerce,factionname,factionuser FROM solidarityfactions");

while($soltaxesrow = mysql_fetch_array($soltaxesquery)
{
	$solfactionname = $soltaxesrow['factionname'];
	$solfactionuser = $soltaxesrow['factionuser'];
	
	$solbalance = $soltaxesrow['factionbank'];
	$solpop = $soltaxesrow['factionpop'];
	$solhappy = $soltaxesrow['happiness'];
	$solhappypercent = $solhappy/100;
	$soltaxesvalue = $solpop*$solhappypercent;
	$soltaxesvalue = $soltaxesvalue*0.1;
	$solcommerce = $soltaxesrow['commerce'];
	$solcommercevalue = $solcommerce*2500;
	
	$solincome = $soltaxesvalue + $solcommercevalue;
	$solnewbalance = $solbalance + $solincome;
	
	$solupdate = mysql_query("UPDATE solidarityfactions SET factionbank=$solnewbalance WHERE factionname=$solfactionname AND factionuser=$solfactionuser");
}
//Research Points
$solresearchquery = mysql_query("SELECT techcomplex,researchpoints,factionname,factionuser FROM solidarityfactions");
$solresearchpenquery = mysql_query("SELECT research FROM factionstates WHERE factionname = 'solidarity'");
$solresearchpenrow=mysql_fetch_row($solresearchpenquery);
$researchpen = $solresearchbonusrow[0];

while($solresearchrow = mysql_fetch_array($solresearchquery)
{
	$solfactionrpname = $solresearchrow['factionname'];
	$solfactionrpuser = $solresearchrow['factionuser'];
	
	$solrpbalance = $solresearchrow['researchpoints'];
	$soltcamount = $solresearchrow['techcomplex'];
	
	$solrpincrease = $soltcamount*100;
	$solrpincrease = $solrpincrease*$researchpen;
	$solnewrp = $solrpbalance + $solrpincrease;
	
	$solresearchupdate = mysql_query("UPDATE solidarityfactions SET researchpoints=$solnewrp WHERE factionname=$solfactionrpname AND factionuser=$solfactionrpuser");
}
//Troops
$soltroopquery = mysql_query("SELECT troops,barracks,factionname,factionuser FROM solidarityfactions");
$soltroopbonusquery = mysql_query("SELECT troopbuild FROM factionstates WHERE factionname = 'solidarity'");
$soltroopbonusrow=mysql_fetch_row($soltroopbonusquery);
$troopbonus = $soltrooppenrow[0];

while($soltrooprow = mysql_fetch_array($soltroopquery)
{
	$solfactiontroopname = $soltrooprow['factionname'];
	$solfactiontroopuser = $soltrooprow['factionuser'];
	
	$soltroopbalance = $soltrooprow['troops'];
	$solbarracksamount = $soltrooprow['barracks'];
	
	$soltroopincrease = $solbarracksamount*1000;
	$soltroopincrease = $soltroopincrease*$troopbonus;
	$solnewtroop = $soltroopbalance + $soltroopincrease;
	
	$soltroopupdate = mysql_query("UPDATE solidarityfactions SET troops=$solnewtroop WHERE factionname=$solfactiontroopname AND factionuser=$solfactiontroopuser");
}

//Mercantile Union Update
//Taxes
$merctaxesquery = mysql_query("SELECT factionbank,factionpop,happiness,commerce,factionname,factionuser FROM mercantilefactions");

while($merctaxesrow = mysql_fetch_array($merctaxesquery)
{
	$mercfactionname = $merctaxesrow['factionname'];
	$mercfactionuser = $merctaxesrow['factionuser'];
	
	$mercbalance = $merctaxesrow['factionbank'];
	$mercpop = $merctaxesrow['factionpop'];
	$merchappy = $merctaxesrow['happiness'];
	$merchappypercent = $merchappy + 5;
	$merchappypercent = $merchappypercent/100;
	$merctaxesvalue = $mercpop*$merchappypercent;
	$merctaxesvalue = $merctaxesvalue*0.1;
	$merccommerce = $merctaxesrow['commerce'];
	$merccommercevalue = $merccommerce*2500;
	
	$mercincome = $merctaxesvalue + $merccommercevalue;
	$mercnewbalance = $mercbalance + $mercincome;
	
	$mercupdate = mysql_query("UPDATE mercantilefactions SET factionbank=$mercnewbalance WHERE factionname=$mercfactionname AND factionuser=$mercfactionuser");
}
//Research Points
$mercresearchquery = mysql_query("SELECT techcomplex,researchpoints,factionname,factionuser FROM mercantilefactions");

while($mercresearchrow = mysql_fetch_array($mercresearchquery)
{
	$mercfactionrpname = $mercresearchrow['factionname'];
	$mercfactionrpuser = $mercresearchrow['factionuser'];
	
	$mercrpbalance = $mercresearchrow['researchpoints'];
	$merctcamount = $mercresearchrow['techcomplex'];
	
	$mercrpincrease = $merctcamount*100;
	$mercnewrp = $mercrpbalance + $mercrpincrease;
	
	$mercresearchupdate = mysql_query("UPDATE mercantilefactions SET researchpoints=$mercnewrp WHERE factionname=$mercfactionrpname AND factionuser=$mercfactionrpuser");
}
//Troops
$merctroopquery = mysql_query("SELECT troops,barracks,factionname,factionuser FROM mercantilefactions");

while($merctrooprow = mysql_fetch_array($merctroopquery)
{
	$mercfactiontroopname = $merctrooprow['factionname'];
	$mercfactiontroopuser = $merctrooprow['factionuser'];
	
	$merctroopbalance = $merctrooprow['troops'];
	$mercbarracksamount = $merctrooprow['barracks'];
	
	$merctroopincrease = $mercbarracksamount*1000;
	$mercnewtroop = $merctroopbalance + $merctroopincrease;
	
	$merctroopupdate = mysql_query("UPDATE mercantilefactions SET troops=$mercnewtroop WHERE factionname=$mercfactiontroopname AND factionuser=$mercfactiontroopuser");
}


echo 'Tick has run. Or should have anyway.';?>
<b>It worked.</b>