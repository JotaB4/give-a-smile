<?php
require_once("../lib/connectme.php");
require("../lib/access.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Студенты - match-point.su</title>
<?php require("../lib/commonhead.php");?>
<script type="text/javascript" src="js/maskplugin.js"></script>
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
				<li>Студенты</li><li class="active">Новый студент</li>
			</ol>
		

		<div class="panel panel-info">
			<div class="panel-body">
				<div class="col-md-4">
					<!--pop-up div for adding user-->
					<div id="adduserdiv" title="Добавить студента">
					  <form action="students.php" method="POST">
					  	Имя*:<br> <input type="text" class="form-control" required name='stname' placeholder="имя студента">
					  	Фамилия*:<br> <input type="text" class="form-control" required name='stlastname' placeholder="фамилия студента" >
					  	Дата рождения*:<br> <input type="text" class="form-control" id='newstudentdate' required name='stbirthdate' placeholder="07.11.1917">
					  	Пол*: муж <input type="radio" name='stgender' value='1' checked> жен 
					  	 &nbsp;<input type="radio" name='stgender' value='0'>
					  	 <br>
					  	Телефон*:<br> <input type="text" class="form-control" id='newstudentphone' required name='stphone' placeholder="999 111-22-33 ">
					  	email*:<br> <input type="text" class="form-control" required name='stemail' placeholder="icanwin@easily.to">
					  	Адрес:<br> <input type="text" class="form-control" name='staddress' placeholder="адрес проживания">
					  	<!--Файл фотографии (в разработке):<br> <input type="file" disabled class="form-control" name='stphoto' placeholder="">-->
					  	Ссылки:<br> <input type="text" class="form-control" name='stlinks' placeholder="ссылка на страницу">
					  	Уровень игры:<br> <input type="text" class="form-control" name='stskill' placeholder="уровень игры">
						Комментарии:
						<textarea rows="4" cols="30" class="form-control" name='stcomments' placeholder="можно ввести комментарии"></textarea>
					  	<br>
					  	<input type="hidden" name='domodify' value='0'>
					  	* - обязательные поля
					  	<br>
					  	<input type="submit" class="btn btn-primary" value="добавить">
					  	<input class="btn btn-default" onclick="window.location='students.php';" value="назад">
					  </form>
					</div>
					<!--pop-up div for adding user-->
				</div>
			</div>
			</div>
	</div>
	</div>
	<script>
		$('#newstudentphone').mask('(000) 000-00-00');
		$('#newstudentdate').datepicker({
			changeYear: true
		});
		$( "#newstudentdate" ).datepicker( "option", "yearRange", "1970:2010" );

		$( '#newstudentdate' ).datepicker( $.datepicker.regional[ "ru" ] );
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
