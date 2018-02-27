<?php
$pattern = '/[^0-9]*/';
$phoneSt = "(906)2643185";
echo preg_replace($pattern,'', $phoneSt);
echo "<br>phone:$phoneSt";
?>