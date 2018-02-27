<?php
if(!empty($_POST['loginuname'])&&!empty($_POST['loginpass'])){
	$dblink = new mysqli("78.108.80.117","u180174","testtest2016","b180174_matchpointdb");
	if($dblink->connect_error)
		trigger_error('Cannot connect to database: '.$dblink->error, E_USER_ERROR);
	else {
		$dblink->set_charset("utf8");
		$sql = sprintf("SELECT 1 FROM admins WHERE username='%s' AND password='%s'",$dblink->real_escape_string($_POST['loginuname']),$dblink->real_escape_string($_POST['loginpass']));
		$auth = $dblink->query($sql);
		if($auth->num_rows == 1){
			session_start();
			$_SESSION['user']="hohoho";
			$_SESSION['SID'] = session_id();
			header("location:index.php");
		}
		else
			echo "<b> нет такого юзера!</b><br>";
			
	}

}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Авторизация - match-point.su </title>
	<style type="text/css">
			
	</style>
</head>

<body>
		<div id="loginform">
			<form method="POST">
				имя:<input type="text" name="loginuname" required />
				<br>
				пароль:<input type="text" name="loginpass" required />
				<br>
				<input type="submit" value="войти">
			</form>
		</div>
</body>
</html>