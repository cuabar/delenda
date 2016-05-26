<?php
include 'includes/connect.php';

session_start();

if(!isset($_SESSION['username']))
{
	header('Location: index.php');
}

$chosencolony = $_POST['colony'];
$playerallegiance = $_POST['playerteam'];

$_SESSION['allegiance'] = $playerallegiance;
$_SESSION['playercolony'] = $chosencolony;

header('Location: game/index.php');
?>