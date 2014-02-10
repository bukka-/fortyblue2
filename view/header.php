<!DOCTYPE html>
<html>
	<head>
		<title>sixtylime</title>

		<script src="/dev/main.js"></script>
		<script src="/vendor/bootstrap/js/bootstrap.js"></script>
		<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">

		<meta name=viewport content="width=device-width, initial-scale=1">
		<meta name="Description" CONTENT="A dynamic timetable for students.">
		<meta type="keywords" content="sixtylime|timetable|students|calendar|IB" >
		
		<link rel="stylesheet" href="/dev/main.css">


		<link rel="shortcut icon" href="/favicon.ico" />

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-21048440-3', 'sixtylime.com');
			ga('send', 'pageview');

		</script>
	</head>

	<body>

		<nav class="navbar navbar-default" role="navigation">
			<div class="container">

				<div class="navbar-header">
					<a class="nav-logo" href="/">Brand</a>
				</div>


				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<div class="btn-group nav-dropdown">
					<button type="button" class="btn btn-default dropdown-toggle dropdown-timetable" data-toggle="dropdown">
						<?
						if(isset($timetable_id)){
							$timetable2 = $timetable->getTimetable($timetable_id);
							echo $timetable2->class.' '.$timetable2->school;
						}else{
							echo 'Select Timetable';
						}

						?>
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu timetable-select" role="menu">
						<?
						$timetables = $login->getTimetables();
							for($i=0; $i<count($timetables); $i++){
								$highlight = "";
								if(isset($timetable_id) && $timetable_id == $timetables[$i]->timetable_id) $highlight = 'class="highlight"';

								echo '<li '.$highlight.'>
										<a href="/timetable/'.$timetables[$i]->timetable_id.'">'.$timetables[$i]->class.' '.$timetables[$i]->school.'</a>
									  </li>';
							}
						?>
						

						<?
						if(isset($_SESSION['user_group']) == "admin"){
						?>
						<li class="divider"></li>
						<li><a href="/timetable_new">Add New</a></li>
						<?
						}	
						?>
					</ul>
				</div>




				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
					<a href="/calendar" title="Calendar">
						<div class="btn btn-default calendar-button">
							<span class="glyphicon glyphicon-calendar"></span>
						</div>
					</a>

					<div class="navbar-text pull-right user_nav">
						<?
						if ($login->isUserLoggedIn() == true) {

							


						?>


							Hi <a href="/user/<? echo $_SESSION['user_name'];?>"><? echo $_SESSION['user_name'];?></a>! <? if(isset($_SESSION['user_group']) == "admin") echo'<a class="btn btn-success" href="/panel">Panel</a>'; ?> 
							<div class="dropdown notification-dropdown">
								<div class="btn btn-default" id="dropdownMenu1" data-toggle="dropdown"><span class="glyphicon glyphicon-bell"></span> <span class="caret"></span></div>
							   
									<ul class="dropdown-menu dropdown-menu-right pull-right" role="menu" aria-labelledby="dropdownMenu1">
									<?
	
										echo '<li role="presentation" class="dropdown-header">Upcoming Events</li>';
										echo $login->getNotifications();
	
	
									?>
	
									<li role="presentation" class="divider"></li>
									<li role="presentation"><a role="menuitem" tabindex="-1" href="/events">All Events</a></li>
								</ul>
							</div>

							<a class="btn btn-default" href="/new_login.php?logout">Logout</a>
						<?
						} else{
						?>
							Hello Guest! <a href="#" id="log-in" class="navbar-link">Log In</a> or <a href="/register" class="btn btn-success">Register</a>
						<?
						}
						?>
					</div>

				</div>
			</div>
		</nav>

	<div class="container">

		<? 
			if(count($login->errors)>0){
				for($i=0;$i<count($login->errors);$i++){
					echo '<div class="alert alert-danger">'.$login->errors[$i].'</div>'; 
				}
			} 
		?>

		<? if(count($login->messages)>0){
			for($i=0;$i<count($login->messages);$i++){
				echo '<div class="alert alert-success">'.$login->messages[$i].'</div>'; 
			}
		} 
		
		?>

		<div class="row user_log_in">
			<?
				if ($login->isUserLoggedIn() == false) {
					include('./view/login.php');
				}
			?>
		</div>

