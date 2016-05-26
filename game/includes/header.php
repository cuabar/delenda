<?php

$allegiance = $_SESSION['allegiance'];
if($allegiance=='solidarity')
{
 echo 'The Solidarity';
}
elseif($allegiance=='mercantile')
{
 echo 'The Mercantile Union';
}
else
{
 echo 'The Origin Authority';
}
?>