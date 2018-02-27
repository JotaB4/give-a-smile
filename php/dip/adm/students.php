<?php
require_once("../lib/connectme.php");
require("../lib/access.php");
if(isset($_POST['stname'])){
	$nameSt = $_POST['stname'];
	$lastnameSt = $_POST['stlastname'];
	$birthdateSt = date("Y-m-d",strtotime($_POST['stbirthdate']));

	$emailSt = $_POST['stemail'];
	$genderSt = $_POST['stgender'];
	//removing non-digits from phone var:
	$phoneSt = preg_replace('/[^0-9]*/','', $_POST['stphone']);
	
	$addrSt = $_POST['staddress'];
	$photoSt = $_POST['stphoto'];
	$skillSt = $_POST['stskill'];
	$linksSt = $_POST['stlinks'];
	$commentsSt = $_POST['stcomments'];
	$userid = $_POST['userid'];

	if($_POST['domodify']=='0'){
		if(!empty($nameSt) && !empty($lastnameSt)&& !empty($birthdateSt)&& !empty($emailSt)&& !empty($genderSt)&& !empty($phoneSt)){
			$sql = "INSERT INTO students
					(id,name,lastname,birthdate,email,gender,phone,address,photo,links,skill,comments)
					VALUES 
					(NULL, '$nameSt','$lastnameSt','$birthdateSt','$emailSt','$genderSt','$phoneSt','$addrSt','$photoSt','$linksSt','$skillSt','$commentsSt')";
			$response = $dblink->query($sql);
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
		}
	}
	if($_POST['domodify']=='1'){
		if(!(empty($nameSt)) && !(empty($lastnameSt))&& !(empty($birthdateSt))&& !(empty($emailSt))&& !(empty($genderSt))&& !(empty($phoneSt))){
			$sql = "UPDATE students 
			SET name='$nameSt',lastname='$lastnameSt',birthdate='$birthdateSt',email='$emailSt',gender='$genderSt',phone='$phoneSt',address='$addrSt',photo='$photoSt',links='$linksSt',skill='$skillSt',comments='$commentsSt' 
			WHERE id = '$userid'";
			$response = $dblink->query($sql);
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
		}	
	}
}
if(!empty($_GET['deleteuser'])){
	$userid = $_GET['deleteuser'];
	$sql = "DELETE FROM students WHERE id = '$userid'";
	$response = $dblink->query($sql);
	if($response ===false){
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Студенты - match-point.su</title>
<?php require("../lib/commonhead.php");?>
</head>

<body>
<?php
require("../lib/menu.php");
drawMenu(4);
?>

		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Студенты</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">

<div>
	<div class="panel panel-info">		
		<div class="panel-heading">
		<input type='button' class="btn btn-primary" onclick="window.location='newstudent.php';" value='добавить'>
		</div>
		<div class="panel-body">
		<div class="panel-body">
						<table data-toggle="table" data-url="tables/data1.json"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="id" data-sort-order="asc">
						    <thead>
							    <tr>
							        <th data-sortable="true">id</th>
							        <th data-sortable="true">Имя</th>
							        <th data-sortable="true">Фамилия</th>
							        <th data-sortable="true">Дата рождения</th>
							        <th data-sortable="true">Пол</th>
							        <th>Телефон</th>
							        <th data-sortable="true">Уровень</th>
							        <th>Ссылки</th>
							        <th>Комментарии</th>
							        <th>Действия</th>
							    
							    </tr>
						    </thead>
						    <tbody>
		<?php 
			$sql = "SELECT * FROM students ORDER BY id ASC";
			$response = $dblink->query($sql);
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
			else {
				$rowInd = 0;
				while($student = $response->fetch_assoc()){
				
					
					echo "<tr><td>".$student['id']."</td>";
					echo "<td>".$student['name']."</td>";
					echo "<td>".$student['lastname']."</td>";
					echo "<td>".date("d.m.Y",strtotime($student['birthdate']))."</td>";
					echo "<td>".(($student['gender'])?'муж':'жен')."</td>";
					echo "<td>".$student['phone']."</td>";
					echo "<td>".$student['skill']."</td>";
					echo "<td>".$student['links']."</td>";
					echo "<td>".$student['comments']."</td>";
					echo "<td><a href='?deleteuser=".$student['id']."' ><img width='20' src='/img/cross.png'></a> <a href='edituser.php?userid=".$student['id']."'><img width='20' src='/img/pencil.png'></a></td></tr>";
					
					$rowInd++;
				}
			}
		?>
						    </tbody>
						</table>
					</div>
	
		</div>
	</div>
</div>

	<script>
		
		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})

	</script>	
</body>

</html>
