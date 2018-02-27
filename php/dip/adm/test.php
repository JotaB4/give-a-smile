<?php
header("Content-Type: text/html; charset=utf-8");
$dblink = new mysqli("localhost","admin","admin","matchpointdb");
if($dblink->connect_error)
	echo "cannot connect to mysql";
else {
	$dblink->set_charset("utf8");
		
	$nameSt = 'Йонас2';
	$lastnameSt = 'Авитов2';

	$sql = "INSERT INTO  rabbit (id,name,lastname)VALUES (NULL,'$nameSt','$lastnameSt')";
	if($dblink->query($sql)===false)
		trigger_error('Wrong SQL: '.$sql.' Error: '.$dblink->error, E_USER_ERROR);

	$sql = "SELECT * FROM students";
	$response = $dblink->query($sql);
	if($response ===false){
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
	}
	else {
		$response->data_seek(0);
		while($row = $response->fetch_assoc()){
			echo $row['id'].'<br>';
			echo $row['name'].'<br>';
			echo $row['lastname'].'<br>';
		}
	}
	echo "<br>inserted<br>";
	echo "<pre>";
	print_r($_GET);
	echo "</pre>";

	if(!empty($_GET))
		echo "get is not empty<br>";
	if($_GET == null)
		echo "get is empty";
	//if($_GET['userid'])
	//	echo $_GET['userid']." is got";
	
	$userid = $_GET['userid'];
	echo "GET: $userid is var<br>";
			
			//$response = $dblink->query($sql);
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
			else {
				while($row = $response->fetch_assoc()){
					$nameSt = $row['stname'];
					$lastnameSt = $row['stlastname'];
					$birthdateSt = $row['stbirthdate'];
					$emailSt = $row['stemail'];
					$genderSt = $row['stgender'];
					$phoneSt = $row['stphone'];
					$addrSt = $row['staddress'];
					$photoSt = $row['stphoto'];
					$linksSt = $row['stlinks'];
					$commentsSt = $row['stcomments'];
				$nameSt="while";
				}
			}
			$userid = $_GET['userid'];
			$sql = "SELECT * FROM students WHERE id = '$userid'";
			$response = $dblink->query($sql);
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
			else {
				while($student = $response->fetch_assoc()){
				$nameSt = $student['stname'];
					$lastnameSt = $student['stlastname'];
					$birthdateSt = $student['stbirthdate'];
					$emailSt = $student['stemail'];
					$genderSt = $student['stgender'];
					$phoneSt = $student['stphone'];
					$addrSt = $student['staddress'];
					$photoSt = $student['stphoto'];
					$linksSt = $student['stlinks'];
					$commentsSt = $student['stcomments'];
			echo "<pre>";
			print_r($student);
			echo "</pre>";
				}
			echo $nameSt;
			}
}
?>
