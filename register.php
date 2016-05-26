<?php
require 'includes/connect.php';

require("PasswordHash.php");

if($_POST['name'] && $_POST['email'] && $_POST['password'] && $_POST['password2'] && $_POST['army'] && $_POST['colonyname'])
{
	if($_POST['password'] == $_POST['password2'])
	{
		$username = $_POST['name'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$factionname = $_POST['colonyname'];
		$allegiance = $_POST['army'];
		
		$unsafe = array("'", ".", '"');
		$factionname = str_replace($unsafe, "", $factionname);
		$username = str_replace($unsafe, "", $username);
		$username = stripslashes($username);
		$password = stripslashes($password);
		$email = stripslashes($email);
		$factionname = stripslashes($factionname);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$email = mysql_real_escape_string($email);
		$factionname = mysql_real_escape_string($factionname);
		
		
		$hasher = new PasswordHash(8, false);
		$checkuser = mysql_query("SELECT username FROM users WHERE username='$username'");
		$usernumrows = mysql_num_rows($checkuser);
		$checkemail = mysql_query("SELECT email FROM users WHERE email='$email'");
		$emailnumrows=mysql_num_rows($checkemail);
		
		if(strlen($password)>72)
		{
			die("Password must be 72 characters or less.<br>");
		}
		if($usernumrows > 0)
		{
			echo 'Your username is already in use.<br>';
		}
		if($emailnumrows >0)
		{
			echo 'Your email is already in use.<br>';
		}
		else
		{
		$hash=$hasher->HashPassword($password);
		
		if(strlen($hash) > 20)
		{
			$encpass = $hash;
		}
		else
		{
		 die("Something went wrong.");
		}
		
		$resources = array("palladium", "tungsten", "gold", "oil", "diamonds", "copper", "livestock", "silver", "coal", "palladium", "tungsten", "gold", "oil", "diamonds", "copper", "livestock", "silver", "coal", "illucite");
		$randresource = $resources[array_rand($resources, 1)];
		
		$register = mysql_query("INSERT INTO users(username, email, password, numoffactions, allegiance) VALUES ('$username', '$email', '$encpass', '1', '$allegiance')") or die('Error in Registration<br><a href="newaccount.php">Try Again</a>');
		$resregister=mysql_query("INSERT INTO resourcebanks(factionuser,factionname,diamonds,oil,copper,silver,gold,palladium,tungsten,coal,livestock,illucite) VALUES ('$username', '$factionname', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0')") or die(mysql_error());
		if($_POST['army'] == 'solidarity')
		{
			$factionstatsquery = mysql_query("SELECT colonies FROM factionstats WHERE factionname = '{$_POST['army']}'");
			$factionqueryrow = mysql_fetch_row($factionstatsquery);
			$colonies = $factionqueryrow[0];
			$newcolonies = $colonies + 1;
			$armyidreg = mysql_query("INSERT INTO solidarityfactions(factionuser, factionname, factionbank, factionpop, happiness, crime, healthcare, education, residential, law, hospitals, educational, barracks, warcentre, techcomplex, commerce, extractor, resource, land, tax) VALUES ('$username', '$factionname', '1000000', '10000', '100', '0', '100', '100', '1', '1', '1', '1', '0', '0', '0', '0', '0', '$randresource', '4996', '5')") or die(mysql_error());
			$factionstats = mysql_query("UPDATE factionstats SET colonies=$newcolonies WHERE factionname='{$_POST['army']}'");
		}
		elseif($_POST['army'] == 'mercantile')
		{
			$factionstatsquery = mysql_query("SELECT colonies FROM factionstats WHERE factionname = '{$_POST['army']}'");
			$factionqueryrow = mysql_fetch_row($factionstatsquery);
			$colonies = $factionqueryrow[0];
			$newcolonies = $colonies + 1;
			$randresource2 = $resources[array_rand($resources, 1)];
			$mercarmyidreg = mysql_query("INSERT INTO mercantilefactions(factionuser, factionname, factionbank, factionpop, happiness, crime, healthcare, education, residential, law, hospitals, educational, barracks, warcentre, techcomplex, commerce, extractor, resource, mercresource, land, tax) VALUES ('$username', '$factionname', '1000000', '10000', '100', '0', '100', '100', '1', '1', '1', '1', '0', '0', '0', '0', '0', '$randresource', '$randresource2', '4996', '5')") or die(mysql_error());
			$factionstats = mysql_query("UPDATE factionstats SET colonies=$newcolonies WHERE factionname='{$_POST['army']}'");
		}
		else
		{
			$factionstatsquery = mysql_query("SELECT colonies FROM factionstats WHERE factionname = '{$_POST['army']}'") or die(mysql_error());;
			$factionqueryrow = mysql_fetch_row($factionstatsquery);
			$colonies = $factionqueryrow[0];
			$newcolonies = $colonies + 1;
			$autharmyidreg = mysql_query("INSERT INTO authorityfactions(factionuser, factionname, factionbank, factionpop, happiness, crime, healthcare, education, residential, law, hospitals, educational, barracks, warcentre, techcomplex, commerce, extractor, resource, land, tax) VALUES ('$username', '$factionname', '1000000', '10000', '100', '0', '100', '100', '1', '1', '1', '1', '0', '0', '0', '0', '0', '$randresource', '4996', '5')") or die(mysql_error());
			$factionstats = mysql_query("UPDATE factionstats SET colonies=$newcolonies WHERE factionname='{$_POST['army']}'");
		}
		//header('Location:index.php');
		}
	}
	else
	{
		echo 'Both password fields must be identical!';
	}
}
else
{
	echo 'All Fields Must Be Complete';
}
?>
<br>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Delenda Est</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="game/styles.css" />
</head>
<body>
<!-- Begin Wrapper -->
<div id="wrapper">
  <!-- Begin Header -->
  <div id="header"><h1>Delenda Est</h1></div>
  <!-- End Header -->
  <!-- Begin Naviagtion -->
  <div id="navigation"> <center><ul class="navul">
      <li><a href="index.php">Home</a></li>
	  <li><a href="about.php">About</a></li>
	  <li><a href="newaccount.php">Register</a></li>
</ul></center></div>
  <!-- End Naviagtion -->
  <!-- Begin Content -->
  <div id="content">
	<div id="gamecontent">
		<div id="constructinfo">
		<h2>Welcome to the War</h2>
		<p>Welcome, hallowed Alpha Tester to Delenda Est. A tale of desperation, brotherly conflict, and turmoil. Also blowing things up. That last one's pretty important.</p>
		<br>
		<p>Having now signed up, there's a few things you need to do. First, please remember that the game is going to be broken to all hell at first. Keep an eye on your statistics
		and every action you perform. If <em>anything</em> doesn't seem right, please report it using the Bug Report system and I will take a look. Don't abuse bugs that you find. Just report
		them. If you find a way to build Superweapons without any War Factories, or gain infinite money with the click of a button, let me know sharpish. At the moment
		Bug Reports require me to log in and check on them, so it may take a while to get them looked at, let alone fixed.</p>
		<br>
		<p>Second, please sign up at the <a href="http://w11.zetaboards.com/ViolentSleep/forum/3632035/">forum</a>, and take a look at the already existing <a href="http://w11.zetaboards.com/ViolentSleep/topic/8483344/1/#new">To-Do list</a>. Suggestions, dicussions on gameplay and getting to know your fellow Alpha Testers are all kind of important at this early stage so you
		can compare notes and hopefully spot where something which should work the same for all factions only works correctly for one faction.</p>
		<br>
		<p>That should be it. So good luck, and have fun!</p>
		<br><center><a href="index.php">Login Now!</a></center>
		</div>
	</div>
  </div>
</div>
</body>
</html>