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
		<!--Login form information-->
		<br><br>
      <h2>Login</h2>
		<form id='login' action='login.php' method='post'>
		<label for='username' >UserName*:</label>
		<input type='text' name='username' id='username'  maxlength="20" /><br>
		<label for='password' >Password*:</label>
		<input type='password' name='password' id='password' maxlength="25" /><br>
		<input type='submit' name='Submit' value='Submit' />
		</form>
	<!--End login form information-->	
		</div>
	</div>
  </div>
</div>
</body>
</html>