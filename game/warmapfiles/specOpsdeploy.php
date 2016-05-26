<?php
require '../../includes/connect.php';

require '../includes/playerdata.php';

$missiontype = $_POST['formmissiontype'];
$missionid = $_POST['formmissionid'];
$battlezone = $_POST['formmissionzone'];
$troopid = $_POST['formmissiontroops'];

$missiontype=stripslashes($missiontype);
$missionid = stripslashes($missionid);
$battlezone = stripslashes($battlezone);
$troopid = stripslashes($troopid);

$missiontype = mysql_real_escape_string($missiontype);
$missionid = mysql_real_escape_string($missionid);
$battlezone = mysql_real_escape_string($battlezone);
$troopid = mysql_real_escape_string($troopid);

//Complex missions
$troop3id = $_POST['formmissiontroops3'];
$troop2id = $_POST['formmissiontroops2'];
$troop3id = stripslashes($troop3id);
$troop2id = stripslashes($troop2id);
$troop3id = mysql_real_escape_string($troop3id);
$troop2id = mysql_real_escape_string($troop2id);
$resource = $_POST['formmissionres'];
$resource = stripslashes($resource);
$resource = mysql_real_escape_string($resource);
$resourceamount = $_POST['formmissionresreq'];
$resourceamount = stripslashes($resourceamount);
$resourceamount = mysql_real_escape_string($resourceamount);

//Get the mission information.
$missiondetails = mysql_query("SELECT * FROM missions WHERE allegiance='$allegiance' AND missiontype='$missiontype'");
$missioninfo = mysql_fetch_array($missiondetails);
//Get the Troop Info
$troopdetails = mysql_query("SELECT * FROM recruits WHERE id='$troopid'");
$troopinfo = mysql_fetch_array($troopdetails);
$skill = $troopinfo['skill'];
$tier = $troopinfo['tier'];
//Complex Missions
if($troop2id != 'none')
{
	$troop2details = mysql_query("SELECT * FROM recruits WHERE id='$troop2id'");
	$troop2info = mysql_fetch_array($troop2details);
	$skill = $skill + $troop2info['skill'];
	$skillfinal = $skill / 2;
}
if($troop3id != 'none')
{
	$troop3details = mysql_query("SELECT * FROM recruits WHERE id='$troop3id'");
	$troop3info = mysql_fetch_array($troop3details);
	$skill = $skill + $troop3info['skill'];
	$skillfinal = $skill / 3;
}
if($resource != 'none')
{
	$removeres = mysql_query("UPDATE $factiondb SET $resource = $resource-$resourceamount WHERE factionname='$chosencolony' AND factionuser='$username'");
}
//Place the mission into the "Active Missions" table
if($missioninfo['id'] < 16)
{
	$missionrecord = mysql_query("INSERT INTO activemissions(user,colony,allegiance,battlezone,mission,tier,skill,ticksleft) VALUES('$username','$chosencolony','$allegiance','$battlezone','$missioninfo[id]','$troopinfo[tier]','$troopinfo[skill]','$missioninfo[ticks]')") or die(mysql_error());
}
else
{
	$missionrecord = mysql_query("INSERT INTO activemissions(user,colony,allegiance,battlezone,mission,tier,skill,ticksleft) VALUES('$username','$chosencolony','$allegiance','$battlezone','$missioninfo[id]','1','$skillfinal','$missioninfo[ticks]')") or die(mysql_error());
}
//Remove troop
$troopassign = mysql_query("DELETE FROM recruits WHERE id='$troopid'");
if($troop2id != 'none')
{
	$troopassign = mysql_query("DELETE FROM recruits WHERE id='$troop2id'");
}
if($troop3id != 'none')
{
	$troopassign = mysql_query("DELETE FROM recruits WHERE id='$troop3id'");
}
echo $skillfinal.'<br>';
echo '<br><br><strong>Mission Underway. Time to Completion: </strong>'.$missioninfo['ticks'].' Weeks.';
?>