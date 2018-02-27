<?php
function drawMenu($current)
{
	
?>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><span>match</span>point</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> admin <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Профиль</a></li>
							<li><a href="#"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg> Настройки</a></li>
							<li><a href="?destroy"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Выйти</a></li>
						</ul>
					</li>
				</ul>
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<ul class="nav menu">
			<li <?php echo ($current=='1')? 'class="active"':"";?>><a href="index.php"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg>Календарь</a></li>
			<li <?php echo($current=='2')? 'class="active"':"";?>><a href="visits.php"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg>Посещения</a></li>
			<li <?php echo($current=='3')? 'class="active"':"";?>><a href="subscriptions.php"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg>Абонементы</a></li>
			<li <?php echo($current=='4')? 'class="active"':"";?>><a href="students.php"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>Студенты</a></li>
			<li <?php echo($current=='5')? 'class="active"':"";?>><a href="tourneys.php"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg>Турниры</a></li>
			<li <?php echo($current=='6')? 'class="active"':"";?>><a href="galery.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg>Галерея</a></li>
			<li role="presentation" class="divider"></li>
			<li><a href="login.html"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>сменить пользователя</a></li>
		</ul>

	</div><!--/.sidebar-->

<?php
}
?>
