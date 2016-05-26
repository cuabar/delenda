<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

if($_POST['volume'] && $_POST['resource'] && $_POST['cost'])
{
	$amount = $_POST['volume'];
	$resource = $_POST['resource'];
	$cost = $_POST['cost'];
	
	$amount = stripslashes($amount);
	$resource = stripslashes($resource);
	$cost = stripslashes($cost);
	$amount = mysql_real_escape_string($amount);
	$resource = mysql_real_escape_string($resource);
	$cost = mysql_real_escape_string($cost);
	
	$checkamount = mysql_query("SELECT $resource FROM resourcebanks WHERE factionuser='$username' AND factionname = '$chosencolony'") or die(mysql_error());
	$amountcheck = mysql_result($checkamount, 0);
	echo $amountcheck.'<br>'.$amount.'<br>'.$resource.'<br>'.$cost;
	
	if($amount > 0 && $amount <= $amountcheck)
	{
		$listoffer = mysql_query("INSERT INTO markets(factionname, colonyname, colonyuser, resource, amount, cost) VALUES ('$allegiance', '$chosencolony', '$username', '$resource', '$amount', '$cost')") or die(mysql_error());
		$removeresource = mysql_query("UPDATE resourcebanks SET $resource=$resource-$amount WHERE factionuser='$username' AND factionname='$chosencolony'");
		header('Location: markets.php?msg=0');
	}
	elseif($amount <= 0 || $cost <= 0)
	{
		header('Location: markets.php?msg=1');
	}
	elseif(!is_numeric($amount) || !is_numeric($cost))
	{
		header('Location: markets.php?msg=2');
	}
}
else
{
	echo 'Something is wrong <br>'.$_POST['volume'].'<br>'.$_POST['resource'].'<br>'.$_POST['cost'];
}
?>