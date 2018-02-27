<?php
require_once("../lib/connectme.php");
require("../lib/access.php");
if(!empty($_GET['delete'])){
	$klassid= $_GET['delete'];
	$sql = "DELETE FROM classes WHERE id='$klassid'";
	$response= $dblink->query($sql);
	if($response ===false){
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);

		}
}

if(!empty($_POST['klplace'])&&!empty($_POST['klstartsdate'])&&!empty($_POST['klduration'])){
	//$combinedtimestamp = mktime($_POST['klstarttimehour'], $_POST['klstarttimemin'], 0, $mo, $da, $ye);
	$combinedtimestamp = $_POST['klstartsdate']." ".$_POST['klstarttimehour'].":".$_POST['klstarttimemin'].":33";
	$klstarts = date('Y-m-d H:i:s', strtotime($combinedtimestamp));
	$klduration = $_POST['klduration'];
	$klplace = $_POST['klplace'];

	$sql = "INSERT INTO classes (id,starts,duration,place)
							VALUES (NULL, '$klstarts','$klduration','$klplace')";
	
	$response_insert = $dblink->query($sql);
	
	if($response_insert ===false){
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
		echo "<script>alert('bad things happend');</script>";

		}
}
$currentdate = date('Y-m-d');
$threedaysplus = date('Y-m-d',  strtotime('+3 days'));
$sql = "SELECT * FROM classes WHERE DATE(starts)>='$currentdate' AND DATE(starts)<='$threedaysplus' ORDER BY starts ASC";
$response_3days = $dblink->query($sql);
if($response_3days ===false){
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
		echo "<script>alert('cannot draw table');</script>";

		}
$sql = "SELECT * FROM classes WHERE DATE(starts)>='$currentdate' ORDER BY starts ASC";
$response_further = $dblink->query($sql);
if($response_further ===false){
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
		echo "<script>alert('cannot draw table');</script>";

		}
?>
<!DOCTYPE html>
<html>
<head>
<title>Календарь - match-point.su</title>
<?php require("../lib/commonhead.php");?>
</head>

<body>
<?php
require("../lib/menu.php");
drawMenu(1);
?>

		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Календарь</li>
			</ol>
		</div>
		
		<div class="row">
			
			<div class="col-md-8">
				<div class="panel panel-blue">
					<div class="panel-heading dark-overlay"><svg class="glyph stroked clipboard-with-paper"><use xlink:href="#stroked-clipboard-with-paper"></use></svg>Ближайшие занятия</div>
					<div class="panel-body">
						<ul class="todo-list">
							<?php
							setlocale (LC_TIME, "ru_RU.UTF-8");
							while($klass = $response_3days->fetch_assoc()){
								echo '<li class="todo-list-item">'.strftime("%d.%m(%A) в %H:%M", strtotime($klass['starts']))."<b> ".$klass['place'].'</b>
											<div class="pull-right action-buttons">
												<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg></a>
												<a href="#" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"></use></svg></a>
											</div>
									</li>';
							}

							?>																
							
						</ul>
					</div>
					
				</div>
			</div>
			<!--<div class="col-md-4">
			
						<div id="calendar"></div>

			</div>	-->
			<div class="col-md-6">
				<div class="panel panel-white">
					<div class="panel-heading dark-overlay"><svg class="glyph stroked clipboard-with-paper"><use xlink:href="#stroked-clipboard-with-paper"></use></svg>Все занятия</div>
					<div class="panel-body">
						<ul class="todo-list">
							<?php
							while($klass = $response_further->fetch_assoc()){
								echo '<li class="todo-list-item">'.date("d-m-y H:i",strtotime($klass['starts']))."<b> ".$klass['place'].'</b>
											<div class="pull-right action-buttons">
												<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg></a>
												<a href="?delete='.$klass['id'].'" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"></use></svg></a>
											</div>
									</li>';
							}

							?>																
							
						</ul>
					</div>
					<div class="panel-footer">
								<button class="btn btn-primary btn-md" id='addklassbtn' type="submit">Добавить</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--pop-up div for adding new class-->
<div id="addklass" title="Добавить тренировку">
  <form action="#" method="POST">
  	Место:<br> <input type="text" class="form-control" required  name='klplace'>
  	<br>
  	Когда:<br> <input type="text" id="newclassdate" class="form-control" required name='klstartsdate'>
  	<br>
  	Во сколько:<br> 
	<input size="3" required type="text" pattern="([01]?[0-9]|2[0-3])" name='klstarttimehour'>:<input type="text" size="3" pattern="[0-5][0-9]" required name='klstarttimemin'>
  	<br>
  	Длительность (мин):<br> <input type="text" class="form-control" required value="60" name='klduration'>
  	<br>
  	<input type="submit" class="btn btn-primary" 
  	value="добавить">
  </form>
</div>
<!--pop-up div for adding new class-->
	<script>
		$('#newclassdate').datepicker({
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
				//adduser pop-up function:
  $(function() {
    $( "#addklass" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
 
    $( "#addklassbtn" ).click(function() {
      $( "#addklass" ).dialog( "open" );
    });
  });
	</script>	
</body>

</html>
