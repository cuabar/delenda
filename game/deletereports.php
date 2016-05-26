<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$deletereports = mysql_query("DELETE FROM missionresults WHERE allegiance='$allegiance' AND user='$username' AND colony='$chosencolony'");

header('Location: missionreports.php');
?>