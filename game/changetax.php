<?php
include '../includes/connect.php';

session_start();

if(!isset($_SESSION['username']))
{
	header('Location: ../index.php');
}

$chosencolony = $_SESSION['playercolony'];
$username = $_SESSION['username'];
$allegiance = $_SESSION['allegiance'];
$factiondb = $allegiance.'factions';
$factionmenu=$allegiance.'.php';

$taxrate = $_POST['rates'];

if($taxrate=='five')
{
	$settax = 5;
}
elseif($taxrate=='ten')
{
	$settax = 10;
}
elseif($taxrate=='twenty')
{
	$settax = 20;
}
else
{
	$settax=25;
}

$updatetax = mysql_query("UPDATE $factiondb SET tax=$settax WHERE factionuser='$username' AND factionname='$chosencolony'");

include 'includes/happinesscheck.php';

header('Location: budget.php');
?>