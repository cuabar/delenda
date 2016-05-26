<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

if($_POST['volume'] && $_POST['id'])
{
	$amount = $_POST['volume'];
	$id = $_POST['id'];
	
	$amount = stripslashes($amount);
	$id = stripslashes($id);
	$amount = mysql_real_escape_string($amount);
	$id = mysql_real_escape_string($id);
	
	$checkamount = mysql_query("SELECT amount, colonyuser, colonyname, cost, factionname, resource FROM markets WHERE entryid='$id'") or die(mysql_error());
	$amountcheck = mysql_fetch_array($checkamount);
	
	if($amount > 0 && $amount <= $amountcheck['amount'])
	{
		$checkbalance = mysql_query("SELECT factionbank FROM $factiondb WHERE factionname='$chosencolony' AND factionuser='$username'");
		$balance = mysql_result($checkbalance, 0);
		
		$payment = $amount * $amountcheck['cost'];
		if($payment > $balance)
		{
			header('Location: markets.php?msg=3');
		}
		else
		{
			$targetdb = $amountcheck['factionname'].'factions';
			$resource = $amountcheck['resource'];
			echo $targetdb.'<br>'.$amountcheck['colonyname'].'<br>'.$amountcheck['colonyuser'];
			$sendmoney = mysql_query("UPDATE $targetdb SET factionbank = factionbank+$payment WHERE factionname = '$amountcheck[2]' AND factionuser = '$amountcheck[1]'");
			$takemoney = mysql_query("UPDATE $factiondb SET factionbank = factionbank-$payment WHERE factionname = '$chosencolony' AND factionuser = '$username'");
			if($amount == $amountcheck[0])
			{
				$removeentry = mysql_query("DELETE FROM markets WHERE entryid='$id'");
			}
			else
			{
				$removeresource = mysql_query("UPDATE markets SET amount = amount-$amount WHERE entryid='$id'");
			}
		
			$giveresources = mysql_query("UPDATE resourcebanks SET $resource=$resource+$amount WHERE factionuser='$username' AND factionname='$chosencolony'") or die(mysql_error());
		
			header('Location: markets.php?msg=0');
		}
	}	
	elseif($amount <= 0 )
	{
		header('Location: markets.php?msg=1');
	}
	elseif(!is_numeric($amount))
	{
		header('Location: markets.php?msg=2');
	}
}
else
{
	echo 'Something is wrong <br>'.$_POST['volume'].'<br>'.$_POST['id'].'<br>';
}
?>