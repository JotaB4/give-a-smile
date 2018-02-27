<?php
require("../lib/access.php");
setlocale (LC_TIME, "ru_RU.UTF-8");
$sql = "SELECT * FROM students ORDER BY id ASC";
$response_students = $dblink->query($sql);
if($response_students ===false)
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
$sql = "SELECT * FROM classes ORDER BY starts ASC";
$response_classes = $dblink->query($sql);
if($response_classes ===false)
		trigger_error('Wrong SQL: '.$sql.'Error: '.$dblink->error, E_USER_ERROR);
?>
<!DOCTYPE html>
<html>
<head>
<title>Посещения - match-point.su </title>
<?php require("../lib/commonhead.php");?>
</head>

<body>
<?php
require("../lib/menu.php");
drawMenu(2);
?>
		
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
			<li class="active">Посещения</li>
		</ol>
		<div class="panel panel-default">
			<div class="panel-body tabs">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">Текущее занятие</a></li>
					<li><a href="#tab2" data-toggle="tab">Общий журнал</a></li>
					<li><a href="#tab3" data-toggle="tab">Инфо</a></li>
				</ul>
		
				<div class="tab-content">
					<div class="tab-pane fade in active" id="tab1">
				
						Занятие: 
						<select class="form-control">
							<?php
								while($klass = $response_classes->fetch_assoc()){
									echo "<option>".strftime("%d.%m(%A) в %H:%M", strtotime($klass['starts']))." ".$klass['place']."</option>";
								}
							?>
						</select>
						<br>
	<!--table-->
						<table data-toggle="table" data-url="tables/data1.json"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="id" data-sort-order="asc">
						    <thead>
							    <tr>
							        <th data-sortable="true">id</th>
							        <th data-sortable="true">Фамилия</th>
							        <th data-sortable="true">Абонемент</th>
							        <th>Действия</th>
							    
							    </tr>
						    </thead>
						    <tbody>
								<?php
								while($student = $response_students->fetch_assoc()){
									echo "<tr><td>".$student['id']."</td>";
									echo "<td>".$student['lastname']."</td>";
									echo "<td>нет инфо</td>";//заглушка! по умолчанию 
									echo "<td><a href='#'>пришел</a></td></tr>";
								}
							?>
						    </tbody>
						</table>

	<!--end-of-table-->
					</div>
				<div class="tab-pane fade" id="tab2">
								<h4>Общий журнал</h4>
									<div class="panel panel-default">
									<div class="panel-body">
										<div class="bootstrap-table">
											<div class="fixed-table-toolbar"></div>
												<div class="fixed-table-container">
													<div class="fixed-table-header"><table></table>
													</div>
													<div class="fixed-table-body">
														<div class="fixed-table-loading" style="top: 37px; display: none;">Loading, please wait…</div>
														<table data-toggle="table" class="table table-hover">
														   <thead>
															    <tr>
															    	<th style="text-align: right; "><div class="th-inner ">Студент</div><div class="fht-cell"></div></th>
																		<?php while($klass = $response_classes->fetch_assoc()){
																					echo '<th><div class="th-inner ">'.date("d-m",strtotime($klass['starts'])).'</div><div class="fht-cell"></div></th>';
																			  	}?>
									 							</tr>
																			  
														   </thead>
															<tbody>
																<?php 
																	while($student = $response_students->fetch_assoc()){
																		echo 	"<tr><td style='text-align: right;' id='".$student['id']."'>".$student['lastname']."</td></tr>";											
																	}
																?>
															</tbody>
														</table>
															</div>
										<div class="fixed-table-pagination"></div>
									</div>
								</div><div class="clearfix"></div>
							</div>
						</div>
					</div>
							<div class="tab-pane fade" id="tab3">
								<h4>Увы...</h4>
								<p>Андер констракшн </p>
							</div>
						</div>
					</div>
				</div><!--/.panel-->
			</div>

	</div><!--/.row-->
	
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
