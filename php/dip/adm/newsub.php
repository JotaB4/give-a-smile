<?php
require_once("../lib/connectme.php");
require("../lib/access.php");
$currentdate = date('Y-m-d');
$sql = "SELECT * FROM students ORDER BY lastname ASC";
$response = $dblink->query($sql);
if($response ===false)
	trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
else {
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Абонементы - match-point.su</title>
<?php require("../lib/commonhead.php");?>
</head>

<body>
	<?php
require("../lib/menu.php");
drawMenu(3);
?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li>Абонементы</li><li class="active">Новый абонемент</li>
			</ol>
		


			<div class="panel-body">
			<div class="col-md-4">
				<div id="newsubdiv" title="Добавить абонемент">
					 <form action="subscriptions.php" method="POST">
					  	Студент:<br> <select name='sub_studentid' class="form-control">
						<?php 
						if($response ===false)
							trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
						else {
							while($opt = $response->fetch_assoc()){
								echo "<option value='".$opt['id']."'>".$opt['lastname']."</option>";
							}

						}

						?>
					  		</select>
					  	<br>
					  	Количество занятий:<br> <input type="text"  class="form-control" required name='sub_lessonsall'>
					  	<br>
					  	Дата выдачи:<br> <input type="text" value='<?=$currentdate?>'class="form-control" disabled>
					  	<br>
					  	Истекает:<br> <input type="text" class="form-control" id="newsubdate" name="sub_expires">
					  	<br>
					  	Оплачено:<br> <input type="text" required class="form-control" name='sub_paid'>
					  	<br>
					  	Всего занятий:<br> <input type="text" required class="form-control" name='sub_classesall'>
					  	<br>
					  	Стоимость одного занятия:<br> <input type="text" required class="form-control" name='sub_costperclass'>
					  	<br>
					  	Комментарии: <br><textarea class="form-control" rows="4" cols="30" name='sub_comments'></textarea>
					  	<br>
					  	<input type="submit" class="btn btn-primary" value="добавить">
					  	<input type="reset"  class="btn btn-default" value="вернуть">
				 
				  	</form>
				  </div>
			</div>
			</div>
	</div>
	</div>
	<script>
		$('#newsubdate').datepicker({
			firstDay: 1
		});
		$( '#newclassdate' ).datepicker( $.datepicker.regional[ "ru" ] );
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
