<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Delenda Est</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<div id="container">
<div id="header"><h1><a href="http://www.free-css.com/free-css-layouts.php">Delenda Est</a></h1></div>
  <div id="wrapper">
    <div id="content">
	<!--Login form information-->
      <h2>Login</h2>
		<form id='login' action='login.php' method='post'>
		<label for='username' >UserName*:</label>
		<input type='text' name='username' id='username'  maxlength="15" /><br>
		<label for='password' >Password*:</label>
		<input type='password' name='password' id='password' maxlength="15" /><br>
		<input type='submit' name='Submit' value='Submit' />
		</form> 
    </div>
  </div>
  <div id="extra">
    <p><strong>Menu</strong></p>
    <?php include 'menu.php'; ?>
  </div>
  <div id="footer">
    <p>Aftershock Industries &copy 2012</p>
  </div>
</div>
</body>
</html>
