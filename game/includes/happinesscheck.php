<?php

$happystats = mysql_query("SELECT education,crime,healthcare,tax FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'") or die(mysql_error());
$happycheck = mysql_fetch_array($happystats);
$resourceeffect = 0;

$resourcecheck = mysql_query("SELECT diamonds FROM resourcebanks WHERE factionname='$chosencolony' AND factionuser='$username'") or die(mysql_error());
$resourcearray = mysql_fetch_array($resourcecheck);
$resource1 = $resourcearray['diamonds'];
if($resource1 > 0)
{
	$resourceeffect = 1;
}

$edupercent = $happycheck['education'];
$lawpercent = $happycheck['crime'];
$hospercent = $happycheck['healthcare'];
$taxpercent = $happycheck['tax'];

if($taxpercent=='5')
{
	$taxhappyeffect = 0;
}
elseif($taxpercent=='10')
{
	$taxhappyeffect == 10;
}
elseif($taxpercent=='25')
{
	$taxhappyeffect = 30;
}
else
{
	$taxhappyeffect = 25;
}

$eduhappyeffect = 100 - $edupercent;
$hoshappyeffect = 100 - $hospercent;

$happyeffect = $taxhappyeffect + $eduhappyeffect + $hoshappyeffect + $lawpercent;
$happyvalue = 100 - $happyeffect;
if($resourceeffect == 1)
{
	$happyvalue = $happyvalue + 2;
}

if($happyvalue > 100)
{
	$happyvalue = 100;
}
elseif($happyvalue < 10)
{
	$happyvalue = 10;
}

$happyupdate = mysql_query("UPDATE $factiondb SET happiness=$happyvalue WHERE factionuser='$username' AND factionname='$chosencolony'");

?>