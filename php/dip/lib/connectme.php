<?php
header("Content-Type: text/html; charset=utf-8");
$dblink = new mysqli("78.108.80.117","u180174","testtest2016","b180174_matchpointdb");
if($dblink->connect_error){
	trigger_error('Cannot connect to database: '.$dblink->error, E_USER_ERROR);
}
else {
	$dblink->set_charset("utf8");
}
?>