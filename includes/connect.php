<?php

$db = mysql_connect("localhost","root","") or die ("Could not connect to databse");
if(!$db)
	die ("No database");
if(!mysql_select_db("delenda",$db))
	die ("No database selected");
	

mysql_select_db("delenda") or die("Cannot find Database");
?>