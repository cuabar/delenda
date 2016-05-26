<?php
$allegiance = $_SESSION['allegiance'];

if($allegiance=='solidarity')
{
 echo '<br><img src="solidarityfiles/images/emblem2.png" width="200" height="190"/><br>';
}
elseif($allegiance=='mercantile')
{
 echo '<img src="mercantilefiles/images/emblem.png" width="97" height="200"/><br>';
}
elseif($allegiance=='authority')
{
 echo '<img src="authorityfiles/images/emblem2.png" width="97" height="200"/><br>';
}?>