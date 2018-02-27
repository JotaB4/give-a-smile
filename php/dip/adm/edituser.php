<?php
require_once("connectme.php");
require("access.php");
if(isset($_GET['userid'])){
			$userid = $_GET['userid'];
			$sql = "SELECT * FROM students WHERE id = '$userid'";
			$response = $dblink->query($sql);
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
			else 
				$student = $response->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>match-point.su - Редактировать даные</title>
<?php require("commonhead.php");?>
</head>

<body>
	<?php
require("menu.php");
drawMenu(4);
?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Редактирование</li>
			</ol>
		<div class="col-md-4">
			<div id="adduserdiv" title="Добавить студента">
			 	 <form action="students.php" method="POST">
				  	Имя:<br> <input class="form-control" type="text" name='stname' value='<?=$student['name']?>'>
				  	<br>
				  	Фамилия:<br> <input class="form-control" type="text" name='stlastname' value='<?=$student['lastname']?>'>
				  	<br>
				  	Дата рождения:<br> <input class="form-control" type="date" name='stbirthdate' value='<?=$student['birthdate']?>'>
				  	<br>
				  	email:<br> <input class="form-control"  type="text" name='stemail' value='<?=$student['email']?>'>
				  	<br>
				  	Пол: муж <input  type="radio" name='stgender' value='1' <?php echo ($student['gender'])? "checked":"";?>> жен 
				  	 &nbsp;<input  type="radio" name='stgender' value='0'<?php echo (!$student['gender'])? "checked":"";?>>
				  	<br>
				  	Телефон:<br> <input class="form-control"  type="text" name='stphone' value='<?=$phoneSt = $student['phone']?>'>
				  	<br>
				  	Адрес:<br> <input class="form-control" type="text" name='staddress' value='<?=$student['address']?>'>
				  	<br>
				  	Файл фотографии:<br> <input class="form-control" type="file" name='stphoto' value='<?=$student['photo']?>'>
				  	<br>
				  	Ссылки:<br> <input type="text" name='stlinks' value='<?=$student['links']?>'>
					<br>
				  	Уровень игры:<br> <input class="form-control" type="text" name='stskill' value='<?=$student['skill']?>'>
				  	<br>
				  	Комментарии: <br><textarea class="form-control" rows="4" cols="30" name='stcomments'><?=$student['comments']?></textarea>
				  	<br>
				  	<input type="hidden" name='domodify' value='1'>
				  	<input type="hidden" name='userid' value='<?=$userid?>'>
				  	<input type="submit" class="btn btn-primary" value="сохранить">
				  	<input type="button" class="btn btn-default" value='отменить' onclick="window.location='students.php';">
				  	<input type="reset" class="btn btn-default" value="вернуть">
				 
			 	 </form>
			</div>
		</div>
		</div>
</div>
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
		$('#calendar').datepicker({
		});

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
