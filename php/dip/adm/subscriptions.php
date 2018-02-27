<?php
require_once("../lib/connectme.php");
require("../lib/access.php");
$currentdate = date('Y-m-d');
$colorbtn=-1;
if(isset($_POST['sub_studentid'])){
	$issue_date = $currentdate;
	$expires = (empty($_POST['sub_expires']))?date('Y-m-d',  strtotime('+1 month')):$_POST['sub_expires'];//$_POST['sub_expires'];
	$paid = $_POST['sub_paid'];
	$cost_per_class = $_POST['sub_costperclass'];
	$classes_left = $_POST['sub_classesall'];
	$classes_all = $_POST['sub_classesall'];
	$comments = $_POST['sub_comments'];
	$student_id = $_POST['sub_studentid'];

	if(!empty($classes_all) && !empty($paid)&& !empty($student_id)){
			$sql = "INSERT INTO subs
					(id,issue_date,expires,paid,cost_per_class,classes_left,classes_all,comments,student_id)
					VALUES 
					(NULL, '$issue_date','$expires','$paid','$cost_per_class','$classes_left','$classes_all','$comments','$student_id')";
			
			$response = $dblink->query($sql);
			
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
		}

}
if($_GET['showsubs']=='current'){
		$sql = "SELECT * FROM subs WHERE DATE(expires)>='$currentdate' ORDER BY id ASC";
			$response = $dblink->query($sql);
			$colorbtn=1;
}
else if($_GET['showsubs']=='expiring'){
		$sql = "SELECT * FROM subs WHERE DATE(expires)='$currentdate' ORDER BY id ASC";
			$response = $dblink->query($sql);
			$colorbtn=3;
}
else {
		$sql = "SELECT * FROM subs ORDER BY id ASC";
			$response = $dblink->query($sql);
			$colorbtn=2;

}
?>
<!DOCTYPE html>
<html>
<head>
<title>Абонементы - match-point.su </title>
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
				<li class="active">Абонементы</li>
			</ol>
		</div><!--/.row-->
	
	<div class="panel panel-info">		
		<div class="panel-heading">
		<input type='button' class="btn btn-primary" onclick="window.location='newsub.php';" value='новый'>
		<form style="display:inline-block;"><input type='hidden' name='showsubs' value='current'><input type='submit' <?php echo($colorbtn==1)? 'class="btn btn-primary"':'class="btn btn-default"';?> value='текущие'></form>
		<form style="display:inline-block;" ><input type='hidden' name='showsubs' value='all'><input type='submit' <?php echo($colorbtn==2)? 'class="btn btn-primary"':'class="btn btn-default"';?> value='все'></form>
		<form style="display:inline-block;"><input type='hidden' name='showsubs' value='expiring'><input type='submit' <?php echo($colorbtn==3)? 'class="btn btn-primary"':'class="btn btn-default"';?> value='истекают'></form>
		</div>
		</div>

		<div class="panel-body">
<table data-toggle="table"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="id" data-sort-order="asc">
						    <thead>
							    <tr>
							        <th data-sortable="true">№</th>
							        <th data-sortable="true">Студент</th>
							        <th data-sortable="true">Осталось</th>
							        <th data-sortable="true">Всего</th>
							        <th data-sortable="true">Заканчивается</th>
							        <th>Оплачено</th>
							        <th data-sortable="true">Выдан</th>
							        <th>Стоимость</th>
							        <th>Комментарий</th>
							       
							    
							    </tr>
						    </thead>
						    <tbody>
		<?php 
			
			if($response ===false)
				trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
			else {
				
					
				while($sub = $response->fetch_assoc()){
				
					echo "<tr><td>".$sub['id']."</td>";
					echo "<td>".$sub['student_id']."</td>";
					echo "<td>".$sub['classes_left']."</td>";
					echo "<td>".$sub['classes_all']."</td>";
					echo "<td>".$sub['expires']."</td>";
					echo "<td>".$sub['paid']."</td>";
					echo "<td>".$sub['issue_date']."</td>";
					echo "<td>".$sub['cost_per_class']."</td>";
					echo "<td>".$sub['comments']."</td></tr>";
						
				}
				
			}
		?>
						    </tbody>
						</table>
		</div>
	</div>
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
