<?php

set_time_limit(0);
ignore_user_abort(true);

include 'includes/connect.php';

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'delendaEstMAIN01cycle90PASS')
{
	//Authority
	//Research Points
	$authresearchquery = mysql_query("SELECT techcomplex,researchpoints,factionname,factionuser,factionpop,factionbank,happiness FROM authorityfactions");
	$authresearchbonusquery = mysql_query("SELECT research FROM factionstats WHERE factionname = 'authority'");
	$authresearchbonusrow=mysql_fetch_row($authresearchbonusquery);
	$researchbonus = $authresearchbonusrow[0];

	while($authresearchrow = mysql_fetch_array($authresearchquery))
	{
		$authhappinessresearch = 1.00;
		if($authresearchrow['happiness'] < 50)
		{
			$authhappinessresearch = $authresearchrow['happiness'];
			$authhappinessresearch = $authhappinessresearch*0.01;
		}
	
		$authresbank = 1.00;
		if($authresearchrow['factionbank'] < 0)
		{
			$authresbank = 0;
		}
	
		$factionrpname = $authresearchrow['factionname'];
		$factionrpuser = $authresearchrow['factionuser'];
		//Check Resources
		$authrprescheck = mysql_query("SELECT palladium, illucite FROM resourcebanks WHERE factionuser='$factionrpuser' AND factionname='$factionrpname'");
		$authrpresrow = mysql_fetch_row($authrprescheck);
		$authpalladiumamount = $authrpresrow[0];
		$authrpilluciteamount = $authrpresrow[1];

		$authrpbalance = $authresearchrow['researchpoints'];
		$authtcamount = $authresearchrow['techcomplex'];
	
		$authrespop = $authtcamount * 5000;
		if($authrespop > $authresearchrow['factionpop'])
		{
			$authressupport = $authresearchrow['factionpop'] / 5000;
			$authressupport = floor($authressupport);
			$authtcamount = $authressupport;
		}
	
		$authrpincrease = $authtcamount*100;
		$authrpincrease = $authrpincrease*$researchbonus;
		if($authpalladiumamount > 0)
		{
			$authrpincrease = $authrpincrease*1.01;
		}
		if($authrpilluciteamount >0)
		{
			$authrpincrease = $authrpincrease*1.1;
		}
	
		$authrpincrease = $authrpincrease * $authhappinessresearch;
		$authrpincrease = $authrpincrease * $authresbank;
		$authrpincrease = round($authrpincrease);
	
		$authnewrp = $authrpbalance + $authrpincrease;
	
		$authresearchupdate = mysql_query("UPDATE authorityfactions SET researchpoints=$authnewrp WHERE factionname='$factionrpname' AND factionuser='$factionrpuser'");
	}
	
	//Resources
	$authresourcequery=mysql_query("SELECT resource,factionname,factionuser,extractor,factionpop FROM authorityfactions") or die(mysql_error());

	while($authresourcerow=mysql_fetch_array($authresourcequery))
	{
		$factionresname=$authresourcerow['factionname'];
		$factionresuser=$authresourcerow['factionuser'];
		$factionrespop=$authresourcerow['factionpop'];
	
		$authextractoramount=$authresourcerow['extractor'];
		$authresourcetype=$authresourcerow['resource'];
		
		$authresamountquery=mysql_query("SELECT $authresourcetype FROM resourcebanks WHERE factionuser='$factionresuser' AND factionname='$factionresname'") or die(mysql_error());
		$authresamountrow=mysql_fetch_row($authresamountquery);
		$authresourceamount=$authresamountrow[0];
	
		$authresourceincrease = $authextractoramount*100;
		$authresourcenew = $authresourceamount + $authresourceincrease;
	
		$authresourceupdate=mysql_query("UPDATE resourcebanks SET $authresourcetype=$authresourcenew WHERE factionuser='$factionresuser' AND factionname='$factionresname'") or die(mysql_error());
	
		$authresourcededuct=$factionrespop / 10000;
		$authresourcededucts=round($authresourcededuct,0,PHP_ROUND_HALF_UP);
	
		$authresdeductquery=mysql_query("SELECT * FROM resourcebanks WHERE factionuser='$factionresuser' AND factionname='$factionresname'") or die(mysql_error());
		$authresdeductrow=mysql_fetch_array($authresdeductquery);
		$diamonds=$authresdeductrow['diamonds'];
		$oil=$authresdeductrow['oil'];
		$palladium=$authresdeductrow['palladium'];
		$livestock=$authresdeductrow['livestock'];
		$illucite=$authresdeductrow['illucite'];
		$gold=$authresdeductrow['gold'];
		$silver=$authresdeductrow['silver'];
		$coal=$authresdeductrow['coal'];
		$copper=$authresdeductrow['copper'];
		$tungsten=$authresdeductrow['tungsten'];
		
		$diamondnew = $diamonds - $authresourcededucts;
		if($diamondnew < 0)
		{
			$diamondnew=0;
		}
		$oilnew = $oil - $authresourcededucts;
		if($oilnew < 0)
		{
			$oilnew=0;
		}
		$silvernew = $silver - $authresourcededucts;
		if($silvernew <0)
		{
			$silvernew=0;
		}
		$goldnew = $gold - $authresourcededucts;
		if($goldnew <0)
		{
			$goldnew=0;
		}
		$illucitenew = $illucite - $authresourcededucts;
		if($illucitenew <0)
		{
			$illucitenew=0;
		}
		$coalnew = $coal - $authresourcededucts;
		if($coalnew < 0)
		{
			$coalnew=0;
		}
		$coppernew = $copper - $authresourcededucts;
		if($coppernew < 0)
		{
			$coppernew=0;
		}
		$livestocknew = $livestock - $authresourcededucts;
		if($livestocknew < 0)
		{
			$livestocknew=0;
		}
		$tungstennew = $tungsten - $authresourcededucts;
		if($tungstennew < 0)
		{
			$tungstennew = 0;
		}
		$palladiumnew = $palladium - $authresourcededucts;
		if($palladiumnew < 0)
		{
			$palladiumnew = 0;
		}
		
		$authresourceupdate2=mysql_query("UPDATE resourcebanks SET diamonds=$diamondnew,palladium=$palladiumnew,tungsten=$tungstennew,livestock=$livestocknew,copper=$coppernew,coal=$coalnew,gold=$goldnew,oil=$oilnew,illucite=$illucitenew,silver=$silvernew WHERE factionuser='$factionresuser' AND factionname='$factionresname'") or die(mysql_error());		
	}
	
	//Solidarity
	//Research Points
	$solresearchquery = mysql_query("SELECT techcomplex,researchpoints,factionname,factionuser,factionbank,factionpop,happiness FROM solidarityfactions");
	$solresearchpenquery = mysql_query("SELECT research FROM factionstats WHERE factionname = 'solidarity'") or die(mysql_error());
	$solresearchpenrow=mysql_fetch_row($solresearchpenquery);
	$researchpen = $solresearchpenrow[0];

	while($solresearchrow = mysql_fetch_array($solresearchquery))
	{
		$solfactionrpname = $solresearchrow['factionname'];
		$solfactionrpuser = $solresearchrow['factionuser'];
		//Check Resources
		$solrprescheck = mysql_query("SELECT palladium, illucite FROM resourcebanks WHERE factionuser='$solfactionrpuser' AND factionname='$solfactionrpname'");
		$solrpresrow = mysql_fetch_row($solrprescheck);
		$solpalladiumamount = $solrpresrow[0];
		$solrpilluciteamount = $solrpresrow[1];
		
		$solhappinessresearch = 1.00;
		if($solresearchrow['happiness'] < 50)
		{
			$solhappinessresearch = $solresearchrow['happiness'];
			$solhappinessresearch = $solhappinessresearch*0.01;
		}
		
		$solresbank = 1.00;
		if($solresearchrow['factionbank'] < 0)
		{
			$solresbank = 0;
		}
		
		$solrpbalance = $solresearchrow['researchpoints'];
		$soltcamount = $solresearchrow['techcomplex'];
		
		$solrespop = $soltcamount * 5000;
		if($solrespop > $solresearchrow['factionpop'])
		{
			$solressupport = $solresearchrow['factionpop'] / 5000;
			$solressupport = floor($solressupport);
			$soltcamount = $solressupport;
		}
		
		$solrpincrease = $soltcamount*100;
		$solrpincrease = $solrpincrease*$researchpen;
		if($solpalladiumamount > 0)
		{
			$solrpincrease = $solrpincrease*1.01;
		}
		if($solrpilluciteamount >0)
		{
			$solrpincrease = $solrpincrease*1.1;
		}
		
		$solrpincrease = $solrpincrease * $solhappinessresearch;
		$solrpincrease = $solrpincrease * $solresbank;
		$solrpincrease = round($solrpincrease);
		
		$solnewrp = $solrpbalance + $solrpincrease;
		
		$solresearchupdate = mysql_query("UPDATE solidarityfactions SET researchpoints=$solnewrp WHERE factionname='$solfactionrpname' AND factionuser='$solfactionrpuser'");
	}
	//Resources
	$solresourcequery=mysql_query("SELECT resource,factionname,factionuser,extractor,factionpop FROM solidarityfactions") or die(mysql_error());

	while($solresourcerow=mysql_fetch_array($solresourcequery))
	{
		$solfactionresname=$solresourcerow['factionname'];
		$solfactionresuser=$solresourcerow['factionuser'];
		$solfactionrespop=$solresourcerow['factionpop'];
		
		$solextractoramount=$solresourcerow['extractor'];
		$solresourcetype=$solresourcerow['resource'];
		
		$solresamountquery=mysql_query("SELECT $solresourcetype FROM resourcebanks WHERE factionuser='$solfactionresuser' AND factionname='$solfactionresname'") or die(mysql_error());
		$solresamountrow=mysql_fetch_row($solresamountquery);
		$solresourceamount=$solresamountrow[0];
		
		$solresourceincrease = $solextractoramount*100;
		$solresourcenew = $solresourceamount + $solresourceincrease;
		
		$solresourceupdate=mysql_query("UPDATE resourcebanks SET $solresourcetype=$solresourcenew WHERE factionuser='$solfactionresuser' AND factionname='$solfactionresname'") or die(mysql_error());
		
		$solresourcededuct=$solfactionrespop / 10000;
		$solresourcededucts=round($solresourcededuct,0,PHP_ROUND_HALF_UP);
		
		$solresdeductquery=mysql_query("SELECT * FROM resourcebanks WHERE factionuser='$solfactionresuser' AND factionname='$solfactionresname'") or die(mysql_error());
		$solresdeductrow=mysql_fetch_array($solresdeductquery);
			$diamonds=$solresdeductrow['diamonds'];
			$oil=$solresdeductrow['oil'];
			$palladium=$solresdeductrow['palladium'];
			$livestock=$solresdeductrow['livestock'];
			$illucite=$solresdeductrow['illucite'];
			$gold=$solresdeductrow['gold'];
			$silver=$solresdeductrow['silver'];
			$coal=$solresdeductrow['coal'];
			$copper=$solresdeductrow['copper'];
			$tungsten=$solresdeductrow['tungsten'];
			
			$diamondnew = $diamonds - $solresourcededucts;
			if($diamondnew < 0)
			{
				$diamondnew=0;
			}
			$oilnew = $oil - $solresourcededucts;
			if($oilnew < 0)
			{
				$oilnew=0;
			}
			$silvernew = $silver - $solresourcededucts;
			if($silvernew <0)
			{
				$silvernew=0;
			}
			$goldnew = $gold - $solresourcededucts;
			if($goldnew <0)
			{
				$goldnew=0;
			}
			$illucitenew = $illucite - $solresourcededucts;
			if($illucitenew <0)
			{
				$illucitenew=0;
			}
			$coalnew = $coal - $solresourcededucts;
			if($coalnew < 0)
			{
				$coalnew=0;
			}
			$coppernew = $copper - $solresourcededucts;
			if($coppernew < 0)
			{
				$coppernew=0;
			}
			$livestocknew = $livestock - $solresourcededucts;
			if($livestocknew < 0)
			{
				$livestocknew=0;
			}
			$tungstennew = $tungsten - $solresourcededucts;
			if($tungstennew < 0)
			{
				$tungstennew = 0;
			}
			$palladiumnew = $palladium - $solresourcededucts;
			if($palladiumnew < 0)
			{
				$palladiumnew = 0;
			}
			
		$solresourceupdate2=mysql_query("UPDATE resourcebanks SET diamonds=$diamondnew,palladium=$palladiumnew,tungsten=$tungstennew,livestock=$livestocknew,copper=$coppernew,coal=$coalnew,gold=$goldnew,oil=$oilnew,illucite=$illucitenew,silver=$silvernew WHERE factionuser='$solfactionresuser' AND factionname='$solfactionresname'") or die(mysql_error());		
	}
	
	//Mercantile
	//Research Points
	$mercresearchquery = mysql_query("SELECT techcomplex,researchpoints,factionname,factionuser,happiness,factionpop,factionbank FROM mercantilefactions") or die(mysql_error());

	while($mercresearchrow = mysql_fetch_array($mercresearchquery))
	{
		$mercfactionrpname = $mercresearchrow['factionname'];
		$mercfactionrpuser = $mercresearchrow['factionuser'];
		$mercrprescheck = mysql_query("SELECT palladium, illucite FROM resourcebanks WHERE factionuser='$mercfactionrpuser' AND factionname='$mercfactionrpname'");
		$mercrpresrow = mysql_fetch_row($mercrprescheck);
		$mercpalladiumamount = $mercrpresrow[0];
		$mercrpilluciteamount = $mercrpresrow[1];
		
		$merchappinessresearch = 1.00;
		if($mercresearchrow['happiness'] < 50)
		{
			$merchappinessresearch = $mercresearchrow['happiness'];
			$merchappinessresearch = $merchappinessresearch*0.01;
		}
		
		$mercresbank = 1.00;
		if($mercresearchrow['factionbank'] < 0)
		{
			$mercresbank = 0;
		}
		$mercrpbalance = $mercresearchrow['researchpoints'];
		$merctcamount = $mercresearchrow['techcomplex'];
		
		$mercrespop = $merctcamount * 5000;
		if($mercrespop > $mercresearchrow['factionpop'])
		{
			$mercressupport = $mercresearchrow['factionpop'] / 5000;
			$mercressupport = floor($mercressupport);
			$merctcamount = $mercressupport;
		}
		
		$mercrpincrease = $merctcamount*100;
		if($mercpalladiumamount > 0)
		{
			$mercrpincrease = $mercrpincrease*1.01;
		}
		if($mercrpilluciteamount >0)
		{
			$mercrpincrease = $mercrpincrease*1.1;
		}
		$mercrpincrease = $mercrpincrease * $merchappinessresearch;
		$mercrpincrease = $mercrpincrease * $mercresbank;
		$mercrpincrease = round($mercrpincrease);
		
		$mercnewrp = $mercrpbalance + $mercrpincrease;
		
		$mercresearchupdate = mysql_query("UPDATE mercantilefactions SET researchpoints=$mercnewrp WHERE factionname='$mercfactionrpname' AND factionuser='$mercfactionrpuser'");
	}
	//Resources
	$mercresourcequery=mysql_query("SELECT resource,factionname,factionuser,extractor,factionpop,mercresource FROM mercantilefactions") or die(mysql_error());
	$getextractortech = mysql_query("SELECT tier FROM technology WHERE techtype = 'extractorauto' AND faction = 'mercantile'");
	$extractortechrow = mysql_fetch_array($getextractortech);
	$extractortech = $extractortechrow['tier'] - 1;
	$extractortech = $extractortech * 0.02;
	$extractortech = $extractortech + 1;

	while($mercresourcerow=mysql_fetch_array($mercresourcequery))
	{
		$mercfactionresname=$mercresourcerow['factionname'];
		$mercfactionresuser=$mercresourcerow['factionuser'];
		$mercfactionrespop=$mercresourcerow['factionpop'];
		
		$mercextractoramount=$mercresourcerow['extractor'];
		$mercresourcetype=$mercresourcerow['resource'];
		$mercresourcetype2=$mercresourcerow['mercresource'];
		
		$mercresamountquery=mysql_query("SELECT $mercresourcetype,$mercresourcetype2 FROM resourcebanks WHERE factionuser='$mercfactionresuser' AND factionname='$mercfactionresname'") or die(mysql_error());
		$mercresamountrow=mysql_fetch_row($mercresamountquery);
		$mercresourceamount=$mercresamountrow[0];
		$mercresourceamount2=$mercresamountrow[1];
		
		$mercresourceincrease = $mercextractoramount*100;
		$mercresourceincrease = $mercresourceincrease*$extractortech;
		if($mercresourcetype == $mercresourcetype2)
		{
			$mercresourceincrease = $mercresourceincrease*2;
			$mercresourcenew = $mercresourcenew + $mercresourceincrease;
		}
		else
		{
			$mercresourcenew = $mercresourceamount + $mercresourceincrease;
			$mercresource2new = $mercresourceamount2 + $mercresourceincrease;
		}
		
		if($mercresourcetype == $mercresourcetype2)
		{
			$mercresourceupdate=mysql_query("UPDATE resourcebanks SET $mercresourcetype=$mercresourcenew WHERE factionuser='$mercfactionresuser' AND factionname='$mercfactionresname'") or die(mysql_error());
		}
		else
		{
			$mercresourceupdate=mysql_query("UPDATE resourcebanks SET $mercresourcetype=$mercresourcenew,$mercresourcetype2=$mercresource2new WHERE factionuser='$mercfactionresuser' AND factionname='$mercfactionresname'") or die(mysql_error());
		}
		
		$mercresourcededuct=$mercfactionrespop / 10000;
		$mercresourcededucts=round($mercresourcededuct,0,PHP_ROUND_HALF_UP);
		
		$mercresdeductquery=mysql_query("SELECT * FROM resourcebanks WHERE factionuser='$mercfactionresuser' AND factionname='$mercfactionresname'") or die(mysql_error());
		$mercresdeductrow=mysql_fetch_array($mercresdeductquery);
			$diamonds=$mercresdeductrow['diamonds'];
			$oil=$mercresdeductrow['oil'];
			$palladium=$mercresdeductrow['palladium'];
			$livestock=$mercresdeductrow['livestock'];
			$illucite=$mercresdeductrow['illucite'];
			$gold=$mercresdeductrow['gold'];
			$silver=$mercresdeductrow['silver'];
			$coal=$mercresdeductrow['coal'];
			$copper=$mercresdeductrow['copper'];
			$tungsten=$mercresdeductrow['tungsten'];
			
			$diamondnew = $diamonds - $mercresourcededucts;
			if($diamondnew < 0)
			{
				$diamondnew=0;
			}
			$oilnew = $oil - $mercresourcededucts;
			if($oilnew < 0)
			{
				$oilnew=0;
			}
			$silvernew = $silver - $mercresourcededucts;
			if($silvernew <0)
			{
				$silvernew=0;
			}
			$goldnew = $gold - $mercresourcededucts;
			if($goldnew <0)
			{
				$goldnew=0;
			}
			$illucitenew = $illucite - $mercresourcededucts;
			if($illucitenew <0)
			{
				$illucitenew=0;
			}
			$coalnew = $coal - $mercresourcededucts;
			if($coalnew < 0)
			{
				$coalnew=0;
			}
			$coppernew = $copper - $mercresourcededucts;
			if($coppernew < 0)
			{
				$coppernew=0;
			}
			$livestocknew = $livestock - $mercresourcededucts;
			if($livestocknew < 0)
			{
				$livestocknew=0;
			}
			$tungstennew = $tungsten - $mercresourcededucts;
			if($tungstennew < 0)
			{
				$tungstennew = 0;
			}
			$palladiumnew = $palladium - $mercresourcededucts;
			if($palladiumnew < 0)
			{
				$palladiumnew = 0;
			}
			
		$mercresourceupdate2=mysql_query("UPDATE resourcebanks SET diamonds=$diamondnew,palladium=$palladiumnew,tungsten=$tungstennew,livestock=$livestocknew,copper=$coppernew,coal=$coalnew,gold=$goldnew,oil=$oilnew,illucite=$illucitenew,silver=$silvernew WHERE factionuser='$mercfactionresuser' AND factionname='$mercfactionresname'") or die(mysql_error());		
	}
}
?>