<html>

<head>
<title>Delenda Est</title>
</head>

<body>
<b>Testing</b>
<br/>
<?php
$resources = array("palladium", "tungsten", "gold", "oil", "diamonds", "copper", "livestock", "silver", "coal", "palladium", "tungsten", "gold", "oil", "diamonds", "copper", "livestock", "silver", "coal", "illucite");
$randresource = $resources[array_rand($resources, 1)];

echo 'Result: '.$randresource?>
</body>
</html>