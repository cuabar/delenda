<?php
require 'includes/connect.php';
$getmercplayers = mysql_query("SELECT COUNT(*) FROM users WHERE allegiance='mercantile'");
$mercplayers = mysql_result($getmercplayers, 0);
$getauthplayers = mysql_query("SELECT COUNT(*) FROM users WHERE allegiance='authority'");
$authplayers = mysql_result($getauthplayers, 0);
$getsolplayers = mysql_query("SELECT COUNT(*) FROM users WHERE allegiance='solidarity'");
$solplayers = mysql_result($getsolplayers, 0);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Delenda Est</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="game/styles.css" />
<script src="game/includes/jquery.js"></script>
<script>
$(document).ready(function(){
	$('#solidarityinfo, #authorityinfo, #mercantileinfo').hide();
	$('#solidarityinfo').show();
	$('#army').change(function(){
		$('#solidarityinfo, #authorityinfo, #mercantileinfo').hide();
		var reveal = '#' +$(this).find('option:selected').attr('value') + 'info';
		$(reveal).slideDown();
	});
});
</script>
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
		<!--Registration form information-->
      <h2>Register New Account</h2>
		<form id='register' action='register.php' method='post'>
		<input type='hidden' name='submitted' id='submitted' value='1'/>
		<label for='name' >Username*: </label>
		<input type='text' name='name' id='name' maxlength="20" /><br>
		<label for='email' >Email Address*:</label>
		<input type='email' name='email' id='email' maxlength="50" /><br>
		<label for='username' >Password*:</label>
		<input type='password' name='password' id='password' maxlength="25" /><br>
		<label for='password' >Retype Password*:</label>
		<input type='password' name='password2' id='password2' maxlength="25" /><br>
		<label for='army' >Faction*:</label>
		<select name="army" id="army">
			<option value="solidarity">The Solidarity</option>
			<option value="mercantile">The Mercantile Union</option>
			<option value="authority">The Origin Authority</option>
		</select><br>
		
		<label for='colonyname' >Outpost Name*:</label>
		<input type='text' name='colonyname' id='colonyname' maxlength="30" /><br>
		<input type='submit' name='Submit' value='Submit' />
		</form>
		<br>
		<div id="solidarityinfo">
		<img src="game/solidarityfiles/images/emblem2.png" width="100" height="100">
		<br>
		<b>The Solidarity</b><br>
		<b>Total Players: </b><?php echo number_format($solplayers); ?><br>
		<b>Advantages: </b> Faster base troop production. Cost effective bomb-type superweapon.<br>
		<b>Disadvantages: </b> Lower base troop effectiveness.
		</div>
		<div id="authorityinfo">
		<img src="game/authorityfiles/images/emblem2.png" width="100" height="100">
		<br>
		<b>The Origin Authority</b><br>
		<b>Total Players: </b><?php echo number_format($authplayers); ?><br>
		<b>Advantages: </b> Most powerful base troop effectiveness. Powerful technologies.<br>
		<b>Disadvantages: </b> Slow troop production.
		</div>
		<div id="mercantileinfo">
		<img src="game/mercantilefiles/images/emblem.png" width="100" height="100">
		<br>
		<b>The Mercantile Union</b><br>
		<b>Total Players: </b><?php echo number_format($mercplayers); ?><br>
		<b>Advantages: </b> More valuable tax income. Balanced Troops. Greater resource availability.<br>
		<b>Disadvantages: </b> None. Hmmm....something's not quite right here....
		</div>
	<!--End registration form information-->
		</div>
	</div>
  </div>
</div>
</body>
</html>