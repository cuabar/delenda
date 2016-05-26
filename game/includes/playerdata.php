<?php
session_start();

if(!isset($_SESSION['username']) && $_SESSION['playercolony'] && $_SESSION['allegiance'])
{
	header('Location: ../index.php');
}

$chosencolony = $_SESSION['playercolony'];
$username = $_SESSION['username'];
$allegiance=$_SESSION['allegiance'];
$factionmenu=$allegiance.'.php';
$factiondb=$allegiance.'factions';
?>