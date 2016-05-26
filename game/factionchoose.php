<?php
include '../includes/connect.php';

session_start();

if(!isset($_SESSION['username']))
{
	header('Location: index.php');
}

$chosencolony = $_POST['colony'];
$_SESSION['playercolony'] = $chosencolony;

header('Location: index.php');
?>